import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  Alert,
  Image,
  ScrollView,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface CartItem {
  id: number;
  name: string;
  price: number;
  duration: number;
  description?: string;
  image?: string;
  category?: string;
}

// ─── Color tokens ────────────────────────────────────────────
const C = {
  bg: '#F0F0EC',
  white: '#FFFFFF',
  green: '#1E5E4C',
  greenDark: '#0F3D2E', // Dark emerald for header
  greenMid: '#2E7D5E',
  greenLight: '#EAF2EE',
  greenBorder: '#C8DDD5',
  textDark: '#1A3329',
  textMid: '#4A6B5D',
  textMuted: '#8AA89A',
  divider: '#D6E5DF',
  red: '#C0392B',
  redBg: '#FDECEA',
};

// Online medical images from Pexels (reliable CDN)
const getServiceImage = (serviceName: string): string => {
  const n = serviceName.toLowerCase();
  
  // X-Ray / Chest X-Ray
  if (n.includes('x-ray') || n.includes('xray') || n.includes('chest')) {
    return 'https://images.pexels.com/photos/6129049/pexels-photo-6129049.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Ultrasound
  if (n.includes('ultrasound') || n.includes('abdomen') || n.includes('pelvic') || n.includes('thyroid') || n.includes('breast') || n.includes('doppler') || n.includes('transvaginal')) {
    return 'https://images.pexels.com/photos/7089401/pexels-photo-7089401.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Blood tests / CBC / Hematology
  if (n.includes('blood') || n.includes('cbc') || n.includes('hematology') || n.includes('complete blood')) {
    return 'https://images.pexels.com/photos/2280571/pexels-photo-2280571.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Urinalysis / Fecalysis
  if (n.includes('urine') || n.includes('urinalysis') || n.includes('fecal') || n.includes('stool')) {
    return 'https://images.pexels.com/photos/5452293/pexels-photo-5452293.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Laboratory / Chemistry
  if (n.includes('lab') || n.includes('chemistry') || n.includes('glucose') || n.includes('lipid') || n.includes('creatinine') || n.includes('hba1c')) {
    return 'https://images.pexels.com/photos/3735747/pexels-photo-3735747.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // ECG
  if (n.includes('ecg') || n.includes('electrocardiogram')) {
    return 'https://images.pexels.com/photos/4386466/pexels-photo-4386466.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Massage
  if (n.includes('massage')) {
    return 'https://images.pexels.com/photos/3757657/pexels-photo-3757657.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
  }
  
  // Default medical image
  return 'https://images.pexels.com/photos/3844581/pexels-photo-3844581.jpeg?auto=compress&cs=tinysrgb&w=400&h=400&fit=crop';
};

export default function CartScreen() {
  const router = useRouter();
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [total, setTotal] = useState<number>(0);
  const [totalMinutes, setTotalMinutes] = useState<number>(0);

  useEffect(() => {
    loadCart();
  }, []);

  const loadCart = async () => {
    try {
      const cart = await AsyncStorage.getItem('booking_cart');
      const items: CartItem[] = cart ? JSON.parse(cart) : [];
      const processedItems = items.map((item) => ({
        ...item,
        price: typeof item.price === 'number' ? item.price : Number(item.price) || 0,
        duration: typeof item.duration === 'number' ? item.duration : Number(item.duration) || 30,
        image: item.image || getServiceImage(item.name),
      }));
      setCartItems(processedItems);
      setTotal(processedItems.reduce((acc, item) => acc + (item.price || 0), 0));
      setTotalMinutes(processedItems.reduce((acc, item) => acc + (item.duration || 0), 0));
    } catch (error) {
      console.error('Error loading cart:', error);
      setCartItems([]);
      setTotal(0);
      setTotalMinutes(0);
    }
  };

  const removeItem = async (id: number) => {
    try {
      const updated = cartItems.filter(item => item.id !== id);
      setCartItems(updated);
      await AsyncStorage.setItem('booking_cart', JSON.stringify(updated));
      setTotal(updated.reduce((acc, item) => acc + (item.price || 0), 0));
      setTotalMinutes(updated.reduce((acc, item) => acc + (item.duration || 0), 0));
      Alert.alert('Removed', 'Service removed from your list.');
    } catch (error) {
      console.error('Error removing item:', error);
    }
  };

  const proceedToCheckout = () => {
    if (cartItems.length === 0) {
      Alert.alert('No Services', 'Please add services to your list first.');
      return;
    }
    const safeCartItems = cartItems.map(item => ({
      id: item.id,
      name: item.name,
      price: typeof item.price === 'number' ? item.price : Number(item.price) || 0,
      duration: typeof item.duration === 'number' ? item.duration : Number(item.duration) || 30,
    }));
    const totalAmount = safeCartItems.reduce((acc, item) => acc + (item.price || 0), 0);
    const totalDuration = safeCartItems.reduce((acc, item) => acc + (item.duration || 0), 0);
    router.push({
      pathname: '/(main)/booking-details',
      params: {
        fromCart: 'true',
        cartItems: JSON.stringify(safeCartItems),
        totalAmount: totalAmount.toString(),
        totalMinutes: totalDuration.toString(),
        serviceCount: cartItems.length.toString(),
      },
    });
  };

  const renderItem = ({ item }: { item: CartItem }) => {
    const priceValue = typeof item.price === 'number' ? item.price : Number(item.price) || 0;
    const durationValue = typeof item.duration === 'number' ? item.duration : Number(item.duration) || 30;
    const imageUri = item.image || getServiceImage(item.name);

    return (
      <View style={styles.cartItem}>
        {/* Online Image */}
        <Image 
          source={{ uri: imageUri }} 
          style={styles.itemImage}
          onError={() => console.log('Image failed to load:', imageUri)}
        />

        {/* Info */}
        <View style={styles.itemInfo}>
          <Text style={styles.itemName}>{item.name}</Text>
          {item.description ? (
            <Text style={styles.itemDesc} numberOfLines={2}>{item.description}</Text>
          ) : null}
          <View style={styles.itemMeta}>
            <Ionicons name="time-outline" size={13} color={C.textMuted} />
            <Text style={styles.itemDuration}>{durationValue} min</Text>
          </View>
        </View>

        {/* Price + Remove */}
          <View style={styles.itemRight}>
            <Text style={styles.itemPrice}>₱{priceValue.toFixed(2)}</Text>
            <TouchableOpacity onPress={() => removeItem(item.id)} style={styles.removeBtn}>
              <Ionicons name="trash-outline" size={16} color="#ef4444" />
            </TouchableOpacity>
          </View>
      </View>
    );
  };

  if (cartItems.length === 0) {
    return (
      <View style={styles.container}>
        {/* Header - Dark Emerald */}
        <View style={styles.headerDark}>
          <TouchableOpacity onPress={() => router.back()} style={styles.backBtnLight}>
            <Ionicons name="arrow-back" size={22} color={C.white} />
          </TouchableOpacity>
          <View style={styles.headerCenter}>
            <Text style={styles.headerTitleLight}>Selected Service</Text>
          </View>
          <View style={{ width: 36 }} />
        </View>

        <View style={styles.emptyContainer}>
          <View style={styles.emptyIconBox}>
            <Ionicons name="cart-outline" size={48} color={C.textMuted} />
          </View>
          <Text style={styles.emptyText}>Your list is empty</Text>
          <Text style={styles.emptySubText}>Add services from the categories to get started.</Text>
          <TouchableOpacity
            style={styles.browseBtn}
            onPress={() => router.push('/(main)/book-appointment')}
          >
            <Text style={styles.browseBtnText}>Browse Services</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* ── Header Dark Emerald ── */}
      <View style={styles.headerDark}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backBtnLight}>
          <Ionicons name="arrow-back" size={22} color={C.white} />
        </TouchableOpacity>
        <View style={styles.headerCenter}>
          <Text style={styles.headerTitleLight}>Selected Service</Text>
        </View>
        <View style={{ width: 36 }} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingBottom: 24, paddingTop: 16 }}>
        {/* ── Service List ── */}
        <View style={styles.sectionCard}>
          <Text style={styles.sectionLabel}>YOUR SELECTED SERVICES</Text>
          {cartItems.map((item, index) => (
            <React.Fragment key={item.id.toString()}>
              {renderItem({ item })}
              {index < cartItems.length - 1 && <View style={styles.itemDivider} />}
            </React.Fragment>
          ))}
        </View>
      </ScrollView>

      {/* ── Footer ── */}
      <View style={styles.footer}>
        <View style={styles.summaryCard}>
          <View style={styles.summaryRow}>
            <View style={styles.summaryIconBox}>
              <Ionicons name="wallet-outline" size={15} color={C.white} />
            </View>
            <Text style={styles.summaryLabel}>Total Amount</Text>
            <Text style={styles.summaryAmount}>
              ₱{(typeof total === 'number' ? total : 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
            </Text>
          </View>

          <View style={styles.summaryDivider} />

          <View style={styles.summaryRow}>
            <View style={styles.summaryIconBox}>
              <Ionicons name="time-outline" size={15} color={C.white} />
            </View>
            <Text style={styles.summaryLabel}>Total Duration</Text>
            <Text style={styles.summaryDuration}>{totalMinutes} min</Text>
          </View>
        </View>

        <TouchableOpacity style={styles.checkoutBtn} onPress={proceedToCheckout} activeOpacity={0.88}>
          <Text style={styles.checkoutBtnText}>PROCEED TO APPOINTMENT</Text>
          <Ionicons name="arrow-forward" size={20} color={C.white} />
        </TouchableOpacity>
        <View style={styles.secureRow}>
          <Ionicons name="shield-checkmark-outline" size={13} color={C.textMuted} />
          <Text style={styles.secureText}>Your booking is safe and secure.</Text>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: C.bg,
  },

  // ── Header Dark Emerald ──
  headerDark: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingTop: 50,
    paddingBottom: 20,
    paddingHorizontal: 20,
    backgroundColor: C.greenDark,
    borderBottomLeftRadius: 1,
    borderBottomRightRadius: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 5,
  },
  backBtnLight: {
    width: 36,
    height: 36,
    justifyContent: 'center',
    alignItems: 'center',
  },
  headerTitleLight: {
    fontSize: 24,
    fontWeight: '700',
    color: C.white,
    letterSpacing: 0.2,
    fontFamily: 'Georgia',
  },
  locationTextLight: {
    fontSize: 13,
    color: 'rgba(255,255,255,0.85)',
    fontWeight: '500',
  },

  // ── Header (Old - kept for reference) ──
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingTop: 36,
    paddingBottom: 12,
    paddingHorizontal: 20,
    backgroundColor: C.bg,
  },
  backBtn: {
    width: 36,
    height: 36,
    justifyContent: 'center',
    alignItems: 'center',
  },
  headerCenter: {
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: '700',
    color: C.textDark,
    letterSpacing: 0.2,
    fontFamily: 'Georgia',
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 3,
    marginTop: 2,
  },
  locationText: {
    fontSize: 13,
    color: C.green,
    fontWeight: '500',
  },

  // ── Banner (Removed) ──

  // ── Section Card ──
  sectionCard: {
    backgroundColor: C.white,
    borderRadius: 16,
    marginHorizontal: 16,
    marginBottom: 14,
    padding: 16,
    borderWidth: 1,
    borderColor: C.greenBorder,
  },
  sectionLabel: {
    fontSize: 11,
    fontWeight: '700',
    color: C.green,
    letterSpacing: 1.2,
    marginBottom: 14,
  },

  // ── Cart Item ──
  cartItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    paddingVertical: 10,
  },
  itemImage: {
    width: 75,
    height: 75,
    borderRadius: 12,
    backgroundColor: C.greenLight,
  },
  itemInfo: {
    flex: 1,
  },
  itemName: {
    fontSize: 15,
    fontWeight: '700',
    color: C.textDark,
    marginBottom: 4,
    fontFamily: 'Georgia',
  },
  itemDesc: {
    fontSize: 12,
    color: C.textMid,
    lineHeight: 17,
    marginBottom: 6,
  },
  itemMeta: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  itemDuration: {
    fontSize: 12,
    color: C.textMuted,
  },
  itemRight: {
    alignItems: 'flex-end',
    gap: 8,
  },
  itemPrice: {
    fontSize: 15,
    fontWeight: '700',
    color: C.green,
  },
  removeBtn: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: '#F0F0EC',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: C.divider,
  },
  itemDivider: {
    height: 1,
    backgroundColor: C.divider,
    marginVertical: 4,
  },

  // ── Summary Card ──
  summaryCard: {
    backgroundColor: C.green,
    borderRadius: 14,
    paddingHorizontal: 20,
    paddingVertical: 10,
    marginBottom: 10,
  },
  summaryRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
    paddingVertical: 5,
  },
  summaryIconBox: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: 'rgba(255,255,255,0.15)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  summaryLabel: {
    flex: 1,
    fontSize: 14,
    color: 'rgba(255,255,255,0.85)',
    fontWeight: '500',
  },
  summaryAmount: {
    fontSize: 15,
    fontWeight: '700',
    color: C.white,
    letterSpacing: 0.3,
  },
  summaryDuration: {
    fontSize: 15,
    fontWeight: '700',
    color: C.white,
  },
  summaryDivider: {
    height: 1,
    backgroundColor: 'rgba(255,255,255,0.2)',
    marginVertical: 2,
  },

  // ── Footer ──
  footer: {
    backgroundColor: C.bg,
    paddingHorizontal: 16,
    paddingTop: 12,
    paddingBottom: 32,
    borderTopWidth: 1,
    borderTopColor: C.divider,
  },
  checkoutBtn: {
    backgroundColor: C.green,
    borderRadius: 14,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 17,
    gap: 10,
  },
  checkoutBtnText: {
    color: C.white,
    fontSize: 15,
    fontWeight: '700',
    letterSpacing: 1,
  },
  secureRow: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 5,
    marginTop: 10,
  },
  secureText: {
    fontSize: 12,
    color: C.textMuted,
  },

  // ── Empty State ──
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 40,
    gap: 10,
  },
  emptyIconBox: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: C.greenLight,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 8,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: '700',
    color: C.textDark,
    fontFamily: 'Georgia',
  },
  emptySubText: {
    fontSize: 14,
    color: C.textMuted,
    textAlign: 'center',
    lineHeight: 20,
  },
  browseBtn: {
    marginTop: 16,
    backgroundColor: C.green,
    paddingHorizontal: 28,
    paddingVertical: 13,
    borderRadius: 25,
  },
  browseBtnText: {
    color: C.white,
    fontSize: 15,
    fontWeight: '600',
  },
});