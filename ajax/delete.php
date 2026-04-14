<?php
/**
 * AJAX Delete Confirmation Modal
 * Premium Glassmorphism Overhaul
 */
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$d  = htmlspecialchars($_REQUEST["d"] ?? '');
$id = htmlspecialchars($_REQUEST["id"] ?? '');

/**
 * Helper to render a high-fidelity delete confirmation modal
 */
function render_delete_modal(string $title, string $message, string $delete_url): void {
    echo <<<HTML
    <div id="custom-content" class="modal-block modal-block-md">
        <section class="card card-modern animate__animated animate__zoomIn">
            <header class="card-header" style="background: rgba(239, 68, 68, 0.1) !important;">
                <div class="card-actions">
                    <button class="btn btn-dark btn-sm modal-dismiss" title="Close">X</button>
                </div>
                <h2 class="card-title text-danger"><i class="fas fa-trash-alt mr-2"></i> Konfirmasi Hapus</h2>
            </header>
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger-transparent mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-exclamation-triangle text-danger fa-2x animate__animated animate__pulse animate__infinite"></i>
                    </div>
                </div>
                <h3 class="font-weight-bold text-white mb-2" style="letter-spacing:-0.03em;">Hapus Data Ini?</h3>
                <p class="text-muted mb-4 px-4">Tindakan ini bersifat permanen. Data berikut akan dihapus dari sistem:</p>
                <div class="p-4 bg-dark-subtle border-radius-md mb-4" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 12px;">
                    <h4 class="text-white font-weight-bold m-0" style="word-break: break-all;">{$message}</h4>
                </div>

                <div class="row pt-3">
                    <div class="col-6">
                        <button class="btn btn-dark btn-block modal-dismiss" style="border-radius: 30px; height: 50px; font-weight:700;">BATAL</button>
                    </div>
                    <div class="col-6">
                        <a href="{$delete_url}" class="btn btn-danger btn-block" style="border-radius: 30px; height: 50px; font-weight:700; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);">
                            PROSES HAPUS
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
HTML;
}

if ($d === "petugas") {
    $data = db_fetch($bp, "SELECT * FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal("Hapus Petugas", htmlspecialchars($data['nama']), "?p=delete&tab=petugas&id=" . urlencode($data['id_petugas']));
    }
}
elseif ($d === "bagian") {
    $data = db_fetch($bp, "SELECT id_seksi, bagian FROM bagian WHERE id_seksi = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal("Hapus Bagian", htmlspecialchars($data['bagian']), "?p=delete&tab=bagian&id=" . urlencode($data['id_seksi']));
    }
}
elseif ($d === "kat") {
    $data = db_fetch($bp, "SELECT id_kat, nama_kat FROM kategori WHERE id_kat = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal("Hapus Kategori", htmlspecialchars($data['nama_kat']), "?p=delete&tab=kat&id=" . urlencode($data['id_kat']));
    }
}
elseif ($d === "cart") {
    $data = db_fetch($bp, "SELECT i.id_inout, i.jml_req, i.satuan, b.nama_barang 
                           FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                           WHERE i.id_inout = :id", ['id' => $id]);
    if ($data) {
        $msg = htmlspecialchars($data['jml_req'] . " " . $data['satuan'] . " " . $data['nama_barang']);
        render_delete_modal("Hapus dari Keranjang", $msg, "?p=delete&tab=cart&id=" . urlencode($id));
    }
}
elseif ($d === "anggaran") {
    $data = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);
    if ($data) {
        $msg = htmlspecialchars($data['akun_anggaran'] . " (" . $data['ket_anggaran'] . ")");
        render_delete_modal("Hapus Anggaran", $msg, "?p=delete&tab=anggaran&id=" . urlencode($id) . "&ta=" . urlencode($data['tahun_anggaran']));
    }
}
elseif ($d === "anggaran_ta") {
    $ta = htmlspecialchars($_REQUEST["ta"] ?? '');
    render_delete_modal("Hapus Tahun Anggaran", "Tahun Anggaran " . $ta, "?p=delete&tab=anggaran_ta&ta=" . urlencode($ta));
}
elseif ($d === "kat_bmn") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal("Hapus Kategori BMN", $nm, "?p=delete&tab=kat_bmn&id=" . urlencode($id));
}
elseif ($d === "bmn") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal("Hapus BMN", $nm, "?p=delete&tab=bmn&id=" . urlencode($id));
}
elseif ($d === "bmn_dist") {
    $id_dist = htmlspecialchars($_REQUEST["id_dist"]);
    $id_bmn  = htmlspecialchars($_REQUEST["id_bmn"]);
    $kat     = htmlspecialchars($_REQUEST["kat"]);
    $bmn     = htmlspecialchars($_REQUEST["bmn"] ?? '');
    $sek     = htmlspecialchars($_REQUEST["sek"] ?? '');
    $p       = htmlspecialchars($_REQUEST["p"] ?? '');
    $jum     = htmlspecialchars($_REQUEST["jum"] ?? '');
    $msg     = "{$bmn} ({$jum}) untuk {$sek}";
    render_delete_modal("Hapus Distribusi BMN", $msg, "?p=delete&tab=bmn_dist&id_dist=" . urlencode($id_dist) . "&kat=" . urlencode($kat) . "&id_bmn=" . urlencode($id_bmn));
}
elseif ($d === "pesanan") {
    render_delete_modal("Hapus Pesanan", "Pengajuan Pesanan Barang", "?p=delete&tab=pesanan&id=" . urlencode($id));
}
elseif ($d === "stok") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal("Nonaktifkan Barang", $nm, "?p=delete&tab=stok&id=" . urlencode($id));
}
else {
    echo "<div class='p-5 text-center text-muted'>Invalid delete request context.</div>";
}

require_once("../global/ajax_footer.php");
?>