import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  StatusBar,
  SafeAreaView,
  Image,
  Modal,
  FlatList,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import * as ImagePicker from 'expo-image-picker';
import * as FileSystem from 'expo-file-system/legacy';
import { authAPI } from '../../lib/api';
import AsyncStorage from '@react-native-async-storage/async-storage';

const colors = {
  primary: '#10B981',
  primaryDark: '#1D4ED8',
  primaryLight: '#60A5FA',
  white: '#FFFFFF',
  dark: '#1F2937',
  gray: '#6B7280',
  lightGray: '#F9FAFB',
  border: '#E5E7EB',
  danger: '#EF4444',
  success: '#10B981',
  warning: '#F59E0B',
  info: '#3B82F6',
  // nEW: dark green used by the new header, matching the app's other
  // dark-green headers (e.g. Queue screen).
  headerGreen: '#065f46',
};

//  NEW: base API URL used for the direct-fetch upload call below.
// Keep this in sync with whatever base URL your lib/api.ts (authAPI) uses.
const API_BASE_URL = 'http://10.155.219.180:8000/api';

// ===== Dropdown option sets =====
const GENDER_OPTIONS = [
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' },
];

const PATIENT_TYPE_OPTIONS = [
  { label: 'Regular', value: 'regular' },
  { label: 'Senior Citizen', value: 'senior_citizen' },
  { label: 'PWD', value: 'pwd' },
  { label: 'Pregnant', value: 'pregnant' },
];

// ===== Single shape for all form fields =====
interface ProfileForm {
  first_name: string;
  last_name: string;
  middle_name: string;
  date_of_birth: string;
  gender: string;
  address: string;
  emergency_contact: string;
  username: string;
  email: string;
  phone: string;
  age: string;
  patient_type: string;
  medical_history: string;
  allergies: string;
  valid_id_path: string | null;
}

const EMPTY_FORM: ProfileForm = {
  first_name: '',
  last_name: '',
  middle_name: '',
  date_of_birth: '',
  gender: '',
  address: '',
  emergency_contact: '',
  username: '',
  email: '',
  phone: '',
  age: '',
  patient_type: '',
  medical_history: '',
  allergies: '',
  valid_id_path: null,
};

// ===== Config-driven dropdown fields =====
type DropdownKey = 'gender' | 'patient_type';

const DROPDOWN_FIELDS: Record<DropdownKey, { title: string; options: { label: string; value: string }[] }> = {
  gender: { title: 'Select Gender', options: GENDER_OPTIONS },
  patient_type: { title: 'Select Patient Type', options: PATIENT_TYPE_OPTIONS },
};

const VALID_ID_REQUIRED_TYPES = ['senior_citizen', 'pwd'];

// ===== ID verification status =====
type IdStatus = 'idle' | 'verifying' | 'valid' | 'invalid';

