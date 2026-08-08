import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Alert,
  Dimensions,
  ActivityIndicator,
  StyleSheet,
  Modal,
  Pressable,
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Calendar } from 'react-native-calendars';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api from './../lib/api';
import { CartItem } from '../../types/patient';

const { width } = Dimensions.get('window');

// ── Palette matched to the reference design ──
const BG = '#DCEEE8';
const HEADER_GREEN = '#064E3B';
const TEAL = '#0FA98A';
const TEAL_DARK = '#0C8A70';
const DARK_CIRCLE = '#2B2B2B';
const TEXT_DARK = '#0F172A';

// Morning: 7 AM - 12 PM | Afternoon: 1 PM - 5 PM
const PERIODS = [
  {
    id: 'morning',
    label: 'Morning',
    icon: 'sunny',
    color: '#F59E0B',
    hours: [7, 8, 9, 10, 11, 12],
  },
  {
    id: 'afternoon',
    label: 'Afternoon',
    icon: 'cloud',
    color: '#0EA5E9',
    hours: [13, 14, 15, 16, 17],
  },
];

const generateTimeSlotsForPeriod = (hours: number[]) => {
  return hours.map((hour) => {
    const displayHour = hour > 12 ? hour - 12 : hour === 0 ? 12 : hour;
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const dbTime = hour.toString().padStart(2, '0') + ':00';
    const display = `${displayHour}:00 ${ampm}`;

    return {
      id: `slot-${hour}`,
      label: display,
      display: display,
      dbTime: dbTime,
      hour: hour,
    };
  });
};

