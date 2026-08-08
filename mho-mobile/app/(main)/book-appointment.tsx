import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  Linking,
  Image,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import api from './../lib/api';

interface Service {
  id: number;
  name: string;
  category: string;
  price: number;
  duration_minutes?: number;
  description: string | null;
}

interface Category {
  id: number;
  name: string;
  dbCategory: string | string[];
  displayName: string;
  description: string;
  icon: keyof typeof Ionicons.glyphMap;
}

const categories: Category[] = [
  {
    id: 1,
    name: 'X-RAY',
    dbCategory: 'X-RAY',
    displayName: 'X-Ray',
    description: 'Advanced digital imaging for accurate diagnosis',
    icon: 'body-outline',
  },
  {
    id: 2,
    name: 'ULTRASOUND',
    dbCategory: 'ULTRASOUND',
    displayName: 'Ultrasound',
    description: 'Safe, painless imaging for better insights',
    icon: 'pulse-outline',
  },
  {
    id: 3,
    name: 'LABORATORY',
    dbCategory: ['CLINICAL CHEMISTRY', 'CLINICAL MICROSCOPY', 'HEMATOLOGY', 'SEROLOGY'],
    displayName: 'Laboratory',
    description: 'Accurate and reliable lab tests',
    icon: 'flask-outline',
  },
  {
    id: 4,
    name: 'OTHERS',
    dbCategory: 'OTHERS',
    displayName: 'Other Services',
    description: "We're healthcare services for your needs",
    icon: 'heart-outline',
  },
];

