const sql = require('mssql');

// SQL Server bağlantı ayarları
const dbConfig = {
    user: 'your_db_user', // Veritabanı kullanıcı adı
    password: 'your_db_password', // Veritabanı şifresi
    server: 'your_server_address', // Sunucu adresi (ör: localhost veya IP)
    database: 'your_database_name', // Veritabanı adı
    options: {
        encrypt: true, // Şifreleme (Azure kullanıyorsanız true olmalı)
        trustServerCertificate: true // Güvenilen sunucu sertifikası
    }
};

// Kullanıcı kayıt fonksiyonu
async function registerUserInDB(email, password) {
    try {
        let pool = await sql.connect(dbConfig);
        const query = `
            INSERT INTO Users (Email, Password)
            VALUES (@Email, @Password)
        `;

        const result = await pool.request()
            .input('Email', sql.NVarChar, email)
            .input('Password', sql.NVarChar, password) // Şifreleme önerilir!
            .query(query);

        return result.rowsAffected[0] > 0; // Kayıt başarılı mı kontrol et
    } catch (err) {
        console.error('Kullanıcı kaydı sırasında hata:', err);
        throw err;
    }
}

// Kullanıcı giriş kontrol fonksiyonu
async function loginUserFromDB(email, password) {
    try {
        let pool = await sql.connect(dbConfig);
        const query = `
            SELECT * FROM Users
            WHERE Email = @Email AND Password = @Password
        `;

        const result = await pool.request()
            .input('Email', sql.NVarChar, email)
            .input('Password', sql.NVarChar, password) // Şifreleme önerilir!
            .query(query);

        return result.recordset.length > 0 ? result.recordset[0] : null;
    } catch (err) {
        console.error('Kullanıcı girişi sırasında hata:', err);
        throw err;
    }
}

module.exports = {
    registerUserInDB,
    loginUserFromDB
};
