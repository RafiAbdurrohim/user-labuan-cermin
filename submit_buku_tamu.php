<?php
include 'koneksi.php';

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];
    
    // Prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("INSERT INTO buku_tamu (nama, email, pesan, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sss", $nama, $email, $pesan);
    
    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>