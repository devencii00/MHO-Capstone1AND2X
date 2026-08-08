import React, { useEffect, useState, useCallback, useRef } from 'react';
import {
  View,
  Text,
  Platform,
  ActivityIndicator,
  Image,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  Animated,
} from 'react-native';

//Import Statements
import { useRouter, useFocusEffect } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import AsyncStorage from '@react-native-async-storage/async-storage';

import api from '../../lib/api';
import { useReverb } from '../../hooks/useReverb';
// FloatingChatButton intentionally NOT imported here — it already lives in
// app/(main)/(tabs)/_layout.tsx, which wraps this screen. Rendering it here too
// was causing it to appear twice on the dashboard tab.

//  Menu items shown in the "What would you like to do?" grid
interface MenuItem {
  id: number;
  name: string;
  description: string;
  icon: keyof typeof Ionicons.glyphMap;
  route: string;
}

const menuItems: MenuItem[] = [
  {
    id: 1,
    name: 'Appointments',
    description: 'Manage your appointments & history.',
    icon: 'calendar-outline',
    route: '/(main)/appointments',
  },
  {
    id: 2,
    name: 'Billing',
    description: 'View and manage your bills & payments.',
    icon: 'card-outline',
    route: '/(main)/billing',
  },
  {
    id: 3,
    name: 'Chat',
    description: 'Message your care team.',
    icon: 'chatbubble-ellipses-outline',
    route: '/(main)/chat',
  },
  {
    id: 4,
    name: 'Records',
    description: 'View your medical records.',
    icon: 'folder-outline',
    route: '/(main)/result',
  },
];

