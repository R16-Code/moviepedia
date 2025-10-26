<?php
// admin/delete_film.php
include '../config/db_koneksi.php'; 
include '../config/functions.php';

// Proteksi Halaman Admin
cek_admin(); 

$film_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = 'hapus_gagal'; // Default status

if ($film_id > 0) {
    
    // 1. Ambil path poster_url SEBELUM menghapus data
    $sql_get_poster = "SELECT poster_url FROM film WHERE id = '$film_id'";
    $result_poster = mysqli_query($koneksi, $sql_get_poster);
    
    if (mysqli_num_rows($result_poster) === 1) {
        $data_film = mysqli_fetch_assoc($result_poster);
        $poster_path = $data_film['poster_url'];
    } else {
        $poster_path = ''; // Film tidak ditemukan
    }

    // 2. Hapus referensi dari 'watchlist' (Penting!)
    $sql_del_watchlist = "DELETE FROM watchlist WHERE film_id = '$film_id'";
    
    if (mysqli_query($koneksi, $sql_del_watchlist)) {
        
        // 3. Hapus data film dari tabel 'film'
        $sql_del_film = "DELETE FROM film WHERE id = '$film_id'";
        
        if (mysqli_query($koneksi, $sql_del_film)) {
            // 4. Jika data film berhasil dihapus, hapus file gambarnya
            if (!empty($poster_path)) {
                $file_to_delete = "../" . $poster_path; // Path relatif dari file delete.php
                
                if (file_exists($file_to_delete)) {
                    unlink($file_to_delete); // Hapus file gambar
                }
            }
            $status = 'hapus_sukses';
        } else {
            $status = 'hapus_gagal_film'; // Gagal hapus data film
        }
    } else {
        $status = 'hapus_gagal_watchlist'; // Gagal hapus referensi watchlist
    }
}

// Redirect kembali ke halaman list dengan status
header("Location: list_film.php?status=" . $status);
exit();
?>