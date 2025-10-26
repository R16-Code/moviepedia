<?php
// auth/login.php
include '../config/db_koneksi.php';
include '../config/functions.php'; 

$error = '';
$pesan_get = isset($_GET['pesan']) ? $_GET['pesan'] : '';

// Jika sudah login, tendang
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitasi($koneksi, $_POST['email']); // Gunakan email untuk login
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $sql = "SELECT id, username, password, role FROM user WHERE email = '$email'"; // Mencari berdasarkan email
        $result = mysqli_query($koneksi, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                // Login sukses
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username']; // Ambil username untuk tampilan
                $_SESSION['role'] = $row['role'];

                // Redirect berdasarkan role
                if ($row['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak ditemukan."; // Pesan error diubah
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie.pedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black flex items-center justify-center min-h-screen">

    <div class="bg-red-800 p-8 rounded-xl shadow-2xl w-full max-w-md border border-red-600">
        
        <a href="../index.php" class="text-3xl font-bold text-red-600 text-center block mb-6">Movie.pedia</a>
        <h2 class="text-2xl font-semibold text-center text-white mb-6">Login ke Akun Anda</h2>

        <?php if ($error): ?>
            <p class="bg-black text-red-400 p-3 rounded-md mb-4 text-sm border border-red-600"><?= $error; ?></p>
        <?php endif; ?>
        
        <?php if ($pesan_get === 'wajib_login'): ?>
            <p class="bg-black text-red-400 p-3 rounded-md mb-4 text-sm border border-red-600">Anda harus login untuk mengakses halaman tersebut.</p>
        <?php elseif ($pesan_get === 'logout_sukses'): ?>
            <p class="bg-black text-white p-3 rounded-md mb-4 text-sm border border-red-600">Anda berhasil logout.</p>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-white mb-1">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-white mb-1">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full p-3 border border-red-600 bg-black text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-red-600 hover:bg-orange-600 text-white font-bold py-3 px-5 rounded-md transition duration-300"
            >
                Login
            </button>
        </form>
        
        <p class="text-center text-gray-300 mt-6 text-sm">
            Belum punya akun? 
            <a href="register.php" class="text-red-600 hover:text-orange-500 font-medium">Daftar di sini</a>.
        </p>
    </div>

</body>
</html>