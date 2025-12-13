<?php
include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_kk'];
    $no_rumah = $_POST['no_rumah'];

    $sql = "INSERT INTO warga (no_rumah, nama_kk) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $no_rumah, $nama);

    if ($stmt->execute()) {
        header("Location: daftar_wg.php");
        exit;
    } else {
        if ($conn->errno == 1062) {
            $error_message = "Nomor rumah '$no_rumah' sudah terdaftar. Silakan gunakan nomor lain.";
        } else {
            $error_message = "Terjadi kesalahan: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Warga Baru</title>
  <link rel="stylesheet" href="../css/style.css?v=3">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
  <h2>Tambah Warga Baru</h2>

  <?php if (!empty($error_message)) { ?>
    <div class="warning-box">
      <?= htmlspecialchars($error_message) ?>
    </div>
  <?php } ?>

  <form method="POST" action="">
    <div class="form-group">
      <label for="no_rumah">Nomor Rumah:</label>
      <input type="text" id="no_rumah" name="no_rumah" required>
    </div>

    <div class="form-group">
      <label for="nama_kk">Nama Kepala Keluarga:</label>
      <input type="text" id="nama_kk" name="nama_kk" required>
    </div>

    <div class="form-actions">
      <a href="#" class="btn" onclick="this.closest('form').submit()">Simpan</a>
      <a href="daftar_wg.php" class="btn-cancel">Batal</a>
    </div>
  </form>
</div>
</body>
</html>