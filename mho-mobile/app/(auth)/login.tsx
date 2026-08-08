import React, { useState, useRef, useEffect } from 'react';
import {
  View, Text, Alert, KeyboardAvoidingView, Platform, TextInput,
  TouchableOpacity, Dimensions, StatusBar, ScrollView,
  AppState, Image,
} from 'react-native';
import { useRouter } from 'expo-router';
import * as Notifications from 'expo-notifications';
import Constants from 'expo-constants';
import { authAPI } from './../lib/api';
import api from './../lib/api';
import { storage } from './../lib/storage';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

const { width, height } = Dimensions.get('window');

export default function LoginScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [errors, setErrors] = useState({ email: '', password: '' });
  const [focus, setFocus] = useState({ email: false, password: false });

  const heartbeatRef = useRef<any>(null);

  const startHeartbeat = async () => {
    if (heartbeatRef.current) clearInterval(heartbeatRef.current);
    heartbeatRef.current = setInterval(async () => {
      try { await api.post('/patient/heartbeat'); } catch (e) {}
    }, 30000);
  };

  const stopHeartbeat = () => {
    if (heartbeatRef.current) {
      clearInterval(heartbeatRef.current);
      heartbeatRef.current = null;
    }
  };

  // ✅ NEW: Registers (or re-registers) this device's Expo push token and
  // ties it to whichever account JUST logged in. This is what stops
  // notifications meant for one patient from landing on another patient's
  // device — every login now overwrites `expo_push_token` for the CURRENT
  // account, instead of relying on a stale token from a previous session.
  const registerPushToken = async () => {
    try {
      const { status: existingStatus } = await Notifications.getPermissionsAsync();
      let finalStatus = existingStatus;

      if (existingStatus !== 'granted') {
        const { status } = await Notifications.requestPermissionsAsync();
        finalStatus = status;
      }

      if (finalStatus !== 'granted') {
        console.log('Push notification permission not granted');
        return;
      }

      const tokenData = await Notifications.getExpoPushTokenAsync({
        projectId: Constants.expoConfig?.extra?.eas?.projectId,
      });

      await api.post('/patient/push-token', {
        expo_push_token: tokenData.data,
      });
    } catch (e) {
      console.log('Push token registration error:', e);
    }
  };

  useEffect(() => {
    const subscription = AppState.addEventListener('change', (nextAppState) => {
      if (nextAppState === 'active') startHeartbeat();
      else stopHeartbeat();
    });
    return () => { stopHeartbeat(); subscription?.remove(); };
  }, []);

  const handleLogin = async () => {
    const newErrors = { email: '', password: '' };
    let hasError = false;
    if (!email.trim()) { newErrors.email = 'Please fill out this field'; hasError = true; }
    if (!password.trim()) { newErrors.password = 'Please fill out this field'; hasError = true; }
    setErrors(newErrors);
    if (hasError) return;

    setLoading(true);
    try {
      const response = await authAPI.login(email, password);
      if (response.success) {
        const token = response.data?.access_token || response.access_token || response.token;
        const userData = response.data?.patient || response.patient || response.data?.user || response.user;

        if (token) {
          await AsyncStorage.setItem('token', token);
          await storage.saveToken(token);
        }
        if (userData && userData.id) {
          const existingPatientStr = await AsyncStorage.getItem('patient');
          let existingPatient = null;
          if (existingPatientStr) { try { existingPatient = JSON.parse(existingPatientStr); } catch (e) {} }
          if (!existingPatient || existingPatient.id === userData.id || existingPatient.email === email) {
            const patientToSave = { ...userData, email };
            await AsyncStorage.setItem('patient_id', userData.id.toString());
            await storage.savePatient(patientToSave);
            await AsyncStorage.setItem('patient', JSON.stringify(patientToSave));
          }
        }
        startHeartbeat();

        // ✅ Register the push token for THIS account right after login.
        // Awaited before navigating so the token is saved before the user
        // can bounce to another screen or log out again quickly.
        await registerPushToken();

        const isProfileComplete = userData?.first_name?.trim() && userData?.last_name?.trim();
        Alert.alert('Success', 'Logged in successfully!');
        router.replace(isProfileComplete ? '/(main)/(tabs)/dashboard' : '/(main)/profile/edit');
      } else {
        Alert.alert('Login Failed', response.message || 'Invalid credentials');
      }
    } catch (error: any) {
      Alert.alert('Login Failed', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={{ flex: 1, backgroundColor: '#ffffff' }}>
      <StatusBar barStyle="dark-content" backgroundColor="#ffffff" />
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={{ flexGrow: 1 }}
          keyboardShouldPersistTaps="handled"
          showsVerticalScrollIndicator={false}
        >
          {/* ── LOGO SECTION ── */}
          <View style={{
            alignItems: 'center',
            paddingTop: 60,
            paddingBottom: 32,
            paddingHorizontal: 24,
          }}>
            <Image
              source={require('../../assets/images/mho123.png')}
              style={{ width: 160, height: 160, resizeMode: 'contain' }}
            />
            <Text style={{
              fontSize: 26,
              fontWeight: '800',
              color: '#16a34a',
              textAlign: 'center',
              marginTop: 16,
              lineHeight: 32,
              letterSpacing: 0.3,
            }}>
              OPOL PRIMARY{'\n'}CARE FACILITY
            </Text>

            {/* decorative line with dot */}
            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 10, width: 220 }}>
              <View style={{ flex: 1, height: 1.5, backgroundColor: '#16a34a', opacity: 0.5 }} />
              <View style={{ width: 7, height: 7, borderRadius: 4, backgroundColor: '#16a34a', marginHorizontal: 6 }} />
              <View style={{ flex: 1, height: 1.5, backgroundColor: '#16a34a', opacity: 0.5 }} />
            </View>

            <Text style={{
              fontSize: 12,
              color: '#6b7280',
              letterSpacing: 2,
              marginTop: 8,
              fontWeight: '500',
            }}>
              OPOL, MISAMIS ORIENTAL
            </Text>
          </View>

          {/* ── FORM SECTION ── */}
          <View style={{ paddingHorizontal: 28, paddingBottom: 40 }}>

            {/* Email */}
            <View style={{ marginBottom: 16 }}>
              <Text style={{ fontSize: 14, fontWeight: '600', color: '#111827', marginBottom: 8 }}>
                Email
              </Text>
              <View style={{
                flexDirection: 'row',
                alignItems: 'center',
                borderWidth: 1.5,
                borderColor: errors.email ? '#ef4444' : focus.email ? '#16a34a' : '#e5e7eb',
                borderRadius: 25,
                paddingHorizontal: 14,
                height: 54,
                backgroundColor: '#fff',
              }}>
                <Ionicons
                  name="mail-outline"
                  size={20}
                  color={errors.email ? '#ef4444' : '#9ca3af'}
                />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  value={email}
                  placeholder="Enter your email"
                  placeholderTextColor="#9ca3af"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  onFocus={() => { setFocus({ ...focus, email: true }); if (errors.email) setErrors(p => ({ ...p, email: '' })); }}
                  onBlur={() => setFocus({ ...focus, email: false })}
                  onChangeText={(text) => { setEmail(text); if (errors.email) setErrors(p => ({ ...p, email: '' })); }}
                />
              </View>
              {errors.email ? (
                <Text style={{ color: '#ef4444', fontSize: 12, marginTop: 4, marginLeft: 2 }}>{errors.email}</Text>
              ) : null}
            </View>

            {/* Password */}
            <View style={{ marginBottom: 8 }}>
              <Text style={{ fontSize: 14, fontWeight: '600', color: '#111827', marginBottom: 8 }}>
                Password
              </Text>
              <View style={{
                flexDirection: 'row',
                alignItems: 'center',
                borderWidth: 1.5,
                borderColor: errors.password ? '#ef4444' : focus.password ? '#16a34a' : '#e5e7eb',
                borderRadius: 25,
                paddingHorizontal: 14,
                height: 54,
                backgroundColor: '#fff',
              }}>
                <Ionicons
                  name="lock-closed-outline"
                  size={20}
                  color={errors.password ? '#ef4444' : '#9ca3af'}
                />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  value={password}
                  placeholder="Enter your password"
                  placeholderTextColor="#9ca3af"
                  secureTextEntry={!showPassword}
                  onFocus={() => { setFocus({ ...focus, password: true }); if (errors.password) setErrors(p => ({ ...p, password: '' })); }}
                  onBlur={() => setFocus({ ...focus, password: false })}
                  onChangeText={(text) => { setPassword(text); if (errors.password) setErrors(p => ({ ...p, password: '' })); }}
                />
                <TouchableOpacity onPress={() => setShowPassword(!showPassword)}>
                  <Ionicons
                    name={showPassword ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={errors.password ? '#ef4444' : '#9ca3af'}
                  />
                </TouchableOpacity>
              </View>
              {errors.password ? (
                <Text style={{ color: '#ef4444', fontSize: 12, marginTop: 4, marginLeft: 2 }}>{errors.password}</Text>
              ) : null}
            </View>

            {/* Forgot Password */}
            <TouchableOpacity
              style={{ alignItems: 'flex-end', marginBottom: 24 }}
              onPress={() => router.push('/(auth)/forgot-password')}
            >
              <Text style={{ color: '#16a34a', fontSize: 13, fontWeight: '600' }}>
                Forgot Password?
              </Text>
            </TouchableOpacity>

            {/* Sign In Button */}
            <TouchableOpacity
              style={{
                backgroundColor: loading ? '#86efac' : '#16a34a',
                borderRadius: 25,
                height: 54,
                alignItems: 'center',
                justifyContent: 'center',
                shadowColor: '#16a34a',
                shadowOffset: { width: 0, height: 4 },
                shadowOpacity: 0.3,
                shadowRadius: 8,
                elevation: 4,
              }}
              onPress={handleLogin}
              disabled={loading}
            >
              <Text style={{ color: '#fff', fontSize: 15, fontWeight: '700', letterSpacing: 1 }}>
                {loading ? 'SIGNING IN...' : 'SIGN IN'}
              </Text>
            </TouchableOpacity>

            {/* OR divider */}
            <View style={{ flexDirection: 'row', alignItems: 'center', marginVertical: 20 }}>
              <View style={{ flex: 1, height: 1, backgroundColor: '#e5e7eb' }} />
              <Text style={{ marginHorizontal: 12, color: '#9ca3af', fontSize: 13, fontWeight: '500' }}>OR</Text>
              <View style={{ flex: 1, height: 1, backgroundColor: '#e5e7eb' }} />
            </View>

            {/* Register Link */}
            <View style={{ flexDirection: 'row', justifyContent: 'center', alignItems: 'center' }}>
              <Text style={{ color: '#6b7280', fontSize: 14 }}>Don,t have an account yet? </Text>
              <TouchableOpacity onPress={() => router.push('/(auth)/register')}>
                <Text style={{ color: '#16a34a', fontSize: 14, fontWeight: '700' }}>Register</Text>
              </TouchableOpacity>
            </View>

          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </View>
  );
}