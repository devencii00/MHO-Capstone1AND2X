import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Alert,
  RefreshControl,
  Modal,
  StatusBar,
  ActivityIndicator,
  Image,
  Linking,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { useIsFocused, useNavigation } from '@react-navigation/native';
import api from '../lib/api';

//MAO NI SYA ANG OBJECT
interface Billing {
  id: number;
  appointment_id: number;
  service_name: string;
  amount: number;
  status: string;
  payment_method?: string;
  payment_reference?: string;
  invoice_number?: string;
  created_at: string;
  paid_at?: string;
}

//I used the useState Hook to manage the state of the billing data.
export default function BillingScreen() {
  const navigation = useNavigation(); // ✅ ADDED
  const [billings, setBillings] = useState<Billing[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedBilling, setSelectedBilling] = useState<Billing | null>(null);

  const [showMethodModal, setShowMethodModal] = useState(false);
  const [showQRModal, setShowQRModal] = useState(false);
  const [qrLoading, setQrLoading] = useState(false);
  const [qrCodeUrl, setQrCodeUrl] = useState<string | null>(null);
  const [checkoutUrl, setCheckoutUrl] = useState<string | null>(null);
  const [paymentType, setPaymentType] = useState<'qrph' | 'gcash'>('qrph');
  const [paymentSourceId, setPaymentSourceId] = useState<string | null>(null);
  const [isVerifying, setIsVerifying] = useState(false);

  const [selectedTab, setSelectedTab] = useState<'all' | 'pending' | 'paid'>('pending');

  const isFocused = useIsFocused();

  //  DEEP LINK HANDLER
  useEffect(() => {
    const handleDeepLink = ({ url }: { url: string }) => {
      console.log('Deep link received:', url);

      if (url.includes('payment-success')) {
        try {
          const urlObj = new URL(url);
          const billingId = urlObj.searchParams.get('billing_id');

          if (billingId) {
            verifyPaymentAfterRedirect(parseInt(billingId));
          } else {
            fetchBillings();
            Alert.alert(
              'Payment Successful! ',
              'Your payment has been processed successfully.',
              [{ text: 'OK' }]
            );
          }
        } catch {
          fetchBillings();
          Alert.alert('Payment Successful! ', 'Your payment has been processed.');
        }
      } else if (url.includes('payment-cancelled') || url.includes('payment-failed')) {
        Alert.alert(
          'Payment Cancelled',
          'Your payment was not completed. Please try again.',
          [{ text: 'OK' }]
        );
      }
    };

    const subscription = Linking.addEventListener('url', handleDeepLink);

    Linking.getInitialURL().then(url => {
      if (url) {
        handleDeepLink({ url });
      }
    });

    return () => {
      subscription?.remove();
    };
  }, []);

  //  VERIFY PAYMENT AFTER REDIRECT
  const verifyPaymentAfterRedirect = async (billingId: number) => {
    try {
      setIsVerifying(true);
      const response = await api.get(`/patient/billings/${billingId}/verify`);

      if (response.data.success && response.data.status === 'paid') {
        await fetchBillings();
        Alert.alert(
          'Payment Confirmed! ',
          'Your payment has been verified successfully.',
          [{ text: 'OK' }]
        );
      } else {
        setTimeout(async () => {
          try {
            const retryResponse = await api.get(`/patient/billings/${billingId}/verify`);
            if (retryResponse.data.success && retryResponse.data.status === 'paid') {
              await fetchBillings();
              Alert.alert('Payment Confirmed! ', 'Your payment has been verified.');
            } else {
              await fetchBillings();
              Alert.alert(
                'Processing...',
                'Your payment is still being processed. It will update automatically.',
                [{ text: 'OK' }]
              );
            }
          } catch {
            Alert.alert('Notice', 'Payment processing. Please wait a moment.');
          }
        }, 3000);
      }
    } catch (error) {
      console.error('Verification error:', error);
      Alert.alert('Notice', 'Unable to verify payment. It will update automatically.');
      await fetchBillings();
    } finally {
      setIsVerifying(false);
    }
  };

  //I used useCallback to optimize the fetch function and prevent unnecessary re-creation.
  const fetchBillings = useCallback(async () => {
    try {
      if (!refreshing && billings.length === 0) setLoading(true);
      const response = await api.get('/patient/billings');
      let data = [];
      if (response.data.success && Array.isArray(response.data.data)) data = response.data.data;
      else if (response.data.success && response.data.data?.data) data = response.data.data.data;
      else if (Array.isArray(response.data)) data = response.data;
      else if (response.data.data && Array.isArray(response.data.data)) data = response.data.data;

     //I used the map() function to display all billing records dynamically."
      const mappedBillings = data.map((item: any) => {
        let rawStatus = (item.status || 'pending').toLowerCase().trim();
        if (rawStatus === 'completed') rawStatus = 'paid';
        return {
          id: item.id,
          appointment_id: item.appointment_id,
          service_name: item.notes || item.service_name || item.description || 'Consultation',
          amount: parseFloat(item.total_amount || item.amount || 0),
          status: rawStatus,
          payment_method: item.payment_method || null,
          payment_reference: item.payment_reference || null,
          invoice_number: item.invoice_number || null,
          created_at: item.created_at,
          paid_at: item.paid_at || null,
        };
      });
      setBillings(mappedBillings);
    } catch (error: any) {
      console.error('Failed to fetch billings:', error?.message || error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, [refreshing, billings.length]);

  useEffect(() => { if (isFocused) fetchBillings(); }, [isFocused, fetchBillings]);

//I used the useEffect Hook to execute code when the component loads
  useEffect(() => {
    let interval: any;
    if (isFocused && billings.some(b => b.status === 'pending')) {
      interval = setInterval(fetchBillings, 10000);
    }
    return () => { if (interval) clearInterval(interval); };
  }, [isFocused, fetchBillings, billings]);

  const onRefresh = () => { setRefreshing(true); fetchBillings(); };

  const handlePayment = (billing: Billing) => {
    setSelectedBilling(billing);
    setShowMethodModal(true);
  };

  const handleSelectPayment = async (type: 'qrph' | 'gcash') => {
    if (!selectedBilling) return;
    setShowMethodModal(false);
    setShowQRModal(true); // ablihi ang "QR/checkout" modal
    setPaymentType(type);
    setQrLoading(true);
    setQrCodeUrl(null);
    setCheckoutUrl(null);  // i-reset ang daan nga checkout URL (kung naa)
    setPaymentSourceId(null); // i-reset ang daan nga source/intent ID
    
//Trigger — pag-select sa "Scan QR Ph"Sa handleSelectPayment('qrph'), mag-post ka sa backend:
    try {
      const response = await api.post('/patient/payment/create-source', {
        billing_id: selectedBilling.id,
        amount: selectedBilling.amount * 100,
        type,
        description: selectedBilling.service_name,
      });

      if (response.data.success) {
        const qrData = response.data.data || response.data;

        if (type === 'qrph') {
          if (qrData.qr_code_url) {
            setQrCodeUrl(qrData.qr_code_url);
          } else {
            Alert.alert('Error', 'QR code was not generated. Please try again.');
          }
        } else {
          if (qrData.checkout_url) {
            setCheckoutUrl(qrData.checkout_url);
          } else {
            Alert.alert('Error', 'Payment link was not generated. Please try again.');
          }
        }

        if (qrData.source_id) setPaymentSourceId(qrData.source_id);
        else if (qrData.id) setPaymentSourceId(qrData.id);

      } else {
        Alert.alert('Error', response.data.message || 'Failed to create payment');
        setShowQRModal(false);
      }
    } catch (error: any) {
      console.log('STATUS:', error?.response?.status);
      console.log('DATA:', JSON.stringify(error?.response?.data));
      console.log('URL:', error?.config?.baseURL, error?.config?.url);
      console.log('MESSAGE:', error?.message);
      Alert.alert('Payment Error', error?.response?.data?.message || error?.message || 'Failed');
      setShowQRModal(false);
    } finally {
      setQrLoading(false);
    }
  };

  const handleOpenCheckout = async () => {
    if (!checkoutUrl) {
      Alert.alert('Error', 'No payment link available');
      return;
    }

    const gcashDeepLink = `gcash://pay?url=${encodeURIComponent(checkoutUrl)}`;
    const canOpenGCash = await Linking.canOpenURL(gcashDeepLink);
    if (canOpenGCash) {
      await Linking.openURL(gcashDeepLink);
      return;
    }

    const supported = await Linking.canOpenURL(checkoutUrl);
    if (supported) {
      await Linking.openURL(checkoutUrl);
    } else {
      Alert.alert('Error', 'Cannot open payment link. Please try again.');
    }
  };

  const handleVerifyPayment = async () => {
    if (!paymentSourceId || !selectedBilling) return;
    try {
      const response = await api.get(`/patient/billings/${selectedBilling.id}/verify`);
      if (response.data.success && response.data.status === 'paid') {
        Alert.alert('Payment Successful! 🎉', 'Your payment has been confirmed.', [
          { text: 'OK', onPress: () => { setShowQRModal(false); fetchBillings(); } }
        ]);
      } else {
        Alert.alert(
          'Payment Pending',
          'Your payment is still being processed. Would you like to check again?',
          [
            { text: 'Cancel', style: 'cancel' },
            { text: 'Check Again', onPress: handleVerifyPayment }
          ]
        );
      }
    } catch (error) {
      Alert.alert('Verification Failed', 'Could not verify payment status.');
    }
  };

  const formatDate = (dateString: string): string => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  };

  const getPaymentLabel = (method?: string): string => {
    if (!method) return 'Paid';
    switch (method.toLowerCase()) {
      case 'gcash': return 'GCash';
      case 'qrph': return 'QRPh';
      default: return method.toUpperCase();
    }
  };

  const getFilteredBillings = (): Billing[] => {
    if (selectedTab === 'all') return billings;
    return billings.filter((b) => b.status === selectedTab);
  };

  const getPendingCount = () => billings.filter((b) => b.status === 'pending').length;
  const getPaidCount = () => billings.filter((b) => b.status === 'paid').length;

  const filteredBillings = getFilteredBillings();
  const pendingCount = getPendingCount();
  const paidCount = getPaidCount();

  if (loading && billings.length === 0) {
    return (
      <SafeAreaView className="items-center justify-center flex-1 bg-[#F0FDF4]">
        <StatusBar barStyle="dark-content" backgroundColor="#F0FDF4" />
        <View className="items-center justify-center w-20 h-20 mb-6 rounded-full bg-emerald-100">
          <Ionicons name="receipt-outline" size={40} color="#059669" />
        </View>
        <ActivityIndicator size="large" color="#059669" />
        <Text className="mt-4 text-base font-semibold text-emerald-600">Loading billings...</Text>
        <Text className="mt-2 text-sm text-gray-500">Please wait a moment</Text>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView className="flex-1 bg-[#F0FDF4]">
      <StatusBar barStyle="dark-content" backgroundColor="#059669" />

      {/* ✅ HEADER WITH BACK BUTTON AND CENTERED TITLE */}
      <View style={{
        backgroundColor: '#059669',
        paddingHorizontal: 16,
        paddingVertical: 14,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        position: 'relative',
      }}>
        {/* Back Button */}
        <TouchableOpacity
          onPress={() => navigation.goBack()}
          style={{
            position: 'absolute',
            left: 16,
            zIndex: 10,
          }}
        >
          <Ionicons name="arrow-back" size={24} color="#FFFFFF" />
        </TouchableOpacity>

        {/* Title */}
        <Text style={{
          fontSize: 18,
          fontWeight: '700',
          color: '#FFFFFF',
          letterSpacing: 0.5,
        }}>
          Billing
        </Text>
      </View>

      {/* VERIFICATION LOADING OVERLAY */}
      {isVerifying && (
        <View style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'center', alignItems: 'center', zIndex: 1000 }}>
          <View style={{ backgroundColor: 'white', padding: 30, borderRadius: 20, alignItems: 'center' }}>
            <ActivityIndicator size="large" color="#059669" />
            <Text style={{ marginTop: 16, fontSize: 16, fontWeight: '600', color: '#059669' }}>Verifying Payment...</Text>
          </View>
        </View>
      )}

      {pendingCount > 0 && (
        <LinearGradient colors={['#059669', '#047857']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0 }} className="flex-row items-center gap-3 px-5 py-4">
          <Ionicons name="alert-circle" size={22} color="#FFFFFF" />
          <Text className="flex-1 text-sm font-semibold leading-5 text-white">
            {pendingCount} pending payment{pendingCount > 1 ? 's' : ''} awaiting your attention
          </Text>
        </LinearGradient>
      )}

      {/* Tabs */}
      <View className="flex-row bg-white border-b shadow-sm border-emerald-100">
        {(['all', 'pending', 'paid'] as const).map((tab) => (
          <TouchableOpacity
            key={`tab-${tab}`}
            className={`flex-1 py-4 items-center justify-center flex-row gap-2 border-b-[3px] ${selectedTab === tab ? 'border-emerald-600' : 'border-transparent'}`}
            onPress={() => setSelectedTab(tab)}
          >
            <Text className={`text-sm font-semibold ${selectedTab === tab ? 'text-emerald-700' : 'text-gray-500'}`}>
              {tab === 'all' ? 'All Billings' : tab === 'pending' ? 'Pending' : 'Paid'}
            </Text>
            {tab === 'pending' && pendingCount > 0 && (
              <View className="bg-red-500 rounded-full min-w-[24px] h-[24px] justify-center items-center px-1">
                <Text className="text-white text-[11px] font-bold">{pendingCount}</Text>
              </View>
            )}
            {tab === 'paid' && paidCount > 0 && (
              <View className="bg-emerald-500 rounded-full min-w-[24px] h-[24px] justify-center items-center px-1">
                <Text className="text-white text-[11px] font-bold">{paidCount}</Text>
              </View>
            )}
          </TouchableOpacity>
        ))}
      </View>

      {/* Billing List */}
      <ScrollView
        className="flex-1"
        showsVerticalScrollIndicator={false}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={['#059669']} tintColor="#059669" />}
      >
        {filteredBillings.length === 0 ? (
          <View className="items-center px-6 py-24">
            <View className="items-center justify-center w-16 h-16 mb-4 rounded-full bg-emerald-100">
              <Ionicons name="receipt-outline" size={32} color="#059669" />
            </View>
            <Text className="mt-2 text-lg font-semibold text-gray-800">No transactions yet</Text>
            <Text className="mt-2 text-sm text-center text-gray-500">
              Your {selectedTab === 'all' ? 'billing' : selectedTab} transactions will appear here
            </Text>
          </View>
        ) : (
          filteredBillings.map((billing, index) => (
            <View key={`bill-${billing.id || index}-${billing.status}-${index}`} className="px-4 py-3">
              {billing.status === 'pending' ? (
                /*  PLAIN WHITE PENDING CARD - SAME STYLE AS PAID  */
                <View className="overflow-hidden bg-white border rounded-2xl border-emerald-100" style={{ elevation: 3, shadowColor: '#000', shadowOpacity: 0.06, shadowRadius: 8, shadowOffset: { width: 0, height: 3 } }}>
                  <View className="p-5">
                    <View className="flex-row items-start justify-between mb-2">
                      <Text className="flex-1 text-4xl font-extrabold text-gray-900">₱{billing.amount.toFixed(2)}</Text>
                      <View className="bg-orange-400 rounded-full px-3 py-1.5 ml-2">
                        <Text className="text-xs font-bold text-white">PENDING</Text>
                      </View>
                    </View>
                    <Text className="mb-4 text-base text-gray-500" numberOfLines={1}>{billing.service_name}</Text>
                    <View className="flex-row items-center gap-1.5 mb-2">
                      <Ionicons name="calendar-outline" size={14} color="#6B7280" />
                      <Text className="text-xs font-medium text-gray-600">{formatDate(billing.created_at)}</Text>
                    </View>
                    {billing.invoice_number && (
                      <View className="flex-row items-center gap-1.5 mb-4">
                        <Ionicons name="document-text-outline" size={14} color="#9CA3AF" />
                        <Text className="text-xs font-medium text-gray-400">{billing.invoice_number}</Text>
                      </View>
                    )}
                    <View className="flex-row justify-end">
                      <TouchableOpacity onPress={() => handlePayment(billing)} activeOpacity={0.85} className="flex-row items-center justify-center gap-2 px-8 py-3 rounded-3xl" style={{ backgroundColor: '#059669', elevation: 2 }}>
                        <Ionicons name="qr-code-outline" size={18} color="#FFFFFF" />
                        <Text className="text-sm font-bold text-white">Pay Now</Text>
                      </TouchableOpacity>
                    </View>
                  </View>
                </View>
                /*  END NEW WHITE PENDING CARD  */
              ) : (
                <View className="overflow-hidden bg-white border rounded-2xl border-emerald-100" style={{ elevation: 3, shadowColor: '#000', shadowOpacity: 0.06, shadowRadius: 8, shadowOffset: { width: 0, height: 3 } }}>
                  <View className="p-5">
                    <View className="flex-row items-start justify-between mb-2">
                      <Text className="flex-1 text-4xl font-extrabold text-gray-900">₱{billing.amount.toFixed(2)}</Text>
                      <View className="bg-emerald-100 rounded-full px-3 py-1.5 ml-2 border border-emerald-200">
                        <View className="flex-row items-center gap-1">
                          <Ionicons name="checkmark-circle" size={14} color="#059669" />
                          <Text className="text-xs font-bold text-emerald-700">PAID</Text>
                        </View>
                      </View>
                    </View>
                    <Text className="mb-4 text-base text-gray-500" numberOfLines={1}>{billing.service_name}</Text>
                    <View className="flex-row items-center gap-1.5 mb-2">
                      <Ionicons name="calendar-outline" size={14} color="#6B7280" />
                      <Text className="text-xs font-medium text-gray-600">{formatDate(billing.created_at)}</Text>
                    </View>
                    {billing.invoice_number && (
                      <View className="flex-row items-center gap-1.5 mb-3">
                        <Ionicons name="document-text-outline" size={14} color="#9CA3AF" />
                        <Text className="text-xs font-medium text-gray-400">{billing.invoice_number}</Text>
                      </View>
                    )}
                    <View className="flex-row justify-end">
                      {billing.payment_method && (
                        <View className="flex-row items-center gap-1.5 bg-gray-50 rounded-full px-3 py-1.5 border border-gray-200">
                          <Ionicons name="cash-outline" size={14} color="#6B7280" />
                          <Text className="text-xs font-semibold text-gray-600">{getPaymentLabel(billing.payment_method)}</Text>
                        </View>
                      )}
                    </View>
                  </View>
                </View>
              )}
            </View>
          ))
        )}
        <View style={{ height: 40 }} />
      </ScrollView>

      {/* ── MODAL 1: Choose Payment Method ── */}
      <Modal visible={showMethodModal} animationType="slide" transparent onRequestClose={() => setShowMethodModal(false)}>
        <View style={{ flex: 1, justifyContent: 'flex-end', backgroundColor: 'rgba(0,0,0,0.4)' }}>
          <View style={{ backgroundColor: 'white', borderTopLeftRadius: 28, borderTopRightRadius: 28, paddingBottom: 36, maxHeight: '85%' }}>
            <LinearGradient colors={['#059669', '#047857']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0 }} style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 24, paddingVertical: 20, borderTopLeftRadius: 28, borderTopRightRadius: 28 }}>
              <View>
                <Text style={{ fontSize: 18, fontWeight: '700', color: 'white' }}>Select Payment Method</Text>
                <Text style={{ fontSize: 13, color: '#D1FAE5', marginTop: 4 }}>Choose how to complete your payment</Text>
              </View>
              <TouchableOpacity onPress={() => setShowMethodModal(false)}>
                <Ionicons name="close" size={26} color="#FFF" />
              </TouchableOpacity>
            </LinearGradient>

            {selectedBilling && (
              <View style={{ alignItems: 'center', paddingVertical: 24, marginHorizontal: 20, marginTop: 24, backgroundColor: '#F0FDF4', borderRadius: 20, borderWidth: 1.5, borderColor: '#D1FAE5' }}>
                <Text style={{ fontSize: 13, color: '#6B7280', marginBottom: 8, fontWeight: '500' }}>{selectedBilling.service_name}</Text>
                <Text style={{ fontSize: 38, fontWeight: '800', color: '#059669' }}>₱{selectedBilling.amount.toFixed(2)}</Text>
              </View>
            )}

            <View style={{ paddingHorizontal: 20, gap: 14, marginTop: 20 }}>
              <TouchableOpacity onPress={() => handleSelectPayment('qrph')} style={{ flexDirection: 'row', alignItems: 'center', padding: 18, borderRadius: 18, borderWidth: 2, borderColor: '#059669', backgroundColor: '#F0FDF4', gap: 16 }}>
                <View style={{ width: 56, height: 56, borderRadius: 14, backgroundColor: '#059669', alignItems: 'center', justifyContent: 'center' }}>
                  <Ionicons name="qr-code" size={30} color="white" />
                </View>
                <View style={{ flex: 1 }}>
                  <Text style={{ fontSize: 17, fontWeight: '700', color: '#065F46', marginBottom: 2 }}>Scan QR Ph</Text>
                  <Text style={{ fontSize: 13, color: '#6B7280' }}>Scan with GCash, Maya, or any bank app</Text>
                </View>
                <View style={{ width: 28, height: 28, borderRadius: 8, backgroundColor: '#DBEAFE', alignItems: 'center', justifyContent: 'center' }}>
                  <Ionicons name="chevron-forward" size={18} color="#059669" />
                </View>
              </TouchableOpacity>

              <TouchableOpacity onPress={() => handleSelectPayment('gcash')} style={{ flexDirection: 'row', alignItems: 'center', padding: 18, borderRadius: 18, borderWidth: 2, borderColor: '#2563EB', backgroundColor: '#EFF6FF', gap: 16 }}>
                <View style={{ width: 56, height: 56, borderRadius: 14, backgroundColor: '#2563EB', alignItems: 'center', justifyContent: 'center' }}>
                  <Ionicons name="phone-portrait" size={30} color="white" />
                </View>
                <View style={{ flex: 1 }}>
                  <Text style={{ fontSize: 17, fontWeight: '700', color: '#1E3A8A', marginBottom: 2 }}>Pay via GCash</Text>
                  <Text style={{ fontSize: 13, color: '#6B7280' }}>Opens the GCash app or website</Text>
                </View>
                <View style={{ width: 28, height: 28, borderRadius: 8, backgroundColor: '#DBEAFE', alignItems: 'center', justifyContent: 'center' }}>
                  <Ionicons name="chevron-forward" size={18} color="#2563EB" />
                </View>
              </TouchableOpacity>
            </View>

            <TouchableOpacity style={{ alignItems: 'center', paddingTop: 24 }} onPress={() => setShowMethodModal(false)}>
              <Text style={{ color: '#9CA3AF', fontSize: 16, fontWeight: '600' }}>Close</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* ── MODAL 2: Scan QR Ph to Pay Pag-display sa QR image Sa showQRModal, gigamit ang stored value sa <Image> component ── */}
      <Modal visible={showQRModal} animationType="slide" transparent onRequestClose={() => setShowQRModal(false)}>
        <View style={{ flex: 1, justifyContent: 'flex-end', backgroundColor: 'rgba(0,0,0,0.4)' }}>
          <View style={{ backgroundColor: 'white', borderTopLeftRadius: 28, borderTopRightRadius: 28, maxHeight: '90%' }}>
            <LinearGradient colors={['#059669', '#047857']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0 }} style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 24, paddingVertical: 20, borderTopLeftRadius: 28, borderTopRightRadius: 28 }}>
              <View>
                <Text style={{ fontSize: 18, fontWeight: '700', color: 'white' }}>
                  {paymentType === 'qrph' ? 'Scan QR to Pay' : 'Pay via GCash'}
                </Text>
                <Text style={{ fontSize: 13, color: '#D1FAE5', marginTop: 4 }}>
                  {paymentType === 'qrph' ? 'GCash / Maya / Bank Apps' : 'GCash App or Website'}
                </Text>
              </View>
              <TouchableOpacity onPress={() => setShowQRModal(false)}>
                <Ionicons name="close" size={26} color="#FFF" />
              </TouchableOpacity>
            </LinearGradient>

            <ScrollView showsVerticalScrollIndicator={true} style={{ maxHeight: 500 }}>
              {selectedBilling && (
                <View style={{ alignItems: 'center', paddingVertical: 18, marginHorizontal: 20, marginTop: 24, backgroundColor: '#F0FDF4', borderRadius: 20, borderWidth: 1.5, borderColor: '#D1FAE5' }}>
                  <Text style={{ fontSize: 12, color: '#6B7280', marginBottom: 6, fontWeight: '500' }}>{selectedBilling.service_name}</Text>
                  <Text style={{ fontSize: 32, fontWeight: '800', color: '#059669' }}>₱{selectedBilling.amount.toFixed(2)}</Text>
                </View>
              )}
                
              <View style={{ alignItems: 'center', paddingVertical: 24, marginHorizontal: 20 }}>
                {qrLoading ? (
                  <View style={{ alignItems: 'center', paddingVertical: 48 }}>
                    <ActivityIndicator size="large" color="#059669" />
                    <Text style={{ marginTop: 16, color: '#6B7280', fontSize: 15, fontWeight: '500' }}>
                      {paymentType === 'qrph' ? 'Generating QRPh Code...' : 'Preparing GCash Payment...'}
                    </Text>
                  </View>
                ) : paymentType === 'qrph' && qrCodeUrl ? (
                  <>
                  
                    <View style={{ padding: 20, backgroundColor: 'white', borderRadius: 20, borderWidth: 2, borderColor: '#E5E7EB', shadowColor: '#000', shadowOpacity: 0.1, shadowRadius: 12, elevation: 6 }}>
                    
                      <Image source={{ uri: qrCodeUrl }} style={{ width: 240, height: 240 }} resizeMode="contain" />
                    </View>
                    <Text style={{ marginTop: 18, fontSize: 14, color: '#6B7280', textAlign: 'center', fontWeight: '500' }}>
                      Scan with GCash, Maya, or any QR Ph-enabled app
                    </Text>
                    <Text style={{ marginTop: 8, fontSize: 12, color: '#9CA3AF', textAlign: 'center' }}>
                      Amount: ₱{selectedBilling?.amount.toFixed(2)}
                    </Text>
                    <Text style={{ marginTop: 4, fontSize: 11, color: '#F59E0B', textAlign: 'center' }}>
                      This code expires after a few minutes
                    </Text>
                  </>
                ) : paymentType === 'gcash' && checkoutUrl ? (
                  <View style={{ alignItems: 'center' }}>
                    <View style={{ width: 96, height: 96, borderRadius: 48, backgroundColor: '#DBEAFE', alignItems: 'center', justifyContent: 'center', marginBottom: 16 }}>
                      <Ionicons name="phone-portrait" size={48} color="#2563EB" />
                    </View>
                    <Text style={{ fontSize: 15, color: '#374151', textAlign: 'center', fontWeight: '600' }}>
                      Ready to pay with GCash
                    </Text>
                    <Text style={{ marginTop: 8, fontSize: 12, color: '#9CA3AF', textAlign: 'center' }}>
                      Amount: ₱{selectedBilling?.amount.toFixed(2)}
                    </Text>
                    <Text style={{ marginTop: 8, fontSize: 13, color: '#6B7280', textAlign: 'center' }}>
                      Tap "Proceed to Payment" below to open GCash
                    </Text>
                  </View>
                ) : (
                  <View style={{ alignItems: 'center', paddingVertical: 48 }}>
                    <View style={{ width: 64, height: 64, borderRadius: 32, backgroundColor: '#FEE2E2', alignItems: 'center', justifyContent: 'center', marginBottom: 12 }}>
                      <Ionicons name="alert-circle-outline" size={36} color="#EF4444" />
                    </View>
                    <Text style={{ color: '#6B7280', fontSize: 15, fontWeight: '600' }}>Failed to generate payment</Text>
                    <Text style={{ color: '#9CA3AF', fontSize: 13, marginTop: 6 }}>Please try again</Text>
                  </View>
                )}
              </View>

              {!qrLoading && (qrCodeUrl || checkoutUrl) && (
                <View style={{ paddingHorizontal: 20, paddingBottom: 36, gap: 12 }}>

                  {paymentType === 'gcash' && checkoutUrl && (
                    <TouchableOpacity onPress={handleOpenCheckout} style={{ borderRadius: 14, overflow: 'hidden' }}>
                      <LinearGradient colors={['#2563EB', '#1D4ED8']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0 }} style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'center', paddingVertical: 16, borderRadius: 14, gap: 10 }}>
                        <Ionicons name="card-outline" size={20} color="white" />
                        <Text style={{ color: 'white', fontWeight: '700', fontSize: 16 }}>Proceed to Payment</Text>
                      </LinearGradient>
                    </TouchableOpacity>
                  )}

                  <TouchableOpacity
                    onPress={handleVerifyPayment}
                    style={paymentType === 'gcash'
                      ? { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', paddingVertical: 14, borderRadius: 14, borderWidth: 2, borderColor: '#34D399', gap: 8, backgroundColor: '#ECFDF5' }
                      : { borderRadius: 14, overflow: 'hidden' }
                    }
                  >
                    {paymentType === 'gcash' ? (
                      <>
                        <Ionicons name="checkmark-circle" size={20} color="#059669" />
                        <Text style={{ color: '#059669', fontWeight: '700', fontSize: 16 }}>I've Completed Payment</Text>
                      </>
                    ) : (
                      <LinearGradient colors={['#059669', '#047857']} start={{ x: 0, y: 0 }} end={{ x: 1, y: 0 }} style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'center', paddingVertical: 16, borderRadius: 14, gap: 10 }}>
                        <Ionicons name="checkmark-circle" size={20} color="white" />
                        <Text style={{ color: 'white', fontWeight: '700', fontSize: 16 }}>I've Completed Payment</Text>
                      </LinearGradient>
                    )}
                  </TouchableOpacity>

                  <TouchableOpacity style={{ alignItems: 'center', paddingTop: 8 }} onPress={() => setShowQRModal(false)}>
                    <Text style={{ color: '#9CA3AF', fontSize: 16, fontWeight: '600' }}>Close</Text>
                  </TouchableOpacity>
                </View>
              )}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}