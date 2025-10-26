<?php
session_start(); // Mulai sesi untuk mengaksesnya

// Hapus semua variabel sesi
$_SESSION = [];

// Hancurkan sesi
session_destroy();

// Redirect kembali ke halaman utama
header("Location: /moviepedia/index.php?pesan=logout_sukses");
exit();
?>