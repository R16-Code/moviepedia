<?php
// admin/dashboard.php
include '../config/db_koneksi.php';
include '../config/functions.php';

// Proteksi Halaman Admin
cek_admin(); 

// Ambil statistik
$res_total_film = mysqli_query($koneksi, "SELECT COUNT(id) as total FROM film");
$total_film = mysqli_fetch_assoc($res_total_film)['total'];

$res_total_user = mysqli_query($koneksi, "SELECT COUNT(id) as total FROM user WHERE role = 'user'");
$total_user = mysqli_fetch_assoc($res_total_user)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white">

    <nav class="bg-black text-white shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-xl font-bold text-red-600 hover:text-orange-500">Admin Movie.pedia</a>
                <span class="text-red-600">|</span>
                <a href="dashboard.php" class="font-bold text-white">Beranda</a>
                <a href="list_film.php" class="text-white hover:text-orange-500">List Film</a>
                <a href="add_film.php" class="text-white hover:text-orange-500">Tambah Film</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="../index.php" target="_blank" class="text-white hover:text-orange-500 text-sm">Lihat Website</a>
                <a href="../auth/logout.php" class="bg-red-600 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-8">
        
        <h1 class="text-3xl font-bold text-red-600 mb-6">Admin Dashboard</h1>
        <p class="text-lg text-gray-400 mb-8">
            Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>!
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-red-800 p-6 rounded-lg shadow-xl border border-red-600">
                <h3 class="text-xl font-semibold text-white mb-2">Total Film</h3>
                <p class="text-4xl font-bold text-red-400"><?= $total_film; ?></p>
            </div>
            
            <div class="bg-red-800 p-6 rounded-lg shadow-xl border border-red-600">
                <h3 class="text-xl font-semibold text-white mb-2">Total Pengguna</h3>
                <p class="text-4xl font-bold text-orange-400"><?= $total_user; ?></p>
            </div>

        </div>

        <div class="mt-10 bg-red-800 p-6 rounded-lg shadow-xl border border-red-600">
             <div class="flex space-x-4">
                <a href="add_film.php" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-5 rounded-lg">
                    + Tambah Film Baru
                </a>
                <a href="list_film.php" class="bg-black hover:bg-red-600 text-white font-bold py-3 px-5 rounded-lg border border-red-600">
                    Lihat Semua Film
                </a>
             </div>
        </div>

    </div>

</body>
</html>