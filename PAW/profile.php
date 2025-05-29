<?php
session_start();

if (!isset($_SESSION['nim'])) {
  header("Location: login.html");
  exit();
}

$nama = $_SESSION['nama'];
$nim = $_SESSION['nim'];
$angkatan = $_SESSION['angkatan'];

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil</title>
  <link rel="stylesheet" href="profile.css" />
  <link rel="stylesheet" href="menu.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="wrapper">
    <div class="container">
      <div class="header-page">
        <img src="logo2.png" alt="PNG Placeholder">
        <div class="menu-icon" onclick="toggleMenu()">
          <i class="fas fa-bars"></i>
        </div>
        <div id="dropdownMenu" class="dropdown-menu">
          <a href="homepage.html">Home</a>
          <a href="profile.html">Profile</a>
          <a href="daftar.html">Booking</a>
          <a href="reqGame.html">Ajukan Game</a>
          <a href="riwayat.html">Riwayat</a>
          <a href="kendalaDanSaran.html">Kendala dan Saran</a>
          <a href="#" id="logoutBtn">Logout</a>
          
        </div>
      </div>

      <div class="profile-container">
        <div class="profile-header">
          <a href="homepage.html" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
          </a>
          <h1>Profil</h1>
        </div>
        <div class="form-box">
          <p class="greeting">Data Diri Anda</p>
        </div>
        <div class="profile-photo">
          <img src="profil.png" alt="Foto Profil" />
        </div>



        <div class="bio-box">
          <label>Nama Lengkap</label>
          <input type="text" value="<?= $nama ?>" readonly />
        </div>

        <div class="bio-box">
          <label>NIM</label>
          <input type="text" value="<?= $nim ?>" readonly />
        </div>

        <div class="bio-box">
          <label>Angkatan</label>
          <input type="text" value="<?= $angkatan ?>" readonly />
        </div>
      </div>

    </div>
  </div>
   <script src="logout.js"></script>
  <script src="menu.js"></script>
  <script src="menu.css" defer></script>
</body>

</html>