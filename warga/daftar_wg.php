<?php
include "../db.php";

$sql = "SELECT * FROM warga";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Warga</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>
  <div class="container">
    <h2>Daftar Warga</h2>
    <div class="actions" style="margin-bottom: 15px;">
        <a href="./add_wg.php" class="btn">Tambah Warga Baru</a>
        <a href="../logout.php" class="btn-cancel">Logout</a>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>Nomor Rumah</th>
          <th>Nama Kepala Keluarga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= htmlspecialchars($row['no_rumah']) ?></td>
          <td><?= htmlspecialchars($row['nama_kk']) ?></td>
          <td>
            <a href="detail.php?no_rumah=<?= urlencode($row['no_rumah']) ?>" class="icon-btn" title="Lihat Profil">
                <i class="fas fa-user"></i>
            </a>
            <a href="./edit_wg.php?no_rumah=<?= urlencode($row['no_rumah']) ?>" class="icon-btn" title="Edit">
                <i class="fas fa-pen"></i>
            </a>
            <a href="./delete_wg.php?no_rumah=<?= urlencode($row['no_rumah']) ?>" class="icon-btn danger" title="Hapus"
                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>