<?php
include 'koneksi.php';

$status_pesan = '';
$alert_class = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis_barang = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $jumlah_barang = mysqli_real_escape_string($conn, $_POST['jumlah_barang']);
    $tgl_masuk = mysqli_real_escape_string($conn, $_POST['tgl_masuk']);
    $tgl_keluar = mysqli_real_escape_string($conn, $_POST['tgl_keluar']);

    $sql = "INSERT INTO barang (`Nama Barang`, `Jenis Barang`, `Jumlah Barang`, `Masuk`, `Keluar`) 
            VALUES ('$nama_barang', '$jenis_barang', '$jumlah_barang', '$tgl_masuk', '$tgl_keluar')";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header("Location: data_barang.php?status=input_sukses");
        exit(); // PENTING: Hentikan eksekusi script setelah redirect
    } else {
        $status_pesan = "Error: " . $sql . "<br>" . mysqli_error($conn);
        $alert_class = "alert-danger";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Barang</title>
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
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%) !important;
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
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #b8bfea;
        }

        .mb-3 {
            position: relative;
        }

        .mb-3 i.field-icon {
            position: absolute;
            right: 15px;
            top: 43px;
            color: #858796;
            pointer-events: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            border: none;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(28, 200, 138, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(28, 200, 138, 0.4);
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

        @media (max-width: 768px) {
            .card-body {
                padding: 25px 20px;
            }

            .btn-success,
            .btn-outline-secondary {
                width: 100%;
                margin-top: 10px !important;
            }
        }

        /* Add icon styles */
        .input-group-icon {
            position: relative;
        }

        .input-group-icon .form-control {
            padding-right: 40px;
        }

        .input-group-icon .icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #858796;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-truck-loading"></i> Tambah Data Barang Baru</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($status_pesan)): ?>
                        <div class="alert <?= $alert_class ?> alert-dismissible fade show" role="alert">
                            <?= $status_pesan ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="input_barang.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">
                                <i class="fas fa-box me-2"></i>Nama Barang
                            </label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jenis_barang" class="form-label">
                                <i class="fas fa-tags me-2"></i>Jenis Barang
                            </label>
                            <input type="text" class="form-control" id="jenis_barang" name="jenis_barang" placeholder="Contoh: Elektronik, Furniture, ATK" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_barang" class="form-label">
                                <i class="fas fa-sort-numeric-up me-2"></i>Jumlah Barang
                            </label>
                            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_masuk" class="form-label">
                                <i class="fas fa-calendar-plus me-2"></i>Tanggal Masuk
                            </label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_keluar" class="form-label">
                                <i class="fas fa-calendar-minus me-2"></i>Tanggal Keluar (Opsional)
                            </label>
                            <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar">
                            <small class="text-muted">*Kosongkan jika barang belum keluar</small>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success shadow"><i class="fas fa-save"></i> Simpan Data</button>
                            <a href="data_barang.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set default date to today for tgl_masuk
    document.getElementById('tgl_masuk').valueAsDate = new Date();

    // Add animation on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;
    });

    // Add focus effect
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.01)';
            this.parentElement.style.transition = 'all 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
</body>
</html>