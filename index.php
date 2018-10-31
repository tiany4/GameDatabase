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
		
		<?php
			function drawTable($numberOfRows,$numberOfCols,$stid) {
				echo "<table style='milligram.css' align='center'>";
				for ($i = 2; $i <= $numberOfCols; $i++) {
					$column_name  = oci_field_name($stid, $i);
					echo "<td>$column_name</td>";
				}
				echo "<td></td>"; // extra column for delete icon
				while ($row = oci_fetch_row($stid)) {
					echo "<tr>";
					echo "<td>"; // Game title column
					echo "<a href='game.php?gameid=".$row[0]."'>".htmlentities($row[1], ENT_QUOTES)."</a>";
					echo "</td>";
						for ($j = 2; $j <= $numberOfCols - 1; $j++) {
							echo "<td>";
							echo ($row[$j] !== null ? htmlentities($row[$j], ENT_QUOTES) : "N/A");
							echo "</td>";
						}
						echo "<td>";
							echo "<a href='edit.php?gameid=".$row[0]."' method=post>Edit</a>";
						echo "</td>";
						echo "<td>";
							echo "<a href='delete.php?gameid=".$row[0]."' method=post>Delete</a>";
						echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<section class='container'>";
					echo "<a class='button' href='newgame.php'>Add a game</a>";
					echo "<p class='float-right'>";
					echo $numberOfRows;
					echo " rows retrieved.</p>";
				echo "</section>";
			}

			include 'conn.php';
			$error = '';
			$statement = "select * from MAIN_GAMES_VIEW";
			$stid = oci_parse($conn, $statement);
			if (oci_execute($stid)) {
			} else {
				$e = oci_error($stid);
				echo $e['message'];
			}

			$numberOfRows = oci_fetch_all($stid, $out);
			$numberOfCols = oci_num_fields($stid);

			if (oci_execute($stid)) {
				drawTable($numberOfRows,$numberOfCols,$stid);
			} else {
				$e = oci_error($stid); 
				echo $e['message']; 
			}
			oci_close($conn);
		?>
		<footer class="footer">
			<section class="container">
				<div class="blank"></div>
				<p>Designed with a modified version of Milligram CSS framework at <a href="https://milligram.io">https://milligram.io</a></p>
				<p>Password hashing framework by Solar Designer at <a href="http://openwall.com">openwall.com</a></p>
			</section>
		</footer>
	</body>
</html>