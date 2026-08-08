import React, { useState, useEffect, useCallback, useRef } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  Image,
  StyleSheet,
  Modal,
  Pressable,
  TextInput,
} from 'react-native';
import { useLocalSearchParams, useRouter, useFocusEffect } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from './../lib/api';
import { Service } from '../../types/patient';
import { C } from '../../constants/colors';

const getServiceTag = (name: string): { label: string; icon: string } => {
  const n = name.toLowerCase();
  if (n.includes('massage') || n.includes('relaxation') || n.includes('annual pe'))
    return { label: 'Relax & Rejuvenate', icon: '' };
  if (n.includes('facial') || n.includes('glow') || n.includes('skin'))
    return { label: 'Glow & Refresh', icon: '' };
  if (n.includes('foot') || n.includes('reflexology'))
    return { label: 'Relax & Balance', icon: '' };
  if (n.includes('scrub') || n.includes('exfoliat'))
    return { label: 'Smooth & Radiant', icon: '' };
  if (n.includes('stone') || n.includes('hot'))
    return { label: 'Relax & Unwind', icon: '' };
  if (n.includes('blood') || n.includes('cbc') || n.includes('hematology'))
    return { label: 'Lab Test', icon: '' };
  if (n.includes('x-ray') || n.includes('xray'))
    return { label: 'Imaging', icon: '' };
  if (n.includes('ultrasound') || n.includes('abdomen'))
    return { label: 'Ultrasound', icon: '' };
  return { label: 'Wellness Service', icon: '' };
};

const getServiceIcon = (serviceName: string): any => {
  const name = serviceName.toLowerCase();
  if (name.includes('massage') || name.includes('annual pe')) return 'body-outline';
  if (name.includes('facial') || name.includes('skin')) return 'happy-outline';
  if (name.includes('foot') || name.includes('reflexology')) return 'footsteps-outline';
  if (name.includes('scrub')) return 'sparkles-outline';
  if (name.includes('stone')) return 'layers-outline';
  if (name.includes('blood') || name.includes('cbc')) return 'water-outline';
  if (name.includes('x-ray')) return 'medkit-outline';
  if (name.includes('ultrasound')) return 'scan-outline';
  if (name.includes('urine') || name.includes('fecal')) return 'flask-outline';
  return 'medical-outline';
};

