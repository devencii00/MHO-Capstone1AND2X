import React, { useCallback, useEffect, useRef, useState } from "react";
import {
  ActivityIndicator,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  View,
} from "react-native";

import { SafeAreaView } from "react-native-safe-area-context";
import { useIsFocused } from "@react-navigation/native";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";

const T = {
  green500: "#06b6d4",
  green600: "#16A34A",
  green700: "#15803D",
  green400: "#22d3ee",
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
  green100: "rgba(34,197,94,0.12)",
  red700: "#b91c1c",
  amber100: "rgba(245,158,11,0.12)",
  amber700: "#b45309",
};

const API_BASE_URL = (
  process.env.EXPO_PUBLIC_API_BASE_URL ?? "http://localhost:8000/api"
).replace(/\/+$/, "");
const APP_BASE_URL = API_BASE_URL.replace(/\/api$/, "");

/* ── helpers ── */

function formatQueueLabel(item: any): string {
  const code =
    item?.queue_code != null
      ? String(item.queue_code)
      : item?.queue_number != null
        ? String(item.queue_number).padStart(3, "0")
        : "---";
  if (code.includes("-")) {
    return code.replace("-", " - ");
  }
  return code;
}

function formatDisplayName(
  raw: string | null | undefined,
  fallback: string,
): string {
  if (!raw) return fallback;
  const name = String(raw).trim();
  if (!name) return fallback;
  const atIdx = name.indexOf("@");
  if (atIdx > -1) {
    const prefix = name.substring(0, atIdx);
    const domain = name.substring(atIdx);
    if (prefix.length > 5) return prefix.substring(0, 5) + "....." + domain;
    return name;
  }
  if (name.indexOf("#") > -1 || /^[0-9]+$/.test(name.replace(/\s/g, ""))) {
    return fallback;
  }
  const parts = name.split(/\s+/).filter((p) => p.length > 0);
  if (parts.length === 0) return fallback;
  const last = parts[parts.length - 1];
  const firstInit = parts[0].charAt(0).toUpperCase() + ".";
  let middleInit = "";
  if (parts.length >= 3 && parts[1].length > 0) {
    middleInit = " " + parts[1].charAt(0).toUpperCase() + ".";
  }
  return firstInit + middleInit + " " + last;
}

function drDisplayName(raw: string | null | undefined, fallback = "Doctor") {
  const n = formatDisplayName(raw, fallback);
  if (!n || n === fallback || n.indexOf("@") > -1) return n;
  return "Dr. " + n;
}

function patientDisplayName(
  raw: string | null | undefined,
  fallback = "Patient",
) {
  return formatDisplayName(raw, fallback);
}

function getDisplayDoctorName(item: any): string {
  if (item?.doctor_name) return drDisplayName(item.doctor_name);
  if (item?.doctor?.name) return drDisplayName(item.doctor.name);
  if (item?.doctor) return drDisplayName(item.doctor);
  return "Doctor";
}

function getDisplayPatientName(item: any): string {
  if (item?.patient_name) return patientDisplayName(item.patient_name);
  if (item?.patient?.name) return patientDisplayName(item.patient.name);
  return "Patient";
}

function roomLabel(roomNumber: any): string {
  if (roomNumber == null) return "";
  const n = parseInt(roomNumber, 10);
  if (isNaN(n) || n < 1) return "";
  return `[ROOM ${n}]`;
}

/* ── types ── */

type MyQueueEntry = {
  queueId: string;
  queueNumber: string;
  doctorId: string;
  doctor: string;
  status: string;
  position: number | null;
  estimatedWaitMinutes: number | null;
};

type DisplayItem = Record<string, any>;

/* ── component ── */

