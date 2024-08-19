<?php
session_start();
include '../koneksi.php'; // Pastikan path file ini benar

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Ambil pesanan pengguna dari
$sql = "SELECT pembayaran.*, paket_wisata.nama AS nama_paket 
        FROM pembayaran 
        JOIN paket_wisata ON pembayaran.paket_wisata_id = paket_wisata.id
        WHERE pembayaran.user_id = ? OR (pembayaran.user_id IS NULL AND pembayaran.nama_pemesan = (SELECT nama FROM users WHERE id = ?))
        ORDER BY pembayaran.tanggal_pemesanan DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Anda - Labuan Cermin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e5799, #7db9e8);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(to right, #1e5799, #2989d8, #207cca, #7db9e8);
            padding: 15px 0;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            transform: translateY(-3px);
            text-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: underline;
        }
        .table-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .nav-link.btn-pesanan {
            background-color: #17a2b8;
            color: white !important;
            border-radius: 20px;
            padding: 5px 15px;
        }
        .nav-link.btn-pesanan:hover {
            background-color: #138496;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .table thead th {
            border-bottom: none;
            background-color: #f8f9fa;
            padding: 15px;
        }
        .table tbody tr {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .table tbody td {
            vertical-align: middle;
            padding: 15px;
            border: none;
        }
        .btn-bayar {
            padding: 8px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-bayar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 justify-content-between">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="paket_wisata.php">Paket Wisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="pemesanan_paket.php">Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Galleri</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link btn-pesanan active" href="#"><i class="bi bi-cart"></i> Pesanan Anda</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="table-container">
                    <h2 class="text-center mb-4">Pesanan Anda</h2>
                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Paket Wisata</th>
                                        <th>Jumlah</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th>Tanggal Pemesanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['nama_paket']; ?></td>
                                        <td><?php echo $row['jumlah']; ?></td>
                                        <td>Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo getStatusColor($row['status']); ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($row['tanggal_pemesanan'])); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'Menunggu Pembayaran'): ?>
                                                <a href="proses_pembayaran.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success btn-bayar">
                                                    <i class="bi bi-credit-card me-1"></i> Bayar
                                                </a>
                                            <?php elseif ($row['status'] == 'Dibayar' || $row['status'] == 'Diproses'): ?>
                                                <button class="btn btn-sm btn-info" onclick="showDetails(<?php echo $row['id']; ?>)">
                                                    <i class="bi bi-info-circle me-1"></i> Detail
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            <i class="bi bi-info-circle me-2"></i> Anda belum memiliki pesanan.
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-4">
                        <a href="pemesanan_paket.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Buat Pesanan Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan detail pesanan -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Detail pesanan akan dimuat di sini -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showDetails(id) {
            // Di sini Anda bisa menggunakan AJAX untuk memuat detail pesanan
            // Untuk contoh, kita akan menggunakan data statis
            const modalBody = document.getElementById('detailModalBody');
            modalBody.innerHTML = `
                <p><strong>ID Pesanan:</strong> ${id}</p>
                <p><strong>Status:</strong> Diproses</p>
                <p><strong>Estimasi Selesai:</strong> 3 hari lagi</p>
                <p>Silakan hubungi kami jika ada pertanyaan.</p>
            `;
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            detailModal.show();
        }
    </script>
</body>
</html>

<?php
function getStatusColor($status) {
    switch ($status) {
        case 'Menunggu Pembayaran':
            return 'warning';
        case 'Dibayar':
            return 'success';
        case 'Diproses':
            return 'info';
        case 'Selesai':
            return 'primary';
        case 'Dibatalkan':
            return 'danger';
        default:
            return 'secondary';
    }
}
?> 