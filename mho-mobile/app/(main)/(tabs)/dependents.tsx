import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, Platform, StyleSheet, TouchableOpacity, ActivityIndicator } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';

// TODO: Replace these with your real API-driven state, e.g.:
// const { data } = useQuery(['dependents'], () => api.get('/patient/dependents'));
// const dependents = data?.dependents ?? [];
export default function DependentsScreen() {
  const router = useRouter();
  const [checkingAuth, setCheckingAuth] = useState(true);
  const [accessDenied, setAccessDenied] = useState(false);
  const [dependents] = useState<{ id: number; name: string; relationship: string }[]>([]);

  useEffect(() => {
    const checkAuth = async () => {
      try {
        // TODO: swap this for whatever key(s) your login flow actually stores —
        // e.g. an auth token. Reusing `patientId` here since that's what the
        // dashboard screen already checks to identify a logged-in patient.
        const patientId =
          (await AsyncStorage.getItem('patientId')) || (await AsyncStorage.getItem('patient_id'));

        setAccessDenied(!patientId);
      } catch (error) {
        console.error('Error checking login state:', error);
        setAccessDenied(true);
      } finally {
        setCheckingAuth(false);
      }
    };

    checkAuth();
  }, []);

  if (checkingAuth) {
    return (
      <View style={[styles.screen, { alignItems: 'center', justifyContent: 'center' }]}>
        <ActivityIndicator size="large" color="#047857" />
      </View>
    );
  }

  return (
    <View style={styles.screen}>
      {/* HEADER */}
      <View style={styles.header}>
        <View style={{ height: Platform.OS === 'ios' ? 50 : 30 }} />
        <Text style={styles.headerTitle}>Dependents</Text>
        <Text style={styles.headerSubtitle}>View linked dependent accounts and their records.</Text>
      </View>

      <ScrollView contentContainerStyle={{ padding: 16, paddingBottom: 48 }}>
        {/* ACCESS DENIED — shown only when there's no logged-in patient */}
        {accessDenied ? (
          <View style={styles.deniedBanner}>
            <Text style={styles.deniedText}>Access denied</Text>
            <Text style={styles.deniedSubtext}>Please log in to view your linked dependents.</Text>
            <TouchableOpacity
              style={styles.deniedBtn}
              activeOpacity={0.85}
              // TODO: point this at your actual login route.
              onPress={() => router.push('/(auth)/login' as any)}
            >
                
              <Text style={styles.deniedBtnText}>Log In</Text>
            </TouchableOpacity>
          </View>
        ) : (
          <>
            {/* GUARDIAN CARE OVERVIEW */}
            <View style={styles.overviewCard}>
              <View style={styles.overviewIcon}>
                <Ionicons name="people-outline" size={20} color="#047857" />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.overviewTitle}>Guardian care overview</Text>
                <Text style={styles.overviewSubtitle}>
                  {dependents.length === 0
                    ? 'No linked dependent accounts found.'
                    : `${dependents.length} linked dependent account${dependents.length > 1 ? 's' : ''}.`}
                </Text>
              </View>
            </View>

            {/* EMPTY STATE */}
            {dependents.length === 0 ? (
              <View style={styles.emptyCard}>
                <View style={styles.emptyIconCircle}>
                  <Ionicons name="person-add-outline" size={32} color="#047857" />
                </View>
                <Text style={styles.emptyTitle}>No dependents linked</Text>
                <Text style={styles.emptySubtitle}>
                  This account does not have any dependent profile linked yet.
                </Text>
              </View>
            ) : (
              <View style={{ gap: 12 }}>
                {dependents.map((dep) => (
                  <View key={dep.id} style={styles.dependentRow}>
                    <View style={styles.dependentAvatar}>
                      <Ionicons name="person-outline" size={20} color="#111827" />
                    </View>
                    <View style={{ flex: 1 }}>
                      <Text style={styles.dependentName}>{dep.name}</Text>
                      <Text style={styles.dependentRelationship}>{dep.relationship}</Text>
                    </View>
                    <Ionicons name="chevron-forward" size={18} color="#9ca3af" />
                  </View>
                ))}
              </View>
            )}
          </>
        )}
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  screen: { flex: 1, backgroundColor: '#F7FAF9' },

  header: {
    backgroundColor: '#065f46',
    paddingHorizontal: 20,
    paddingBottom: 24,
    // borderBottomLeftRadius: 28,
    // borderBottomRightRadius: 28,
  },
  headerTitle: {
    fontSize: 20,
    alignSelf: 'center',
    fontWeight: '800',
    color: '#fff',
    marginTop: 4,
  },
  headerSubtitle: {
    alignSelf: 'center',
    fontSize: 13,
    color: 'rgba(255,255,255,0.85)',
    marginTop: 4,
  },

  deniedBanner: {
    backgroundColor: '#FEF2F2',
    borderWidth: 1,
    borderColor: '#FECACA',
    borderRadius: 12,
    paddingVertical: 14,
    paddingHorizontal: 14,
    marginBottom: 16,
  },
  deniedText: {
    color: '#DC2626',
    fontSize: 13,
    fontWeight: '700',
  },
  deniedSubtext: {
    color: '#B91C1C',
    fontSize: 12,
    marginTop: 4,
    lineHeight: 16,
  },
  deniedBtn: {
    alignSelf: 'flex-start',
    marginTop: 12,
    backgroundColor: '#DC2626',
    paddingHorizontal: 16,
    paddingVertical: 9,
    borderRadius: 10,
  },
  deniedBtnText: {
    color: '#fff',
    fontSize: 13,
    fontWeight: '700',
  },

  overviewCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 14,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 2 },
    elevation: 1,
  },
  overviewIcon: {
    width: 40,
    height: 40,
    borderRadius: 12,
    backgroundColor: 'rgba(4,120,87,0.1)',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  overviewTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#111827',
  },
  overviewSubtitle: {
    fontSize: 12,
    color: '#6b7280',
    marginTop: 2,
  },

  emptyCard: {
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 20,
    paddingVertical: 36,
    paddingHorizontal: 24,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    shadowOffset: { width: 0, height: 2 },
    elevation: 1,
  },
  emptyIconCircle: {
    width: 72,
    height: 72,
    borderRadius: 36,
    backgroundColor: 'rgba(4,120,87,0.1)',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 16,
  },
  emptyTitle: {
    fontSize: 16,
    fontWeight: '800',
    color: '#111827',
    marginBottom: 6,
  },
  emptySubtitle: {
    fontSize: 13,
    color: '#6b7280',
    textAlign: 'center',
    lineHeight: 18,
  },

  dependentRow: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 14,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 2 },
    elevation: 1,
  },
  dependentAvatar: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: '#f3f4f6',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  dependentName: {
    fontSize: 15,
    fontWeight: '700',
    color: '#111827',
  },
  dependentRelationship: {
    fontSize: 12,
    color: '#6b7280',
    marginTop: 1,
  },
});