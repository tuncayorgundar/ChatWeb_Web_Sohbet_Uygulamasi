const WebSocket = require('ws');

// Kullanıcıları saklamak için bir harita
const users = new Map();

// WebSocket sunucusu oluştur
const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', (ws) => {
    console.log('Yeni bir istemci bağlandı.');

    // Bağlantı sırasında kullanıcı adını saklayın
    ws.on('message', (message) => {
        const messageData = JSON.parse(message);

        // Kullanıcı adı kontrolü
        if (!users.has(ws)) {
            users.set(ws, messageData.username);
        }

        console.log(`Mesaj alındı: ${message}`);

        // Mesajı tüm istemcilere yayınla
        wss.clients.forEach((client) => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify(messageData));
            }
        });
    });

    ws.on('close', () => {
        console.log('Bir istemci bağlantıyı kapattı.');
        users.delete(ws); // Kullanıcıyı haritadan sil
    });
});

console.log('WebSocket sunucusu 8080 portunda çalışıyor.');
