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
    <title>Login | Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-gradient-soft { background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); }
    </style>
</head>
<body class="bg-gradient-soft min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/70 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl w-full max-w-[450px] border border-white">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500/10 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk melanjutkan</p>
        </div>

        <form action="controllers/c_login.php?aksi=login" method="POST" class="space-y-6">
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Username</label>
                <input type="text" name="username" required placeholder="Masukkan username" 
                    class="w-full px-5 py-3.5 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-500 uppercase ml-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                    class="w-full px-5 py-3.5 rounded-2xl bg-white/50 border border-slate-200 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10 outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.97]">
                Masuk ke Akun
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-600">
            Belum punya akun? <a href="registrasi.php" class="text-blue-600 font-bold hover:underline">Daftar Sekarang</a>
        </div>
    </div>
</body>
</html>