<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Pengembang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 m-0">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4 p-4 text-center bg-white">
                    <div class="card-body">
                        <!-- Icon Profil -->
                        <div class="mb-3">
                            <span class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-inline-block">
                                <i class="bi bi-person-badge fs-1"></i>
                            </span>
                        </div>

                        <!-- <h3 class="fw-bold mb-2 text-dark">Tentang Pengembang</h3>
                        <p class="text-muted small mb-4"></p> -->
                        
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <h5 class="fw-bold text-primary mb-1">FRAZA SAKA AFGANI</h5>
                            <p class="text-secondary small mb-0">Software Engineering (XII PPLG 2)</p>
                            <p class="text-muted text-uppercase" style="font-size: 0.75rem;">SMKN 4 Kota Tasikmalaya</p>
                        </div>

                        <div>
                          <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill shadow-sm">
                               Kembali 
                          </a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>