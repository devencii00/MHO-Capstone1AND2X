// useState() -> stores data that changes.
// useEffect() -> runs code when the page loads.
// useRef() -> stores values without re-rendering.
// useCallback() -> prevents functions from recreating every render.
import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
  View,
  Text,
  ScrollView,
  RefreshControl,
  ActivityIndicator,
  Vibration,
  TouchableOpacity,
  Modal,
  FlatList,
  StatusBar,
  Image,
  Alert,
  InteractionManager,
  Platform,
} from 'react-native';
import * as Speech from 'expo-speech';
import AsyncStorage from '@react-native-async-storage/async-storage';
import Svg, { Circle, Path } from 'react-native-svg';
import { Ionicons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import api, { API_HOST } from '../../lib/api';

// Para sa real-time WebSocket connection gamit ang Laravel Echo ug Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js/react-native';


const PATIENT_TOKEN_KEY = '@patient_access_token';

// Counter for unique keys
let keyCounter = 0;
const getUniqueKey = (prefix: string) => `${prefix}-${++keyCounter}-${Date.now()}`;

// Safe Alert wrapper - defers to next tick so it never fires mid-render
const safeAlert = (title: string, message?: string, buttons?: any[]) => {
  InteractionManager.runAfterInteractions(() => {
    Alert.alert(title, message, buttons);
  });
};

// Shadow styles object for reuse
const shadowStyles = Platform.select({
  ios: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
  },
  android: {
    elevation: 4,
  },
  default: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
  },
});

const shadowLg = Platform.select({
  ios: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 12,
  },
  android: {
    elevation: 8,
  },
  default: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 12,
  },
});

const shadow2xl = Platform.select({
  ios: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.2,
    shadowRadius: 24,
  },
  android: {
    elevation: 16,
  },
  default: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.2,
    shadowRadius: 24,
  },
});

