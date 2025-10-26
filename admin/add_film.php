<?php
// admin/add_film.php
include '../config/db_koneksi.php';
include '../config/functions.php';

cek_admin(); 
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua data dari form
    $judul = sanitasi($koneksi, $_POST['judul']);
    $tahun = (int)$_POST['tahun_rilis'];
    $genre = sanitasi($koneksi, $_POST['genre']);
    $director = sanitasi($koneksi, $_POST['director']);
    $cast = sanitasi($koneksi, $_POST['cast']);
    $rating = (float)$_POST['rating_imdb'];
    $sinopsis = sanitasi($koneksi, $_POST['sinopsis']);
    $trailer_url = sanitasi($koneksi, $_POST['trailer_url']);
    
    $poster_path = ''; // Path poster akan disimpan di sini

    // --- Logika Upload Gambar ---
    if (isset($_FILES['poster_file']) && $_FILES['poster_file']['error'] == 0) {
        $target_dir = "../uploads/posters/"; 
        $file_name = time() . '_' . basename($_FILES["poster_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["poster_file"]["tmp_name"]);
        if($check === false) {
            $error = "File bukan gambar.";
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $error = "Maaf, hanya file JPG, JPEG, & PNG yang diizinkan.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $error .= " File Anda gagal di-upload.";
        } else {
            if (move_uploaded_file($_FILES["poster_file"]["tmp_name"], $target_file)) {
                $poster_path = "uploads/posters/" . $file_name; 
            } else {
                $error = "Maaf, terjadi error saat meng-upload file.";
            }
        }
    }
    // --- Akhir Logika Upload ---

    if (empty($judul) || $tahun == 0) {
        $error = "Judul dan Tahun Rilis wajib diisi.";
    }

    // Hanya lanjut jika tidak ada error
    if (empty($error)) {
        $sql = "INSERT INTO film (
                    judul, tahun_rilis, genre, director, cast, 
                    rating_imdb, sinopsis, poster_url, trailer_url
                ) VALUES (
                    '$judul', '$tahun', '$genre', '$director', '$cast', 
                    '$rating', '$sinopsis', '$poster_path', '$trailer_url'
                )";
        
        if (mysqli_query($koneksi, $sql)) {
            header("Location: list_film.php?status=tambah_sukses");
            exit();
        } else {
            $error = "Gagal menambah film: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Film Baru - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black">

    <nav class="bg-black text-white shadow-lg w-full sticky top-0 z-50 border-b border-red-600">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-xl font-bold text-red-600 hover:text-orange-500">Admin Movie.pedia</a>
                <span class="text-red-600">|</span>
                <a href="dashboard.php" class="text-white hover:text-orange-500">Beranda</a>
                <a href="list_film.php" class="text-white hover:text-orange-500">List Film</a>
                <a href="add_film.php" class="font-bold text-white">Tambah Film</a> </div>
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
            <h2 class="text-2xl font-bold text-white mb-6">Formulir Tambah Film Baru</h2>
            
            <?php if ($error): ?>
                <p class="bg-black text-red-400 p-3 rounded-md mb-6 text-sm border border-red-600"><?= $error; ?></p>
            <?php endif; ?>

            <form action="add_film.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-white mb-1">Judul Film *</label>
                        <input type="text" id="judul" name="judul" required
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                    
                    <div>
                        <label for="tahun_rilis" class="block text-sm font-medium text-white mb-1">Tahun Rilis *</label>
                        <input type="number" id="tahun_rilis" name="tahun_rilis" min="1900" max="2099" required
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                    
                    <div>
                        <label for="rating_imdb" class="block text-sm font-medium text-white mb-1">Rating IMDb</label>
                        <input type="number" id="rating_imdb" name="rating_imdb" step="0.1" min="0" max="10"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>

                    <div>
                        <label for="genre" class="block text-sm font-medium text-white mb-1">Genre (Contoh: Action, Drama)</label>
                        <input type="text" id="genre" name="genre"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>

                    <div>
                        <label for="director" class="block text-sm font-medium text-white mb-1">Director</label>
                        <input type="text" id="director" name="director"
                               class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                    </div>
                </div>

                <div>
                    <label for="cast" class="block text-sm font-medium text-white mb-1">Cast (Aktor/Aktris)</label>
                    <textarea id="cast" name="cast" rows="3"
                                 class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"></textarea>
                </div>
                
                <div>
                    <label for="sinopsis" class="block text-sm font-medium text-white mb-1">Sinopsis</label>
                    <textarea id="sinopsis" name="sinopsis" rows="5"
                                 class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"></textarea>
                </div>

                <div>
                    <label for="trailer_url" class="block text-sm font-medium text-white mb-1">URL Trailer YouTube</label>
                    <input type="url" id="trailer_url" name="trailer_url" placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600">
                </div>
                
                <div>
                    <label for="poster_file" class="block text-sm font-medium text-white mb-1">Upload Poster (JPG/PNG)</label>
                    <input type="file" id="poster_file" name="poster_file"
                           class="w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-orange-600">
                </div>
                
                <div class="border-t border-red-600 pt-6 text-right">
                    <button type="submit" class="bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Simpan Film
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>