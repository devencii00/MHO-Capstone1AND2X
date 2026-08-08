import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';

const API_BASE_URL = 'http://10.155.219.180:8000/api';
const WS_HOST = '10.155.219.180';
const WS_PORT = 8080;
const APP_KEY = 'ykz3vcptchgggf2xol3y';

interface Notification {
  id: number;
  type: string;
  title: string;
  message: string;
  appointment_id: number;
  is_read: boolean;
  created_at: string;
}

export default function Notifications() {
  const router = useRouter();
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [authToken, setAuthToken] = useState<string | null>(null);

  const wsRef = useRef<WebSocket | null>(null);
  const pingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const activeRef = useRef(true);
  const authTokenRef = useRef<string | null>(null);

  useEffect(() => {
    authTokenRef.current = authToken;
  }, [authToken]);

  useEffect(() => {
    activeRef.current = true;
    loadAuthToken();

    return () => {
      activeRef.current = false;
      if (pingRef.current) clearInterval(pingRef.current);
      wsRef.current?.close();
    };
  }, []);

  const loadAuthToken = async () => {
    try {
      const token = await AsyncStorage.getItem('auth_token');
      const patientId = await AsyncStorage.getItem('patient_id');

      if (token && patientId) {
        setAuthToken(token);
        authTokenRef.current = token;
        await loadNotifications(token);
        connectWebSocket(patientId);
      } else {
        setIsLoading(false);
      }
    } catch (error) {
      console.error('Error loading token:', error);
      setIsLoading(false);
    }
  };

  const connectWebSocket = (patientId: string) => {
    const url = `ws://${WS_HOST}:${WS_PORT}/app/${APP_KEY}?protocol=7&client=js&version=8.0.0&flash=false`;
    const ws = new WebSocket(url);
    wsRef.current = ws;

    ws.onopen = () => {
      console.log('✅ Reverb WS connected');
      if (pingRef.current) clearInterval(pingRef.current);
      pingRef.current = setInterval(() => {
        if (ws.readyState === WebSocket.OPEN) {
          ws.send(JSON.stringify({ event: 'pusher:ping', data: {} }));
        }
      }, 30000);
    };

    ws.onmessage = (e) => {
      try {
        const msg = JSON.parse(e.data);

        if (msg.event === 'pusher:connection_established') {
          ws.send(
            JSON.stringify({
              event: 'pusher:subscribe',
              data: { channel: 'my-channel' },
            })
          );
          return;
        }

        if (msg.event === 'pusher_internal:subscription_succeeded') {
          console.log('✅ Subscribed to my-channel');
          return;
        }

        const eventName = (msg.event ?? '').replace(/^\./, '');

        if (msg.channel === 'my-channel' && eventName === 'appointment-notification') {
          const data = typeof msg.data === 'string' ? JSON.parse(msg.data) : msg.data;
          console.log(' Real-time notification received:', data);

          if (authTokenRef.current) loadNotifications(authTokenRef.current);

          Alert.alert(
            data.title || 'Appointment Update',
            data.message || 'Your appointment has been updated.',
            [{ text: 'OK' }]
          );
        }
      } catch (err) {
        console.warn('WS parse error:', err);
      }
    };

    ws.onerror = (err) => console.warn('Reverb WS error:', err);

    ws.onclose = (e) => {
      if (pingRef.current) clearInterval(pingRef.current);
      if (activeRef.current) {
        console.log('Reverb WS closed, reconnecting in 3s…', e.code);
        setTimeout(() => connectWebSocket(patientId), 3000);
      }
    };
  };

  const loadNotifications = async (token: string) => {
    try {
      const response = await fetch(`${API_BASE_URL}/patient/notifications`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
      });

      if (response.ok) {
        const result = await response.json();
        const data = result.data || result;
        setNotifications(Array.isArray(data) ? data : []);
        await AsyncStorage.setItem('appointment_notifications', JSON.stringify(data));
      } else if (response.status === 401) {
        await AsyncStorage.removeItem('auth_token');
        Alert.alert('Session Expired', 'Please login again', [
          { text: 'OK', onPress: () => router.replace('/login') },
        ]);
      } else {
        const saved = await AsyncStorage.getItem('appointment_notifications');
        if (saved) setNotifications(JSON.parse(saved));
      }
    } catch (error) {
      console.error('Error loading notifications:', error);
      const saved = await AsyncStorage.getItem('appointment_notifications');
      if (saved) setNotifications(JSON.parse(saved));
    } finally {
      setIsLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    if (authToken) await loadNotifications(authToken);
    setRefreshing(false);
  };

  const markAsRead = async (id: number) => {
    if (!authToken) return;
    try {
      const response = await fetch(`${API_BASE_URL}/patient/notifications/${id}/read`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${authToken}`,
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
      });
      if (response.ok) {
        setNotifications((prev) =>
          prev.map((n) => (n.id === id ? { ...n, is_read: true } : n))
        );
      }
    } catch (error) {
      console.error('Error marking as read:', error);
    }
  };

  const deleteNotification = async (id: number) => {
    if (!authToken) return;
    try {
      const response = await fetch(`${API_BASE_URL}/patient/notifications/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${authToken}`,
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
      });
      if (response.ok) {
        setNotifications((prev) => prev.filter((n) => n.id !== id));
      }
    } catch (error) {
      console.error('Error deleting notification:', error);
    }
  };

  const clearAll = () => {
    Alert.alert('Clear All', 'Remove all notifications?', [
      { text: 'Cancel', style: 'cancel' },
      {
        text: 'Clear',
        style: 'destructive',
        onPress: async () => {
          for (const n of notifications) await deleteNotification(n.id);
          setNotifications([]);
          await AsyncStorage.removeItem('appointment_notifications');
          Alert.alert('Success', 'All notifications cleared');
        },
      },
    ]);
  };

  const viewAppointmentDetails = (appointmentId: number) => {
    if (appointmentId) {
      router.push({
        pathname: '/booking-details',
        params: { id: appointmentId.toString() },
      });
    }
  };

  const renderItem = ({ item }: { item: Notification }) => (
    <TouchableOpacity
      className={`flex-row bg-white rounded-xl p-4 mb-3 shadow-sm relative ${
        !item.is_read ? 'bg-blue-50 border-l-4 border-l-blue-600' : ''
      }`}
      onPress={() => {
        markAsRead(item.id);
        viewAppointmentDetails(item.appointment_id);
      }}
      onLongPress={() => {
        Alert.alert('Delete', 'Delete this notification?', [
          { text: 'Cancel', style: 'cancel' },
          {
            text: 'Delete',
            style: 'destructive',
            onPress: () => deleteNotification(item.id),
          },
        ]);
      }}
    >
      <View className="mr-3">
        <Ionicons
          name={item.type === 'cancelled' ? 'close-circle' : 'alert-circle'}
          size={28}
          color={item.type === 'cancelled' ? '#DC2626' : '#F59E0B'}
        />
      </View>
      
      <View className="flex-1">
        {/* ✅ Title removed - message only */}
        <Text className="mb-2 text-sm text-gray-500">{item.message}</Text>
        <Text className="text-xs text-gray-400">
          {new Date(item.created_at).toLocaleString()}
        </Text>
      </View>
      
      {!item.is_read && (
        <View className="absolute w-2 h-2 bg-blue-600 rounded-full top-4 right-4" />
      )}
    </TouchableOpacity>
  );

  if (isLoading) {
    return (
      <View className="items-center justify-center flex-1 bg-gray-50">
        <ActivityIndicator size="large" color="#2563EB" />
        <Text className="mt-3 text-gray-500">Loading notifications...</Text>
      </View>
    );
  }

  return (
    <View className="flex-1 bg-gray-50">
      {/* Header */}
      <View className="flex-row items-center justify-between px-5 pt-[60px] pb-5 bg-white border-b border-gray-200">
        <TouchableOpacity onPress={() => router.back()} className="p-1">
          <Ionicons name="arrow-back" size={24} color="#1F2937" />
        </TouchableOpacity>
        <Text className="text-lg font-semibold text-gray-800">Notifications</Text>
        {notifications.length > 0 ? (
          <TouchableOpacity onPress={clearAll}>
            <Text className="text-sm font-medium text-red-500">Clear All</Text>
          </TouchableOpacity>
        ) : (
          <View className="w-16" />
        )}
      </View>

      {/* Content */}
      {notifications.length === 0 ? (
        <View className="items-center justify-center flex-1 px-10">
          <Ionicons name="notifications-off-outline" size={64} color="#D1D5DB" />
          <Text className="mt-4 text-lg font-semibold text-gray-600">
            No notifications yet
          </Text>
          <Text className="mt-2 text-sm text-center text-gray-400">
            Notifications from staff will appear here
          </Text>
        </View>
      ) : (
        <FlatList
          data={notifications}
          renderItem={renderItem}
          keyExtractor={(item) => item.id.toString()}
          contentContainerStyle={{ padding: 20 }}
          showsVerticalScrollIndicator={false}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
          }
        />
      )}
    </View>
  );
}