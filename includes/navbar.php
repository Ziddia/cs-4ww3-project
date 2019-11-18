<?php if(!isset($_SESSION)) session_start(); ?>

<!-- 'hack' I got the concept for from https://web.archive.org/web/20120929010042/http://csscience.com/css3-tabs/#tabC

	this is used to stop the page from 'jumping' during the :target transitions
	(without this it would move to put the #target at the top of the page)

	basically the idea is to insert these 2 spans which are fixed at top-left corner,
	and direct the a tag from the hamburger icon to these spans (such that browser
	doesn't need to jump to the #target, it's always at top-left corner). then we use
	fancy CSS selectors to apply the animations to the actual target elements
	(for example, #front:target ~ .navbar-close > nav for expanding the navbar).
 -->
<span class="target-fix" id="close-front"></span>
<span class="target-fix" id="front"></span>
<div class="navbar-close">
	<nav class="mobile-navbar">
		<ul>
			<?php if (!isset($_SESSION["username"])) : ?>
				<li><a id="nav_login" href="#login">Log In</a></li>
				<li><a id="nav_reg" href="registration.php">Register</a></li>
			<?php endif; ?>
			<li><a id="nav_search" href="search.php">Search Transit Stations</a></li>
			<li><a id="nav_submit" href="submission.php">Submit Transit Station</a></li>
			<?php if (isset($_SESSION["username"])) : ?><li><a href="logout.php">Log Out</a></li><?php endif; ?>
			<!-- Link to close the menu via the :target stuff -->
			<li><a href="#close-front">Close</a></li>
		</ul>
	</nav>
</div>

<nav class="desktop-navbar">
	<ul>
		<?php if (!isset($_SESSION["username"])) : ?>
			<li><a id="nav_login" href="#login">Log In</a></li>
			<li><a id="nav_reg" href="registration.php">Register</a></li>
		<?php endif; ?>
		<li><a id="nav_search_d" href="search.php">Search Transit Stations</a></li>
		<li><a id="nav_submit_d" href="submission.php">Submit Transit Station</a></li>
		<?php if (isset($_SESSION["username"])) : ?><li><a href="logout.php">Log Out</a></li><?php endif; ?>
	</ul>
</nav>

<script type="text/javascript">
	var page_id = "nav_<?php echo $page_id; ?>";
	document.getElementById(page_id).classList.add("active");

	page_id = page_id + "_d";
	document.getElementById(page_id).classList.add("active");
</script>