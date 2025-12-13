<?php
include "../db.php";

// Validasi parameter no_rumah
if (!isset($_GET['no_rumah'])) {
    header("Location: daftar_wg.php");
    exit;
}

$no_rumah = $_GET['no_rumah'];

// Hapus data warga 
$delete = "DELETE FROM warga WHERE no_rumah = ?";
$stmt = $conn->prepare($delete);
$stmt->bind_param("s", $no_rumah);

if ($stmt->execute()) {
    header("Location: daftar_wg.php");
    exit;
} else {
    echo "Error: " . $conn->error;
    exit;
}
?>