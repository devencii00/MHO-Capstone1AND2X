import AsyncStorage from '@react-native-async-storage/async-storage';

const TOKEN_KEY = '@patient_access_token';
const PATIENT_KEY = '@patient_data';

export const storage = {
  saveToken: async (token: string): Promise<void> => {
    try {
      await AsyncStorage.setItem(TOKEN_KEY, token);
      console.log(' Token saved');
    } catch (error) {
      console.error('Error saving token:', error);
    }
  },

  getToken: async (): Promise<string | null> => {
    try {
      const token = await AsyncStorage.getItem(TOKEN_KEY);
      return token;
    } catch (error) {
      console.error('Error getting token:', error);
      return null;
    }
  },

  removeToken: async (): Promise<void> => {
    try {
      await AsyncStorage.removeItem(TOKEN_KEY);
      console.log('Token removed');
    } catch (error) {
      console.error('Error removing token:', error);
    }
  },

  savePatient: async (patient: any): Promise<void> => {
    try {
      await AsyncStorage.setItem(PATIENT_KEY, JSON.stringify(patient));
      console.log(' Patient data saved');
    } catch (error) {
      console.error('Error saving patient:', error);
    }
  },

  getPatient: async (): Promise<any | null> => {
    try {
      const data = await AsyncStorage.getItem(PATIENT_KEY);
      return data ? JSON.parse(data) : null;
    } catch (error) {
      console.error('Error getting patient:', error);
      return null;
    }
  },

  removePatient: async (): Promise<void> => {
    try {
      await AsyncStorage.removeItem(PATIENT_KEY);
    } catch (error) {
      console.error('Error removing patient:', error);
    }
  },

  clearAll: async (): Promise<void> => {
    try {
      await AsyncStorage.multiRemove([TOKEN_KEY, PATIENT_KEY]);
    } catch (error) {
      console.error('Error clearing storage:', error);
    }
  },
};
export default storage;