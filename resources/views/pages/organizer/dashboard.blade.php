@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="modern-dashboard-container py-4">
    <div class="container-fluid px-md-5">
        
        @if(session('success'))
            <div class="custom-alert-success mb-4 p-3 d-flex align-items-center animate__animated animate__fadeIn">
                <i class="fa-solid fa-circle-check mr-3 text-success"></i>
                <div class="text-white small"><strong>Berhasil:</strong> {{ session('success') }}</div>
            </div>
        @endif

        <div class="row">
            
            <div class="col-lg-9 col-12 mb-4 mb-lg-0">
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-slate-800">
                    <div>
                        <h1 class="dashboard-main-title mb-1">Pusat Kendali Promotor</h1>
                        <p class="dashboard-subtitle text-muted mb-0">Manajemen komparatif, distribusi tiket, dan status penjualan real-time.</p>
                    </div>
                    <div class="live-status-pill d-none d-sm-flex align-items-center">
                        <span class="pulse-dot mr-2"></span> 
                        <span class="text-white-50 font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">LIVE FEED</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <div class="mini-summary-card d-flex align-items-center p-3">
                            <div class="summary-icon-wrapper purple-bg mr-3"><i class="fa-solid fa-calendar-days"></i></div>
                            <div>
                                <div class="summary-label text-uppercase">Total Event Anda</div>
                                <div class="summary-counter text-white">{{ $totalEvent }} <span class="text-muted small font-weight-normal" style="font-size: 12px;">Terbit</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mini-summary-card d-flex align-items-center p-3">
                            <div class="summary-icon-wrapper green-bg mr-3"><i class="fa-solid fa-id-card-clip"></i></div>
                            <div>
                                <div class="summary-label text-uppercase">Status Lisensi</div>
                                <div class="summary-counter text-success" style="font-size: 16px; font-weight: 800;"><i class="fa-solid fa-circle-check mr-1"></i> VERIFIED PRO</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="catalog-section-header mb-4 mt-4.5">
                    <h2 class="section-title-label text-white mb-0">
                        <i class="fa-solid fa-layer-group text-purple mr-2"></i> Katalog Konser Aktif
                    </h2>
                </div>

                <div class="clean-grid-layout">
                    @forelse($myEvents as $event)
                        <div class="compact-promoter-card">
                            <div class="compact-card-media">
                                @if($event->images && $event->images->first())
                                    <img src="{{ asset($event->images->first()->image_path) }}" alt="{{ $event->title }}">
                                @else
                                    <div class="media-empty-placeholder"><i class="fa-solid fa-photo-film"></i></div>
                                @endif
                                <div class="card-media-overlay"></div>
                                <div class="floating-price-tag">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="compact-card-body p-3 d-flex flex-column justify-content-between">
                                <div class="mb-3">
                                    <h3 class="compact-title text-white mb-2 text-truncate-2" title="{{ $event->title }}">{{ $event->title }}</h3>
                                    <p class="compact-desc text-muted text-truncate-2 mb-0">{{ strip_tags($event->description) }}</p>
                                </div>

                                <div>
                                    <div class="compact-meta-box mb-3 p-2.5">
                                        <div class="meta-line text-truncate mb-1.5">
                                            <i class="fa-regular fa-calendar-days mr-2 text-purple"></i> 
                                            {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="meta-line text-truncate">
                                            <i class="fa-solid fa-location-dot mr-2 text-rose"></i> 
                                            {{ $event->location }}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column pt-3 compact-action-container">
                                        <div class="d-flex align-items-center mb-2" style="gap: 8px !important; width: 100%;">
                                            <a href="{{ route('organizer.events.show', $event->event_id) }}" class="btn-compact-action action-blue-manifes" title="Lihat Pembeli & Manifes">
                                                <i class="fa-solid fa-users-viewfinder"></i> Manifes
                                            </a>
                                            <a href="{{ route('organizer.events.edit', $event->event_id) }}" class="btn-compact-action action-yellow-edit" title="Atur Kategori & Harga Tiket">
                                                <i class="fa-solid fa-ticket"></i> Atur Tiket
                                            </a>
                                        </div>
                                        
                                        <form action="{{ route('organizer.events.destroy', $event->event_id) }}" method="POST" onsubmit="return confirm('⚠️ PERINGATAN KERAS!\nMenghapus event ini akan menghapus semua manifes dan tiket penonton yang sudah dibeli secara permanen.\n\nApakah Anda yakin ingin melanjutkan?');" class="w-100 m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-compact-action action-red-delete w-100" title="Hapus Event Permanen">
                                                <i class="fa-solid fa-trash-can"></i> Hapus Event
                                            </button>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="grid-empty-state py-5 text-center">
                            <div class="empty-state-card p-5 text-muted">
                                <i class="fa-regular fa-folder-open mb-3 d-block" style="font-size: 42px; color: #475569;"></i>
                                <h4 class="h6 font-weight-bold text-white mb-1">Belum Ada Event Kontrol</h4>
                                <p class="small mb-0 text-muted">Gunakan bilah menu kanan untuk menambahkan event baru Anda ke dalam sistem.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <div class="col-lg-3 col-12">
                <div class="sticky-sidebar-panel p-4">
                    
                    <div class="sidebar-section-title mb-3">
                        <span class="text-uppercase small font-weight-bold text-muted-glow" style="letter-spacing: 1px; font-size: 11px;">Menu Pintasan Fitur</span>
                    </div>

                    <a href="{{ route('organizer.events.create') }}" class="btn-sidebar-primary btn-block mb-4 p-3 text-center text-white d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-circle-plus mr-2 font-size-md"></i> + Buat Event Baru
                    </a>

                    <hr style="border-top: 1px solid rgba(255,255,255,0.08);" class="my-3">

                    <div class="sidebar-feature-box p-3 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="feature-box-icon text-primary mr-2.5"><i class="fa-solid fa-circle-info mt-0.5"></i></div>
                            <div>
                                <div class="feature-box-title text-white mb-1">Fitur Manifes</div>
                                <p class="feature-box-text text-muted mb-0">Klik tombol <span class="text-primary font-weight-bold">Manifes</span> di tiap kartu untuk memantau detail validasi data pembeli tiket konser Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-feature-box p-3">
                        <div class="d-flex align-items-start">
                            <div class="feature-box-icon text-warning mr-2.5"><i class="fa-solid fa-circle-exclamation mt-0.5"></i></div>
                            <div>
                                <div class="feature-box-title text-white mb-1">Fitur Atur Tiket</div>
                                <p class="feature-box-text text-muted mb-0">Klik tombol <span class="text-warning font-weight-bold">Atur Tiket</span> untuk mengubah kelas kategori bangku, harga, kuota stok, ataupun syarat masuk.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<style>
    /* Global Base Configuration */
    .modern-dashboard-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: transparent;
    }
    
    .border-slate-800 { border-color: rgba(51, 65, 85, 0.5) !important; }

    /* Dashboard Header & Typography Typography Upgrade */
    .dashboard-main-title { 
        font-weight: 800; 
        font-size: 28px; 
        letter-spacing: -0.75px; 
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .dashboard-subtitle { font-size: 13px; color: #94a3b8 !important; font-weight: 400; }
    .section-title-label { font-size: 18px; font-weight: 700; letter-spacing: -0.3px; }

    /* Live Feed Badge Component */
    .live-status-pill {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 6px 14px;
        border-radius: 30px;
        backdrop-blur: 4px;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        animation: pulseAnimation 2s infinite;
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Top Summary Overview Widgets */
    .mini-summary-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        transition: border-color 0.3s ease;
    }
    .mini-summary-card:hover { border-color: rgba(168, 85, 247, 0.35); }
    .summary-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .purple-bg { background: rgba(168, 85, 247, 0.14); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.2); }
    .green-bg { background: rgba(16, 185, 129, 0.14); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }
    .summary-label { font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; margin-bottom: 2px; }
    .summary-counter { font-size: 20px; font-weight: 800; letter-spacing: -0.5px; }

    /* Ultra Responsive CSS Grid System */
    .clean-grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 22px;
        width: 100%;
    }
    .grid-empty-state { grid-column: 1 / -1; }

    /* Premium Modern Dark Card Module */
    .compact-promoter-card {
        background: #111a2e;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1), border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .compact-promoter-card:hover {
        transform: translateY(-6px);
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.5), 0 0 20px rgba(99, 102, 241, 0.15);
    }

    /* Thumbnail Media & Price Layer */
    .compact-card-media {
        position: relative;
        height: 155px;
        width: 100%;
        overflow: hidden;
        background: #090f1d;
    }
    .compact-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .compact-promoter-card:hover .compact-card-media img {
        transform: scale(1.06);
    }
    .card-media-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, #111a2e 0%, rgba(17, 26, 46, 0.4) 40%, transparent 100%);
    }
    .floating-price-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(6px);
        color: #38bdf8;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 800;
        border-radius: 30px;
        border: 1px solid rgba(56, 189, 248, 0.2);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    /* Card Details Structure */
    .compact-card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 18px !important;
    }
    .compact-title { 
        font-size: 14.5px; 
        font-weight: 700; 
        line-height: 1.45; 
        min-height: 42px;
        transition: color 0.2s ease;
    }
    .compact-promoter-card:hover .compact-title { color: #38bdf8 !important; }
    .compact-desc { font-size: 12px; color: #94a3b8 !important; line-height: 1.5; min-height: 36px; }
    
    /* Metadata Box */
    .compact-meta-box {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 10px;
    }
    .meta-line { font-size: 11px; color: #cbd5e1; font-weight: 500; display: flex; align-items: center; }
    .text-purple { color: #c084fc !important; }
    .text-rose { color: #f43f5e !important; }

    /* UPGRADED SPACING: Memberikan padding bawah seimbang agar tidak mepet lengkungan luar */
    .compact-action-container { 
        border-top: 1px solid rgba(255, 255, 255, 0.06); 
        padding-top: 14px !important;
        padding-bottom: 4px !important;
        width: 100%;
    }
    
    .btn-compact-action {
        flex: 1; 
        padding: 10px 4px;
        font-size: 11.5px;
        font-weight: 700;
        text-align: center;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }
    .btn-compact-action:hover { 
        transform: translateY(-2px); 
    }
    .btn-compact-action:active {
        transform: translateY(0);
    }
    
    /* Desain Tombol Manifes Premium */
    .action-blue-manifes { 
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); 
        color: #ffffff !important; 
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .action-blue-manifes:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Desain Tombol Atur Tiket Premium */
    .action-yellow-edit { 
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
        color: #090f1d !important; 
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
    }
    .action-yellow-edit:hover {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.35);
    }

    /* Desain Tombol Hapus Premium Red */
    .action-red-delete {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }
    .action-red-delete:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
    }

    /* Sidebar Area Wrapper styling */
    .sticky-sidebar-panel {
        background: rgba(22, 32, 51, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        position: sticky;
        top: 24px;
        backdrop-filter: blur(8px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    .text-muted-glow { color: #64748b; font-weight: 700; letter-spacing: 0.5px; }
    
    /* Creative Sidebar Call To Action Button */
    .btn-sidebar-primary {
        background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
        transition: all 0.25s ease;
        text-decoration: none !important;
    }
    .btn-sidebar-primary:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 20px rgba(124, 58, 237, 0.5); 
        color: #fff !important; 
    }

    /* Shortcut Descriptive Feature Widget Boxes */
    .sidebar-feature-box {
        background: #111a2e;
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 12px;
    }
    .feature-box-icon { font-size: 13px; }
    .feature-box-title { font-size: 12px; font-weight: 700; letter-spacing: -0.1px; }
    .feature-box-text { font-size: 11px; color: #94a3b8 !important; line-height: 1.5; }

    /* Miscs Components */
    .empty-state-card { background: #111a2e; border: 1px dashed rgba(255,255,255,0.1); border-radius: 14px; }
    .custom-alert-success { background: #10b981; border-radius: 10px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.2); }
    
    /* Multiline Utility Overflow Ellipsis */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .media-empty-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #334155;
    }
</style>
@endsection