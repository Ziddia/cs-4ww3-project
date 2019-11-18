<header>
	<!-- This is broken down into mobile/non-mobile, depending on screen width. -->
	<div class="headerButtons">
		<div class="mobile">
			<!-- Responsible for displaying the hamburger icon.
				The 'a' tag is for performing menu animations via CSS :target attributes.
			 -->
			<div class="mobile-nav icon"><a href="#front"><i class="fa fa-bars fa-2x"></i></a></div>
			<div class="mobile-buttons">
				<a class="btn btn-secondary" href="#login">Login</a>
				<a class="btn btn-secondary" href="registration.php">Sign Up</a>
			</div>
		</div>
		<div class="not-mobile">
			<!-- Show buttons related to account login, register, logout -->
			<div class="desktop-buttons">
				<a class="btn btn-secondary" href="#login">Login</a>
				<a class="btn btn-secondary" href="registration.php">Sign Up</a>
			</div>
		</div>
	</div>
	
	<!-- Page title, links backwards to home page. -->
	<div class="title">
		<a href="index.php"><h1>Transit Rating</h1></a>
	</div>
</header>