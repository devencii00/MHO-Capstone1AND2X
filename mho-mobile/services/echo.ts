import Echo from 'laravel-echo';
import Pusher from 'pusher-js/react-native';
import eventEmitter from './eventEmitter';

(global as any).Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: '175407db6688418e21e6',
    cluster: 'ap1',
    forceTLS: true,
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
    // REMOVE: wsHost, wsPort, wssPort
});

// Listen to staff messages
echo.channel('patient-notifications')
    .listen('.staff-message', (data: any) => {  // Note the dot prefix
        console.log(' Staff message received:', data);
        
        eventEmitter.emit('newStaffMessage', {
            title: data.title || 'OPOL MEDLAB MEDICAL CLINIC',
            message: data.message || 'New notification',
            patientId: data.patientId,
            timestamp: data.timestamp,
        });
    });

// Connection monitoring
echo.connector.pusher.connection.bind('connected', () => {
    console.log('Pusher connected to patient-notifications');
});

echo.connector.pusher.connection.bind('error', (err: any) => {
    console.error(' Pusher error:', err);
});

export default echo;