export default function BookAppointmentScreen() {
  const router = useRouter();
  const [services, setServices] = useState<Service[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchServices();
  }, []);

  const fetchServices = async (): Promise<void> => {
    try {
      setLoading(true);
      setError(null);

      const response = await api.get('/services');
      const responseData = response.data;

      console.log('API Response success:', responseData.success);

      let allServices: Service[] = [];

      if (responseData.success && responseData.data) {
        if (responseData.data.all_services && Array.isArray(responseData.data.all_services)) {
          allServices = responseData.data.all_services as Service[];
        }
        else if (responseData.data.categories && typeof responseData.data.categories === 'object') {
          Object.values(responseData.data.categories).forEach((services: any) => {
            if (Array.isArray(services)) {
              allServices = [...allServices, ...(services as Service[])];
            }
          });
        }
        else if (Array.isArray(responseData.data)) {
          allServices = responseData.data as Service[];
        }
      }

      console.log('Total services loaded:', allServices.length);
      setServices(allServices);
    } catch (error: any) {
      console.error('Error fetching services:', error);
      if (error.response) {
        setError(error.response.data?.message || `Server error: ${error.response.status}`);
      } else if (error.request) {
        setError('Cannot connect to server. Please check your connection.');
      } else {
        setError(error.message || 'An unexpected error occurred');
      }
    } finally {
      setLoading(false);
    }
  };

  const getServiceCount = (category: Category): number => {
    if (Array.isArray(category.dbCategory)) {
      return services.filter((s: Service) => category.dbCategory.includes(s.category)).length;
    } else {
      return services.filter((s: Service) => s.category === category.dbCategory).length;
    }
  };

  const handleCardPress = (category: Category): void => {
    const dbCategories: string[] = Array.isArray(category.dbCategory)
      ? category.dbCategory
      : [category.dbCategory];

    const categoryServices: Service[] = services.filter((s: Service) =>
      dbCategories.includes(s.category)
    );

    console.log(`🔍 ${category.displayName} - Services found:`, categoryServices.length);

    if (categoryServices.length === 0) {
      Alert.alert('No Services', `No ${category.displayName} services available yet.`);
      return;
    }

    router.push({
      pathname: '/(main)/service-details',
      params: {
        category: category.name,
        dbCategory: JSON.stringify(dbCategories),
      },
    });
  };

  const handleBack = (): void => {
    router.back();
  };

  if (loading) {
    return (
      <View className="items-center justify-center flex-1 p-5 bg-gray-50">
        <ActivityIndicator size="large" color="#059669" />
        <Text className="mt-3 text-gray-500">Loading services...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View className="items-center justify-center flex-1 p-5 bg-gray-50">
        <Ionicons name="alert-circle" size={48} color="#EF4444" />
        <Text className="mt-3 text-base text-center text-red-500">{error}</Text>
        <TouchableOpacity
          className="mt-5 bg-emerald-700 px-5 py-2.5 rounded-xl"
          onPress={fetchServices}
        >
          <Text className="text-sm font-semibold text-white">Retry</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <ScrollView
      className="flex-1 bg-gray-50"
      showsVerticalScrollIndicator={false}
      contentContainerStyle={{ paddingBottom: 24 }}
    >
      {/* Emerald Diagnostics Header */}
      <LinearGradient
        colors={['#065f46', '#047857']}
        className="px-6 pt-9 pb-10 rounded-b-[40px]"
      >
        {/* Back Button */}
        <TouchableOpacity
          className="absolute z-10 items-center justify-center w-10 h-10 rounded-full top-12 left-5 bg-white/20"
          onPress={handleBack}
          activeOpacity={0.7}
        >
          <Ionicons name="arrow-back" size={24} color="#ffffff" />
        </TouchableOpacity>

        <Text className="mt-4 text-xl font-bold text-center text-white">
          Quality care you can trust
        </Text>
        <View className="flex-row items-center justify-center mt-1">
          <Text className="text-sm text-emerald-100">
            Accurate results for a healthier you
          </Text>
          <Ionicons name="heart-outline" size={14} color="#d1fae5" style={{ marginLeft: 6 }} />
        </View>
      </LinearGradient>

      <View className="px-4 pt-6" />

      <View className="gap-4 px-4">
        {categories.map((category: Category) => {
          const serviceCount: number = getServiceCount(category);

          return (
            <View
              key={category.id}
              className="bg-white rounded-[24px] overflow-hidden"
              style={{
                elevation: 4,
                shadowColor: '#000',
                shadowOpacity: 0.08,
                shadowRadius: 12,
                shadowOffset: { width: 0, height: 4 },
                minHeight: 180,
              }}
            >
              {/* Doctor Image Background */}
              <View className="relative h-[120px] bg-emerald-50">
                <Image
                  source={{ 
                    uri: category.id === 1 
                      ? 'https://images.pexels.com/photos/6129049/pexels-photo-6129049.jpeg?w=400'
                      : category.id === 2
                      ? 'https://images.pexels.com/photos/7089401/pexels-photo-7089401.jpeg?w=400'
                      : category.id === 3
                      ? 'https://images.pexels.com/photos/2280571/pexels-photo-2280571.jpeg?w=400'
                      : 'https://images.pexels.com/photos/5452293/pexels-photo-5452293.jpeg?w=400'
                  }}
                  className="absolute right-0 bottom-0 w-[180px] h-[120px] opacity-80"
                  resizeMode="cover"
                />
                {/* Gradient overlay */}
                <LinearGradient
                  colors={['rgba(255,255,255,1)', 'rgba(255,255,255,0)']}
                  start={{ x: 0, y: 0.5 }}
                  end={{ x: 0.7, y: 0.5 }}
                  className="absolute inset-0"
                />
                
                {/* Icon + Service Count Row */}
                <View className="flex-row items-start justify-between p-4">
                  {/* Icon */}
                  <View className="items-center justify-center w-12 h-12 rounded-full bg-emerald-700" style={{ elevation: 3 }}>
                    <Ionicons name={category.icon} size={24} color="#ffffff" />
                  </View>
                  
                  {/* Service Count - Top Right */}
                  <View className="bg-white/90 rounded-full px-3 py-1.5" style={{ elevation: 2 }}>
                    <Text className="text-[12px] font-bold text-emerald-700">
                      {serviceCount} service{serviceCount === 1 ? '' : 's'}
                    </Text>
                  </View>
                </View>
              </View>

              {/* Bottom Content */}
              <View className="p-4">
                <Text className="text-lg font-extrabold tracking-wide text-emerald-900">
                  {category.displayName.toUpperCase()}
                </Text>
                <Text className="mt-1 text-xs leading-4 text-gray-500" numberOfLines={2}>
                  {category.description}
                </Text>

                {/* Select Services Button - Bottom Right */}
                <View className="flex-row justify-end mt-3">
                  <TouchableOpacity
                    className="flex-row items-center px-4 py-2.5 rounded-2xl bg-emerald-700"
                    activeOpacity={0.85}
                    onPress={() => handleCardPress(category)}
                    style={{ elevation: 2 }}
                  >
                    <Text className="mr-1.5 text-xs font-bold text-white">Select services</Text>
                    <Ionicons name="chevron-forward" size={14} color="#ffffff" />
                  </TouchableOpacity>
                </View>
              </View>
            </View>
          );
        })}
      </View>

      {/* Need Help footer */}
      <View className="mx-4 mt-6 bg-emerald-800 rounded-[20px] p-5 flex-row items-center justify-between">
        <View className="flex-row items-center flex-1">
          <View className="items-center justify-center w-12 h-12 mr-3 rounded-full bg-white/15">
            <Ionicons name="headset-outline" size={24} color="#ffffff" />
          </View>
          <View>
            <Text className="text-base font-bold text-white">Need Help?</Text>
            <Text className="text-xs text-emerald-100">We,re here for you</Text>
          </View>
        </View>
        <TouchableOpacity
          className="flex-row items-center px-4 py-2 ml-3 bg-white rounded-full"
          activeOpacity={0.85}
          onPress={() => Linking.openURL('tel:09082457001')}
        >
          <Ionicons name="call" size={14} color="#065f46" />
          <Text className="ml-2 text-xs font-bold text-emerald-800">Contact Us</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}