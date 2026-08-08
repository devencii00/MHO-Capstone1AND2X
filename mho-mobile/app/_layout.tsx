import "@/global.css";
import 'react-native-get-random-values';
import { Stack } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import { useEffect, useState, useRef } from 'react';
import { View, AppState, Platform } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import eventEmitter from '../services/eventEmitter';
import * as Notifications from 'expo-notifications';
import * as Device from 'expo-device';
import { ProfileProvider } from './context/ProfileContext';

const WS_HOST = '10.155.219.180';
const WS_PORT = 8080;
const APP_KEY = 'ykz3vcptchgggf2xol3y';
const API_BASE_URL = 'http://10.155.219.180:8000/api';

// IMMEDIATELY create notification channel - this must run at module level
// or very early in the app lifecycle for background notifications to work
if (Platform.OS === 'android') {
  // This runs as soon as the module is imported, before any components mount
  Notifications.setNotificationChannelAsync('default', {
    name: 'Default Notifications',
    importance: Notifications.AndroidImportance.MAX,
    vibrationPattern: [0, 250, 250, 250],
    lightColor: '#065f46',
    sound: 'default',
    bypassDnd: true,
    lockscreenVisibility: Notifications.AndroidNotificationVisibility.PUBLIC,
    enableVibrate: true,
  }).catch(error => console.warn('Failed to create notification channel:', error));
}

// Configure notification handler - this also needs to be at module level
Notifications.setNotificationHandler({
  handleNotification: async () => ({
    shouldShowAlert: true,
    shouldPlaySound: true,
    shouldSetBadge: true,
    shouldShowBanner: true,
    shouldShowList: true,
    priority: Notifications.AndroidNotificationPriority.HIGH,
  }),
});

export default function RootLayout() {
  const [connectionStatus, setConnectionStatus] = useState('Connecting...');
  const wsRef = useRef<WebSocket | null>(null);
  const pingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const activeRef = useRef(true);
  const appState = useRef(AppState.currentState);

  useEffect(() => {
    activeRef.current = true;
    
    // Register notification categories FIRST
    setupNotificationCategories();
    
    // Setup notification listeners
    setupNotificationListeners();
    
    // Then setup WebSocket
    setupWebSocket();

    const subscription = AppState.addEventListener('change', (nextAppState) => {
      const wasBackground = appState.current.match(/inactive|background/);
      if (wasBackground && nextAppState === 'active') {
        if (!wsRef.current || wsRef.current.readyState === WebSocket.CLOSED) {
          setupWebSocket();
        }
      }
      appState.current = nextAppState;
    });

    return () => {
      activeRef.current = false;
      subscription.remove();
      if (pingRef.current) clearInterval(pingRef.current);
      wsRef.current?.close();
    };
  }, []);

  // ✅ Setup notification categories with actions
  const setupNotificationCategories = async () => {
    try {
      await Notifications.setNotificationCategoryAsync('patient_called', [
        {
          identifier: 'yes_on_my_way',
          buttonTitle: 'Yes, on my way',
          options: { 
            opensAppToForeground: true,
          },
        },
        {
          identifier: 'no_not_yet',
          buttonTitle: 'No, not yet',
          options: { 
            opensAppToForeground: true,
          },
        },
      ]);
      console.log('Notification categories registered');
    } catch (error) {
      console.error('Failed to register notification categories:', error);
    }
  };

  // ✅ Setup notification listeners
  const setupNotificationListeners = () => {
    // Handle notification tap (when app is closed/background)
    const responseSubscription = Notifications.addNotificationResponseReceivedListener((response) => {
      const data = response.notification.request.content.data;
      console.log('Notification tapped:', data);
      
      // Handle the action identifier (Yes/No buttons)
      const actionId = response.actionIdentifier;
      console.log('Action pressed:', actionId);
      
      // You can emit events or navigate based on the action
      if (data?.type === 'patient_called') {
        eventEmitter.emit('patientCalledResponse', {
          queue_id: data.queue_id,
          patient_id: data.patient_id,
          queue_number: data.queue_number,
          action: actionId,
        });
      }
    });

    // Handle notification received while app is in foreground
    const receivedSubscription = Notifications.addNotificationReceivedListener((notification) => {
      console.log('Notification received (foreground):', notification.request.content);
      
      // Emit event for foreground notifications too
      const data = notification.request.content.data;
      if (data?.type === 'patient_called') {
        eventEmitter.emit('patientCalledWhileActive', data);
      }
    });

    return () => {
      responseSubscription.remove();
      receivedSubscription.remove();
    };
  };

  const setupWebSocket = async () => {
    try {
      const token = await AsyncStorage.getItem('@patient_access_token');
      const patientDataRaw = await AsyncStorage.getItem('@patient_data');

      if (!patientDataRaw || !token) {
        setConnectionStatus('Waiting for login');
        return;
      }

      let patientId: string | undefined;
      try {
        const patientData = JSON.parse(patientDataRaw);
        patientId = patientData?.id ?? patientData?.patient_id ?? patientData?.patientId;
      } catch (parseErr) {
        console.error('Failed to parse @patient_data:', parseErr);
      }

      if (!patientId) {
        console.warn('No patient id found inside @patient_data');
        setConnectionStatus('Waiting for login');
        return;
      }

      // ✅ Register push token when WebSocket connects
      await registerPushToken(token);

      if (wsRef.current) {
        wsRef.current.close();
        wsRef.current = null;
      }

      connectWS(patientId);
    } catch (error) {
      console.error('WebSocket setup error:', error);
      setConnectionStatus('Setup failed');
    }
  };

  const connectWS = (patientId: string) => {
    const url = `ws://${WS_HOST}:${WS_PORT}/app/${APP_KEY}?protocol=7&client=js&version=8.0.0&flash=false`;
    const ws = new WebSocket(url);
    wsRef.current = ws;

    const channelName = `patient-notifications.${patientId}`;

    ws.onopen = () => {
      console.log('Reverb WS connected');
      setConnectionStatus('Connected');

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
          ws.send(JSON.stringify({
            event: 'pusher:subscribe',
            data: { channel: channelName },
          }));
          return;
        }

        if (msg.event === 'pusher_internal:subscription_succeeded') {
          console.log('Subscribed to', channelName);
          setConnectionStatus('Listening');
          return;
        }

        const eventName = (msg.event ?? '').replace(/^\./, '');

        if (msg.channel === channelName && eventName === 'staff-message') {
          const data = typeof msg.data === 'string' ? JSON.parse(msg.data) : msg.data;
          console.log('New message received:', data);
          saveMessageToStorage(data);
          eventEmitter.emit('newStaffMessage', data);
        }

      } catch (err) {
        console.warn('WS parse error:', err);
      }
    };

    ws.onerror = (err) => {
      console.warn('Reverb WS error:', err);
      setConnectionStatus('Error');
    };

    ws.onclose = (e) => {
      if (pingRef.current) clearInterval(pingRef.current);
      setConnectionStatus('Disconnected');
      if (activeRef.current) {
        console.log('Reverb WS closed, reconnecting in 3s…', e.code);
        setTimeout(() => connectWS(patientId), 3000);
      }
    };
  };

  return (
    <ProfileProvider>{/* ✅ ADDED — wraps everything so useProfile() works anywhere below */}
      <Stack>
        <Stack.Screen name="index" options={{ headerShown: false }} />
        <Stack.Screen name="(auth)" options={{ headerShown: false }} />
        <Stack.Screen name="(main)" options={{ headerShown: false }} />
        <Stack.Screen name="queue-history" options={{ headerShown: false }} />
      </Stack>

      <StatusBar style="light" />

      {__DEV__ && (
        <View style={{
          position: 'absolute',
          bottom: 10,
          right: 10,
          backgroundColor: connectionStatus.includes('Connected') || connectionStatus === 'Listening'
            ? '#22c55e'
            : '#ef4444',
          width: 10,
          height: 10,
          borderRadius: 5,
          zIndex: 9999,
        }} />
      )}
    </ProfileProvider>
  );
}

