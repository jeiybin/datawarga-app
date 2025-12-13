<?php
include "../db.php";

if (!isset($_GET['id'])) {
    header("Location: ../warga/daftar_wg.php");
    exit;
}

$id = intval($_GET['id']); 

$sql = "SELECT iuran.*, warga.nama_kk, warga.no_rumah 
        FROM iuran 
        JOIN warga ON iuran.no_rumah = warga.no_rumah 
        WHERE iuran.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    header("Location: ../warga/daftar_wg.php");
    exit;
}

$iuran = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal_bayar'];

    if ($tanggal) {
        $update = "UPDATE iuran SET tanggal_bayar = ? WHERE id = ?";
        $stmtUp = $conn->prepare($update);
        $stmtUp->bind_param("si", $tanggal, $id);
    } else {
        $update = "UPDATE iuran SET tanggal_bayar = NULL WHERE id = ?";
        $stmtUp = $conn->prepare($update);
        $stmtUp->bind_param("i", $id);
    }

    if ($stmtUp->execute()) {
        header("Location: ../warga/detail.php?no_rumah=" . urlencode($iuran['no_rumah']) . "&tahun=" . $iuran['tahun']);
        exit;
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Iuran</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>
  <div class="container">
    <h2>Edit Iuran – <?= htmlspecialchars($iuran['nama_kk']) ?> (Rumah <?= htmlspecialchars($iuran['no_rumah']) ?>)</h2>
    <p><strong>Bulan:</strong> <?= htmlspecialchars($iuran['bulan']) ?></p>
    <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($iuran['status'])) ?></p>

    <form method="POST" action="">
      <div class="form-group">
        <label for="tanggal_bayar"><strong>Tanggal Bayar:</strong></label><br>
        <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="<?= htmlspecialchars($iuran['tanggal_bayar']) ?>">
      </div>

      <div class="form-actions">
        <a href="#" class="btn" onclick="this.closest('form').submit()">Simpan</a>
        <a href="../warga/detail.php?no_rumah=<?= urlencode($iuran['no_rumah']) ?>&tahun=<?= $iuran['tahun'] ?>" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>