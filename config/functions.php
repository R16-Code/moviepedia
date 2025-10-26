<?php
session_start(); // Mulai session

// Memastikan pengguna saat ini sudah login.
// Jika belum, pengguna akan dialihkan ke halaman login.
function cek_login() {
    // Cek status sesi dan mulai jika belum dimulai (untuk pencegahan error)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Periksa apakah 'user_id' sudah tersimpan di session
    if (!isset($_SESSION['user_id'])) {
        // Alihkan pengguna ke halaman login dan berikan pesan 'wajib_login'
        header("Location: /moviepedia/auth/login.php?pesan=wajib_login");
        exit();
    }
}

// Memastikan pengguna saat ini adalah seorang Admin.
// Jika bukan Admin, pengguna akan dialihkan ke halaman utama.
function cek_admin() {
    cek_login(); // Pastikan mereka sudah login terlebih dahulu
    
    // Periksa apakah role di session BUKAN 'admin'
    if ($_SESSION['role'] !== 'admin') {
        // Alihkan pengguna ke halaman index dan berikan pesan 'bukan_admin'
        header("Location: /moviepedia/index.php?pesan=bukan_admin"); 
        exit();
    }
}

/**
 * Melakukan hashing pada password menggunakan algoritma yang kuat.
 * @param string $password Kata sandi yang ingin di-hash
 * @return string Hasil hash password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Membersihkan dan mengamankan string input dari pengguna untuk mencegah serangan XSS/SQL Injection.
 * @param mysqli $koneksi Objek koneksi database
 * @param string $data Data input dari pengguna
 * @return string Data yang sudah dibersihkan dan di-escape
 */
function sanitasi($koneksi, $data) {
    // Langkah 1: Hilangkan spasi di awal/akhir (trim)
    // Langkah 2: Konversi karakter khusus HTML (&, <, >) menjadi entitas (htmlspecialchars)
    // Langkah 3: Escape karakter khusus SQL untuk keamanan (mysqli_real_escape_string)
    return mysqli_real_escape_string($koneksi, htmlspecialchars(trim($data)));
}

/**
 * Mengubah URL YouTube standar menjadi format URL Embed yang bisa dimasukkan ke dalam Iframe.
 * @param string $url URL YouTube dari database
 * @return string URL Embed yang siap pakai atau string kosong jika URL tidak valid
 */
function get_youtube_embed_url($url) {
    if (empty($url)) {
        return '';
    }
    
    // Gunakan Regular Expression (regex) untuk menemukan ID video YouTube (11 karakter unik)
    preg_match(
        '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', 
        $url, 
        $match
    );

    // Cek apakah ID video berhasil ditemukan (ID ada di $match[1])
    if (isset($match[1])) {
        // Bangun URL embed resmi YouTube
        return 'https://www.youtube.com/embed/' . $match[1];
    }
    
    // Jika regex tidak menemukan ID yang cocok, kembalikan kosong
    return '';
}
?>