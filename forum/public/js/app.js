// DOM elementlerini seç
const chatContainer = document.getElementById('chatContainer');
const messageForm = document.getElementById('messageForm');
const messageInput = document.getElementById('messageInput');
const sendMessageButton = document.getElementById('sendMessageButton');

// WebSocket bağlantısı kur
const socket = new WebSocket('ws://localhost:8080');

// Bağlantı açıldığında çalışır
socket.addEventListener('open', () => {
    console.log('WebSocket bağlantısı kuruldu.');
});

// Sunucudan gelen mesajları dinle
socket.addEventListener('message', (event) => {
    const messageData = JSON.parse(event.data);
    displayMessage(messageData.username, messageData.message); // Gelen mesajları göster
});

// Mesaj gönderme işlevi
function sendMessage() {
    const messageText = messageInput.value.trim();
    if (messageText === '') return;

    // Mesaj verilerini oluştur
    const messageData = {
        username: 'Kullanıcı1', // Burada gerçek kullanıcı adını ekleyebilirsiniz
        message: messageText,
    };

    // Mesajı sunucuya gönder
    socket.send(JSON.stringify(messageData));

    // Mesajı ekrana yazdır
    displayMessage(messageData.username, messageData.message);

    // Giriş alanını temizle
    messageInput.value = '';
    messageInput.focus();
}

// Mesajı ekrana yazdırma işlevi
function displayMessage(username, messageText) {
    // Mesaj elementi oluştur
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    
    // Mesaj başlığı (kullanıcı adı)
    const usernameElement = document.createElement('strong');
    usernameElement.textContent = `${username}: `;
    messageElement.appendChild(usernameElement);

    // Mesaj içeriği
    const textElement = document.createElement('span');
    textElement.textContent = messageText;
    messageElement.appendChild(textElement);

    chatContainer.appendChild(messageElement);
    chatContainer.scrollTop = chatContainer.scrollHeight; // En son mesaja kaydır
}

// Gönder butonuna tıklandığında gönderme
sendMessageButton.addEventListener('click', (event) => {
    event.preventDefault();
    sendMessage();
});

// Enter tuşu ile gönderme
messageInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});
