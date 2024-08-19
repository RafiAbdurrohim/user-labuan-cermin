<?php
include 'koneksi.php';

if (isset($_GET['paket_id'])) {
    $paket_id = $_GET['paket_id'];
    $sql = "SELECT komponen FROM paket_wisata WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo $row['komponen'];
    }
}

$conn->close();
?>