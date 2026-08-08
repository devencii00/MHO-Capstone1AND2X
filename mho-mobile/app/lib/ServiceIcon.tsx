import React from 'react';
import { View } from 'react-native';
import { MaterialCommunityIcons } from '@expo/vector-icons';

interface ServiceIconProps {
  category?: string;
  size?: number;
}

export default function ServiceIcon({ category, size = 44 }: ServiceIconProps) {
  const cat = (category || '').toLowerCase();
  let name: React.ComponentProps<typeof MaterialCommunityIcons>['name'] = 'stethoscope';
  let bg = '#DBEAFE';
  let color = '#2563EB';
  const iconSize = size * 0.5;

  if (cat.includes('radio') || cat.includes('xray') || cat.includes('x-ray')) {
    name = 'radiobox-marked'; bg = '#EDE9FE'; color = '#7C3AED';
  } else if (cat.includes('lab') || cat.includes('blood') || cat.includes('chem') || cat.includes('urinalysis') || cat.includes('fecalysis')) {
    name = 'flask-outline'; bg = '#D1FAE5'; color = '#059669';
  } else if (cat.includes('cardio') || cat.includes('ecg') || cat.includes('heart')) {
    name = 'heart-pulse'; bg = '#FEE2E2'; color = '#DC2626';
  } else if (cat.includes('ultra') || cat.includes('sono')) {
    name = 'waveform'; bg = '#FEF3C7'; color = '#D97706';
  } else if (cat.includes('eye') || cat.includes('opth')) {
    name = 'eye-outline'; bg = '#CFFAFE'; color = '#0891B2';
  }

  return (
    <View style={{ width: size, height: size, borderRadius: size / 2, backgroundColor: bg, alignItems: 'center', justifyContent: 'center' }}>
      <MaterialCommunityIcons name={name} size={iconSize} color={color} />
    </View>
  );
}