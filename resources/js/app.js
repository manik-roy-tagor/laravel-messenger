import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

Echo.channel('chat').listen('MessageSent', (e) => { console.log(e); });


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-key',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('message-form');
    const messagesDiv = document.getElementById('messages');
    const typeSelect = document.getElementById('type');
    const contentField = document.getElementById('content-field');
    const fileField = document.getElementById('file-field');

    // টাইপ চেঞ্জ হলে ফিল্ড দেখানো/লুকানো
    typeSelect.addEventListener('change', function() {
        if (typeSelect.value === 'text') {
            contentField.style.display = 'block';
            fileField.style.display = 'none';
        } else {
            contentField.style.display = 'none';
            fileField.style.display = 'block';
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        axios.post('/messenger/send', formData)
            .then(response => {
                console.log('মেসেজ সেন্ড সফল');
                form.reset();  // ফর্ম রিসেট
                loadMessages();  // মেসেজ রিলোড
            })
            .catch(error => console.error(error));
    });

    function loadMessages() {
        const receiverId = document.getElementById('receiver_id').value;
        axios.get(`/messenger/get-messages/${receiverId}`)
            .then(response => {
                messagesDiv.innerHTML = '';  // পুরনো মেসেজ ক্লিয়ার
                response.data.forEach(msg => {
                    const div = document.createElement('div');
                    div.innerHTML = `<strong>${msg.user.name}:</strong> ${msg.content || 'ফাইল'} (${msg.type})`;
                    messagesDiv.appendChild(div);
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight;  // স্ক্রল ডাউন
            });
    }

    window.loadChat = function(userId) {
        document.getElementById('receiver_id').value = userId;
        loadMessages();  // চ্যাট লোড

        // রিয়েল-টাইম লিস্টেন
        Echo.private(`chat.${userId}`)  // চ্যানেল নাম অনুসারে
            .listen('MessageSent', (e) => {
                loadMessages();  // নতুন মেসেজ আসলে রিলোড
            });
    };
});

// "লোডিং ছাড়া" মানে যদি লোডিং ইন্ডিকেটর না চান, তাহলে axios কলে লোডিং শো করার কোড রিমুভ করুন।
