<?php
/**
 * app/views/itinerary/builder-list.php
 * Daftar Itinerary Saya (Manual Builder)
 */
?>

<!-- Hero Section -->
<div style="background:linear-gradient(135deg,#0a0f1e 0%, #1a2a4a 50%, #0d2316 100%);padding:3.5rem 0 2.5rem;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(26,107,191,0.12) 1px, transparent 1px);background-size:28px 28px;pointer-events:none;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <nav aria-label="breadcrumb" class="hero-breadcrumb mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" style="color:rgba(255,255,255,0.5);font-size:0.85rem;text-decoration:none;">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/itinerary" style="color:rgba(255,255,255,0.5);font-size:0.85rem;text-decoration:none;">Itinerary</a></li>
                <li class="breadcrumb-item active" style="color:#fff;font-size:0.85rem;">Itinerary Saya</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(26,107,191,0.2);border:1px solid rgba(96,165,250,0.3);border-radius:30px;padding:0.25rem 0.9rem;font-size:0.75rem;font-weight:700;color:#60a5fa;letter-spacing:0.5px;text-transform:uppercase;margin-bottom:0.6rem;">
                    <i class="fas fa-tools"></i> MANUAL BUILDER
                </span>
                <h1 style="color:#fff;font-weight:800;font-size:2.2rem;margin-bottom:0.4rem;letter-spacing:-0.5px;">
                    Itinerary Saya
                </h1>
                <p style="color:rgba(255,255,255,0.65);font-size:0.95rem;margin:0;max-width:550px;">
                    Kelola dan atur jadwal rencana liburan ke Bogor secara interaktif dengan drag-and-drop.
                </p>
            </div>
            <div>
                <button type="button" class="btn" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);color:#fff;font-weight:700;padding:0.75rem 1.4rem;border-radius:12px;font-size:0.92rem;border:none;box-shadow:0 4px 18px rgba(26,107,191,0.35);" data-bs-toggle="modal" data-bs-target="#modalCreateItinerary">
                    <i class="fas fa-plus me-2"></i>Buat Itinerary Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($itineraries)): ?>
    <!-- Empty State -->
    <div class="text-center py-5" data-aos="fade-up" style="background:#fff;border-radius:24px;border:1px solid rgba(26,107,191,0.1);padding:3.5rem 1.5rem;box-shadow:0 4px 24px rgba(0,0,0,0.04);">
        <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,rgba(26,107,191,0.1),rgba(58,158,58,0.1));margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-route" style="font-size:2.2rem;color:#1a6bbf;"></i>
        </div>
        <h3 style="font-weight:800;color:#0f172a;font-size:1.4rem;">Belum Ada Itinerary Tersimpan</h3>
        <p style="color:#64748b;max-width:460px;margin:0.5rem auto 2rem;font-size:0.92rem;line-height:1.6;">
            Kamu belum membuat itinerary buatan sendiri. Mulai rancang jadwal liburan impianmu sekarang dengan drag & drop destinasi kesukaanmu!
        </p>
        <button type="button" class="btn" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);color:#fff;font-weight:700;padding:0.75rem 1.8rem;border-radius:30px;font-size:0.95rem;border:none;" data-bs-toggle="modal" data-bs-target="#modalCreateItinerary">
            <i class="fas fa-plus me-2"></i>Buat Sekarang
        </button>
    </div>

    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($itineraries as $idx => $itin): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 70 ?>">
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 4px 18px rgba(0,0,0,0.05);overflow:hidden;transition:all 0.3s ease;" class="h-100 d-flex flex-column" id="itin-card-<?= $itin['id'] ?>">
                <div style="padding:1.4rem;background:linear-gradient(135deg, #0d1529 0%, #0f2135 100%);position:relative;">
                    <span style="font-size:0.7rem;font-weight:700;color:#60a5fa;background:rgba(26,107,191,0.25);border:1px solid rgba(96,165,250,0.3);padding:0.2rem 0.65rem;border-radius:20px;display:inline-block;margin-bottom:0.5rem;">
                        <i class="fas fa-calendar-alt me-1"></i><?= (int)$itin['total_days'] ?: 1 ?> Hari Perjalanan
                    </span>
                    <h3 style="color:#fff;font-size:1.15rem;font-weight:800;margin:0 0 0.3rem;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($itin['title']) ?>">
                        <?= htmlspecialchars($itin['title']) ?>
                    </h3>
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.55);">
                        Diperbarui <?= date('d M Y, H:i', strtotime($itin['updated_at'])) ?>
                    </div>
                </div>

                <div style="padding:1.25rem;" class="flex-grow-1 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between p-2.5 mb-3" style="background:#f8fafc;border-radius:12px;border:1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt" style="color:#1a6bbf;font-size:1.1rem;"></i>
                            <span style="font-size:0.85rem;font-weight:700;color:#334155;"><?= (int)$itin['total_items'] ?> Destinasi</span>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#64748b;background:#e2e8f0;padding:0.2rem 0.6rem;border-radius:20px;">
                            Manual
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-2">
                        <a href="<?= BASE_URL ?>/itinerary/builder/<?= $itin['id'] ?>" class="btn w-100" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);color:#fff;font-weight:700;font-size:0.85rem;border-radius:10px;padding:0.55rem;">
                            <i class="fas fa-edit me-1.5"></i> Edit Itinerary (Builder)
                        </a>

                        <div class="d-flex gap-2">
                            <a href="<?= BASE_URL ?>/peta?itinerary_id=<?= $itin['id'] ?>" class="btn btn-outline-primary flex-fill" style="font-weight:700;font-size:0.8rem;border-radius:10px;padding:0.45rem;">
                                <i class="fas fa-map-marked-alt me-1"></i> Lihat Peta
                            </a>

                            <?php
                            $waText = "Halo! Ini Itinerary Liburan Bogor saya (" . htmlspecialchars($itin['title']) . "): " . BASE_URL . "/peta?itinerary_id=" . $itin['id'];
                            $waUrl  = "https://api.whatsapp.com/send?text=" . urlencode($waText);
                            ?>
                            <a href="<?= $waUrl ?>" target="_blank" class="btn btn-outline-success" style="font-weight:700;font-size:0.8rem;border-radius:10px;padding:0.45rem 0.75rem;" title="Bagikan via WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>

                            <button type="button" onclick="deleteItinerary(<?= $itin['id'] ?>)" class="btn btn-outline-danger" style="font-weight:700;font-size:0.8rem;border-radius:10px;padding:0.45rem 0.75rem;" title="Hapus Itinerary">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Create Itinerary -->
