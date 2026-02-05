<?php
session_start();

if (isset($_SESSION['data'])) {
  $role = $_SESSION['data']['role'];

  header("Location: views/$role/dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-gradient-soft { background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); }
    </style>
</head>
<body class="bg-gradient-soft min-h-screen flex items-center justify-center p-4 py-10">
    <div class="bg-white/70 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl w-full max-w-[650px] border border-white">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500/10 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi data berikut untuk mendaftar</p>
        </div>

        <form action="controllers/c_login.php?aksi=registrasi" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2 space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama lengkap sesuai data sekolah" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">NIS</label>
                <input type="text" name="nis" required placeholder="Nomor Induk Siswa" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Kelas</label>
                <input type="text" name="kelas" required placeholder="Contoh: XII-RPL-1" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Email</label>
                <input type="email" name="email" required placeholder="email@sekolah.com" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Username</label>
                <input type="text" name="username" required placeholder="Username" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="md:col-span-2 space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                    class="w-full px-5 py-3 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
                    <input type="hidden" name="role" value="user">
            </div>

            <button type="submit" class="md:col-span-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.97] mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-600">
            Sudah punya akun? <a href="index.php" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>
</body>
</html>