import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function readApiToken() {
    try {
        return localStorage.getItem('api_token') || '';
    } catch (_) {
        return '';
    }
}

console.log('[Echo] Initializing with host:', import.meta.env.VITE_REVERB_HOST, 'port:', import.meta.env.VITE_REVERB_PORT);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel) => ({
        authorize: (socketId, callback) => {
            const token = readApiToken();
            const headers = {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };

            if (token) {
                headers.Authorization = 'Bearer ' + token;
            }

            window.axios
                .post('/api/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name,
                }, { headers })
                .then((response) => {
                    console.log('[Echo] Auth success for channel:', channel.name);
                    callback(null, response.data);
                })
                .catch((error) => {
                    console.error('[Echo] Auth failed for channel:', channel.name, error.response ? error.response.status : error);
                    callback(error);
                });
        },
    }),
});

// Connection status logging deferred to avoid race with Pusher init
setTimeout(function () {
    try {
        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
            var conn = window.Echo.connector.pusher.connection;
            conn.bind('connected', function () {
                console.log('[Echo] Connected to Reverb, socket ID:', window.Echo.socketId());
            });
            conn.bind('disconnected', function () {
                console.warn('[Echo] Disconnected from Reverb');
            });
            conn.bind('error', function (err) {
                console.error('[Echo] Reverb connection error:', err);
            });
        }
    } catch (_) {}
}, 100);
