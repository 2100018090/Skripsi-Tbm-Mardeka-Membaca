<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Chat Anggota & Volunteer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Tombol buka chat -->
<div class="fixed bottom-0 right-20 mb-4 z-[9999]">
    <button id="open-chat">
        <img src="{{ asset('storage/img/cs.jpg') }}" class="w-20 h-20 rounded-lg shadow-lg hover:opacity-90">
    </button>
</div>

<!-- Panel chat -->
<div id="chat-container"
     class="hidden fixed bottom-28 right-4 w-80 md:w-96 shadow-xl rounded-lg bg-white z-[9998] flex flex-col h-[420px]">
    <div class="flex items-center justify-between bg-[#64C0B7] text-white px-4 py-3 rounded-t-lg">
        <h2 class="font-semibold">Chat Volunteer</h2>
        <button id="close-chat" class="text-2xl leading-none">&times;</button>
    </div>

    <div id="chatbox" class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50 text-sm"></div>

    <div class="flex border-t p-3">
        <input id="user-input" class="flex-1 border px-3 py-2 rounded-l-md focus:ring-2 focus:ring-[#64C0B7]" placeholder="Ketik pesan…" autocomplete="off">
        <button id="send-button" class="bg-[#64C0B7] text-white px-4 rounded-r-md">Kirim</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById('chat-container');
    const box = document.getElementById('chatbox');
    const input = document.getElementById('user-input');
    const send = document.getElementById('send-button');
    const openBtn = document.getElementById('open-chat');
    const closeBtn = document.getElementById('close-chat');

    const myId = {{ auth()->id() ?? 'null' }};
    const escape = t => t.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const scrollToBottom = () => box.scrollTop = box.scrollHeight;

    let lastMessageId = 0;
    let allMessages = [];

    function renderMessages(){
        box.innerHTML = '';
        if (allMessages.length === 0) {
            box.innerHTML = '<p class="text-center text-gray-500">Belum ada pesan.</p>';
            return;
        }
        allMessages.forEach(m => {
            const div = document.createElement('div');
            const isMe = m.pengirim_id === myId;
            const isBot = m.pengirim_id === null;

            div.className = isMe ? 'text-right' : 'text-left';
            div.innerHTML = `<span class="${isBot ? 'bg-green-100 text-green-700' : (isMe ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800')} inline-block px-3 py-1 rounded-lg max-w-[80%] break-words">${escape(m.isi)}</span>`;
            box.appendChild(div);
        });
        scrollToBottom();
    }

    async function loadMessages(){
        box.innerHTML = '<p class="text-center text-gray-400">Loading…</p>';
        try {
            const res = await fetch("{{ route('chat.getMessages') }}");
            const data = await res.json();
            allMessages = data.messages ?? data;
            lastMessageId = allMessages.reduce((maxId, m) => Math.max(maxId, m.id || 0), 0);
            if (!panel.classList.contains('hidden')) {
                renderMessages();
            } else {
                box.innerHTML = '';
            }
        } catch(e) {
            box.innerHTML = '<p class="text-red-500 text-center">Gagal memuat pesan.</p>';
            console.error(e);
        }
    }

    async function pollNewMessages(){
        try {
            const res = await fetch("{{ route('chat.getMessages') }}?after_id=" + lastMessageId);
            const data = await res.json();
            const newMessages = data.messages ?? data;
            if(newMessages.length){
                allMessages.push(...newMessages);
                lastMessageId = newMessages.reduce((maxId,m) => Math.max(maxId, m.id || lastMessageId), lastMessageId);
                if(!panel.classList.contains('hidden')){
                    renderMessages();
                }
            }
        } catch(e){
            console.error('Polling error:', e);
        }
    }

    async function sendMessage(){
        const text = input.value.trim();
        if (!text) return;

        allMessages.push({ isi: text, pengirim_id: myId });
        renderMessages();
        input.value = '';
        send.disabled = true;

        try {
            await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: text, penerima_id: null })
            });
        } catch(e) {
            allMessages.push({ isi: '⛔ Gagal kirim pesan.', pengirim_id: 0 });
            renderMessages();
        } finally {
            send.disabled = false;
        }
    }

    openBtn.addEventListener('click', () => {
        panel.classList.remove('hidden');
        renderMessages();
    });

    closeBtn.addEventListener('click', () => {
        panel.classList.add('hidden');
    });

    send.addEventListener('click', sendMessage);
    input.addEventListener('keyup', e => { if (e.key === 'Enter') sendMessage(); });

    loadMessages();
    setInterval(pollNewMessages, 3000);
});
</script>

</body>
</html>
