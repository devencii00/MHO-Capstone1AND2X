// context/ThemeContext.tsx
import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface ThemeContextType {
  isDark: boolean;
  toggleTheme: () => void;
  colors: {
    background: string;
    card: string;
    text: string;
    subText: string;
    border: string;
    primary: string;
    danger: string;
    success: string;
    warning: string;
    input: string;
    inputText: string;
    placeholder: string;
    tabBar: string;
    tabBarBorder: string;
    headerBg: string;
    headerText: string;
    icon: string;
    badge: string;
    badgeText: string;
  };
}

const ThemeContext = createContext<ThemeContextType>({} as ThemeContextType);

export const useTheme = () => useContext(ThemeContext);

export function ThemeProvider({ children }: { children: ReactNode }) {
  const [isDark, setIsDark] = useState(false);

  useEffect(() => {
    loadTheme();
  }, []);

  const loadTheme = async () => {
    try {
      const saved = await AsyncStorage.getItem('theme');
      setIsDark(saved === 'dark');
    } catch (e) {
      setIsDark(false);
    }
  };

  const toggleTheme = async () => {
    const newTheme = !isDark;
    setIsDark(newTheme);
    await AsyncStorage.setItem('theme', newTheme ? 'dark' : 'light');
  };

  const colors = {
    // Backgrounds
    background: isDark ? '#0F172A' : '#F9FAFB',
    card: isDark ? '#1E293B' : '#FFFFFF',
    headerBg: isDark ? '#1E293B' : '#FFFFFF',
    input: isDark ? '#334155' : '#F3F4F6',
    tabBar: isDark ? '#1E293B' : '#FFFFFF',
    tabBarBorder: isDark ? '#334155' : '#E5E7EB',
    
    // Text - Light blue in dark mode
    text: isDark ? '#BFDBFE' : '#111827',
    subText: isDark ? '#93C5FD' : '#6B7280',
    headerText: isDark ? '#BFDBFE' : '#111827',
    inputText: isDark ? '#BFDBFE' : '#111827',
    placeholder: isDark ? '#4B5563' : '#9CA3AF',
    icon: isDark ? '#93C5FD' : '#6B7280',
    
    // Borders
    border: isDark ? '#334155' : '#E5E7EB',
    
    // Brand colors
    primary: '#2563EB',
    danger: '#EF4444',
    success: '#10B981',
    warning: '#F59E0B',
    
    // Badges
    badge: isDark ? '#1E3A5F' : '#DBEAFE',
    badgeText: isDark ? '#93C5FD' : '#1E40AF',
  };

  return (
    <ThemeContext.Provider value={{ isDark, toggleTheme, colors }}>
      {children}
    </ThemeContext.Provider>
  );
}