<?php
if(!empty($_REQUEST['p']))
{ $p=htmlspecialchars($_REQUEST['p']); }
else {$p='stok';}

if(!empty($_REQUEST['k']))
{ $k=htmlspecialchars($_REQUEST['k']); }
else {$k='';}

switch ($p)
   {  
   	  //BERANDA
		 case 'home'       : echo"Beranda";break;
		 case 'petugas'    : echo"Data Petugas";break;
		 case 'petugas_add': echo"Penambahan Petugas";;break;
		 case 'bagian'     : echo"Bidang / Seksi";break;
		 case 'bagian_add' : echo"Tambah Bidang / Seksi";break;
		 case 'stok'       : echo"Data Stok $k";break;
		 case 'stok_add'   : echo"Penambahan Jenis Barang";break;
		 case 'stok_edit'   : echo"Edit Data Barang";break;
		 case 'kat'        : echo"Kategori";break;
		 case 'kat_add'    : echo"Tambah Kategori";break;
		 case 'menipis'    : echo"Stok Menipis";break;
		 case 'rekap'    : echo"Rekapitulasi";break;
		 case 'cekout'    : echo"Permohonan Pemakaian Barang";break;
		 case 'proses_permintaan'    : echo"Permohonan Pemakaian Barang";break;
		 case 'cekin'    : echo"Penambahan Stok Barang";break;
		 case 'ajukan'    : echo"Pengajuan Pemakaian Barang";break;
		 case 'permintaan'    : echo"Data Permintaan Barang";break;
		 case 'prosesacc'    : echo"Proses ACC";break;
		 case 'setting'    : echo"Pengaturan ";break;
		 case 'anggaran'    : echo"Anggaran";break;
		 case 'anggaran_ta'    : echo"Anggaran TA";break;
		 case 'anggaran_det'    : echo"Detail Anggaran";break;
		 case 'rekap_stok'    : echo"Rekpitulasi Stok Barang";break;
		 case 'cari'    : echo"Pencarian : $k";break;
		 case 'profil'    : echo"Profil Saya <b>$_SESSION[namap]</b>";break;
	  
	  case ''     : echo"Aplikasi Manajemen Barang";break;
	  
   } 
?>
