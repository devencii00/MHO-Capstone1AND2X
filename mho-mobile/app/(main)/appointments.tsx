import React, { useState, useCallback } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
  Modal,
  Platform,
} from 'react-native';
import { useRouter, useFocusEffect } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import api from './../lib/api';

// ── Status config ──────────────────────────────────────────────
const STATUS_CONFIG: Record<string, { color: string; bg: string; label: string; icon: any }> = {
  approved:  { color: '#16a34a', bg: '#f0fdf4', label: 'Approved',  icon: 'checkmark-circle-outline' },
  confirmed: { color: '#16a34a', bg: '#f0fdf4', label: 'Confirmed', icon: 'checkmark-circle-outline' },
  completed: { color: '#0891b2', bg: '#ecfeff', label: 'Completed', icon: 'checkbox-outline' },
  cancelled: { color: '#ef4444', bg: '#fef2f2', label: 'Cancelled', icon: 'close-circle-outline' },
};

const getStatusCfg = (status: string) =>
  STATUS_CONFIG[status?.toLowerCase()] ?? { color: '#64748b', bg: '#f8fafc', label: status, icon: 'ellipse-outline' };

const getServiceIcon = (serviceName: string): any => {
  const name = serviceName?.toLowerCase() || '';
  if (name.includes('ecg') || name.includes('cardio') || name.includes('heart')) return 'pulse-outline';
  if (name.includes('lab') || name.includes('blood') || name.includes('test')) return 'flask-outline';
  if (name.includes('ultrasound') || name.includes('imaging')) return 'scan-outline';
  if (name.includes('xray') || name.includes('x-ray') || name.includes('x ray')) return 'medical-outline';
  if (name.includes('dental')) return 'happy-outline';
  if (name.includes('eye') || name.includes('vision')) return 'eye-outline';
  return 'clipboard-outline';
};

const FILTERS = ['Approved', 'Completed', 'Cancelled'];

