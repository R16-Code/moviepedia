<?php
include '../config/db_koneksi.php';
include '../config/functions.php';

cek_login(); 

$user_id = $_SESSION['user_id'];

// Query JOIN untuk mengambil data film berdasarkan watchlist user
$sql = "SELECT f.*
        FROM watchlist w
        JOIN film f ON w.film_id = f.id
        WHERE w.user_id = '$user_id'
        ORDER BY f.judul ASC";

$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist Saya - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .text-custom-neon-yellow { color: #F4F754; } 
    </style>
</head>
<body class="bg-black text-white font-sans min-h-screen">
    <nav class="bg-black shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-red-600">Movie.pedia</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../index.php" class="text-white hidden hover:text-orange-500 hidden md:block">Beranda</a>
                    <a href="watchlist.php" class="text-red-600 font-medium hover:text-orange-500 hidden md:block">Watchlist Saya</a>
                    <a href="../about.php" class="text-white hover:text-orange-500 hidden md:block">Tentang Kami</a>
                    <a href="../contact.php" class="text-white hover:text-orange-500 hidden md:block">Kontak</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="text-white hover:text-orange-500 hidden md:block">Admin</a>
                    <?php endif; ?>
                    <span class="text-red-600 hidden md:block">|</span>
                    <span class="font-medium">Halo, <?= htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="auth/logout.php" class="bg-red-600 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="text-white hover:text-orange-500">Login</a>
                    <a href="auth/register.php" class="bg-red-600 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-8">

        <h1 class="text-3xl font-bold text-red-600 mb-6">Watchlist Saya</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $poster_display_path = !empty($row['poster_url']) 
                        ? '../' . htmlspecialchars($row['poster_url']) 
                        : '../assets/images/default.jpg';
                    ?>
                
                    <div class="bg-red-800 rounded-xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300 hover:shadow-red-600/40 border border-red-600">
                        
                        <a href="../detail_film.php?id=<?= $row['id']; ?>">
                            <img 
                                src="<?= $poster_display_path; ?>" 
                                alt="<?= htmlspecialchars($row['judul']); ?>" 
                                class="w-full h-48 object-cover border-b border-red-600" 
                            >
                        </a>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-xl mb-1">
                                <a href="../detail_film.php?id=<?= $row['id']; ?>" class="text-white hover:text-orange-500">
                                    <?= htmlspecialchars($row['judul']); ?>
                                </a>
                            </h3>
                            <p class="text-sm text-gray-400 mb-3"><?= $row['tahun_rilis']; ?></p>
                            
                            <p class="text-gray-400 text-base mb-4 line-clamp-3">
                                <?= htmlspecialchars(empty($row['sinopsis']) ? 'Sinopsis belum tersedia.' : $row['sinopsis']); ?>
                            </p>
                            
                            <div class="mt-auto flex justify-between items-center">
                                <a href="../detail_film.php?id=<?= $row['id']; ?>" class="text-custom-neon-yellow hover:text-orange-500 font-semibold text-sm">
                                    Lihat Detail &rarr;
                                </a>
                                <a href="watchlist_action.php?id=<?= $row['id']; ?>&action=remove" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-2 px-3 rounded-md text-sm" onclick="return confirm('Hapus dari watchlist?');">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full bg-black text-center p-10 rounded-lg shadow-xl border border-red-600">
                    <h2 class="text-2xl font-semibold text-white mb-3">Watchlist Anda Kosong</h2>
                    <p class="text-gray-400 mb-6">Anda belum menambahkan film apapun ke watchlist.</p>
                    <a href="../index.php" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg">
                        Cari Film Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>