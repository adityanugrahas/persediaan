<header class="header">
				<div class="logo-container">
					<a href="#" class="logo">
						<img src="img/<?php echo"$set[logo_header]";?>" height="40" alt="<?php echo"$set[logo_header]";?>" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
<?php
    if(!empty($_SESSION["photo"])) { $photo="$_SESSION[photo]";} else { $photo="user.jpg";}

?>			
				<!-- start: search & user box -->
				<div class="header-right">
			
					
		
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="img/users/<?php echo"$photo";?>" alt="<?php echo"$_SESSION[nama]";?>" class="img-circle" data-lock-picture="img/user/<?php echo"$_SESSION[photo]";?>" />
							</figure>
							<div class="profile-info" data-lock-name="<?php echo"$_SESSION[nama]";?>" data-lock-email="<?php echo"$_SESSION[nama]";?>">
								<span class="name"><?php echo"$_SESSION[nama]";?></span>
								<span class="role"><?php echo"$_SESSION[level]"?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=profil"><i class="fa fa-user"></i> My Profile</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=setting"><i class="fa fa-cog"></i> Setting</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="proses/logout.php"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
	