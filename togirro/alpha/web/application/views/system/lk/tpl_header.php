<header id="header" class="page-header fixed-top">
	<nav class="navbar navbar-expand-lg navbar-light">
		<div class="header-container">
			<div class="header-left">
				<a class="navbar-brand" href="/"><?= $project_name; ?></a>
				<button type="button"  class="navbar-toggler sidebar-toggler" data-toggle="collapse" data-target="#page_sidebar" aria-controls="page_sidebar" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-bars"></i>
				</button>
			</div>
			<div class="header-right">
				<div class="header-tools">
					<button type="button"  class="navbar-toggler"data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<i class="fas fa-bars"></i>
					</button>
				</div>
				<div class="header-menu">
					<ul class="navbar-nav">
						{admin}
						<li class="nav-item">
							<a class="nav-link admin-highlight" href="{url}">{content}</a>
						</li>
						{/admin}
<!-- 
						<li class="nav-item dropdown">
							<div class="nav-link dropdown-toggle" href="#" id="lang-dropdown">
								{language_content}
							</div>
							{languages}
						</li> -->

						<li class="nav-item">
							<a class="nav-link" href="{login_url}">{login_content}</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="header-menu-mobile">
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					{admin}
					<li class="nav-item">
						<a class="nav-link admin-highlight" href="{url}">{content}</a>
					</li>
					{/admin}
<!--
					<li class="nav-item dropdown">
						<div class="nav-link dropdown-toggle" href="#" id="lang-dropdown">
							{language_content}
						</div>
						{languages}
					</li>
-->
					<li class="nav-item">
						<a class="nav-link" href="{login_url}">{login_content}</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>