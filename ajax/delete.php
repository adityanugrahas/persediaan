<?php
/**
 * AJAX Delete Confirmation Modal
 * Migrated to PDO with prepared statements
 */
include("../global/koneksi.php");

$d  = htmlspecialchars($_REQUEST["d"]);
$id = htmlspecialchars($_REQUEST["id"] ?? '');

/**
 * Helper to render a delete confirmation modal
 */
function render_delete_modal(string $title, string $message, string $delete_url): void {
    echo <<<HTML
    <div id='custom-content' class='modal-block modal-full-color modal-block-danger'>
    <section class='card'>
        <header class='card-header'>
            <h2 class='card-title'>Konfirmasi Hapus</h2>
        </header>
        <div class='card-body'>
            <div class='modal-wrapper'>
                <div class='modal-icon'>
                    <i class='fas fa-exclamation-triangle'></i>
                </div>
                <div class='modal-text'>
                    <p>Apakah Anda yakin menghapus data:</p>
                    <h4>{$message}</h4>
                </div>
            </div>
        </div>
        <footer class='card-footer'>
            <div class='row'>
                <div class='col-md-12 text-right'>
                    <button class='btn btn-default modal-dismiss'>Batal</button>
                    <a href='{$delete_url}' class='btn btn-danger'><i class='fas fa-trash'></i> Hapus</a>
                </div>
            </div>
        </footer>
    </section>
    </div>
HTML;
}

if ($d === "petugas") {
    $data = db_fetch($bp, "SELECT * FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal(
            "Hapus Petugas",
            htmlspecialchars($data['nama']),
            "?p=delete&tab=petugas&id=" . urlencode($data['id_petugas'])
        );
    }
}
elseif ($d === "bagian") {
    $data = db_fetch($bp, "SELECT id_seksi, bagian FROM bagian WHERE id_seksi = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal(
            "Hapus Bagian",
            htmlspecialchars($data['bagian']),
            "?p=delete&tab=bagian&id=" . urlencode($data['id_seksi'])
        );
    }
}
elseif ($d === "kat") {
    $data = db_fetch($bp, "SELECT id_kat, nama_kat FROM kategori WHERE id_kat = :id", ['id' => $id]);
    if ($data) {
        render_delete_modal(
            "Hapus Kategori",
            htmlspecialchars($data['nama_kat']),
            "?p=delete&tab=kat&id=" . urlencode($data['id_kat'])
        );
    }
}
elseif ($d === "cart") {
    $data = db_fetch($bp, "SELECT i.id_inout, i.jml_req, i.satuan, b.nama_barang 
                           FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                           WHERE i.id_inout = :id", ['id' => $id]);
    if ($data) {
        $msg = htmlspecialchars($data['jml_req'] . " " . $data['satuan'] . " " . $data['nama_barang']) . " dari Keranjang?";
        render_delete_modal("Hapus dari Keranjang", $msg, "?p=delete&tab=cart&id=" . urlencode($id));
    }
}
elseif ($d === "anggaran") {
    $data = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);
    if ($data) {
        $msg = htmlspecialchars($data['akun_anggaran'] . " (" . $data['ket_anggaran'] . ")");
        render_delete_modal(
            "Hapus Anggaran",
            $msg,
            "?p=delete&tab=anggaran&id=" . urlencode($id) . "&ta=" . urlencode($data['tahun_anggaran'])
        );
    }
}
elseif ($d === "anggaran_ta") {
    $ta = htmlspecialchars($_REQUEST["ta"]);
    render_delete_modal(
        "Hapus Tahun Anggaran",
        "Tahun Anggaran " . $ta,
        "?p=delete&tab=anggaran_ta&ta=" . urlencode($ta)
    );
}
elseif ($d === "kat_bmn") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal(
        "Hapus Kategori BMN",
        $nm,
        "?p=delete&tab=kat_bmn&id=" . urlencode($id)
    );
}
elseif ($d === "bmn") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal(
        "Hapus BMN",
        $nm,
        "?p=delete&tab=bmn&id=" . urlencode($id)
    );
}
elseif ($d === "bmn_dist") {
    $id_dist = htmlspecialchars($_REQUEST["id_dist"]);
    $id_bmn  = htmlspecialchars($_REQUEST["id_bmn"]);
    $kat     = htmlspecialchars($_REQUEST["kat"]);
    $bmn     = htmlspecialchars($_REQUEST["bmn"] ?? '');
    $sek     = htmlspecialchars($_REQUEST["sek"] ?? '');
    $p       = htmlspecialchars($_REQUEST["p"] ?? '');
    $jum     = htmlspecialchars($_REQUEST["jum"] ?? '');
    $msg     = "{$bmn} sebanyak {$jum} untuk {$sek} ({$p})";
    render_delete_modal(
        "Hapus Distribusi BMN",
        $msg,
        "?p=delete&tab=bmn_dist&id_dist=" . urlencode($id_dist) . "&kat=" . urlencode($kat) . "&id_bmn=" . urlencode($id_bmn)
    );
}
elseif ($d === "pesanan") {
    render_delete_modal(
        "Hapus Pesanan",
        "Apakah Anda yakin?",
        "?p=delete&tab=pesanan&id=" . urlencode($id)
    );
}
elseif ($d === "stok") {
    $nm = htmlspecialchars($_REQUEST["nm"] ?? '');
    render_delete_modal(
        "Nonaktifkan Barang",
        $nm,
        "?p=delete&tab=stok&id=" . urlencode($id)
    );
}
?>