export default function ServiceDetailsScreen() {
  const { category, dbCategory } = useLocalSearchParams();
  const router = useRouter();
  const [services, setServices] = useState<Service[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [cartCount, setCartCount] = useState<number>(0);
  const [modalVisible, setModalVisible] = useState<boolean>(false);
  const [selectedService, setSelectedService] = useState<Service | null>(null);
  const [searchQuery, setSearchQuery] = useState<string>('');
  
  // 👇 Track if initial fetch is done
  const isFirstFetch = useRef(true);

  // 👇 FAST FETCH using useFocusEffect - no loading indicator on refocus
  useFocusEffect(
    useCallback(() => {
      // Only show loading on very first fetch
      if (isFirstFetch.current) {
        setLoading(true);
      }
      
      if (category) {
        fetchServices(String(category), String(dbCategory || ''));
      } else {
        fetchAllServices();
      }
      
      refreshCartCount();
      
      // Mark first fetch as done
      isFirstFetch.current = false;
    }, [category, dbCategory])
  );

  const refreshCartCount = async (): Promise<void> => {
    try {
      const cart: string | null = await AsyncStorage.getItem('booking_cart');
      const cartItems: any[] = cart ? JSON.parse(cart) : [];
      setCartCount(cartItems.length);
    } catch (e) {
      setCartCount(0);
    }
  };

  const fetchAllServices = async (): Promise<void> => {
    try {
      setError(null);
      const response = await api.get('/services');
      const data = response.data;
      let allServices: Service[] = [];
      if (data.success && data.data) {
        if (data.data.all_services && Array.isArray(data.data.all_services)) {
          allServices = data.data.all_services as Service[];
        } else if (data.data.categories && typeof data.data.categories === 'object') {
          Object.values(data.data.categories).forEach((services: any) => {
            if (Array.isArray(services)) allServices = [...allServices, ...(services as Service[])];
          });
        }
      }
      setServices(allServices);
    } catch (error: any) {
      setError(error.message || 'Failed to load services');
    } finally {
      setLoading(false); // 👈 Always turn off loading
    }
  };

  const fetchServices = async (categoryParam: string, dbCategoryParam: string): Promise<void> => {
    try {
      setError(null);
      const response = await api.get('/services');
      const data = response.data;
      let allServices: Service[] = [];
      if (data.success && data.data) {
        if (data.data.all_services && Array.isArray(data.data.all_services)) {
          allServices = data.data.all_services as Service[];
        } else if (data.data.categories && typeof data.data.categories === 'object') {
          Object.values(data.data.categories).forEach((services: any) => {
            if (Array.isArray(services)) allServices = [...allServices, ...(services as Service[])];
          });
        }
      }
      let targetCategories: string[] = [];
      if (dbCategoryParam) {
        try { targetCategories = JSON.parse(dbCategoryParam) as string[]; }
        catch (e) { targetCategories = [dbCategoryParam]; }
      } else {
        targetCategories = [categoryParam.toUpperCase()];
      }
      const filtered = allServices.filter((s: Service) => targetCategories.includes(s.category));
      if (filtered.length > 0) {
        setServices(filtered);
        setError(null);
      } else {
        setServices(allServices);
        setError(`No exact matches. Showing all ${allServices.length} services.`);
      }
    } catch (error: any) {
      setError(error.message || 'Failed to load services');
    } finally {
      setLoading(false); // 👈 Always turn off loading
    }
  };

  const handleServicePress = (service: Service): void => {
    setSelectedService(service);
    setModalVisible(true);
  };

  const confirmAddToCart = async (): Promise<void> => {
    if (!selectedService) return;
    
    try {
      const cart: string | null = await AsyncStorage.getItem('booking_cart');
      let cartItems: any[] = cart ? JSON.parse(cart) : [];
      const existingItem = cartItems.find((item: any) => item.id === selectedService.id);
      
      if (existingItem) {
        Alert.alert('Already Added', `${selectedService.name} is already in your cart.`);
        setModalVisible(false);
        setSelectedService(null);
        return;
      }
      
      cartItems.push({
        id: selectedService.id,
        name: selectedService.name,
        category: selectedService.category,
        price: selectedService.price,
        duration: selectedService.duration_minutes || 30,
      });
      
      await AsyncStorage.setItem('booking_cart', JSON.stringify(cartItems));
      setCartCount(cartItems.length);
      setModalVisible(false);
      
      Alert.alert('Added!', `${selectedService.name} was added to your list.`);
    } catch (error) {
      Alert.alert('Error', 'Failed to add to cart');
    } finally {
      setSelectedService(null);
    }
  };

  const cancelAddToCart = (): void => {
    setModalVisible(false);
    setSelectedService(null);
  };

  const Header = () => (
    <View style={{
      backgroundColor: C.headerBg,
      paddingTop: 12,
      paddingBottom: 20,
      paddingHorizontal: 16,
      alignItems: 'center',
    }}>
      <View style={{
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        width: '100%',
        marginBottom: 4,
      }}>
        <TouchableOpacity
          onPress={() => router.back()}
          style={{
            width: 40, height: 40, borderRadius: 12,
            backgroundColor: 'rgba(255,255,255,0.15)',
            alignItems: 'center', justifyContent: 'center',
          }}
        >
          <Ionicons name="arrow-back" size={22} color="#fff" />
        </TouchableOpacity>

        <View style={{ alignItems: 'center' }}>
          <Text style={{
            fontSize: 26, fontWeight: '700', color: '#fff',
            fontFamily: 'Georgia', letterSpacing: 0.3,
          }}>
            Service Details
          </Text>
        </View>

        <TouchableOpacity
          onPress={() => router.push('/cart')}
          style={{
            width: 40, height: 40, borderRadius: 12,
            backgroundColor: 'rgba(255,255,255,0.15)',
            alignItems: 'center', justifyContent: 'center',
          }}
        >
          <Ionicons name="list-outline" size={22} color="#fff" />
          {cartCount > 0 && (
            <View style={{
              position: 'absolute', top: -4, right: -4,
              minWidth: 18, height: 18, borderRadius: 9,
              backgroundColor: '#EF4444',
              alignItems: 'center', justifyContent: 'center',
              paddingHorizontal: 4,
            }}>
              <Text style={{ fontSize: 10, fontWeight: '700', color: '#fff' }}>{cartCount}</Text>
            </View>
          )}
        </TouchableOpacity>
      </View>
    </View>
  );

  const ConfirmationModal = () => (
    <Modal
      animationType="fade"
      transparent={true}
      visible={modalVisible}
      onRequestClose={cancelAddToCart}
    >
      <Pressable 
        style={{
          flex: 1,
          backgroundColor: C.overlay,
          justifyContent: 'center',
          alignItems: 'center',
          padding: 24,
        }}
        onPress={cancelAddToCart}
      >
        <Pressable 
          style={{
            backgroundColor: C.white,
            borderRadius: 24,
            width: '100%',
            maxWidth: 320,
            padding: 24,
            shadowColor: '#000',
            shadowOffset: { width: 0, height: 10 },
            shadowOpacity: 0.15,
            shadowRadius: 20,
            elevation: 10,
          }}
          onPress={() => {}} 
        >
          <Text style={{
            fontSize: 18,
            fontWeight: '600',
            color: C.textDark,
            textAlign: 'center',
            marginBottom: 8,
            fontFamily: 'Georgia',
          }}>
            Add this service?
          </Text>

          <Text style={{
            fontSize: 16,
            fontWeight: '700',
            color: C.green,
            textAlign: 'center',
            marginBottom: 24,
            textTransform: 'uppercase',
            letterSpacing: 0.5,
          }}>
            {selectedService?.name}
          </Text>

          <View style={{ flexDirection: 'row', gap: 12 }}>
            <TouchableOpacity
              onPress={cancelAddToCart}
              style={{
                flex: 1,
                backgroundColor: C.redLight,
                borderRadius: 14,
                paddingVertical: 14,
                alignItems: 'center',
                borderWidth: 1,
                borderColor: '#FECACA',
              }}
            >
              <Text style={{ fontSize: 16, fontWeight: '600', color: C.red }}>
                No, Cancel
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              onPress={confirmAddToCart}
              style={{
                flex: 1,
                backgroundColor: C.green,
                borderRadius: 14,
                paddingVertical: 14,
                alignItems: 'center',
                shadowColor: C.green,
                shadowOffset: { width: 0, height: 4 },
                shadowOpacity: 0.3,
                shadowRadius: 8,
                elevation: 4,
              }}
            >
              <Text style={{ fontSize: 16, fontWeight: '700', color: C.white }}>
                Yes, Add
              </Text>
            </TouchableOpacity>
          </View>
        </Pressable>
      </Pressable>
    </Modal>
  );

  const q = searchQuery.trim().toLowerCase();
  const displayedServices = q
    ? services.filter((s) =>
        s.name.toLowerCase().includes(q) ||
        (s.category || '').toLowerCase().includes(q) ||
        (s.description || '').toLowerCase().includes(q)
      )
    : services;

  const cardBackgrounds = [
    'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=500',
    'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=500',
    'https://images.unsplash.com/photo-1551076805-e1869033e561?w=500',
    'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=500',
    'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=500',
    'https://images.unsplash.com/photo-1551601651-2a8555f1a29e?w=500',
    'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500',
    'https://images.unsplash.com/photo-1581595220892-b0739db3ba8c?w=500',
  ];

  if (loading) {
    return (
      <SafeAreaView style={{ flex: 1, backgroundColor: C.headerBg }}>
        <Header />
        <View style={{ flex: 1, backgroundColor: C.bg, justifyContent: 'center', alignItems: 'center' }}>
          <ActivityIndicator size="large" color={C.green} />
          <Text style={{ marginTop: 12, color: C.textMuted, fontSize: 14 }}>Loading services...</Text>
        </View>
      </SafeAreaView>
    );
  }

  if (error && services.length === 0) {
    return (
      <SafeAreaView style={{ flex: 1, backgroundColor: C.headerBg }}>
        <Header />
        <View style={{ flex: 1, backgroundColor: C.bg, justifyContent: 'center', alignItems: 'center', padding: 24 }}>
          <Ionicons name="alert-circle-outline" size={48} color="#EF4444" />
          <Text style={{ marginTop: 12, color: '#EF4444', fontSize: 14, textAlign: 'center' }}>{error}</Text>
          <TouchableOpacity
            style={{ marginTop: 20, backgroundColor: C.green, paddingHorizontal: 24, paddingVertical: 12, borderRadius: 12 }}
            onPress={() => category ? fetchServices(String(category), String(dbCategory || '')) : fetchAllServices()}
          >
            <Text style={{ color: '#fff', fontWeight: '600' }}>Retry</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  if (services.length === 0) {
    return (
      <SafeAreaView style={{ flex: 1, backgroundColor: C.headerBg }}>
        <Header />
        <View style={{ flex: 1, backgroundColor: C.bg, justifyContent: 'center', alignItems: 'center', padding: 24 }}>
          <Ionicons name="fitness-outline" size={48} color={C.textMuted} />
          <Text style={{ marginTop: 12, color: C.textDark, fontSize: 16, fontWeight: '600', textAlign: 'center' }}>
            No services available
          </Text>
          <TouchableOpacity
            style={{ marginTop: 20, backgroundColor: C.green, paddingHorizontal: 24, paddingVertical: 12, borderRadius: 12 }}
            onPress={() => category ? fetchServices(String(category), String(dbCategory || '')) : fetchAllServices()}
          >
            <Text style={{ color: '#fff', fontWeight: '600' }}>Refresh</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: C.headerBg }}>
      <Header />
      
      {/* 👇 FIXED SEARCH BAR - DILI MU-SCROLL 👇 */}
      <View style={{ 
        backgroundColor: C.bg, 
        paddingHorizontal: 16, 
        paddingTop: 16, 
        paddingBottom: 8 
      }}>
        <View style={{
          flexDirection: 'row',
          alignItems: 'center',
          backgroundColor: C.white,
          borderRadius: 25,
          paddingHorizontal: 14,
          borderWidth: 1,
          borderColor: C.greenBorder,
          shadowColor: '#000',
          shadowOffset: { width: 0, height: 2 },
          shadowOpacity: 0.05,
          shadowRadius: 6,
          elevation: 2,
        }}>
          <Ionicons name="search" size={18} color={C.textMuted} />
          <TextInput
            value={searchQuery}
            onChangeText={setSearchQuery}
            placeholder="Search services..."
            placeholderTextColor={C.textMuted}
            style={{
              flex: 1,
              paddingVertical: 12,
              paddingHorizontal: 10,
              fontSize: 14,
              color: C.textDark,
            }}
          />
          {searchQuery.length > 0 && (
            <TouchableOpacity onPress={() => setSearchQuery('')} hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}>
              <Ionicons name="close-circle" size={18} color={C.textMuted} />
            </TouchableOpacity>
          )}
        </View>
      </View>
      {/* 👆 END FIXED SEARCH BAR 👆 */}

      {/* 👇 SCROLLABLE CONTENT - CARDS RA ANG MU-SCROLL 👇 */}
      <ScrollView
        style={{ flex: 1, backgroundColor: C.bg }}
        contentContainerStyle={{ paddingBottom: 40 }}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
      >
        {displayedServices.length === 0 ? (
          <View style={{ alignItems: 'center', paddingTop: 60, paddingHorizontal: 24 }}>
            <Ionicons name="search-outline" size={40} color={C.textMuted} />
            <Text style={{ marginTop: 12, color: C.textDark, fontSize: 15, fontWeight: '600', textAlign: 'center' }}>
              No services match "{searchQuery}"
            </Text>
            <Text style={{ marginTop: 4, color: C.textMuted, fontSize: 13, textAlign: 'center' }}>
              Try a different keyword
            </Text>
          </View>
        ) : (
          <View style={{ padding: 14 }}>
            {displayedServices.map((service: Service, index: number) => {
              const tag = getServiceTag(service.name);
              const icon = getServiceIcon(service.name);
              const bgImage = cardBackgrounds[index % cardBackgrounds.length];
              const duration = service.duration_minutes || 60;

              return (
                <TouchableOpacity
                  key={service.id || index}
                  activeOpacity={0.85}
                  onPress={() => handleServicePress(service)}
                  style={{
                    borderRadius: 25,
                    marginBottom: 16,
                    overflow: 'hidden',
                    borderWidth: 1,
                    borderColor: C.greenBorder,
                    shadowColor: '#000',
                    shadowOffset: { width: 0, height: 3 },
                    shadowOpacity: 0.1,
                    shadowRadius: 10,
                    elevation: 4,
                    minHeight: 155,
                  }}
                >
                  <Image
                    source={{ uri: bgImage }}
                    style={{
                      position: 'absolute',
                      top: 0, left: 0, right: 0, bottom: 0,
                    }}
                    resizeMode="cover"
                  />
                  
                  <View style={{
                    position: 'absolute',
                    top: 0, left: 0, right: 0, bottom: 0,
                    backgroundColor: 'rgba(255, 255, 255, 0.85)',
                  }} />

                  
                  <View style={{ flexDirection: 'row' }}>
                    
                    <View style={{
                      width: 80,
                      minHeight: 155,
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                      <View style={{
                        width: 56,
                        height: 56,
                        borderRadius: 28,
                        backgroundColor: C.green,
                        justifyContent: 'center',
                        alignItems: 'center',
                        shadowColor: '#000',
                        shadowOffset: { width: 0, height: 2 },
                        shadowOpacity: 0.15,
                        shadowRadius: 5,
                        elevation: 4,
                      }}>
                        <Ionicons name={icon} size={28} color="#fff" />
                      </View>
                    </View>

                    
                    <View style={{ flex: 1, paddingVertical: 16, paddingRight: 8, justifyContent: 'center' }}>
                      <Text style={{
                        fontSize: 16,
                        fontWeight: '800',
                        color: C.textDark,
                        textTransform: 'uppercase',
                        fontFamily: 'Georgia',
                        marginBottom: 8,
                        letterSpacing: 0.5,
                      }}>
                        {service.name}
                      </Text>

                      {service.description ? (
                        <Text style={{
                          fontSize: 12, color: C.textMid,
                          lineHeight: 18, marginBottom: 12,
                        }} numberOfLines={2}>
                          {service.description}
                        </Text>
                      ) : null}

                      {/* Tag + Duration Row */}
                      <View style={{ flexDirection: 'row', alignItems: 'center', gap: 8, flexWrap: 'wrap' }}>
                        <View style={{
                          flexDirection: 'row', alignItems: 'center', gap: 4,
                          backgroundColor: 'rgba(255, 255, 255, 0.9)',
                          borderWidth: 1, borderColor: C.tagBorder,
                          borderRadius: 20,
                          paddingHorizontal: 12, paddingVertical: 5,
                        }}>
                          <Text style={{ fontSize: 11 }}>{tag.icon}</Text>
                          <Text style={{ fontSize: 11, color: C.textMid, fontWeight: '500' }}>
                            {tag.label}
                          </Text>
                        </View>

                       
                        <View style={{
                          flexDirection: 'row', alignItems: 'center', gap: 4,
                          backgroundColor: 'rgba(30, 94, 76, 0.08)',
                          borderRadius: 20,
                          paddingHorizontal: 12, paddingVertical: 5,
                        }}>
                          <Ionicons name="time-outline" size={13} color={C.green} />
                          <Text style={{ fontSize: 11, color: C.green, fontWeight: '600' }}>
                            {duration} min
                          </Text>
                        </View>
                      </View>
                    </View>

                    <View style={{
                      width: 105,
                      borderLeftWidth: 1,
                      borderLeftColor: 'rgba(200, 221, 213, 0.5)',
                      justifyContent: 'center',
                      alignItems: 'center',
                      padding: 12,
                      backgroundColor: 'rgba(255, 255, 255, 0.3)',
                    }}>
                      <Text style={{
                        fontSize: 20,
                        fontWeight: '800',
                        color: C.green,
                        textAlign: 'center',
                      }}>
                        ₱{Number(service.price).toLocaleString('en-PH', { minimumFractionDigits: 0 })}
                      </Text>
                      <View style={{ 
                        marginTop: 6,
                        backgroundColor: C.green,
                        paddingHorizontal: 12,
                        paddingVertical: 5,
                        borderRadius: 14,
                      }}>
                        <Text style={{ fontSize: 11, fontWeight: '700', color: '#fff' }}>
                          ADD +
                        </Text>
                      </View>
                    </View>
                  </View>
                </TouchableOpacity>
              );
            })}
          </View>
        )}

        <View style={{ flexDirection: 'row', justifyContent: 'center', alignItems: 'center', gap: 6, paddingBottom: 8 }}>
          <Ionicons name="shield-checkmark-outline" size={14} color={C.textMuted} />
          <Text style={{ fontSize: 12, color: C.textMuted }}>Your wellness is our priority.</Text>
        </View>
      </ScrollView>
      {/* 👆 END SCROLLABLE CONTENT 👆 */}

      
      <ConfirmationModal />
    </SafeAreaView>
  );
}