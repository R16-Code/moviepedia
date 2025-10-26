<?php
include '../config/db_koneksi.php';
include '../config/functions.php';

// Pastikan pengguna sudah login
cek_login(); 

$user_id = $_SESSION['user_id'];
// Ambil ID film dan jenis aksi yang diinginkan (tambah/hapus) dari URL
$film_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Lakukan pemeriksaan dasar terhadap parameter
if ($film_id === 0 || ($action !== 'add' && $action !== 'remove')) {
    // Jika ID film tidak valid atau aksi tidak dikenali, alihkan kembali ke halaman utama
    header("Location: ../index.php");
    exit();
}

if ($action === 'add') {
    // Jika aksinya adalah 'add', masukkan film ini ke tabel watchlist
    // Kita gunakan INSERT IGNORE untuk mencegah duplikasi data jika pengguna mengklik dua kali
    $sql = "INSERT IGNORE INTO watchlist (user_id, film_id) VALUES ('$user_id', '$film_id')";
    mysqli_query($koneksi, $sql);
} 

if ($action === 'remove') {
    // Jika aksinya adalah 'remove', hapus entri film ini dari daftar tonton pengguna
    $sql = "DELETE FROM watchlist WHERE user_id = '$user_id' AND film_id = '$film_id'";
    mysqli_query($koneksi, $sql);
}

// Setelah selesai melakukan aksi, kembalikan pengguna ke halaman detail film
header("Location: ../detail_film.php?id=" . $film_id);
exit();
?>