<?php
$i = 0;
?>
<div class="header-nav collapse">
	<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
		<nav>
			<ul class="nav nav-pills" id="mainNav">
				<li class="dropdown">
					<a class="nav-link dropdown-toggle" href="#">
						Data Barang
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="nav-link" href="?p=stok_all">
								<i class="fa fa-cubes"></i> Data Barang
							</a>
						</li>
						<li class="dropdown-submenu">
							<a class="nav-link" href="">
								<i class="fa fa-cube"></i> Kategori
							</a>
							<ul class="dropdown-menu">
								<?php
								$skat = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY nama_kat");
								if (count($skat) > 0) {
									echo "<li><a class='nav-link' href='?p=kat'><i class='fa fa-cube'></i> Semua Kategori</a></li>";
									foreach ($skat as $kat) {
										echo "<li><a class='nav-link' href='?p=stok&kat=" . htmlspecialchars($kat['id_kat']) . "'><i class='fa fa-cube'></i> " . htmlspecialchars($kat['nama_kat']) . "</a></li>";
									}
								}
								?>
							</ul>
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
                                <i class="fa fa-check"></i> Permintaan 
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="?p=rekap&f=pakai">
                                <i class="fa fa-arrow-right"></i> Pemakaian
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