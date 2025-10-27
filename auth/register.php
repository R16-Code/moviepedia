<?php
// auth/register.php
include '../config/db_koneksi.php';
include '../config/functions.php';

$error = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitasi($koneksi, $_POST['username']);
    $email = sanitasi($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (empty($username) || empty($email) || empty($password) || empty($konfirmasi_password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif ($password !== $konfirmasi_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek Keunikan Username
        $sql_cek_user = "SELECT id FROM user WHERE username = '$username'";
        $result_cek_user = mysqli_query($koneksi, $sql_cek_user);

        // Cek Keunikan Email
        $sql_cek_email = "SELECT id FROM user WHERE email = '$email'";
        $result_cek_email = mysqli_query($koneksi, $sql_cek_email);

        if (mysqli_num_rows($result_cek_user) > 0) {
            $error = "Username '$username' sudah digunakan.";
        } elseif (mysqli_num_rows($result_cek_email) > 0) {
            $error = "Email '$email' sudah digunakan.";
        } else {
            $hashed_password = hash_password($password);
            // Query INSERT menyertakan email
            $sql_insert = "INSERT INTO user (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')";
            
            if (mysqli_query($koneksi, $sql_insert)) {
                $sukses = "Registrasi berhasil! Silakan <a href='login.php' class='font-bold hover:underline text-red-600'>login di sini</a>.";
            } else {
                $error = "Registrasi gagal. Terjadi kesalahan server.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black flex items-center justify-center min-h-screen py-10">

    <div class="bg-black p-8 rounded-xl shadow-2xl w-full max-w-md border-[2px] border-red-600"">
        
        <a href="../index.php" class="text-3xl font-bold text-red-600 text-center block mb-6">Movie.pedia</a>
        <h2 class="text-2xl font-semibold text-center text-white mb-6">Buat Akun Baru</h2>

        <?php if ($error): ?>
            <p class="bg-black text-red-400 p-3 rounded-md mb-4 text-sm border border-red-600"><?= $error; ?></p>
        <?php endif; ?>
        <?php if ($sukses): ?>
            <p class="bg-black text-white p-3 rounded-md mb-4 text-sm border border-red-600"><?= $sukses; ?></p>
        <?php endif; ?>

        <?php if (!$sukses): ?>
        <form action="register.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-white mb-1">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?= htmlspecialchars($username ?? ''); ?>"
                    required
                    class="w-full p-3 border border-red-600 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-white mb-1">Email (Untuk Login)</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($email ?? ''); ?>"
                    required
                    class="w-full p-3 border border-red-600 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-white mb-1">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full p-3 border border-red-600 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>
            
             <div>
                <label for="konfirmasi_password" class="block text-sm font-medium text-white mb-1">Konfirmasi Password</label>
                <input 
                    type="password" 
                    id="konfirmasi_password" 
                    name="konfirmasi_password" 
                    required
                    class="w-full p-3 border border-red-600 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-5 rounded-md transition duration-300"
            >
                Daftar
            </button>
        </form>
        
        <p class="text-center text-gray-300 mt-6 text-sm">
            Sudah punya akun? 
            <a href="login.php" class="text-red-600 hover:text-orange-500 font-medium">Login di sini</a>.
        </p>
        <?php endif; ?>

    </div>

</body>
</html>