@extends('admin.layouts.default.beranda')
@section('content')
@php
    $volunteerId = auth()->id();
@endphp

<div id="chat-app"
     class="flex h-screen overflow-hidden bg-gray-100"
     data-volunteer-id="{{ $volunteerId }}"
     data-csrf="{{ csrf_token() }}">

    <!-- Sidebar Kontak -->
    <aside class="w-1/4 bg-white border-r border-gray-200 shadow-sm">
        <header class="p-5 border-b border-gray-200 text-lg font-bold text-indigo-600">📇 Daftar Anggota</header>
        <div id="contact-list" class="overflow-y-auto h-full p-4 space-y-3"></div>
    </aside>

    <!-- Area Chat Utama -->
    <section class="flex-1 flex flex-col">
        <!-- Header Nama Anggota -->
        <header id="chat-header"
                class="bg-white p-5 border-b border-gray-200 text-xl font-semibold text-indigo-700 shadow-sm">
            Pilih anggota
        </header>

        <!-- Isi Chat -->
        <main id="chat-body" class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50"></main>

        <!-- Input Pesan -->
        <footer class="bg-white border-t border-gray-200 p-4 shadow-inner">
            <form id="chat-form" class="flex items-center gap-3">
                <input id="chat-input" type="text"
                       placeholder="Ketik pesan kamu di sini..."
                       class="flex-1 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <button id="send-btn"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold transition disabled:opacity-50"
                        disabled>Kirim</button>
            </form>
        </footer>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const app          = document.getElementById('chat-app');
    const volunteerId  = Number(app.dataset.volunteerId);
    const csrf         = app.dataset.csrf;
    const listEl       = document.getElementById('contact-list');
    const bodyEl       = document.getElementById('chat-body');
    const headerEl     = document.getElementById('chat-header');
    const inputEl      = document.getElementById('chat-input');
    const sendBtn      = document.getElementById('send-btn');
    const formEl       = document.getElementById('chat-form');

    let allMessages = [];
    let selectedId  = null;

    const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const scrollBottom = () => bodyEl.scrollTop = bodyEl.scrollHeight;

    function renderContacts() {
        const byPengirim = {};
        allMessages.forEach(m => {
            if (m.pengirim_id !== volunteerId) {
                byPengirim[m.pengirim_id] = m;
            }
        });

        listEl.innerHTML = '';
        Object.values(byPengirim).forEach(m => {
            const item = document.createElement('div');
            item.className = 'flex items-center cursor-pointer hover:bg-indigo-50 p-3 rounded-lg transition';
            item.dataset.id = m.pengirim_id;
            item.innerHTML = `
                <div class="w-12 h-12 bg-indigo-500 rounded-full mr-4 flex items-center justify-center text-white font-bold text-lg shadow">
                    ${esc(String(m.pengirim_id).slice(-1))}
                </div>
                <div class="flex-1">
                    <h2 class="font-semibold text-indigo-700">Anggota #${m.pengirim_id}</h2>
                    <p class="text-sm text-gray-500 truncate">${esc(m.isi)}</p>
                </div>`;
            item.onclick = () => openChat(m.pengirim_id);
            listEl.appendChild(item);
        });
    }

    function renderMessages() {
        const msgs = allMessages.filter(m =>
            m.pengirim_id === selectedId || m.penerima_id === selectedId
        );

        bodyEl.innerHTML = msgs.map(m => {
            const isMe = m.pengirim_id === volunteerId;
            return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                <div class="max-w-sm px-4 py-2 rounded-xl mb-2
                            ${isMe ? 'bg-indigo-500 text-white' : 'bg-white text-gray-800 border border-gray-200 shadow'}">
                    ${esc(m.isi)}
                </div>
            </div>`;
        }).join('');
        scrollBottom();
    }

    function openChat(id) {
        selectedId = id;
        headerEl.textContent = 'Anggota #' + id;
        sendBtn.disabled = false;
        renderMessages();
    }

    async function fetchMessages() {
        try {
            const r = await fetch('/chat/messages');
            if (!r.ok) throw new Error('HTTP '+r.status);
            const j = await r.json();
            allMessages = Array.isArray(j) ? j : j.messages;
            renderContacts();
            if (selectedId) renderMessages();
        } catch(e) {
            console.error('Load error', e);
        }
    }

    formEl.addEventListener('submit', async e => {
        e.preventDefault();
        const text = inputEl.value.trim();
        if (!text || !selectedId) return;
        inputEl.value = '';

        allMessages.push({
            id: Date.now(),
            pengirim_id: volunteerId,
            penerima_id: selectedId,
            isi: text
        });
        renderMessages();

        try{
            await fetch('/chat/send', {
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({message: text, penerima_id: selectedId})
            });
        }catch(err){
            console.error('Send error', err);
        }
    });

    fetchMessages();
    setInterval(fetchMessages, 4000);
});
</script>
@endsection
