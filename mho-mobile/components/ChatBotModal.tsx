import React, { useState, useRef, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  Keyboard,
  Platform,
  StyleSheet,
  Animated,
  Dimensions,
  PanResponder,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

const { height: SCREEN_HEIGHT } = Dimensions.get('window');

interface Message {
  id: string;
  text: string;
  fromUser: boolean;
}

interface ChatBotModalProps {
  visible: boolean;
  onClose: () => void;
  botName?: string;
}

// TODO: Replace this with your real chatbot API call,:
// const res = await api.post('/chatbot/reply', { message: userText });
// return res.data.reply;
async function getBotReply(userText: string): Promise<string> {
  const text = userText.trim().toLowerCase();

  // Simple greeting condition — expand this list / hook up your real API later.
  if (['hi', 'hello', 'hey', 'hayss', 'kumusta', 'musta'].includes(text)) {
    return new Promise((resolve) => {
      setTimeout(() => resolve('Hello! How can I help you?'), 1200);
    });
  }

  if (text.includes('what health do you recommend')) {
    return new Promise((resolve) => {
      setTimeout(() => resolve('Death Health'), 1200);
    });
  }

  if (text.includes('what services do you recommend')) {
    return new Promise((resolve) => {
      setTimeout(() => resolve('I recommend visiting a healthcare provider for a proper evaluation.'), 1200);
    });
  }

  return new Promise((resolve) => {
    setTimeout(() => resolve("Sorry, I didn't quite get that. Could you rephrase?"), 1200);
  });
}

function TypingDots() {
  const dot1 = useRef(new Animated.Value(0)).current;
  const dot2 = useRef(new Animated.Value(0)).current;
  const dot3 = useRef(new Animated.Value(0)).current;

  React.useEffect(() => {
    const animateDot = (dot: Animated.Value, delay: number) =>
      Animated.loop(
        Animated.sequence([
          Animated.delay(delay),
          Animated.timing(dot, { toValue: -4, duration: 300, useNativeDriver: true }),
          Animated.timing(dot, { toValue: 0, duration: 300, useNativeDriver: true }),
          Animated.delay(300 - delay),
        ])
      ).start();

    animateDot(dot1, 0);
    animateDot(dot2, 150);
    animateDot(dot3, 300);
  }, []);

  return (
    <View style={styles.typingBubble}>
      <Animated.View style={[styles.dot, { transform: [{ translateY: dot1 }] }]} />
      <Animated.View style={[styles.dot, { transform: [{ translateY: dot2 }] }]} />
      <Animated.View style={[styles.dot, { transform: [{ translateY: dot3 }] }]} />
    </View>
  );
}

export default function ChatBotModal({ visible, onClose, botName = 'Bobo' }: ChatBotModalProps) {
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isThinking, setIsThinking] = useState(false);
  const listRef = useRef<FlatList>(null);
  const keyboardHeight = useRef(new Animated.Value(0)).current;

  // Keeps the sheet mounted long enough to play the closing (slide-down) animation
  // before it actually unmounts.
  const [isRendered, setIsRendered] = useState(visible);
  const translateY = useRef(new Animated.Value(SCREEN_HEIGHT)).current;
  const backdropOpacity = useRef(new Animated.Value(0)).current;

  // OPEN / CLOSE slide animation, driven by the `visible` prop.
  useEffect(() => {
    if (visible) {
      setIsRendered(true);
      translateY.setValue(SCREEN_HEIGHT);
      Animated.parallel([
        Animated.timing(translateY, {
          toValue: 0,
          duration: 300,
          useNativeDriver: true,
        }),
        Animated.timing(backdropOpacity, {
          toValue: 1,
          duration: 250,
          useNativeDriver: true,
        }),
      ]).start();
    } else {
      Animated.parallel([
        Animated.timing(translateY, {
          toValue: SCREEN_HEIGHT,
          duration: 220,
          useNativeDriver: true,
        }),
        Animated.timing(backdropOpacity, {
          toValue: 0,
          duration: 220,
          useNativeDriver: true,
        }),
      ]).start(() => setIsRendered(false));
    }
  }, [visible]);

  // Manual keyboard tracking — more reliable than KeyboardAvoidingView
  // when rendered inside a custom sheet, and always resets cleanly on dismiss/cancel.
  useEffect(() => {
    const showEvent = Platform.OS === 'ios' ? 'keyboardWillShow' : 'keyboardDidShow';
    const hideEvent = Platform.OS === 'ios' ? 'keyboardWillHide' : 'keyboardDidHide';

    const showSub = Keyboard.addListener(showEvent, (e) => {
      Animated.timing(keyboardHeight, {
        toValue: e.endCoordinates.height,
        duration: Platform.OS === 'ios' ? e.duration || 250 : 200,
        useNativeDriver: false,
      }).start();
    });

    const hideSub = Keyboard.addListener(hideEvent, () => {
      Animated.timing(keyboardHeight, {
        toValue: 0,
        duration: Platform.OS === 'ios' ? 250 : 200,
        useNativeDriver: false,
      }).start();
    });

    return () => {
      showSub.remove();
      hideSub.remove();
    };
  }, []);

  useEffect(() => {
    if (!visible) {
      Keyboard.dismiss();
      keyboardHeight.setValue(0);
    }
  }, [visible]);

  // SWIPE-DOWN-TO-DISMISS — attached to the drag handle area at the top of the sheet.
  const DRAG_CLOSE_THRESHOLD = 120;
  const panResponder = useRef(
    PanResponder.create({
      onStartShouldSetPanResponder: () => true,
      onMoveShouldSetPanResponder: (_, gesture) =>
        Math.abs(gesture.dy) > 6 && Math.abs(gesture.dy) > Math.abs(gesture.dx),

      onPanResponderMove: (_, gesture) => {
        if (gesture.dy > 0) {
          translateY.setValue(gesture.dy);
        }
      },

      onPanResponderRelease: (_, gesture) => {
        if (gesture.dy > DRAG_CLOSE_THRESHOLD || gesture.vy > 0.8) {
          // Past the threshold — let go and close. The `visible` effect above
          // takes over and finishes the slide-down smoothly.
          onClose();
        } else {
          Animated.spring(translateY, {
            toValue: 0,
            useNativeDriver: true,
            friction: 8,
          }).start();
        }
      },
    })
  ).current;

  const handleSend = async () => {
    const trimmed = input.trim();
    if (!trimmed || isThinking) return;

    const userMsg: Message = { id: Date.now().toString(), text: trimmed, fromUser: true };
    setMessages((prev) => [...prev, userMsg]);
    setInput('');
    setIsThinking(true);
    setTimeout(() => listRef.current?.scrollToEnd({ animated: true }), 100);

    try {
      const replyText = await getBotReply(trimmed);
      if (replyText) {
        const botMsg: Message = { id: (Date.now() + 1).toString(), text: replyText, fromUser: false };
        setMessages((prev) => [...prev, botMsg]);
      }
    } finally {
      setIsThinking(false);
      setTimeout(() => listRef.current?.scrollToEnd({ animated: true }), 100);
    }
  };

  // "New chat" — clears the conversation and lets the user start fresh
  // without closing the modal. (kept in case you want to wire a button to it later)
  const handleNewChat = () => {
    setMessages([]);
    setInput('');
    setIsThinking(false);
  };

  const renderItem = ({ item }: { item: Message }) => {
    if (item.fromUser) {
      return (
        <View style={styles.userRow}>
          <View style={styles.userBubble}>
            <Text style={styles.userText}>{item.text}</Text>
          </View>
        </View>
      );
    }
    return (
      <View style={styles.botRow}>
        <View style={styles.botBubble}>
          <Text style={styles.botText}>{item.text}</Text>
        </View>
      </View>
    );
  };

  if (!isRendered) return null;

  return (
    <View style={styles.backdropWrap} pointerEvents="box-none">
      {/* Dimmed backdrop — tapping it closes the sheet, fades in/out with the slide. */}
      <Animated.View style={[styles.backdrop, { opacity: backdropOpacity }]}>
        <TouchableOpacity style={styles.backdropTouchable} activeOpacity={1} onPress={onClose} />
      </Animated.View>

      <Animated.View style={[styles.sheet, { transform: [{ translateY }] }]}>
        {/* Drag handle area — swipe down here to dismiss */}
        <View style={styles.dragArea} {...panResponder.panHandlers}>
          <View style={styles.dragHandle} />
        </View>

        <Animated.View style={{ flex: 1, paddingBottom: keyboardHeight }}>
          {/* MESSAGES */}
          <FlatList
            ref={listRef}
            data={messages}
            keyExtractor={(item) => item.id}
            renderItem={renderItem}
            style={{ flex: 1 }}
            contentContainerStyle={{ padding: 16, paddingTop: 8, paddingBottom: 24, flexGrow: 1 }}
            onContentSizeChange={() => listRef.current?.scrollToEnd({ animated: true })}
            ListEmptyComponent={
              !isThinking ? (
                <View style={styles.emptyState}>
                  <Text style={styles.emptyText}>Type a message to start chatting with {botName}.</Text>
                </View>
              ) : null
            }
            ListFooterComponent={isThinking ? <TypingDots /> : null}
          />

          {/* INPUT — rounded floating pill + round send button */}
          <View style={styles.inputRow}>
            <TextInput
              style={styles.input}
              placeholder={`Type a message to ${botName}...`}
              placeholderTextColor="#9ca3af"
              value={input}
              onChangeText={setInput}
              onSubmitEditing={handleSend}
              returnKeyType="send"
            />
            <TouchableOpacity
              style={[styles.sendBtn, (isThinking || !input.trim()) && { opacity: 0.5 }]}
              onPress={handleSend}
              activeOpacity={0.85}
              disabled={isThinking || !input.trim()}
            >
              <Ionicons name="send" size={18} color="#fff" />
            </TouchableOpacity>
          </View>
        </Animated.View>
      </Animated.View>
    </View>
  );
}

