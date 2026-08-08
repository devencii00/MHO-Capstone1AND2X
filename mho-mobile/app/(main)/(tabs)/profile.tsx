import React, { useState, useEffect } from 'react';
import {
  View, Text, ScrollView, Alert, TouchableOpacity,
  ActivityIndicator, Image, StatusBar, Modal,
  Platform,
} from 'react-native';
import { useRouter } from 'expo-router';
import * as ImagePicker from 'expo-image-picker';
import { Ionicons } from '@expo/vector-icons';
import Svg, { Path } from 'react-native-svg';
import { authAPI } from '../../lib/api';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useProfile } from '../../context/ProfileContext';

export default function ProfileScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [profile, setProfile] = useState<any>(null);
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const [logoutLoading, setLogoutLoading] = useState(false);

  // resetProfileState (dili clearProfileImage) ang gamiton sa logout,
  // para magpabilin ang saved image sa AsyncStorage per-user.
  const { profileImage, setProfileImage, refreshProfileImage, resetProfileState } = useProfile();

  useEffect(() => {
    loadProfile();
  }, []);

  const loadProfile = async () => {
    try {
      setLoading(true);
      const response = await authAPI.getProfile();

      if (response.success) {
        const patientData = response.data.patient || response.data;
        setProfile(patientData);
        await refreshProfileImage();
      } else {
        Alert.alert('Error', response.message);
      }
    } catch (error) {
      Alert.alert('Error', 'Failed to load profile');
    } finally {
      setLoading(false);
    }
  };

  const pickImage = async () => {
    try {
      const result = await ImagePicker.launchImageLibraryAsync({
        mediaTypes: ImagePicker.MediaTypeOptions.Images,
        allowsEditing: true,
        aspect: [1, 1],
        quality: 0.8,
      });

      if (!result.canceled) {
        const uri = result.assets[0].uri;
        await setProfileImage(uri);
      }
    } catch (error) {
      console.error('Image picker error:', error);
      Alert.alert('Error', 'Failed to pick image');
    }
  };

  const getInitials = () => {
    const first = profile?.first_name?.charAt(0) || '';
    const last = profile?.last_name?.charAt(0) || '';
    const initials = (first + last).toUpperCase().trim();
    return initials || '👤';
  };

  const getDisplayName = () => {
    const first = profile?.first_name || '';
    const last = profile?.last_name || '';
    const fullName = `${first} ${last}`.trim();
    return fullName || 'Patient';
  };

  const getDisplayEmail = () => {
    if (profile?.email && profile.email !== 'Not set') return profile.email;
    if (profile?.username && profile.username !== 'Not set') return profile.username;
    return 'No email set';
  };

  const handleLogout = async () => {
    if (logoutLoading) return;

    setLogoutLoading(true);
    try {
      try {
        await authAPI.logout();
      } catch (e) {
        console.log('Logout API error:', e);
      }

      // Clears only the in-memory display (tab icon + this screen) so the
      // next person who logs in on this device doesn't see this user's photo.
      // The saved image itself stays in AsyncStorage under this patient's own
      // key and will reload automatically next time THEY log in.
      resetProfileState();

      // Remove auth-related keys
      const authKeysToRemove = [
        'token',
        'patientFirstName',
        'patientLastName',
        'patientName',
        'patientId',
        'userData',
        'patient',
        'patient_id',
      ];
      await AsyncStorage.multiRemove(authKeysToRemove);

      setShowLogoutModal(false);

      setTimeout(() => {
        router.replace('/(auth)/login');
      }, 100);

    } catch (error) {
      console.error('Logout error:', error);
      Alert.alert('Error', 'Failed to logout. Please try again.');
    } finally {
      setLogoutLoading(false);
    }
  };

  const menuItems = [
    {
      id: 1,
      title: 'Profile',
      subtitle: 'View and edit your personal information',
      icon: 'person-outline',
      onPress: () => router.push('/(main)/profile/edit'),
    },
    {
      id: 2,
      title: 'Billing ',
      subtitle: 'View your billing',
      icon: 'document-text-outline',
      onPress: () => router.push('/(main)/billing'),
    },
    {
      id: 3,
      title: 'Queue',
      subtitle: 'View your queue',
      icon: 'document-text-outline',
      onPress: () => router.push('/(main)/(tabs)/queue'),
    },
    {
      id: 4,
      title: 'Result',
      subtitle: 'View your results and status',
      icon: 'clipboard-outline',
      onPress: () => router.push('/(main)/result'),
    },
    {
      id: 5,
      title: 'Notification',
      subtitle: 'View and manage your notifications',
      icon: 'notifications-outline',
    },

    {
      id: 6,
      title: 'Logout',
      subtitle: 'Sign out from your account',
      icon: 'log-out-outline',
      onPress: () => setShowLogoutModal(true),
    },
  ];

  if (loading) {
    return (
      <View className="items-center justify-center flex-1 bg-green-700">
        <ActivityIndicator size="large" color="#fff" />
        <Text className="mt-3 text-green-100">Loading...</Text>
      </View>
    );
  }

  return (
    <View className="flex-1 bg-gray-50">
      <StatusBar barStyle="light-content" backgroundColor="#15803d" />

      <ScrollView showsVerticalScrollIndicator={false} bounces={false}>
        {/* Green header with diagonal wave */}
        <View className="relative bg-[#065f46]">
          <View style={{ paddingTop: Platform.OS === 'ios' ? 60 : 40 }}>
            <View className="flex-row items-center justify-between w-full px-8 mb-3">
              <View className="w-8" />
              <TouchableOpacity className="items-center justify-center w-8 h-8">
                <Ionicons name="settings-outline" size={22} color="#fff" />
              </TouchableOpacity>
            </View>

            {/* Avatar with camera badge */}
            <View className="items-center">
              <View className="relative">
                {profileImage ? (
                  <Image
                    source={{ uri: profileImage }}
                    className="w-24 h-24 border-4 border-white rounded-full"
                  />
                ) : (
                  <View className="items-center justify-center w-24 h-24 bg-green-500 border-4 border-white rounded-full">
                    <Text className="text-3xl font-bold text-white">{getInitials()}</Text>
                  </View>
                )}
                <TouchableOpacity
                  onPress={pickImage}
                  className="absolute bottom-0 right-0 items-center justify-center w-8 h-8 bg-green-600 border-2 border-white rounded-full"
                >
                  <Ionicons name="camera" size={16} color="#fff" />
                </TouchableOpacity>
              </View>

              <Text className="mt-3 text-lg font-bold text-white">
                {getDisplayName()}
              </Text>
              <Text className="mt-1 text-sm text-green-200">
                {getDisplayEmail()}
              </Text>
            </View>

            <View style={{ height: 50 }} />
          </View>

          <Svg
            width="100%"
            height="60"
            viewBox="0 0 1440 120"
            preserveAspectRatio="none"
            style={{ position: 'absolute', bottom: -1, left: 0, right: 0 }}
          >
            <Path
              fill="#f9fafb"
              d="M0,60 C240,100 480,20 720,40 C960,60 1200,100 1440,50 L1440,120 L0,120 Z"
            />
          </Svg>
        </View>

        {/* Menu list */}
        <View className="px-4 pt-8 pb-10">
          {menuItems.map((item) => (
            <TouchableOpacity
              key={item.id}
              onPress={item.onPress}
              className="flex-row items-center p-4 mb-3 bg-white rounded-2xl"
              style={{
                shadowColor: '#000',
                shadowOffset: { width: 0, height: 1 },
                shadowOpacity: 0.05,
                shadowRadius: 3,
                elevation: 1,
              }}
            >
              <View className="items-center justify-center mr-3 w-11 h-11 bg-green-50 rounded-xl">
                <Ionicons name={item.icon as any} size={20} color="#16A34A" />
              </View>
              <View className="flex-1">
                <Text className="text-base font-semibold text-gray-900">
                  {item.title}
                </Text>
                <Text className="mt-0.5 text-xs text-gray-500">
                  {item.subtitle}
                </Text>
              </View>
              <Ionicons name="chevron-forward" size={18} color="#16A34A" />
            </TouchableOpacity>
          ))}
        </View>
      </ScrollView>

      {/* LOGOUT CONFIRMATION MODAL */}
      <Modal
        visible={showLogoutModal}
        transparent
        animationType="fade"
        onRequestClose={() => {
          if (!logoutLoading) setShowLogoutModal(false);
        }}
      >
        <View className="items-center justify-center flex-1 bg-black/50">
          <View className="w-[85%] max-w-sm rounded-3xl p-8 items-center bg-white">
            <Text className="mb-2 text-lg font-bold text-gray-800">
              Log out?
            </Text>
            <Text className="mb-6 text-sm text-center text-gray-500">
              Are you sure you want to log out?
            </Text>

            <View className="flex-row w-full gap-3">
              <TouchableOpacity
                onPress={() => {
                  if (!logoutLoading) setShowLogoutModal(false);
                }}
                disabled={logoutLoading}
                className="flex-1 py-3 border border-gray-200 rounded-xl"
              >
                <Text className="font-semibold text-center text-gray-600">
                  Cancel
                </Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={handleLogout}
                disabled={logoutLoading}
                className={`flex-1 py-3 rounded-xl ${logoutLoading ? 'bg-red-300' : 'bg-red-500'}`}
              >
                {logoutLoading ? (
                  <ActivityIndicator size="small" color="#fff" />
                ) : (
                  <Text className="font-bold text-center text-white">
                    Yes, Log out
                  </Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}