// Quick client-side quality/blur heuristic.
// A genuinely sharp photo of a printed ID has a lot of fine detail (text,
// edges), which JPEG compression can't squeeze down much — so sharp photos
// end up with a healthy bytes-per-pixel ratio. Blurry / very low-quality
// photos are smooth and compress much harder, ending up with unusually low
// bytes-per-pixel for their resolution. This is a fast, no-dependency proxy —
// it is NOT a substitute for real CV/ML blur detection. If/when there's a
// backend endpoint for that (e.g. OpenCV Laplacian variance, or an OCR/ID
// verification service), swap the body of this function for an API call to
// authAPI.verifyValidId(uri) and keep the same return shape.
async function verifyValidId(
  uri: string,
  width: number,
  height: number
): Promise<{ valid: boolean; reason?: string }> {
  try {
    // Note: Some Expo type definitions may not include the optional
    // 'size' flag on InfoOptions. Cast to any to preserve runtime behavior
    // and avoid a TypeScript type error.
    const info = await FileSystem.getInfoAsync(uri, ({ size: true } as any));
    if (!info.exists || !('size' in info) || !info.size) {
      return { valid: false, reason: "Couldn't read the image file. Please try again." };
    }

    const pixelCount = width * height;
    if (!pixelCount) {
      return { valid: false, reason: 'Could not read image dimensions. Please try another photo.' };
    }

    const bytesPerPixel = info.size / pixelCount;

    // Tunable threshold — raise it if you're getting false "blurry" rejections,
    // lower it if obviously blurry photos are slipping through.
    const MIN_BYTES_PER_PIXEL = 0.08;
    const MIN_DIMENSION = 600; // reject tiny/low-res photos too

    if (width < MIN_DIMENSION && height < MIN_DIMENSION) {
      return { valid: false, reason: 'Image resolution is too low. Please take a closer, clearer photo.' };
    }

    if (bytesPerPixel < MIN_BYTES_PER_PIXEL) {
      return {
        valid: false,
        reason: 'Dili madawat imong nahong sa camera palihog upload ug kanang sakto clear  ug madawat na nahong.kabalo ko na bati kag nawong',
      };
    }

    return { valid: true };
  } catch (err) {
    console.error('ID verification error:', err);
    return { valid: false, reason: 'Verification failed. Please try again.' };
  }
}

// Actually uploads the picked photo to the server and returns the
// server-stored path (e.g. "valid_ids/abc123.jpg") — this is the value
// that should end up in valid_id_path, NOT the local device file:// URI.
async function uploadValidId(uri: string): Promise<{ success: boolean; path?: string; message?: string }> {
  try {
    const token = await AsyncStorage.getItem('@patient_access_token');
    const formData = new FormData();
    formData.append('valid_id_image', {
      uri,
      name: 'valid_id.jpg',
      type: 'image/jpeg',
    } as any);

    const response = await fetch(`${API_BASE_URL}/patient/upload-valid-id`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
        // Do NOT set Content-Type manually for FormData — React Native
        // needs to generate the multipart boundary itself.
      },
      body: formData as any,
    });

    const data = await response.json();

    if (response.ok && data.success) {
      return { success: true, path: data.data.valid_id_path };
    }
    return { success: false, message: data.message || 'Upload failed.' };
  } catch (err) {
    console.error('Valid ID upload error:', err);
    return { success: false, message: 'Network error while uploading your ID. Please try again.' };
  }
}

