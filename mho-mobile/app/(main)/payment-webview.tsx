// app/(main)/payment-webview.tsx
import React, { useState, useRef } from 'react';
import { View, StyleSheet, ActivityIndicator, Text, Alert } from 'react-native';
import { WebView } from 'react-native-webview';
import { useLocalSearchParams, router } from 'expo-router';
import api from '../lib/api'; // ⚠️ adjust this path if your lib folder lives elsewhere

export default function PaymentWebView() {
  const { url, billingId } = useLocalSearchParams();
  const [loading, setLoading] = useState(true);
  const [verifying, setVerifying] = useState(false);

  // Prevents double-handling if onShouldStartLoadWithRequest fires more than once
  const handledRef = useRef(false);

  // Validate URL before loading
  if (!url || url === 'undefined' || url === 'null') {
    return (
      <View style={styles.container}>
        <Text style={styles.errorText}>Invalid payment URL</Text>
        <Text style={styles.errorSubtext} onPress={() => router.back()}>
          Go Back
        </Text>
      </View>
    );
  }

  const goToBilling = () => {
    router.replace({
      pathname: '/(main)/billing',
      params: { refresh: 'true' },
    });
  };

  const verifyAndFinish = async (outcome: 'success' | 'failed') => {
    if (handledRef.current) return;
    handledRef.current = true;

    if (outcome === 'failed') {
      Alert.alert('Payment Cancelled', 'The payment was not completed.');
      goToBilling();
      return;
    }

    if (!billingId) {
      console.error('Missing billingId — cannot verify payment.');
      goToBilling();
      return;
    }

    setVerifying(true);
    try {
      // This hits PaymentController@verifyPaymentStatus, which checks the
      // PayMongo API directly and marks the billing row as 'paid'.
      const res = await api.get(`/patient/payment/verify/${billingId}`);

      if (res.data?.status === 'paid') {
        Alert.alert('Payment Successful', 'Your payment has been confirmed! 🎉');
      } else {
        // PayMongo can take a few seconds to settle — billing screen will
        // still show it as pending until the next refresh/poll picks it up.
        Alert.alert(
          'Payment Received',
          'We are confirming your payment. This may take a few moments.'
        );
      }
    } catch (e) {
      console.error('Verify payment error:', e);
      Alert.alert(
        'Could Not Confirm Payment',
        'Your payment may have gone through — please check your billing page shortly.'
      );
    } finally {
      setVerifying(false);
      goToBilling();
    }
  };

  // Custom schemes like "patient-queue://" are not http/https, so the
  // WebView will never "navigate" to them and onNavigationStateChange
  // won't fire with that URL. We must intercept BEFORE it tries to load.
  const handleShouldStartLoad = (request: any) => {
    const navUrl: string = request.url || '';
    console.log('Intercepted URL:', navUrl);

    if (navUrl.startsWith('patient-queue://payment-success')) {
      verifyAndFinish('success');
      return false; // block the WebView from attempting to load the scheme
    }

    if (navUrl.startsWith('patient-queue://payment-failed')) {
      verifyAndFinish('failed');
      return false;
    }

    return true; // allow normal http/https navigation (GCash pages, etc.)
  };

  const handleNavigationStateChange = (navState: any) => {
    // Kept for debugging the redirect chain (GCash → PayMongo bridge → app)
    console.log('Navigation URL:', navState.url);
  };

  return (
    <View style={styles.container}>
      {(loading || verifying) && (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#2563EB" />
          <Text style={styles.loadingText}>
            {verifying ? 'Confirming your payment…' : 'Loading payment gateway...'}
          </Text>
        </View>
      )}

      <WebView
        source={{ uri: url as string }}
        onLoadStart={() => setLoading(true)}
        onLoadEnd={() => setLoading(false)}
        onNavigationStateChange={handleNavigationStateChange}
        onShouldStartLoadWithRequest={handleShouldStartLoad}
        javaScriptEnabled={true}
        domStorageEnabled={true}
        startInLoadingState={true}
        style={styles.webview}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  loadingContainer: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    zIndex: 1,
  },
  loadingText: {
    marginTop: 12,
    fontSize: 16,
    color: '#6B7280',
  },
  webview: {
    flex: 1,
  },
  errorText: {
    fontSize: 18,
    color: '#EF4444',
    textAlign: 'center',
    marginTop: 100,
  },
  errorSubtext: {
    fontSize: 16,
    color: '#2563EB',
    textAlign: 'center',
    marginTop: 20,
    textDecorationLine: 'underline',
  },
});