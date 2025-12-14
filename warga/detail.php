<?php
include "../db.php";

// Pastikan ada parameter no_rumah di URL
if (!isset($_GET['no_rumah'])) {
    echo "Nomor rumah tidak ditemukan.";
    exit;
}

$no_rumah = $_GET['no_rumah'];

// Ambil data warga
$sql_warga = "SELECT * FROM warga WHERE no_rumah = ?";
$stmt = $conn->prepare($sql_warga);
$stmt->bind_param("s", $no_rumah);
$stmt->execute();
$result_warga = $stmt->get_result();

if (!$result_warga || $result_warga->num_rows == 0) {
    echo "Data warga tidak ditemukan.";
    exit;
}

$warga = $result_warga->fetch_assoc();

// daftar bulan
$bulan_list = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

// ambil tahun dari URL, default tahun sekarang
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date("Y");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Warga</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>
  <div class="container">
    <a href="daftar_wg.php" class="back-icon" title="Kembali">
      <i class="fas fa-chevron-left"></i>
    </a>

    <h2>Profil Warga</h2>
    <p><strong>Nomor Rumah:</strong> <?= htmlspecialchars($warga['no_rumah']) ?></p>
    <p><strong>Nama Kepala Keluarga:</strong> <?= htmlspecialchars($warga['nama_kk']) ?></p>

    <!-- Filter Tahun -->
    <form method="GET" action="">
      <input type="hidden" name="no_rumah" value="<?= htmlspecialchars($no_rumah) ?>">
      <label for="tahun"><strong>Pilih Tahun:</strong></label>
      <select name="tahun" id="tahun" onchange="this.form.submit()">
        <?php for ($y = date("Y"); $y >= 2020; $y--) { ?>
          <option value="<?= $y ?>" <?= ($tahun == $y ? "selected" : "") ?>><?= $y ?></option>
        <?php } ?>
      </select>
    </form>

    <h3>Status Iuran Tahun <?= $tahun ?></h3>
    <table class="data-table">
      <thead>
        <tr>
          <th>Bulan</th>
          <th>Status</th>
          <th>Tanggal Bayar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bulan_list as $bulan) {
          $sql_iuran = "SELECT * FROM iuran WHERE no_rumah = ? AND bulan = ? AND tahun = ?";
          $stmt_iuran = $conn->prepare($sql_iuran);
          $stmt_iuran->bind_param("ssi", $no_rumah, $bulan, $tahun);
          $stmt_iuran->execute();
          $result_iuran = $stmt_iuran->get_result();
          $iuran = $result_iuran ? $result_iuran->fetch_assoc() : null;
        ?>
        <tr>
          <td><?= $bulan ?></td>
          <td>
            <?php
            if ($iuran) {
                echo ($iuran['status'] == 'sudah')
                    ? "<span style='color:green;font-weight:600;'>Lunas</span>"
                    : "<span style='color:red;font-weight:600;'>Belum</span>";
            } else {
                echo "<span style='color:gray;'>Belum Bayar</span>";
            }
            ?>
          </td>
          <td><?= $iuran['tanggal_bayar'] ?? '-' ?></td>
          <td>
            <?php if ($iuran) { ?>
                <a href="../iuran/edit_iuran.php?id=<?= $iuran['id'] ?>" class="icon-btn" title="Edit">
                    <i class="fas fa-pen"></i>
                </a>
                <a href="../iuran/delete_iuran.php?id=<?= $iuran['id'] ?>" class="icon-btn danger" title="Hapus"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    <i class="fas fa-trash"></i>
                </a>
            <?php } else { ?>
                <a href="../iuran/add_iuran.php?no_rumah=<?= urlencode($no_rumah) ?>&bulan=<?= urlencode($bulan) ?>&tahun=<?= $tahun ?>"
                    class="icon-btn" title="Tambah Iuran">
                    <i class="fas fa-plus"></i>
                </a>
            <?php } ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>