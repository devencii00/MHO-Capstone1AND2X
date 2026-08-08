import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert, StyleSheet } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { authAPI } from './../lib/api';

export default function ResetPasswordScreen() {
  const router = useRouter();
  const { token } = useLocalSearchParams();

  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');

  const handleReset = async () => {
    if (!password || !confirm) {
      Alert.alert('Error', 'Fill all fields');
      return;
    }

    if (password !== confirm) {
      Alert.alert('Error', 'Passwords do not match');
      return;
    }

    try {
      await authAPI.resetPassword(token as string, password);

      Alert.alert('Success', 'Password updated successfully');
      router.replace('/(auth)/login');
    } catch (err: any) {
      Alert.alert('Error', err.message);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Reset Password</Text>

      <TextInput
        placeholder="New Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
        style={styles.input}
      />

      <TextInput
        placeholder="Confirm Password"
        secureTextEntry
        value={confirm}
        onChangeText={setConfirm}
        style={styles.input}
      />

      <TouchableOpacity style={styles.button} onPress={handleReset}>
        <Text style={styles.buttonText}>Update Password</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, justifyContent: 'center' },
  title: { fontSize: 22, fontWeight: '600', marginBottom: 20 },
  input: {
    borderWidth: 1,
    borderColor: '#ddd',
    padding: 12,
    borderRadius: 10,
    marginBottom: 12,
  },
  button: {
    backgroundColor: '#16A34A',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
  },
  buttonText: { color: '#fff', fontWeight: '600' },
});