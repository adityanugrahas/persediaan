<?php
/**
 * Process: Add Data — All insert operations
 * Fully migrated to PDO prepared statements
 */

if (!empty($_GET['tab'])) {
    $tab = htmlspecialchars($_GET['tab']);
} else {
    $tab = '';
}

switch ($tab) {
    case "petugas":       petugas(); break;
    case "bagian":        bagian(); break;
    case "barang":        barang(); break;
    case "order":         order(); break;
    case "pesan":         pesan(); break;
    case "stok_add":      stok_add(); break;
    case "anggaran_add":  anggaran_add(); break;
    case "pagu_add":      pagu_add(); break;
    case "bmn_kat_add":   bmn_kat_add(); break;
    case "bmn":           bmn(); break;
    case "bmn_dist":      bmn_dist(); break;
    case "bmn_kembali":   bmn_kembali(); break;
    case "kat_add":       kat_add(); break;
    default:              echo '{"failure":true}'; break;
}


/* ========================================================================
   ADD PETUGAS
   ======================================================================== */
function petugas()
{
    require("global/koneksi.php");

    $id        = date("ymdhis");
    $users_id  = trim($_POST["users_id"]);
    $pwd       = password_safe_hash(trim($_POST["pwd"]));
    $nama      = trim($_POST["nama"]);
    $seksi     = $_POST["seksi"];
    $level     = $_POST["level"];
    $status    = $_POST["status"];

    // Get next urutan
    $ur     = db_fetch($bp, "SELECT COALESCE(MAX(urutan), 0) AS urutan FROM petugas");
    $urutan = ($ur['urutan'] ?? 0) + 1;

    // Handle photo upload
    $filename = handle_upload('photo', 'img/users/', $id, 800);

    $stmt = $bp->prepare("INSERT INTO petugas (id_petugas, users_id, pwd, nama, seksi, p_level, photo, urutan, p_status)
                          VALUES (:id, :users_id, :pwd, :nama, :seksi, :level, :photo, :urutan, :status)");
    $result = $stmt->execute([
        'id'       => $id,
        'users_id' => $users_id,
        'pwd'      => $pwd,
        'nama'     => $nama,
        'seksi'    => $seksi,
        'level'    => $level,
        'photo'    => $filename,
        'urutan'   => $urutan,
        'status'   => $status,
    ]);

    redirect($result ? "?p=petugas&m=addok" : "?p=petugas&m=addgagal");
}


/* ========================================================================
   ADD BAGIAN
   ======================================================================== */
function bagian()
{
    require("global/koneksi.php");

    $id    = date("ymdhis");
    $bagian = trim($_POST["bagian"]);
    $ket   = trim($_POST["ket"]);
    $induk = $_POST["induk"];

    $urut = db_fetch($bp, "SELECT COALESCE(MAX(urutan), 0) AS urutan FROM bagian");
    $urutan_val = ($urut['urutan'] ?? 0) + 1;

    $stmt = $bp->prepare("INSERT INTO bagian (id_seksi, induk, bagian, keterangan, urutan)
                          VALUES (:id, :induk, :bagian, :ket, :urutan)");
    $result = $stmt->execute([
        'id'     => $id,
        'induk'  => $induk,
        'bagian' => $bagian,
        'ket'    => $ket,
        'urutan' => $urutan_val,
    ]);

    redirect($result ? "?p=bagian&m=addok" : "?p=bagian&m=addgagal");
}


/* ========================================================================
   ADD ANGGARAN
   ======================================================================== */
function anggaran_add()
{
    require("global/koneksi.php");

    $id       = date("ymdhis");
    $kode     = date("ymd.Hi");
    $tgl_ok   = date("Y-m-d H:i:s");
    $pemroses = $_SESSION["idp"];
    $ta       = trim($_POST["ta"]);
    $akun     = trim($_POST["akun"]);
    $ket_akun = trim($_POST["ket_akun"]);
    $pagu     = (int)$_POST["pagu"];

    // Handle attachment
    $filename = handle_upload('lampiran', './lampiran/', $id, 500);

    // Insert anggaran
    $stmt1 = $bp->prepare("INSERT INTO anggaran (id_anggaran, tahun_anggaran, akun_anggaran, ket_anggaran, pagu_anggaran, serapan_anggaran, status)
                           VALUES (:id, :ta, :akun, :ket, :pagu, 0, 1)");
    $stmt1->execute([
        'id'   => $id,
        'ta'   => $ta,
        'akun' => $akun,
        'ket'  => $ket_akun,
        'pagu' => $pagu,
    ]);

    // Insert nota record
    $stmt2 = $bp->prepare("INSERT INTO nota (id_nota, kode_nota, jenis, id_anggaran, tanggal, pemroses, jum_out, jum_in, keterangan, lampiran, status)
                           VALUES (:id, :kode, 'pagu', :id_ang, :tgl, :pemroses, 0, :pagu, 'penambahan pagu', :lampiran, 'ok')");
    $stmt2->execute([
        'id'       => $id,
        'kode'     => $kode,
        'id_ang'   => $id,
        'tgl'      => $tgl_ok,
        'pemroses' => $pemroses,
        'pagu'     => $pagu,
        'lampiran' => $filename,
    ]);

    redirect("?p=anggaran&m=addok");
}


/* ========================================================================
   ADD PAGU (additional pagu to existing anggaran)
   ======================================================================== */
function pagu_add()
{
    require("global/koneksi.php");

    $id           = date("ymdhis");
    $kode_nota    = trim($_POST["kode"]);
    $jenis        = "Tambah Pagu";
    $id_anggaran  = $_POST["id_anggaran"];
    $tanggal      = $_POST["tanggal"];
    $pemroses     = $_SESSION["idp"];
    $jum_in       = (int)$_POST["pagu_tambahan"];
    $ket          = trim($_POST["keterangan"]);

    // Get current pagu
    $sta  = db_fetch($bp, "SELECT tahun_anggaran, pagu_anggaran FROM anggaran WHERE id_anggaran = :id", ['id' => $id_anggaran]);
    $ta   = $sta['tahun_anggaran'];
    $pagu = $sta['pagu_anggaran'] + $jum_in;

    $filename = handle_upload('lampiran', './lampiran/', $id, 500);

    // Insert nota
    $stmt = $bp->prepare("INSERT INTO nota (id_nota, kode_nota, jenis, id_anggaran, tanggal, pemroses, jum_in, keterangan, lampiran, status)
                          VALUES (:id, :kode, :jenis, :id_ang, :tgl, :pemroses, :jum_in, :ket, :lampiran, 'ok')");
    $stmt->execute([
        'id'       => $id,
        'kode'     => $kode_nota,
        'jenis'    => $jenis,
        'id_ang'   => $id_anggaran,
        'tgl'      => $tanggal,
        'pemroses' => $pemroses,
        'jum_in'   => $jum_in,
        'ket'      => $ket,
        'lampiran' => $filename,
    ]);

    // Update pagu
    $bp->prepare("UPDATE anggaran SET pagu_anggaran = :pagu WHERE id_anggaran = :id")
       ->execute(['pagu' => $pagu, 'id' => $id_anggaran]);

    redirect("?p=anggaran_ta&ta=" . urlencode($ta) . "&m=addok");
}


/* ========================================================================
   ADD BARANG (stok_barang)
   ======================================================================== */
function barang()
{
    require("global/koneksi.php");

    $id   = date("ymdhis");
    $ps   = $_POST["ps"] ?? '';
    $tgl  = date("Y-m-d H:i:s");

    $kategori     = $_POST["kategori"];
    $nama_barang  = trim($_POST["nama_barang"]);
    $satuan       = trim($_POST["satuan"]);
    $stok_minimal = (int)$_POST["stok_minimal"];
    $harga        = (int)$_POST["harga"];
    $keterangan   = trim($_POST["keterangan"]);

    $filename = handle_upload('lampiran', './img/barang/', $id, 400);

    $stmt = $bp->prepare("INSERT INTO stok_barang (id_barang, kategori, nama_barang, satuan, stok_minimal, harga_satuan, keterangan, gambar)
                          VALUES (:id, :kat, :nama, :sat, :min, :harga, :ket, :gambar)");
    $stmt->execute([
        'id'    => $id,
        'kat'   => $kategori,
        'nama'  => $nama_barang,
        'sat'   => $satuan,
        'min'   => $stok_minimal,
        'harga' => $harga,
        'ket'   => $keterangan,
        'gambar'=> $filename,
    ]);

    $redir = "stok";

    // If this was for a pesanan, link it
    if (!empty($ps)) {
        $psn = db_fetch($bp, "SELECT * FROM pesanan WHERE id_pesanan = :id", ['id' => $ps]);
        if ($psn) {
            $bp->prepare("INSERT INTO notif (id_notif, tgl_notif, id_seksi, id_petugas, id_barang, status, keterangan)
                          VALUES (:id, :tgl, :seksi, :ptg, :brg, '0', :ket)")
               ->execute([
                   'id'    => $id,
                   'tgl'   => $tgl,
                   'seksi' => $psn['id_seksi'],
                   'ptg'   => $psn['petugas'],
                   'brg'   => $id,
                   'ket'   => "Barang {$psn['nama_barang']} telah terdaftar di sistem dengan nama {$nama_barang}",
               ]);

            $bp->prepare("UPDATE pesanan SET id_barang = :brg, nama_barang = :nama, ket_barang = :ket, satuan = :sat WHERE id_pesanan = :id")
               ->execute([
                   'brg'  => $id,
                   'nama' => $nama_barang,
                   'ket'  => $keterangan,
                   'sat'  => $satuan,
                   'id'   => $ps,
               ]);
            $redir = "pesanan";
        }
    }

    redirect("?p={$redir}&m=addok");
}


/* ========================================================================
   ADD ORDER (stok_inout - request out)
   ======================================================================== */
function order()
{
    require("global/koneksi.php");

    $id          = date("ymdhis");
    $tgl         = date("Y-m-d H:i:s");
    $jenis       = $_POST["jenis"];
    $id_petugas  = $_POST["id_petugas"];
    $id_seksi    = $_POST["id_seksi"];
    $id_barang   = $_POST["id_barang"];
    $jum_req     = (int)$_POST["jum_req"];
    $satuan      = trim($_POST["satuan"]);
    $jum_stok    = (int)$_POST["jum_stok"];
    $keterangan  = trim($_POST["keterangan"]);

    $stmt = $bp->prepare("INSERT INTO stok_inout (id_inout, jenis, petugas, id_seksi, tgl, id_barang, jml_req, satuan, jml_stok, keterangan, status)
                          VALUES (:id, :jenis, :ptg, :seksi, :tgl, :brg, :req, :sat, :stok, :ket, '0')");
    $result = $stmt->execute([
        'id'    => $id,
        'jenis' => $jenis,
        'ptg'   => $id_petugas,
        'seksi' => $id_seksi,
        'tgl'   => $tgl,
        'brg'   => $id_barang,
        'req'   => $jum_req,
        'sat'   => $satuan,
        'stok'  => $jum_stok,
        'ket'   => $keterangan,
    ]);

    redirect($result ? "?p=stok&m=addok" : "?p=stok&m=addgagal");
}


/* ========================================================================
   ADD PESAN (pesanan - item request/order)
   ======================================================================== */
function pesan()
{
    require("global/koneksi.php");

    $id          = date("ymdhis");
    $tgl         = date("Y-m-d H:i:s");
    $id_petugas  = $_POST["id_petugas"];
    $id_seksi    = $_POST["id_seksi"];
    $id_barang   = !empty($_POST["id_barang"]) ? $_POST["id_barang"] : "baru";
    $nama_barang = trim($_POST["nama_barang"]);
    $ket_barang  = trim($_POST["ket_barang"]);
    $jum_pes     = (int)$_POST["jum_pes"];
    $satuan      = trim($_POST["satuan"]);
    $keterangan  = trim($_POST["keterangan"]);

    $filename = handle_upload('lampiran', './lampiran/', $id, 400);

    $stmt = $bp->prepare("INSERT INTO pesanan (id_pesanan, id_seksi, petugas, id_barang, nama_barang, ket_barang, jumlah, satuan, tgl_pesan, keterangan, lampiran, jumlah_stok)
                          VALUES (:id, :seksi, :ptg, :brg, :nama, :ket_brg, :jum, :sat, :tgl, :ket, :lamp, 0)");
    $result = $stmt->execute([
        'id'      => $id,
        'seksi'   => $id_seksi,
        'ptg'     => $id_petugas,
        'brg'     => $id_barang,
        'nama'    => $nama_barang,
        'ket_brg' => $ket_barang,
        'jum'     => $jum_pes,
        'sat'     => $satuan,
        'tgl'     => $tgl,
        'ket'     => $keterangan,
        'lamp'    => $filename,
    ]);

    redirect($result ? "?p=stok&m=addok" : "?p=stok&m=addgagal");
}


/* ========================================================================
   ADD BMN KATEGORI
   ======================================================================== */
function bmn_kat_add()
{
    require("global/koneksi.php");

    $id     = date("ymdhis");
    $editor = $_SESSION["idp"];
    $nama   = trim($_POST["nama_kat"]);
    $ket    = trim($_POST["ket"]);

    $stmt = $bp->prepare("INSERT INTO kat_bmn (id_kat, editor, nama_kat, ket_kat, status)
                          VALUES (:id, :editor, :nama, :ket, 'ON')");
    $result = $stmt->execute([
        'id'     => $id,
        'editor' => $editor,
        'nama'   => $nama,
        'ket'    => $ket,
    ]);

    redirect($result ? "?p=bmn_kat&m=addok" : "?p=bmn_kat&m=addgagal");
}


/* ========================================================================
   ADD STOK (stok_inout - stock in)
   ======================================================================== */
function stok_add()
{
    require("global/koneksi.php");

    $id          = date("ymdhis");
    $tgl         = date("Y-m-d H:i:s");
    $jenis       = $_POST["jenis"];
    $id_petugas  = $_POST["id_petugas"];
    $id_seksi    = $_POST["id_seksi"];
    $id_barang   = $_POST["id_barang"];
    $jml_in      = (int)$_POST["jml_in"];
    $satuan      = trim($_POST["satuan"]);
    $harga       = (int)$_POST["harga"];
    $jum_stok    = (int)$_POST["jum_stok"];
    $keterangan  = trim($_POST["keterangan"]);

    $stmt = $bp->prepare("INSERT INTO stok_inout (id_inout, jenis, petugas, id_seksi, tgl, id_barang, jml_in, satuan, harga, jml_stok, keterangan, status)
                          VALUES (:id, :jenis, :ptg, :seksi, :tgl, :brg, :jml_in, :sat, :harga, :stok, :ket, '0')");
    $result = $stmt->execute([
        'id'     => $id,
        'jenis'  => $jenis,
        'ptg'    => $id_petugas,
        'seksi'  => $id_seksi,
        'tgl'    => $tgl,
        'brg'    => $id_barang,
        'jml_in' => $jml_in,
        'sat'    => $satuan,
        'harga'  => $harga,
        'stok'   => $jum_stok,
        'ket'    => $keterangan,
    ]);

    redirect($result ? "?p=stok&m=addok" : "?p=stok&m=addgagal");
}


/* ========================================================================
   ADD KATEGORI (stok_barang categories)
   ======================================================================== */
function kat_add()
{
    require("global/koneksi.php");

    $id     = date("ymdhis");
    $editor = $_SESSION["idp"];
    $nama   = trim($_POST["nama_kat"]);
    $ket    = trim($_POST["ket"]);
    $idb    = $_REQUEST["idb"] ?? '';
    $pg     = $_REQUEST["pg"] ?? '';
    $fwd    = !empty($pg) ? "{$pg}&id={$idb}" : "kat";

    $stmt = $bp->prepare("INSERT INTO kategori (id_kat, editor, nama_kat, ket_kat, status)
                          VALUES (:id, :editor, :nama, :ket, 'ON')");
    $result = $stmt->execute([
        'id'     => $id,
        'editor' => $editor,
        'nama'   => $nama,
        'ket'    => $ket,
    ]);

    redirect($result ? "?p={$fwd}&m=addok" : "?p={$fwd}&m=addgagal");
}


/* ========================================================================
   ADD BMN (Barang Milik Negara)
   ======================================================================== */
function bmn()
{
    require("global/koneksi.php");

    $id           = date("ymdhis");
    $kat_bmn      = $_POST["kat_bmn"];
    $kode         = trim($_POST["kode"]);
    $nama_barang  = trim($_POST["nama_barang"]);
    $jumlah       = (int)$_POST["jumlah"];
    $satuan       = trim($_POST["satuan"]);
    $asal_oleh    = trim($_POST["asal_oleh"]);
    $tgl_oleh     = $_POST["tgl_oleh"];
    $bukti_oleh   = trim($_POST["bukti_oleh"]);
    $harga_oleh   = (int)$_POST["harga_oleh"];
    $keterangan   = trim($_POST["keterangan"]);

    $filename = handle_upload('lampiran', './img/barang/', $id, 400);

    $stmt = $bp->prepare("INSERT INTO bmn (id_bmn, kat_bmn, kode_bmn, nama_bmn, jumlah_bmn, satuan, asal_oleh, tgl_oleh, bukti_oleh, harga_oleh, stok_minimal, keterangan, gambar, aktif)
                          VALUES (:id, :kat, :kode, :nama, :jum, :sat, :asal, :tgl, :bukti, :harga, 0, :ket, :gambar, 'ya')");
    $stmt->execute([
        'id'    => $id,
        'kat'   => $kat_bmn,
        'kode'  => $kode,
        'nama'  => $nama_barang,
        'jum'   => $jumlah,
        'sat'   => $satuan,
        'asal'  => $asal_oleh,
        'tgl'   => $tgl_oleh,
        'bukti' => $bukti_oleh,
        'harga' => $harga_oleh,
        'ket'   => $keterangan,
        'gambar'=> $filename,
    ]);

    redirect("?p=bmn&m=addok");
}


/* ========================================================================
   ADD BMN DISTRIBUSI
   ======================================================================== */
function bmn_dist()
{
    require("global/koneksi.php");

    $id             = date("ymdhis");
    $id_bmn         = $_POST["id_bmn"];
    $kat            = $_POST["kat"];
    $nm             = $_POST["nm"];
    $jumlah_pakai   = (int)$_POST["jumlah_pakai"];
    $seksi          = $_POST["seksi"];
    $pengguna       = $_POST["pengguna"];
    $tgl_dist       = $_POST["tgl_dist"];
    $lokasi         = trim($_POST["lokasi"]);
    $kondisi        = trim($_POST["kondisi"]);
    $nama_lampiran  = trim($_POST["nama_lampiran"]);
    $keterangan     = trim($_POST["keterangan"]);

    $filename = handle_upload('lampiran', './lampiran/', $id, 400);

    $stmt = $bp->prepare("INSERT INTO bmn_dist (id_dist, id_bmn, jumlah_pakai, keterangan, seksi, pengguna, lokasi, kondisi, tgl_dist, lampiran, nama_lampiran)
                          VALUES (:id, :bmn, :jum, :ket, :seksi, :pgg, :lok, :kon, :tgl, :lamp, :nm_lamp)");
    $stmt->execute([
        'id'      => $id,
        'bmn'     => $id_bmn,
        'jum'     => $jumlah_pakai,
        'ket'     => $keterangan,
        'seksi'   => $seksi,
        'pgg'     => $pengguna,
        'lok'     => $lokasi,
        'kon'     => $kondisi,
        'tgl'     => $tgl_dist,
        'lamp'    => $filename,
        'nm_lamp' => $nama_lampiran,
    ]);

    redirect("?p=bmn&kat=" . urlencode($kat) . "&nm=" . urlencode($nm) . "&m=addok");
}


/* ========================================================================
   ADD BMN KEMBALI (return)
   ======================================================================== */
function bmn_kembali()
{
    require("global/koneksi.php");

    $id               = date("ymdhis");
    $id_bmn           = $_POST["id_bmn"];
    $kat              = $_POST["kat"];
    $nm               = $_POST["nm"];
    $jumlah_kembali   = (int)$_POST["jumlah_kembali"];
    $seksi            = $_POST["seksi"];
    $pengguna         = $_POST["pengguna"];
    $tgl_dist         = $_POST["tgl_dist"];
    $lokasi           = trim($_POST["lokasi"]);
    $kondisi          = trim($_POST["kondisi"]);
    $nama_lampiran    = trim($_POST["nama_lampiran"]);
    $keterangan       = trim($_POST["keterangan"]);

    $filename = handle_upload('lampiran', './lampiran/', $id, 400);

    $stmt = $bp->prepare("INSERT INTO bmn_dist (id_dist, id_bmn, jumlah_kembali, keterangan, seksi, pengguna, lokasi, kondisi, tgl_dist, lampiran, nama_lampiran)
                          VALUES (:id, :bmn, :jum, :ket, :seksi, :pgg, :lok, :kon, :tgl, :lamp, :nm_lamp)");
    $stmt->execute([
        'id'      => $id,
        'bmn'     => $id_bmn,
        'jum'     => $jumlah_kembali,
        'ket'     => $keterangan,
        'seksi'   => $seksi,
        'pgg'     => $pengguna,
        'lok'     => $lokasi,
        'kon'     => $kondisi,
        'tgl'     => $tgl_dist,
        'lamp'    => $filename,
        'nm_lamp' => $nama_lampiran,
    ]);

    redirect("?p=bmn&kat=" . urlencode($kat) . "&nm=" . urlencode($nm) . "&m=addok");
}
?>
