<?php
include "../db.php";

// Validasi parameter
if (!isset($_GET['no_rumah']) || !isset($_GET['bulan']) || !isset($_GET['tahun'])) {
    header("Location: ../warga/daftar_wg.php");
    exit;
}

$no_rumah = $_GET['no_rumah'];
$bulan    = $_GET['bulan'];
$tahun    = intval($_GET['tahun']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status  = $_POST['status'];
    $tanggal = $_POST['tanggal_bayar'];

    $sql = "INSERT INTO iuran (no_rumah, bulan, tahun, status, tanggal_bayar) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $no_rumah, $bulan, $tahun, $status, $tanggal);

    if ($stmt->execute()) {
        header("Location: ../warga/detail.php?no_rumah=" . urlencode($no_rumah) . "&tahun=" . $tahun);
        exit;
    } else {
        if ($conn->errno == 1062) {
            $error_message = "Data iuran untuk $bulan $tahun sudah ada.";
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
  <title>Tambah Iuran</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>
  <div class="container">
    <h2>Tambah Iuran Bulan <?= htmlspecialchars($bulan) ?> Tahun <?= htmlspecialchars($tahun) ?></h2>

    <?php if (!empty($error_message)) { ?>
      <div class="warning-box">
        <?= htmlspecialchars($error_message) ?>
      </div>
    <?php } ?>

    <form method="POST" action="">
      <label for="status"><strong>Status:</strong></label><br>
      <select name="status" id="status" required>
        <option value="sudah">Sudah</option>
        <option value="belum">Belum</option>
      </select><br><br>

      <label for="tanggal_bayar"><strong>Tanggal Bayar:</strong></label><br>
      <input type="date" name="tanggal_bayar" id="tanggal_bayar"><br><br>

      <div class="form-actions">
        <a href="#" class="btn" onclick="this.closest('form').submit()">Simpan</a>
        <a href="../warga/detail.php?no_rumah=<?= urlencode($no_rumah) ?>&tahun=<?= $tahun ?>" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>