<div class="modal fade" id="modalCreateItinerary" tabindex="-1" aria-labelledby="modalCreateItineraryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.15);overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg, #0d1529 0%, #1a2a4a 100%);color:#fff;border:none;padding:1.25rem 1.5rem;">
                <h5 class="modal-title font-weight-bold" id="modalCreateItineraryLabel" style="font-weight:800;font-size:1.1rem;">
                    <i class="fas fa-plus-circle me-2" style="color:#60a5fa;"></i>Buat Itinerary Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/itinerary/builder/store" method="POST">
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="mb-3">
                        <label for="itineraryTitle" class="form-label" style="font-weight:700;color:#334155;font-size:0.88rem;">Nama / Judul Itinerary</label>
                        <input type="text" class="form-control" id="itineraryTitle" name="title" placeholder="Contoh: Liburan Keluarga Juni 3D2N" required style="border-radius:10px;padding:0.65rem 0.9rem;border:1.5px solid #cbd5e1;font-size:0.9rem;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem;background:#f8fafc;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;font-weight:600;">Batal</button>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);color:#fff;font-weight:700;border-radius:10px;padding:0.5rem 1.4rem;">
                        <i class="fas fa-arrow-right me-1"></i> Mulai Susun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteItinerary(id) {
    Swal.fire({
        title: 'Hapus Itinerary?',
        text: 'Itinerary ini beserta susunan perjalanannya akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('<?= BASE_URL ?>/itinerary/builder/' + id + '/delete', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('itin-card-' + id);
                if (card) {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.closest('.col-lg-4').remove();
                        const remaining = document.querySelectorAll('[id^="itin-card-"]').length;
                        if (remaining === 0) location.reload();
                    }, 400);
                }
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Itinerary dihapus', showConfirmButton:false, timer:1800 });
            } else {
                Swal.fire({ icon:'error', title:'Gagal', text: data.message || 'Gagal menghapus itinerary' });
            }
        })
        .catch(() => {
            Swal.fire({ icon:'error', title:'Error', text:'Terjadi kesalahan server.' });
        });
    });
}
</script>