export default function AppointmentsScreen() {
  const router = useRouter();

  const [appointments, setAppointments] = useState<any[]>([]);
  const [patientName, setPatientName]   = useState('');
  const [loading, setLoading]           = useState(true);
  const [refreshing, setRefreshing]     = useState(false);
  const [activeFilter, setActiveFilter] = useState('Approved');
  const [showCancelModal, setShowCancelModal] = useState(false);
  const [selectedAppt, setSelectedAppt]       = useState<any>(null);
  const [cancelling, setCancelling]           = useState(false);

  const fetchAppointments = async () => {
    try {
      const response = await api.get('/patient/appointments');
      const result   = response.data;
      if (result.success && result.data) {
        setAppointments(result.data);
        if (result.patient_name) setPatientName(result.patient_name);
        else if (result.data[0]?.patient_name) setPatientName(result.data[0].patient_name);
      } else {
        setAppointments([]);
      }
    } catch {
      setAppointments([]);
    } finally {
      setLoading(false);
    }
  };

  const fetchProfile = async () => {
    try {
      const res = await api.get('/patient/profile');
      if (res.data?.success && res.data?.data?.name) {
        setPatientName(res.data.data.name);
      } else if (res.data?.name) {
        setPatientName(res.data.name);
      }
    } catch {}
  };

  useFocusEffect(useCallback(() => {
    fetchProfile();
    fetchAppointments();
  }, []));

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchAppointments();
    setRefreshing(false);
  };

  const confirmCancel = async () => {
    if (!selectedAppt) return;
    setCancelling(true);
    try {
      const res = await api.post(`/patient/appointments/${selectedAppt.id}/cancel`, { reason: 'Cancelled by patient' });
      if (res.data.success) {
        setShowCancelModal(false);
        setSelectedAppt(null);
        fetchAppointments();
      }
    } catch {}
    finally { setCancelling(false); }
  };

  const filtered = activeFilter === '__ALL__'
    ? appointments
    : appointments.filter(a => a.status?.toLowerCase() === activeFilter.toLowerCase());

  return (
    <View style={{ flex: 1, backgroundColor: '#F5F7F6' }}>

      {/* ── HEADER ── */}
      <View style={{
        backgroundColor: '#0B4D2E',
        paddingTop: Platform.OS === 'ios' ? 56 : 36,
        paddingBottom: 24,
        paddingHorizontal: 20,
      }}>
        {/* Back arrow row */}
        <TouchableOpacity
          onPress={() => router.back()}
          style={{ flexDirection: 'row', alignItems: 'center', gap: 6, marginBottom: 18, alignSelf: 'flex-start' }}
        >
          <Ionicons name="arrow-back" size={20} color="#fff" />
        </TouchableOpacity>

        {/* Title */}
        <View>
          <Text style={{ fontSize: 20, color: '#fff', textAlign: 'center', fontWeight: '700' }}>
            Appointments Record
          </Text>
          <Text style={{ fontSize: 12, color: '#a7d5b8', marginTop: 4, textAlign: 'center' }}>
            View and manage your appointments
          </Text>
        </View>
      </View>

      {/* ── FILTER TABS (pill style) ── */}
      <View style={{ backgroundColor: '#F5F7F6' }}>
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={{ paddingHorizontal: 16, paddingVertical: 12, gap: 8 }}
        >
          {FILTERS.map(f => {
            const active = activeFilter === f;
            return (
              <TouchableOpacity
                key={f}
                onPress={() => setActiveFilter(f)}
                style={{
                  paddingHorizontal: 18,
                  paddingVertical: 7,
                  borderRadius: 20,
                  backgroundColor: active ? '#0B4D2E' : 'transparent',
                  borderWidth: active ? 0 : 1.5,
                  borderColor: '#d1d5db',
                }}
              >
                <Text style={{
                  fontSize: 13,
                  fontWeight: '600',
                  color: active ? '#fff' : '#374151',
                }}>
                  {f}
                </Text>
              </TouchableOpacity>
            );
          })}
        </ScrollView>
      </View>

      {/* ── LIST ── */}
      {loading ? (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
          <ActivityIndicator size="large" color="#0B4D2E" />
          <Text style={{ marginTop: 10, color: '#6b7280', fontSize: 13 }}>Loading appointments...</Text>
        </View>
      ) : (
        <ScrollView
          showsVerticalScrollIndicator={false}
          contentContainerStyle={{ padding: 16, gap: 10, paddingBottom: 40 }}
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={['#0B4D2E']} />}
        >
          {filtered.length === 0 ? (
            <View style={{ alignItems: 'center', paddingVertical: 60, gap: 12 }}>
              <View style={{
                width: 72, height: 72, borderRadius: 36,
                backgroundColor: '#f0fdf4',
                alignItems: 'center', justifyContent: 'center',
              }}>
                <Ionicons name="calendar-outline" size={34} color="#0B4D2E" />
              </View>
              <Text style={{ fontSize: 16, fontWeight: '700', color: '#111827' }}>
                No {activeFilter} appointments
              </Text>
              <Text style={{ fontSize: 13, color: '#6b7280', textAlign: 'center', lineHeight: 18 }}>
                No {activeFilter.toLowerCase()} appointments found.
              </Text>
            </View>
          ) : (
            filtered.map((appt, idx) => {
              const cfg = getStatusCfg(appt.status);
              const serviceIcon = getServiceIcon(appt.service?.name || '');
              return (
                <TouchableOpacity
                  key={appt.id || idx}
                  activeOpacity={0.8}
                  onPress={() => {
                    if (appt.status?.toLowerCase() === 'approved' || appt.status?.toLowerCase() === 'confirmed') {
                      router.push('/(main)/(tabs)/queue');
                    }
                  }}
                  style={{
                    backgroundColor: '#fff',
                    borderRadius: 16,
                    overflow: 'hidden',
                    
                    shadowColor: '#000',
                    shadowOpacity: 0.05,
                    shadowRadius: 6,
                    shadowOffset: { width: 0, height: 2 },
                    elevation: 2,
                  }}
                >
                  <View style={{ flexDirection: 'row', alignItems: 'center', padding: 14, gap: 12 }}>
                    {/* Service icon */}
                    <View style={{
                      width: 48, height: 48, borderRadius: 14,
                      backgroundColor: cfg.bg,
                      alignItems: 'center', justifyContent: 'center',
                      flexShrink: 0,
                    }}>
                      <Ionicons name={serviceIcon} size={24} color={cfg.color} />
                    </View>

                    {/* Middle content */}
                    <View style={{ flex: 1, gap: 4 }}>
                      <Text style={{ fontSize: 15, fontWeight: '700', color: '#111827' }} numberOfLines={1}>
                        {appt.service?.name || 'Appointment'}
                      </Text>
                      <View style={{ flexDirection: 'row', alignItems: 'center', gap: 5 }}>
                        <Ionicons name="calendar-outline" size={12} color="#6b7280" />
                        <Text style={{ fontSize: 12, color: '#6b7280' }}>
                          {appt.date}  •  {appt.time}
                        </Text>
                      </View>
                      {(appt.request_id || appt.id) ? (
                        <Text style={{ fontSize: 11, color: '#9ca3af' }}>
                          Request ID: {appt.request_id || appt.id}
                        </Text>
                      ) : null}
                    </View>

                    {/* Status badge + arrow */}
                    <View style={{ alignItems: 'flex-end', gap: 6, flexShrink: 0 }}>
                      <View style={{
                        flexDirection: 'row', alignItems: 'center', gap: 4,
                        backgroundColor: cfg.bg,
                        paddingHorizontal: 9, paddingVertical: 4,
                        borderRadius: 20,
                      }}>
                        <Text style={{ fontSize: 11, fontWeight: '700', color: cfg.color, textTransform: 'capitalize' }}>
                          {cfg.label}
                        </Text>
                        <Ionicons name={cfg.icon} size={13} color={cfg.color} />
                      </View>
                      <Ionicons name="chevron-forward" size={16} color="#d1d5db" />
                    </View>
                  </View>
                </TouchableOpacity>
              );
            })
          )}
        </ScrollView>
      )}

      {/* ── CANCEL MODAL ── */}
      <Modal visible={showCancelModal} animationType="fade" transparent onRequestClose={() => setShowCancelModal(false)}>
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <View style={{ width: '85%', backgroundColor: '#fff', borderRadius: 24, overflow: 'hidden' }}>
            <View style={{ alignItems: 'center', paddingTop: 32, paddingBottom: 16, backgroundColor: '#fef2f2' }}>
              <View style={{ width: 72, height: 72, borderRadius: 36, backgroundColor: '#fff', alignItems: 'center', justifyContent: 'center' }}>
                <Ionicons name="close-circle" size={48} color="#ef4444" />
              </View>
              <Text style={{ marginTop: 14, fontSize: 18, fontWeight: '800', color: '#111827' }}>Cancel Appointment</Text>
              <Text style={{ paddingHorizontal: 24, marginTop: 6, fontSize: 13, textAlign: 'center', color: '#6b7280' }}>
                Are you sure you want to cancel this appointment?
              </Text>
            </View>
            <View style={{ flexDirection: 'row', gap: 12, padding: 20 }}>
              <TouchableOpacity
                style={{ flex: 1, paddingVertical: 13, backgroundColor: '#f3f4f6', borderRadius: 14, alignItems: 'center' }}
                onPress={() => { setShowCancelModal(false); setSelectedAppt(null); }}
              >
                <Text style={{ fontSize: 14, fontWeight: '600', color: '#6b7280' }}>No, Keep It</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={{ flex: 1, paddingVertical: 13, backgroundColor: '#ef4444', borderRadius: 14, alignItems: 'center' }}
                onPress={confirmCancel}
                disabled={cancelling}
              >
                {cancelling
                  ? <ActivityIndicator size="small" color="#fff" />
                  : <Text style={{ fontSize: 14, fontWeight: '600', color: '#fff' }}>Yes, Cancel</Text>
                }
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}