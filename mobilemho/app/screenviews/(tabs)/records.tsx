import React, { useEffect, useMemo, useRef, useState } from "react";
import type { ReactNode } from "react";
import {
  ActivityIndicator,
  Animated,
  Platform,
  Pressable,
  ScrollView,
  StatusBar,
  StyleProp,
  StyleSheet,
  Text,
  View,
  ViewStyle,
} from "react-native";

import { SafeAreaView } from "react-native-safe-area-context";
import DateTimePicker from "@react-native-community/datetimepicker";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";

const T = {
  green500: "#06b6d4",
  green600: "#16A34A",
  green700: "#15803D",
  green100: "#cffafe",
  slate50: "#f8fafc",
  slate100: "#f1f5f9",
  slate200: "#e2e8f0",
  slate300: "#cbd5e1",
  slate400: "#94a3b8",
  slate500: "#64748b",
  slate600: "#475569",
  slate700: "#334155",
  slate800: "#1e293b",
  slate900: "#0f172a",
  white: "#ffffff",
  red700: "#b91c1c",
};

const API_BASE_URL = (
  process.env.EXPO_PUBLIC_API_BASE_URL ?? "http://localhost:8000/api"
).replace(/\/+$/, "");

type RecordsTabKey = "visits" | "prescriptions";

type AnimatedCardProps = {
  children: ReactNode;
  delay?: number;
  style?: StyleProp<ViewStyle>;
};

type VisitHistoryItem = {
  id: string;
  dateKey: string;
  date: string;
  patientName: string;
  doctor: string;
  doctorInitials: string;
  reason: string;
  diagnosis: string;
  treatment: string;
  paymentStatus: string;
  appointmentType: string;
  prescriptionMedicines: PrescriptionMedicineItem[];
};

type PrescriptionMedicineItem = {
  id: string;
  name: string;
  dosage: string;
  frequency: string;
  duration: string;
  instructions: string;
};

type PrescriptionHistoryItem = {
  id: string;
  dateKey: string;
  date: string;
  doctor: string;
  doctorInitials: string;
  patientName: string;
  summary: string;
  reason: string;
  prescriptionNotes: string;
  diagnosis: string;
  treatment: string;
  medicines: PrescriptionMedicineItem[];
};

function AnimatedCard({ children, delay = 0, style }: AnimatedCardProps) {
  const anim = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    Animated.timing(anim, {
      toValue: 1,
      duration: 480,
      delay,
      useNativeDriver: true,
    }).start();
  }, [anim, delay]);

  return (
    <Animated.View
      style={[
        {
          opacity: anim,
          transform: [
            {
              translateY: anim.interpolate({
                inputRange: [0, 1],
                outputRange: [18, 0],
              }),
            },
          ],
        },
        style,
      ]}
    >
      {children}
    </Animated.View>
  );
}

function formatDateTime(value: any): string {
  if (!value) return "Date unavailable";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return "Date unavailable";
  return `${date.toLocaleDateString()} · ${date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}`;
}

function formatDateOnly(value: any): string {
  if (!value) return "Date unavailable";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return "Date unavailable";
  return date.toLocaleDateString();
}