export default function BookingDetailsScreen() {
  const router = useRouter();
  const params = useLocalSearchParams();

  const fromCart = params.fromCart === 'true';
  const cartItemsParam = params.cartItems as string;
  const totalAmountParam = parseFloat(params.totalAmount as string) || 0;

  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [totalAmount, setTotalAmount] = useState<number>(0);
  const [totalDuration, setTotalDuration] = useState<number>(0);

  const serviceId = parseInt(params.serviceId as string);
  const serviceName = params.serviceName as string;
  const servicePrice = parseFloat(params.servicePrice as string) || 0;
  const serviceDuration = parseInt(params.serviceDuration as string) || 30;
  const categoryName = params.categoryName as string;

  const today = new Date().toISOString().split('T')[0];
  const [selectedDate, setSelectedDate] = useState<string | null>(null);
  const [selectedPeriod, setSelectedPeriod] = useState<string | null>(null);
  const [selectedTime, setSelectedTime] = useState<string | null>(null);
  const [showSummary, setShowSummary] = useState(false);
  const [modalVisible, setModalVisible] = useState(false);
  const [loading, setLoading] = useState(false);
  const [existingAppointments, setExistingAppointments] = useState<any[]>([]);
  const [loadingSlots, setLoadingSlots] = useState(false);

  useEffect(() => {
    if (fromCart && cartItemsParam) {
      try {
        const items = JSON.parse(cartItemsParam);
        const safeItems = items.map((item: any) => ({
          ...item,
          price: Number(item.price) || 0,
          duration: Number(item.duration) || 30,
        }));
        setCartItems(safeItems);
        setTotalAmount(Number(totalAmountParam) || 0);
        setTotalDuration(safeItems.reduce((a: number, i: any) => a + (i.duration || 30), 0));
      } catch (e) {
        console.error('Cart parse error:', e);
      }
    } else {
      setTotalDuration(serviceDuration);
    }
  }, [fromCart, cartItemsParam, totalAmountParam, serviceDuration]);

  useEffect(() => {
    if (selectedDate) fetchExistingAppointments();
  }, [selectedDate]);

  // ✅ Also fetch appointments on mount to mark dates
  useEffect(() => {
    fetchExistingAppointments();
  }, []);

  const fetchExistingAppointments = async () => {
    try {
      setLoadingSlots(true);
      const response = await api.get('/patient/appointments');
      if (response.data.success && response.data.data) {
        setExistingAppointments(response.data.data);
      } else {
        setExistingAppointments([]);
      }
    } catch (e: any) {
      console.error('Error fetching appointments:', e);
      setExistingAppointments([]);
    } finally {
      setLoadingSlots(false);
    }
  };

  // ✅ Get dates that have appointments
  const getAppointmentDates = (): string[] => {
    return existingAppointments
      .filter((a) => a.status !== 'cancelled')
      .map((a) => a.appointment_date)
      .filter((date) => date);
  };

  // ✅ UPDATED: now checks (1) past date, (2) past time-of-day if date is today,
  // (3) whether this exact time slot is already booked, and (4) the 1-per-day rule.
  const isTimeSlotAvailable = (dbTime: string, hour: number): boolean => {
    if (!selectedDate) return false;
    const now = new Date();
    const todayStr = now.toISOString().split('T')[0];

    // Past date entirely
    if (selectedDate < todayStr) return false;

    // ✅ If the selected date is today, disable hours that already passed
    if (selectedDate === todayStr) {
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();
      if (hour < currentHour || (hour === currentHour && currentMinute > 0)) {
        return false;
      }
    }

    const appointmentsOnThisDate = existingAppointments.filter(
      (a) => a.appointment_date === selectedDate && a.status !== 'cancelled'
    );

    // ✅ If this exact time slot is already taken by someone, disable it
    const isSlotAlreadyBooked = appointmentsOnThisDate.some(
      (a) => a.appointment_time === dbTime
    );
    if (isSlotAlreadyBooked) return false;

    // Existing rule: only 1 appointment per day allowed for this patient/date
    if (appointmentsOnThisDate.length >= 1) return false;

    return true;
  };

  // ✅ UPDATED: now takes dbTime so it can tell "already booked" apart from
  // "already passed" and from the "1 per day" rule.
  const getSlotUnavailableReason = (dbTime: string, hour: number): string => {
    const now = new Date();
    const todayStr = now.toISOString().split('T')[0];

    if (!selectedDate || selectedDate < todayStr) return 'This date has already passed.';

    if (selectedDate === todayStr) {
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();
      if (hour < currentHour || (hour === currentHour && currentMinute > 0)) {
        return 'This time has already passed for today.';
      }
    }

    const appointmentsOnThisDate = existingAppointments.filter(
      (a) => a.appointment_date === selectedDate && a.status !== 'cancelled'
    );

    const isSlotAlreadyBooked = appointmentsOnThisDate.some(
      (a) => a.appointment_time === dbTime
    );
    if (isSlotAlreadyBooked) {
      return 'This time slot is already booked.';
    }

    if (appointmentsOnThisDate.length >= 1) {
      return 'You already have an appointment on this date. Only 1 appointment per day is allowed.';
    }

    return 'This slot is unavailable.';
  };

  const getEndTime = (hour: number, durationMinutes: number) => {
    const totalMins = hour * 60 + durationMinutes;
    const endH = Math.floor(totalMins / 60);
    const endM = totalMins % 60;
    const h12 = endH > 12 ? endH - 12 : endH === 0 ? 12 : endH;
    const ampm = endH >= 12 ? 'PM' : 'AM';
    return `${h12}:${endM.toString().padStart(2, '0')} ${ampm}`;
  };

  const formatDateDisplay = (d: string | null) => (d ? new Date(d).toDateString() : 'Not selected');

  const formatDuration = (mins: number) => {
    const h = Math.floor(mins / 60);
    const m = mins % 60;
    if (h && m) return `${h}h ${m}m`;
    if (h) return `${h} hr${h > 1 ? 's' : ''}`;
    return `${m} min`;
  };

  const handleDateSelect = (day: any) => {
    if (day.dateString < today) {
      Alert.alert('Invalid Date', 'Please select today or a future date.');
      return;
    }
    setSelectedDate(day.dateString);
    setSelectedPeriod(null);
    setSelectedTime(null);
    setShowSummary(false);
  };

  const clearSessionAndRelogin = async () => {
    await AsyncStorage.multiRemove(['token', 'patient_id', 'user_data', 'user']);
    Alert.alert('Session Expired', 'Please login again.', [
      { text: 'OK', onPress: () => router.replace('/(auth)/login') },
    ]);
  };

  const getActualPatientId = async () => {
    try {
      const response = await api.get('/patient/profile');
      return response.data.success ? response.data.data.id : null;
    } catch {
      return null;
    }
  };

  const handleConfirmBooking = async () => {
    if (!selectedTime) {
      Alert.alert('Select a Time', 'Please choose a specific time.');
      return;
    }
    const period = PERIODS.find((p) => p.id === selectedPeriod);
    if (!period) return;

    const timeSlots = generateTimeSlotsForPeriod(period.hours);
    const selectedSlot = timeSlots.find((s) => s.display === selectedTime);
    if (!selectedSlot) return;

    if (!isTimeSlotAvailable(selectedSlot.dbTime, selectedSlot.hour)) {
      Alert.alert('Slot Unavailable', getSlotUnavailableReason(selectedSlot.dbTime, selectedSlot.hour));
      setSelectedTime(null);
      return;
    }
    await createBooking(selectedSlot);
  };

  const createBooking = async (slot: any) => {
    setLoading(true);
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) {
        await clearSessionAndRelogin();
        return;
      }

      const patientId = await getActualPatientId();
      if (!patientId) {
        setLoading(false);
        Alert.alert('Account Error', 'Please login again.', [
          { text: 'Login Again', onPress: clearSessionAndRelogin },
        ]);
        return;
      }

      const displayTime = slot.display;
      const endTime = getEndTime(slot.hour, totalDuration);

      let requestBody: any = {};

      if (fromCart && cartItems.length > 0) {
        requestBody = {
          service_id: cartItems[0].id,
          appointment_date: selectedDate,
          appointment_time: slot.dbTime,
          notes: JSON.stringify({
            is_multi_service: true,
            services: cartItems.map((i) => ({
              id: i.id,
              name: i.name,
              price: i.price,
              duration: i.duration,
            })),
            total_amount: totalAmount,
            total_duration: totalDuration,
            start_time: displayTime,
            estimated_end_time: endTime,
            payment_method: 'cash',
            payment_status: 'pending',
          }),
        };
      } else {
        requestBody = {
          service_id: serviceId,
          appointment_date: selectedDate,
          appointment_time: slot.dbTime,
          notes: JSON.stringify({
            duration_minutes: totalDuration,
            start_time: displayTime,
            estimated_end_time: endTime,
            payment_method: 'cash',
            payment_status: 'pending',
          }),
        };
      }

      const response = await api.post('/patient/appointments', requestBody);

      if (response.data.success) {
        if (fromCart) await AsyncStorage.removeItem('booking_cart');

        // ✅ IMMEDIATE: Close modal & navigate directly to Dashboard
        setModalVisible(false);
        setLoading(false);
        router.push('/(main)/(tabs)/dashboard');

        fetchExistingAppointments();
        return;
      } else {
        setLoading(false);
        Alert.alert('Error', response.data.message || 'Failed to book.');
      }
    } catch (e: any) {
      console.error('Booking error:', e);
      setLoading(false);
      const errorMessage = e.response?.data?.message || e.message || 'Please try again.';
      Alert.alert('Booking Failed', errorMessage);
    }
  };

  const displayServiceName =
    fromCart && cartItems.length > 0
      ? `${cartItems.length} Service${cartItems.length > 1 ? 's' : ''}`
      : serviceName;
  const displayCategory = fromCart && cartItems.length > 0 ? 'Multiple Services' : categoryName;
  const displayTotal = fromCart ? totalAmount : servicePrice;

  const selectedPeriodObj = PERIODS.find((p) => p.id === selectedPeriod);
  const timeSlots = selectedPeriodObj ? generateTimeSlotsForPeriod(selectedPeriodObj.hours) : [];
  const selectedSlotObj = timeSlots.find((s) => s.display === selectedTime);
  const estimatedEndTime = selectedSlotObj ? getEndTime(selectedSlotObj.hour, totalDuration) : null;

  // ✅ Get appointment dates for marking
  const appointmentDates = getAppointmentDates();

  // ── Custom circular day renderer ──
  const renderDay = ({ date, state }: any) => {
    if (!date) return <View style={{ width: 40, height: 40 }} />;

    const isSelected = date.dateString === selectedDate;
    const isPast = date.dateString < today;
    const isToday = date.dateString === today;
    const hasAppointment = appointmentDates.includes(date.dateString);

    return (
      <TouchableOpacity
        activeOpacity={0.75}
        disabled={isPast}
        onPress={() => handleDateSelect(date)}
        style={{ alignItems: 'center', justifyContent: 'center' }}
      >
        <View
          style={[
            styles.dayCircle,
            (isSelected || hasAppointment) && { backgroundColor: DARK_CIRCLE },
            isPast && styles.dayCirclePast,
          ]}
        >
          <Text
            style={[
              styles.dayText,
              (isSelected || hasAppointment) && { color: '#fff' },
              isPast && { color: '#B9C6C0' },
              isToday && !isSelected && !hasAppointment && { color: TEAL_DARK },
            ]}
          >
            {date.day}
          </Text>
          {isPast && <View style={styles.dayStrike} />}
        </View>

        {/* ✅ Checkmark for selected date */}
        {isSelected && (
          <View style={styles.dayCheckBadge}>
            <Ionicons name="checkmark" size={9} color="#fff" />
          </View>
        )}

        {/* ✅ Checkmark for dates with appointments */}
        {hasAppointment && !isSelected && (
          <View style={styles.dayCheckBadge}>
            <Ionicons name="checkmark" size={9} color="#fff" />
          </View>
        )}
      </TouchableOpacity>
    );
  };

  return (
    <View style={{ flex: 1, backgroundColor: BG }}>
      {/* ── DARK GREEN HEADER ── */}
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backBtn} activeOpacity={0.8}>
          <Ionicons name="chevron-back" size={22} color="#fff" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Book Appointment</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingBottom: 24 }}>

        {/* ── CALENDAR ── */}
        <View style={styles.calendarWrap}>
          <Calendar
            current={today}
            minDate={today}
            hideExtraDays
            firstDay={1}
            dayComponent={renderDay}
            onDayPress={handleDateSelect}
            renderArrow={(direction: 'left' | 'right') => (
              <View style={styles.navArrow}>
                <Ionicons
                  name={direction === 'left' ? 'chevron-back' : 'chevron-forward'}
                  size={16}
                  color={TEXT_DARK}
                />
              </View>
            )}
            theme={{
              backgroundColor: 'transparent',
              calendarBackground: 'transparent',
              textMonthFontWeight: '800',
              textMonthFontSize: 24,
              monthTextColor: TEXT_DARK,
              textDayHeaderFontWeight: '700',
              textDayHeaderFontSize: 12,
              textSectionTitleColor: '#6B8478',
              ...({
                'stylesheet.calendar.header': {
                  header: {
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    paddingHorizontal: 4,
                    marginBottom: 14,
                  },
                  dayHeader: {
                    fontSize: 12,
                    fontWeight: '700',
                    color: '#6B8478',
                  },
                },
              } as any),
            }}
          />
        </View>

        {/* ── AVAILABLE SLOTS ── */}
        {selectedDate && (
          <View style={styles.periodSection}>
            <Text style={styles.sectionLabel}>Available Slots</Text>
            <View style={styles.periodRow}>
              {PERIODS.map((period) => {
                const isSelected = selectedPeriod === period.id;
                return (
                  <TouchableOpacity
                    key={period.id}
                    style={[styles.periodPill, isSelected && { backgroundColor: TEAL }]}
                    onPress={() => {
                      setSelectedPeriod(period.id);
                      setSelectedTime(null);
                      setShowSummary(false);
                      setModalVisible(true);
                    }}
                    activeOpacity={0.85}
                  >
                    <Ionicons
                      name={period.icon as any}
                      size={16}
                      color={isSelected ? '#fff' : period.color}
                    />
                    <Text style={[styles.periodPillText, isSelected && { color: '#fff' }]}>
                      {period.label}
                    </Text>
                  </TouchableOpacity>
                );
              })}
            </View>
          </View>
        )}

        {!selectedDate && (
          <View style={styles.hintBox}>
            <Ionicons name="hand-left-outline" size={16} color="#6B8478" />
            <Text style={styles.hintText}>Tap a date above to continue</Text>
          </View>
        )}

        <View style={{ height: 40 }} />
      </ScrollView>

      {/* ── BOTTOM-SHEET MODAL ── */}
      <Modal
        visible={modalVisible}
        transparent
        animationType="slide"
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalOuter}>
          <Pressable style={StyleSheet.absoluteFill} onPress={() => setModalVisible(false)} />
          <View style={styles.sheetCard}>
            <View style={styles.sheetHandle} />
            <View style={styles.sheetTitleRow}>
              <View style={{ width: 28 }} />
              <Text style={styles.sheetTitle}>Choose your Time</Text>
              <TouchableOpacity onPress={() => setModalVisible(false)} style={styles.sheetCloseBtn}>
                <Ionicons name="close" size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            <ScrollView
              showsVerticalScrollIndicator={false}
              style={{ maxHeight: '100%' }}
              contentContainerStyle={{ paddingBottom: 12 }}
            >
              {loadingSlots ? (
                <View style={styles.loadingRow}>
                  <ActivityIndicator size="small" color={TEAL} />
                  <Text style={styles.loadingText}>Checking availability…</Text>
                </View>
              ) : (
                <>
                  <View style={styles.timeRow}>
                    <View style={styles.timeCol}>
                      <Text style={styles.timeColLabel}>From</Text>
                      <View style={styles.timeBox}>
                        <Ionicons name="time-outline" size={16} color="#64748B" />
                        <Text style={styles.timeBoxText} numberOfLines={1}>
                          {selectedTime ?? 'Select'}
                        </Text>
                      </View>
                    </View>
                    <View style={styles.timeCol}>
                      <Text style={styles.timeColLabel}>To</Text>
                      <View style={[styles.timeBox, styles.timeBoxDisabled]}>
                        <Ionicons name="time-outline" size={16} color="#94A3B8" />
                        <Text style={[styles.timeBoxText, { color: '#94A3B8' }]} numberOfLines={1}>
                          {estimatedEndTime ?? '—:—'}
                        </Text>
                      </View>
                    </View>
                  </View>

                  <View style={styles.slotGrid}>
                    {timeSlots.map((slot) => {
                      const available = isTimeSlotAvailable(slot.dbTime, slot.hour);
                      const selected = selectedTime === slot.display;

                      const appointmentsOnThisDate = existingAppointments.filter(
                        (a) => a.appointment_date === selectedDate && a.status !== 'cancelled'
                      );
                      const isSlotAlreadyBooked = appointmentsOnThisDate.some(
                        (a) => a.appointment_time === slot.dbTime
                      );
                      const hasExistingAppointment = appointmentsOnThisDate.length >= 1;

                      const now = new Date();
                      const todayStr = now.toISOString().split('T')[0];
                      const isPastDate = !!selectedDate && selectedDate < todayStr;
                      const isPastTimeToday =
                        selectedDate === todayStr &&
                        (slot.hour < now.getHours() ||
                          (slot.hour === now.getHours() && now.getMinutes() > 0));

                      // ✅ Priority: passed > booked > 1-per-day
                      let statusLabel = '';
                      if (isPastDate || isPastTimeToday) statusLabel = 'Passed';
                      else if (isSlotAlreadyBooked) statusLabel = 'Booked';
                      else if (!available && hasExistingAppointment) statusLabel = '1/Day';

                      return (
                        <TouchableOpacity
                          key={slot.id}
                          activeOpacity={0.85}
                          style={[
                            styles.slotChip,
                            selected && styles.slotChipSelected,
                            !available && styles.slotChipDisabled,
                          ]}
                          onPress={() => {
                            if (!available) {
                              Alert.alert('Unavailable', getSlotUnavailableReason(slot.dbTime, slot.hour));
                            } else {
                              setSelectedTime(slot.display);
                              setShowSummary(false);
                            }
                          }}
                          disabled={!available}
                        >
                          <Text
                            style={[
                              styles.slotChipText,
                              selected && { color: '#fff' },
                              !available && { color: '#94A3B8' },
                            ]}
                          >
                            {slot.display}
                          </Text>
                          {statusLabel !== '' && (
                            <Text style={styles.slotChipStatus}>{statusLabel}</Text>
                          )}
                        </TouchableOpacity>
                      );
                    })}
                  </View>

                  {selectedTime && !showSummary && (
                    <TouchableOpacity
                      style={styles.bookNowBtn}
                      onPress={() => setShowSummary(true)}
                      activeOpacity={0.88}
                    >
                      <Text style={styles.bookNowText}>Review Summary</Text>
                    </TouchableOpacity>
                  )}

                  {/* ── SUMMARY ── */}
                  {showSummary && (
                    <View style={styles.summaryBlock}>
                      {[
                        { label: 'Service', value: displayServiceName },
                        { label: 'Category', value: displayCategory },
                        { label: 'Duration', value: formatDuration(totalDuration) },
                        { label: 'Date', value: formatDateDisplay(selectedDate) },
                        { label: 'Period', value: selectedPeriodObj?.label ?? '—' },
                        { label: 'Time', value: selectedTime ?? '—' },
                        { label: 'Est. Done', value: estimatedEndTime ?? '—' },
                      ].map((row, i) => (
                        <View key={i} style={styles.summaryRow}>
                          <Text style={styles.summaryLabel}>{row.label}</Text>
                          <Text style={styles.summaryValue}>{row.value}</Text>
                        </View>
                      ))}

                      <View style={styles.summaryDivider} />
                      <View style={styles.summaryTotalRow}>
                        <Text style={styles.summaryTotalLabel}>Total Amount</Text>
                        <Text style={styles.summaryTotalValue}>
                          ₱{(isNaN(displayTotal) ? 0 : displayTotal).toFixed(2)}
                        </Text>
                      </View>

                      <TouchableOpacity
                        style={[styles.bookNowBtn, (loading || !selectedTime) && styles.bookNowBtnDisabled]}
                        onPress={handleConfirmBooking}
                        disabled={loading || !selectedTime}
                        activeOpacity={0.88}
                      >
                        {loading ? (
                          <ActivityIndicator color="#fff" size="small" />
                        ) : (
                          <Text style={styles.bookNowText}>Book Now</Text>
                        )}
                      </TouchableOpacity>
                      <Text style={styles.summaryFootnote}>
                        You'll receive confirmation once staff approves your booking.
                      </Text>
                    </View>
                  )}
                </>
              )}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingTop: 56,
    paddingHorizontal: 20,
    paddingBottom: 18,
    backgroundColor: HEADER_GREEN,
  },
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(255,255,255,0.15)',
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: '#fff',
  },
  modalOuter: {
    flex: 1,
    justifyContent: 'flex-end',
    backgroundColor: 'rgba(0,0,0,0.4)',
  },
  calendarWrap: {
    marginHorizontal: 16,
    marginTop: 8,
  },
  navArrow: {
    width: 34,
    height: 34,
    borderRadius: 17,
    alignItems: 'center',
    justifyContent: 'center',
  },
  dayCircle: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  dayCirclePast: {
    backgroundColor: '#EAF2EE',
  },
  dayStrike: {
    position: 'absolute',
    width: 26,
    height: 1,
    backgroundColor: '#C9D8D0',
    transform: [{ rotate: '45deg' }],
  },
  dayText: {
    fontSize: 15,
    fontWeight: '700',
    color: TEXT_DARK,
  },
  dayCheckBadge: {
    position: 'absolute',
    top: -2,
    right: 2,
    width: 14,
    height: 14,
    borderRadius: 7,
    backgroundColor: TEAL,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1.5,
    borderColor: BG,
  },
  periodSection: {
    marginHorizontal: 20,
    marginTop: 20,
  },
  sectionLabel: {
    fontSize: 17,
    fontWeight: '800',
    color: TEXT_DARK,
    marginBottom: 12,
  },
  periodRow: {
    flexDirection: 'row',
    gap: 10,
  },
  periodPill: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    backgroundColor: '#fff',
    paddingVertical: 10,
    paddingHorizontal: 18,
    borderRadius: 24,
  },
  periodPillText: {
    fontSize: 13,
    fontWeight: '700',
    color: TEXT_DARK,
  },
  hintBox: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    justifyContent: 'center',
    marginTop: 16,
    marginHorizontal: 20,
  },
  hintText: {
    fontSize: 13,
    fontWeight: '600',
    color: '#6B8478',
  },
  sheetCard: {
    backgroundColor: '#fff',
    borderTopLeftRadius: 28,
    borderTopRightRadius: 28,
    paddingTop: 12,
    paddingHorizontal: 20,
    paddingBottom: 24,
    maxHeight: '75%',
  },
  sheetHandle: {
    width: 40,
    height: 4,
    borderRadius: 2,
    backgroundColor: '#E2E8F0',
    alignSelf: 'center',
    marginBottom: 14,
  },
  sheetTitleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  sheetCloseBtn: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: '#F1F5F9',
    alignItems: 'center',
    justifyContent: 'center',
  },
  sheetTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: TEXT_DARK,
    textAlign: 'center',
  },
  loadingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    justifyContent: 'center',
    paddingVertical: 12,
  },
  loadingText: {
    fontSize: 13,
    color: '#64748B',
  },
  timeRow: {
    flexDirection: 'row',
    gap: 14,
    marginBottom: 18,
  },
  timeCol: {
    flex: 1,
  },
  timeColLabel: {
    fontSize: 12,
    fontWeight: '700',
    color: '#64748B',
    textAlign: 'center',
    marginBottom: 6,
  },
  timeBox: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    borderRadius: 16,
    paddingVertical: 12,
  },
  timeBoxDisabled: {
    backgroundColor: '#F8FAFC',
  },
  timeBoxText: {
    fontSize: 15,
    fontWeight: '700',
    color: TEXT_DARK,
  },
  slotGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
    marginBottom: 8,
  },
  slotChip: {
    minWidth: '30%',
    flexGrow: 1,
    alignItems: 'center',
    paddingVertical: 10,
    paddingHorizontal: 8,
    borderRadius: 14,
    backgroundColor: '#F1F5F9',
  },
  slotChipSelected: {
    backgroundColor: TEAL,
  },
  slotChipDisabled: {
    opacity: 0.55,
  },
  slotChipText: {
    fontSize: 13,
    fontWeight: '700',
    color: TEXT_DARK,
  },
  slotChipStatus: {
    fontSize: 9,
    fontWeight: '700',
    color: '#EF4444',
    marginTop: 2,
  },
  bookNowBtn: {
    backgroundColor: TEAL,
    borderRadius: 28,
    paddingVertical: 16,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 8,
  },
  bookNowBtnDisabled: {
    backgroundColor: '#CBD5E1',
  },
  bookNowText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '800',
  },
  summaryBlock: {
    marginTop: 18,
    borderTopWidth: 1,
    borderTopColor: '#F1F5F9',
    paddingTop: 14,
  },
  summaryRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 7,
  },
  summaryLabel: {
    fontSize: 13,
    color: '#64748B',
    fontWeight: '500',
  },
  summaryValue: {
    fontSize: 13,
    fontWeight: '700',
    color: TEXT_DARK,
    maxWidth: '55%',
    textAlign: 'right',
  },
  summaryDivider: {
    height: 1,
    backgroundColor: '#F1F5F9',
    marginVertical: 8,
  },
  summaryTotalRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  summaryTotalLabel: {
    fontSize: 15,
    fontWeight: '800',
    color: TEXT_DARK,
  },
  summaryTotalValue: {
    fontSize: 22,
    fontWeight: '900',
    color: TEAL_DARK,
  },
  summaryFootnote: {
    textAlign: 'center',
    fontSize: 11,
    color: '#94A3B8',
    marginTop: 10,
    lineHeight: 16,
  },
});