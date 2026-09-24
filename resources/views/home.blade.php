<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RAJA CELL - Konter HP Tasikmalaya</title>
<meta name="description" content="RAJA CELL - Konter jual beli HP, aksesoris, pulsa & paket data di Cihideng, Tasikmalaya.">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'><path d='M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1.5a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-10a.5.5 0 0 0-.5-.5H11.5z'/></svg>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
<style>
    :root {
        --primary: #2563EB;
        --primary-dark: #1D4ED8;
        --navy: #1F2937;
        --muted: #6B7280;
        --bg-soft: #F8FAFC;
        --border-soft: #E2E8F0;
    }
    
    /* Mencegah halaman bisa digeser ke kanan-kiri (overflow horizontal) */
    html, body {
        max-width: 100%;
        overflow-x: hidden;
    }

    body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: var(--navy); background: #fff; }

    .navbar-brand-custom { font-weight: 700; font-size: 1.3rem; color: var(--navy) !important; }
    .nav-link-custom { color: var(--muted) !important; font-weight: 500; font-size: 0.95rem; }
    .nav-link-custom:hover { color: var(--primary) !important; }

    .hero { padding: 5.5rem 0 4.5rem; background: linear-gradient(180deg, #F8FAFC 0%, #ffffff 100%); }
    .hero-badge {
        background: rgba(37,99,235,0.08); color: var(--primary); font-weight: 600; font-size: 0.85rem;
        padding: 0.4rem 0.9rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .hero h1 { font-size: 2.75rem; font-weight: 800; line-height: 1.15; letter-spacing: -0.02em; }
    .hero p.lead { color: var(--muted); font-size: 1.1rem; max-width: 520px; }

    .btn-primary-custom {
        background: var(--primary); border: none; color: #fff; font-weight: 600;
        padding: 0.75rem 1.6rem; border-radius: 0.6rem; transition: background 0.15s ease;
    }
    .btn-primary-custom:hover { background: var(--primary-dark); color: #fff; }
    .btn-outline-custom {
        border: 1px solid var(--border-soft); color: var(--navy); font-weight: 600;
        padding: 0.75rem 1.6rem; border-radius: 0.6rem; background: #fff;
    }
    .btn-outline-custom:hover { background: var(--bg-soft); color: var(--navy); }

    .hero-visual {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); border-radius: 1.25rem;
        padding: 2.5rem; color: #fff; position: relative; overflow: hidden;
        box-shadow: 0 20px 40px rgba(37,99,235,0.25);
    }
    .hero-visual .bi { opacity: 0.15; position: absolute; font-size: 9rem; top: -1rem; right: -1rem; }

    .section-eyebrow { color: var(--primary); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; }
    .section-title { font-size: 1.9rem; font-weight: 700; margin-bottom: 0.75rem; letter-spacing: -0.01em; }
    .section-sub { color: var(--muted); max-width: 620px; }

    .service-card {
        border: 1px solid var(--border-soft); border-radius: 1rem; padding: 1.9rem; height: 100%;
        transition: box-shadow 0.15s ease, transform 0.15s ease;
    }
    .service-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.06); transform: translateY(-2px); }
    .service-icon {
        width: 48px; height: 48px; border-radius: 0.7rem; background: rgba(37,99,235,0.08); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1rem;
    }
    .service-card h5 { font-weight: 700; }
    .service-card p { color: var(--muted); font-size: 0.93rem; margin-bottom: 0; }

    .product-card {
        border: 1px solid var(--border-soft); border-radius: 1rem; overflow: hidden; height: 100%; background: #fff;
    }
    .product-photo {
        width: 100%; height: 160px; object-fit: cover; background: var(--bg-soft);
    }
    .product-photo-fallback {
        width: 100%; height: 160px; display: flex; align-items: center; justify-content: center;
        background: var(--bg-soft); color: #CBD5E1; font-size: 2.2rem;
    }
    .product-body { padding: 1rem 1.1rem; }
    .product-body h6 { font-weight: 700; margin-bottom: 0.25rem; font-size: 0.95rem; }
    .product-price { color: var(--primary); font-weight: 700; font-size: 0.95rem; }
    .badge-bestseller {
        background: #FEF3C7; color: #92400E; font-size: 0.72rem; font-weight: 700;
        padding: 0.3rem 0.6rem; border-radius: 50px;
    }

    .why-item { display: flex; gap: 1rem; align-items: flex-start; }
    .why-icon {
        width: 42px; height: 42px; border-radius: 50%; background: rgba(37,99,235,0.08); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;
    }
    .why-item h6 { font-weight: 700; margin-bottom: 0.2rem; }
    .why-item p { color: var(--muted); font-size: 0.9rem; margin-bottom: 0; }

    .contact-box { background: var(--navy); border-radius: 1.25rem; padding: 2.5rem; color: #fff; }
    .contact-box .info-row { display: flex; gap: 0.9rem; align-items: flex-start; margin-bottom: 1.3rem; }
    .contact-box .info-row i { font-size: 1.1rem; color: #93C5FD; margin-top: 0.2rem; }
    .contact-box .info-row .label { font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-bottom: 0.15rem; }
    .contact-box .info-row .value { font-weight: 600; }
    .map-frame { border-radius: 1.25rem; overflow: hidden; border: 1px solid var(--border-soft); min-height: 340px; }

    .social-btn {
        width: 42px; height: 42px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff;
        display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.1rem;
    }
    .social-btn:hover { background: rgba(255,255,255,0.2); color: #fff; }

    footer { border-top: 1px solid var(--border-soft); padding: 2.2rem 0; color: var(--muted); font-size: 0.88rem; }
    section { scroll-margin-top: 80px; }

    .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.is-visible { opacity: 1; transform: translateY(0); }
    @media (prefers-reduced-motion: reduce) {
        .reveal { opacity: 1; transform: none; transition: none; }
    }

    #backToTop {
        position: fixed; bottom: 24px; right: 24px; width: 46px; height: 46px; border-radius: 50%;
        background: var(--primary); color: #fff; border: none; display: none; align-items: center; justify-content: center;
        font-size: 1.2rem; box-shadow: 0 8px 20px rgba(37,99,235,0.35); z-index: 999; transition: opacity 0.2s ease;
    }
    #backToTop:hover { background: var(--primary-dark); }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-md navbar-light bg-white sticky-top border-bottom py-3">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-2" href="#top">
            <span class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                <i class="bi bi-shop"></i>
            </span>
            RAJA CELL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav align-items-md-center gap-md-4">
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#kontak">Kontak</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section id="top" class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <span class="hero-badge"><i class="bi bi-geo-alt"></i> Cihideng, Tasikmalaya</span>
                <h1 class="mt-3 mb-3">Konter HP terpercaya untuk kebutuhan Anda.</h1>
                <p class="lead mb-4">
                    RAJA CELL melayani jual beli handphone, aksesoris, hingga pulsa dan paket data
                    dengan harga bersahabat dan pelayanan ramah.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#produk" class="btn btn-primary-custom"><i class="bi bi-bag-check me-1"></i> Lihat Produk</a>
                    <a href="#kontak" class="btn btn-outline-custom">Hubungi Kami</a>
                </div>
            </div>
            <div class="col-lg-6 reveal">
                <div class="hero-visual">
                    <i class="bi bi-shop"></i>
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill mb-3">RAJA CELL</span>
                    <h3 class="fw-bold mb-2">Sedia HP, Aksesoris & Pulsa</h3>
                    <p class="text-white-50 mb-0">Satu tempat untuk semua kebutuhan gadget harian Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LAYANAN -->
<section id="layanan" class="py-5 my-4">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:600px;">
            <div class="section-eyebrow">Layanan Kami</div>
            <h2 class="section-title">Fokus pada dua hal, kami kerjakan dengan baik.</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5 reveal">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-phone"></i></div>
                    <h5>Jual Beli HP & Aksesoris</h5>
                    <p>Handphone baru maupun second, charger, casing, earphone, dan aksesoris pendukung lainnya.</p>
                </div>
            </div>
            <div class="col-md-5 reveal">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-wifi"></i></div>
                    <h5>Pulsa & Paket Data</h5>
                    <p>Isi ulang pulsa semua operator dan paket data internet, tersedia untuk pembelian di tempat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRODUK BEST SELLER / PENCARIAN PRODUK -->
<section id="produk" class="py-5 my-4">
    <div class="container">
        <div class="row justify-content-center mb-5 reveal">
            <div class="col-lg-8 text-center">
                {{-- Tambahkan id="sectionEyebrow" --}}
                <div class="section-eyebrow" id="sectionEyebrow">
                    Produk Favorit
                </div>
                {{-- Tambahkan id="sectionTitle" --}}
                <h2 class="section-title mb-1" id="sectionTitle">
                    Yang paling banyak dicari pelanggan kami.
                </h2>
                <p class="section-sub mx-auto mb-4" id="sectionDesc">
                Cari Produk yang Kamu mauu
            </p>
                
                {{-- Search Bar Live AJAX --}}
                <div class="d-flex justify-content-center">
                    <div class="w-100 mb-0" style="max-width: 400px;">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="text"
                                id="searchInputField"
                                class="form-control bg-light border-start-0 ps-0 shadow-none rounded-end-3"
                                placeholder="Ketik nama produk..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- WADAH PRODUK AJAX --}}
        <div id="product-container">
            @include('partials.product-grid')
        </div>
    </div>
</section>

<!-- KENAPA PILIH KAMI -->
<section class="py-5 my-4">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5 reveal">
                <div class="section-eyebrow">Kenapa RAJA CELL</div>
                <h2 class="section-title">Alasan pelanggan kembali lagi.</h2>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6 reveal">
                        <div class="why-item">
                            <div class="why-icon"><i class="bi bi-tag"></i></div>
                            <div><h6>Harga Bersahabat</h6><p>Harga jujur dan sepadan dengan kualitas barang.</p></div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal">
                        <div class="why-item">
                            <div class="why-icon"><i class="bi bi-patch-check"></i></div>
                            <div><h6>Barang Terjamin</h6><p>Setiap produk dicek dulu sebelum sampai ke pelanggan.</p></div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal">
                        <div class="why-item">
                            <div class="why-icon"><i class="bi bi-people"></i></div>
                            <div><h6>Pelayanan Ramah</h6><p>Dilayani langsung dengan santai, tidak terburu-buru.</p></div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal">
                        <div class="why-item">
                            <div class="why-icon"><i class="bi bi-geo-alt"></i></div>
                            <div><h6>Lokasi Mudah Dijangkau</h6><p>Berada di Cihideng, dekat dengan pemukiman warga.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KONTAK -->
<section id="kontak" class="py-5 my-4">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:600px;">
            <div class="section-eyebrow">Hubungi Kami</div>
            <h2 class="section-title">Datang langsung atau hubungi dulu.</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-5 reveal">
                <div class="contact-box h-100">
                    <div class="info-row">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <div class="label">Alamat</div>
                            <div class="value">Paseh, Gg. Sukawargi Desa No. RT 003/009, Tugujaya, Kec. Cihideng, Kab. Tasikmalaya, Jawa Barat 46126</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <i class="bi bi-whatsapp"></i>
                        <div>
                            <div class="label">WhatsApp / Telepon</div>
                            <div class="value">0812XXXXXXXX</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <i class="bi bi-clock-fill"></i>
                        <div>
                            <div class="label">Jam Operasional</div>
                            <div class="value">08.00 - 21.00 WIB</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="#" class="social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 reveal">
                <div class="map-frame">
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode('Paseh, Gg. Sukawargi Desa, Tugujaya, Kec. Cihideng, Kab. Tasikmalaya, Jawa Barat 46126') }}&output=embed"
                        width="100%" height="100%" style="border:0; min-height:340px;" allowfullscreen loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary text-white rounded-2 d-flex align-items-center justify-content-center" style="width:26px;height:26px;font-size:0.8rem;">
                <i class="bi bi-shop"></i>
            </span>
            <strong class="text-dark">RAJA CELL</strong>
            <a href="{{ route('login') }}" class="text-muted text-decoration-none ms-2" style="font-size: 0.75rem; opacity: 0.4;" title="Area Admin / Kasir">
                <i class="bi bi-lock-fill"></i> Login
            </a>
        </div>
        <div>&copy; {{ date('Y') }} RAJA CELL. Konter HP Cihideng, Tasikmalaya.</div>
    </div>
</footer>

<button id="backToTop" title="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>
<script>
    // 1. Script Animasi Scroll Reveal
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(el => observer.observe(el));

    // 2. Script Tombol Kembali ke Atas (Back to Top)
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTop.style.display = 'flex';
        } else {
            backToTop.style.display = 'none';
        }
    });
    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // 3. Script Live AJAX Search & Dynamic Title
    let searchTimeout;
    const searchInputField = document.getElementById('searchInputField');
    const sectionEyebrow = document.getElementById('sectionEyebrow');
    const sectionTitle = document.getElementById('sectionTitle');
    const sectionDesc = document.getElementById('sectionDesc');

    if (searchInputField) {
        searchInputField.addEventListener('input', function() {
            let keyword = this.value.trim();
            clearTimeout(searchTimeout);

            // Ubah judul secara live saat diketik
            if (keyword.length > 0) {
                sectionEyebrow.textContent = "Hasil Pencarian";
                sectionTitle.textContent = `Pencarian: "${keyword}"`;
                sectionDesc.textContent = "Menampilkan produk dari seluruh inventaris toko.";
            } else {
                sectionEyebrow.textContent = "Produk Favorit";
                sectionTitle.textContent = "Yang paling banyak dicari pelanggan kami.";
                sectionDesc.textContent = "Diambil langsung dari data penjualan toko, bukan daftar contoh.";
            }

            // Jeda 300ms untuk memuat data produk via AJAX secara mulus
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('home.search') }}?search=${encodeURIComponent(keyword)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('product-container').innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
            }, 300);
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>