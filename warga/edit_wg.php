<?php
session_start();
include "../db.php";

// Validasi parameter
if (!isset($_GET['no_rumah'])) {
    header("Location: daftar_wg.php");
    exit;
}

$no_rumah = $_GET['no_rumah'];

// Ambil data warga
$sql = "SELECT * FROM warga WHERE no_rumah = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $no_rumah);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: daftar_wg.php");
    exit;
}

$warga = $result->fetch_assoc();

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama_kk'];

    $update = "UPDATE warga SET nama_kk = ? WHERE no_rumah = ?";
    $stmtUp = $conn->prepare($update);

    if ($stmtUp) {
        $stmtUp->bind_param("ss", $nama, $no_rumah);
        if ($stmtUp->execute()) {
            header("Location: daftar_wg.php");
            exit;
        } else {
            echo "Execute failed: " . $stmtUp->error;
            exit;
        }
    } else {
        echo "Prepare failed: " . $conn->error;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Warga</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>
  <div class="container">
    <h2>Edit Warga</h2>
    <form method="POST">
      <div class="form-group">
        <label for="no_rumah"><strong>Nomor Rumah:</strong></label><br>
        <input type="text" id="no_rumah" value="<?= htmlspecialchars($warga['no_rumah']) ?>" readonly>
      </div>

      <div class="form-group">
        <label for="nama_kk"><strong>Nama Kepala Keluarga:</strong></label><br>
        <input type="text" id="nama_kk" name="nama_kk" value="<?= htmlspecialchars($warga['nama_kk']) ?>" required>
      </div>

      <div class="form-actions">
        <a href="#" class="btn" onclick="this.closest('form').submit()">Simpan</a>
        <a href="daftar_wg.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>