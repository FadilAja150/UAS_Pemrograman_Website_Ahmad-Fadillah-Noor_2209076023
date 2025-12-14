<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location:data_barang.php");
    exit();
}

$id_barang = mysqli_real_escape_string($conn, $_GET['id']);
$status_pesan = '';
$alert_class = '';

// BAGIAN PERTAMA: Ambil data lama
$query_ambil_data = "SELECT * FROM barang WHERE id='$id_barang'";
$result_ambil_data = mysqli_query($conn, $query_ambil_data);

if (mysqli_num_rows($result_ambil_data) == 0) {
    header("Location:data_barang.php");
    exit();
}
$data_lama = mysqli_fetch_assoc($result_ambil_data);


// BAGIAN KEDUA: Logika Pemrosesan Form Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama_barang_baru = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis_barang_baru = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $jumlah_barang_baru = mysqli_real_escape_string($conn, $_POST['jumlah_barang']);
    $tgl_masuk_baru = mysqli_real_escape_string($conn, $_POST['tgl_masuk']);
    $tgl_keluar_baru = mysqli_real_escape_string($conn, $_POST['tgl_keluar']);

    $sql_update = "UPDATE barang SET 
                    `Nama Barang` = '$nama_barang_baru',
                    `Jenis Barang` = '$jenis_barang_baru',
                    `Jumlah Barang` = '$jumlah_barang_baru',
                    `Masuk` = '$tgl_masuk_baru',
                    `Keluar` = '$tgl_keluar_baru'
                   WHERE id = '$id_barang'";

    if (mysqli_query($conn, $sql_update)) {
        mysqli_close($conn);
        header("Location:data_barang.php?status=update_sukses");
        exit(); // PENTING
    } else {
        $status_pesan = "Error saat memperbarui data: " . mysqli_error($conn);
        $alert_class = "alert-danger";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            animation: fadeIn 0.5s ease-in;
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

        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%) !important;
            padding: 25px 30px;
            border: none;
        }

        .card-header h4 {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 35px;
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #f6c23e;
            box-shadow: 0 0 0 0.2rem rgba(246, 194, 62, 0.25);
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #f6c23e;
        }

        .mb-3 {
            position: relative;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
            border: none;
            color: #fff;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(246, 194, 62, 0.3);
        }

        .btn-warning:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(246, 194, 62, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #858796;
            color: #858796;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #858796;
            border-color: #858796;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(133, 135, 150, 0.3);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }

        .shadow-lg {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2) !important;
        }

        .info-badge {
            display: inline-block;
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .form-control.modified {
            border-color: #f6c23e;
            background-color: #fffbf0;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 25px 20px;
            }

            .btn-warning,
            .btn-outline-secondary {
                width: 100%;
                margin-top: 10px !important;
            }

            .info-badge {
                display: block;
                margin-left: 0;
                margin-top: 10px;
            }
        }

        /* Highlight effect for changed values */
        @keyframes highlight {
            0% {
                background-color: #fff3cd;
            }
            100% {
                background-color: white;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Data Barang 
                        <span class="info-badge">
                            <i class="fas fa-hashtag"></i> ID: <?= $id_barang ?>
                        </span>
                    </h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($status_pesan)): ?>
                        <div class="alert <?= $alert_class ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= $status_pesan ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Ubah data yang diperlukan, kemudian klik tombol "Update Data" untuk menyimpan perubahan.
                    </div>

                    <form action="edit_barang.php?id=<?= $id_barang ?>" method="POST" id="editForm">
                        
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">
                                <i class="fas fa-box me-2"></i>Nama Barang
                            </label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                                    value="<?= htmlspecialchars($data_lama['Nama Barang']) ?>" 
                                    data-original="<?= htmlspecialchars($data_lama['Nama Barang']) ?>"
                                    placeholder="Masukkan nama barang" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jenis_barang" class="form-label">
                                <i class="fas fa-tags me-2"></i>Jenis Barang
                            </label>
                            <input type="text" class="form-control" id="jenis_barang" name="jenis_barang" 
                                    value="<?= htmlspecialchars($data_lama['Jenis Barang']) ?>"
                                    data-original="<?= htmlspecialchars($data_lama['Jenis Barang']) ?>"
                                    placeholder="Contoh: Elektronik, Furniture, ATK" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_barang" class="form-label">
                                <i class="fas fa-sort-numeric-up me-2"></i>Jumlah Barang
                            </label>
                            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" 
                                    value="<?= htmlspecialchars($data_lama['Jumlah Barang']) ?>"
                                    data-original="<?= htmlspecialchars($data_lama['Jumlah Barang']) ?>"
                                    placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_masuk" class="form-label">
                                <i class="fas fa-calendar-plus me-2"></i>Tanggal Masuk
                            </label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" 
                                    value="<?= htmlspecialchars($data_lama['Masuk']) ?>"
                                    data-original="<?= htmlspecialchars($data_lama['Masuk']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_keluar" class="form-label">
                                <i class="fas fa-calendar-minus me-2"></i>Tanggal Keluar (Opsional)
                            </label>
                            <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar"
                                    value="<?= htmlspecialchars($data_lama['Keluar']) ?>"
                                    data-original="<?= htmlspecialchars($data_lama['Keluar']) ?>">
                            <small class="text-muted">*Kosongkan jika barang belum keluar</small>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning shadow">
                                <i class="fas fa-sync-alt"></i> Update Data
                            </button>
                            <a href="data_barang.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal / Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Detect changes in form fields
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const original = this.getAttribute('data-original');
            if (this.value !== original) {
                this.classList.add('modified');
            } else {
                this.classList.remove('modified');
            }
        });

        // Add focus effect
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.01)';
            this.parentElement.style.transition = 'all 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Add animation on form submit
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
        btn.disabled = true;
    });

    // Confirm before leaving if changes are made
    let formChanged = false;
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Reset formChanged when form is submitted
    document.getElementById('editForm').addEventListener('submit', function() {
        formChanged = false;
    });
</script>
</body>
</html>