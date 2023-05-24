import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

let addedCount = 0
const info = document.getElementById('info')

const newRecordAdded = () => {
    if(!info) return

    if(info.classList.contains('d-none')) {
        info.classList.remove('d-none')
    }

    const msg = window.lang.addedMsg
    info.textContent = msg.replace('#', addedCount.toString())
}

echo.private('Record.Added.To.' + window.subscriptionId)
    .listen('.record.added', (e) => {
        addedCount++
        newRecordAdded()
    })
    .error(console.warn);

window.onbeforeunload = function() {
    echo.disconnect();
}
