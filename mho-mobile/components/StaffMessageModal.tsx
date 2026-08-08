import React, { useEffect, useRef, useState } from 'react';
import {View,Text,StyleSheet,Animated,Dimensions,TouchableOpacity,PanResponder,} from 'react-native';
import eventEmitter from '../services/eventEmitter';
import { Ionicons } from '@expo/vector-icons';

const { width } = Dimensions.get('window');

export default function StaffMessageModal() {
  const [visible, setVisible] = useState(false);
  const [messageData, setMessageData] = useState({ title: '', message: '' });

  const translateX = useRef(new Animated.Value(0)).current;
  const opacity = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    const handleNewMessage = (data: any) => {
      setMessageData({
        title: data.title || 'OPOL MEDLAB MEDICAL CLINIC',
        message: data.message,
      });

      setVisible(true);

      translateX.setValue(0);

      Animated.timing(opacity, {
        toValue: 1,
        duration: 200,
        useNativeDriver: true,
      }).start();

      setTimeout(() => {
        handleClose();
      }, 8000);
    };

    eventEmitter.on('newStaffMessage', handleNewMessage);

    return () => {
      eventEmitter.off('newStaffMessage', handleNewMessage);
    };
  }, []);

  const handleClose = () => {
    Animated.parallel([
      Animated.timing(translateX, {
        toValue: width,
        duration: 250,
        useNativeDriver: true,
      }),
      Animated.timing(opacity, {
        toValue: 0,
        duration: 200,
        useNativeDriver: true,
      }),
    ]).start(() => {
      setVisible(false);
      translateX.setValue(0);
    });
  };

  const panResponder = useRef(
    PanResponder.create({
      onMoveShouldSetPanResponder: (_, gestureState) => {
        return Math.abs(gestureState.dx) > 10;
      },
      onPanResponderMove: (_, gestureState) => {
        translateX.setValue(gestureState.dx);
      },
      onPanResponderRelease: (_, gestureState) => {
        if (gestureState.dx > 120) {
          handleClose(); 
        } else {
          Animated.spring(translateX, {
            toValue: 0,
            useNativeDriver: true,
          }).start();
        }
      },
    })
  ).current;

  if (!visible) return null;

  return (
    <Animated.View
      style={[
        styles.container,
        {
          opacity,
          transform: [{ translateX }],
        },
      ]}
      {...panResponder.panHandlers}
    >
      <TouchableOpacity activeOpacity={0.9} onPress={handleClose} style={styles.card}>
        <View style={styles.iconBox}>
          <Ionicons name="notifications-outline" size={30} color="#fff" />
        </View>

     
        <View style={styles.textContainer}>
          <Text style={styles.title} numberOfLines={1}>
            {messageData.title}
          </Text>
          <Text style={styles.message} numberOfLines={2}>
            {messageData.message}
          </Text>
        </View>

   
        <Text style={styles.close}>✕</Text>
      </TouchableOpacity>
    </Animated.View>
  );
}

const styles = StyleSheet.create({
  container: {
    position: 'absolute',
    top: 55,
    left: 10,
    right: 10,
    zIndex: 9999,
  },

  card: {
    width: width - 20,
    backgroundColor: '#111827',
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,   // bigger
    paddingHorizontal: 14,
    borderRadius: 18,     // smoother
    shadowColor: '#000',
    shadowOpacity: 0.3,
    shadowRadius: 10,
    elevation: 10,
  },

  iconBox: {
    width: 48,           // bigger icon box
    height: 48,
    borderRadius: 12,
    backgroundColor: '#1f2937',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },

  textContainer: {
    flex: 1,
  },

  title: {
    color: '#fff',
    fontSize: 15,
    fontWeight: 'bold',
  },

  message: {
    color: '#d1d5db',
    fontSize: 13,
    marginTop: 3,
  },

  close: {
    color: '#9ca3af',
    fontSize: 18,
    paddingHorizontal: 6,
  },
});