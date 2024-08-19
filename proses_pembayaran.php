<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: lihat_pesanan.php");
    exit();
}

$pembayaran_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil detail pembayaran
$sql = "SELECT pembayaran.*, paket_wisata.nama AS nama_paket 
        FROM pembayaran 
        JOIN paket_wisata ON pembayaran.paket_wisata_id = paket_wisata.id
        WHERE pembayaran.id = ? AND pembayaran.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pembayaran_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>
          alert('Pembayaran tidak ditemukan atau bukan milik Anda.');
          window.location.href = 'lihat_pesanan.php';
          </script>";
    exit();
}

$pembayaran = $result->fetch_assoc();

// Proses pembayaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Di sini Anda bisa menambahkan logika untuk memproses pembayaran
    // Misalnya, integrasi dengan payment gateway

    // Untuk contoh ini, kita hanya akan mengubah status menjadi 'Dibayar'
    $sql_update = "UPDATE pembayaran SET status = 'Dibayar' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $pembayaran_id);
    
    if ($stmt_update->execute()) {
        echo "<script>
              alert('Pembayaran berhasil!');
              window.location.href = 'lihat_pesanan.php';
              </script>";
        exit();
    } else {
        $error = "Terjadi kesalahan saat memproses pembayaran.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran - Labuan Cermin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e5799, #7db9e8);
            min-height: 100vh;
        }
        .payment-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
        }
        .payment-method {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method:hover {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        }
        .payment-method.selected {
            border-color: #28a745;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="payment-container">
                    <h2 class="text-center mb-4">Proses Pembayaran</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Detail Pesanan</h5>
                            <p><strong>Paket Wisata:</strong> <?php echo $pembayaran['nama_paket']; ?></p>
                            <p><strong>Jumlah:</strong> <?php echo $pembayaran['jumlah']; ?></p>
                            <p><strong>Total Bayar:</strong> Rp <?php echo number_format($pembayaran['total_bayar'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    <form method="POST">
                        <h5 class="mb-3">Pilih Metode Pembayaran:</h5>
                        <div class="payment-method" onclick="selectPayment(this, 'transfer')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="transfer" value="transfer" required>
                                <label class="form-check-label" for="transfer">
                                    Transfer Bank
                                </label>
                            </div>
                        </div>
                        <div class="payment-method" onclick="selectPayment(this, 'ewallet')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="ewallet" value="ewallet" required>
                                <label class="form-check-label" for="ewallet">
                                    E-Wallet
                                </label>
                            </div>
                        </div>
                        <div class="payment-method" onclick="selectPayment(this, 'creditcard')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="creditcard" value="creditcard" required>
                                <label class="form-check-label" for="creditcard">
                                    Kartu Kredit
                                </label>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                            <a href="lihat_pesanan.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPayment(element, method) {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById(method).checked = true;
        }
    </script>
</body>
</html>