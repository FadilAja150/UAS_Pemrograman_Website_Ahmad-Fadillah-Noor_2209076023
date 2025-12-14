<?php
include 'koneksi.php';
enforce_login(); // Wajib login untuk akses

// --- 1. LOGIKA PENCARIAN (SEARCH) ---
$search = $_GET['search'] ?? '';
$search_query = "";
if (!empty($search)) {
    // Tambahkan kondisi pencarian
    $search_query = " WHERE `Nama Barang` LIKE ? OR `Jenis Barang` LIKE ?";
    $search_param = "%" . $search . "%";
}

// --- 2. LOGIKA PAGINASI (PAGINATION) ---
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Hitung total data (dengan mempertimbangkan pencarian)
$count_sql = "SELECT COUNT(id) FROM barang" . $search_query;
if (!empty($search)) {
    $stmt_count = $conn->prepare($count_sql);
    $stmt_count->bind_param("ss", $search_param, $search_param);
    $stmt_count->execute();
    $total_result = $stmt_count->get_result()->fetch_row();
} else {
    $total_result = $conn->query($count_sql)->fetch_row();
}
$total_data = $total_result[0];
$total_pages = ceil($total_data / $limit);

// --- 3. LOGIKA FETCH DATA UTAMA (dengan PAGINASI dan SEARCH) ---
$sql = "SELECT * FROM barang" . $search_query . " ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $stmt->bind_param("ssii", $search_param, $search_param, $start, $limit);
} else {
    $stmt->bind_param("ii", $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .header-section h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .action-bar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 2px solid #e9ecef;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        }
        
        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge.bg-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }
        
        .badge.bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            border: none;
            color: #333;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 151, 30, 0.3);
            color: #333;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }
        
        .pagination {
            margin-top: 25px;
        }
        
        .page-link {
            border: 2px solid #e9ecef;
            color: #667eea;
            margin: 0 3px;
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .text-muted {
            padding: 50px;
            font-size: 1.1rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            
            .header-section {
                padding: 15px;
            }
            
            .header-section h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="main-container">
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2><i class="fas fa-box-open me-2"></i>Data Inventori Barang</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="user-badge">
                        <i class="fas fa-user-circle me-2"></i><?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                    <a href="logout.php" class="btn btn-logout btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <div class="action-bar">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <a href="input_barang.php" class="btn btn-success shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Barang Baru
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="data_barang.php" method="GET" class="d-flex search-box">
                        <input type="search" name="search" class="form-control me-2" placeholder="🔍 Cari Nama/Jenis Barang..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="data_barang.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Daftar Barang 
                    <span class="badge bg-light text-dark ms-2"><?= $total_data ?> Items</span>
                </h5>
            </div>
            <div class="card-body p-0">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th><i class="fas fa-tag me-2"></i>Nama Barang</th>
                                <th><i class="fas fa-layer-group me-2"></i>Jenis Barang</th>
                                <th><i class="fas fa-cubes me-2"></i>Jumlah Stok</th>
                                <th><i class="fas fa-calendar-plus me-2"></i>Tgl Masuk</th>
                                <th><i class="fas fa-calendar-minus me-2"></i>Tgl Keluar</th>
                                <th><i class="fas fa-cog me-2"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = $start + 1; ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= $no++ ?></strong></td>
                                    <td><strong><?= htmlspecialchars($row["Nama Barang"]) ?></strong></td>
                                    <td><?= htmlspecialchars($row["Jenis Barang"]) ?></td>
                                    
                                    <?php
                                    $jumlah = $row["Jumlah Barang"];
                                    $badge_class = ($jumlah <= 10) ? 'bg-danger' : 'bg-success';
                                    ?>
                                    <td><span class='badge <?= $badge_class ?>'><?= $jumlah ?> Unit</span></td>

                                    <td><?= htmlspecialchars($row["Masuk"]) ?></td>
                                    <td><?= htmlspecialchars($row["Keluar"]) ?></td>
                                    
                                    <td>
                                        <a href='edit_barang.php?id=<?= $row["id"] ?>' class='btn btn-warning btn-sm me-2'>
                                            <i class='fas fa-edit me-1'></i>Ubah
                                        </a>
                                        <a href='hapus_barang.php?id=<?= $row["id"] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class='fas fa-trash-alt me-1'></i>Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='7'>
                                    <div class='empty-state'>
                                        <i class='fas fa-inbox'></i>
                                        <p class='text-muted mb-0'>Tidak ada data barang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav class="px-4 pb-4">
                        <ul class="pagination justify-content-center mb-0">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php 
                                // Mempertahankan query pencarian di link paginasi
                                $query_params = http_build_query(array_merge($_GET, ['page' => $i])); 
                                ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= $query_params ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php 
$stmt->close();
$conn->close();
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>