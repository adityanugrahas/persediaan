<?php
/**
 * Process: Update Data — All update operations
 * Fully migrated to PDO prepared statements
 */

if (!empty($_GET['tab'])) {
    $tab = htmlspecialchars($_GET['tab']);
} else {
    $tab = '';
}

switch ($tab) {
    case "setting":       setting(); break;
    case "bagian":        bagian(); break;
    case "petugas":       petugas(); break;
    case "setting2":      setting2(); break;
    case "barang":        barang(); break;
    case "permintaan":    permintaan(); break;
    case "cart":          cart(); break;
    case "anggaran":      anggaran(); break;
    case "anggaran_set":  anggaran_set(); break;
    case "stok_tambah":   stok_tambah(); break;
    case "bmn":           bmn(); break;
    case "notifopen":     notifopen(); break;
    case "kat_edit":      kat_edit(); break;
    case "katbr":         katbr(); break;
    default:              echo '{"failure":true}'; break;
}


/* ========================================================================
   UPDATE SETTING (initial setup — insert)
   ======================================================================== */
function setting()
{
    if (!empty($_POST["id"])) {
        // Update existing setting
        require("global/koneksi.php");
        $id      = $_POST["id"];
        $nama    = trim($_POST["nama"]);
        $alamat  = trim($_POST["alamat"]);
        $telepon = trim($_POST["telepon"]);
        $email   = trim($_POST["email"]);

        // Handle logo upload
        $filename = handle_upload('logo', 'img/', 'logo', 100);
        if ($filename) {
            $bp->prepare("UPDATE setting SET logo_header = :logo WHERE setting_id = :id")
               ->execute(['logo' => $filename, 'id' => $id]);
        }

        $bp->prepare("UPDATE setting SET nama_kantor = :nama, alamat_kantor = :alamat, telp_kantor = :telp, email_kantor = :email WHERE setting_id = :id")
           ->execute([
               'nama'  => $nama,
               'alamat'=> $alamat,
               'telp'  => $telepon,
               'email' => $email,
               'id'    => $id,
           ]);

        redirect("?p=setting&m=updok");
    } else {
        // First-time setup — insert
        require("../global/koneksi.php");
        $id      = date("ymdhis");
        $nama    = trim($_POST["nama"]);
        $alamat  = trim($_POST["alamat"]);
        $telepon = trim($_POST["telepon"]);
        $email   = trim($_POST["email"]);
        $title   = trim($_POST["title"]);

        $filename = handle_upload('logo', '../img/', 'logo', 100);

        $stmt = $bp->prepare("INSERT INTO setting (setting_id, nama_kantor, alamat_kantor, telp_kantor, email_kantor, title_head, logo_header)
                              VALUES (:id, :nama, :alamat, :telp, :email, :title, :logo)");
        $result = $stmt->execute([
            'id'     => $id,
            'nama'   => $nama,
            'alamat' => $alamat,
            'telp'   => $telepon,
            'email'  => $email,
            'title'  => $title,
            'logo'   => $filename,
        ]);

        redirect($result ? "../index.php" : "../setting.php");
    }
}


/* ========================================================================
   UPDATE BAGIAN
   ======================================================================== */
function bagian()
{
    require("global/koneksi.php");

    $id     = $_POST["id_seksi"];
    $bagian = trim($_POST["bagian"]);
    $ket    = trim($_POST["ket"]);

    $bp->prepare("UPDATE bagian SET bagian = :bagian, keterangan = :ket WHERE id_seksi = :id")
       ->execute(['bagian' => $bagian, 'ket' => $ket, 'id' => $id]);

    redirect("?p=bagian&m=updok");
}


/* ========================================================================
   UPDATE ANGGARAN
   ======================================================================== */
function anggaran()
{
    require("global/koneksi.php");

    $id_anggaran  = $_POST["id_anggaran"];
    $akun         = trim($_POST["akun"]);
    $ket_akun     = trim($_POST["ket_akun"]);
    $pagu_lama    = (int)$_POST["pagu_lama"];
    $pagu_baru    = (int)$_POST["pagu_baru"];
    $ket_pagu     = trim($_POST["ket_pagu_baru"]);
    $id_nota      = date("ymdhis");
    $kode_nota    = date("ymd.Hi");
    $tgl_ok       = date("Y-m-d H:i:s");
    $pemroses     = $_SESSION["idp"];

    $jum_in  = max(0, $pagu_baru - $pagu_lama);
    $jum_out = max(0, $pagu_lama - $pagu_baru);

    // Update anggaran
    $bp->prepare("UPDATE anggaran SET akun_anggaran = :akun, ket_anggaran = :ket, pagu_anggaran = :pagu WHERE id_anggaran = :id")
       ->execute(['akun' => $akun, 'ket' => $ket_akun, 'pagu' => $pagu_baru, 'id' => $id_anggaran]);

    // Handle attachment
    $filename = handle_upload('lampiran', './lampiran/', $id_nota, 500);

    // Insert nota record
    $bp->prepare("INSERT INTO nota (id_nota, kode_nota, jenis, id_anggaran, tanggal, pemroses, jum_out, jum_in, keterangan, lampiran, status)
                  VALUES (:id, :kode, 'pagu', :id_ang, :tgl, :pemroses, :out, :in, :ket, :lamp, 'ok')")
       ->execute([
           'id'       => $id_nota,
           'kode'     => $kode_nota,
           'id_ang'   => $id_anggaran,
           'tgl'      => $tgl_ok,
           'pemroses' => $pemroses,
           'out'      => $jum_out,
           'in'       => $jum_in,
           'ket'      => $ket_pagu,
           'lamp'     => $filename,
       ]);

    redirect("?p=anggaran&m=updok");
}


/* ========================================================================
   UPDATE ANGGARAN STATUS (activate/deactivate)
   ======================================================================== */
function anggaran_set()
{
    require("global/koneksi.php");

    $ta  = $_GET["ta"];
    $set = $_GET["set"];
    $status = ($set === "ok") ? 1 : 0;

    $bp->prepare("UPDATE anggaran SET status = :status WHERE tahun_anggaran = :ta")
       ->execute(['status' => $status, 'ta' => $ta]);

    redirect("?p=anggaran&m=updok");
}


/* ========================================================================
   UPDATE CART (edit stok_inout item in cart)
   ======================================================================== */
function cart()
{
    require("global/koneksi.php");

    $id_inout = $_POST["id_inout"];
    $ket      = trim($_POST["keterangan"]);
    $jenis    = $_POST["jenis"];

    if ($jenis === "in") {
        $jml_in = (int)$_POST["jml_in"];
        $harga  = (int)$_POST["harga"];
        $bp->prepare("UPDATE stok_inout SET jml_in = :jml, harga = :harga, keterangan = :ket WHERE id_inout = :id")
           ->execute(['jml' => $jml_in, 'harga' => $harga, 'ket' => $ket, 'id' => $id_inout]);
        $redir = "cekin";
    } else {
        $jml_req = (int)$_POST["jml_req"];
        $bp->prepare("UPDATE stok_inout SET jml_req = :jml, keterangan = :ket WHERE id_inout = :id")
           ->execute(['jml' => $jml_req, 'ket' => $ket, 'id' => $id_inout]);
        $redir = "cekout";
    }

    redirect("?p={$redir}&m=updok");
}


/* ========================================================================
   UPDATE PERMINTAAN (process stock-out requests)
   ======================================================================== */
function permintaan()
{
    require("global/koneksi.php");

    $tanggal      = date("Y-m-d H:i:s");
    $jum          = (int)$_POST["jum"];
    $id_inout     = $_POST["id_inout"];
    $jml_out      = $_POST["jml_out"];
    $catatan      = $_POST["catatan"];
    $id_barang    = $_POST["id_barang"];
    $jumlah_stok  = $_POST["jumlah_stok"];

    $bp->beginTransaction();
    try {
        for ($i = 0; $i < $jum; $i++) {
            $sisa = (int)$jumlah_stok[$i] - (int)$jml_out[$i];

            $bp->prepare("UPDATE stok_inout SET status = '2', jml_out = :out, jml_stok = :sisa, catatan = :cat, tgl_ok = :tgl WHERE id_inout = :id")
               ->execute([
                   'out'  => (int)$jml_out[$i],
                   'sisa' => $sisa,
                   'cat'  => $catatan[$i],
                   'tgl'  => $tanggal,
                   'id'   => $id_inout[$i],
               ]);

            $bp->prepare("UPDATE stok_barang SET jumlah_stok = :sisa WHERE id_barang = :id")
               ->execute(['sisa' => $sisa, 'id' => $id_barang[$i]]);
        }
        $bp->commit();
        redirect("?p=permintaan&m=updok");
    } catch (Exception $e) {
        $bp->rollBack();
        redirect("?p=permintaan&m=updgagal");
    }
}


/* ========================================================================
   UPDATE STOK TAMBAH (approve stock additions)
   ======================================================================== */
function stok_tambah()
{
    require("global/koneksi.php");

    $id_nota    = date("ymdhis");
    $dana       = $_POST["sumber_dana"];
    $jum        = (int)$_POST["jum"];
    $id_inout   = $_POST["id_inout"];
    $id_barang  = $_POST["id_barang"];
    $kode       = $_POST["kode"];
    $tgl_ok     = $_POST["tgl_ok"];
    $jml_in     = $_POST["jml_in"];
    $jml_stok   = $_POST["jml_stok"];
    $satuan     = $_POST["satuan"];
    $keterangan = trim($_POST["keterangan"]);
    $total      = (int)$_POST["total"];
    $pemroses   = $_SESSION["idp"];

    $bp->beginTransaction();
    try {
        for ($i = 0; $i < $jum; $i++) {
            $saldo = (int)$jml_stok[$i] + (int)$jml_in[$i];

            $bp->prepare("UPDATE stok_inout SET status = '2', kode_nota = :kode, jml_in = :jml, sumber_dana = :dana, jml_stok = :saldo, tgl_ok = :tgl WHERE id_inout = :id")
               ->execute([
                   'kode'  => $kode,
                   'jml'   => (int)$jml_in[$i],
                   'dana'  => $dana,
                   'saldo' => $saldo,
                   'tgl'   => $tgl_ok,
                   'id'    => $id_inout[$i],
               ]);

            $bp->prepare("UPDATE stok_barang SET jumlah_stok = :saldo WHERE id_barang = :id")
               ->execute(['saldo' => $saldo, 'id' => $id_barang[$i]]);
        }

        // Handle attachment
        $filename = handle_upload('lampiran', './lampiran/', $id_nota, 500);

        // Insert nota record
        $bp->prepare("INSERT INTO nota (id_nota, kode_nota, jenis, id_anggaran, tanggal, pemroses, jum_out, jum_in, keterangan, lampiran, status)
                      VALUES (:id, :kode, 'belanja', :dana, :tgl, :pemroses, :total, 0, :ket, :lamp, 'ok')")
           ->execute([
               'id'       => $id_nota,
               'kode'     => $kode,
               'dana'     => $dana,
               'tgl'      => $tgl_ok,
               'pemroses' => $pemroses,
               'total'    => $total,
               'ket'      => $keterangan,
               'lamp'     => $filename,
           ]);

        // Update anggaran serapan
        $sag = db_fetch($bp, "SELECT pagu_anggaran, serapan_anggaran FROM anggaran WHERE id_anggaran = :id", ['id' => $dana]);
        if ($sag) {
            $serapan = $sag['serapan_anggaran'] + $total;
            $bp->prepare("UPDATE anggaran SET serapan_anggaran = :serapan WHERE id_anggaran = :id")
               ->execute(['serapan' => $serapan, 'id' => $dana]);
        }

        $bp->commit();
        redirect("?p=rekap&f=masuk&m=updok");
    } catch (Exception $e) {
        $bp->rollBack();
        redirect("?p=rekap&f=masuk&m=updgagal");
    }
}


/* ========================================================================
   UPDATE PETUGAS
   ======================================================================== */
function petugas()
{
    require("global/koneksi.php");

    $id         = $_POST["id_petugas"];
    $nama       = trim($_POST["nama"]);
    $users_id   = trim($_POST["users_id"]);
    $pwd        = trim($_POST["pwd"]);
    $seksi      = $_POST["seksi"];
    $level      = $_POST["level"];
    $photo_lama = $_POST["photo_lama"];
    $status     = $_POST["status"];

    // Handle photo upload
    $filename = handle_upload('photo', 'img/users/', $id, 300);
    if ($filename) {
        if (!empty($photo_lama) && file_exists("img/users/{$photo_lama}")) {
            @unlink("img/users/{$photo_lama}");
        }
        $bp->prepare("UPDATE petugas SET photo = :photo WHERE id_petugas = :id")
           ->execute(['photo' => $filename, 'id' => $id]);
    }

    // Check if password changed — if so, hash it
    $existing = db_fetch($bp, "SELECT pwd FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    if ($existing && $pwd !== $existing['pwd']) {
        // Password was changed; hash the new one
        $pwd = password_safe_hash($pwd);
    }

    $bp->prepare("UPDATE petugas SET nama = :nama, users_id = :uid, pwd = :pwd, seksi = :seksi, p_level = :level, p_status = :status WHERE id_petugas = :id")
       ->execute([
           'nama'   => $nama,
           'uid'    => $users_id,
           'pwd'    => $pwd,
           'seksi'  => $seksi,
           'level'  => $level,
           'status' => $status,
           'id'     => $id,
       ]);

    redirect("?p=petugas&m=updok");
}


/* ========================================================================
   UPDATE BARANG (stok_barang)
   ======================================================================== */
function barang()
{
    require("global/koneksi.php");

    $id_barang     = $_POST["id_barang"];
    $kategori      = $_POST["kategori"];
    $nama_barang   = trim($_POST["nama_barang"]);
    $satuan        = trim($_POST["satuan"]);
    $batas_stok    = (int)$_POST["batas_stok"];
    $harga         = (int)$_POST["harga"];
    $keterangan    = trim($_POST["keterangan"]);
    $aktif         = $_POST["aktif"];
    $lampiran_lama = $_POST["lampiran_lama"];

    // Handle photo upload
    $filename = handle_upload('lampiran_baru', 'img/barang/', $id_barang, 400);
    if ($filename) {
        if (!empty($lampiran_lama) && file_exists("img/barang/{$lampiran_lama}")) {
            @unlink("img/barang/{$lampiran_lama}");
        }
        $bp->prepare("UPDATE stok_barang SET gambar = :gambar WHERE id_barang = :id")
           ->execute(['gambar' => $filename, 'id' => $id_barang]);
    }

    $bp->prepare("UPDATE stok_barang SET kategori = :kat, nama_barang = :nama, satuan = :sat, harga_satuan = :harga, stok_minimal = :min, keterangan = :ket, aktif = :aktif WHERE id_barang = :id")
       ->execute([
           'kat'   => $kategori,
           'nama'  => $nama_barang,
           'sat'   => $satuan,
           'harga' => $harga,
           'min'   => $batas_stok,
           'ket'   => $keterangan,
           'aktif' => $aktif,
           'id'    => $id_barang,
       ]);

    redirect("?p=stok&m=updok");
}


/* ========================================================================
   UPDATE SETTING2 (update existing settings)
   ======================================================================== */
function setting2()
{
    require("global/koneksi.php");

    $id       = $_POST["id"];
    $nama     = trim($_POST["nama"]);
    $alamat   = trim($_POST["alamat"]);
    $telepon  = trim($_POST["telepon"]);
    $email    = trim($_POST["email"]);
    $title    = trim($_POST["title"]);
    $logo_lama = $_POST["logo_lama"];

    $tgl = date("ymdhis");
    $filename = handle_upload('logo_baru', 'img/', $tgl . '-logo', 300);
    if ($filename) {
        if (!empty($logo_lama) && file_exists("img/{$logo_lama}")) {
            @unlink("img/{$logo_lama}");
        }
        $bp->prepare("UPDATE setting SET logo_header = :logo WHERE setting_id = :id")
           ->execute(['logo' => $filename, 'id' => $id]);
    }

    $bp->prepare("UPDATE setting SET title_head = :title, nama_kantor = :nama, alamat_kantor = :alamat, telp_kantor = :telp, email_kantor = :email WHERE setting_id = :id")
       ->execute([
           'title'  => $title,
           'nama'   => $nama,
           'alamat' => $alamat,
           'telp'   => $telepon,
           'email'  => $email,
           'id'     => $id,
       ]);

    redirect("?p=setting&m=updok");
}


/* ========================================================================
   UPDATE BMN
   ======================================================================== */
function bmn()
{
    require("global/koneksi.php");

    $id_bmn      = $_POST["id_bmn"];
    $kat_bmn     = $_POST["kat_bmn"];
    $kode        = trim($_POST["kode"]);
    $nama_barang = trim($_POST["nama_barang"]);
    $jumlah      = (int)$_POST["jumlah"];
    $satuan      = trim($_POST["satuan"]);
    $asal_oleh   = trim($_POST["asal_oleh"]);
    $tgl_oleh    = $_POST["tgl_oleh"];
    $bukti_oleh  = trim($_POST["bukti_oleh"]);
    $harga_oleh  = (int)$_POST["harga_oleh"];
    $keterangan  = trim($_POST["keterangan"]);
    $pengguna    = trim($_POST["pengguna"] ?? '');
    $lokasi      = trim($_POST["lokasi"] ?? '');
    $kondisi     = trim($_POST["kondisi"] ?? '');
    $gambar_lama = $_POST["gambar_lama"] ?? '';

    $filename = handle_upload('lampiran_baru', 'img/barang/', $id_bmn, 400);
    if ($filename) {
        if (!empty($gambar_lama) && file_exists("img/barang/{$gambar_lama}")) {
            @unlink("img/barang/{$gambar_lama}");
        }
        $bp->prepare("UPDATE bmn SET gambar = :gambar WHERE id_bmn = :id")
           ->execute(['gambar' => $filename, 'id' => $id_bmn]);
    }

    $bp->prepare("UPDATE bmn SET kat_bmn = :kat, kode_bmn = :kode, nama_bmn = :nama, jumlah_bmn = :jum, satuan = :sat, asal_oleh = :asal, tgl_oleh = :tgl, bukti_oleh = :bukti, harga_oleh = :harga, keterangan = :ket, pengguna = :pgg, lokasi = :lok, kondisi = :kon WHERE id_bmn = :id")
       ->execute([
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
           'pgg'   => $pengguna,
           'lok'   => $lokasi,
           'kon'   => $kondisi,
           'id'    => $id_bmn,
       ]);

    redirect("?p=bmn&m=updok");
}


/* ========================================================================
   NOTIF OPEN (mark notification as read)
   ======================================================================== */
function notifopen()
{
    require("global/koneksi.php");

    $idnotif = $_GET["idnotif"];
    $k       = $_GET["k"] ?? '';

    $bp->prepare("UPDATE notif SET status = '1' WHERE id_notif = :id")
       ->execute(['id' => $idnotif]);

    redirect("?p=stok&k=" . urlencode($k));
}


/* ========================================================================
   UPDATE KATEGORI
   ======================================================================== */
function kat_edit()
{
    require("global/koneksi.php");

    $id   = $_POST["id_kat"];
    $nama = trim($_POST["nama_kat"]);
    $ket  = trim($_POST["ket"]);

    $bp->prepare("UPDATE kategori SET nama_kat = :nama, ket_kat = :ket WHERE id_kat = :id")
       ->execute(['nama' => $nama, 'ket' => $ket, 'id' => $id]);

    redirect("?p=kat&m=updok");
}


/* ========================================================================
   UPDATE KATEGORI BMN
   ======================================================================== */
function katbr()
{
    require("global/koneksi.php");

    $id   = $_POST["id_kat"];
    $nama = trim($_POST["nama_kat"]);
    $ket  = trim($_POST["ket"]);

    $bp->prepare("UPDATE kat_bmn SET nama_kat = :nama, ket_kat = :ket WHERE id_kat = :id")
       ->execute(['nama' => $nama, 'ket' => $ket, 'id' => $id]);

    redirect("?p=bmn_kat&m=updok");
}
?>