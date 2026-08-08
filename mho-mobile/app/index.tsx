import React, { useEffect, useState } from 'react';
import { 
  View, 
  Text, 
  TouchableOpacity, 
  ImageBackground, 
  ScrollView,
  ActivityIndicator,
} from 'react-native';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function WelcomeScreen() {
  const router = useRouter();
  const [checkingAuth, setCheckingAuth] = useState(true);

  useEffect(() => {
    checkExistingLogin();
  }, []);

  const checkExistingLogin = async () => {
    try {
      const token     = await AsyncStorage.getItem('token');
      const patientId = await AsyncStorage.getItem('patient_id');
      if (token && patientId) {
        // Already logged in — skip welcome/login, go straight to dashboard
        router.replace('/(main)/(tabs)/dashboard');
        return;
      }
    } catch (error) {
      console.error('Auth check error:', error);
    } finally {
      setCheckingAuth(false);
    }
  };

  // While checking AsyncStorage, show a blank/loading state instead of
  // flashing the welcome screen for a split second.
  if (checkingAuth) {
    return (
      <View style={{ flex: 1, backgroundColor: '#065f46', alignItems: 'center', justifyContent: 'center' }}>
        <ActivityIndicator size="large" color="#FFFFFF" />
      </View>
    );
  }

  return (
    <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
      <ImageBackground 
        source={require('../assets/images/bas.jpg')}
        className="w-full h-full "
        resizeMode="cover"
      >
        <View className="items-center justify-between flex-1 px-5 py-5">
          <View className="flex-1" />

          {/* Auth Buttons */}
          <View className="w-full mb-10">
            {/* Login Button */}
            <TouchableOpacity
              className="items-center w-full py-4 mb-3 rounded-full shadow-lg bg-emerald-600 shadow-emerald-600/30"
              onPress={() => router.push('/(auth)/login')}
              activeOpacity={0.9}
            >
              <Text className="text-lg font-bold text-white">Login</Text>
            </TouchableOpacity>

            {/* Register Button */}
            <TouchableOpacity
              className="items-center w-full py-4 border-2 rounded-full border-emerald-600 bg-white/90"
              onPress={() => router.push('/(auth)/register')}
              activeOpacity={0.9}
            >
              <Text className="text-lg font-bold text-emerald-600">Register</Text>
            </TouchableOpacity>
          </View>
        </View>
      </ImageBackground>
    </ScrollView>
  );
}