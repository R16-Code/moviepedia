<?php
// Memuat koneksi database dan fungsi-fungsi penting (termasuk memulai session)
include 'config/db_koneksi.php';
include 'config/functions.php';

// Aturan dasar untuk menampilkan daftar film
$limit = 6; // Kita ingin menampilkan 6 film per halaman (cocok untuk grid 3 kolom)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil nomor halaman dari URL, default-nya halaman 1
$offset = ($page - 1) * $limit; // Hitung titik awal pengambilan data di database

// Tangani input dari formulir pencarian dan filter
$search_keyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_genre = isset($_GET['genre']) ? mysqli_real_escape_string($koneksi, $_GET['genre']) : '';

// Susun kondisi WHERE untuk query SQL berdasarkan filter/pencarian
$where_clause = [];
if (!empty($search_keyword)) {
    // Jika ada kata kunci, tambahkan kondisi untuk mencari di kolom judul
    $where_clause[] = "judul LIKE '%$search_keyword%'";
}
if (!empty($filter_genre)) {
    // Jika ada filter genre, tambahkan kondisi genre
    $where_clause[] = "genre LIKE '%$filter_genre%'";
}

// Gabungkan semua kondisi (jika ada) menjadi satu string WHERE
$where_string = !empty($where_clause) ? " WHERE " . implode(" AND ", $where_clause) : "";

// Hitung total film yang cocok dengan filter/pencarian saat ini
$sql_total = "SELECT COUNT(id) as total FROM film $where_string";
$result_total = mysqli_query($koneksi, $sql_total);
$total_results = mysqli_fetch_assoc($result_total)['total'];

// Hitung berapa total halaman yang dibutuhkan
$total_pages = ceil($total_results / $limit);

// Ambil data film yang benar-benar akan ditampilkan di halaman ini
// Data diurutkan berdasarkan tahun rilis terbaru dan dibatasi oleh LIMIT dan OFFSET
$sql = "SELECT * FROM film $where_string ORDER BY tahun_rilis DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $sql);

// Hitung rentang item yang sedang ditampilkan (misalnya: "Showing 7 to 12 of 30")
$start_item = $offset + 1;
$end_item = min($page * $limit, $total_results);
if ($total_results == 0) {
    $start_item = 0; // Pastikan rentang menjadi "Showing 0 to 0" jika tak ada hasil
}