export default function QueueDisplayScreen() {
  const router = useRouter();
  const isFocused = useIsFocused();
  const loadingQueueRef = useRef(false);
  const loadingDisplayRef = useRef(false);

  const [loading, setLoading] = useState(true);
  const [myQueue, setMyQueue] = useState<MyQueueEntry | null>(null);
  const [displayData, setDisplayData] = useState<{
    now_serving: DisplayItem[];
    next: DisplayItem[];
    wait_queue: DisplayItem[];
    date?: string;
    counts?: Record<string, number>;
  }>({ now_serving: [], next: [], wait_queue: [] });
  const [error, setError] = useState("");

  /* ── fetch patient's own queue entry ── */
  const loadMyQueue = useCallback(async () => {
    if (loadingQueueRef.current) return;
    loadingQueueRef.current = true;
    try {
      const token = (globalThis as any)?.apiToken as string | undefined;
      if (!token) {
        router.replace("/screenviews/aut-landing/login-screen" as any);
        return;
      }
      const res = await fetch(`${API_BASE_URL}/queues?per_page=10`, {
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        setMyQueue(null);
        return;
      }
      const rawList = Array.isArray(data?.data) ? data.data : [];
      const active =
        rawList.find(
          (q: any) => q?.status === "waiting" || q?.status === "serving",
        ) ?? null;
      if (!active) {
        setMyQueue(null);
        return;
      }
      const docFirst = active?.appointment?.doctor?.firstname ?? "";
      const docLast = active?.appointment?.doctor?.lastname ?? "";
      const docName =
        `Dr. ${[docFirst, docLast].filter(Boolean).join(" ")}`.trim();
      setMyQueue({
        queueId: String(active.queue_id ?? ""),
        queueNumber:
          active.queue_number != null ? String(active.queue_number) : "",
        doctorId:
          active?.appointment?.doctor_id != null
            ? String(active.appointment.doctor_id)
            : "",
        doctor: docName === "Dr." ? "Doctor" : docName,
        status: active.status === "serving" ? "serving" : "waiting",
        position: typeof active.position === "number" ? active.position : null,
        estimatedWaitMinutes:
          typeof active.estimated_wait_minutes === "number"
            ? active.estimated_wait_minutes
            : null,
      });
    } catch {
      setMyQueue(null);
    } finally {
      loadingQueueRef.current = false;
    }
  }, [router]);

  /* ── fetch queue display data ── */
  const loadDisplayData = useCallback(async (doctorId: string) => {
    if (loadingDisplayRef.current) return;
    loadingDisplayRef.current = true;
    try {
      const res = await fetch(
        `${APP_BASE_URL}/queue-display/data?doctor_id=${encodeURIComponent(doctorId)}`,
        {
          headers: { Accept: "application/json" },
        },
      );
      const data = await res.json().catch(() => ({}));
      if (!res.ok) return;
      setDisplayData({
        now_serving: Array.isArray(data?.now_serving) ? data.now_serving : [],
        next: Array.isArray(data?.next) ? data.next : [],
        wait_queue: Array.isArray(data?.wait_queue) ? data.wait_queue : [],
        date: data?.date,
        counts: data?.counts,
      });
    } catch {
      // silent — polling will retry
    } finally {
      loadingDisplayRef.current = false;
    }
  }, []);

  /* ── initial load + polling queues ── */
  useEffect(() => {
    if (!isFocused) return;
    let cancelled = false;
    let intervalId: ReturnType<typeof setInterval> | undefined;

    async function init() {
      setLoading(true);
      setError("");
      const token = (globalThis as any)?.apiToken as string | undefined;
      if (!token) {
        router.replace("/screenviews/aut-landing/login-screen" as any);
        return;
      }
      try {
        await loadMyQueue();
        intervalId = setInterval(() => {
          void loadMyQueue();
        }, 15000);
      } catch {
        if (!cancelled) setError("Network error.");
      } finally {
        if (!cancelled) setLoading(false);
      }
    }
    void init();
    return () => {
      cancelled = true;
      if (intervalId) clearInterval(intervalId);
    };
  }, [isFocused, loadMyQueue, router]);

  /* ── poll display data when doctorId is available ── */
  useEffect(() => {
    if (!isFocused) return;
    const doctorId = myQueue?.doctorId ?? "";
    if (!doctorId) return;

    let cancelled = false;
    let intervalId: ReturnType<typeof setInterval> | undefined;

    async function sync() {
      if (cancelled) return;
      await loadDisplayData(doctorId);
    }
    void sync();
    intervalId = setInterval(() => {
      void sync();
    }, 10000);

    return () => {
      cancelled = true;
      if (intervalId) clearInterval(intervalId);
    };
  }, [isFocused, loadDisplayData, myQueue?.doctorId]);

  /* ── determine if patient is being served now ── */
  const patientIsServing =
    myQueue?.status === "serving" ||
    displayData.now_serving.some(
      (item) =>
        item?.queue_id != null &&
        myQueue?.queueId != null &&
        String(item.queue_id) === myQueue.queueId,
    );

  /* ── render ── */
  return (
    <SafeAreaView style={styles.safe}>
      <StatusBar barStyle="light-content" backgroundColor={T.green700} />
      <ScrollView
        style={styles.pageScroll}
        contentContainerStyle={styles.pageScrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* ── header ── */}
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
              <Text style={styles.headerTitle}>Queue Display</Text>
              <Text style={styles.headerGreeting}>Live queue status</Text>
            </View>
            <View style={styles.headerBtn}>
              <Text
                style={styles.headerBtnText}
                onPress={() => router.replace("/screenviews/(tabs)" as any)}
              >
                Back
              </Text>
            </View>
          </View>
        </View>

        {/* ── content surface ── */}
        <View style={styles.contentSurface}>
          {error ? <Text style={styles.inlineError}>{error}</Text> : null}

          {loading ? (
            <View style={styles.loadingWrap}>
              <ActivityIndicator size="large" color={T.green700} />
              <Text style={styles.loadingText}>Loading queue data…</Text>
            </View>
          ) : (
            <>
              {/* ── My Queue Card ── */}
              {myQueue ? (
                <View
                  style={[
                    styles.myQueueCard,
                    patientIsServing && styles.myQueueCardServing,
                  ]}
                >
                  <View style={styles.myQueueHeader}>
                    <View style={styles.infoIconCircle}>
                      <Ionicons
                        name={
                          patientIsServing ? "checkmark-circle" : "time-outline"
                        }
                        size={18}
                        color={patientIsServing ? T.green700 : T.green700}
                      />
                    </View>
                    <View style={{ flex: 1 }}>
                      <Text style={styles.myQueueLabel}>Your Queue</Text>
                      <Text style={styles.myQueueNumber}>
                        {myQueue.queueNumber ? `#${myQueue.queueNumber}` : "—"}
                      </Text>
                    </View>
                    <View style={styles.myQueueStatusBadge}>
                      <Text style={styles.myQueueStatusText}>
                        {patientIsServing ? "SERVING" : "WAITING"}
                      </Text>
                    </View>
                  </View>
                  <View style={styles.myQueueMeta}>
                    <Text style={styles.myQueueDoctor}>{myQueue.doctor}</Text>
                    {myQueue.position != null ? (
                      <Text style={styles.myQueuePosition}>
                        Position: {myQueue.position}
                      </Text>
                    ) : null}
                    {myQueue.estimatedWaitMinutes != null ? (
                      <Text style={styles.myQueueWait}>
                        Est. wait: {myQueue.estimatedWaitMinutes} mins
                      </Text>
                    ) : null}
                  </View>
                </View>
              ) : null}

              {/* ── Now Serving ── */}
              <View style={styles.displaySection}>
                <Text style={styles.displaySectionTitle}>Now serving</Text>
                {displayData.now_serving.length === 0 ? (
                  <View style={styles.emptyCard}>
                    <Text style={styles.emptyText}>
                      No queue is currently being served.
                    </Text>
                  </View>
                ) : (
                  displayData.now_serving.map((item, idx) => {
                    const isMine =
                      item?.queue_id != null &&
                      myQueue?.queueId != null &&
                      String(item.queue_id) === myQueue.queueId;
                    return (
                      <View
                        key={item.queue_id ?? idx}
                        style={[
                          styles.servingCard,
                          isMine && styles.servingCardHighlighted,
                        ]}
                      >
                        <View style={styles.servingTopRow}>
                          <Text
                            style={[
                              styles.servingCode,
                              isMine && styles.servingCodeHighlighted,
                            ]}
                          >
                            {formatQueueLabel(item)}
                          </Text>
                          {roomLabel(item?.room_number) ? (
                            <View style={styles.roomPill}>
                              <Text style={styles.roomPillText}>
                                {roomLabel(item?.room_number)}
                              </Text>
                            </View>
                          ) : null}
                        </View>
                        <View style={styles.servingBottomRow}>
                          <Text style={styles.servingPatient}>
                            {getDisplayPatientName(item)}
                          </Text>
                          <Text style={styles.servingDoctor}>
                            {getDisplayDoctorName(item)}
                          </Text>
                        </View>
                        {isMine ? (
                          <View style={styles.youBadge}>
                            <Ionicons
                              name="person"
                              size={12}
                              color={T.green700}
                            />
                            <Text style={styles.youBadgeText}>You</Text>
                          </View>
                        ) : null}
                      </View>
                    );
                  })
                )}
              </View>

              {/* ── Next in Line ── */}
              <View style={styles.displaySection}>
                <View style={styles.displaySectionTitleRow}>
                  <Text style={styles.displaySectionTitle}>Next in line</Text>
                  {displayData.counts?.waiting != null ? (
                    <Text style={styles.displaySectionMeta}>
                      {displayData.counts.waiting} waiting
                    </Text>
                  ) : null}
                </View>
                {displayData.next.length === 0 ? (
                  <View style={styles.emptyCard}>
                    <Text style={styles.emptyText}>No patients waiting.</Text>
                  </View>
                ) : (
                  displayData.next.map((item, idx) => (
                    <View key={item.queue_id ?? idx} style={styles.lineCard}>
                      <View style={styles.lineCardLeft}>
                        <Text style={styles.lineCode}>
                          {formatQueueLabel(item)}
                        </Text>
                        <Text style={styles.linePatient}>
                          {getDisplayPatientName(item)}
                        </Text>
                      </View>
                      <View style={styles.lineCardRight}>
                        <Text style={styles.lineDoctor}>
                          {getDisplayDoctorName(item)}
                        </Text>
                        {item?.estimated_wait_minutes != null ? (
                          <Text style={styles.lineWait}>
                            Est. {item.estimated_wait_minutes}min
                          </Text>
                        ) : null}
                      </View>
                    </View>
                  ))
                )}
              </View>

              {/* ── Wait Queue ── */}
              {displayData.wait_queue.length > 0 ? (
                <View style={styles.displaySection}>
                  <View style={styles.displaySectionTitleRow}>
                    <Text
                      style={[
                        styles.displaySectionTitle,
                        { color: T.green600 },
                      ]}
                    >
                      Wait queue
                    </Text>
                    {displayData.counts?.on_hold != null ? (
                      <Text style={styles.displaySectionMeta}>
                        {displayData.counts.on_hold} on hold
                      </Text>
                    ) : null}
                  </View>
                  {displayData.wait_queue.map((item, idx) => (
                    <View key={item.queue_id ?? idx} style={styles.waitCard}>
                      <View style={styles.lineCardLeft}>
                        <Text style={[styles.lineCode, { color: T.green600 }]}>
                          {formatQueueLabel(item)}
                        </Text>
                        <Text
                          style={[styles.linePatient, { color: T.slate600 }]}
                        >
                          {getDisplayPatientName(item)}
                        </Text>
                      </View>
                      <View style={styles.lineCardRight}>
                        <Text style={styles.lineDoctor}>
                          {getDisplayDoctorName(item)}
                        </Text>
                      </View>
                    </View>
                  ))}
                </View>
              ) : null}
            </>
          )}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

/* ── styles ── */

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
    paddingBottom: 20,
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

  /* ── loading ── */
  loadingWrap: {
    paddingVertical: 48,
    alignItems: "center",
    justifyContent: "center",
    gap: 12,
  },
  loadingText: {
    fontSize: 12,
    color: T.slate500,
    fontWeight: "600",
  },

  /* ── my queue card ── */
  myQueueCard: {
    backgroundColor: T.white,
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: T.slate200,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 8,
    elevation: 2,
    marginBottom: 18,
  },
  myQueueCardServing: {
    borderColor: T.green700,
    borderWidth: 2,
  },
  myQueueHeader: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    marginBottom: 10,
  },
  myQueueLabel: {
    fontSize: 9,
    fontWeight: "600",
    color: T.slate400,
    letterSpacing: 0.2,
    marginBottom: 2,
    lineHeight: 12,
  },
  myQueueNumber: {
    fontSize: 20,
    fontWeight: "800",
    color: T.green700,
    lineHeight: 24,
  },
  myQueueStatusBadge: {
    borderRadius: 999,
    paddingHorizontal: 10,
    paddingVertical: 4,
    backgroundColor: T.green100,
    borderWidth: 1,
    borderColor: "rgba(34,197,94,0.25)",
  },
  myQueueStatusText: {
    fontSize: 9,
    fontWeight: "800",
    color: T.green700,
    letterSpacing: 0.8,
  },
  myQueueMeta: {
    gap: 4,
  },
  myQueueDoctor: {
    fontSize: 12,
    fontWeight: "700",
    color: T.slate800,
  },
  myQueuePosition: {
    fontSize: 11,
    color: T.slate500,
    lineHeight: 15,
  },
  myQueueWait: {
    fontSize: 11,
    color: T.slate500,
    lineHeight: 15,
  },

  /* ── display sections ── */
  displaySection: {
    marginBottom: 20,
  },
  displaySectionTitleRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  displaySectionTitle: {
    fontSize: 13,
    fontWeight: "700",
    color: T.slate700,
    textTransform: "uppercase",
    letterSpacing: 1.2,
    marginBottom: 10,
  },
  displaySectionMeta: {
    fontSize: 10,
    color: T.slate400,
    fontWeight: "600",
    marginBottom: 10,
  },

  /* ── empty state ── */
  emptyCard: {
    borderRadius: 14,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    paddingHorizontal: 14,
    paddingVertical: 14,
  },
  emptyText: {
    fontSize: 12,
    color: T.slate400,
    lineHeight: 16,
    textAlign: "center",
  },

  /* ── now serving card ── */
  servingCard: {
    borderRadius: 16,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    padding: 14,
    marginBottom: 10,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 6,
    elevation: 1,
    position: "relative",
  },
  servingCardHighlighted: {
    borderColor: T.green700,
    borderWidth: 2,
    backgroundColor: "rgba(34,197,94,0.05)",
  },
  servingTopRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  servingCode: {
    fontSize: 28,
    fontWeight: "800",
    fontFamily: "serif",
    color: T.slate800,
    letterSpacing: 0.5,
  },
  servingCodeHighlighted: {
    color: T.green700,
  },
  roomPill: {
    borderRadius: 999,
    paddingHorizontal: 10,
    paddingVertical: 4,
    backgroundColor: "rgba(6,182,212,0.08)",
    borderWidth: 1,
    borderColor: "rgba(6,182,212,0.18)",
  },
  roomPillText: {
    fontSize: 10,
    fontWeight: "700",
    color: T.green600,
    letterSpacing: 0.3,
  },
  servingBottomRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    borderTopWidth: 1,
    borderTopColor: T.slate100,
    paddingTop: 10,
  },
  servingPatient: {
    fontSize: 15,
    fontWeight: "700",
    color: T.slate800,
    flex: 1,
  },
  servingDoctor: {
    fontSize: 13,
    fontWeight: "600",
    color: T.green500,
    textAlign: "right",
  },
  youBadge: {
    position: "absolute",
    top: -6,
    right: 10,
    flexDirection: "row",
    alignItems: "center",
    gap: 3,
    borderRadius: 999,
    paddingHorizontal: 8,
    paddingVertical: 2,
    backgroundColor: T.green100,
    borderWidth: 1,
    borderColor: T.green700,
  },
  youBadgeText: {
    fontSize: 9,
    fontWeight: "800",
    color: T.green700,
  },

  /* ── next / wait cards ── */
  lineCard: {
    borderRadius: 14,
    borderWidth: 1,
    borderColor: T.slate200,
    backgroundColor: T.white,
    paddingHorizontal: 14,
    paddingVertical: 12,
    flexDirection: "row",
    alignItems: "flex-start",
    justifyContent: "space-between",
    gap: 10,
    marginBottom: 8,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.04,
    shadowRadius: 4,
    elevation: 1,
  },
  lineCardLeft: {
    flex: 1,
  },
  lineCardRight: {
    alignItems: "flex-end",
    flexShrink: 0,
  },
  lineCode: {
    fontSize: 16,
    fontWeight: "700",
    color: T.slate800,
    marginBottom: 2,
  },
  linePatient: {
    fontSize: 11,
    color: T.slate500,
    lineHeight: 15,
  },
  lineDoctor: {
    fontSize: 11,
    fontWeight: "600",
    color: T.slate600,
    lineHeight: 15,
  },
  lineWait: {
    fontSize: 10,
    color: T.slate400,
    lineHeight: 14,
    marginTop: 2,
  },

  /* ── wait queue card ── */
  waitCard: {
    borderRadius: 14,
    borderWidth: 1,
    borderColor: "rgba(6,182,212,0.18)",
    backgroundColor: "rgba(6,182,212,0.04)",
    paddingHorizontal: 14,
    paddingVertical: 12,
    flexDirection: "row",
    alignItems: "flex-start",
    justifyContent: "space-between",
    gap: 10,
    marginBottom: 8,
    shadowColor: T.slate900,
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.04,
    shadowRadius: 4,
    elevation: 1,
  },

  /* ── info icon circle (reused in my queue card) ── */
  infoIconCircle: {
    width: 36,
    height: 36,
    borderRadius: 10,
    backgroundColor: "rgba(6,182,212,0.1)",
    alignItems: "center",
    justifyContent: "center",
  },
});
