<?php
if(!empty($_REQUEST['p']))
{ $p=htmlspecialchars($_REQUEST['p']); }
else {$p='home';}
 

switch ($p)
   {  
   	  //BERANDA
		 case 'home'       : include "content/dashboard.php";break;
		 case 'petugas'    : include "content/petugas.php";break;
		 case 'petugas_add': include "content/petugas_add.php";break;
		 case 'bagian'     : include "content/bagian.php";break;
		 case 'bagian_add' : include "content/bagian_add.php";break;
		 case 'stok'       : include "content/stok.php";break;
		 case 'stok_add'   : include "content/stok_add.php";break;
		 case 'stok_edit'   : include "content/stok_edit.php";break;
		 case 'kat'        : include "content/kat.php";break;
		 case 'kat_add'    : include "content/kat_add.php";break;
		 case 'menipis'    : include "content/menipis.php";break;
		 case 'rekap'    : include "content/rekap.php";break;
		 case 'cekout'    : include "content/cekout.php";break;
		 case 'proses_permintaan'    : include "content/proses_permintaan.php";break;
		 case 'cekin'    : include "content/cekin.php";break;
		 case 'ajukan'    : include "content/ajukan.php";break;
		 case 'permintaan'    : include "content/permintaan.php";break;
		 case 'prosesacc'    : include "content/prosesacc.php";break;
		 case 'setting'    : include "content/setting.php";break;
		 case 'anggaran'    : include "content/anggaran.php";break;
		 case 'anggaran_ta'    : include "content/anggaran_ta.php";break;
		 case 'anggaran_det'    : include "content/anggaran_det.php";break;
		 case 'rekap_stok'    : include "content/rekap_stok.php";break;
		 case 'cari'    : include "content/cari.php";break;
		 case 'profil'    : include "content/profil.php";break;
		 case 'bmn_kat'    : include "content/bmn_kat.php";break;
		 case 'bmn_add'    : include "content/bmn_add.php";break;
		 case 'bmn'    : include "content/bmn.php";break;
		 case 'bmn_dist'    : include "content/bmn_dist.php";break;
		 case 'bmn_pakai'    : include "content/bmn_pakai.php";break;
		 case 'bmn_kat_pakai'    : include "content/bmn_kat_pakai.php";break;
		 case 'bmn_kat_det'    : include "content/bmn_kat_det.php";break;
		 case 'bmn_pemegang'    : include "content/bmn_pemegang.php";break;
		 case 'bmn_pemegang_det'    : include "content/bmn_pemegang_det.php";break;
		 case 'pesanan'    : include "content/pesanan.php";break;
		 case 'stok_all'    : include "content/stok_all.php";break;
		 case 'rekap_pemakai'    : include "content/rekap_pemakai.php";break;
		 case 'stok_mutasi'    : include "content/stok_mutasi.php";break;
		 case 'stok_kat'    : include "content/stok_kat.php";break;
		 case 'bmn_detail'    : include "content/bmn_detail.php";break;
		
		 //case 'setting'    : echo"<meta http-equiv=\"refresh\" content=\"0; URL=setting.php\">";break;
		 		 
	  
   	  case 'proadd'       : include "proses/proadd.php";break;
	  case 'logout'       : include "proses/logout.php";break;
	  case 'update'       : include "proses/update.php";break;
	  case 'delete'       : include "proses/delete.php";break;
	  case 'userset'      : include "content/userset.php";break;
	  case 'keluar'      : include "proses/logout.php";break;
	  
	  case ''     : include "content/$landing";break;
	  
	  
	  case 'login'       : echo"<meta http-equiv=\"refresh\" content=\"0; URL=login.php\">";break;
		
	  
   } 
?>
