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
  SafeAreaView,
  Animated,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';

interface Message {
  id: string;
  text: string;
  fromUser: boolean;
}

// TODO: Replace this with your real chatbot API call, e.g.:
// const res = await api.post('/chatbot/reply', { message: userText });
// return res.data.reply;
async function getBotReply(userText: string): Promise<string> {
  const text = userText.trim().toLowerCase();

  if (['hi', 'hello', 'hey', 'hayss', 'kumusta', 'musta'].includes(text)) {
    return new Promise((resolve) => {
      setTimeout(() => resolve('Hello! How can I help you?'), 1200);
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

export default function ChatScreen() {
  const router = useRouter();
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isThinking, setIsThinking] = useState(false);
  const listRef = useRef<FlatList>(null);
  const keyboardHeight = useRef(new Animated.Value(0)).current;

  // Manual keyboard tracking — resets cleanly, no stuck input.
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

  return (
    <SafeAreaView style={styles.safe}>
      {/* Simple header — back button on the left only */}
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backBtn} activeOpacity={0.8}>
          <Ionicons name="arrow-back" size={22} color="#111827" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Message</Text>
        <View style={{ width: 34 }} />
      </View>

      <Animated.View style={{ flex: 1, paddingBottom: keyboardHeight }}>
        <FlatList
          ref={listRef}
          data={messages}
          keyExtractor={(item) => item.id}
          renderItem={renderItem}
          style={{ flex: 1 }}
          contentContainerStyle={{ padding: 16, paddingBottom: 24, flexGrow: 1 }}
          onContentSizeChange={() => listRef.current?.scrollToEnd({ animated: true })}
          ListEmptyComponent={
            !isThinking ? (
              <View style={styles.emptyState}>
                <Text style={styles.emptyText}>Type a message to start chatting.</Text>
              </View>
            ) : null
          }
          ListFooterComponent={isThinking ? <TypingDots /> : null}
        />

        <View style={styles.inputRow}>
          <TextInput
            style={styles.input}
            placeholder="Type a message..."
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
    </SafeAreaView>
  );
}

const TEAL = '#14B8A6';

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: '#F7FAF9' },

  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 12,
    paddingTop: 30,
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#e5e7eb',
    backgroundColor: '#065f46',
  },
  backBtn: {
    width: 34,
    height: 34,
    borderRadius: 17,
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerTitle: { fontSize: 16, fontWeight: '800', color: '#111827' },

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