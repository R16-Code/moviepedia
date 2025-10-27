<?php
// detail_film.php
include 'config/db_koneksi.php';
include 'config/functions.php'; 

$film_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($film_id === 0) { die("Error: ID film tidak valid."); }

$sql_film = "SELECT * FROM film WHERE id = '$film_id'";
$result_film = mysqli_query($koneksi, $sql_film);
if (mysqli_num_rows($result_film) === 0) { die("Film tidak ditemukan."); }
$film = mysqli_fetch_assoc($result_film);

// --- Logika Cek Watchlist ---
$is_logged_in = isset($_SESSION['user_id']);
$is_on_watchlist = false;
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $sql_wl = "SELECT id FROM watchlist WHERE user_id = '$user_id' AND film_id = '$film_id'";
    $result_wl = mysqli_query($koneksi, $sql_wl);
    $is_on_watchlist = mysqli_num_rows($result_wl) > 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($film['judul']); ?> - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Warna kustom untuk Rating/Aksen */
        .text-custom-neon-yellow { color: #F4F754; } 
        .hover\:text-custom-neon-yellow:hover { color: #F4F754; }
    </style>
</head>
<body class="bg-black text-white font-sans">

    <nav class="bg-black shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-red-600">Movie.pedia</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php" class="text-white hidden hover:text-orange-500 hidden md:block">Beranda</a>
                    <a href="user/watchlist.php" class="text-white hover:text-orange-500 hidden md:block">Watchlist Saya</a>
                    <a href="about.php" class="text-white hover:text-orange-500 hidden md:block">Tentang Kami</a>
                    <a href="contact.php" class="text-white hover:text-orange-500 hidden md:block">Kontak</a>
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
        <div class="bg-black rounded-lg shadow-xl overflow-hidden md:grid md:grid-cols-3 gap-8 border border-red-600">
            
            <div class="md:col-span-1 p-6">
                <?php
                $poster_display_path = !empty($film['poster_url']) 
                    ? htmlspecialchars($film['poster_url']) 
                    : 'assets/images/default.jpg';
                ?>
                <img 
                    src="<?= $poster_display_path; ?>" 
                    alt="<?= htmlspecialchars($film['judul']); ?>" 
                    class="w-full h-auto rounded-lg shadow-2xl border-2 border-red-700" 
                >
            </div>

            <div class="p-6 md:p-8 md:col-span-2">
                
                <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($film['judul']); ?></h1>
                <p class="text-xl text-gray-400 mb-4">(<?= $film['tahun_rilis']; ?>)</p>

                <div class="flex items-center mb-4">
                    <span class="text-custom-neon-yellow text-2xl font-bold flex items-center">
                        <svg class="w-6 h-6 text-custom-neon-yellow mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <?= $film['rating_imdb']; ?>
                    </span>
                    <span class="text-gray-400 text-lg ml-1">/ 10</span>
                </div>

                <div class="space-y-2 mb-6 text-gray-300">
                    <p><strong>Genre:</strong> <?= htmlspecialchars($film['genre'] ?: 'N/A'); ?></p>
                    <p><strong>Director:</strong> <?= htmlspecialchars($film['director'] ?: 'N/A'); ?></p>
                    <p><strong>Cast:</strong> <?= htmlspecialchars($film['cast'] ?: 'N/A'); ?></p>
                </div>

                <div class="mb-6">
                    <?php if ($is_logged_in): ?>
                        <?php if ($is_on_watchlist): ?>
                            <a href="user/watchlist_action.php?id=<?= $film['id']; ?>&action=remove" class="w-full text-center block bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                Hapus dari Watchlist
                            </a>
                        <?php else: ?>
                            <a href="user/watchlist_action.php?id=<?= $film['id']; ?>&action=add" class="w-full text-center block bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                + Tambahkan ke Watchlist
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-gray-400 p-4 text-center bg-black rounded-lg border border-red-900">
                            Silakan <a href="auth/login.php" class="text-red-600 font-semibold hover:text-orange-500">login</a> untuk menambahkan ke watchlist.
                        </p>
                    <?php endif; ?>
                </div>

                <h3 class="text-xl font-semibold mb-2">Sinopsis</h3>
                <p class="text-gray-300 leading-relaxed mb-6">
                    <?= nl2br(htmlspecialchars(empty($film['sinopsis']) ? 'Sinopsis belum tersedia.' : $film['sinopsis'])); ?>
                </p>

                <?php
                $embed_url = get_youtube_embed_url($film['trailer_url']);
                if (!empty($embed_url)):
                ?>
                    <hr class="my-6 border-red-900">
                    <h3 class="text-xl font-semibold mb-4">Trailer</h3>
                    <div class="w-full aspect-video rounded-lg overflow-hidden shadow-2xl border border-red-600">
                        <iframe 
                            class="w-full h-full" 
                            src="<?= $embed_url; ?>" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                        ></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>