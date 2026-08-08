import React, { useState } from 'react';
import {
  View,
  Text,
  Alert,
  KeyboardAvoidingView,
  Platform,
  TextInput,
  TouchableOpacity,
  StatusBar,
  ScrollView,
  Image,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { authAPI } from './../lib/api';
import { storage } from './../lib/storage';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function RegisterScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [focus, setFocus] = useState({
    username: false,
    email: false,
    password: false,
    confirm: false,
  });

  const [formData, setFormData] = useState({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const [errors, setErrors] = useState<Record<string, string>>({});

  const validate = () => {
    const newErrors: Record<string, string> = {};
    if (!formData.username.trim()) newErrors.username = 'Username is required';
    if (!formData.email.trim()) newErrors.email = 'Email is required';
    else if (!/\S+@\S+\.\S+/.test(formData.email)) newErrors.email = 'Invalid email format';
    if (!formData.password.trim()) newErrors.password = 'Password is required';
    else if (formData.password.length < 8) newErrors.password = 'Password must be at least 8 characters';
    if (!formData.password_confirmation.trim()) newErrors.password_confirmation = 'Confirm your password';
    else if (formData.password !== formData.password_confirmation) newErrors.password_confirmation = 'Passwords do not match';
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleRegister = async () => {
    if (!validate()) return;
    setLoading(true);
    try {
      const response = await authAPI.register(formData);
      if (response.success) {
        const token = response.data?.access_token || response.access_token || response.token;
        const patientData = response.data?.patient || response.patient;
        if (token) { await AsyncStorage.setItem('token', token); await storage.saveToken(token); }
        if (patientData) { await storage.savePatient(patientData); await AsyncStorage.setItem('patient', JSON.stringify(patientData)); }
        Alert.alert('Success', 'Account created successfully!', [{ text: 'OK', onPress: () => router.replace('/(auth)/login') }]);
      } else {
        Alert.alert('Error', response.message || 'Registration failed');
      }
    } catch (error: any) {
      Alert.alert('Error', error.response?.data?.message || error.message || 'Something went wrong. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const inputStyle = (fieldFocus: boolean, hasError: boolean) => ({
    flexDirection: 'row' as const,
    alignItems: 'center' as const,
    borderWidth: 1.5,
    borderColor: hasError ? '#ef4444' : fieldFocus ? '#16a34a' : '#e5e7eb',
    borderRadius: 10,
    paddingHorizontal: 14,
    height: 52,
    backgroundColor: '#fff',
  });

  return (
    <View style={{ flex: 1, backgroundColor: '#fff' }}>
      <StatusBar barStyle="dark-content" backgroundColor="#fff" />
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={{ flexGrow: 1 }}
          keyboardShouldPersistTaps="handled"
          showsVerticalScrollIndicator={false}
        >
          {/* ── Logo & Title ── */}
          <View style={{ alignItems: 'center', paddingTop: 56, paddingBottom: 24, paddingHorizontal: 24 }}>
            <Image
              source={require('../../assets/images/MHO12.jpg')}
              style={{ width: 130, height: 130, borderRadius: 65, marginBottom: 18 }}
              resizeMode="cover"
            />
            <Text style={{
              fontSize: 26, fontWeight: '800', color: '#16a34a',
              textAlign: 'center', letterSpacing: 0.5, lineHeight: 32,
            }}>
              OPOL PRIMARY{'\n'}CARE FACILITY
            </Text>

            {/* Divider with dot */}
            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 10, marginBottom: 4, width: '80%' }}>
              <View style={{ flex: 1, height: 1.5, backgroundColor: '#16a34a' }} />
              <View style={{ width: 6, height: 6, borderRadius: 3, backgroundColor: '#16a34a', marginHorizontal: 6 }} />
              <View style={{ flex: 1, height: 1.5, backgroundColor: '#16a34a' }} />
            </View>

            <Text style={{ fontSize: 11, color: '#9ca3af', letterSpacing: 2, fontWeight: '600', marginTop: 4 }}>
              OPOL, MISAMIS ORIENTAL
            </Text>
          </View>

          {/* ── Form ── */}
          <View style={{ paddingHorizontal: 24, paddingBottom: 40 }}>

            {/* USERNAME */}
            <View style={{ marginBottom: 16 }}>
              <Text style={{ fontSize: 13, fontWeight: '700', color: '#111827', marginBottom: 8 }}>Username</Text>
              <View style={[inputStyle(focus.username, !!errors.username), { borderRadius: 25 }]}>
                <Ionicons name="person-outline" size={18} color={errors.username ? '#ef4444' : '#16a34a'} />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  placeholder="Enter your username"
                  placeholderTextColor="#9ca3af"
                  autoCapitalize="none"
                  value={formData.username}
                  onFocus={() => { setFocus({ ...focus, username: true }); if (errors.username) setErrors(p => ({ ...p, username: '' })); }}
                  onBlur={() => setFocus({ ...focus, username: false })}
                  onChangeText={t => { setFormData({ ...formData, username: t }); if (errors.username) setErrors(p => ({ ...p, username: '' })); }}
                />
              </View>
              {errors.username ? <Text style={{ fontSize: 11, color: '#ef4444', marginTop: 4, marginLeft: 2 }}>{errors.username}</Text> : null}
            </View>

            {/* EMAIL */}
            <View style={{ marginBottom: 16 }}>
              <Text style={{ fontSize: 13, fontWeight: '700', color: '#111827', marginBottom: 8 }}>Email</Text>
              <View style={[inputStyle(focus.email, !!errors.email), { borderRadius: 25 }]}>
                <Ionicons name="mail-outline" size={18} color={errors.email ? '#ef4444' : '#16a34a'} />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  placeholder="Enter your email"
                  placeholderTextColor="#9ca3af"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  value={formData.email}
                  onFocus={() => { setFocus({ ...focus, email: true }); if (errors.email) setErrors(p => ({ ...p, email: '' })); }}
                  onBlur={() => setFocus({ ...focus, email: false })}
                  onChangeText={t => { setFormData({ ...formData, email: t }); if (errors.email) setErrors(p => ({ ...p, email: '' })); }}
                />
              </View>
              {errors.email ? <Text style={{ fontSize: 11, color: '#ef4444', marginTop: 4, marginLeft: 2 }}>{errors.email}</Text> : null}
            </View>

            {/* PASSWORD */}
            <View style={{ marginBottom: 16 }}>
              <Text style={{ fontSize: 13, fontWeight: '700', color: '#111827', marginBottom: 8 }}>Password</Text>
              <View style={[inputStyle(focus.password, !!errors.password), { borderRadius: 25 }]}>
                <Ionicons name="lock-closed-outline" size={18} color={errors.password ? '#ef4444' : '#16a34a'} />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  placeholder="Enter your password"
                  placeholderTextColor="#9ca3af"
                  secureTextEntry={!showPassword}
                  value={formData.password}
                  onFocus={() => { setFocus({ ...focus, password: true }); if (errors.password) setErrors(p => ({ ...p, password: '' })); }}
                  onBlur={() => setFocus({ ...focus, password: false })}
                  onChangeText={t => { setFormData({ ...formData, password: t }); if (errors.password) setErrors(p => ({ ...p, password: '' })); }}
                />
                <TouchableOpacity onPress={() => setShowPassword(!showPassword)}>
                  <Ionicons name={showPassword ? 'eye-off-outline' : 'eye-outline'} size={18} color={errors.password ? '#ef4444' : '#9ca3af'} />
                </TouchableOpacity>
              </View>
              {errors.password ? <Text style={{ fontSize: 11, color: '#ef4444', marginTop: 4, marginLeft: 2 }}>{errors.password}</Text> : null}
            </View>

            {/* CONFIRM PASSWORD */}
            <View style={{ marginBottom: 28 }}>
              <Text style={{ fontSize: 13, fontWeight: '700', color: '#111827', marginBottom: 8 }}>Confirm Password</Text>
              <View style={[inputStyle(focus.confirm, !!errors.password_confirmation), { borderRadius: 25 }]}>
                <Ionicons name="lock-closed-outline" size={18} color={errors.password_confirmation ? '#ef4444' : '#16a34a'} />
                <TextInput
                  style={{ flex: 1, marginLeft: 10, fontSize: 15, color: '#111827' }}
                  placeholder="Confirm your password"
                  placeholderTextColor="#9ca3af"
                  secureTextEntry={!showConfirmPassword}
                  value={formData.password_confirmation}
                  onFocus={() => { setFocus({ ...focus, confirm: true }); if (errors.password_confirmation) setErrors(p => ({ ...p, password_confirmation: '' })); }}
                  onBlur={() => setFocus({ ...focus, confirm: false })}
                  onChangeText={t => { setFormData({ ...formData, password_confirmation: t }); if (errors.password_confirmation) setErrors(p => ({ ...p, password_confirmation: '' })); }}
                />
                <TouchableOpacity onPress={() => setShowConfirmPassword(!showConfirmPassword)}>
                  <Ionicons name={showConfirmPassword ? 'eye-off-outline' : 'eye-outline'} size={18} color={errors.password_confirmation ? '#ef4444' : '#9ca3af'} />
                </TouchableOpacity>
              </View>
              {errors.password_confirmation ? <Text style={{ fontSize: 11, color: '#ef4444', marginTop: 4, marginLeft: 2 }}>{errors.password_confirmation}</Text> : null}
            </View>

            {/* REGISTER BUTTON */}
            <TouchableOpacity
              onPress={handleRegister}
              disabled={loading}
              style={{
                backgroundColor: loading ? '#86efac' : '#16a34a',
                borderRadius: 25,
                paddingVertical: 15,
                alignItems: 'center',
                marginBottom: 20,
              }}
            >
              <Text style={{ color: '#fff', fontSize: 15, fontWeight: '800', letterSpacing: 1 }}>
                {loading ? 'Creating Account...' : 'REGISTER'}
              </Text>
            </TouchableOpacity>

            {/* OR divider */}
            <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 20 }}>
              <View style={{ flex: 1, height: 1, backgroundColor: '#e5e7eb' }} />
              <Text style={{ marginHorizontal: 12, fontSize: 13, color: '#9ca3af', fontWeight: '600' }}>OR</Text>
              <View style={{ flex: 1, height: 1, backgroundColor: '#e5e7eb' }} />
            </View>

            {/* LOGIN LINK */}
            <View style={{ flexDirection: 'row', justifyContent: 'center', alignItems: 'center' }}>
              <Text style={{ fontSize: 14, color: '#6b7280' }}>Already have an account? </Text>
              <TouchableOpacity onPress={() => router.push('/(auth)/login')}>
                <Text style={{ fontSize: 14, fontWeight: '700', color: '#16a34a' }}>Sign In</Text>
              </TouchableOpacity>
            </View>

          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </View>
  );
}