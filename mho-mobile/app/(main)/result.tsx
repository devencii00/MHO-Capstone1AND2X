import React, { useEffect, useState } from 'react';
  import {
    View,
    Text,
    ScrollView,
    RefreshControl,
    ActivityIndicator,
    StatusBar,
    TouchableOpacity,
    Image,
    Alert,
    Modal,
  } from 'react-native';
  import { useRouter } from 'expo-router';
  import { Ionicons } from '@expo/vector-icons';
  import { LinearGradient } from 'expo-linear-gradient';
  import api from '../lib/api';
  import ServiceIcon from '../lib/ServiceIcon';
  import { downloadPdf, MedicalResult } from '../lib/pdfExport';
  import { useReverb } from '../hooks/useReverb';

  type CategoryTab = 'xray' | 'ultrasound' | 'laboratory';

  const TABS: { key: CategoryTab; label: string }[] = [
    { key: 'xray', label: 'X-Ray' },
    { key: 'ultrasound', label: 'Ultrasound' },
    { key: 'laboratory', label: 'Laboratory' },
  ];

  export default function ResultScreen() {
    const router = useRouter();
    const [results, setResults] = useState<MedicalResult[]>([]);
    const [loading, setLoading] = useState(true);
    const [refreshing, setRefreshing] = useState(false);
    const [downloadingId, setDownloadingId] = useState<number | null>(null);
    const [imageErrors, setImageErrors] = useState<Record<number, boolean>>({});
    const [viewerVisible, setViewerVisible] = useState(false);
    const [viewerItem, setViewerItem] = useState<MedicalResult | null>(null);
    const [activeTab, setActiveTab] = useState<CategoryTab>('xray');

    // NEW: tracks which card is currently expanded to show consultation/allergies/findings
    const [expandedId, setExpandedId] = useState<number | null>(null);

    // NEW: toggles a card open/closed when tapped
    const toggleExpand = (id: number) => {
      setExpandedId((prev) => (prev === id ? null : id));
    };

    // REAL-TIME: Listen for new medical results
    useReverb('patient-notifications', 'result.created', (data: any) => {
      console.log(' Reverb: New medical result available!', data);
      loadResults();
    });

    // REAL-TIME: Listen for result updates
    useReverb('patient-notifications', 'result.updated', (data: any) => {
      console.log('Reverb: Medical result updated!', data);
      loadResults();
    });

    //  FIXED: Safe JSON parse function
    const safeJsonParse = (str: any): any => {
      if (!str) return null;
      
      // If already an object, return as is
      if (typeof str === 'object') return str;
      
      // If string, try to parse
      if (typeof str === 'string') {
        try {
          // Clean the string: escape unescaped newlines
          const cleaned = str
            .replace(/\n/g, '\\n')
            .replace(/\r/g, '\\r')
            .replace(/\t/g, '\\t');
          return JSON.parse(cleaned);
        } catch (e) {
          // If still fails, try original string
          try {
            return JSON.parse(str);
          } catch (e2) {
            // Return null if completely invalid
            console.warn('Failed to parse JSON:', str?.substring(0, 100));
            return null;
          }
        }
      }
      
      return null;
    };

    const loadResults = async () => {
      try {
        const response = await api.get('/patient/results');

        if (response.data.success) {
          const rawData = response.data.data || [];

          //  Log all raw data for debugging
          console.log(' Total raw results:', rawData.length);
          rawData.forEach((item: any) => {
    const findingsPreview =
      typeof item.findings === 'string'
        ? item.findings.substring(0, 50)
        : item.findings != null
        ? JSON.stringify(item.findings).substring(0, 50)
        : 'null';

    console.log(
      `[Result #${item.id}] service: ${item.service_name} | category: ${item.service_category} | status: ${item.status} | has_image: ${!!item.image_url || !!item.image_base64} | findings: ${findingsPreview}...`
    );
  });

          //  FIXED: Relaxed filtering - show ALL results
          const data = rawData.filter((item: any) => {
            const hasImage = !!item.image_url || !!item.image_base64;
            
            //  ALWAYS show if has image
            if (hasImage) {
              console.log(`Showing #${item.id} - has image`);
              return true;
            }
            
            //  ALWAYS show if status is 'completed'
            if (item.status === 'completed') {
              console.log(` Showing #${item.id} - completed status`);
              return true;
            }
            
            //  ALWAYS show if status is 'awaiting_result'
            if (item.status === 'awaiting_result') {
              console.log(` Showing #${item.id} - awaiting_result status`);
              return true;
            }
            
            // Check findings
            const f = item.findings;
            
            // If no findings at all
            if (!f || f === '' || f === '""' || f === '{}' || f === 'null') {
              console.log(` Filtering #${item.id} - empty findings`);
              return false;
            }
            
            // Try to parse findings
            const parsed = safeJsonParse(f);
            
            if (parsed && (parsed.findings_text || parsed.impression || Object.keys(parsed).length > 0)) {
              console.log(` Showing #${item.id} - valid findings`);
              return true;
            }
            
            // If findings is a non-empty string
            if (typeof f === 'string' && f.trim().length > 0) {
              console.log(` Showing #${item.id} - has text findings`);
              return true;
            }
            
            console.log(` Filtering #${item.id} - no valid content`);
            return false;
          });

          console.log(' Filtered results count:', data.length);
          setResults(data);
          setImageErrors({});
        }
      } catch (err) {
        console.error('Error loading results:', err);
      } finally {
        setLoading(false);
        setRefreshing(false);
      }
    };

    useEffect(() => {
      loadResults();
    }, []);

    //  Polling interval (Reverb handles real-time updates)
    useEffect(() => {
      const interval = setInterval(loadResults, 60000);
      return () => clearInterval(interval);
    }, []);

    const onRefresh = () => {
      setRefreshing(true);
      loadResults();
    };

    const handleDownload = async (item: MedicalResult) => {
      if (downloadingId === item.id) return;

      setDownloadingId(item.id);

      try {
        const timeoutId = setTimeout(() => {
          setDownloadingId(null);
          Alert.alert('Timeout', 'Download is taking too long. Please try again.');
        }, 30000);

        await downloadPdf(item);
        clearTimeout(timeoutId);

        Alert.alert('Success', 'PDF downloaded successfully! ');
      } catch (error: any) {
        console.error('[Download] Error:', error);
        Alert.alert(
          'Download Failed',
          error?.message || 'Failed to generate PDF. Please try again.',
          [{ text: 'OK' }]
        );
      } finally {
        setDownloadingId(null);
      }
    };

    const handleViewImage = (item: MedicalResult) => {
      setViewerItem(item);
      setViewerVisible(true);
    };

    const handleImageError = (id: number) => {
      setImageErrors((prev) => ({ ...prev, [id]: true }));
    };

    const formatDate = (dateString: string): string => {
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
          weekday: 'short',
          month: 'short',
          day: 'numeric',
          year: 'numeric',
        });
      } catch {
        return dateString;
      }
    };

    const getImageSource = (item: MedicalResult) => {
      if ((item as any).image_base64) return { uri: (item as any).image_base64 };
      if (item.image_url) return { uri: item.image_url };
      return null;
    };

    const getCategoryStyle = (category?: string) => {
      const cat = (category || '').toLowerCase();
      
      if (cat.includes('ultrasound')) {
        return { textColor: '#ea580c', iconBg: '#fef3e2' };
      }
      if (cat.includes('laboratory') || cat.includes('lab')) {
        return { textColor: '#16a34a', iconBg: '#dcfce7' };
      }
      if (cat.includes('xray') || cat.includes('x-ray')) {
        return { textColor: '#2563eb', iconBg: '#dbeafe' };
      }
      
      return { textColor: '#6b7280', iconBg: '#f3f4f6' };
    };

    // ─────────────────────────────────────────────────────────────
    // FIXED: Laboratory is now the CATCH-ALL category.
    // ─────────────────────────────────────────────────────────────
    const matchesTab = (category: string | undefined, tab: CategoryTab) => {
      const cat = (category || '').toLowerCase();
      const isXray = cat.includes('xray') || cat.includes('x-ray');
      const isUltrasound = cat.includes('ultrasound') || cat.includes('ultra');

      if (tab === 'xray') return isXray;
      if (tab === 'ultrasound') return isUltrasound;
      if (tab === 'laboratory') return !isXray && !isUltrasound; // catch-all
      return false;
    };

    const filteredResults = results.filter((item) =>
      matchesTab(item.service_category, activeTab)
    );

    if (loading) {
      return (
        <View className="flex-1">
          <LinearGradient
            colors={['#10B981', '#059669']}
            className="items-center justify-center flex-1"
          >
            <ActivityIndicator size="large" color="#FFFFFF" />
            <Text className="mt-3 font-semibold text-white">Loading results...</Text>
          </LinearGradient>
        </View>
      );
    }

    return (
      <View className="flex-1" style={{ backgroundColor: '#F0FDF4' }}>
        <StatusBar barStyle="light-content" />

        {/* HEADER */}
      <LinearGradient
        colors={['#10B981', '#059669']}
        start={{ x: 0, y: 0 }}
        end={{ x: 1, y: 1 }}
        className="pt-[60px] pb-[28px] px-6 rounded-b-[35px] shadow-lg"
      >
        <View className="relative items-center justify-center">
          {/* Back Button - Left Side */}
          <TouchableOpacity
            onPress={() => router.back()}
            className="absolute left-0 z-10"
          >
            <Ionicons name="arrow-back" size={24} color="#FFFFFF" />
          </TouchableOpacity>

          {/* Title - Centered */}
          <Text className="text-white text-[28px] font-bold">Medical Results</Text>
        </View>
      </LinearGradient>

        {/* CATEGORY TABS */}
        <View
          className="flex-row bg-white"
          style={{
            borderBottomWidth: 1,
            borderBottomColor: '#E5E7EB',
          }}
        >
          {TABS.map((tab) => {
            const isActive = activeTab === tab.key;
            return (
              <TouchableOpacity
                key={tab.key}
                activeOpacity={0.7}
                onPress={() => setActiveTab(tab.key)}
                className="items-center flex-1 pt-3 pb-2.5"
              >
                <Text
                  style={{
                    fontSize: 13,
                    fontWeight: isActive ? '700' : '600',
                    color: isActive ? '#047857' : '#9CA3AF',
                  }}
                >
                  {tab.label}
                </Text>
                <View
                  className="mt-1.5 rounded-full"
                  style={{
                    height: 2.5,
                    width: '50%',
                    backgroundColor: isActive ? '#047857' : 'transparent',
                  }}
                />
              </TouchableOpacity>
            );
          })}
        </View>

        {/* CONTENT */}
        <ScrollView
          className="flex-1 px-4 pt-5"
          showsVerticalScrollIndicator={false}
          refreshControl={
            <RefreshControl
              refreshing={refreshing}
              onRefresh={onRefresh}
              colors={['#10B981']}
            />
          }
        >
          {filteredResults.length === 0 ? (
            <View className="items-center mt-24">
              <View className="items-center justify-center w-20 h-20 mb-4 bg-gray-100 rounded-full">
                <Ionicons name="document-text-outline" size={40} color="#9CA3AF" />
              </View>
              <Text className="text-xl font-bold text-[#6B7280] mt-3">
                No results found
              </Text>
              <Text className="text-sm text-[#9CA3AF] mt-2 text-center">
                Your {TABS.find((t) => t.key === activeTab)?.label.toLowerCase()} results will appear here
              </Text>
            </View>
          ) : (
            filteredResults.map((item) => {
              const isDownloading = downloadingId === item.id;
              const hasImage = !!(
                item.image_url || (item as any).image_base64
              );
              const { textColor, iconBg } = getCategoryStyle(
                item.service_category
              );
              // NEW: is this specific card currently expanded?
              const isExpanded = expandedId === item.id;

              return (
                // CHANGED: View -> TouchableOpacity so the whole card is tappable to expand
                <TouchableOpacity
                  key={item.id}
                  activeOpacity={0.85}
                  onPress={() => toggleExpand(item.id)}
                  className="mb-4 overflow-hidden bg-white rounded-[20px] border border-gray-100 px-4 py-4"
                  style={{
                    shadowColor: '#000',
                    shadowOffset: { width: 0, height: 2 },
                    shadowOpacity: 0.06,
                    shadowRadius: 6,
                    elevation: 2,
                  }}
                >
                  {/* HEADER ROW */}
                  <View className="flex-row items-start justify-between">
                    <View className="flex-row items-center flex-1 gap-3">
                      <View
                        className="items-center justify-center w-12 h-12 rounded-full"
                        style={{ backgroundColor: iconBg }}
                      >
                        <ServiceIcon category={item.service_category} />
                      </View>
                      <View className="flex-1">
                        <Text
                          className="font-bold text-[15px] text-[#1F2937] uppercase"
                          numberOfLines={1}
                        >
                          {item.service_name}
                        </Text>
                        {item.service_category && (
                          <Text
                            className="text-[12px] font-semibold mt-0.5"
                            style={{ color: textColor }}
                          >
                            {item.service_category}
                          </Text>
                        )}
                      </View>
                    </View>
                    <View className="flex-row items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 border border-green-200">
                      <Ionicons
                        name="checkmark-circle"
                        size={12}
                        color="#16A34A"
                      />
                      <Text className="text-[10px] font-bold text-green-700">
                        DONE
                      </Text>
                    </View>
                  </View>

                  {/* METADATA */}
                  <View className="flex-row items-center gap-1.5 mt-3">
                    <Ionicons name="calendar-outline" size={13} color="#6b7280" />
                    <Text className="text-[12px] text-[#6B7280] font-medium">
                      {formatDate(item.created_at)}
                    </Text>
                  </View>

                  <View className="flex-row items-center gap-1.5 mt-1.5">
                    <Ionicons
                      name="image-outline"
                      size={13}
                      color="#9ca3af"
                    />
                    <Text className="text-[12px] text-[#9CA3AF] font-medium">
                      {hasImage ? 'Includes 1 image' : 'No images'}
                    </Text>
                  </View>

                  {/* ACTION BUTTONS */}
                  <View className="flex-row gap-2.5 mt-3.5">
                    <TouchableOpacity
                      activeOpacity={0.7}
                      disabled={!hasImage}
                      onPress={() => handleViewImage(item)}
                      className="flex-1 flex-row items-center justify-center gap-1.5 py-2.5 rounded-xl border"
                      style={{
                        borderColor: hasImage ? '#bbf7d0' : '#e5e7eb',
                        backgroundColor: hasImage ? '#f0fdf4' : '#f9fafb',
                        opacity: hasImage ? 1 : 0.5,
                      }}
                    >
                      <Ionicons
                        name="image-outline"
                        size={15}
                        color={hasImage ? '#16a34a' : '#9ca3af'}
                      />
                      <Text
                        className="text-[12.5px] font-bold"
                        style={{
                          color: hasImage ? '#16a34a' : '#9ca3af',
                        }}
                      >
                        View Image
                      </Text>
                    </TouchableOpacity>

                    <TouchableOpacity
                      activeOpacity={0.7}
                      disabled={isDownloading}
                      onPress={() => handleDownload(item)}
                      className="flex-1 flex-row items-center justify-center gap-1.5 py-2.5 rounded-xl border"
                      style={{
                        borderColor: isDownloading ? '#fcd34d' : '#bbf7d0',
                        backgroundColor: isDownloading ? '#fef9c3' : '#f0fdf4',
                        opacity: isDownloading ? 0.7 : 1,
                      }}
                    >
                      {isDownloading ? (
                        <>
                          <ActivityIndicator
                            size="small"
                            color="#d97706"
                          />
                          <Text className="text-[12.5px] font-bold text-amber-600">
                            Generating...
                          </Text>
                        </>
                      ) : (
                        <>
                          <Ionicons
                            name="document-text-outline"
                            size={15}
                            color="#16a34a"
                          />
                          <Text className="text-[12.5px] font-bold text-[#16a34a]">
                            Download PDF
                          </Text>
                        </>
                      )}
                    </TouchableOpacity>
                  </View>

                  {/* NEW: EXPANDABLE DETAILS — shows consultation, allergies, findings/impression */}
                  {isExpanded && (
                    <View className="pt-4 mt-4 border-t border-gray-100">
                      {(item as any).consultation ? (
                        <View className="mb-3">
                          <Text className="text-[12px] font-bold text-[#374151] mb-1">
                            Consultation
                          </Text>
                          <Text className="text-[13px] text-[#4B5563] leading-5">
                            {typeof (item as any).consultation === 'string'
                              ? (item as any).consultation
                              : JSON.stringify((item as any).consultation)}
                          </Text>
                        </View>
                      ) : null}

                      {(item as any).allergies ? (
                        <View className="mb-3">
                          <Text className="text-[12px] font-bold text-[#374151] mb-1">
                            Allergies
                          </Text>
                          <Text className="text-[13px] text-[#4B5563] leading-5">
                            {Array.isArray((item as any).allergies)
                              ? (item as any).allergies.join(', ')
                              : (item as any).allergies}
                          </Text>
                        </View>
                      ) : null}

                      {(() => {
                        const parsed = safeJsonParse(item.findings);
                        if (parsed?.findings_text || parsed?.impression) {
                          return (
                            <>
                              {parsed.findings_text && (
                                <View className="mb-3">
                                  <Text className="text-[12px] font-bold text-[#374151] mb-1">
                                    Findings
                                  </Text>
                                  <Text className="text-[13px] text-[#4B5563] leading-5">
                                    {parsed.findings_text}
                                  </Text>
                                </View>
                              )}
                              {parsed.impression && (
                                <View className="mb-3">
                                  <Text className="text-[12px] font-bold text-[#374151] mb-1">
                                    Impression
                                  </Text>
                                  <Text className="text-[13px] text-[#4B5563] leading-5">
                                    {parsed.impression}
                                  </Text>
                                </View>
                              )}
                            </>
                          );
                        }
                        if (
                          typeof item.findings === 'string' &&
                          item.findings.trim().length > 0
                        ) {
                          return (
                            <View className="mb-3">
                              <Text className="text-[12px] font-bold text-[#374151] mb-1">
                                Findings
                              </Text>
                              <Text className="text-[13px] text-[#4B5563] leading-5">
                                {item.findings}
                              </Text>
                            </View>
                          );
                        }
                        return null;
                      })()}

                      {!(item as any).consultation &&
                        !(item as any).allergies &&
                        !item.findings && (
                          <Text className="text-[12px] text-[#9CA3AF] italic">
                            No additional details available.
                          </Text>
                        )}
                    </View>
                  )}

                  {/* NEW: chevron indicator showing expand/collapse state */}
                  <View className="items-center mt-2">
                    <Ionicons
                      name={isExpanded ? 'chevron-up' : 'chevron-down'}
                      size={16}
                      color="#9CA3AF"
                    />
                  </View>
                </TouchableOpacity>
              );
            })
          )}
          <View className="h-[40px]" />
        </ScrollView>

        {/* IMAGE VIEWER MODAL */}
        <Modal
          visible={viewerVisible}
          transparent
          animationType="fade"
          onRequestClose={() => setViewerVisible(false)}
        >
          <View
            style={{
              flex: 1,
              backgroundColor: 'rgba(0,0,0,0.9)',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            {/* CLOSE BUTTON */}
            <TouchableOpacity
              style={{
                position: 'absolute',
                top: 50,
                right: 20,
                zIndex: 10,
                width: 40,
                height: 40,
                borderRadius: 20,
                backgroundColor: 'rgba(255,255,255,0.15)',
                alignItems: 'center',
                justifyContent: 'center',
              }}
              onPress={() => setViewerVisible(false)}
            >
              <Ionicons name="close" size={24} color="#fff" />
            </TouchableOpacity>

            {/* IMAGE OR ERROR */}
            {viewerItem && getImageSource(viewerItem) && !imageErrors[viewerItem.id] ? (
              <Image
                source={getImageSource(viewerItem)!}
                style={{ width: '92%', height: '70%' }}
                resizeMode="contain"
                onError={() =>
                  viewerItem && handleImageError(viewerItem.id)
                }
              />
            ) : (
              <View className="items-center px-8">
                <Ionicons name="image-outline" size={48} color="#9CA3AF" />
                <Text className="mt-3 font-semibold text-white">
                  Failed to load image
                </Text>
              </View>
            )}

            {/* IMAGE TITLE */}
            {viewerItem && (
              <Text className="mt-4 text-white/80 text-[13px] font-medium">
                {viewerItem.service_name}
              </Text>
            )}
          </View>
        </Modal>
      </View>
    );
  }