// ✅ Updated push token registration - removed channel creation from here
async function registerPushToken(authToken: string) {
  try {
    if (!Device.isDevice) {
      console.log('Push notifications need a physical device');
      return;
    }

    // ✅ DON'T create channel here - already created at module level
    
    const { status: existingStatus } = await Notifications.getPermissionsAsync();
    let finalStatus = existingStatus;

    if (existingStatus !== 'granted') {
      const { status } = await Notifications.requestPermissionsAsync();
      finalStatus = status;
    }

    if (finalStatus !== 'granted') {
      console.log('Push notification permission denied');
      return;
    }

    let tokenData;
    try {
      tokenData = await Notifications.getExpoPushTokenAsync({
        projectId: 'c0cc4324-dc42-4f86-803f-afe1447bbc80',
      });
      console.log('✅ Got Expo push token:', tokenData.data);
    } catch (tokenError) {
      console.log('Could not fetch Expo push token:', (tokenError as Error)?.message);
      return;
    }

    const response = await fetch(`${API_BASE_URL}/patient/push-token`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${authToken}`,
      },
      body: JSON.stringify({ expo_push_token: tokenData.data }),
    });

    if (response.ok) {
      console.log('✅ Expo push token registered on server:', tokenData.data);
    } else {
      console.warn('Failed to register push token on server:', response.status);
      const errorBody = await response.text();
      console.warn('Server response:', errorBody);
    }
  } catch (error) {
    console.log('Push token registration error:', (error as Error)?.message);
  }
}

// Unregister push token on logout
export async function unregisterPushToken(authToken: string) {
  try {
    if (!Device.isDevice) return;

    let tokenData;
    try {
      tokenData = await Notifications.getExpoPushTokenAsync({
        projectId: 'c0cc4324-dc42-4f86-803f-afe1447bbc80',
      });
    } catch {
      return;
    }

    const response = await fetch(`${API_BASE_URL}/patient/push-token/unregister`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${authToken}`,
      },
      body: JSON.stringify({ expo_push_token: tokenData.data }),
    });

    if (response.ok) {
      console.log('Push token unregistered on logout');
    } else {
      console.warn('Failed to unregister push token:', response.status);
    }
  } catch (error) {
    console.log('Push token unregister skipped:', (error as Error)?.message);
  }
}

async function saveMessageToStorage(data: any) {
  try {
    const existing = await AsyncStorage.getItem('staff_messages');
    let messages = existing ? JSON.parse(existing) : [];

    messages.unshift({
      id: Date.now(),
      title: data.title || 'OPOL MEDLAB',
      message: data.message,
      timestamp: data.timestamp || new Date().toISOString(),
      read: false,
    });

    if (messages.length > 50) messages = messages.slice(0, 50);

    await AsyncStorage.setItem('staff_messages', JSON.stringify(messages));
  } catch (error) {
    console.error('Error saving message:', error);
  }
}