export default function EditProfileScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  const [form, setForm] = useState<ProfileForm>(EMPTY_FORM);
  const [activeDropdown, setActiveDropdown] = useState<DropdownKey | null>(null);

  // Verification state for the Valid ID upload
  const [idStatus, setIdStatus] = useState<IdStatus>('idle');
  const [idErrorMessage, setIdErrorMessage] = useState<string>('');

  const updateField = <K extends keyof ProfileForm>(key: K, value: ProfileForm[K]) => {
    setForm(prev => ({ ...prev, [key]: value }));
  };

  const requiresValidId = VALID_ID_REQUIRED_TYPES.includes(form.patient_type);
  const patientTypeLabel = form.patient_type === 'senior_citizen' ? 'Senior Citizen' : 'PWD';

  const calculateAge = (birthDate: string) => {
    if (!birthDate) return '';
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
      age--;
    }
    return age.toString();
  };

  useEffect(() => {
    loadProfile();
  }, []);

  const loadProfile = async () => {
    try {
      setLoading(true);
      const response = await authAPI.getProfile();

      if (response.success) {
        const patientData = response.data?.patient || response.data || response;

        // ✅ FIX: age was only ever computed inside handleDateOfBirthChange,
        // which fires only when the user actively types into the Date of
        // Birth field. If date_of_birth already had a value from the
        // database and the user never touched that field this session,
        // form.age stayed '' the whole time — so it never got included in
        // the save payload and the "age" column stayed NULL forever, even
        // though a date_of_birth was clearly on file.
        // Now we derive age from date_of_birth as soon as the profile
        // loads, falling back to whatever age the backend already stored
        // (if any) only when there's no date_of_birth to compute from.
        const dob = patientData.date_of_birth
          ? String(patientData.date_of_birth).split('T')[0]
          : '';
        const derivedAge = dob ? calculateAge(dob) : '';

        setForm({
          first_name: patientData.first_name || '',
          last_name: patientData.last_name || '',
          middle_name: patientData.middle_name || '',
          date_of_birth: dob,
          gender: patientData.gender || '',
          address: patientData.address || '',
          emergency_contact: patientData.emergency_contact || '',
          username: patientData.username || '',
          email: patientData.email || '',
          phone: patientData.phone || '',
          age: derivedAge || (patientData.age ? String(patientData.age) : ''),
          patient_type: patientData.patient_type || 'regular',
          medical_history: patientData.medical_history || '',
          allergies: patientData.allergies || '',
          valid_id_path: patientData.valid_id_path || null,
        });

        // If they already have a saved, previously-uploaded ID, treat it as valid
        // (it was presumably already verified before / on a prior save).
        if (patientData.valid_id_path) {
          setIdStatus('valid');
        }
      } else {
        Alert.alert('Error', response.message || 'Failed to load profile');
      }
    } catch (error: any) {
      console.error('Load profile error:', error);
      Alert.alert('Error', 'Failed to load profile');
    } finally {
      setLoading(false);
    }
  };

  // Picks an image, then runs the verification step with a loading state
  // before accepting it. Blurry/low-quality photos get rejected.
  const pickValidIdImage = async () => {
    const permissionResult = await ImagePicker.requestMediaLibraryPermissionsAsync();

    if (!permissionResult.granted) {
      Alert.alert('Permission Required', 'Please allow access to your photo library');
      return;
    }

    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [16, 9],
      quality: 0.8,
    });

    if (result.canceled) return;

    const asset = result.assets[0];

    // Clear any previous file while we verify the new one
    updateField('valid_id_path', asset.uri);
    setIdStatus('verifying');
    setIdErrorMessage('');

    const check = await verifyValidId(asset.uri, asset.width ?? 0, asset.height ?? 0);

    if (check.valid) {
      setIdStatus('valid');
    } else {
      setIdStatus('invalid');
      setIdErrorMessage(check.reason || 'This photo could not be verified. Please try another one.');
    }
  };

  const removeValidId = () => {
    updateField('valid_id_path', null);
    setIdStatus('idle');
    setIdErrorMessage('');
  };

  const handleDateOfBirthChange = (text: string) => {
    const calculatedAge = calculateAge(text);
    setForm(prev => ({
      ...prev,
      date_of_birth: text,
      age: calculatedAge || prev.age,
    }));
  };

  const getOptionLabel = (key: DropdownKey, value: string, placeholder: string) => {
    const option = DROPDOWN_FIELDS[key].options.find(opt => opt.value === value);
    return option ? option.label : placeholder;
  };

  const handleSave = async () => {
    if (!form.first_name.trim()) { Alert.alert('Error', 'First name is required'); return; }
    if (!form.last_name.trim()) { Alert.alert('Error', 'Last name is required'); return; }
    if (!form.email.trim()) { Alert.alert('Error', 'Email is required'); return; }
    if (!form.phone.trim()) { Alert.alert('Error', 'Phone number is required'); return; }

    // Block save while verifying, and require idStatus === 'valid'
    // (not just "a file exists") before letting a Senior/PWD save.
    if (requiresValidId) {
      if (idStatus === 'verifying') {
        Alert.alert('Please wait', 'Still verifying your Valid ID.');
        return;
      }
      if (idStatus !== 'valid') {
        Alert.alert(
          'Valid ID Required',
          `Please upload a clear, non-blurry valid ID for ${patientTypeLabel} classification.`
        );
        return;
      }
    }

    setSaving(true);

    try {
      // ✅ Defensive re-derive right before saving too — guarantees age is
      // always in sync with date_of_birth at save time even if some future
      // code path sets date_of_birth without going through
      // handleDateOfBirthChange.
      const finalAge = form.date_of_birth ? calculateAge(form.date_of_birth) : form.age;

      const trimmed: Record<string, any> = {
        first_name: form.first_name.trim(),
        last_name: form.last_name.trim(),
        middle_name: form.middle_name.trim(),
        email: form.email.trim().toLowerCase(),
        phone: form.phone.trim(),
        username: form.username.trim().toLowerCase(),
        gender: form.gender,
        address: form.address.trim(),
        emergency_contact: form.emergency_contact.trim(),
        date_of_birth: form.date_of_birth,
        age: finalAge ? parseInt(finalAge, 10) : undefined,
        patient_type: form.patient_type || 'regular',
        medical_history: form.medical_history.trim(),
        allergies: form.allergies.trim(),
        valid_id_path: form.valid_id_path,
      };

      const formData: Record<string, any> = {};
      Object.entries(trimmed).forEach(([key, value]) => {
        if (value !== '' && value !== null && value !== undefined) {
          formData[key] = value;
        }
      });

      console.log('SENDING DATA:', JSON.stringify(formData, null, 2));

      const response = await authAPI.updateProfile(formData);

      if (response.success) {
        const fullName = `${form.first_name.trim()} ${form.last_name.trim()}`;
        await AsyncStorage.setItem('patientName', fullName);

        const userData = {
          name: fullName,
          first_name: form.first_name.trim(),
          last_name: form.last_name.trim(),
          email: form.email.trim(),
          phone: form.phone.trim(),
        };
        await AsyncStorage.setItem('userData', JSON.stringify(userData));

        Alert.alert('Success', 'Profile updated successfully', [
          { text: 'OK', onPress: () => router.replace('../(tabs)/dashboard') },
        ]);
      } else {
        Alert.alert('Error', response.message || 'Failed to update profile');
      }
    } catch (error: any) {
      console.error('Update error:', error);

      if (error.response?.data) {
        const errors = error.response.data;
        let errorMessage = 'Validation failed:\n\n';

        if (errors.errors) {
          Object.keys(errors.errors).forEach(key => {
            errorMessage += `• ${key}: ${errors.errors[key]}\n`;
          });
        } else if (typeof errors === 'object') {
          Object.keys(errors).forEach(key => {
            if (key !== 'success' && key !== 'message') {
              errorMessage += `• ${key}: ${JSON.stringify(errors[key])}\n`;
            }
          });
        }

        if (errorMessage === 'Validation failed:\n\n') {
          errorMessage = errors.message || JSON.stringify(errors);
        }

        Alert.alert('Error', errorMessage);
      } else {
        Alert.alert('Error', error.message || 'Network error');
      }
    } finally {
      setSaving(false);
    }
  };

  const DropdownField = ({
    fieldKey,
    label,
    required,
    placeholder,
  }: {
    fieldKey: DropdownKey;
    label: string;
    required?: boolean;
    placeholder: string;
  }) => {
    const value = form[fieldKey];
    return (
      <View style={styles.inputGroup}>
        <Text style={styles.label}>
          {label} {required && <Text style={styles.required}>*</Text>}
        </Text>
        <TouchableOpacity style={styles.dropdown} onPress={() => setActiveDropdown(fieldKey)}>
          <Text style={[styles.dropdownText, !value && styles.placeholderText]}>
            {getOptionLabel(fieldKey, value, placeholder)}
          </Text>
          <Ionicons name="chevron-down" size={20} color={colors.gray} />
        </TouchableOpacity>
      </View>
    );
  };

  const renderDropdownModal = () => {
    const config = activeDropdown ? DROPDOWN_FIELDS[activeDropdown] : null;
    return (
      <Modal
        visible={!!activeDropdown}
        transparent
        animationType="slide"
        onRequestClose={() => setActiveDropdown(null)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>{config?.title}</Text>
              <TouchableOpacity onPress={() => setActiveDropdown(null)}>
                <Ionicons name="close" size={24} color={colors.dark} />
              </TouchableOpacity>
            </View>
            <FlatList
              data={config?.options || []}
              keyExtractor={(item, index) => `${item.value}-${index}`}
              renderItem={({ item }) => {
                const selected = activeDropdown && form[activeDropdown] === item.value;
                return (
                  <TouchableOpacity
                    style={[styles.modalOption, selected && styles.modalOptionSelected]}
                    onPress={() => {
                      if (activeDropdown) updateField(activeDropdown, item.value);
                      setActiveDropdown(null);
                    }}
                  >
                    <Text style={[styles.modalOptionText, selected && styles.modalOptionTextSelected]}>
                      {item.label}
                    </Text>
                    {selected && <Ionicons name="checkmark" size={20} color={colors.primary} />}
                  </TouchableOpacity>
                );
              }}
            />
          </View>
        </View>
      </Modal>
    );
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.loadingContainer}>
        <StatusBar barStyle="dark-content" backgroundColor={colors.lightGray} />
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Loading profile...</Text>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      {/*  NEW: dark green header with a back arrow (left) and a centered
          "Patient Details" title. StatusBar switched to light-content since
          the header background is now dark. */}
      <StatusBar barStyle="light-content" backgroundColor={colors.headerGreen} />
      <View style={styles.header}>
        <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={22} color={colors.white} />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Patient Details</Text>
        {/* Empty spacer matching the back button's width, so the title
            stays visually centered instead of drifting toward the button. */}
        <View style={styles.placeholder} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} style={styles.content}>
        {/* ===== SECTION 1: PERSONAL INFORMATION ===== */}
        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <Ionicons name="person-outline" size={20} color={colors.primary} />
            <Text style={styles.cardTitle}>Personal Information</Text>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>First Name <Text style={styles.required}>*</Text></Text>
            <TextInput
              style={styles.input}
              value={form.first_name}
              onChangeText={(v) => updateField('first_name', v)}
              placeholder="Enter first name"
              placeholderTextColor={colors.gray}
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Middle Name</Text>
            <TextInput
              style={styles.input}
              value={form.middle_name}
              onChangeText={(v) => updateField('middle_name', v)}
              placeholder="Enter middle name"
              placeholderTextColor={colors.gray}
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Last Name <Text style={styles.required}>*</Text></Text>
            <TextInput
              style={styles.input}
              value={form.last_name}
              onChangeText={(v) => updateField('last_name', v)}
              placeholder="Enter last name"
              placeholderTextColor={colors.gray}
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Username</Text>
            <TextInput
              style={styles.input}
              value={form.username}
              onChangeText={(v) => updateField('username', v)}
              placeholder="Enter username"
              placeholderTextColor={colors.gray}
              autoCapitalize="none"
            />
          </View>
        </View>

        {/* ===== SECTION 2: CONTACT INFORMATION ===== */}
        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <Ionicons name="call-outline" size={20} color={colors.primary} />
            <Text style={styles.cardTitle}>Contact Information</Text>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Email Address <Text style={styles.required}>*</Text></Text>
            <TextInput
              style={styles.input}
              value={form.email}
              onChangeText={(v) => updateField('email', v)}
              placeholder="Enter email address"
              placeholderTextColor={colors.gray}
              keyboardType="email-address"
              autoCapitalize="none"
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Phone Number <Text style={styles.required}>*</Text></Text>
            <TextInput
              style={styles.input}
              value={form.phone}
              onChangeText={(v) => updateField('phone', v)}
              placeholder="Enter phone number"
              placeholderTextColor={colors.gray}
              keyboardType="phone-pad"
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Address</Text>
            <TextInput
              style={[styles.input, styles.textArea]}
              value={form.address}
              onChangeText={(v) => updateField('address', v)}
              placeholder="Enter complete address"
              placeholderTextColor={colors.gray}
              multiline
              numberOfLines={3}
            />
          </View>
        </View>

        {/* ===== SECTION 3: PERSONAL DETAILS ===== */}
        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <Ionicons name="calendar-outline" size={20} color={colors.primary} />
            <Text style={styles.cardTitle}>Personal Details</Text>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Date of Birth</Text>
            <TextInput
              style={styles.input}
              value={form.date_of_birth}
              onChangeText={handleDateOfBirthChange}
              placeholder="YYYY-MM-DD"
              placeholderTextColor={colors.gray}
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Age</Text>
            <TextInput
              style={[styles.input, styles.disabledInput]}
              value={form.age}
              editable={false}
              placeholder="Auto-calculated"
              placeholderTextColor={colors.gray}
            />
          </View>

          <DropdownField fieldKey="gender" label="Gender" placeholder="Select Gender" />
        </View>

        {/* ===== SECTION 4: PATIENT CLASSIFICATION ===== */}
        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <Ionicons name="medkit-outline" size={20} color={colors.primary} />
            <Text style={styles.cardTitle}>Patient Classification</Text>
          </View>

          <DropdownField fieldKey="patient_type" label="Patient Type" placeholder="Select Patient Type" />

          {requiresValidId && (
            <View style={styles.validIdSection}>
              <Text style={[styles.label, { marginBottom: 10 }]}>
                Valid ID <Text style={styles.required}>*</Text>
              </Text>

              {/* Verifying state */}
              {idStatus === 'verifying' && form.valid_id_path && (
                <View style={styles.idPreviewCard}>
                  <Image source={{ uri: form.valid_id_path }} style={[styles.idPreviewImage, { opacity: 0.4 }]} />
                  <View style={styles.idVerifyingOverlay}>
                    <ActivityIndicator size="small" color={colors.white} />
                    <Text style={styles.idVerifyingText}>Verifying ID...</Text>
                  </View>
                </View>
              )}

              {/* Invalid/blurry state */}
              {idStatus === 'invalid' && (
                <View style={styles.idErrorCard}>
                  {form.valid_id_path && (
                    <Image source={{ uri: form.valid_id_path }} style={styles.idErrorImage} />
                  )}
                  <View style={styles.idErrorBadge}>
                    <Ionicons name="close-circle" size={13} color={colors.danger} />
                    <Text style={styles.idErrorBadgeText}>Failed</Text>
                  </View>
                  <Text style={styles.idErrorText}>{idErrorMessage}</Text>
                  <TouchableOpacity style={styles.idRetryBtn} onPress={pickValidIdImage} activeOpacity={0.8}>
                    <Ionicons name="camera-outline" size={15} color={colors.white} />
                    <Text style={styles.idOverlayBtnText}>Try Again</Text>
                  </TouchableOpacity>
                </View>
              )}

              {/* Verified, valid state */}
              {idStatus === 'valid' && form.valid_id_path && (
                <View style={styles.idPreviewCard}>
                  <Image source={{ uri: form.valid_id_path }} style={styles.idPreviewImage} />
                  <View style={styles.idPreviewOverlay}>
                    <TouchableOpacity style={styles.idOverlayBtn} onPress={pickValidIdImage}>
                      <Ionicons name="camera-outline" size={15} color={colors.white} />
                      <Text style={styles.idOverlayBtnText}>Change</Text>
                    </TouchableOpacity>
                    <TouchableOpacity
                      style={[styles.idOverlayBtn, styles.idOverlayBtnDanger]}
                      onPress={removeValidId}
                    >
                      <Ionicons name="trash-outline" size={15} color={colors.white} />
                    </TouchableOpacity>
                  </View>
                  <View style={styles.idUploadedBadge}>
                    <Ionicons name="checkmark-circle" size={13} color={colors.success} />
                    <Text style={styles.idUploadedBadgeText}>Verified</Text>
                  </View>
                </View>
              )}

              {/* Idle / nothing uploaded yet */}
              {idStatus === 'idle' && (
                <TouchableOpacity style={styles.idUploadCard} onPress={pickValidIdImage} activeOpacity={0.8}>
                  <View style={styles.idUploadIconCircle}>
                    <Ionicons name="alert-circle-outline" size={24} color={colors.warning} />
                  </View>
                  <Text style={styles.idUploadTitle}>Upload Valid ID</Text>
                  <Text style={styles.idUploadSubtitle}>
                    Required to confirm your{' '}
                    <Text style={styles.idUploadSubtitleBold}>{patientTypeLabel}</Text> classification —
                    tap to choose a clear, non-blurry photo
                  </Text>
                </TouchableOpacity>
              )}
            </View>
          )}
        </View>

        {/* Bottom padding for FAB */}
        <View style={styles.bottomPadding} />
      </ScrollView>

      {/* Floating Action Button - Save at Bottom Right */}
      <TouchableOpacity
        style={[styles.fab, idStatus === 'verifying' && styles.fabDisabled]}
        onPress={handleSave}
        disabled={saving || idStatus === 'verifying'}
        activeOpacity={0.8}
      >
        {saving ? (
          <ActivityIndicator size="small" color={colors.white} />
        ) : (
          <>
            <Ionicons name="save-outline" size={20} color={colors.white} />
            <Text style={styles.fabText}>Save</Text>
          </>
        )}
      </TouchableOpacity>

      {renderDropdownModal()}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.lightGray },
  loadingContainer: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.lightGray },
  loadingText: { marginTop: 12, fontSize: 14, color: colors.gray },
  //  UPDATED: header is now dark green with a white back arrow and a
  // centered white title. headerTitle uses flex:1 + textAlign:'center' so
  // it centers relative to the row, and `placeholder` mirrors the back
  // button's width so the title doesn't skew toward the left.
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingVertical: 20,
    backgroundColor: colors.headerGreen,
    borderBottomWidth: 0,
  },
  backButton: { padding: 8, width: 40,marginTop: 20 },
  headerTitle: {
    flex: 1,
    textAlign: 'center',
    fontSize: 18,
    marginTop: 20,
    fontWeight: '700',
    color: colors.white,
  },
  placeholder: { width: 40 },
  content: { flex: 1, padding: 16 },
  bottomPadding: { height: 80 },
  card: {
    backgroundColor: colors.white,
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    marginBottom: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  cardTitle: { fontSize: 16, fontWeight: '600', color: colors.dark },
  inputGroup: { marginBottom: 16 },
  label: { fontSize: 14, fontWeight: '500', color: colors.dark, marginBottom: 8 },
  required: { color: colors.danger },
  input: {
    backgroundColor: colors.lightGray,
    borderRadius: 12,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 15,
    color: colors.dark,
    borderWidth: 1,
    borderColor: colors.border,
  },
  textArea: { height: 80, textAlignVertical: 'top' },
  disabledInput: { backgroundColor: '#F3F4F6', color: colors.gray },
  dropdown: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: colors.lightGray,
    borderRadius: 12,
    paddingHorizontal: 14,
    paddingVertical: 12,
    borderWidth: 1,
    borderColor: colors.border,
  },
  dropdownText: { fontSize: 15, color: colors.dark },
  placeholderText: { color: colors.gray },
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0, 0, 0, 0.5)', justifyContent: 'flex-end' },
  modalContent: { backgroundColor: colors.white, borderTopLeftRadius: 20, borderTopRightRadius: 20, maxHeight: '80%' },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  modalTitle: { fontSize: 18, fontWeight: '600', color: colors.dark },
  modalOption: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 16,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  modalOptionSelected: { backgroundColor: '#EFF6FF' },
  modalOptionText: { fontSize: 16, color: colors.dark },
  modalOptionTextSelected: { color: colors.primary, fontWeight: '500' },

  validIdSection: { marginBottom: 4 },
  idUploadCard: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#FFFBEB',
    borderRadius: 14,
    borderWidth: 1.5,
    borderColor: '#FBBF24',
    borderStyle: 'dashed',
    paddingVertical: 26,
    paddingHorizontal: 18,
    marginBottom: 16,
  },
  idUploadIconCircle: {
    width: 52,
    height: 52,
    borderRadius: 26,
    backgroundColor: colors.white,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.06,
    shadowRadius: 3,
    elevation: 1,
  },
  idUploadTitle: { fontSize: 14.5, fontWeight: '700', color: '#92400E', marginBottom: 4 },
  idUploadSubtitle: { fontSize: 12.5, lineHeight: 18, color: '#92400E', textAlign: 'center', opacity: 0.85 },
  idUploadSubtitleBold: { fontWeight: '700', opacity: 1 },
  idPreviewCard: {
    position: 'relative',
    borderRadius: 14,
    overflow: 'hidden',
    marginBottom: 16,
    backgroundColor: colors.lightGray,
    borderWidth: 1,
    borderColor: colors.border,
  },
  idPreviewImage: { width: '100%', height: 180, resizeMode: 'cover' },
  idPreviewOverlay: { position: 'absolute', top: 8, right: 8, flexDirection: 'row', gap: 6 },
  idOverlayBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: 'rgba(17, 24, 39, 0.65)',
    paddingHorizontal: 10,
    paddingVertical: 7,
    borderRadius: 20,
  },
  idOverlayBtnDanger: { backgroundColor: 'rgba(239, 68, 68, 0.85)', paddingHorizontal: 8 },
  idOverlayBtnText: { color: colors.white, fontSize: 12, fontWeight: '600' },
  idUploadedBadge: {
    position: 'absolute',
    bottom: 8,
    left: 8,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: 'rgba(255,255,255,0.92)',
    paddingHorizontal: 9,
    paddingVertical: 5,
    borderRadius: 20,
  },
  idUploadedBadgeText: { fontSize: 11, fontWeight: '700', color: colors.success },

  idVerifyingOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    backgroundColor: 'rgba(17, 24, 39, 0.35)',
  },
  idVerifyingText: { color: colors.white, fontSize: 13, fontWeight: '600' },

  idErrorCard: {
    borderRadius: 14,
    overflow: 'hidden',
    marginBottom: 16,
    backgroundColor: '#FEF2F2',
    borderWidth: 1.5,
    borderColor: colors.danger,
    padding: 14,
    alignItems: 'center',
  },
  idErrorImage: {
    width: '100%',
    height: 140,
    resizeMode: 'cover',
    borderRadius: 10,
    marginBottom: 10,
    opacity: 0.85,
  },
  idErrorBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: colors.white,
    paddingHorizontal: 9,
    paddingVertical: 5,
    borderRadius: 20,
    marginBottom: 8,
  },
  idErrorBadgeText: { fontSize: 11, fontWeight: '700', color: colors.danger },
  idErrorText: {
    fontSize: 12.5,
    lineHeight: 18,
    color: '#991B1B',
    textAlign: 'center',
    marginBottom: 12,
  },
  idRetryBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    backgroundColor: colors.danger,
    paddingHorizontal: 16,
    paddingVertical: 9,
    borderRadius: 20,
  },

  fab: {
    position: 'absolute',
    bottom: 20,
    right: 20,
    backgroundColor: colors.primary,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    paddingHorizontal: 20,
    paddingVertical: 14,
    borderRadius: 30,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 5,
  },
  fabDisabled: { opacity: 0.5 },
  fabText: { color: colors.white, fontSize: 16, fontWeight: '600' },
});