// Simpan semua parameter URL saat ini (search, genre) untuk digunakan pada link pagination
$query_params = $_GET; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie.pedia - Katalog Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans">

    <nav class="bg-black shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-red-600">Movie.pedia</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php" class="text-red-600 font-medium hidden hover:text-orange-500 hidden md:block">Beranda</a>
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

        <form action="index.php" method="GET" class="mb-8 p-3 bg-red-800 rounded-lg shadow-xl flex flex-col md:flex-row gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari judul film..." 
                value="<?= htmlspecialchars($search_keyword); ?>"
                class="flex-grow p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
            >
            <select name="genre" class="p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-600">
                <option value="">Semua Genre</option>
                <option value="Action" <?= $filter_genre == 'Action' ? 'selected' : ''; ?>>Action</option>
                <option value="Drama" <?= $filter_genre == 'Drama' ? 'selected' : ''; ?>>Drama</option>
                <option value="Comedy" <?= $filter_genre == 'Comedy' ? 'selected' : ''; ?>>Comedy</option>
                <option value="Horror" <?= $filter_genre == 'Horror' ? 'selected' : ''; ?>>Horror</option>
                <option value="Sci-Fi" <?= $filter_genre == 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
            </select>
            <button type="submit" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-5 rounded-md">Cari</button>
            <a href="index.php" class="bg-black hover:bg-red-600 text-white font-bold py-3 px-5 rounded-md text-center border border-red-600">Reset</a>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    // Tentukan path poster, gunakan default jika poster_url kosong
                    $poster_display_path = !empty($row['poster_url']) 
                        ? htmlspecialchars($row['poster_url']) 
                        : 'assets/images/default.jpg';
                    ?>
                    <div class="bg-red-800 rounded-xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300 hover:shadow-red-600/40 border border-gray-500">
                        <a href="detail_film.php?id=<?= $row['id']; ?>">
                            <img 
                                src="<?= $poster_display_path; ?>" 
                                alt="<?= htmlspecialchars($row['judul']); ?>" 
                                class="w-full h-48 object-cover border-b border-red-600" 
                            >
                        </a>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-xl mb-1">
                                <a href="detail_film.php?id=<?= $row['id']; ?>" class="text-white hover:text-orange-500">
                                    <?= htmlspecialchars($row['judul']); ?>
                                </a>
                            </h3>
                            <p class="text-sm text-gray-400 mb-3"><?= $row['tahun_rilis']; ?></p>
                            <p classclass="text-gray-400 text-base mb-4 line-clamp-3"> 
                                <?= htmlspecialchars(empty($row['sinopsis']) ? 'Sinopsis belum tersedia.' : $row['sinopsis']); ?>
                            </p>
                            <div class="mt-auto flex justify-between items-center">
                                <a href="detail_film.php?id=<?= $row['id']; ?>" class="text-[#F4F754] hover:text-red-500 font-semibold text-sm mt-5">
                                    Baca Selengkapnya &rarr;
                                </a>
                                <span class="text-sm text-[#F4F754] flex items-center mt-5">
                                    <svg class="w-4 h-4 text-[#F4F754] mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <?= $row['rating_imdb']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-red-600 col-span-full text-center text-xl py-10">
                    Film tidak ditemukan. Coba reset filter atau kata kunci pencarian Anda.
                </p>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): // Hanya tampilkan blok ini jika film lebih dari 1 halaman ?>
        <div class="flex items-center justify-between border-t border-red-600 bg-black px-4 py-3 sm:px-6 mt-12 rounded-lg shadow-xl">
            
            <div class="flex flex-1 justify-between sm:hidden">
                <?php if ($page > 1):
                    $query_params['page'] = $page - 1; // Atur ke halaman sebelumnya
                ?>
                    <a href="index.php?<?= http_build_query($query_params); ?>" class="relative inline-flex items-center rounded-md border border-red-600 bg-red-800 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Previous</a>
                <?php else: ?>
                    <span class="relative inline-flex items-center rounded-md border border-black bg-black px-4 py-2 text-sm font-medium text-gray-700">Previous</span>
                <?php endif; ?>
                
                <?php if ($page < $total_pages):
                    $query_params['page'] = $page + 1; // Atur ke halaman berikutnya
                ?>
                    <a href="index.php?<?= http_build_query($query_params); ?>" class="relative ml-3 inline-flex items-center rounded-md border border-red-600 bg-red-800 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Next</a>
                <?php else: ?>
                    <span class="relative ml-3 inline-flex items-center rounded-md border border-black bg-black px-4 py-2 text-sm font-medium text-gray-700">Next</span>
                <?php endif; ?>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-400">
                        Showing
                        <span class="font-medium"><?= $start_item; ?></span>
                        to
                        <span class="font-medium"><?= $end_item; ?></span>
                        of
                        <span class="font-medium"><?= $total_results; ?></span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <?php if ($page > 1):
                            $query_params['page'] = $page - 1;
                        ?>
                            <a href="index.php?<?= http_build_query($query_params); ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-red-600 hover:bg-red-800 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 010 1.06L9.06 10l3.73 3.71a.75.75 0 11-1.06 1.06l-4.25-4.25a.75.75 0 010-1.06l4.25-4.25a.75.75 0 011.06 0z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-700 ring-1 ring-inset ring-black">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 010 1.06L9.06 10l3.73 3.71a.75.75 0 11-1.06 1.06l-4.25-4.25a.75.75 0 010-1.06l4.25-4.25a.75.75 0 011.06 0z" clip-rule="evenodd" /></svg>
                            </span>
                        <?php endif; ?>

                        <?php
                        $window = 1; // Tampilkan 1 halaman di kiri dan kanan halaman aktif
                        $show_first_ellipsis = ($page - $window) > 2; // Tampilkan ... setelah halaman 1
                        $show_last_ellipsis = ($page + $window) < ($total_pages - 1); // Tampilkan ... sebelum halaman terakhir

                        for ($i = 1; $i <= $total_pages; $i++):
                            $query_params['page'] = $i;
                            $page_href = 'index.php?' . http_build_query($query_params);

                            // Aturan: Tampilkan halaman 1, halaman terakhir, dan halaman di sekitar halaman aktif
                            if ($i == 1 || $i == $total_pages || ($i >= $page - $window && $i <= $page + $window)):
                        ?>
                                <a href="<?= $page_href; ?>" 
                                    aria-current="<?= ($i == $page) ? 'page' : 'false'; ?>" 
                                    class="relative z-10 inline-flex items-center <?= ($i == $page) ? 'bg-red-600 text-white' : 'text-gray-200 ring-1 ring-inset ring-red-600 hover:bg-red-800'; ?> px-4 py-2 text-sm font-semibold focus:z-20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    <?= $i; ?>
                                </a>
                        <?php
                            // Tampilkan "..." setelah halaman 1
                            elseif ($i == 2 && $show_first_ellipsis):
                        ?>
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-red-600">...</span>
                        <?php
                            // Tampilkan "..." sebelum halaman terakhir
                            elseif ($i == ($total_pages - 1) && $show_last_ellipsis):
                        ?>
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-red-600">...</span>
                        <?php
                            endif;
                        endfor;
                        ?>

                        <?php if ($page < $total_pages):
                            $query_params['page'] = $page + 1;
                        ?>
                            <a href="index.php?<?= http_build_query($query_params); ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-red-600 hover:bg-red-800 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 010-1.06L10.94 10 7.21 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-700 ring-1 ring-inset ring-black">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 010-1.06L10.94 10 7.21 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" /></svg>
                            </span>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>