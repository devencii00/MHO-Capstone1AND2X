import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  Dimensions,
  ScrollView,
  Image,
} from 'react-native';
import { useRouter } from 'expo-router';
import { authAPI } from './../lib/api';

const { width, height } = Dimensions.get('window');

export default function ForgotPasswordScreen() {
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [emailError, setEmailError] = useState('');

  // Email validation function
  const validateEmail = (email: string) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  const handleSendReset = async () => {
    // Clear previous errors
    setEmailError('');

    // Check if email is empty
    if (!email.trim()) {
      setEmailError('Please enter your email address');
      Alert.alert('Error', 'Please enter your email address');
      return;
    }

    // Check if email format is valid
    if (!validateEmail(email.trim())) {
      setEmailError('Please enter a valid email address');
      Alert.alert('Invalid Email', 'Please enter a valid email address (e.g., user@example.com)');
      return;
    }

    setLoading(true);

    try {
      const response = await authAPI.forgotPassword(email.trim());

      Alert.alert(
        'Success',
        'Reset link has been sent to your email. Please check your inbox.',
        [
          {
            text: 'OK',
            onPress: () => router.replace('/(auth)/login'),
          },
        ]
      );
    } catch (error: any) {
      console.log('Error details:', error?.response?.data);

      let errorMessage = 'Failed to send reset link. Please try again.';

      // Handle different error responses
      if (error?.response?.status === 422) {
        // Validation error
        if (error?.response?.data?.errors?.email) {
          errorMessage = error.response.data.errors.email[0];
          setEmailError(errorMessage);
        } else if (error?.response?.data?.message) {
          errorMessage = error.response.data.message;
        }
      } else if (error?.response?.status === 404) {
        errorMessage = 'No account found with this email address.';
      } else if (error?.response?.status === 429) {
        errorMessage = 'Too many attempts. Please try again later.';
      } else if (error?.message) {
        errorMessage = error.message;
      }

      Alert.alert(
        'Error',
        errorMessage
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={{ flex: 1, backgroundColor: '#FFFFFF' }}>
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
        style={{ flex: 1 }}
      >
        <ScrollView
          contentContainerStyle={{
            flexGrow: 1,
            justifyContent: 'center',
            alignItems: 'center',
            paddingHorizontal: 24,
            paddingTop: 60,
            paddingBottom: 40,
          }}
          keyboardShouldPersistTaps="handled"
        >
          {/* Logo Image Only - No background */}
          <Image
            source={require('../../assets/images/mho123.png')}
            style={{
              width: 150,
              height: 150,
              marginBottom: 32,
            }}
            resizeMode="contain"
          />

          {/* Title */}
          <Text
            style={{
              fontSize: 26,
              fontWeight: 'bold',
              color: '#111827',
              marginBottom: 8,
            }}
          >
            Forgot Password
          </Text>
          <Text
            style={{
              fontSize: 14,
              color: '#6b7280',
              textAlign: 'center',
              lineHeight: 20,
              marginBottom: 32,
              paddingHorizontal: 20,
            }}
          >
            Enter your email and we'll send you a reset link
          </Text>

          {/* Form */}
          <View style={{ width: '100%', maxWidth: 400 }}>
            {/* Email Input */}
            <View style={{ marginBottom: 20 }}>
              <TextInput
                placeholder="Enter your email"
                placeholderTextColor="#9ca3af"
                value={email}
                onChangeText={(text) => {
                  setEmail(text);
                  if (emailError) setEmailError(''); // Clear error when typing
                }}
                style={{
                  borderWidth: 1.5,
                  borderColor: emailError ? '#ef4444' : '#d1d5db',
                  backgroundColor: '#F9FAFB',
                  borderRadius: 12,
                  paddingHorizontal: 16,
                  height: 55,
                  color: '#111827',
                  fontSize: 15,
                }}
                keyboardType="email-address"
                autoCapitalize="none"
                autoCorrect={false}
              />
              {/* Show email error if any */}
              {emailError ? (
                <Text
                  style={{
                    color: '#ef4444',
                    fontSize: 12,
                    marginTop: 6,
                    marginLeft: 4,
                  }}
                >
                  {emailError}
                </Text>
              ) : null}
            </View>

            {/* Send Reset Link Button */}
            <TouchableOpacity
              style={{
                backgroundColor: '#0B4D2E',
                paddingVertical: 15,
                borderRadius: 12,
                alignItems: 'center',
                shadowColor: '#0B4D2E',
                shadowOffset: { width: 0, height: 4 },
                shadowOpacity: 0.3,
                shadowRadius: 6,
                elevation: 5,
                opacity: loading ? 0.6 : 1,
              }}
              onPress={handleSendReset}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <Text style={{ fontSize: 16, fontWeight: '600', color: '#fff' }}>
                  Send Reset Link
                </Text>
              )}
            </TouchableOpacity>

            {/* Back to Login */}
            <TouchableOpacity
              onPress={() => router.back()}
              style={{ marginTop: 20, alignItems: 'center' }}
            >
              <Text
                style={{
                  fontSize: 14,
                  fontWeight: '600',
                  color: '#0B4D2E',
                }}
              >
                Back to Login
              </Text>
            </TouchableOpacity>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </View>
  );
}