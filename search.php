<html>
	<?php include 'session.php'?>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="milligram.css"/>
	<body>
		<nav class = "navigation">
			<section class = "container">
			<a class="navigation-title" href="index.php"><h1 class="title">Game Stuff</h1></a>
				<ul class="navigation-list float-right">
					<?php
						if ($_SESSION['admin'] == 1) {
							echo "<li class='navigation-item'>";
								echo "<a class='navigation-link' href='manage.php'>Manage</a>";
							echo "</li>";
						}
					?>
            		<li class="navigation-item">
               			<a class="navigation-link" href='search.php'>Search</a>
            		</li>
            		<li class="navigation-item">
						<?php
							if (!isset($_SESSION['username'])) {
								echo "<a class='navigation-link' href='login.php'>Login</a>";
							} else {
								echo "<a class='navigation-link' href='logout.php'>Logout</a>";
							}
						?>
            		</li>
        		</ul>
			</section>
		</nav>
		<div class="blank"></div>
        <section class="container">
            <form action="results.php" method="GET">
                <label>Looking for something?</label>
                <input placeholder="Some Game" name="searchtitle" type="text" value="" required pattern="^[a-zA-Z0-9_: ]+$">
                <input class="button" value="Search" type="submit">
            </form>
        </section>
        <footer class="footer">
            <section class="container">
                <div class="blank"></div>
                <p>Designed with a modified version of Milligram CSS framework at <a href="https://milligram.io">https://milligram.io</a></p>
                <p>Password hashing framework by Solar Designer at <a href="http://openwall.com">openwall.com</a></p>
        </section>
		</footer>
	</body>
</html>