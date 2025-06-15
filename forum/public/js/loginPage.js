import { registerUserInDB, loginUserFromDB } from '../../server/dbConfig.js';

document.getElementById('registerForm').addEventListener('submit', async (event) => {
    event.preventDefault();
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;

    try {
        const isRegistered = await registerUserInDB(email, password);
        if (isRegistered) {
            alert('Kayıt başarılı! Hoş geldiniz: ' + email);
            document.getElementById('registerForm').reset();
        } else {
            alert('Kayıt yapılamadı!');
        }
    } catch (error) {
        alert('Kayıt hatası: ' + error.message);
    }
});

document.getElementById('loginForm').addEventListener('submit', async (event) => {
    event.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    try {
        const user = await loginUserFromDB(email, password);
        if (user) {
            alert('Giriş başarılı! Hoş geldiniz: ' + email);
            document.getElementById('loginForm').reset();
            window.location.href = 'sohbet.html'; // Başarılı girişte yönlendirme
        } else {
            alert('Geçersiz email veya şifre!');
        }
    } catch (error) {
        alert('Giriş hatası: ' + error.message);
    }
});
