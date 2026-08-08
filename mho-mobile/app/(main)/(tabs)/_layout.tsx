import React from 'react';
import { Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { View, StyleSheet, Image } from 'react-native';
import StaffMessageModal from '../../../components/StaffMessageModal';
import FloatingChatButton from '@/components/FloatingChatButton';
import { useProfile } from '../../context/ProfileContext';

function TabIcon({ name, focusedName, color, size, focused }: {
  name: any;
  focusedName: any;
  color: string;
  size: number;
  focused: boolean;
}) {
  return (
    <View style={styles.iconWrapper}>
      <Ionicons
        name={focused ? focusedName : name}
        size={size + 4} 
        color={focused ? '#059669' : color}
      />
    </View>
  );
}


function ProfileTabIcon({ size, focused }: {
  size: number;
  focused: boolean;
}) {
  const { profileImage } = useProfile();
  const avatarSize = size + 6;

  return (
    <View style={styles.iconWrapper}>
      {profileImage ? (
        <Image
          source={{ uri: profileImage }}
          style={[
            styles.avatar,
            {
              width: avatarSize,
              height: avatarSize,
              borderRadius: avatarSize / 2,
              borderColor: focused ? '#059669' : '#E5E7EB',
            },
          ]}
        />
      ) : (
        // Fallback kung wala pay avatar (e.g. bag-ong user, wala pa nag-upload)
        <View
          style={[
            styles.avatarFallback,
            {
              width: avatarSize,
              height: avatarSize,
              borderRadius: avatarSize / 2,
              borderColor: focused ? '#059669' : '#E5E7EB',
            },
          ]}
        >
          <Ionicons
            name="person"
            size={size - 2}
            color={focused ? '#059669' : '#9CA3AF'}
          />
        </View>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  iconWrapper: {
    width: 60,  
    height: 40, 
    alignItems: 'center',
    justifyContent: 'center',
  },
  avatar: {
    borderWidth: 2,
  },
  avatarFallback: {
    borderWidth: 2,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#F3F4F6',
  },
});

export default function TabsLayout() {
  return (
    <>
      <Tabs
        screenOptions={{
          headerShown: false,
          tabBarStyle: {
            backgroundColor: '#FFFFFF',
            borderTopWidth: 1,
            borderRadius: 15,
            borderTopColor: '#E5E7EB',
            height: 90,
            paddingBottom: 12,
            paddingTop: 12,
          },
          tabBarActiveTintColor: '#059669',
          tabBarInactiveTintColor: '#9CA3AF',
          tabBarLabelStyle: {
            fontSize: 12,
            fontWeight: '500',
            marginTop: 4,
            marginBottom: 2,
          },
        }}
      >
        <Tabs.Screen
          name="dashboard"
          options={{
            title: 'Home',
            lazy: false,
            tabBarIcon: ({ color, size, focused }) => (
              <TabIcon name="home-outline" focusedName="home" color={color} size={size} focused={focused} />
            ),
          }}
        />
        <Tabs.Screen
          name="notification"
          options={{
            title: 'Notification',
            tabBarIcon: ({ color, size, focused }) => (
              <TabIcon name="notifications-outline" focusedName="notifications" color={color} size={size} focused={focused} />
            ),
          }}
        />
        <Tabs.Screen
          name="queue"
          options={{
            title: 'Queue',
            lazy: true,
            tabBarIcon: ({ color, size, focused }) => (
              <TabIcon name="people-outline" focusedName="people" color={color} size={size} focused={focused} />
            ),
          }}
        />

        <Tabs.Screen
          name="dependents"
          options={{
            title: 'Dependents',
            lazy: false,
            tabBarIcon: ({ color, size, focused }) => (
              <TabIcon name="people-outline" focusedName="people" color={color} size={size} focused={focused} />
            ),
          }}
        />

        <Tabs.Screen
          name="profile"
          options={{
            title: 'Profile',
            lazy: false,
            tabBarIcon: ({ size, focused }) => (
              <ProfileTabIcon size={size} focused={focused} />
            ),
          }}
        />
      </Tabs>
      <StaffMessageModal />
      <FloatingChatButton />
    </>
  );
}