<?php
include '../config/db_koneksi.php';
include '../config/functions.php';

cek_admin(); 

$status = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT id, judul, tahun_rilis, genre, rating_imdb, poster_url FROM film ORDER BY id DESC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Film - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-custom-neon-yellow { color: #F4F754; } 
    </style>
</head>
<body class="bg-black">

    <nav class="bg-black text-white shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-xl font-bold text-red-600 hover:text-orange-500">Admin Movie.pedia</a>
                <span class="text-red-600">|</span>
                <a href="dashboard.php" class="text-white hover:text-orange-500">Beranda</a>
                <a href="list_film.php" class="font-bold text-white">List Film</a> 
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
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-red-600">Manajemen Data Film</h1>
            <a href="add_film.php" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg">
                + Tambah Film Baru
            </a>
        </div>

        <?php if ($status === 'tambah_sukses'): ?>
            <p class="bg-red-900 text-white p-3 rounded-md mb-6 text-sm border border-red-600">Film baru berhasil ditambahkan.</p>
        <?php elseif ($status === 'edit_sukses'): ?>
            <p class="bg-red-900 text-white p-3 rounded-md mb-6 text-sm border border-red-600">Data film berhasil diperbarui.</p>
        <?php elseif ($status === 'hapus_sukses'): ?>
            <p class="bg-red-900 text-orange-400 p-3 rounded-md mb-6 text-sm border border-red-600">Film berhasil dihapus.</p>
        <?php endif; ?>

        <div class="bg-red-800 rounded-lg shadow-lg overflow-x-auto border border-red-600">
            <table class="min-w-full divide-y divide-red-600">
                <thead class="bg-black">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Poster</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-900">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $poster_display_path = !empty($row['poster_url']) 
                                ? '../' . htmlspecialchars($row['poster_url']) 
                                : '../assets/images/default.jpg';
                            ?>
                            <tr class="hover:bg-red-700 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?= $poster_display_path; ?>" alt="Poster" class="w-12 h-16 object-cover rounded border border-red-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white"><?= htmlspecialchars($row['judul']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?= $row['tahun_rilis']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?= htmlspecialchars($row['genre']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-custom-neon-yellow"><?= $row['rating_imdb']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="edit_film.php?id=<?= $row['id']; ?>" class="text-orange-500 hover:text-orange-400">Edit</a>
                                    <a href="delete_film.php?id=<?= $row['id']; ?>" class="text-red-500 hover:text-red-400" onclick="return confirm('Yakin hapus? Ini juga akan menghapus data watchlist terkait.');">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Belum ada data film.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>