const TEAL = '#14B8A6';

const styles = StyleSheet.create({
  backdropWrap: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'flex-end',
    zIndex: 999,
    elevation: 999,
  },
  backdrop: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(0,0,0,0.4)',
  },
  backdropTouchable: {
    flex: 1,
  },
  sheet: {
    height: '75%',
    backgroundColor: '#F7FAF9',
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    overflow: 'hidden',
  },
  dragArea: {
    alignItems: 'center',
    paddingTop: 10,
    paddingBottom: 8,
  },
  dragHandle: {
    width: 40,
    height: 4,
    borderRadius: 2,
    backgroundColor: '#d1d5db',
  },

  botRow: { alignItems: 'flex-start', marginBottom: 10 },
  botBubble: {
    backgroundColor: '#EEF2F1',
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 18,
    borderBottomLeftRadius: 4,
    maxWidth: '82%',
  },
  botText: { color: '#111827', fontSize: 14, lineHeight: 19 },

  userRow: { alignItems: 'flex-end', marginBottom: 10 },
  userBubble: {
    backgroundColor: TEAL,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 18,
    borderBottomRightRadius: 4,
    maxWidth: '75%',
  },
  userText: { color: '#fff', fontSize: 14, lineHeight: 19 },

  typingBubble: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#EEF2F1',
    paddingHorizontal: 16,
    paddingVertical: 12,
    borderRadius: 18,
    borderBottomLeftRadius: 4,
    alignSelf: 'flex-start',
    gap: 4,
  },
  dot: { width: 6, height: 6, borderRadius: 3, backgroundColor: '#9ca3af' },

  emptyState: { flex: 1, alignItems: 'center', justifyContent: 'center', paddingTop: 60 },
  emptyText: { color: '#9ca3af', fontSize: 13, textAlign: 'center', paddingHorizontal: 40 },

  inputRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginHorizontal: 12,
    marginBottom: Platform.OS === 'ios' ? 4 : 30,
    gap: 8,
  },
  input: {
    flex: 1,
    backgroundColor: '#EFF1F0',
    borderRadius: 22,
    paddingHorizontal: 16,
    paddingVertical: 10,
    fontSize: 14,
    color: '#111827',
  },
  sendBtn: {
    width: 42,
    height: 42,
    borderRadius: 21,
    backgroundColor: TEAL,
    alignItems: 'center',
    justifyContent: 'center',
  },
});