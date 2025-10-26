<?php
// admin/edit_film.php
include '../config/db_koneksi.php';
include '../config/functions.php';

cek_admin(); 
$error = '';
$film_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Logika saat form disubmit (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_update = (int)$_POST['film_id'];
    $judul = sanitasi($koneksi, $_POST['judul']);
    $tahun = (int)$_POST['tahun_rilis'];
    $genre = sanitasi($koneksi, $_POST['genre']);
    $director = sanitasi($koneksi, $_POST['director']);
    $cast = sanitasi($koneksi, $_POST['cast']);
    $rating = (float)$_POST['rating_imdb'];
    $sinopsis = sanitasi($koneksi, $_POST['sinopsis']);
    $trailer_url = sanitasi($koneksi, $_POST['trailer_url']);
    
    $poster_lama = sanitasi($koneksi, $_POST['poster_url_lama']);
    $poster_path = $poster_lama; 

    // --- Logika Upload Gambar BARU ---
    if (isset($_FILES['poster_file']) && $_FILES['poster_file']['error'] == 0) {
        $target_dir = "../uploads/posters/";
        $file_name = time() . '_' . basename($_FILES["poster_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["poster_file"]["tmp_name"]);
        if($check === false) { $error = "File bukan gambar."; $uploadOk = 0; }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $error = "Maaf, hanya file JPG, JPEG, & PNG."; $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["poster_file"]["tmp_name"], $target_file)) {
                $poster_path = "uploads/posters/" . $file_name;
                if (!empty($poster_lama) && file_exists("../" . $poster_lama)) {
                    unlink("../" . $poster_lama);
                }
            } else {
                $error = "Maaf, terjadi error saat meng-upload file baru.";
            }
        }
    }
    // --- Akhir Logika Upload ---

    if (empty($judul) || $tahun == 0) {
        $error = "Judul dan Tahun Rilis wajib diisi.";
    }

    if (empty($error)) {
        $sql = "UPDATE film SET 
                    judul = '$judul', tahun_rilis = '$tahun', genre = '$genre', 
                    director = '$director', cast = '$cast', rating_imdb = '$rating', 
                    sinopsis = '$sinopsis', poster_url = '$poster_path', trailer_url = '$trailer_url'
                WHERE id = '$id_update'";
        
        if (mysqli_query($koneksi, $sql)) {
            header("Location: list_film.php?status=edit_sukses");
            exit();
        } else {
            $error = "Gagal memperbarui film: " . mysqli_error($koneksi);
        }
    }
}
// 2. Logika saat halaman di-load (SELECT data lama)
if ($film_id === 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') { die("ID film tidak valid."); }
$sql_select = "SELECT * FROM film WHERE id = '$film_id'";
$result_select = mysqli_query($koneksi, $sql_select);
if (mysqli_num_rows($result_select) === 1) {
    $data_film = mysqli_fetch_assoc($result_select);
} else {
    die("Data film tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film - Admin</title>
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
        
        <div class="bg-red-800 p-6 md:p-8 rounded-lg shadow-xl max-w-4xl mx-auto border border-red-600">
            <h2 class="text-2xl font-bold text-white mb-6">Formulir Edit Film (ID: <?= $film_id; ?>)</h2>
            
            <?php if ($error): ?>
                <p class="bg-black text-red-400 p-3 rounded-md mb-6 text-sm border border-red-600"><?= $error; ?></p>
            <?php endif; ?>

            <form action="edit_film.php?id=<?= $film_id; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="film_id" value="<?= $film_id; ?>">
                <input type="hidden" name="poster_url_lama" value="<?= htmlspecialchars($data_film['poster_url']); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-white mb-1">Judul Film *</label>
                        <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data_film['judul']); ?>" required
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                    
                    <div>
                        <label for="tahun_rilis" class="block text-sm font-medium text-white mb-1">Tahun Rilis *</label>
                        <input type="number" id="tahun_rilis" name="tahun_rilis" value="<?= $data_film['tahun_rilis']; ?>" required
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                    
                    <div>
                        <label for="rating_imdb" class="block text-sm font-medium text-white mb-1">Rating IMDb</label>
                        <input type="number" id="rating_imdb" name="rating_imdb" step="0.1" value="<?= $data_film['rating_imdb']; ?>"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>

                    <div>
                        <label for="genre" class="block text-sm font-medium text-white mb-1">Genre</label>
                        <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($data_film['genre']); ?>"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>

                    <div>
                        <label for="director" class="block text-sm font-medium text-white mb-1">Director</label>
                        <input type="text" id="director" name="director" value="<?= htmlspecialchars($data_film['director']); ?>"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                </div>

                <div>
                    <label for="cast" class="block text-sm font-medium text-white mb-1">Cast</label>
                    <textarea id="cast" name="cast" rows="3"
                                 class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"><?= htmlspecialchars($data_film['cast']); ?></textarea>
                </div>
                
                <div>
                    <label for="sinopsis" class="block text-sm font-medium text-white mb-1">Sinopsis</label>
                    <textarea id="sinopsis" name="sinopsis" rows="5"
                                 class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"><?= htmlspecialchars($data_film['sinopsis']); ?></textarea>
                </div>

                 <div>
                    <label for="trailer_url" class="block text-sm font-medium text-white mb-1">URL Trailer YouTube</label>
                    <input type="url" id="trailer_url" name="trailer_url" value="<?= htmlspecialchars($data_film['trailer_url']); ?>"
                           class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                </div>

                <div>
                    <label for="poster_file" class="block text-sm font-medium text-white mb-1">Ganti Poster (Biarkan kosong jika tidak ingin ganti)</label>
                    <input type="file" id="poster_file" name="poster_file"
                           class="w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-orange-600">
                    
                    <?php if (!empty($data_film['poster_url'])): ?>
                        <p class="mt-2 text-sm text-gray-300">Poster Saat Ini:</p>
                        <img src="../<?= htmlspecialchars($data_film['poster_url']); ?>" alt="Poster" class="mt-2 rounded-md shadow-sm border border-red-600" style="max-height: 200px;">
                    <?php endif; ?>
                </div>
                
                <div class="border-t border-red-600 pt-6 flex justify-end space-x-3">
                    <a href="list_film.php" class="bg-black hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 border border-red-600">
                        Batal
                    </a>
                    <button type="submit" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Perbarui Data Film
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>