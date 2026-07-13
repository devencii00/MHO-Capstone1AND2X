import { Ionicons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import React, { useCallback, useEffect, useMemo, useState } from 'react';
import {
  Modal,
  Pressable,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TextInput,
  View,
} from 'react-native';

import { SafeAreaView } from "react-native-safe-area-context";

const T = {
  green500: '#06b6d4',
  green600: '#16A34A',
  green700: '#15803D',
  slate50: '#f8fafc',
  slate100: '#f1f5f9',
  slate200: '#e2e8f0',
  slate300: '#cbd5e1',
  slate400: '#94a3b8',
  slate500: '#64748b',
  slate600: '#556370',
  slate700: '#334155',
  slate800: '#1e293b',
  slate900: '#0f172a',
  white: '#ffffff',
  red100: 'rgba(239,68,68,0.12)',
  red700: '#b91c1c',
  green100: 'rgba(34,197,94,0.12)',
};

const API_BASE_URL = (process.env.EXPO_PUBLIC_API_BASE_URL ?? 'http://localhost:8000/api').replace(/\/+$/, '');

type MedicalBackgroundCategory =
  | 'allergy_food'
  | 'allergy_drug'
  | 'condition'
  | 'history_present_illness'
  | 'family_social_history'
  | 'surgical_history';

type MedicalBackgroundItem = {
  medical_background_id: number;
  category: MedicalBackgroundCategory;
  name: string;
  notes: string | null;
  diagnosis_date: string | null;
  procedure_date: string | null;
};

function categoryLabel(category: MedicalBackgroundCategory): string {
  if (category === 'allergy_food') return 'Allergy (Food)';
  if (category === 'allergy_drug') return 'Allergy (Drug)';
  if (category === 'condition') return 'Condition';
  if (category === 'history_present_illness') return 'History / Present Illness';
  if (category === 'family_social_history') return 'Family / Social History';
  if (category === 'surgical_history') return 'Surgical History';
  return category;
}

function formatDate(raw: string | null | undefined): string {
  if (!raw) return '';
  const d = new Date(raw);
  if (isNaN(d.getTime())) return raw;
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
}

function toDateInputValue(raw: string | null | undefined): Date {
  if (!raw) return new Date();
  const d = new Date(raw);
  return isNaN(d.getTime()) ? new Date() : d;
}

const CATEGORIES: MedicalBackgroundCategory[] = [
  'allergy_food',
  'allergy_drug',
  'condition',
  'history_present_illness',
  'family_social_history',
  'surgical_history',
];

const WEEKDAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function CalendarModal({
  visible,
  value,
  onSelect,
  onClose,
}: {
  visible: boolean;
  value: Date | null;
  onSelect: (d: Date) => void;
  onClose: () => void;
}) {
  const [viewYear, setViewYear] = useState(() => {
    if (value) return value.getFullYear();
    return new Date().getFullYear();
  });
  const [viewMonth, setViewMonth] = useState(() => {
    if (value) return value.getMonth();
    return new Date().getMonth();
  });
  const [pickerMode, setPickerMode] = useState<'day' | 'month' | 'year'>('day');
  const [decadeStart, setDecadeStart] = useState(() => {
    const y = value ? value.getFullYear() : new Date().getFullYear();
    return Math.floor(y / 10) * 10;
  });

  useEffect(() => {
    if (visible && value) {
      setViewYear(value.getFullYear());
      setViewMonth(value.getMonth());
      setDecadeStart(Math.floor(value.getFullYear() / 10) * 10);
    }
  }, [visible, value]);

  // Day grid
  const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
  const firstWeekday = new Date(viewYear, viewMonth, 1).getDay();
  const today = new Date();
  const todayStr = `${today.getFullYear()}-${today.getMonth()}-${today.getDate()}`;
  const selectedStr = value ? `${value.getFullYear()}-${value.getMonth()}-${value.getDate()}` : '';

  const cells: (number | null)[] = [];
  for (let i = 0; i < firstWeekday; i++) cells.push(null);
  for (let d = 1; d <= daysInMonth; d++) cells.push(d);

  function prev() {
    if (pickerMode === 'day') {
      if (viewMonth === 0) { setViewYear((y) => y - 1); setViewMonth(11); }
      else { setViewMonth((m) => m - 1); }
    } else if (pickerMode === 'year') {
      setDecadeStart((d) => d - 10);
    }
  }

  function next() {
    if (pickerMode === 'day') {
      if (viewMonth === 11) { setViewYear((y) => y + 1); setViewMonth(0); }
      else { setViewMonth((m) => m + 1); }
    } else if (pickerMode === 'year') {
      setDecadeStart((d) => d + 10);
    }
  }

  const canGoNextDay = useMemo(() => {
    if (pickerMode !== 'day') return true;
    const next = viewMonth === 11 ? new Date(viewYear + 1, 0, 1) : new Date(viewYear, viewMonth + 1, 1);
    return next <= today;
  }, [viewYear, viewMonth, pickerMode]);

  function selectMonth(m: number) {
    setViewMonth(m);
    setPickerMode('day');
  }

  function selectYear(y: number) {
    setViewYear(y);
    setPickerMode('month');
  }

  const years: number[] = [];
  for (let i = 0; i < 10; i++) years.push(decadeStart + i);

  function renderHeaderNav() {
    if (pickerMode === 'year') {
      return (
        <View style={styles.calHeader}>
          <Pressable onPress={prev} style={styles.calNavBtn}>
            <Ionicons name="chevron-back" size={20} color={T.slate700} />
          </Pressable>
          <Text style={styles.calHeaderText}>
            {decadeStart} – {decadeStart + 9}
          </Text>
          <Pressable onPress={next} style={styles.calNavBtn}>
            <Ionicons name="chevron-forward" size={20} color={T.slate700} />
          </Pressable>
        </View>
      );
    }

    return (
      <View style={styles.calHeader}>
        <Pressable onPress={prev} style={styles.calNavBtn}>
          <Ionicons name="chevron-back" size={20} color={T.slate700} />
        </Pressable>
        <Pressable onPress={() => setPickerMode(pickerMode === 'day' ? 'month' : 'year')} style={styles.calHeaderBtn}>
          <Text style={styles.calHeaderText}>
            {pickerMode === 'day' ? `${MONTHS[viewMonth]} ${viewYear}` : viewYear}
          </Text>
          <Ionicons name="chevron-down" size={14} color={T.slate500} />
        </Pressable>
        <Pressable
          onPress={canGoNextDay ? next : undefined}
          style={[styles.calNavBtn, !canGoNextDay && { opacity: 0.25 }]}
        >
          <Ionicons name="chevron-forward" size={20} color={T.slate700} />
        </Pressable>
      </View>
    );
  }

  function renderBody() {
    if (pickerMode === 'month') {
      return (
        <View style={styles.calGrid}>
          {MONTHS_SHORT.map((m, i) => {
            const date = new Date(viewYear, i, 1);
            const isFuture = date > today;
            const isSelected = value && value.getFullYear() === viewYear && value.getMonth() === i;
            return (
              <Pressable
                key={m}
                onPress={() => { if (!isFuture) selectMonth(i); }}
                style={[styles.calMonthCell, isSelected && styles.calDaySelected]}
              >
                <Text style={[styles.calMonthText, isSelected && styles.calDayTextSelected, isFuture && styles.calDayTextFuture]}>
                  {m}
                </Text>
              </Pressable>
            );
          })}
        </View>
      );
    }

    if (pickerMode === 'year') {
      return (
        <View style={styles.calGrid}>
          {years.map((y) => {
            const date = new Date(y, 0, 1);
            const isFuture = date > today;
            const isSelected = value && value.getFullYear() === y;
            return (
              <Pressable
                key={y}
                onPress={() => { if (!isFuture) selectYear(y); }}
                style={[styles.calYearCell, isSelected && styles.calDaySelected]}
              >
                <Text style={[styles.calYearText, isSelected && styles.calDayTextSelected, isFuture && styles.calDayTextFuture]}>
                  {y}
                </Text>
              </Pressable>
            );
          })}
        </View>
      );
    }

    // Day mode
    return (
      <>
        {/* Weekday headers */}
        <View style={styles.calWeekdayRow}>
          {WEEKDAYS.map((w) => (
            <Text key={w} style={styles.calWeekdayText}>{w}</Text>
          ))}
        </View>
        <View style={styles.calGrid}>
          {cells.map((day, i) => {
            if (day == null) return <View key={`blank-${i}`} style={styles.calDayCell} />;
            const date = new Date(viewYear, viewMonth, day);
            const dayStr = `${viewYear}-${viewMonth}-${day}`;
            const isToday = dayStr === todayStr;
            const isSelected = dayStr === selectedStr;
            const isFuture = date > today;
            return (
              <Pressable
                key={`day-${day}`}
                onPress={() => { if (!isFuture) { onSelect(date); onClose(); } }}
                style={[styles.calDayCell, isSelected && styles.calDaySelected, isToday && !isSelected && styles.calDayToday]}
              >
                <Text style={[styles.calDayText, isSelected && styles.calDayTextSelected, isFuture && styles.calDayTextFuture]}>
                  {day}
                </Text>
              </Pressable>
            );
          })}
        </View>
      </>
    );
  }

  return (
    <Modal visible={visible} transparent animationType="fade" onRequestClose={onClose}>
      <Pressable style={styles.calOverlay} onPress={onClose}>
        <Pressable style={styles.calContainer} onPress={(e) => e.stopPropagation()}>
          {renderHeaderNav()}
          {renderBody()}
          {/* Footer */}
          <View style={styles.calFooter}>
            <Pressable onPress={onClose} style={styles.calCloseBtn}>
              <Text style={styles.calCloseBtnText}>Cancel</Text>
            </Pressable>
            {pickerMode === 'day' && value ? (
              <Pressable onPress={onClose} style={styles.calDoneBtn}>
                <Text style={styles.calDoneBtnText}>Done</Text>
              </Pressable>
            ) : null}
            {pickerMode !== 'day' ? (
              <Pressable onPress={() => setPickerMode('day')} style={styles.calDoneBtn}>
                <Text style={styles.calDoneBtnText}>Back</Text>
              </Pressable>
            ) : null}
          </View>
        </Pressable>
      </Pressable>
    </Modal>
  );
}

export default function PatientMedicalBackgroundScreen() {
  const router = useRouter();
  const isOnboarding = Boolean((globalThis as any)?.currentUser?.is_first_login);
  const [items, setItems] = useState<MedicalBackgroundItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [deletingId, setDeletingId] = useState<number | null>(null);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const [category, setCategory] = useState<MedicalBackgroundCategory>('condition');
  const [categoryMenuOpen, setCategoryMenuOpen] = useState(false);
  const [name, setName] = useState('');
  const [notes, setNotes] = useState('');
  const [diagnosisDate, setDiagnosisDate] = useState<Date | null>(null);
  const [procedureDate, setProcedureDate] = useState<Date | null>(null);
  const [showDiagnosisPicker, setShowDiagnosisPicker] = useState(false);
  const [showProcedurePicker, setShowProcedurePicker] = useState(false);
  const [activeDateField, setActiveDateField] = useState<'diagnosis' | 'procedure' | null>(null);

  const canSave = useMemo(() => {
    return name.trim().length > 0 && !saving;
  }, [name, saving]);

  async function load() {
    setLoading(true);
    setError('');
    try {
      const token = (globalThis as any)?.apiToken as string | undefined;
      if (!token) {
        setError('Please log in again.');
        return;
      }

      const res = await fetch(`${API_BASE_URL}/medical-backgrounds?per_page=100`, {
        headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        const msg = typeof data?.message === 'string' && data.message.length > 0 ? data.message : 'Unable to load medical background.';
        setError(msg);
        return;
      }

      const raw = Array.isArray(data?.data) ? data.data : Array.isArray(data) ? data : [];
      const mapped: MedicalBackgroundItem[] = raw
        .map((r: any) => ({
          medical_background_id: Number(r?.medical_background_id),
          category: String(r?.category ?? 'condition') as MedicalBackgroundCategory,
          name: r?.name != null ? String(r.name) : '',
          notes: r?.notes != null ? String(r.notes) : null,
          diagnosis_date: r?.diagnosis_date != null ? String(r.diagnosis_date) : null,
          procedure_date: r?.procedure_date != null ? String(r.procedure_date) : null,
        }))
        .filter((x: MedicalBackgroundItem) => x.medical_background_id > 0);
      setItems(mapped);
    } catch {
      setError('Network error. Please try again.');
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    load();
  }, []);

  function resetForm() {
    setName('');
    setNotes('');
    setDiagnosisDate(null);
    setProcedureDate(null);
    setCategory('condition');
  }

  async function addEntry() {
    const trimmedName = name.trim();
    const trimmedNotes = notes.trim();
    if (!trimmedName) {
      setError('Please enter a name.');
      return;
    }

    setSaving(true);
    setError('');
    setSuccess('');
    try {
      const token = (globalThis as any)?.apiToken as string | undefined;
      if (!token) {
        setError('Please log in again.');
        return;
      }

      const body: Record<string, any> = {
        category,
        name: trimmedName,
      };
      if (trimmedNotes.length > 0) body.notes = trimmedNotes;
      if (diagnosisDate) body.diagnosis_date = formatDate(diagnosisDate.toISOString());
      if (procedureDate) body.procedure_date = formatDate(procedureDate.toISOString());

      const res = await fetch(`${API_BASE_URL}/medical-backgrounds`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(body),
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        const msg = typeof data?.message === 'string' && data.message.length > 0 ? data.message : 'Unable to save entry.';
        setError(msg);
        return;
      }

      resetForm();
      setSuccess('Saved.');
      await load();
    } catch {
      setError('Network error. Please try again.');
    } finally {
      setSaving(false);
    }
  }

  async function deleteEntry(id: number) {
    if (!id) return;
    setDeletingId(id);
    setError('');
    setSuccess('');
    try {
      const token = (globalThis as any)?.apiToken as string | undefined;
      if (!token) {
        setError('Please log in again.');
        return;
      }

      const res = await fetch(`${API_BASE_URL}/medical-backgrounds/${id}`, {
        method: 'DELETE',
        headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        const msg = typeof data?.message === 'string' && data.message.length > 0 ? data.message : 'Unable to delete entry.';
        setError(msg);
        return;
      }
      setSuccess('Deleted.');
      await load();
    } catch {
      setError('Network error. Please try again.');
    } finally {
      setDeletingId(null);
    }
  }

  function openDatePicker(field: 'diagnosis' | 'procedure') {
    setCategoryMenuOpen(false);
    setActiveDateField(field);
    if (field === 'diagnosis') {
      setShowDiagnosisPicker(true);
    } else {
      setShowProcedurePicker(true);
    }
  }

  function onDateSelect(date: Date) {
    if (activeDateField === 'diagnosis') {
      setDiagnosisDate(date);
    } else if (activeDateField === 'procedure') {
      setProcedureDate(date);
    }
  }

  return (
    <SafeAreaView style={styles.safe}>
      <StatusBar barStyle="light-content" backgroundColor={T.green700} />

      <ScrollView style={{ flex: 1 }} contentContainerStyle={{ flexGrow: 1 }} showsVerticalScrollIndicator={false}>
        <View style={styles.header}>
          <View style={styles.circleTopRight} />
          <View style={styles.circleBottomLeft} />
          <View style={styles.circleMidLeft} />
          <View style={styles.headerInner}>
            <View style={{ flex: 1 }}>
              <View style={styles.eyebrowRow}>
                <View style={[styles.eyebrowDot, { backgroundColor: 'rgba(255,255,255,0.7)' }]} />
                <Text style={[styles.eyebrowText, { color: 'rgba(255,255,255,0.8)' }]}>Patient Portal</Text>
              </View>
              <Text style={styles.headerTitle}>Medical background</Text>
              <Text style={styles.headerSub}>
                {isOnboarding ? 'Step 2 of 3 · Add allergies and conditions (optional).' : 'Add allergies and conditions anytime.'}
              </Text>
            </View>
            <Pressable
                         style={({ pressed }) => [styles.headerBtn, pressed && { opacity: 0.85 }]}
                          onPress={() => router.navigate(isOnboarding ? '/screenviews/aut-landing/fillup-info' : '/screenviews/profile' as any)}
                       >
                         <Text style={styles.headerBtnText}>Back</Text>
                       </Pressable>
          </View>
        </View>

        <View style={[styles.scroll, styles.scrollContent]}>
        {error ? <Text style={styles.inlineError}>{error}</Text> : null}
        {success ? <Text style={styles.inlineSuccess}>{success}</Text> : null}

        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <View style={styles.eyebrowRow}>
              <View style={styles.eyebrowDot} />
              <Text style={styles.eyebrowText}>Add</Text>
            </View>
            <Text style={styles.cardTitle}>Medical background</Text>
            <Text style={styles.cardSubtitle}>You can add allergies or conditions now, or leave this blank and continue.</Text>
          </View>

          <View style={styles.cardBody}>
            <Text style={styles.label}>Category</Text>
            <View style={styles.dropdownWrap}>
              <Pressable
                onPress={() => setCategoryMenuOpen((current) => !current)}
                style={({ pressed }) => [
                  styles.dropdownTrigger,
                  categoryMenuOpen && styles.dropdownTriggerActive,
                  pressed && { opacity: 0.9 },
                ]}
              >
                <Text style={styles.dropdownTriggerText}>{categoryLabel(category)}</Text>
                <Ionicons
                  name={categoryMenuOpen ? 'chevron-up-outline' : 'chevron-down-outline'}
                  size={18}
                  color={T.slate600}
                />
              </Pressable>
              {categoryMenuOpen ? (
                <View style={styles.dropdownMenu}>
                  <ScrollView style={{ maxHeight: 240 }} nestedScrollEnabled>
                    {CATEGORIES.map((c) => (
                      <Pressable
                        key={c}
                        onPress={() => {
                          setCategory(c);
                          setCategoryMenuOpen(false);
                        }}
                        style={({ pressed }) => [
                          styles.dropdownItem,
                          category === c && styles.dropdownItemActive,
                          pressed && { opacity: 0.9 },
                        ]}
                      >
                        <Text style={[styles.dropdownItemText, category === c && styles.dropdownItemTextActive]}>
                          {categoryLabel(c)}
                        </Text>
                        {category === c ? <Ionicons name="checkmark-outline" size={18} color={T.green700} /> : null}
                      </Pressable>
                    ))}
                  </ScrollView>
                </View>
              ) : null}
            </View>

            <Text style={[styles.label, { marginTop: 12 }]}>Name</Text>
            <TextInput
              value={name}
              onChangeText={setName}
              onFocus={() => setCategoryMenuOpen(false)}
              placeholder="e.g. Penicillin, Asthma, Shellfish"
              placeholderTextColor="#9ca3af"
              style={styles.input}
            />

            <Text style={[styles.label, { marginTop: 12 }]}>Diagnosis Date</Text>
            <Pressable
              onPress={() => openDatePicker('diagnosis')}
              style={({ pressed }) => [
                styles.dateInput,
                pressed && { opacity: 0.85 },
              ]}
            >
              <Text style={[styles.dateInputText, !diagnosisDate && { color: T.slate400 }]}>
                {diagnosisDate ? formatDate(diagnosisDate.toISOString()) : 'YYYY-MM-DD'}
              </Text>
              <Ionicons name="calendar-outline" size={18} color={T.slate600} />
            </Pressable>

            <Text style={[styles.label, { marginTop: 12 }]}>Procedure Date</Text>
            <Pressable
              onPress={() => openDatePicker('procedure')}
              style={({ pressed }) => [
                styles.dateInput,
                pressed && { opacity: 0.85 },
              ]}
            >
              <Text style={[styles.dateInputText, !procedureDate && { color: T.slate400 }]}>
                {procedureDate ? formatDate(procedureDate.toISOString()) : 'YYYY-MM-DD'}
              </Text>
              <Ionicons name="calendar-outline" size={18} color={T.slate600} />
            </Pressable>

            <Text style={[styles.label, { marginTop: 12 }]}>Notes (optional)</Text>
            <TextInput
              value={notes}
              onChangeText={setNotes}
              onFocus={() => setCategoryMenuOpen(false)}
              placeholder="Additional details"
              placeholderTextColor="#9ca3af"
              style={[styles.input, { height: 84, textAlignVertical: 'top' }]}
              multiline
            />

            <Pressable
              onPress={addEntry}
              disabled={!canSave}
              style={({ pressed }) => [
                styles.primaryButton,
                (!canSave || saving) && { opacity: 0.55 },
                pressed && { opacity: 0.85 },
              ]}
            >
              <Text style={styles.primaryButtonText}>{saving ? 'Saving…' : 'Add entry'}</Text>
            </Pressable>
          </View>
        </View>

        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <View style={styles.eyebrowRow}>
              <View style={styles.eyebrowDot} />
              <Text style={styles.eyebrowText}>List</Text>
            </View>
            <Text style={styles.cardTitle}>Your entries</Text>
            <Text style={styles.cardSubtitle}>
              {loading ? 'Loading…' : items.length === 0 ? 'No entries yet.' : 'Tap Delete to remove an entry.'}
            </Text>
          </View>

          <View style={styles.cardBody}>
            {items.map((it) => {
              const dDate = formatDate(it.diagnosis_date);
              const pDate = formatDate(it.procedure_date);
              const dateParts: string[] = [];
              if (dDate) dateParts.push(`Diagnosed: ${dDate}`);
              if (pDate) dateParts.push(`Procedure: ${pDate}`);
              return (
                <View key={it.medical_background_id} style={styles.itemRow}>
                  <View style={{ flex: 1 }}>
                    <Text style={styles.itemTitle}>{it.name}</Text>
                    <Text style={styles.itemSub}>
                      {categoryLabel(it.category)}
                      {dateParts.length > 0 ? ` · ${dateParts.join(' · ')}` : ''}
                      {it.notes ? ` · ${it.notes}` : ''}
                    </Text>
                  </View>
                  <Pressable
                    onPress={() => deleteEntry(it.medical_background_id)}
                    disabled={deletingId === it.medical_background_id}
                    style={({ pressed }) => [
                      styles.dangerBtn,
                      deletingId === it.medical_background_id && { opacity: 0.6 },
                      pressed && { opacity: 0.85 },
                    ]}
                  >
                    <Text style={styles.dangerBtnText}>{deletingId === it.medical_background_id ? '…' : 'Delete'}</Text>
                  </Pressable>
                </View>
              );
            })}
          </View>
        </View>

        {isOnboarding ? (
          <Pressable
            onPress={() => router.push('/screenviews/verify' as any)}
            style={({ pressed }) => [styles.primaryButton, pressed && { opacity: 0.85 }]}
          >
            <Text style={styles.primaryButtonText}>Next</Text>
          </Pressable>
        ) : null}

        {/* <Pressable
          onPress={() => router.back()}
          style={({ pressed }) => [styles.secondaryButton, pressed && { opacity: 0.85 }]}
        >
          <Text style={styles.secondaryButtonText}>Done</Text>
        </Pressable> */}
        </View>

        <CalendarModal
          visible={showDiagnosisPicker}
          value={diagnosisDate}
          onSelect={onDateSelect}
          onClose={() => {
            setShowDiagnosisPicker(false);
            setActiveDateField(null);
          }}
        />
        <CalendarModal
          visible={showProcedurePicker}
          value={procedureDate}
          onSelect={onDateSelect}
          onClose={() => {
            setShowProcedurePicker(false);
            setActiveDateField(null);
          }}
        />
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: T.green700 },
  header: { backgroundColor:
     T.green700, 
     paddingHorizontal: 20, 
     paddingTop: 50,
      paddingBottom: 30 },
  
  
  headerInner: { 
    flexDirection: 'row',
     alignItems: 'flex-start',
      justifyContent: 'space-between',
       gap: 12 },

  headerTitle: {
    fontFamily: 'serif',
    fontSize: 30,
    fontWeight: '700',
    color: T.white,
    marginBottom: 2,
    letterSpacing: 0.3,
  },
  headerSub: { fontSize: 12, color: 'rgba(255,255,255,0.75)', fontWeight: '400' },
  headerBtn: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 999,
    backgroundColor: 'rgba(255,255,255,0.16)',
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.25)',
  },
  headerBtnText: { color: T.white, fontSize: 12, fontWeight: '600' },
  eyebrowRow: { flexDirection: 'row', alignItems: 'center', gap: 5, marginBottom: 4 },
  eyebrowDot: { width: 6, height: 6, borderRadius: 3, backgroundColor: T.green600 },
  eyebrowText: { fontSize: 9, fontWeight: '700', letterSpacing: 0.9, textTransform: 'uppercase', color: T.green600 },
  scroll: {
    flex: 1,
    backgroundColor: T.slate100,
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    marginTop: -16,
  },
    circleTopRight: {
    position: 'absolute',
    top: -80,
    right: -80,
    width: 280,
    height: 280,
    borderRadius: 140,
    backgroundColor: 'rgba(255,255,255,0.08)',
  },
  circleBottomLeft: {
    position: 'absolute',
    bottom: -80,
    left: -60,
    width: 190,
    height: 190,
    borderRadius: 95,
    backgroundColor: 'rgba(255,255,255,0.07)',
  },
  circleMidLeft: {
    position: 'absolute',
    top: 30,
    left: -90,
    width: 180,
    height: 180,
    borderRadius: 90,
    backgroundColor: 'rgba(255,255,255,0.05)',
  },


  scrollContent: { padding: 16, gap: 12, paddingBottom: 28 },
  inlineError: {
    backgroundColor: T.red100,
    borderColor: 'rgba(239,68,68,0.25)',
    borderWidth: 1,
    color: T.red700,
    padding: 10,
    borderRadius: 12,
    fontSize: 12,
  },
  inlineSuccess: {
    backgroundColor: T.green100,
    borderColor: 'rgba(34,197,94,0.25)',
    borderWidth: 1,
    color: T.green700,
    padding: 10,
    borderRadius: 12,
    fontSize: 12,
  },
  card: {
    backgroundColor: T.white,
    borderWidth: 1,
    borderColor: T.slate200,
    borderRadius: 18,
    overflow: 'hidden',
    shadowColor: '#0f172a',
    shadowOpacity: 0.04,
    shadowOffset: { width: 0, height: 2 },
    shadowRadius: 10,
    elevation: 2,
  },
  cardHeader: { paddingHorizontal: 14, paddingTop: 14, paddingBottom: 10 },
  cardTitle: { fontSize: 16, fontWeight: '700', color: T.slate900, marginBottom: 3 },
  cardSubtitle: { fontSize: 12, color: T.slate500 },
  cardBody: { paddingHorizontal: 14, paddingBottom: 14, gap: 10 },
  label: { fontSize: 11, fontWeight: '600', color: T.slate700 },
  input: {
    borderRadius: 12,
    borderWidth: 1,
    borderColor: T.slate200,
    paddingHorizontal: 12,
    paddingVertical: 10,
    fontSize: 13,
    color: T.slate800,
    backgroundColor: T.white,
  },
  dateInput: {
    borderRadius: 12,
    borderWidth: 1,
    borderColor: T.slate200,
    paddingHorizontal: 12,
    paddingVertical: 10,
    fontSize: 13,
    color: T.slate800,
    backgroundColor: T.white,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 10,
  },
  dateInputText: {
    fontSize: 13,
    color: T.slate800,
    flex: 1,
  },
  dropdownWrap: {
    position: 'relative',
    zIndex: 10,
  },
  dropdownTrigger: {
    minHeight: 46,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: T.slate200,
    paddingHorizontal: 12,
    paddingVertical: 10,
    backgroundColor: T.white,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 10,
  },
  dropdownTriggerActive: {
    borderColor: 'rgba(8,145,178,0.35)',
  },
  dropdownTriggerText: {
    fontSize: 13,
    color: T.slate800,
    flex: 1,
  },
  dropdownMenu: {
    marginTop: 8,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    overflow: 'hidden',
    shadowColor: '#0f172a',
    shadowOpacity: 0.06,
    shadowOffset: { width: 0, height: 4 },
    shadowRadius: 10,
    elevation: 3,
  },
  dropdownItem: {
    minHeight: 44,
    paddingHorizontal: 12,
    paddingVertical: 10,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 10,
    borderBottomWidth: 1,
    borderBottomColor: T.slate100,
  },
  dropdownItemActive: {
    backgroundColor: '#ecfeff',
  },
  dropdownItemText: {
    fontSize: 13,
    color: T.slate700,
    flex: 1,
  },
  dropdownItemTextActive: {
    color: T.green700,
    fontWeight: '700',
  },
  primaryButton: {
    marginTop: 6,
    borderRadius: 14,
    backgroundColor: T.green700,
    paddingVertical: 12,
    alignItems: 'center',
    justifyContent: 'center',
  },
  primaryButtonText: { color: T.white, fontSize: 13, fontWeight: '700' },
  itemRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.slate50,
    padding: 12,
    borderRadius: 14,
  },
  itemTitle: { fontSize: 13, fontWeight: '700', color: T.slate900, marginBottom: 2 },
  itemSub: { fontSize: 12, color: T.slate600 },
  dangerBtn: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 999,
    backgroundColor: T.red100,
    borderWidth: 1,
    borderColor: 'rgba(239,68,68,0.25)',
  },
  dangerBtnText: { fontSize: 12, fontWeight: '700', color: T.red700 },
  secondaryButton: {
    borderRadius: 14,
    backgroundColor: T.white,
    borderWidth: 1,
    borderColor: T.slate200,
    paddingVertical: 12,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 2,
  },
  secondaryButtonText: { color: T.slate800, fontSize: 13, fontWeight: '700' },

  /* Calendar Modal */
  calOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.45)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  calContainer: {
    width: '100%',
    maxWidth: 340,
    backgroundColor: T.white,
    borderRadius: 20,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOpacity: 0.15,
    shadowOffset: { width: 0, height: 8 },
    shadowRadius: 24,
    elevation: 10,
  },
  calHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: T.slate100,
  },
  calNavBtn: {
    width: 36,
    height: 36,
    borderRadius: 18,
    alignItems: 'center',
    justifyContent: 'center',
  },
  calHeaderText: {
    fontSize: 15,
    fontWeight: '700',
    color: T.slate800,
  },
  calHeaderBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 8,
  },
  calWeekdayRow: {
    flexDirection: 'row',
    paddingHorizontal: 8,
    paddingTop: 10,
    paddingBottom: 4,
  },
  calWeekdayText: {
    flex: 1,
    textAlign: 'center',
    fontSize: 10,
    fontWeight: '700',
    color: T.slate400,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  calGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    paddingHorizontal: 8,
    paddingBottom: 8,
  },
  calDayCell: {
    width: '14.28%',
    aspectRatio: 1.2,
    alignItems: 'center',
    justifyContent: 'center',
  },
  calDaySelected: {
    backgroundColor: T.green700,
    borderRadius: 10,
  },
  calDayToday: {
    borderWidth: 1.5,
    borderColor: T.green600,
    borderRadius: 10,
  },
  calDayText: {
    fontSize: 13,
    fontWeight: '500',
    color: T.slate800,
  },
  calDayTextSelected: {
    color: T.white,
    fontWeight: '700',
  },
  calDayTextFuture: {
    color: T.slate300,
  },
  calMonthCell: {
    width: '25%',
    aspectRatio: 1.6,
    alignItems: 'center',
    justifyContent: 'center',
    marginVertical: 4,
  },
  calMonthText: {
    fontSize: 13,
    fontWeight: '600',
    color: T.slate700,
  },
  calYearCell: {
    width: '25%',
    aspectRatio: 1.6,
    alignItems: 'center',
    justifyContent: 'center',
    marginVertical: 4,
  },
  calYearText: {
    fontSize: 14,
    fontWeight: '600',
    color: T.slate700,
  },
  calFooter: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'flex-end',
    gap: 8,
    paddingHorizontal: 16,
    paddingVertical: 12,
    borderTopWidth: 1,
    borderTopColor: T.slate100,
  },
  calCloseBtn: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 10,
  },
  calCloseBtnText: {
    fontSize: 13,
    fontWeight: '600',
    color: T.slate500,
  },
  calDoneBtn: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 10,
    backgroundColor: T.green700,
  },
  calDoneBtnText: {
    fontSize: 13,
    fontWeight: '700',
    color: T.white,
  },
});
