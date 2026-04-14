<?php
$jum = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif = 'ya'");
$jum_ps = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM pesanan");
?>
<div class="header-nav collapse">
	<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
		<nav>
			<ul class="nav nav-pills" id="mainNav">
				<li class="dropdown">
					<a class="nav-link dropdown-toggle" href="#">
						Persediaan
						<?php if ($jum > 0): ?><span class="badge badge-danger"><?= $jum ?></span><?php endif; ?>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="nav-link" href="?p=stok_all">
								<i class="fa fa-cubes"></i> Data Barang
							</a>
						</li>
						<li class="dropdown-submenu">
							<a class="nav-link dropdown-toggle" href="#">
								<i class="fas fa-tags mr-1"></i> Kategori
							</a>
							<ul class="dropdown-menu">
								<?php
								$skat = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY nama_kat");
								if (count($skat) > 0) {
									echo "<li><a class='nav-link' href='?p=stok_all'><i class='fas fa-th-large'></i> Semua Kategori</a></li>";
									foreach ($skat as $kat) {
										echo "<li><a class='nav-link' href='?p=stok_kat&kat=" . htmlspecialchars($kat['id_kat']) . "'><i class='fas fa-tag'></i> " . htmlspecialchars($kat['nama_kat']) . "</a></li>";
									}
								}
								?>
								<li>
									<a class="nav-link" href="?p=kat">
										<i class="fa fa-edit"></i> Edit Kategori
									</a>
								</li>
								<li>
    								<a class='nav-link simple-ajax-modal modal-with-form' href='ajax/kat_add.php'>
										<i class="fa fa-plus"></i> Tambah Kategori
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="nav-link" href="?p=menipis">
								<i class="fa fa-exclamation-circle"></i> Stok Menipis
								<?php if ($jum > 0): ?><span class="badge badge-danger"><?= $jum ?></span><?php endif; ?>
							</a>
						</li>
						<li>
							<a class="nav-link" href="?p=pesanan">
								<i class="fa fa-exclamation-circle"></i> Pesanan Stok Kosong
								<?php if ($jum_ps > 0): ?><span class="badge badge-danger"><?= $jum_ps ?></span><?php endif; ?>
							</a>
						</li>
						<li>
							<a class="nav-link" href="?p=stok_add">
								<i class="fa fa-plus"></i> Tambah Barang
							</a>
						</li>
					</ul>
				</li>

				<li class="dropdown">
					<a class="nav-link dropdown-toggle" href="#">
						Data Petugas
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="nav-link" href="?p=petugas">
								<i class="fa fa-users"></i> Data Petugas
							</a>
						</li>
						<li class="dropdown-submenu">
							<a class="nav-link" href="#">
								<i class="fa fa-project-diagram"></i> Bidang/Seksi
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class='nav-link' href='?p=petugas'>
										<i class='fa fa-project-diagram'></i> Semua
									</a>
								</li>
								<?php
								$sbdg = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY urutan");
								foreach ($sbdg as $bdg) {
									echo "<li><a class='nav-link' href='?p=petugas&id_seksi=" . htmlspecialchars($bdg['id_seksi']) . "'>" . htmlspecialchars($bdg['bagian']) . "</a></li>";
								}
								?>
								<li>
									<a class="nav-link" href="?p=bagian">
										<i class="fa fa-edit"></i> Edit Bidang/Seksi
									</a>
								</li>
								<li>
									<a class="nav-link" href="?p=bagian_add">
										<i class="fa fa-plus"></i> Tambah Bidang/Seksi
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="nav-link" href="?p=petugas_add">
								<i class="fa fa-plus"></i> Tambah Petugas
							</a>
						</li>
					</ul>
				</li>

				<li class="dropdown">
					<a class="nav-link dropdown-toggle" href="#">
						Rekapitulasi
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="nav-link" href="?p=permintaan">
								<i class="fa fa-list"></i> Permintaan Barang
							</a>
						</li>
						<li class="dropdown-submenu">
							<a class="nav-link" href="#">
								<i class="fa fa-exchange-alt"></i> Distribusi Barang
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="?p=rekap&f=pakai">
										<i class="fa fa-arrow-right"></i> Pemakaian
									</a>
								</li>
								<li>
									<a class="nav-link" href="?p=rekap&f=masuk">
										<i class="fa fa-arrow-left"></i> Penambahan Stok
									</a>
								</li>
								<li>
									<a class="nav-link" href="?p=rekap&f=semua">
										<i class="fa fa-exchange-alt"></i> Semua
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="nav-link" href="?p=rekap_stok">
								<i class="fa fa-cubes"></i> Rekap Stok
							</a>
						</li>
						<li>
							<a class="nav-link" href="?p=rekap_pemakai">
								<i class="fa fa-users"></i> Rekap Pemakai
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a class="nav-link dropdown-toggle" href="#">
						BMN
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="nav-link" href="?p=bmn_kat">
								<i class="fa fa-cubes"></i> Data BMN
							</a>
						</li>
						<li class="dropdown-submenu">
							<a class="nav-link" href="">
								<i class="fa fa-cube"></i> Kategori
							</a>
							<ul class="dropdown-menu">
								<?php
								$skat_bmn = db_fetch_all($bp, "SELECT * FROM kat_bmn ORDER BY nama_kat");
								if (count($skat_bmn) > 0) {
									echo "<li><a class='nav-link' href='?p=bmn_kat'><i class='fa fa-cube'></i> Semua Kategori</a></li>";
									foreach ($skat_bmn as $kat) {
										echo "<li><a class='nav-link' href='?p=bmn&kat=" . htmlspecialchars($kat['id_kat']) . "&nm=" . htmlspecialchars($kat['nama_kat']) . "'><i class='fa fa-cube'></i> " . htmlspecialchars($kat['nama_kat']) . "</a></li>";
									}
								}
								?>
								<li>
									<a href='ajax/bmn_kat_add.php' class='simple-ajax-modal'>
										<i class="fa fa-plus"></i> Tambah Kategori
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="nav-link" href="?p=bmn_detail">
								<i class="fa fa-random"></i> Distribusi BMN
							</a>
						</li>
						<li>
							<a class="nav-link" href="?p=bmn_pemegang">
								<i class="fa fa-user"></i> Pemegang Aset
							</a>
						</li>
						<li>
							<a class="nav-link" href="?p=bmn_add">
								<i class="fa fa-plus"></i> Tambah BMN
							</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="nav-link" href="manual_book.pdf" target='_blank'>
						<i class="fa fa-book"></i> Buku Panduan
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>