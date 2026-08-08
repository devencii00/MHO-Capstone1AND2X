import React, { useState } from 'react';
import {View,TextInput,Text,StyleSheet,TouchableOpacity,TextInputProps,} from 'react-native';
import { colors } from '../constants/colors';

interface InputProps extends TextInputProps {
  label?: string;
  error?: string;
  icon?: React.ReactNode;
}

export const Input: React.FC<InputProps> = ({
  label,
  error,
  icon,
  secureTextEntry,
  style,
  ...props
}) => {
  const [isSecure, setIsSecure] = useState<boolean>(!!secureTextEntry);

  return (
    <View style={styles.container}>
      {label && <Text style={styles.label}>{label}</Text>}

      <View style={[styles.inputContainer, error && styles.inputError]}>
        {icon && <View style={styles.iconLeft}>{icon}</View>}

        <TextInput
          style={[
            styles.input,
            icon ? styles.inputWithIcon : undefined,
            style,
          ].filter(Boolean)}
          secureTextEntry={isSecure}
          placeholderTextColor={colors.gray}
          {...props}
        />

        {secureTextEntry && (
          <TouchableOpacity
            onPress={() => setIsSecure(!isSecure)}
            style={styles.eyeButton}
          >
            <Text>{isSecure ? '👁️' : '👁️‍🗨️'}</Text>
          </TouchableOpacity>
        )}
      </View>

      {error && <Text style={styles.errorText}>{error}</Text>}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    marginBottom: 16,
  },
  label: {
    fontSize: 14,
    fontWeight: '500',
    color: colors.dark,
    marginBottom: 6,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 10,
    backgroundColor: colors.white,
  },
  input: {
    flex: 1,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 16,
    color: colors.dark,
  },
  inputWithIcon: {
    paddingLeft: 40,
  },
  iconLeft: {
    position: 'absolute',
    left: 12,
    zIndex: 1,
  },
  eyeButton: {
    paddingHorizontal: 12,
  },
  inputError: {
    borderColor: colors.danger,
  },
  errorText: {
    fontSize: 12,
    color: colors.danger,
    marginTop: 4,
  },
});