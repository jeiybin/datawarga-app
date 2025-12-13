<?php
include "../db.php";

// Validasi parameter ID iuran
if (!isset($_GET['id'])) {
    header("Location: ../warga/daftar_wg.php");
    exit;
}

$id = intval($_GET['id']); // id iuran tetap ada sebagai PK auto_increment

// Ambil data iuran untuk mendapatkan no_rumah dan tahun
$sql = "SELECT * FROM iuran WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    header("Location: ../warga/daftar_wg.php");
    exit;
}

$iuran = $result->fetch_assoc();
$no_rumah = $iuran['no_rumah'];
$tahun    = $iuran['tahun']; // ambil tahun dari record iuran

// Eksekusi penghapusan
$delete_sql = "DELETE FROM iuran WHERE id = ?";
$stmtDel = $conn->prepare($delete_sql);
$stmtDel->bind_param("i", $id);
$delete_result = $stmtDel->execute();

// Redirect ke halaman profil warga
if ($delete_result) {
    header("Location: ../warga/detail.php?no_rumah=" . urlencode($no_rumah) . "&tahun=" . $tahun);
    exit;
} else {
    echo "Terjadi kesalahan saat menghapus data: " . $conn->error;
}
?>