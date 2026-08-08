import React, { useRef, useState } from 'react';
import { Animated, PanResponder, Dimensions, Image, StyleSheet } from 'react-native';
import ChatBotModal from './ChatBotModal';

const { width: SCREEN_WIDTH, height: SCREEN_HEIGHT } = Dimensions.get('window');
const BUTTON_SIZE = 60;
const MARGIN = 16;

// Starting position: bottom-right corner, above the tab bar / bottom edge.
const START_X = SCREEN_WIDTH - BUTTON_SIZE - MARGIN;
const START_Y = SCREEN_HEIGHT - BUTTON_SIZE - 140;

export default function FloatingChatButton() {
  const [showChat, setShowChat] = useState(false);

  const pan = useRef(new Animated.ValueXY({ x: START_X, y: START_Y })).current;
  const lastOffset = useRef({ x: START_X, y: START_Y });
  const isDragging = useRef(false);

  const panResponder = useRef(
    PanResponder.create({
      onStartShouldSetPanResponder: () => true,
      onMoveShouldSetPanResponder: (_, gesture) =>
        Math.abs(gesture.dx) > 3 || Math.abs(gesture.dy) > 3,

      onPanResponderGrant: () => {
        isDragging.current = false;
        pan.setOffset(lastOffset.current);
        pan.setValue({ x: 0, y: 0 });
      },

      onPanResponderMove: (_, gesture) => {
        if (Math.abs(gesture.dx) > 3 || Math.abs(gesture.dy) > 3) {
          isDragging.current = true;
        }
        Animated.event([null, { dx: pan.x, dy: pan.y }], {
          useNativeDriver: false,
        })(_, gesture);
      },

      onPanResponderRelease: (_, gesture) => {
        pan.flattenOffset();

        // Clamp vertically so it always stays on-screen.
        let newX = lastOffset.current.x + gesture.dx;
        let newY = lastOffset.current.y + gesture.dy;
        newY = Math.max(60, Math.min(SCREEN_HEIGHT - BUTTON_SIZE - MARGIN, newY));

        // SNAP TO NEAREST SIDE — wherever it's dropped, it always settles
        // on the left or right edge, never stuck in the middle.
        const buttonCenterX = newX + BUTTON_SIZE / 2;
        const isCloserToRight = buttonCenterX > SCREEN_WIDTH / 2;
        newX = isCloserToRight ? SCREEN_WIDTH - BUTTON_SIZE - MARGIN : MARGIN;

        lastOffset.current = { x: newX, y: newY };

        Animated.spring(pan, {
          toValue: { x: newX, y: newY },
          useNativeDriver: false,
          friction: 7,
          tension: 60,
        }).start();

        // If it was basically a tap (no real drag), open the chat.
        if (!isDragging.current) {
          setShowChat(true);
        }
        isDragging.current = false;
      },
    })
  ).current;

  return (
    <>
      <Animated.View
        style={[styles.container, { transform: pan.getTranslateTransform() }]}
        {...panResponder.panHandlers}
      >
        <Image
          source={require('../assets/images/Chatbot.jpg')}
          style={styles.logo}
          resizeMode="cover"
        />
      </Animated.View>

      <ChatBotModal
        visible={showChat}
        onClose={() => setShowChat(false)}
        botName="HealthBot"
      />
    </>
  );
}

const styles = StyleSheet.create({
  container: {
    position: 'absolute',
    width: BUTTON_SIZE,
    height: BUTTON_SIZE,
    borderRadius: BUTTON_SIZE / 2,
    zIndex: 999,
    elevation: 10,
    shadowColor: '#000',
    shadowOpacity: 0.3,
    shadowRadius: 8,
    shadowOffset: { width: 0, height: 4 },
    backgroundColor: '#fff',
  },
  logo: {
    width: BUTTON_SIZE,
    height: BUTTON_SIZE,
    borderRadius: BUTTON_SIZE / 2,
    borderWidth: 2,
    borderColor: '#fff',
  },
});