import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { authAPI } from '../lib/api'; //  i-adjust ang path base sa imong folder structure

interface ProfileContextType {
  profileImage: string | null;
  patientId: string;
  setProfileImage: (uri: string | null) => Promise<void>;
  refreshProfileImage: () => Promise<void>;
  clearProfileImage: () => Promise<void>; // deletes the CURRENT user's saved image permanently (e.g. "remove photo" button)
  resetProfileState: () => void; // clears only the in-memory display state on logout — does NOT delete AsyncStorage data
}

const ProfileContext = createContext<ProfileContextType | undefined>(undefined);

export function ProfileProvider({ children }: { children: ReactNode }) {
  const [profileImage, setProfileImageState] = useState<string | null>(null);
  const [patientId, setPatientId] = useState<string>('');

  const getKey = (pId: string) => `profile_image_${pId}`;

  // Fetches the logged-in patient's id, then loads THAT patient's saved image
  // from AsyncStorage using their unique key. Different patient = different key
  // = they only ever see their own picture.
  const refreshProfileImage = async () => {
    try {
      const response = await authAPI.getProfile();
      if (response.success) {
        const patientData = response.data.patient || response.data;
        const pId = String(patientData?.id || patientData?.patient_id || '');
        setPatientId(pId);

        if (pId) {
          const savedImage = await AsyncStorage.getItem(getKey(pId));
          setProfileImageState(savedImage || null);
        }
      }
    } catch (error) {
      console.log('Failed to refresh profile image:', error);
    }
  };

  // Saves/updates the current patient's image — used when picking a new photo.
  const setProfileImage = async (uri: string | null) => {
    setProfileImageState(uri);

    if (patientId) {
      if (uri) {
        await AsyncStorage.setItem(getKey(patientId), uri);
      } else {
        await AsyncStorage.removeItem(getKey(patientId));
      }
    }
  };

  // Permanently deletes the CURRENT patient's saved image from AsyncStorage.
  // Use this only for an explicit "remove my photo" action — NOT for logout.
  const clearProfileImage = async () => {
    if (patientId) {
      await AsyncStorage.removeItem(getKey(patientId));
    }
    setProfileImageState(null);
    setPatientId('');
  };

  //  Use this on logout instead of clearProfileImage().
  // Only wipes the in-memory state (so the tab icon/screen don't keep showing
  // the previous user's photo) — the saved image stays safely in AsyncStorage
  // under that patient's own key, ready to reappear next time they log in.
  const resetProfileState = () => {
    setProfileImageState(null);
    setPatientId('');
  };

  useEffect(() => {
    refreshProfileImage();
  }, []);

  return (
    <ProfileContext.Provider
      value={{
        profileImage,
        patientId,
        setProfileImage,
        refreshProfileImage,
        clearProfileImage,
        resetProfileState,
      }}
    >
      {children}
    </ProfileContext.Provider>
  );
}

export function useProfile() {
  const context = useContext(ProfileContext);
  if (!context) {
    throw new Error('useProfile must be used within a ProfileProvider');
  }
  return context;
}