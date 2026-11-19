<?php
session_start();

// Hapus semua data di dalam session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect balik ke halaman login dengan pesan
header("Location: login.php?pesan=logout");
exit;
