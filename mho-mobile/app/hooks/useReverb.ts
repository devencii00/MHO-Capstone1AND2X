// hooks/useReverb.ts
import { useEffect, useRef } from 'react';

const WS_HOST = '10.155.219.180';
const WS_PORT = 8080;
const APP_KEY = 'ykz3vcptchgggf2xol3y';

export function useReverb(
  channelName: string,
  eventName: string,
  onEvent: (data: any) => void
) {
  const onEventRef = useRef(onEvent);
  useEffect(() => { onEventRef.current = onEvent; }, [onEvent]);

  useEffect(() => {
    let ws: WebSocket;
    let pingInterval: ReturnType<typeof setInterval>;
    let active = true;

    const connect = () => {
      const socketId = Math.random().toString(36).slice(2);
      const url = `ws://${WS_HOST}:${WS_PORT}/app/${APP_KEY}?protocol=7&client=js&version=8.0.0&flash=false`;

      ws = new WebSocket(url);

      ws.onopen = () => {
        console.log(' Reverb WS connected');
        // Keep-alive ping every 30s
        pingInterval = setInterval(() => {
          if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({ event: 'pusher:ping', data: {} }));
          }
        }, 30000);
      };

      ws.onmessage = (e) => {
        try {
          const msg = JSON.parse(e.data);

          // Pusher protocol: connection established
          if (msg.event === 'pusher:connection_established') {
            // Subscribe to channel after connection
            ws.send(JSON.stringify({
              event: 'pusher:subscribe',
              data: { channel: channelName },
            }));
            return;
          }

          // Subscription confirmed
          if (msg.event === 'pusher_internal:subscription_succeeded') {
            console.log(`Subscribed to ${channelName}`);
            return;
          }

          // Match your event (Reverb prefixes custom events with a dot)
          const incomingEvent = (msg.event ?? '').replace(/^\./, '');
          const incomingChannel = msg.channel ?? '';

          if (incomingChannel === channelName && incomingEvent === eventName) {
            const parsed = typeof msg.data === 'string'
              ? JSON.parse(msg.data)
              : msg.data;
            if (active) onEventRef.current(parsed);
          }
        } catch (err) {
          console.warn('Reverb WS parse error:', err);
        }
      };

      ws.onerror = (err) => console.warn('Reverb WS error:', err);

      ws.onclose = (e) => {
        clearInterval(pingInterval);
        if (active) {
          console.log('Reverb WS closed, reconnecting in 3s...', e.code);
          setTimeout(connect, 3000);
        }
      };
    };

    connect();

    return () => {
      active = false;
      clearInterval(pingInterval);
      ws?.close();
    };
  }, [channelName, eventName]);
}

//  kini ang "live update" mechanism sa imong app — sa panahon nga naay bag-ong pasyente sa queue, na-cancel nga appointment, o na-mark as paid nga billing, dili na kinahanglan mag-poll/mag-refresh manually ang app — direkta na i-push sa server ngadto sa naka-connect nga devices.