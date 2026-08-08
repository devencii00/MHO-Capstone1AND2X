import React, { useState, useEffect } from 'react';
import { 
  View, Text, ScrollView, TouchableOpacity, StatusBar, ActivityIndicator, 
  RefreshControl, Modal, Platform 
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Calendar } from 'react-native-calendars';
import api from './lib/api';
import AsyncStorage from '@react-native-async-storage/async-storage';

type FilterType = 'all' | 'completed' | 'cancelled';

interface HistoryItem {
  id: string;
  queueNumber: string;
  status: 'completed' | 'cancelled';
  date: string;
  rawDate: string; // ✅ Raw date for filtering
  durationMinutes: number;
}

export default function QueueHistoryScreen() {
  const router = useRouter();
  const [filter, setFilter] = useState<FilterType>('all');
  const [history, setHistory] = useState<HistoryItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [refreshing, setRefreshing] = useState(false);
  
  // ✅ Date filter states
  const [selectedFilterDate, setSelectedFilterDate] = useState<string | null>(null);
  const [showCalendar, setShowCalendar] = useState(false);

  // ── FETCH QUEUE HISTORY ──
  const fetchQueueHistory = async () => {
    try {
      setError(null);

      const token = await AsyncStorage.getItem('token');
      if (!token) {
        setError('Please login again');
        return;
      }

      const response = await api.get('/patient/queue/history');

      if (response.data.success && response.data.data) {
        const formattedData: HistoryItem[] = response.data.data.map((item: any) => {
          const durationMinutes = item.duration_minutes ||
            (item.served_at && item.created_at
              ? Math.round((new Date(item.served_at).getTime() - new Date(item.created_at).getTime()) / 60000)
              : 0);

          const dateObj = new Date(item.served_at || item.created_at || item.updated_at);
          const formattedDate = dateObj.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
          }) + ' • ' + dateObj.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
          });

          // ✅ Store raw date (YYYY-MM-DD) for filtering
          const rawDate = dateObj.toISOString().split('T')[0];

          return {
            id: item.id?.toString() || item.original_queue_id?.toString(),
            queueNumber: item.queue_number || '---',
            status: item.status === 'served' ? 'completed' : item.status === 'cancelled' ? 'cancelled' : 'completed',
            date: formattedDate,
            rawDate: rawDate,
            durationMinutes: durationMinutes,
          };
        });

        setHistory(formattedData);
      } else {
        setHistory([]);
      }
    } catch (err: any) {
      console.error('Queue history fetch error:', err);
      const errorMessage = err?.response?.data?.message || err?.message || 'Failed to fetch queue history';
      setError(errorMessage);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  // ── LOAD DATA ON MOUNT ──
  useEffect(() => {
    fetchQueueHistory();
  }, []);

  // ── PULL TO REFRESH ──
  const onRefresh = () => {
    setRefreshing(true);
    fetchQueueHistory();
  };

  // ✅ FILTER LOGIC (Status + Date)
  const filteredHistory = history.filter((item) => {
    // Status filter
    if (filter === 'all') {
      // Show all statuses
    } else if (item.status !== filter) {
      return false;
    }
    
    // ✅ Date filter
    if (selectedFilterDate && item.rawDate !== selectedFilterDate) {
      return false;
    }
    
    return true;
  });

  const filters: { key: FilterType; label: string }[] = [
    { key: 'all', label: 'All' },
    { key: 'completed', label: 'Completed' },
    { key: 'cancelled', label: 'Cancelled' },
  ];

  // ✅ Format date for display
  const formatCalendarDate = (dateString: string | null): string => {
    if (!dateString) return 'Filter by Date';
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  };

  const today = new Date().toISOString().split('T')[0];

  return (
    <View className="flex-1 bg-[#F0F5F1]">
      <StatusBar barStyle="light-content" backgroundColor="#065f46" />

      {/* ── HEADER ── */}
      <View className="bg-[#065f46] pt-[60px] pb-5 px-5 flex-row items-center justify-between">
        <TouchableOpacity
          onPress={() => router.back()}
          className="items-center justify-center w-10 h-10 rounded-full bg-white/15"
        >
          <Ionicons name="chevron-back" size={22} color="#fff" />
        </TouchableOpacity>
        <Text className="text-[18px] font-bold text-white">Queue History</Text>
        
        {/* ✅ DATE FILTER BUTTON - Right side of header */}
        <TouchableOpacity
          onPress={() => setShowCalendar(true)}
          className={`items-center justify-center w-10 h-10 rounded-full ${
            selectedFilterDate ? 'bg-white/30' : 'bg-white/15'
          }`}
        >
          <Ionicons 
            name="calendar-outline" 
            size={20} 
            color="#fff" 
          />
        </TouchableOpacity>
      </View>

      {/* ✅ Selected date indicator */}
      {selectedFilterDate && (
        <View className="flex-row items-center justify-between px-5 py-2 bg-emerald-50">
          <View className="flex-row items-center gap-2">
            <Ionicons name="calendar" size={14} color="#065f46" />
            <Text className="text-[12px] font-semibold text-emerald-800">
              {formatCalendarDate(selectedFilterDate)}
            </Text>
          </View>
          <TouchableOpacity
            onPress={() => setSelectedFilterDate(null)}
            className="p-1"
          >
            <Ionicons name="close-circle" size={18} color="#065f46" />
          </TouchableOpacity>
        </View>
      )}

      {/* ── FILTER TABS ── */}
      <View className="flex-row gap-2 px-5 pt-5 pb-3">
        {filters.map((f) => {
          const isActive = filter === f.key;
          return (
            <TouchableOpacity
              key={f.key}
              onPress={() => setFilter(f.key)}
              className={`flex-1 items-center py-2.5 rounded-2xl ${isActive ? 'bg-emerald-700' : 'bg-white'}`}
              style={!isActive ? { elevation: 1 } : undefined}
            >
              <Text className={`text-[13px] font-semibold ${isActive ? 'text-white' : 'text-gray-600'}`}>
                {f.label}
              </Text>
            </TouchableOpacity>
          );
        })}
      </View>

      {/* ── LOADING STATE ── */}
      {loading && !refreshing && (
        <View className="items-center justify-center flex-1">
          <ActivityIndicator size="large" color="#065f46" />
          <Text className="mt-3 text-sm text-gray-500">Loading your queue history...</Text>
        </View>
      )}

      {/* ── ERROR STATE ── */}
      {error && !loading && (
        <View className="items-center justify-center flex-1 px-5">
          <View className="items-center">
            <Ionicons name="alert-circle" size={48} color="#ef4444" />
            <Text className="mt-3 text-base font-semibold text-center text-gray-800">
              Something went wrong
            </Text>
            <Text className="mt-1 text-sm text-center text-gray-500">{error}</Text>
            <TouchableOpacity
              onPress={() => {
                setLoading(true);
                fetchQueueHistory();
              }}
              className="px-6 py-3 mt-5 bg-emerald-700 rounded-2xl"
            >
              <Text className="font-semibold text-white">Try Again</Text>
            </TouchableOpacity>
          </View>
        </View>
      )}

      {/* ── HISTORY LIST ── */}
      {!loading && !error && (
        <ScrollView
          className="flex-1 px-5"
          showsVerticalScrollIndicator={false}
          contentContainerStyle={{ paddingBottom: 24 }}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#065f46" />
          }
        >
          {/* ✅ Results count */}
          <Text className="mb-3 text-[11px] text-gray-400">
            {filteredHistory.length} result{filteredHistory.length !== 1 ? 's' : ''}
            {selectedFilterDate ? ` on ${formatCalendarDate(selectedFilterDate)}` : ''}
          </Text>

          {filteredHistory.length > 0 ? (
            filteredHistory.map((item) => {
              const isCompleted = item.status === 'completed';
              return (
                <TouchableOpacity
                  key={item.id}
                  className="flex-row items-center justify-between p-4 mb-3 bg-white rounded-2xl"
                  style={{ elevation: 1 }}
                  activeOpacity={0.8}
                >
                  <View className="flex-1">
                    <View className="flex-row items-center gap-1.5 mb-1.5">
                      <Ionicons
                        name={isCompleted ? 'checkmark-circle' : 'close-circle'}
                        size={15}
                        color={isCompleted ? '#16a34a' : '#ef4444'}
                      />
                      <Text
                        className="text-[12px] font-bold"
                        style={{ color: isCompleted ? '#16a34a' : '#ef4444' }}
                      >
                        {isCompleted ? 'Completed' : 'Cancelled'}
                      </Text>
                      <Text className="text-[11px] text-gray-400 ml-1">{item.date}</Text>
                    </View>

                    <Text className="text-[22px] font-black text-emerald-900 leading-tight mb-1">
                      {item.queueNumber}
                    </Text>

                    <View className="flex-row items-center gap-1">
                      <Ionicons name="time-outline" size={12} color="#9ca3af" />
                      <Text className="text-[11px] text-gray-400">{item.durationMinutes} min</Text>
                    </View>
                  </View>

                  <Ionicons name="chevron-forward" size={18} color="#cbd5e1" />
                </TouchableOpacity>
              );
            })
          ) : (
            <View className="items-center justify-center py-20">
              <View className="items-center justify-center w-20 h-20 mb-4 bg-gray-100 rounded-full">
                <Ionicons name="time-outline" size={36} color="#9ca3af" />
              </View>
              <Text className="mb-1 text-base font-semibold text-gray-500">No history found</Text>
              <Text className="px-8 text-sm text-center text-gray-400">
                {selectedFilterDate 
                  ? `No records on ${formatCalendarDate(selectedFilterDate)}`
                  : filter === 'all' 
                  ? 'Your completed and cancelled queues will appear here'
                  : filter === 'completed'
                  ? 'No completed queues yet'
                  : 'No cancelled queues'}
              </Text>
            </View>
          )}
        </ScrollView>
      )}

      {/* ✅ CALENDAR MODAL */}
      <Modal
        visible={showCalendar}
        transparent
        animationType="slide"
        onRequestClose={() => setShowCalendar(false)}
      >
        <View className="justify-end flex-1 bg-black/40">
          <View className="p-5 bg-white rounded-t-3xl">
            {/* Modal Header */}
            <View className="flex-row items-center justify-between mb-4">
              <Text className="text-lg font-bold text-gray-800">Filter by Date</Text>
              <TouchableOpacity
                onPress={() => setShowCalendar(false)}
                className="items-center justify-center w-8 h-8 bg-gray-100 rounded-full"
              >
                <Ionicons name="close" size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            <Calendar
              current={today}
              maxDate={today}
              onDayPress={(day: any) => {
                setSelectedFilterDate(day.dateString);
                setShowCalendar(false);
              }}
              markedDates={{
                [today]: { selected: true, selectedColor: '#065f46' },
                ...(selectedFilterDate && {
                  [selectedFilterDate]: { selected: true, selectedColor: '#0FA98A' }
                }),
                // ✅ Mark dates that have history
                ...history.reduce((acc: any, item) => {
                  acc[item.rawDate] = { marked: true, dotColor: '#065f46' };
                  return acc;
                }, {}),
              }}
              theme={{
                backgroundColor: '#ffffff',
                calendarBackground: '#ffffff',
                textSectionTitleColor: '#64748B',
                selectedDayBackgroundColor: '#065f46',
                selectedDayTextColor: '#ffffff',
                todayTextColor: '#065f46',
                dayTextColor: '#0F172A',
                textDisabledColor: '#CBD5E1',
                arrowColor: '#065f46',
                monthTextColor: '#0F172A',
                textDayFontWeight: '600',
                textMonthFontWeight: '800',
                textDayHeaderFontWeight: '700',
                textDayFontSize: 14,
                textMonthFontSize: 18,
                textDayHeaderFontSize: 12,
              }}
            />

            {/* Buttons */}
            <View className="flex-row gap-3 mt-4">
              <TouchableOpacity
                onPress={() => {
                  setSelectedFilterDate(null);
                  setShowCalendar(false);
                }}
                className="flex-1 py-3 bg-gray-100 rounded-2xl"
              >
                <Text className="text-sm font-semibold text-center text-gray-600">Clear Filter</Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => setShowCalendar(false)}
                className="flex-1 py-3 bg-emerald-700 rounded-2xl"
              >
                <Text className="text-sm font-semibold text-center text-white">Done</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}