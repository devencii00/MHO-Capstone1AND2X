import axios from 'axios';
import { storage } from './storage';

const API_HOST = '10.155.219.180';   // ← usa ra ka lugar diin i-usab ang IP
const API_URL = `http://${API_HOST}:8000/api`;

export { API_HOST };

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 30000,
});


api.interceptors.request.use(
  async (config) => {
    const token = await storage.getToken();

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    console.log(` ${config.method?.toUpperCase()} ${config.url}`);
    return config;
  },
  (error) => {
    console.error('Request Error:', error);
    return Promise.reject(error);
  }
);


api.interceptors.response.use(
  (response) => {
    console.log(`${response.status} ${response.config.url}`);
    return response;
  },
  async (error) => {
    console.error('API Error:', error.message);
    if (error.code === 'ECONNABORTED') {
      console.error('Request timeout');
    } else if (error.message === 'Network Error') {
      console.error('Cannot connect to server');
      console.error(`Check: ${API_URL}`);
    }

    if (error.response?.status === 401) {
      await storage.clearAll();
    }

    return Promise.reject(error);
  }
);

export async function updateFcmToken(patientId: number, fcmToken: string) {
  try {
    const response = await api.post('/patient/update-fcm-token', {
      patient_id: patientId,
      fcm_token: fcmToken,
    });
    return response.data;
  } catch (error) {
    console.error('Failed to update FCM token:', error);
    throw error;
  }
}

export const authAPI = {
  register: async (data: {
    username: string;
    email: string;
    password: string;
    password_confirmation: string;
  }) => {
    const response = await api.post('/patient/register-simple', data);
    return response.data;
  },

  login: async (login: string, password: string) => {
    const response = await api.post('/patient/login', {
      login,
      password,
    });
    return response.data;
  },

  logout: async () => {
    const response = await api.post('/patient/logout');
    return response.data;
  },

  forgotPassword: async (email: string) => {
    const response = await api.post('/patient/forgot-password', {
      email,
    });
    return response.data;
  },

  resetPassword: async (token: string, password: string) => {
    const response = await api.post('/patient/reset-password', {
      token,
      password,
      password_confirmation: password,
    });
    return response.data;
  },

  getProfile: async () => {
    const response = await api.get('/patient/profile');
    return response.data;
  },

  updateProfile: async (data: any) => {
    const response = await api.post('/patient/profile', data);
    return response.data;
  },

  changePassword: async (
    current_password: string,
    new_password: string,
    new_password_confirmation: string
  ) => {
    const response = await api.post('/patient/change-password', {
      current_password,
      new_password,
      new_password_confirmation,
    });
    return response.data;
  },

  updateProfileFormData: async (formData: FormData) => {
    const response = await api.post('/patient/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
      },
    });
    return response.data;
  },

  getDashboard: async () => {
    const response = await api.get('/patient/dashboard');
    return response.data;
  },

  getServices: async () => {
    const response = await api.get('/services');
    return response.data;
  },

  bookAppointment: async (data: {
    service_id: number;
    appointment_date: string;
    appointment_time: string;
    notes?: string;
  }) => {
    const response = await api.post('/patient/appointments', data);
    return response.data;
  },

  getMyAppointments: async () => {
    const response = await api.get('/patient/appointments');
    return response.data;
  },

  cancelAppointment: async (id: number, reason?: string) => {
    const response = await api.post(`/patient/appointments/${id}/cancel`, {
      reason,
    });
    return response.data;
  },

  updateDeviceToken: async (device_token: string) => {
    const response = await api.post('/patient/fcm-token', {
      device_token,
    });
    return response.data;
  },

  uploadValidId: async (formData: FormData) => {
    const response = await api.post('/patient/upload-id', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data;
  },
};

export default api;