export default function DashboardScreen() {
  const router = useRouter();

  const isFocusedRef = useRef(true);

  const [patientName, setPatientName] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [greeting, setGreeting] = useState('');
  const [refreshing, setRefreshing] = useState(false);
  const [appointments, setAppointments] = useState<any[]>([]);
  const [appointmentsLoaded, setAppointmentsLoaded] = useState(false);

  const [cartCount, setCartCount] = useState(0);
  const bounceAnim = useRef(new Animated.Value(1)).current;

  //  REAL-TIME: Listen for approved appointments via Reverb
  useReverb('patient-notifications', 'appointment.approved', async (data: any) => {
    console.log(' Reverb: Appointment approved!', data);
    try {
      const patientId = await AsyncStorage.getItem('patientId') || 
                        await AsyncStorage.getItem('patient_id');
      
      if (data.patientId == patientId || data.patient_id == patientId) {
        console.log(' My appointment was approved! Refreshing...');
        fetchAppointments();
      }
    } catch (error) {
      console.error('Reverb callback error:', error);
    }
  });

  //  REAL-TIME: Listen for staff messages
  useReverb('patient-notifications', 'staff-message', async (data: any) => {
    console.log(' Reverb: Staff message received!', data);
    if (data.status === 'approved') {
      fetchAppointments();
    }
  });

  const bounceBadge = () => {
    Animated.sequence([
      Animated.spring(bounceAnim, { toValue: 1.4, useNativeDriver: true, speed: 30 }),
      Animated.spring(bounceAnim, { toValue: 1, useNativeDriver: true, speed: 30 }),
    ]).start();
  };

  const loadCartCount = async () => {
    try {
      const stored = await AsyncStorage.getItem('booking_cart');
      const parsed = stored ? JSON.parse(stored) : [];
      const count = Array.isArray(parsed) ? parsed.length : 0;
      if (count !== cartCount) bounceBadge();
      setCartCount(count);
    } catch {
      setCartCount(0);
    }
  };

  //  FIXED: Fetch fresh data from API FIRST, then fallback to AsyncStorage
  const getPatientData = async () => {
    try {
      //  FIRST: Try to get fresh data from API
      try {
        const response = await api.get('/patient/profile');
        if (response.data?.success && response.data?.data) {
          const data = response.data.data;
          const first = data.first_name || '';
          const last = data.last_name || '';
          const name = `${first} ${last}`.trim();
          
          if (name && name !== 'Not set Not set') {
            setPatientName(name);
            await AsyncStorage.setItem('patientFirstName', first);
            await AsyncStorage.setItem('patientLastName', last);
            await AsyncStorage.setItem('patientName', name);
            await AsyncStorage.setItem('patient', JSON.stringify(data));
            return;
          }
        }
      } catch (apiError) {
        console.log('API fetch failed, trying AsyncStorage...');
      }

      const patientStr = await AsyncStorage.getItem('patient');
      const firstName = await AsyncStorage.getItem('patientFirstName');
      const lastName = await AsyncStorage.getItem('patientLastName');
      const fullName = await AsyncStorage.getItem('patientName');

      if (patientStr) {
        try {
          const patientData = JSON.parse(patientStr);
          const first = patientData.first_name || firstName || '';
          const last = patientData.last_name || lastName || '';
          if (first || last) {
            const name = `${first} ${last}`.trim();
            if (name && name !== 'Not set Not set') {
              setPatientName(name);
              return;
            }
          }
        } catch {}
      }

      if (firstName && lastName) {
        const name = `${firstName} ${lastName}`.trim();
        if (name && name !== 'Not set Not set') {
          setPatientName(name);
          return;
        }
      }

      if (fullName && fullName !== 'Not set Not set') {
        setPatientName(fullName);
        return;
      }

      setPatientName('Patient');
    } catch {
      setPatientName('Patient');
    } finally {
      setIsLoading(false);
    }
  };

  const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) setGreeting('Good morning');
    else if (hour < 18) setGreeting('Good afternoon');
    else setGreeting('Good evening');
  };

  //Reusable Function
  const fetchAppointments = async () => {
    try {
      const response = await api.get('/patient/appointments');
      const result = response.data;
      if (result.success && result.data) {
        setAppointments(result.data);
      } else {
        setAppointments([]);
      }
    } catch {
      setAppointments([]);
    } finally {
      setAppointmentsLoaded(true);
    }
  };

  //  Use useFocusEffect from expo-router (replaces useIsFocused)
  useFocusEffect(
    useCallback(() => {
      console.log('🟢 Dashboard focused');
      isFocusedRef.current = true;
      
      getPatientData();
      getGreeting();
      fetchAppointments();
      loadCartCount();
      
      return () => {
        console.log('🔴 Dashboard unfocused');
        isFocusedRef.current = false;
      };
    }, [])
  );

  //  Fixed polling - uses ref instead of isFocused state
  useEffect(() => {
    const interval = setInterval(() => {
      if (isFocusedRef.current) {
        fetchAppointments();
        loadCartCount();
      }
    }, 5000);
    
    return () => {
      clearInterval(interval);
    };
  }, []); // Empty dependency array is fine since we use ref

  const onRefresh = async () => {
    setRefreshing(true);
    await getPatientData();
    getGreeting();
    await fetchAppointments();
    await loadCartCount();
    setRefreshing(false);
  };

  const pendingAppointments = appointments.filter((a) => a.status?.toLowerCase() === 'pending');
  const hasActiveAppointments = pendingAppointments.length > 0;

  if (isLoading) {
    return (
      <View className="items-center justify-center flex-1 bg-[#F0F5FB]">
        <ActivityIndicator size="large" color="#047857" />
        <Text className="mt-3 text-gray-500">Loading...</Text>
      </View>
    );
  }

  return (
    <>
      <ScrollView
        className="flex-1 bg-[#F7FAF9]"
        contentContainerStyle={{ paddingBottom: 48 }}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={['#047857']} />}
      >
        <View style={{ height: Platform.OS === 'ios' ? 60 : 40 }} />

        {/* GREETING + CART */}
        <View className="flex-row items-start justify-between px-5 pt-3 pb-1">
          <View className="flex-1">
            <Text className="text-base text-gray-500">{greeting},</Text>
            <Text className="text-[24px] font-extrabold text-gray-900 tracking-[-0.5px]">
              {patientName}
            </Text>
            <Text className="mt-0.5 text-sm text-gray-500">Welcome to Opol Medical Laboratory</Text>
          </View>

          <TouchableOpacity
              onPress={() => router.push('/(main)/cart')}
              style={{
                marginTop: 6, width: 44, height: 44, alignItems: 'center', justifyContent: 'center',
                borderRadius: 12, borderWidth: 1, borderColor: '#e5e7eb', backgroundColor: '#fff',
              }}
              activeOpacity={0.8}
            >
              <Ionicons 
                name="notifications-outline" 
                size={20} 
                color="#6b7280" 
              />
              {cartCount > 0 && (
                <Animated.View style={{
                  position: 'absolute', top: 2, right: 2, backgroundColor: '#ef4444',
                  borderRadius: 8, minWidth: 16, height: 16, alignItems: 'center',
                  justifyContent: 'center', paddingHorizontal: 3,
                  transform: [{ scale: bounceAnim }],
                }}>
                  <Text style={{ color: '#fff', fontSize: 9, fontWeight: '700' }}>
                    {cartCount > 9 ? '9+' : cartCount}
                  </Text>
                </Animated.View>
              )}
            </TouchableOpacity>
        </View>

        {/* HERO CARD */}
        <View className="mx-5 rounded-3xl overflow-hidden h-[230px] mt-4 mb-6 shadow-lg" style={{ elevation: 4 }}>
          <LinearGradient colors={['#065f46', '#0d9488']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0.3 }} className="flex-1">
            <Image
              source={{ uri: 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=500' }}
              className="absolute right-0 bottom-0 w-[200px] h-[230px] opacity-90"
              resizeMode="cover"
            />
            <View className="absolute top-0 bottom-0 left-0" style={{ width: '62%', backgroundColor: 'rgba(5,46,38,0.35)' }} />
            <View className="flex-1 p-5 justify-between max-w-[230px]">
              <View className="flex-row items-center self-start gap-1.5 bg-white/20 px-2.5 py-1 rounded-[20px]">
                <Ionicons name="flask-outline" size={13} color="#fff" />
                <Text className="text-white text-[11px] font-semibold tracking-[0.3px]">Medical Laboratory</Text>
              </View>
              <View>
                <Text className="text-[22px] font-extrabold text-white leading-[26px]">
                  Quality Care,{'\n'}Accurate Results
                </Text>
                <Text className="text-[#d1fae5] text-[12px] mt-2 mb-3 leading-[16px]">
                  Fast, accurate, and reliable diagnostic services for you and your family.
                </Text>
                <TouchableOpacity
                  className="flex-row items-center self-start gap-1.5 px-4 py-2.5 bg-white rounded-[22px]"
                  onPress={() => router.push('/book-appointment')}
                  activeOpacity={0.85}
                >
                  <Text className="text-emerald-800 font-bold text-[13px]">Book Appointment</Text>
                  <Ionicons name="arrow-forward" size={15} color="#065f46" />
                </TouchableOpacity>
              </View>
            </View>
          </LinearGradient>
        </View>

        {/* NO CURRENT APPOINTMENTS CARD */}
        {!appointmentsLoaded ? (
          <View style={{ alignItems: 'center', paddingVertical: 24 }}>
            <ActivityIndicator size="small" color="#047857" />
          </View>
        ) : (
          <View
            style={{
              flexDirection: 'row',
              alignItems: 'center',
              marginHorizontal: 20,
              marginBottom: 24,
              paddingVertical: 28,
              paddingHorizontal: 16,
              minHeight: 110,
              backgroundColor: '#fff',
              borderRadius: 20,
              shadowColor: '#000',
              shadowOpacity: 0.04,
              shadowRadius: 8,
              shadowOffset: { width: 0, height: 2 },
              elevation: 1,
            }}
          >
            <View
              style={{
                width: 70,
                height: 80,
                borderRadius: 14,
                backgroundColor: 'rgba(4,120,87,0.1)',
                alignItems: 'center',
                justifyContent: 'center',
                marginRight: 14,
              }}
            >
              <Ionicons name="calendar-outline" size={33} color="#047857" />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={{ fontSize: 15, fontWeight: '800', color: '#111827' }}>
                {hasActiveAppointments ? 'You Have Pending Appointments' : 'No Current Appointments'}
              </Text>
              <Text style={{ fontSize: 12, color: '#6b7280', marginTop: 2, lineHeight: 16 }}>
                {hasActiveAppointments
                  ? `You have ${pendingAppointments.length} pending appointment${pendingAppointments.length > 1 ? 's' : ''}.`
                  : 'You have no active appointments or queue entries.'}
              </Text>
            </View>
          </View>
        )}

        {/* WHAT WOULD YOU LIKE TO DO */}
        <View className="px-5 mb-3">
          <Text className="text-lg font-bold text-gray-900">What would you like to do?</Text>
        </View>

        <View className="flex-row flex-wrap justify-between px-5 mb-6">
          {menuItems.map((item) => (
            <TouchableOpacity
              key={item.id}
              className="bg-white rounded-2xl p-4 mb-3 w-[47.5%] shadow-sm"
              style={{ elevation: 1 }}
              activeOpacity={0.85}
              onPress={() => router.push(item.route as any)}
            >
              <View style={{ flexDirection: 'row', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 14 }}>
                <View
                  style={{
                    width: 40,
                    height: 40,
                    borderRadius: 12,
                    backgroundColor: '#f3f4f6',
                    alignItems: 'center',
                    justifyContent: 'center',
                  }}
                >
                  <Ionicons name={item.icon} size={27} color="#111827" />
                </View>
                <View
                  style={{
                    width: 26,
                    height: 26,
                    borderRadius: 13,
                    backgroundColor: '#047857',
                    alignItems: 'center',
                    justifyContent: 'center',
                  }}
                >
                  <Ionicons name="arrow-forward" size={15} color="#fff" />
                </View>
              </View>
              <Text className="text-[15px] font-bold text-gray-900 mb-0.5">{item.name}</Text>
              <Text className="text-[12px] text-gray-500 leading-[16px]" numberOfLines={2}>{item.description}</Text>
            </TouchableOpacity>
          ))}
        </View>

        <View style={{ height: 20 }} />
      </ScrollView>
    </>
  );
}