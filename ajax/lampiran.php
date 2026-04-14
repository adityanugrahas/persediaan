<?php
include("../global/koneksi.php");
$d = $_REQUEST["d"] ?? '';

if ($d == "bmn_dist") { 
    $id_dist = $_REQUEST["id_dist"] ?? '';
    $data = db_fetch($bp, "SELECT lampiran, nama_lampiran FROM bmn_dist WHERE id_dist = :id", ['id' => $id_dist]);

    if (!$data) {
        echo "Data tidak ditemukan.";
        exit;
    }

    $file_path = "../lampiran/" . $data["lampiran"];
    $getExt = explode('.', $data["lampiran"]);
    $file_ext = strtolower(end($getExt));
    
    $lampiran_content = "";
    if (!empty($data["lampiran"]) && file_exists($file_path)) { 
        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $lampiran_content = "<img src='lampiran/" . htmlspecialchars($data['lampiran']) . "' class='img-fluid rounded shadow-sm' style='max-width: 100%;'>";
        } else {
            $lampiran_content = "<div class='alert alert-info'><i class='fa fa-file'></i> <a href='lampiran/" . htmlspecialchars($data['lampiran']) . "' target='_blank'>" . htmlspecialchars($data['nama_lampiran'] ?: $data['lampiran']) . "</a></div>";
        }
    } else {
        $lampiran_content = "<div class='text-center'><i class='fa fa-times-circle fa-4x text-danger'></i><h3 class='mt-3'>TIDAK ADA LAMPIRAN</h3></div>";
    }

    echo "
    <div id='custom-content' class='modal-block modal-block-lg modal-full-color modal-block-primary'>
    <section class='card'>
            <header class='card-header'>
                <h2 class='card-title'>Pratinjau Lampiran: " . htmlspecialchars($data['nama_lampiran'] ?: 'Data BMN') . "</h2>
            </header>
            <div class='card-body'>
                <div class='modal-wrapper'>
                    <div class='modal-text text-center'>
                        $lampiran_content                    
                    </div>
                </div>
            </div>
            <footer class='card-footer'>
                <div class='row'>
                    <div class='col-md-12 text-right'>
                        <button class='btn btn-outline-light modal-dismiss'>Tutup</button>
                    </div>
                </div>
            </footer>
        </section>
    </div>";
}
?>