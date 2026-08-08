export interface PatientAccount {
  id: number;
  username: string;
  email: string;
  status: string;
  created_at: string;
}

export interface PatientProfile {
  id: number;
  first_name: string;
  last_name: string;
  middle_name?: string;
  date_of_birth?: string;
  gender?: string;
  address?: string;
  emergency_contact?: string;
  patient_type: string;
  medical_history?: string;
  allergies?: string;
  valid_id_path?: string;
  valid_id_type?: string;
  status: string;
}

export interface AuthResponse {
  success: boolean;
  data: {
    account: PatientAccount;
    patient: PatientProfile;
    access_token: string;
    token_type: string;
  };
  message: string;
}

export interface RegisterData {
  username: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface LoginData {
  login: string;
  password: string;
}

export interface UpdateProfileData {
  first_name?: string;
  last_name?: string;
  middle_name?: string;
  date_of_birth?: string;
  gender?: string;
  address?: string;
  emergency_contact?: string;
  patient_type?: string;
  medical_history?: string;
  allergies?: string;
}

export interface Service {
  id: number;
  name: string;
  category: string;
  price: number;
  duration_minutes?: number;
  description: string | null;
  image?: string;
}

export interface CartItem {
  id: number;
  name: string;
  price: number;
  duration: number;
}

