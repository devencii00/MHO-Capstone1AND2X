import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Alert,
  ActivityIndicator,
  StatusBar,
  SafeAreaView,
  TouchableOpacity,
  Image,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { authAPI } from '../../lib/api';
import AsyncStorage from '@react-native-async-storage/async-storage';

const colors = {
  primary: '#2563EB',
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
};

export default function InformationScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [profile, setProfile] = useState<any>(null);

  useEffect(() => {
    loadProfile();
  }, []);

  const loadProfile = async () => {
    try {
      setLoading(true);
      const response = await authAPI.getProfile();
      
      if (response.success) {
        let patientData = null;
        if (response.data && response.data.patient) {
          patientData = response.data.patient;
        } else if (response.data && !response.data.patient) {
          patientData = response.data;
        } else {
          patientData = response;
        }
        
        setProfile(patientData);
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

  const formatDate = (dateString: string) => {
    if (!dateString || dateString === 'Not set' || dateString === '') return 'Not set';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  const getPatientTypeLabel = (type: string) => {
    switch(type) {
      case 'senior': return 'Senior Citizen';
      case 'pwd': return 'PWD (Person with Disability)';
      case 'regular': return 'Regular';
      default: return type || 'Not set';
    }
  };

  const getStatusLabel = (status: string) => {
    switch(status) {
      case 'active': return 'Active';
      case 'inactive': return 'Inactive';
      case 'pending': return 'Pending';
      default: return status || 'Not set';
    }
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.loadingContainer}>
        <StatusBar barStyle="dark-content" backgroundColor={colors.lightGray} />
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Loading information...</Text>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor={colors.lightGray} />
      
      {/* Header */}
      <View style={styles.header}></View>

      <ScrollView showsVerticalScrollIndicator={false} style={styles.content}>
        {/* Profile Summary Card */}
        <View style={styles.summaryCard}>
          <View style={styles.avatarContainer}>
            <View style={styles.avatar}>
              <Text style={styles.avatarText}>
                {profile?.first_name?.charAt(0) || ''}{profile?.last_name?.charAt(0) || ''}
              </Text>
            </View>
          </View>
          <Text style={styles.fullName}>
            {profile?.first_name || ''} {profile?.middle_name ? profile.middle_name + ' ' : ''}{profile?.last_name || ''}
          </Text>
          <Text style={styles.userRole}>
            {getPatientTypeLabel(profile?.patient_type)}
          </Text>
          <View style={[styles.statusBadge, profile?.status === 'active' ? styles.statusActive : styles.statusInactive]}>
            <Text style={styles.statusText}>{getStatusLabel(profile?.status)}</Text>
          </View>
        </View>

        {/* Information Sections - MATCHING EDIT PROFILE FIELDS */}
        <View style={styles.infoContainer}>
          
          {/* Personal Information */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="person-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Personal Information</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>First Name</Text>
                <Text style={styles.infoValue}>{profile?.first_name || 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Middle Name</Text>
                <Text style={styles.infoValue}>{profile?.middle_name || 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Last Name</Text>
                <Text style={styles.infoValue}>{profile?.last_name || 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Username</Text>
                <Text style={styles.infoValue}>@{profile?.username || profile?.first_name || 'user'}</Text>
              </View>
            </View>
          </View>

          {/* Contact Information */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="call-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Contact Information</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Email Address</Text>
                <Text style={styles.infoValue}>{profile?.email || 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Phone Number</Text>
                <Text style={styles.infoValue}>{profile?.phone || profile?.phone_number || 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Address</Text>
                <Text style={styles.infoValue}>{profile?.address || 'Not set'}</Text>
              </View>
            </View>
          </View>

          {/* Personal Details */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="calendar-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Personal Details</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Date of Birth</Text>
                <Text style={styles.infoValue}>{formatDate(profile?.date_of_birth)}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Age</Text>
                <Text style={styles.infoValue}>{profile?.age ? `${profile.age} years old` : 'Not set'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Gender</Text>
                <Text style={styles.infoValue}>
                  {profile?.gender ? profile.gender.charAt(0).toUpperCase() + profile.gender.slice(1) : 'Not set'}
                </Text>
              </View>
            </View>
          </View>

          {/* Patient Classification */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="medkit-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Patient Classification</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Patient Type</Text>
                <Text style={styles.infoValue}>{getPatientTypeLabel(profile?.patient_type)}</Text>
              </View>
              
              {(profile?.patient_type === 'senior' || profile?.patient_type === 'pwd') && (
                <>
                  <View style={styles.infoRow}>
                    <Text style={styles.infoLabel}>Valid ID Type</Text>
                    <Text style={styles.infoValue}>{profile?.valid_id_type || 'Not set'}</Text>
                  </View>
                  
                  {profile?.valid_id_path && (
                    <View style={styles.infoRow}>
                      <Text style={styles.infoLabel}>Valid ID</Text>
                      <TouchableOpacity 
                        style={styles.viewIdButton}
                        onPress={() => {
                          // Show image modal or navigate to view ID
                          Alert.alert('Valid ID', 'Tap to view ID', [
                            { text: 'View', onPress: () => console.log('View ID') },
                            { text: 'Cancel' }
                          ]);
                        }}
                      >
                        <Text style={styles.viewIdText}>View Uploaded ID</Text>
                      </TouchableOpacity>
                    </View>
                  )}
                </>
              )}
            </View>
          </View>

          {/* Medical Information */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="fitness-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Medical Information</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Medical History</Text>
                <Text style={styles.infoValue}>{profile?.medical_history || 'None'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Allergies</Text>
                <Text style={styles.infoValue}>{profile?.allergies || 'None'}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Emergency Contact</Text>
                <Text style={styles.infoValue}>{profile?.emergency_contact || 'Not set'}</Text>
              </View>
            </View>
          </View>

          {/* Account Information */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <Ionicons name="shield-outline" size={22} color={colors.primary} />
              <Text style={styles.sectionTitle}>Account Information</Text>
            </View>
            
            <View style={styles.infoCard}>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Account Status</Text>
                <Text style={[styles.infoValue, profile?.status === 'active' ? { color: colors.success } : { color: colors.danger }]}>
                  {getStatusLabel(profile?.status)}
                </Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Member Since</Text>
                <Text style={styles.infoValue}>{formatDate(profile?.created_at)}</Text>
              </View>
              
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Last Updated</Text>
                <Text style={styles.infoValue}>{formatDate(profile?.updated_at)}</Text>
              </View>
            </View>
          </View>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.lightGray,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: colors.lightGray,
  },
  loadingText: {
    marginTop: 12,
    fontSize: 14,
    color: colors.gray,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: colors.white,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: colors.dark,
  },
  placeholder: {
    width: 40,
  },
  content: {
    flex: 1,
  },
  summaryCard: {
    backgroundColor: colors.white,
    margin: 16,
    marginBottom: 8,
    padding: 20,
    borderRadius: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 10,
    elevation: 2,
  },
  avatarContainer: {
    marginBottom: 12,
  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: colors.primary,
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    fontSize: 32,
    fontWeight: 'bold',
    color: colors.white,
  },
  fullName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: colors.dark,
    marginBottom: 4,
    textAlign: 'center',
  },
  userRole: {
    fontSize: 14,
    color: colors.gray,
    marginBottom: 8,
  },
  statusBadge: {
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
    marginTop: 4,
  },
  statusActive: {
    backgroundColor: '#D1FAE5',
  },
  statusInactive: {
    backgroundColor: '#FEE2E2',
  },
  statusText: {
    fontSize: 12,
    fontWeight: '600',
    color: colors.dark,
  },
  infoContainer: {
    paddingHorizontal: 16,
    paddingBottom: 30,
  },
  section: {
    marginBottom: 20,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
    marginBottom: 12,
    paddingLeft: 4,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.dark,
  },
  infoCard: {
    backgroundColor: colors.white,
    borderRadius: 16,
    paddingVertical: 8,
    paddingHorizontal: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 5,
    elevation: 1,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  infoLabel: {
    fontSize: 14,
    color: colors.gray,
    fontWeight: '500',
    flex: 1,
  },
  infoValue: {
    fontSize: 14,
    color: colors.dark,
    fontWeight: '500',
    flex: 1,
    textAlign: 'right',
  },
  viewIdButton: {
    backgroundColor: colors.primary,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 8,
  },
  viewIdText: {
    color: colors.white,
    fontSize: 12,
    fontWeight: '600',
  },
});