// app/(main)/_layout.tsx
import { TouchableOpacity, View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useRouter, usePathname, Stack } from 'expo-router';
import { useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function MainLayout() {
  const router = useRouter();
  const pathname = usePathname();
  const [cartCount, setCartCount] = useState(0);
  

  const loadCartCount = async () => {
    try {
      const cart = await AsyncStorage.getItem('booking_cart');
      const cartItems = cart ? JSON.parse(cart) : [];
      setCartCount(cartItems.length);
    } catch (error) {
      console.error('Error loading cart:', error);
    }
  };

  useEffect(() => {
    loadCartCount();
  }, [pathname]);

  const goToCart = () => {
    router.push('/(main)/cart');
  };

  const CartIcon = () => (
    <TouchableOpacity onPress={goToCart} style={styles.cartButton}>
      <Ionicons name="clipboard-outline" size={24} color="#1F2937" />
      {cartCount > 0 && (
        <View style={styles.cartBadge}>
          <Text style={styles.cartBadgeText}>{cartCount}</Text>
        </View>
      )}
    </TouchableOpacity>
  );

  return (
    <Stack>
      {/* Tab Navigation - Hidden Header */}
      <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
      
      {/* Book Appointment Screen - Hidden Header */}
      <Stack.Screen 
        name="book-appointment" 
        options={{ 
          headerShown: false,
        }} 
      />

      <Stack.Screen 
        name="appointments" 
        options={{ 
          headerShown: false,
        }} 
      />
      
      {/* Service Details Screen - Hidden Header */}
      <Stack.Screen 
        name="service-details" 
        options={{ 
          headerShown: false,
        }} 
      />
      
      {/* Booking Details Screen - Hidden Header */}
      <Stack.Screen 
        name="booking-details" 
        options={{ 
          headerShown: false,
        }} 
      />

      <Stack.Screen 
        name="billing" 
        options={{ 
          headerShown: false,
        }} 
      />

      <Stack.Screen 
        name="result" 
        options={{ 
          headerShown: false,
        }} 
      />

      <Stack.Screen 
        name="chat" 
        options={{ 
          headerShown: false,
        }} 
      />
      
      {/* Cart Screen - With Header */}
      <Stack.Screen 
        name="cart" 
        options={{ 
          title: 'Selected services',
          headerShown: false,
        }} 
      />
      
      {/* Checkout Screen - With Header
      <Stack.Screen 
        name="checkout" 
        options={{ 
          title: 'Checkout',
          // headerBackTitle: 'Back',
        }} 
      /> */}
      
      {/* Payment WebView Screen - Full Screen Modal */}
      <Stack.Screen 
        name="payment-webview" 
        options={{ 
          headerShown: false,
          presentation: 'fullScreenModal',
        }} 
      />

      {/*  FIX: These two were missing, so Expo Router fell back to its
          default header (with the auto-generated "profile/edit" title)
          for both screens inside the app/(main)/profile/ folder. */}
      <Stack.Screen 
        name="profile/edit" 
        options={{ 
          headerShown: false,
        }} 
      />

      <Stack.Screen 
        name="profile/information" 
        options={{ 
          headerShown: false,
        }} 
      />
    </Stack>
  );
}



const styles = StyleSheet.create({
  cartButton: {
    marginRight: 1,
    padding: 8,
    position: 'relative',
  },
  cartBadge: {
    position: 'absolute',
    top: 0,
    right: 0,
    backgroundColor: '#EF4444',
    borderRadius: 10,
    minWidth: 18,
    height: 18,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 4,
  },
  cartBadgeText: {
    color: '#FFFFFF',
    fontSize: 10,
    fontWeight: 'bold',
  },
});