function toDateKey(value: any): string {
  if (!value) return "";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return "";
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

function formatDateKeyLabel(value: string): string {
  if (!value) return "Date unavailable";
  return formatDateOnly(value);
}

function formatDoctorName(raw: any): string {
  const first = raw?.firstname ? String(raw.firstname) : "";
  const last = raw?.lastname ? String(raw.lastname) : "";
  const full = `Dr. ${[first, last].filter(Boolean).join(" ")}`.trim();
  return full === "Dr." ? "Doctor" : full;
}

function formatDoctorInitials(raw: any): string {
  if (!raw) return "Doctor";
  const first = raw?.firstname ? String(raw.firstname) : "";
  const middle = raw?.middlename ? String(raw.middlename) : "";
  const last = raw?.lastname ? String(raw.lastname) : "";
  const firstInit = first ? first.charAt(0).toUpperCase() + "." : "";
  const middleInit = middle ? middle.charAt(0).toUpperCase() + "." : "";
  const parts = [firstInit, middleInit, last].filter(Boolean);
  return parts.length > 0 ? `Dr. ${parts.join(" ")}` : "Doctor";
}

function formatPatientName(raw: any): string {
  if (!raw) return "Patient";
  const first = raw?.firstname ? String(raw.firstname) : "";
  const middle = raw?.middlename ? String(raw.middlename) : "";
  const last = raw?.lastname ? String(raw.lastname) : "";
  const parts = [first, middle, last].filter(Boolean);
  return parts.length > 0 ? parts.join(" ") : "Patient";
}

function formatAppointmentType(value: any): string {
  const raw = typeof value === "string" ? value.trim().toLowerCase() : "";
  if (raw === "walk_in" || raw === "walk-in" || raw === "walk in")
    return "Walk-in";
  if (raw === "scheduled") return "Scheduled";
  return "Visit";
}

function normalizeText(value: any): string {
  return typeof value === "string" ? value.trim() : "";
}

function TabCard({
  title,
  value,
  subtitle,
  icon,
  active,
  onPress,
  delay,
}: {
  title: string;
  value: string;
  subtitle: string;
  icon: React.ComponentProps<typeof Ionicons>["name"];
  active: boolean;
  onPress: () => void;
  delay: number;
}) {
  return (
    <AnimatedCard delay={delay} style={styles.tabCardWrap}>
      <Pressable
        onPress={onPress}
        style={({ pressed }) => [
          styles.tabCard,
          active && styles.tabCardActive,
          pressed && { opacity: 0.88 },
        ]}
      >
        <View
          style={[styles.tabIconCircle, active && styles.tabIconCircleActive]}
        >
          <Ionicons
            name={icon}
            size={18}
            color={active ? T.white : T.green700}
          />
        </View>
        <Text
          style={[styles.tabCardTitle, active && styles.tabCardTitleActive]}
        >
          {title}
        </Text>
        <Text
          style={[styles.tabCardValue, active && styles.tabCardValueActive]}
        >
          {value}
        </Text>
        <Text
          style={[
            styles.tabCardSubtitle,
            active && styles.tabCardSubtitleActive,
          ]}
        >
          {subtitle}
        </Text>
      </Pressable>
    </AnimatedCard>
  );
}

export default function PatientRecordsScreen() {
  const router = useRouter();
  const [activeTab, setActiveTab] = useState<RecordsTabKey>("visits");
  const [visits, setVisits] = useState<VisitHistoryItem[]>([]);
  const [prescriptions, setPrescriptions] = useState<PrescriptionHistoryItem[]>(
    [],
  );
  const [expandedKey, setExpandedKey] = useState<string | null>(null);
  const [selectedDateKey, setSelectedDateKey] = useState("");
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    let cancelled = false;

    async function loadRecords() {
      setLoading(true);
      setError("");

      try {
        const token = (globalThis as any)?.apiToken as string | undefined;
        if (!token) {
          setError("Please log in again.");
          return;
        }

        const [visitsRes, prescriptionsRes] = await Promise.all([
          fetch(`${API_BASE_URL}/visits?per_page=100`, {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          }),
          fetch(`${API_BASE_URL}/prescriptions?per_page=100`, {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          }),
        ]);

        const [visitsData, prescriptionsData] = await Promise.all([
          visitsRes.json().catch(() => ({})),
          prescriptionsRes.json().catch(() => ({})),
        ]);

        if (!visitsRes.ok || !prescriptionsRes.ok) {
          const anyMessage = visitsData?.message || prescriptionsData?.message;
          if (!cancelled) {
            setError(
              typeof anyMessage === "string" && anyMessage.length > 0
                ? anyMessage
                : "Unable to load records history.",
            );
          }
          return;
        }

        const visitRows = Array.isArray(visitsData?.data)
          ? visitsData.data
          : [];
        const prescriptionRows = Array.isArray(prescriptionsData?.data)
          ? prescriptionsData.data
          : [];

        const mappedVisits: VisitHistoryItem[] = visitRows
          .map((row: any) => {
            const doctorRaw =
              row?.appointment?.doctor ||
              row?.prescriptions?.[0]?.doctor ||
              null;
            const doctor = doctorRaw ? formatDoctorName(doctorRaw) : "Doctor";
            const doctorInitials = doctorRaw
              ? formatDoctorInitials(doctorRaw)
              : "Doctor";
            const patientRaw = row?.appointment?.patient || null;
            const patientName = patientRaw
              ? formatPatientName(patientRaw)
              : "Patient";
            const prescriptionMedicines: PrescriptionMedicineItem[] = (
              Array.isArray(row?.prescriptions) ? row.prescriptions : []
            )
              .flatMap((prescription: any) =>
                Array.isArray(prescription?.items) ? prescription.items : [],
              )
              .map((item: any, idx: number) => ({
                id: String(
                  item?.item_id ??
                    `${row?.transaction_id ?? "v"}-${item?.medicine_id ?? idx}`,
                ),
                name:
                  normalizeText(
                    item?.medicine_name ||
                      item?.medicine?.generic_name ||
                      item?.medicine?.brand_name,
                  ) || "Medicine",
                dosage: normalizeText(item?.dosage),
                frequency: normalizeText(item?.frequency),
                duration: normalizeText(item?.duration),
                instructions: normalizeText(item?.instructions),
              }));

            return {
              id: String(row?.transaction_id ?? ""),
              dateKey: toDateKey(
                row?.visit_datetime ??
                  row?.transaction_datetime ??
                  row?.appointment?.appointment_datetime,
              ),
              date: formatDateTime(
                row?.visit_datetime ??
                  row?.transaction_datetime ??
                  row?.appointment?.appointment_datetime,
              ),
              patientName,
              doctor,
              doctorInitials,
              reason:
                normalizeText(row?.appointment?.reason_for_visit) ||
                "Clinic visit",
              diagnosis:
                normalizeText(row?.diagnosis) || "No diagnosis recorded.",
              treatment:
                normalizeText(row?.treatment_notes) ||
                "No treatment notes recorded.",
              paymentStatus: normalizeText(row?.payment_status) || "Unknown",
              appointmentType: formatAppointmentType(
                row?.appointment?.appointment_type,
              ),
              prescriptionMedicines,
            };
          })
          .filter((item: VisitHistoryItem) => item.id.length > 0);

        const mappedPrescriptions: PrescriptionHistoryItem[] = prescriptionRows
          .map((row: any) => {
            const doctorRaw = row?.doctor || null;
            const doctor = doctorRaw ? formatDoctorName(doctorRaw) : "Doctor";
            const doctorInitials = doctorRaw
              ? formatDoctorInitials(doctorRaw)
              : "Doctor";
            const patientRaw = row?.transaction?.appointment?.patient || null;
            const patientName = patientRaw
              ? formatPatientName(patientRaw)
              : "Patient";
            const medicines: PrescriptionMedicineItem[] = (
              Array.isArray(row?.items) ? row.items : []
            ).map((item: any) => ({
              id: String(
                item?.item_id ??
                  `${row?.prescription_id ?? "rx"}-${item?.medicine_id ?? Math.random()}`,
              ),
              name:
                normalizeText(
                  item?.medicine_name ||
                    item?.medicine?.generic_name ||
                    item?.medicine?.brand_name,
                ) || "Medicine",
              dosage: normalizeText(item?.dosage),
              frequency: normalizeText(item?.frequency),
              duration: normalizeText(item?.duration),
              instructions: normalizeText(item?.instructions),
            }));

            return {
              id: String(row?.prescription_id ?? ""),
              dateKey: toDateKey(
                row?.prescribed_datetime ??
                  row?.transaction?.visit_datetime ??
                  row?.transaction?.transaction_datetime,
              ),
              date: formatDateTime(
                row?.prescribed_datetime ??
                  row?.transaction?.visit_datetime ??
                  row?.transaction?.transaction_datetime,
              ),
              doctor,
              doctorInitials,
              patientName,
              summary: medicines[0]?.name ?? "Prescription",
              reason:
                normalizeText(
                  row?.transaction?.appointment?.reason_for_visit,
                ) || "Clinic visit",
              prescriptionNotes:
                normalizeText(row?.notes) || "No prescription notes recorded.",
              diagnosis:
                normalizeText(row?.transaction?.diagnosis) ||
                "No diagnosis recorded.",
              treatment:
                normalizeText(row?.transaction?.treatment_notes) ||
                "No treatment notes recorded.",
              medicines,
            };
          })
          .filter((item: PrescriptionHistoryItem) => item.id.length > 0);

        if (!cancelled) {
          setVisits(mappedVisits);
          setPrescriptions(mappedPrescriptions);
          setExpandedKey(null);
          setError("");
        }
      } catch {
        if (!cancelled) setError("Network error. Please try again.");
      } finally {
        if (!cancelled) setLoading(false);
      }
    }

    void loadRecords();
    return () => {
      cancelled = true;
    };
  }, []);

  const filteredVisits = useMemo(
    () =>
      selectedDateKey
        ? visits.filter((item) => item.dateKey === selectedDateKey)
        : visits,
    [selectedDateKey, visits],
  );
  const filteredPrescriptions = useMemo(
    () =>
      selectedDateKey
        ? prescriptions.filter((item) => item.dateKey === selectedDateKey)
        : prescriptions,
    [prescriptions, selectedDateKey],
  );
  const activeItemsCount = useMemo(() => {
    if (activeTab === "visits") return filteredVisits.length;
    return filteredPrescriptions.length;
  }, [activeTab, filteredPrescriptions.length, filteredVisits.length]);

  const availableDateKeys = useMemo(() => {
    const source = activeTab === "visits" ? visits : prescriptions;

    return Array.from(
      new Set(
        source.map((item) => item.dateKey).filter((value) => value.length > 0),
      ),
    ).sort((a, b) => b.localeCompare(a));
  }, [activeTab, prescriptions, visits]);

  function toggleDetails(key: string) {
    setExpandedKey((current) => (current === key ? null : key));
  }

  function renderVisits() {
    if (filteredVisits.length === 0) {
      return (
        <Text style={styles.emptyText}>
          No visit history found for the selected date.
        </Text>
      );
    }

    return filteredVisits.map((item) => {
      const expanded = expandedKey === `visit-${item.id}`;
      return (
        <View key={item.id} style={styles.listCard}>
          <View style={styles.listCardTop}>
            <View style={styles.listMain}>
              <Text style={styles.listTitle}>{item.patientName}</Text>
              <Text
                style={styles.listSubtitle}
              >{`${item.doctorInitials} · ${item.date}`}</Text>
              <Text
                style={styles.listMeta}
              >{`${item.appointmentType} · Payment ${item.paymentStatus}`}</Text>
            </View>
            <Pressable
              onPress={() => toggleDetails(`visit-${item.id}`)}
              style={({ pressed }) => [
                styles.detailButton,
                pressed && { opacity: 0.85 },
              ]}
            >
              <Text style={styles.detailButtonText}>
                {expanded ? "Hide details" : "View details"}
              </Text>
            </Pressable>
          </View>
          {expanded ? (
            <View style={styles.detailPanel}>
              <View style={styles.consultGrid}>
                <View style={styles.consultGridCol}>
                  <Text style={styles.detailLabel}>Diagnosis</Text>
                  <Text style={styles.detailValue}>{item.diagnosis}</Text>
                </View>
                <View style={styles.consultGridCol}>
                  <Text style={styles.detailLabel}>Treatment Notes</Text>
                  <Text style={styles.detailValue}>{item.treatment}</Text>
                </View>
              </View>
              <Text style={styles.detailLabel}>Prescription Items</Text>
              {item.prescriptionMedicines.length > 0 ? (
                item.prescriptionMedicines.map((medicine) => (
                  <View key={medicine.id} style={styles.medicineRow}>
                    <Text style={styles.medicineName}>{medicine.name}</Text>
                    <Text style={styles.medicineMeta}>
                      {[
                        "Dosage: " + medicine.dosage,
                        "Freq: " + medicine.frequency,
                        "Duration: " + medicine.duration,
                      ]
                        .filter(
                          (part) =>
                            !part.endsWith(": ") && !part.endsWith(": -"),
                        )
                        .join(" · ") || "No dosage details"}
                    </Text>
                    {medicine.instructions ? (
                      <Text style={styles.medicineInstructions}>
                        {medicine.instructions}
                      </Text>
                    ) : null}
                  </View>
                ))
              ) : (
                <Text style={styles.detailValue}>
                  No prescription items recorded for this visit.
                </Text>
              )}
            </View>
          ) : null}
        </View>
      );
    });
  }

  function renderPrescriptions() {
    if (filteredPrescriptions.length === 0) {
      return (
        <Text style={styles.emptyText}>
          No prescription history found for the selected date.
        </Text>
      );
    }

    return filteredPrescriptions.map((item) => {
      const expanded = expandedKey === `prescription-${item.id}`;
      return (
        <View key={item.id} style={styles.listCard}>
          <View style={styles.listCardTop}>
            <View style={styles.listMain}>
              <Text style={styles.listTitle}>{item.doctorInitials}</Text>
              <Text
                style={styles.listSubtitle}
              >{`${item.patientName} · ${item.date}`}</Text>
              <Text style={styles.listMeta}>{item.reason}</Text>
            </View>
            <Pressable
              onPress={() => toggleDetails(`prescription-${item.id}`)}
              style={({ pressed }) => [
                styles.detailButton,
                pressed && { opacity: 0.85 },
              ]}
            >
              <Text style={styles.detailButtonText}>
                {expanded ? "Hide details" : "View details"}
              </Text>
            </Pressable>
          </View>
          {expanded ? (
            <View style={styles.detailPanel}>
              <Text style={styles.detailLabel}>Medicines</Text>
              {item.medicines.length > 0 ? (
                <View style={styles.rxTableHeader}>
                  <Text style={[styles.rxTableHeaderText, { flex: 2.5 }]}>
                    Medicine
                  </Text>
                  <Text style={[styles.rxTableHeaderText, { flex: 1.5 }]}>
                    Dosage
                  </Text>
                  <Text style={[styles.rxTableHeaderText, { flex: 1.5 }]}>
                    Frequency
                  </Text>
                  <Text style={[styles.rxTableHeaderText, { flex: 1 }]}>
                    Duration
                  </Text>
                  <Text style={[styles.rxTableHeaderText, { flex: 2 }]}>
                    Instructions
                  </Text>
                </View>
              ) : null}
              {item.medicines.length > 0 ? (
                item.medicines.map((medicine) => (
                  <View key={medicine.id} style={styles.rxTableRow}>
                    <Text
                      style={[styles.medicineName, { flex: 2.5 }]}
                      numberOfLines={2}
                    >
                      {medicine.name}
                    </Text>
                    <Text style={[styles.medicineMeta, { flex: 1.5 }]}>
                      {medicine.dosage || "-"}
                    </Text>
                    <Text style={[styles.medicineMeta, { flex: 1.5 }]}>
                      {medicine.frequency || "-"}
                    </Text>
                    <Text style={[styles.medicineMeta, { flex: 1 }]}>
                      {medicine.duration || "-"}
                    </Text>
                    <Text
                      style={[styles.medicineInstructions, { flex: 2 }]}
                      numberOfLines={2}
                    >
                      {medicine.instructions || "-"}
                    </Text>
                  </View>
                ))
              ) : (
                <Text style={styles.detailValue}>
                  No medicine items recorded.
                </Text>
              )}
            </View>
          ) : null}
        </View>
      );
    });
  }

  return (
    <SafeAreaView style={styles.safe}>
      <StatusBar barStyle="light-content" backgroundColor={T.green700} />
      <ScrollView
        style={styles.pageScroll}
        contentContainerStyle={styles.pageScrollContent}
        showsVerticalScrollIndicator={false}
      >
        <View style={styles.header}>
          <View style={styles.circleTopRight} />
          <View style={styles.circleBottomLeft} />
          <View style={styles.circleMidLeft} />
          <View style={styles.headerRow}>
            <View style={{ flex: 1 }}>
              <View style={styles.eyebrowRow}>
                <View
                  style={[
                    styles.eyebrowDot,
                    { backgroundColor: "rgba(255,255,255,0.7)" },
                  ]}
                />
                <Text
                  style={[
                    styles.eyebrowText,
                    { color: "rgba(255,255,255,0.8)" },
                  ]}
                >
                  Patient Portal
                </Text>
              </View>

              <Text style={styles.headerTitle}>Records</Text>
              <Text style={styles.headerGreeting}>
                Review your visit history and prescriptions.
              </Text>
            </View>
            <Pressable
              style={({ pressed }) => [
                styles.headerBtn,
                pressed && { opacity: 0.85 },
              ]}
              onPress={() => router.navigate("/screenviews" as any)}
            >
              <Text style={styles.headerBtnText}>Back</Text>
            </Pressable>
          </View>
        </View>

        <View style={styles.contentSurface}>
          {error ? <Text style={styles.inlineError}>{error}</Text> : null}

          <View style={styles.tabsRow}>
            <TabCard
              title="Visits"
              value={String(visits.length)}
              subtitle="Consultation history"
              icon="calendar-outline"
              active={activeTab === "visits"}
              onPress={() => setActiveTab("visits")}
              delay={20}
            />
            <TabCard
              title="Prescriptions"
              value={String(prescriptions.length)}
              subtitle="Medicines and notes"
              icon="medkit-outline"
              active={activeTab === "prescriptions"}
              onPress={() => setActiveTab("prescriptions")}
              delay={40}
            />
          </View>

          <AnimatedCard delay={70} style={styles.filterToolbar}>
            <View style={styles.filterToolbarHeader}>
              <View style={{ flex: 1 }}>
                <Text style={styles.filterTitle}>Record date filter</Text>
                <Text style={styles.filterSubtitle}>
                  Applies to visits and prescriptions without setting up each
                  display separately.
                </Text>
              </View>
            </View>

            <View style={styles.filterButtonRow}>
              <Pressable
                onPress={() => setSelectedDateKey("")}
                style={({ pressed }) => [
                  styles.filterButton,
                  !selectedDateKey && styles.filterButtonActive,
                  pressed && { opacity: 0.85 },
                ]}
              >
                <Text
                  style={[
                    styles.filterButtonText,
                    !selectedDateKey && styles.filterButtonTextActive,
                  ]}
                >
                  All dates
                </Text>
              </Pressable>

              {Platform.OS === "web" ? (
                <View style={styles.filterButton}>
                  {React.createElement("input", {
                    type: "date",
                    value: selectedDateKey || "",
                    onChange: (event: any) =>
                      setSelectedDateKey(event?.target?.value ?? ""),
                    style: {
                      width: "100%",
                      borderWidth: 0,
                      outlineStyle: "none",
                      backgroundColor: "transparent",
                      fontSize: 11,
                      fontWeight: "700",
                      color: T.green700,
                    },
                  })}
                </View>
              ) : (
                <Pressable
                  onPress={() => setShowDatePicker(true)}
                  style={({ pressed }) => [
                    styles.filterButton,
                    pressed && { opacity: 0.85 },
                  ]}
                >
                  <Text style={styles.filterButtonText}>
                    {selectedDateKey ? "Change date" : "Pick date"}
                  </Text>
                </Pressable>
              )}
            </View>

            {selectedDateKey ? (
              <View style={styles.selectedDateRow}>
                <Text
                  style={styles.selectedDateText}
                >{`Showing ${formatDateKeyLabel(selectedDateKey)}`}</Text>
                <Pressable
                  onPress={() => setSelectedDateKey("")}
                  style={({ pressed }) => [
                    styles.clearDateButton,
                    pressed && { opacity: 0.85 },
                  ]}
                >
                  <Text style={styles.clearDateButtonText}>Clear</Text>
                </Pressable>
              </View>
            ) : null}

            {availableDateKeys.length > 0 ? (
              <ScrollView
                horizontal
                showsHorizontalScrollIndicator={false}
                contentContainerStyle={styles.quickDateRow}
              >
                {availableDateKeys.slice(0, 8).map((dateKey) => {
                  const active = selectedDateKey === dateKey;
                  return (
                    <Pressable
                      key={dateKey}
                      onPress={() => setSelectedDateKey(dateKey)}
                      style={({ pressed }) => [
                        styles.quickDateChip,
                        active && styles.quickDateChipActive,
                        pressed && { opacity: 0.85 },
                      ]}
                    >
                      <Text
                        style={[
                          styles.quickDateChipText,
                          active && styles.quickDateChipTextActive,
                        ]}
                      >
                        {formatDateKeyLabel(dateKey)}
                      </Text>
                    </Pressable>
                  );
                })}
              </ScrollView>
            ) : null}
          </AnimatedCard>

          <AnimatedCard delay={80} style={styles.sectionCard}>
            <View style={styles.sectionHeader}>
              <View style={{ flex: 1 }}>
                <Text style={styles.sectionTitle}>
                  {activeTab === "visits"
                    ? "Visit history"
                    : "Prescription history"}
                </Text>
                <Text style={styles.sectionSubtitle}>
                  {loading
                    ? "Loading history..."
                    : `${activeItemsCount} ${activeTab} entr${activeItemsCount === 1 ? "y" : "ies"} available`}
                </Text>
              </View>
              {loading ? (
                <ActivityIndicator size="small" color={T.green700} />
              ) : null}
            </View>
            <View style={styles.sectionBody}>
              {activeTab === "visits" ? renderVisits() : null}
              {activeTab === "prescriptions" ? renderPrescriptions() : null}
            </View>
          </AnimatedCard>
        </View>
      </ScrollView>

      {Platform.OS !== "web" && showDatePicker ? (
        <DateTimePicker
          value={selectedDateKey ? new Date(selectedDateKey) : new Date()}
          mode="date"
          display={Platform.OS === "ios" ? "spinner" : "default"}
          onChange={(event, date) => {
            if (Platform.OS !== "ios") {
              setShowDatePicker(false);
            }
            if (event.type === "dismissed" || !date) {
              return;
            }
            setSelectedDateKey(toDateKey(date));
            if (Platform.OS === "ios") {
              setShowDatePicker(false);
            }
          }}
        />
      ) : null}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: {
    flex: 1,
    backgroundColor: T.green700,
  },
  pageScroll: {
    flex: 1,
    backgroundColor: "rgba(255,255,255,0.07)",
  },
  pageScrollContent: {
    flexGrow: 1,
  },
  header: {
    backgroundColor: T.green700,
    paddingHorizontal: 20,
    paddingTop: 50,
    paddingBottom: 30,
    position: "relative",
    overflow: "hidden",
  },
  circleTopRight: {
    position: "absolute",
    top: -80,
    right: -80,
    width: 280,
    height: 280,
    borderRadius: 140,
    backgroundColor: "rgba(255,255,255,0.08)",
  },
  circleBottomLeft: {
    position: "absolute",
    bottom: -80,
    left: -60,
    width: 190,
    height: 190,
    borderRadius: 95,
    backgroundColor: "rgba(255,255,255,0.07)",
  },
  circleMidLeft: {
    position: "absolute",
    top: 30,
    left: -90,
    width: 180,
    height: 180,
    borderRadius: 90,
    backgroundColor: "rgba(255,255,255,0.05)",
  },
  headerRow: {
    flexDirection: "row",
    alignItems: "flex-start",
    justifyContent: "space-between",
    gap: 12,
    position: "relative",
    zIndex: 1,
  },
  headerEyebrow: {
    fontSize: 9,
    fontWeight: "700",
    letterSpacing: 1.2,
    color: "rgba(255,255,255,0.65)",
    marginBottom: 2,
  },

  eyebrowRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 5,
    marginBottom: 4,
  },
  eyebrowDot: {
    width: 6,
    height: 6,
    borderRadius: 3,
    backgroundColor: T.green500,
  },
  eyebrowText: {
    fontSize: 9,
    fontWeight: "700",
    letterSpacing: 0.9,
    textTransform: "uppercase",
    color: T.green600,
  },
  headerTitle: {
    fontSize: 30,
    fontWeight: "800",
    fontFamily: "serif",
    color: T.white,
    letterSpacing: 0.2,
    lineHeight: 34,
  },
  headerGreeting: {
    fontSize: 12,
    color: "rgba(255,255,255,0.75)",
    marginTop: 2,
    fontWeight: "400",
  },
  headerBtn: {
    marginTop: 4,
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 999,
    backgroundColor: "rgba(255,255,255,0.15)",
    borderWidth: 1,
    borderColor: "rgba(255,255,255,0.25)",
  },
  headerBtnText: {
    fontSize: 12,
    fontWeight: "700",
    color: T.white,
  },
  contentSurface: {
    flex: 1,
    backgroundColor: T.slate100,
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    paddingTop: 20,
    paddingHorizontal: 14,
    paddingBottom: 84,
  },
  inlineError: {
    fontSize: 12,
    color: T.red700,
    marginBottom: 10,
  },
  filterToolbar: {
    backgroundColor: T.white,
    borderRadius: 18,
    borderWidth: 1,
    borderColor: T.slate200,
    padding: 14,
    marginBottom: 14,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
  },
  filterToolbarHeader: {
    flexDirection: "row",
    alignItems: "flex-start",
    justifyContent: "space-between",
    gap: 12,
    marginBottom: 10,
  },
  filterTitle: {
    fontSize: 13,
    fontWeight: "700",
    color: T.slate800,
  },
  filterSubtitle: {
    marginTop: 3,
    fontSize: 11,
    lineHeight: 15,
    color: T.slate500,
  },
  filterButtonRow: {
    flexDirection: "row",
    gap: 8,
    marginBottom: 10,
  },
  filterButton: {
    borderRadius: 999,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    paddingHorizontal: 12,
    paddingVertical: 8,
  },
  filterButtonActive: {
    backgroundColor: T.green700,
    borderColor: T.green700,
  },
  filterButtonText: {
    fontSize: 11,
    fontWeight: "700",
    color: T.green700,
  },
  filterButtonTextActive: {
    color: T.white,
  },
  selectedDateRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    gap: 10,
    marginBottom: 10,
  },
  selectedDateText: {
    flex: 1,
    fontSize: 11,
    color: T.slate700,
    lineHeight: 15,
  },
  clearDateButton: {
    borderRadius: 999,
    backgroundColor: T.green100,
    paddingHorizontal: 10,
    paddingVertical: 6,
  },
  clearDateButtonText: {
    fontSize: 10,
    fontWeight: "700",
    color: T.green700,
  },
  quickDateRow: {
    paddingRight: 8,
    gap: 8,
  },
  quickDateChip: {
    borderRadius: 999,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.slate50,
    paddingHorizontal: 12,
    paddingVertical: 8,
  },
  quickDateChipActive: {
    backgroundColor: T.green100,
    borderColor: T.green700,
  },
  quickDateChipText: {
    fontSize: 10,
    fontWeight: "700",
    color: T.slate700,
  },
  quickDateChipTextActive: {
    color: T.green700,
  },
  tabsRow: {
    flexDirection: "row",
    gap: 8,
    marginBottom: 18,
  },
  tabCardWrap: {
    flex: 1,
  },
  tabCard: {
    minHeight: 144,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    padding: 12,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 8,
    elevation: 2,
  },
  tabCardActive: {
    backgroundColor: T.green700,
    borderColor: T.green700,
  },
  tabIconCircle: {
    width: 36,
    height: 36,
    borderRadius: 10,
    backgroundColor: "rgba(6,182,212,0.1)",
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 8,
  },
  tabIconCircleActive: {
    backgroundColor: "rgba(255,255,255,0.18)",
  },
  tabCardTitle: {
    fontSize: 11,
    fontWeight: "700",
    color: T.slate500,
    marginBottom: 4,
  },
  tabCardTitleActive: {
    color: "rgba(255,255,255,0.8)",
  },
  tabCardValue: {
    fontSize: 20,
    fontWeight: "800",
    color: T.green700,
    marginBottom: 4,
  },
  tabCardValueActive: {
    color: T.white,
  },
  tabCardSubtitle: {
    fontSize: 10,
    lineHeight: 14,
    color: T.slate500,
  },
  tabCardSubtitleActive: {
    color: "rgba(255,255,255,0.78)",
  },
  sectionCard: {
    backgroundColor: T.white,
    borderRadius: 18,
    borderWidth: 1,
    borderColor: T.slate200,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
    overflow: "hidden",
  },
  sectionHeader: {
    paddingHorizontal: 16,
    paddingTop: 14,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: T.slate100,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    gap: 12,
  },
  sectionTitle: {
    fontSize: 15,
    fontWeight: "700",
    color: T.slate800,
  },
  sectionSubtitle: {
    fontSize: 11,
    color: T.slate500,
    marginTop: 2,
  },
  sectionBody: {
    padding: 16,
  },
  emptyText: {
    fontSize: 12,
    color: T.slate500,
    lineHeight: 18,
  },
  listCard: {
    borderRadius: 16,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.slate50,
    padding: 14,
    marginBottom: 12,
  },
  listCardTop: {
    flexDirection: "row",
    alignItems: "flex-start",
    justifyContent: "space-between",
    gap: 12,
  },
  listMain: {
    flex: 1,
  },
  listTitle: {
    fontSize: 13,
    fontWeight: "700",
    color: T.slate800,
    marginBottom: 3,
  },
  listSubtitle: {
    fontSize: 11,
    color: T.slate600,
    lineHeight: 15,
    marginBottom: 2,
  },
  listMeta: {
    fontSize: 11,
    color: T.slate500,
    lineHeight: 15,
  },
  detailButton: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 999,
    backgroundColor: T.white,
    borderWidth: 1,
    borderColor: T.slate200,
  },
  detailButtonText: {
    fontSize: 11,
    fontWeight: "700",
    color: T.green700,
  },
  detailPanel: {
    marginTop: 12,
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: T.slate200,
  },
  detailLabel: {
    fontSize: 11,
    fontWeight: "700",
    color: T.slate600,
    textTransform: "uppercase",
    letterSpacing: 0.3,
    marginBottom: 4,
    marginTop: 8,
  },
  detailValue: {
    fontSize: 12,
    lineHeight: 18,
    color: T.slate700,
  },
  medicineRow: {
    marginTop: 8,
    padding: 10,
    borderRadius: 12,
    backgroundColor: T.white,
    borderWidth: 1,
    borderColor: T.slate200,
  },
  medicineName: {
    fontSize: 12,
    fontWeight: "700",
    color: T.slate800,
    marginBottom: 3,
  },
  medicineMeta: {
    fontSize: 11,
    lineHeight: 16,
    color: T.slate600,
  },
  medicineInstructions: {
    fontSize: 11,
    lineHeight: 16,
    color: T.slate500,
    marginTop: 3,
  },
  consultGrid: {
    flexDirection: "row",
    gap: 12,
    marginBottom: 4,
  },
  consultGridCol: {
    flex: 1,
  },
  rxTableHeader: {
    flexDirection: "row",
    gap: 4,
    paddingVertical: 6,
    paddingHorizontal: 8,
    backgroundColor: T.green100,
    borderRadius: 8,
    marginTop: 8,
    marginBottom: 4,
  },
  rxTableHeaderText: {
    fontSize: 9,
    fontWeight: "700",
    color: T.green700,
    textTransform: "uppercase",
    letterSpacing: 0.2,
  },
  rxTableRow: {
    flexDirection: "row",
    gap: 4,
    paddingVertical: 8,
    paddingHorizontal: 8,
    borderBottomWidth: 1,
    borderBottomColor: T.slate100,
    alignItems: "flex-start",
  },
});
