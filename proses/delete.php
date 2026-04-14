<?php
/**
 * Process: Delete Data — All delete operations
 * Fully migrated to PDO prepared statements
 */

if (!empty($_GET['tab'])) {
    $tab = htmlspecialchars($_GET['tab']);
} else {
    $tab = '';
}

switch ($tab) {
    case "petugas":     petugas(); break;
    case "bagian":      bagian(); break;
    case "kat":         kat(); break;
    case "cart":        cart(); break;
    case "anggaran":    anggaran(); break;
    case "anggaran_ta": anggaran_ta(); break;
    case "stok":        stok(); break;
    case "bmn_dist":    bmn_dist(); break;
    case "pesanan":     pesanan(); break;
    case "bmn":         bmn(); break;
    case "kat_bmn":     kat_bmn(); break;
    default:            echo '{"failure":true}'; break;
}

/* ========================================================================
   DELETE PETUGAS
   ======================================================================== */
function petugas()
{
    require("global/koneksi.php");
    $id = $_GET["id"];

    // Get photo to delete
    $row = db_fetch($bp, "SELECT photo FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    if ($row && !empty($row['photo']) && file_exists("img/users/{$row['photo']}")) {
        unlink("img/users/{$row['photo']}");
    }

    $bp->prepare("DELETE FROM petugas WHERE id_petugas = :id")->execute(['id' => $id]);
    redirect("?p=petugas&m=delok");
}

/* ========================================================================
   DELETE STOK (soft delete — set aktif='tidak')
   ======================================================================== */
function stok()
{
    require("global/koneksi.php");
    $id = $_REQUEST["id"];

    $bp->prepare("UPDATE stok_barang SET aktif = 'tidak' WHERE id_barang = :id")->execute(['id' => $id]);
    redirect("?p=stok&m=delok");
}

/* ========================================================================
   DELETE BAGIAN
   ======================================================================== */
function bagian()
{
    require("global/koneksi.php");
    $id = $_GET["id"];

    $bp->prepare("DELETE FROM bagian WHERE id_seksi = :id")->execute(['id' => $id]);
    redirect("?p=bagian&m=delok");
}

/* ========================================================================
   DELETE KATEGORI
   ======================================================================== */
function kat()
{
    require("global/koneksi.php");
    $id = $_GET["id"];

    $bp->prepare("DELETE FROM kategori WHERE id_kat = :id")->execute(['id' => $id]);
    redirect("?p=kat&m=delok");
}

/* ========================================================================
   DELETE KATEGORI BMN
   ======================================================================== */
function kat_bmn()
{
    require("global/koneksi.php");
    $id = $_GET["id"];

    $bp->prepare("DELETE FROM kat_bmn WHERE id_kat = :id")->execute(['id' => $id]);
    redirect("?p=bmn_kat&m=delok");
}

/* ========================================================================
   DELETE CART (stok_inout item)
   ======================================================================== */
function cart()
{
    require("global/koneksi.php");
    $id = $_GET["id"];

    $bp->prepare("DELETE FROM stok_inout WHERE id_inout = :id")->execute(['id' => $id]);
    redirect("?p=cekout&m=delok");
}

/* ========================================================================
   DELETE ANGGARAN (single entry)
   ======================================================================== */
function anggaran()
{
    require("global/koneksi.php");
    $id = $_GET["id"];
    $ta = $_GET["ta"];

    $bp->prepare("DELETE FROM anggaran WHERE id_anggaran = :id")->execute(['id' => $id]);
    redirect("?p=anggaran_ta&ta=" . urlencode($ta) . "&m=delok");
}

/* ========================================================================
   DELETE ANGGARAN TA (entire fiscal year)
   ======================================================================== */
function anggaran_ta()
{
    require("global/koneksi.php");
    $ta = $_GET["ta"];

    $bp->prepare("DELETE FROM anggaran WHERE tahun_anggaran = :ta")->execute(['ta' => $ta]);
    redirect("?p=anggaran&m=delok");
}

/* ========================================================================
   DELETE BMN DISTRIBUSI
   ======================================================================== */
function bmn_dist()
{
    require("global/koneksi.php");
    $id_dist = $_REQUEST["id_dist"];
    $kat     = $_REQUEST["kat"];
    $id_bmn  = $_REQUEST["id_bmn"];

    // Delete attachment if exists
    $row = db_fetch($bp, "SELECT lampiran FROM bmn_dist WHERE id_dist = :id", ['id' => $id_dist]);
    if ($row && !empty($row['lampiran']) && file_exists("lampiran/{$row['lampiran']}")) {
        unlink("lampiran/{$row['lampiran']}");
    }

    $bp->prepare("DELETE FROM bmn_dist WHERE id_dist = :id")->execute(['id' => $id_dist]);
    redirect("?p=bmn_pakai&id_bmn=" . urlencode($id_bmn) . "&kat=" . urlencode($kat) . "&m=delok");
}

/* ========================================================================
   DELETE PESANAN
   ======================================================================== */
function pesanan()
{
    require("global/koneksi.php");
    $id = $_REQUEST["id"];

    $row = db_fetch($bp, "SELECT lampiran FROM pesanan WHERE id_pesanan = :id", ['id' => $id]);
    if ($row && !empty($row['lampiran']) && file_exists("lampiran/{$row['lampiran']}")) {
        unlink("lampiran/{$row['lampiran']}");
    }

    $bp->prepare("DELETE FROM pesanan WHERE id_pesanan = :id")->execute(['id' => $id]);
    redirect("?p=pesanan&m=delok");
}

/* ========================================================================
   DELETE BMN
   ======================================================================== */
function bmn()
{
    require("global/koneksi.php");
    $id = $_REQUEST["id"];

    $row = db_fetch($bp, "SELECT gambar FROM bmn WHERE id_bmn = :id", ['id' => $id]);
    if ($row && !empty($row['gambar']) && file_exists("img/barang/{$row['gambar']}")) {
        unlink("img/barang/{$row['gambar']}");
    }

    $bp->prepare("DELETE FROM bmn WHERE id_bmn = :id")->execute(['id' => $id]);
    redirect("?p=bmn&m=delok");
}
?>