export default function QueueScreen() {
  const router = useRouter();

  // Track when component is ready for navigation
  const isComponentReady = useRef<boolean>(false);

  const [queueData, setQueueData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [isCalled, setIsCalled] = useState(false);
  const [countdownSeconds, setCountdownSeconds] = useState<number>(0);
  const [countdownText, setCountdownText] = useState<string>('');
  const [currentQueueNumber, setCurrentQueueNumber] = useState<string>('---');
  const [selectedServices, setSelectedServices] = useState<any[]>([]);
  const [selectedQueueNumber, setSelectedQueueNumber] = useState<string | null>(null);
  const [showServicesModal, setShowServicesModal] = useState(false);
  const [refreshKey, setRefreshKey] = useState(0);
  const [servicesLoading, setServicesLoading] = useState(false);
  const [showCalledModal, setShowCalledModal] = useState(false);
  const [calledQueueNumber, setCalledQueueNumber] = useState<string>('');
  const [showCompletedModal, setShowCompletedModal] = useState(false);
  const [isResponding, setIsResponding] = useState(false);

  // FIX: bag-ong state para sa "offline" banner. Gina-set true kung
  // sunod-sunod nga mag-fail ang fetchQueueStatus (network error), aron
  // naay clear visual feedback sa user nga naa'y connection issue imbes
  // nga permi ra silent fail nga walay ikasulti sa user.
  const [isOffline, setIsOffline] = useState(false);

  const previousStatus      = useRef<string | null>(null);
  const previousPosition    = useRef<number | null>(null);
  const intervalId          = useRef<any>(null);
  const countdownIntervalId = useRef<any>(null);
  const speechTimeouts      = useRef<any[]>([]);
  const speechCancelled     = useRef<boolean>(false);
  const initialCountdownSet = useRef<boolean>(false);
  const vibrationInterval   = useRef<any>(null);
  const isVibrating         = useRef<boolean>(false);
  const calledHandled       = useRef<boolean>(false);
  const completedHandled    = useRef<boolean>(false);
  const respondingRef       = useRef<boolean>(false);
  const hadActiveQueue      = useRef<boolean>(false);
  const isMounted           = useRef<boolean>(true);

  
  const consecutiveFailures = useRef<number>(0);

  // Para sa WebSocket Echo instance ug patient ID tracking
  const echoRef = useRef<any>(null);
  const patientIdRef = useRef<number | null>(null);

  // Pag-setup sa Laravel Echo WebSocket gamit ang Reverb para sa real-time queue updates
  const setupEcho = useCallback(async (patientId: number) => {
    // Kung connected na para sa same patient, ayaw na pag-reconnect
    if (echoRef.current && patientIdRef.current === patientId) {
      return;
    }

    const token = await AsyncStorage.getItem(PATIENT_TOKEN_KEY);
    if (!token) {
      console.warn('setupEcho: walay token nga nakit-an, gi-skip ang WebSocket setup');
      return;
    }

    // Disconnect sa daan nga connection kung naa
    if (echoRef.current) {
      echoRef.current.disconnect();
      echoRef.current = null;
    }

    try {
      const reverbPort = 8080;

      // Himoa ang bag-ong Echo instance gamit ang Reverb
      echoRef.current = new Echo({
        broadcaster: 'reverb',
        key: process.env.EXPO_PUBLIC_REVERB_APP_KEY || 'ykz3vcptchgggf2xol3y',
        wsHost: process.env.EXPO_PUBLIC_REVERB_HOST || API_HOST,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        authorizer: (channel: any) => {
          return {
            authorize: (socketId: string, callback: Function) => {
              api.post('/broadcasting/auth', {
                socket_id: socketId,
                channel_name: channel.name,
              }, {
                headers: {
                  Authorization: `Bearer ${token}`,
                },
              })
              .then((response) => {
                callback(null, response.data);
              })
              .catch((error) => {
                callback(error);
              });
            },
          };
        },
      });

      patientIdRef.current = patientId;

      // Paminaw sa patient-specific channel kung tawagon na ang pasyente
      echoRef.current
        .channel(`patient.${patientId}`)
        .listen('.patient.called', (data: any) => {
          if (!isMounted.current) return;

          // Kung processing pa ang status, pasabot gitawag pa ang pasyente
          if (data.status === 'processing' && data.patient_id === patientId) {
            handlePatientCalled(data.queue_number);
          }
          // Kung in_progress na, pasabot ni-confirm na ang pasyente
          else if (data.status === 'in_progress' && data.patient_id === patientId) {
            setQueueData((prev: any) => prev ? { ...prev, status: 'in_progress' } : prev);
            previousStatus.current = 'in_progress';
            stopAllNotifications();
            setIsCalled(false);
            setShowCalledModal(false);
            calledHandled.current = false;
          }
          // Kung completed na, humana ang queue
          else if (data.status === 'completed' && data.patient_id === patientId) {
            handleQueueCompleted();
          }
        });

      // Paminaw sab sa general notifications channel isip backup
      echoRef.current
        .channel('patient-notifications')
        .listen('.patient.called', (data: any) => {
          if (data.patient_id === patientId && data.status === 'processing') {
            handlePatientCalled(data.queue_number);
          }
        });

      console.log('Connected sa Reverb WebSocket para sa patient:', patientId);

    } catch (error) {
      console.error('Sayop sa Reverb WebSocket connection:', error);
    }
  }, []);

  // Pag-disconnect sa WebSocket kung dili na kinahanglan
  const disconnectEcho = useCallback(() => {
    if (echoRef.current) {
      echoRef.current.disconnect();
      echoRef.current = null;
      patientIdRef.current = null;
    }
  }, []);

  useEffect(() => {
    isMounted.current = true;

    // Delay initialization to let navigation context fully load
    const initTimer = setTimeout(() => {
      isComponentReady.current = true;
      initializeApp();
    }, 500);

    return () => {
      isMounted.current = false;
      clearTimeout(initTimer);
      cleanupAll();
      disconnectEcho(); // Disconnect WebSocket kung mugawas sa screen
    };
  }, []);

  const cleanupAll = () => {
    if (intervalId.current) clearInterval(intervalId.current);
    if (countdownIntervalId.current) clearInterval(countdownIntervalId.current);
    stopVibration();
    stopSpeech();
  };

  const stopVibration = () => {
    Vibration.cancel();
    if (vibrationInterval.current) { clearInterval(vibrationInterval.current); vibrationInterval.current = null; }
    isVibrating.current = false;
  };

  const stopSpeech = () => {
    speechCancelled.current = true;
    Speech.stop();
    speechTimeouts.current.forEach(t => clearTimeout(t));
    speechTimeouts.current = [];
  };

  const stopAllNotifications = () => {
    stopVibration();
    stopSpeech();
    if (isMounted.current) setIsCalled(false);
  };

  useEffect(() => {
    if (countdownSeconds > 0) {
      if (countdownIntervalId.current) clearInterval(countdownIntervalId.current);
      countdownIntervalId.current = setInterval(() => {
        setCountdownSeconds(prev => {
          if (prev <= 1) {
            clearInterval(countdownIntervalId.current);
            setTimeout(() => fetchQueueStatus(), 0);
            return 0;
          }
          return prev - 1;
        });
      }, 1000);
    }
    return () => { if (countdownIntervalId.current) clearInterval(countdownIntervalId.current); };
  }, [countdownSeconds]);

  useEffect(() => {
    if (countdownSeconds > 0) {
      const minutes = Math.floor(countdownSeconds / 60);
      const seconds = countdownSeconds % 60;
      setCountdownText(minutes > 0
        ? `${minutes} min${minutes > 1 ? 's' : ''} ${seconds} sec${seconds !== 1 ? 's' : ''}`
        : `${seconds} second${seconds !== 1 ? 's' : ''}`
      );
    } else {
      setCountdownText('');
    }
  }, [countdownSeconds]);

  const initializeApp = async () => {
    await fetchQueueStatus();
    // Kuhaa ang polling interval, WebSocket na ang bahala sa real-time updates
    if (intervalId.current) clearInterval(intervalId.current);
    intervalId.current = setInterval(() => { fetchQueueStatus(); }, 3000);
  };

  const speakMultipleTimes = (message: string, times: number, interval: number = 2500) => {
    stopSpeech();
    speechCancelled.current = false;

    let count = 0;
    const speakNext = () => {
      if (speechCancelled.current || count >= times || !isMounted.current) return;
      count++;
      Speech.speak(message, {
        language: 'en-US',
        pitch: 1.0,
        rate: 0.9,
        volume: 1.0,
        onDone: () => {
          if (speechCancelled.current || count >= times || !isMounted.current) return;
          const t = setTimeout(speakNext, interval);
          speechTimeouts.current.push(t);
        },
        onStopped: () => {
          speechCancelled.current = true;
        },
        onError: () => {
          speechCancelled.current = true;
        },
      });
    };
    speakNext();
  };

  const startVibration = () => {
    stopVibration();
    isVibrating.current = true;
    vibrationInterval.current = setInterval(() => { if (isVibrating.current) Vibration.vibrate(500); }, 1000);
  };

  const handleQueueCompleted = () => {
    if (completedHandled.current) return;
    completedHandled.current = true;
    stopAllNotifications();
    if (isMounted.current) setShowCompletedModal(true);
  };

  const handlePatientCalled = (queueNumber: string) => {
    if (calledHandled.current) return;
    if (respondingRef.current) return;
    calledHandled.current = true;
    respondingRef.current = false;
    if (!isMounted.current) return;
    setIsCalled(true);
    setCalledQueueNumber(queueNumber);
    setShowCalledModal(true);
    startVibration();
    speakMultipleTimes(`Queue number ${queueNumber}, please proceed to the clinic now.`, 3, 1000);
  };

  const handleCalledResponse = async (proceed: boolean) => {
    if (respondingRef.current || isResponding) return;
    respondingRef.current = true;
    setIsResponding(true);
    setShowCalledModal(false);
    stopAllNotifications();

    try {
      const response = await api.post('/patient/queue/respond', {
        response: proceed ? 'on_my_way' : 'not_yet',
      }, { timeout: 8000 });

      if (response.data.success) {
        if (proceed) {
          setQueueData((prev: any) => prev ? { ...prev, status: 'in_progress' } : prev);
          previousStatus.current = 'in_progress';
          setIsCalled(false);
          safeAlert('On the way!', 'Your consultation is now being served.');
        } else {
          setQueueData((prev: any) => prev ? { ...prev, status: 'waiting' } : prev);
          previousStatus.current = 'waiting';
          calledHandled.current = false;
          setIsCalled(false);
          setCountdownSeconds((queueData?.position || 1) * 15 * 60);
          safeAlert('Noted!', 'You are back in the waiting queue. You will be called again.');
        }

        setTimeout(() => {
          fetchQueueStatus();
          respondingRef.current = false;
          setIsResponding(false);
        }, 1500);
      } else {
        respondingRef.current = false;
        setIsResponding(false);
        safeAlert(
          'Could Not Save Response',
          response.data.message || 'Please check your latest queue status below.'
        );
        fetchQueueStatus();
      }
    } catch (error: any) {
      // Detalyado nga log aron klaro dayon kung timeout, walay response
      // (network/offline), o server-side error (401/404/409/500 etc.)
      console.error(
        'Queue respond error:',
        error?.response?.status,
        error?.response?.data || error?.message || error
      );
      respondingRef.current = false;
      setIsResponding(false);

      if (error?.code === 'ECONNABORTED' || !error?.response) {
        safeAlert(
          'Connection Issue',
          "We couldn't confirm your response — your connection may be slow or offline. We'll refresh your queue status now; please try again if it's still showing the call."
        );
      } else if (error.response.status === 404 || error.response.status === 409) {
        safeAlert(
          'Already Updated',
          'This call is no longer active on the clinic\'s side. Refreshing your queue status now.'
        );
      } else if (error.response.status === 401) {
        safeAlert(
          'Session Expired',
          'Please log in again to continue.'
        );
      } else {
        safeAlert(
          'Something Went Wrong',
          'We couldn\'t send your response. Please try again in a moment.'
        );
      }

      fetchQueueStatus();
    }
  };

  const fetchQueueStatus = async () => {
    try {
      // FIX: gigamit na ang husto nga storage key (PATIENT_TOKEN_KEY /
      // '@patient_access_token'), parehas sa gigamit sa _layout.tsx pag-save
      // sa token pagka-login. Sa daan nga code, 'token' ang gi-check dinhi
      // pero wala gyud'y na-save nga value gamit niana nga key bisan asa
      // sa app, mao nga kanunay ni-uli og null ang AsyncStorage.getItem('token')
      // ug ang function mo-return dayon (setLoading(false); return;) nga
      // morag "nag-timeout/network error" ang tan-awon pero session/key
      // mismatch diay ang tinuod nga hinungdan.
      const token = await AsyncStorage.getItem(PATIENT_TOKEN_KEY);
      if (!token) {
        console.warn('fetchQueueStatus: walay token nga nakit-an sa storage');
        if (isMounted.current) setLoading(false);
        return;
      }

      const response = await api.get('/patient/queue/status');
      const result = response.data;

      if (!isMounted.current) return;

      if (result.success && result.data) {
        // FIX: successful ang request — i-reset ang failure counter ug
        // i-clear ang offline banner kung display pa siya.
        consecutiveFailures.current = 0;
        if (isOffline) setIsOffline(false);

        const newData = result.data;

        // Kung naay patient_id, i-setup ang WebSocket para sa real-time updates
        if (newData.patient_id) {
          setupEcho(newData.patient_id);
        }

        if (newData.queue_number) {
          setCurrentQueueNumber(newData.queue_number.toString());
          hadActiveQueue.current = true;
        }

        const isBeingCalled = newData.status === 'processing' &&
                             newData.call_response === 'no_response';

        if (isBeingCalled && !calledHandled.current && !respondingRef.current) {
          handlePatientCalled(newData.queue_number);
        }

        if (newData.status === 'in_progress' && respondingRef.current) {
          stopAllNotifications();
          setIsCalled(false);
          setShowCalledModal(false);
          respondingRef.current = false;
          setIsResponding(false);
          calledHandled.current = false;
        }

        if (newData.status === 'waiting' && respondingRef.current) {
          stopAllNotifications();
          setIsCalled(false);
          setShowCalledModal(false);
          respondingRef.current = false;
          setIsResponding(false);
          calledHandled.current = false;
        }

        if (newData.status === 'completed' && !completedHandled.current) {
          handleQueueCompleted();
        }

        if (newData.status === 'waiting') {
          if (!initialCountdownSet.current || previousPosition.current !== newData.position) {
            setCountdownSeconds((newData.position || 1) * 15 * 60);
            initialCountdownSet.current = true;
          }
        } else if (newData.status === 'processing') {
          setCountdownSeconds(0);
          setCountdownText('Waiting for your response...');
          initialCountdownSet.current = false;
        } else if (newData.status === 'in_progress') {
          setCountdownSeconds(0);
          setCountdownText('');
          initialCountdownSet.current = false;
        }

        previousStatus.current = newData.status;
        previousPosition.current = newData.position;
        setQueueData(newData);

        if (newData.status !== 'in_progress' && newData.status !== 'processing') {
          if (isVibrating.current) {
            stopAllNotifications();
          }
        }

        if (newData.status === 'waiting') {
          calledHandled.current = false;
          respondingRef.current = false;
          setIsResponding(false);
          completedHandled.current = false;
        }
      } else {
        // FIX: success man gihapon ni siya nga response (walay network
        // error), so i-reset pod ang failure counter/offline banner dinhi.
        consecutiveFailures.current = 0;
        if (isOffline) setIsOffline(false);

        // Kung walay active queue, i-disconnect ang WebSocket
        disconnectEcho();

        if (hadActiveQueue.current && !completedHandled.current) {
          handleQueueCompleted();
        }

        hadActiveQueue.current = false;
        previousStatus.current = null;
        previousPosition.current = null;
        setQueueData(null);
        setCurrentQueueNumber('---');
        setCountdownSeconds(0);
        setCountdownText('');
        initialCountdownSet.current = false;
        stopAllNotifications();
      }
    } catch (error: any) {
      // FIX: gi-expand ang error log para makita dayon ang tinuod nga
      // rason sa sunod nga "Network Error" (o bisan unsa nga sayop):
      //   - message / code: e.g. "Network Error", "ECONNABORTED" (timeout)
      //   - status / data: kung naa'y response gikan sa server (401, 500...)
      //   - url / baseURL: aron makumpirma nato asa gyud nga address ang
      //     gisulayan pag-abot sa request, kung tama ba ang IP/port.
      // Kaniadto, "console.error('Fetch error:', error)" ra, nga usahay
      // dili klaro kay ang AxiosError object daghan layer/circular ref,
      // mao nga morag "walay laman" ra ang makita sa console.
      console.error('Fetch queue status error:', {
        message: error?.message,
        code: error?.code,
        status: error?.response?.status,
        data: error?.response?.data,
        url: error?.config?.url,
        baseURL: error?.config?.baseURL,
      });

      // FIX: i-dugang ang failure counter kada network error. Human sa
      // 2 ka sunod-sunod nga fail (~6 segundos base sa 3s polling interval),
      // ipakita ang offline banner sa user imbes nga silent lang ang fail
      // kada 3 segundos nga walay ikasulti sa UI.
      consecutiveFailures.current += 1;
      if (consecutiveFailures.current >= 2 && isMounted.current) {
        setIsOffline(true);
      }

      if (isMounted.current) {
        setQueueData(null);
        setCurrentQueueNumber('---');
      }
    } finally {
      if (isMounted.current) {
        setLoading(false);
        setRefreshing(false);
      }
    }
  };

  const onRefresh = () => { setRefreshing(true); fetchQueueStatus(); };

  const processServicesList = (services: any[]) => {
    if (!services || services.length === 0) return [];
    let foundInProgress = false;
    return services.map((s) => {
      if (s.status === 'completed') return s;
      let status = s.status || 'pending';
      if (!foundInProgress && status !== 'completed') { status = 'in_progress'; foundInProgress = true; }
      else if (status !== 'completed') { status = 'pending'; }
      return { ...s, status };
    });
  };

  const viewServices = (item: any) => {
    const services = item?.services || [];
    setSelectedQueueNumber(item?.queue_number || null);

    if (!services || services.length === 0) {
      setSelectedServices([]);
      setShowServicesModal(true);
      return;
    }

    setSelectedServices(processServicesList(services));
    setShowServicesModal(true);
  };

  const refreshServices = async () => {
    if (!selectedQueueNumber) {
      setServicesLoading(true);
      try {
        const response = await api.get('/patient/queue/status');
        const result = response.data;
        if (result.success && result.data?.services) {
          setSelectedServices(processServicesList(result.data.services));
          setRefreshKey(prev => prev + 1);
        }
      } catch (error: any) {
        console.error('refreshServices error (own status):', {
          message: error?.message,
          status: error?.response?.status,
        });
      }
      finally { setServicesLoading(false); }
      return;
    }

    setServicesLoading(true);
    try {
      const response = await api.get('/patient/queue/status');
      const result = response.data;

      if (result.success && result.data) {
        let matchedServices: any[] | null = null;

        if (result.data.queue_number === selectedQueueNumber) {
          matchedServices = result.data.services || [];
        } else if (Array.isArray(result.data.queue_list)) {
          const match = result.data.queue_list.find(
            (q: any) => q.queue_number === selectedQueueNumber
          );
          matchedServices = match?.services || [];
        }

        if (matchedServices) {
          setSelectedServices(processServicesList(matchedServices));
          setRefreshKey(prev => prev + 1);
        }
      }
    } catch (error: any) {
      console.error('refreshServices error (queue list item):', {
        message: error?.message,
        status: error?.response?.status,
      });
    }
    finally { setServicesLoading(false); }
  };

  const hasQueue      = queueData && queueData.queue_number;
  const isBeingServed = queueData?.status === 'in_progress';
  const isProcessing  = queueData?.status === 'processing';
  const isSkipped      = queueData?.status === 'skipped';
  const peopleAhead   = hasQueue && !isBeingServed && !isProcessing && queueData?.position ? Math.max(0, Number(queueData.position) - 1) : 0;
  const etaMinRange   = hasQueue && !isBeingServed && !isProcessing && peopleAhead > 0 ? { low: peopleAhead * 15, high: (peopleAhead + 1) * 15 } : null;

  // Everyone in the queue list except the current patient — this is what
  // renders under the "Next in Line" heading now, instead of the old
  // "View List" modal.
  const nextInLine = (queueData?.queue_list || []).filter(
    (item: any) => item.queue_number !== queueData?.queue_number
  );

  const navigateTo = useCallback((path: string) => {
    if (!isMounted.current || !isComponentReady.current) {
      console.warn('Navigation not ready yet');
      return;
    }

    InteractionManager.runAfterInteractions(() => {
      try {
        if (router && typeof router.push === 'function') {
          router.push(path as any);
        }
      } catch (error) {
        console.error('Navigation error:', error);
        safeAlert('Navigation Error', 'Could not navigate.');
      }
    });
  }, [router]);

  if (loading) {
    return (
      <View className="flex-1 items-center justify-center bg-[#065f46]">
        <ActivityIndicator size="large" color="#FFFFFF" />
        <Text className="mt-3 text-base font-medium text-white">Loading queue status...</Text>
      </View>
    );
  }

  return (
    <>
      <StatusBar barStyle="light-content" backgroundColor="#065f46" />

      <ScrollView
        className="flex-1 bg-[#F0F5F1]"
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={['#047857']} tintColor="#047857" />}
      >
        {/* HEADER */}
        <View className="bg-[#065f46] pt-16 pb-5 px-5 flex-row items-center justify-between">
          <View className="w-10 h-10" />
          <Text className="text-lg font-bold text-white">Queue Number</Text>
          <TouchableOpacity
            className="items-center justify-center w-10 h-10 rounded-full"
            style={{ backgroundColor: 'rgba(255, 255, 255, 0.2)' }}
            onPress={() => navigateTo('/queue-history')}
          >
            <Ionicons name="time-outline" size={22} color="#fff" />
          </TouchableOpacity>
        </View>

        {/* FIX: Offline banner — ipakita kung sunod-sunod nga mag-fail
            ang polling (network error), aron naay clear feedback sa user
            imbes nga silent lang ang mga fail kada 3 segundos. */}
        {isOffline && (
          <View className="flex-row items-center justify-center gap-2 px-5 py-2 bg-red-100">
            <Ionicons name="cloud-offline-outline" size={14} color="#dc2626" />
            <Text className="text-[11px] text-red-700 font-medium">
              Can't reach the server. Check your connection.
            </Text>
          </View>
        )}

        {/* Indikasyon nga aktibo ang WebSocket real-time connection */}
        {echoRef.current && (
          <View className="bg-green-100 px-5 py-1.5 flex-row items-center justify-center gap-2">
            <View className="w-2 h-2 bg-green-500 rounded-full" />
            <Text className="text-[11px] text-green-700 font-medium">Real-time connection active</Text>
          </View>
        )}

        {hasQueue ? (
          <View className="px-5 pt-5">
            {/* SKIPPED BANNER */}
            {isSkipped && (
              <View className="rounded-[20px] mb-4 bg-red-50 border border-red-200 overflow-hidden">
                <View className="flex-row items-center px-4 py-4">
                  <View className="w-[50px] h-[50px] rounded-full bg-red-100 items-center justify-center mr-3">
                    <Ionicons name="warning" size={26} color="#ef4444" />
                  </View>
                  <View className="flex-1">
                    <Text className="text-[15px] font-bold text-red-700">Queue Skipped</Text>
                    <Text className="text-xs text-red-600 mt-0.5 leading-[17px]">
                      Please talk to the staff for requeue
                    </Text>
                  </View>
                </View>
              </View>
            )}

            {/* NOTIFY BANNER */}
            {!isSkipped && (
              <View className="rounded-[20px] mb-4 bg-[#e8f5ee] overflow-hidden">
                <View className="flex-row items-center px-4 py-4">
                  <View className="w-[50px] h-[50px] rounded-full bg-white items-center justify-center mr-3">
                    <Ionicons name="notifications" size={26} color="#065f46" />
                  </View>
                  <View className="flex-1">
                    <Text className="text-[15px] font-bold text-emerald-900">We'll notify you!</Text>
                    <Text className="text-xs text-emerald-700 mt-0.5 leading-[17px]">
                      You'll receive a notification when{'\n'}it's almost your turn.
                    </Text>
                  </View>
                </View>
              </View>
            )}

            {/* TICKET CARD */}
            <View
              className="bg-white rounded-[24px] overflow-hidden mb-4"
              style={shadowStyles}
            >
              <Image
                source={{ uri: 'https://images.pexels.com/photos/5452293/pexels-photo-5452293.jpeg?w=400' }}
                className="absolute right-0 top-0 bottom-0 w-[180px]"
                style={{ opacity: 0.3 }}
                resizeMode="cover"
                blurRadius={2}
              />
              <View className="px-5 pt-6 pb-5">
                <View className="flex-row items-center gap-1.5 mb-3">
                  <View className={`w-2 h-2 rounded-full ${isSkipped ? 'bg-red-500' : 'bg-emerald-600'}`} />
                  <Text className={`text-[11px] font-bold tracking-widest uppercase ${isSkipped ? 'text-red-600' : 'text-emerald-700'}`}>
                    {isSkipped ? 'Queue Skipped' : 'Your Current Queue'}
                  </Text>
                </View>
                <Text className={`text-[64px] font-black leading-none ${isSkipped ? 'text-red-500' : 'text-emerald-900'}`}>
                  {currentQueueNumber}
                </Text>
                {isBeingServed ? (
                  <Text className="text-[15px] font-semibold text-blue-600 mt-3">You are being served</Text>
                ) : isProcessing ? (
                  <Text className="text-[15px] font-semibold text-orange-500 mt-3">Waiting for your response...</Text>
                ) : isSkipped ? (
                  <View>
                    <Text className="text-[15px] font-semibold text-red-500 mt-3">Queue Skipped</Text>
                    <Text className="text-[13px] text-red-400 mt-1">Please talk to staff for requeue</Text>
                  </View>
                ) : (
                  <Text className="text-[15px] font-semibold text-gray-700 mt-3">You are in line</Text>
                )}
                <View className="flex-row items-center gap-2 pt-3 mt-3 border-t border-gray-100">
                  <Ionicons name="time-outline" size={16} color={isSkipped ? '#ef4444' : '#047857'} />
                  <Text className={`text-xs font-medium ${isSkipped ? 'text-red-500' : 'text-emerald-700'}`}>
                    {isSkipped ? 'Status:' : 'Est. Waiting Time:'}
                  </Text>
                  <Text className={`text-sm font-bold ${isSkipped ? 'text-red-600' : 'text-emerald-900'}`}>
                    {isBeingServed ? 'Now' : isProcessing ? 'Waiting...' : isSkipped ? 'Skipped - Talk to Staff' : countdownText ? countdownText : etaMinRange ? `${etaMinRange.low}-${etaMinRange.high} min` : 'Less than 15 min'}
                  </Text>
                </View>
              </View>
            </View>

            {/* DIVIDER */}
            <View className="my-2 border-t border-gray-200" />

            {/* NEXT IN LINE */}
            <View className="mt-4 mb-2">
              <Text className="text-base font-extrabold text-gray-900">Next in Line</Text>
              <Text className="text-xs text-gray-400 mt-0.5">
                {nextInLine.length > 0
                  ? `${nextInLine.length} ${nextInLine.length > 1 ? 'people' : 'person'} ahead or after you`
                  : 'No one else in the queue right now'}
              </Text>
            </View>

            {nextInLine.length > 0 ? (
              <View className="mb-4">
                {nextInLine.map((item: any, index: number) => {
                  const isNowServing  = item.status === 'in_progress';
                  const isItemProc    = item.status === 'processing';
                  const isItemSkipped = item.status === 'skipped';
                  return (
                    <View
                      key={item.queue_number || getUniqueKey('nl')}
                      className="mb-3 overflow-hidden rounded-2xl"
                      style={[
                        isNowServing ? {
                          backgroundColor: '#f0fdf4',
                          borderWidth: 1,
                          borderColor: '#86efac'
                        } : isItemProc ? {
                          backgroundColor: '#fff7ed',
                          borderWidth: 1,
                          borderColor: '#fdba74'
                        } : isItemSkipped ? {
                          backgroundColor: '#fef2f2',
                          borderWidth: 1,
                          borderColor: '#fecaca'
                        } : {
                          backgroundColor: '#ffffff',
                          borderWidth: 1,
                          borderColor: '#f3f4f6'
                        }
                      ]}
                    >
                      <View className="flex-row items-center p-4">
                        <View
                          className="w-[50px] h-[50px] rounded-2xl items-center justify-center mr-3.5"
                          style={{
                            backgroundColor: isNowServing ? '#22c55e'
                              : isItemProc ? '#fb923c'
                              : isItemSkipped ? '#ef4444'
                              : '#f3f4f6'
                          }}
                        >
                          <Text
                            className="text-lg font-extrabold"
                            style={{
                              color: isNowServing || isItemProc || isItemSkipped ? '#ffffff' : '#4b5563'
                            }}
                          >
                            {item.queue_number?.replace('Q-', '') || index + 1}
                          </Text>
                        </View>
                        <View className="flex-1 mr-3">
                          <Text
                            className="text-[15px] font-bold mb-0.5"
                            style={{
                              color: isNowServing ? '#166534' : isItemSkipped ? '#991b1b' : '#111827'
                            }}
                            numberOfLines={1}
                          >
                            {item.name || 'Patient'}
                          </Text>
                          <View className="flex-row items-center gap-2">
                            <View
                              className="px-2.5 py-0.5 rounded-full"
                              style={{
                                backgroundColor: isNowServing ? '#bbf7d0'
                                  : isItemProc ? '#fed7aa'
                                  : isItemSkipped ? '#fecaca'
                                  : '#f3f4f6'
                              }}
                            >
                              <Text
                                className="text-[10px] font-bold"
                                style={{
                                  color: isNowServing ? '#166534'
                                    : isItemProc ? '#9a3412'
                                    : isItemSkipped ? '#991b1b'
                                    : '#6b7280'
                                }}
                              >
                                {item.status_display || (isNowServing ? 'Now Serving' : isItemProc ? 'Processing' : isItemSkipped ? 'Skipped' : 'Waiting')}
                              </Text>
                            </View>
                            {item.service_count > 0 && (
                              <View className="flex-row items-center gap-1 bg-gray-50 px-2 py-0.5 rounded-full">
                                <Ionicons name="medkit-outline" size={10} color="#6b7280" />
                                <Text className="text-[10px] font-medium text-gray-500">
                                  {item.service_count} service{item.service_count > 1 ? 's' : ''}
                                </Text>
                              </View>
                            )}
                          </View>
                        </View>
                        <TouchableOpacity
                          onPress={() => viewServices(item)}
                          className="items-center justify-center w-9 h-9 rounded-xl"
                          style={{
                            backgroundColor: '#f9fafb',
                            borderWidth: 1,
                            borderColor: '#f3f4f6'
                          }}
                        >
                          <Svg width={18} height={18} viewBox="0 0 24 24" fill="none" stroke="#047857" strokeWidth={2.5}>
                            <Path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <Circle cx="12" cy="12" r="3" />
                          </Svg>
                        </TouchableOpacity>
                      </View>
                    </View>
                  );
                })}
              </View>
            ) : (
              <View className="items-center py-10 mb-4 bg-white border border-gray-100 rounded-2xl">
                <Ionicons name="people-outline" size={26} color="#9ca3af" />
                <Text className="mt-2 text-sm text-gray-400">No one else in the queue</Text>
              </View>
            )}
          </View>
        ) : (
          <View className="items-center px-8 pt-16 mt-11">
            <View className="items-center justify-center w-24 h-24 mb-5 rounded-full bg-emerald-100">
              <Ionicons name="calendar-outline" size={48} color="#065f46" />
            </View>
            <Text className="text-[20px] font-bold text-emerald-900 mb-2">No Active Queue</Text>
            <Text className="text-[13px] text-gray-500 text-center leading-[19px] mb-8">
              You don't have any active queue right now.{'\n'}Book an appointment and join the queue.
            </Text>
          </View>
        )}

        {/* Guard: ayaw ipakita ang banner kung nag-process pa ang response */}
        {isCalled && !showCalledModal && !isResponding && (
          <TouchableOpacity
            className="bg-orange-100 mx-5 mt-1 mb-1 p-3.5 rounded-2xl border-l-4 border-orange-500"
            onPress={() => setShowCalledModal(true)}
          >
            <Text className="text-gray-900 text-center text-[13px] font-semibold">
               You are being called! Please proceed to the clinic immediately!
            </Text>
            <Text className="text-center text-[11px] text-orange-500 mt-1">Tap to respond</Text>
          </TouchableOpacity>
        )}

        <View className="h-8" />
      </ScrollView>

      {/* CALLED MODAL */}
      <Modal visible={showCalledModal} animationType="fade" transparent onRequestClose={() => {}}>
        <View
          className="items-center justify-center flex-1"
          style={{ backgroundColor: 'rgba(0, 0, 0, 0.55)' }}
        >
          <View
            className="w-[88%] bg-white rounded-[28px] overflow-hidden"
            style={shadow2xl}
          >
            <View className="bg-[#065f46] items-center pt-9 pb-6 px-6">
              <View
                className="w-20 h-20 rounded-full items-center justify-center mb-3.5"
                style={{ backgroundColor: 'rgba(255, 255, 255, 0.2)' }}
              >
                <Ionicons name="megaphone" size={40} color="#fff" />
              </View>
              <Text className="text-[22px] font-black text-white text-center">You're Being Called!</Text>
              <Text className="text-[13px] text-emerald-100 mt-1.5 text-center leading-[18px]">
                Queue #{calledQueueNumber} — please proceed{'\n'}to the clinic now.
              </Text>
            </View>
            <View className="px-6 pt-5 pb-2">
              <Text className="text-[15px] font-bold text-gray-900 text-center">Are you ready to proceed?</Text>
              <Text className="mt-1 text-xs text-center text-gray-500">Let the clinic know if you're on your way.</Text>
            </View>
            <View className="flex-row gap-3 p-5">
              {/* Ang duha ka buttons kay disabled kung nag-respond pa para di maka-double tap */}
              <TouchableOpacity
                className="flex-1 py-3.5 bg-red-50 rounded-2xl items-center border-[1.5px] border-red-200"
                onPress={() => handleCalledResponse(false)}
                disabled={isResponding}
                style={isResponding ? { opacity: 0.5 } : {}}
              >
                {isResponding ? (
                  <ActivityIndicator size="small" color="#ef4444" />
                ) : (
                  <>
                    <Ionicons name="close" size={18} color="#ef4444" />
                    <Text className="text-[13px] font-bold text-red-500 mt-0.5">No</Text>
                  </>
                )}
              </TouchableOpacity>
              <TouchableOpacity
                className="flex-[2] py-3.5 bg-[#065f46] rounded-2xl flex-row items-center justify-center gap-2"
                onPress={() => handleCalledResponse(true)}
                disabled={isResponding}
                style={isResponding ? { opacity: 0.5 } : {}}
              >
                {isResponding ? (
                  <ActivityIndicator size="small" color="#fff" />
                ) : (
                  <>
                    <Ionicons name="walk" size={20} color="#fff" />
                    <Text className="text-sm font-black text-white">Yes</Text>
                  </>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* COMPLETED MODAL */}
      <Modal visible={showCompletedModal} animationType="fade" transparent onRequestClose={() => {}}>
        <View
          className="items-center justify-center flex-1"
          style={{ backgroundColor: 'rgba(0, 0, 0, 0.55)' }}
        >
          <View
            className="w-[88%] bg-white rounded-[28px] overflow-hidden"
            style={shadow2xl}
          >
            <View className="items-center px-5 pt-6 pb-5 bg-green-50">
              <View className="w-[72px] h-[72px] rounded-full bg-green-100 items-center justify-center mb-3">
                <Ionicons name="checkmark-circle" size={48} color="#16a34a" />
              </View>
              <Text className="text-[22px] font-black text-green-900 text-center">Queue Completed!</Text>
              <Text className="text-[13px] text-green-700 mt-1.5 text-center leading-[18px]">
                Your queue #{currentQueueNumber} has been completed.{'\n'}Thank you for your patience!
              </Text>
            </View>
            <View className="px-5 pt-3">
              <View className="flex-row items-center gap-2.5 bg-amber-50 rounded-[14px] p-3.5 border border-amber-200">
                <Ionicons name="receipt-outline" size={22} color="#b45309" />
                <View className="flex-1">
                  <Text className="text-sm font-bold text-amber-800">Proceed to Billing</Text>
                  <Text className="text-xs text-amber-600 mt-0.5">
                    Please go to the billing counter to complete your visit.
                  </Text>
                </View>
              </View>
            </View>
            <View className="flex-row gap-3 px-5 pt-4 pb-6">
              <TouchableOpacity
                className="flex-1 py-3.5 bg-gray-100 rounded-2xl items-center"
                onPress={() => {
                  setShowCompletedModal(false);
                  setQueueData(null);
                  setCurrentQueueNumber('---');
                  disconnectEcho(); // Disconnect WebSocket kung humana ang queue
                }}
              >
                <Text className="text-sm font-semibold text-gray-500">Close</Text>
              </TouchableOpacity>
              <TouchableOpacity
                className="flex-[2] py-3.5 bg-amber-700 rounded-2xl flex-row items-center justify-center gap-2"
                onPress={() => {
                  setShowCompletedModal(false);
                  setQueueData(null);
                  setCurrentQueueNumber('---');
                  disconnectEcho(); // Disconnect WebSocket kung humana ang queue
                  navigateTo('/(main)/(tabs)/billing');
                }}
                activeOpacity={0.85}
              >
                <Ionicons name="receipt-outline" size={18} color="#fff" />
                <Text className="text-sm font-black text-white">Go to Billing</Text>
                <Ionicons name="arrow-forward" size={16} color="#fff" />
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* SERVICES MODAL */}
      <Modal
        visible={showServicesModal}
        animationType="slide"
        transparent
        onRequestClose={() => { setShowServicesModal(false); setSelectedQueueNumber(null); }}
      >
        <View className="justify-end flex-1">
          <TouchableOpacity
            className="flex-1"
            activeOpacity={1}
            onPress={() => { setShowServicesModal(false); setSelectedQueueNumber(null); }}
            style={{ backgroundColor: 'rgba(0, 0, 0, 0.5)' }}
          />
          <View
            className="bg-white rounded-t-[32px] max-h-[75%] overflow-hidden"
            style={shadow2xl}
          >
            <View className="items-center pt-3 pb-1">
              <View className="w-10 h-1.5 rounded-full bg-gray-300" />
            </View>
            <View className="flex-row items-center justify-between px-5 py-4 border-b border-gray-100">
              <View>
                <Text className="text-[20px] font-extrabold text-gray-900">Services</Text>
                <Text className="text-xs text-gray-400 mt-0.5">{selectedServices.length} services</Text>
              </View>
              <View className="flex-row items-center gap-2">
                <TouchableOpacity
                  className="items-center justify-center rounded-full w-9 h-9"
                  style={{ backgroundColor: '#f0fdf4' }}
                  onPress={refreshServices}
                  disabled={servicesLoading}
                >
                  {servicesLoading
                    ? <ActivityIndicator size="small" color="#059669" />
                    : <Ionicons name="refresh" size={18} color="#059669" />
                  }
                </TouchableOpacity>
                <TouchableOpacity
                  onPress={() => { setShowServicesModal(false); setSelectedQueueNumber(null); }}
                  className="items-center justify-center bg-gray-100 rounded-full w-9 h-9"
                >
                  <Ionicons name="close" size={20} color="#6b7280" />
                </TouchableOpacity>
              </View>
            </View>
            {selectedServices.length > 0 ? (
              <FlatList
                data={selectedServices}
                keyExtractor={(item, index) => getUniqueKey('svc')}
                showsVerticalScrollIndicator={false}
                contentContainerStyle={{ paddingHorizontal: 16, paddingTop: 8, paddingBottom: 30 }}
                renderItem={({ item, index }) => {
                  const itemName = item?.name || item?.service_name || `Service ${index + 1}`;
                  const itemStatus = item?.status || 'pending';
                  let statusDisplay = 'Pending', statusColor = '#64748b', statusBg = '#f1f5f9';
                  if (itemStatus === 'completed') { statusDisplay = 'Completed'; statusColor = '#16a34a'; statusBg = '#dcfce7'; }
                  else if (itemStatus === 'in_progress') { statusDisplay = 'In Progress'; statusColor = '#ea580c'; statusBg = '#fff7ed'; }

                  return (
                    <View
                      className="flex-row items-center p-4 mb-2 rounded-2xl"
                      style={{
                        backgroundColor: '#ffffff',
                        borderWidth: 1,
                        borderColor: '#f3f4f6'
                      }}
                    >
                      <View
                        className="items-center justify-center w-10 h-10 mr-3 rounded-xl"
                        style={{ backgroundColor: statusBg }}
                      >
                        <Text className="text-[15px] font-bold" style={{ color: statusColor }}>{index + 1}</Text>
                      </View>
                      <View className="flex-1">
                        <Text
                          className="text-sm font-semibold text-gray-900"
                          style={itemStatus === 'completed' ? { textDecorationLine: 'line-through' } : {}}
                          numberOfLines={1}
                        >
                          {itemName}
                        </Text>
                      </View>
                      <View
                        className="px-3 py-1.5 rounded-full"
                        style={{ backgroundColor: statusBg }}
                      >
                        <Text className="text-[11px] font-bold" style={{ color: statusColor }}>{statusDisplay}</Text>
                      </View>
                    </View>
                  );
                }}
              />
            ) : (
              <View className="items-center py-16">
                <Text className="text-sm text-gray-400">No services available</Text>
              </View>
            )}
          </View>
        </View>
      </Modal>
    </>
  );
}