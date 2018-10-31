<html>
	<?php include 'session.php';
		if (!isset($_GET['gameid'])) {
			header("Location:index.php");
			die();
		}
	?>
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
                $row = oci_fetch_row($stid);
				echo "<h1>".$row[1]."</h1>";
				echo "<h3>Basic Info</h3>";
				echo "<table style='milligram.css' align='center'>";
				for ($i = 2; $i <= $numberOfCols; $i++) {
					$column_name  = oci_field_name($stid, $i);
					echo "<td>$column_name</td>";
				}
					echo "<tr>";
					echo "<td>"; // Game title column
					echo $row[1];
					echo "</td>";
						for ($j = 2; $j <= $numberOfCols - 1; $j++) {
							echo "<td>";
							echo ($row[$j] !== null ? htmlentities($row[$j], ENT_QUOTES) : "Unknown");
							echo "</td>";
						}
					echo "</tr>";
				echo "</table>";
			}
			
			include 'conn.php';
            $error = '';
			$gameid = $_GET['gameid'];
			$statement = "select * from MAIN_GAMES_VIEW where ID ='" . $gameid . "'";
			$stid = oci_parse($conn, $statement);
			oci_execute($stid);
	

			$numberOfRows = oci_fetch_all($stid, $out);
			$numberOfCols = oci_num_fields($stid);

			oci_execute($stid);

			echo "<section class='container'>";
				drawTable($numberOfRows,$numberOfCols,$stid);
				echo "<a class='button' href='edit.php?gameid=".$gameid."'>Edit</a>";
			echo "</section>";
			oci_close($conn);
		?>
		<br>
		<div class="container">
			<div class="row">
				<div class="column column-33">
					<h3>Characters</h3>
					<?php
						include 'conn.php';
						$statement = "select charactername from characters where gameid ='" . $gameid . "'";
						$character = oci_parse($conn, $statement);
						oci_execute($character);
						while ($row = oci_fetch_row($character)) {
							echo "<h5>".$row[0]."</h5>";
						}
					?>
				</div>
				<div class="column column-33">
					<h3>Genre</h3>
					<?php
						include 'conn.php';
						$statement = "select genres from genres where gameid ='" . $gameid . "'";
						$genres = oci_parse($conn, $statement);
						oci_execute($genres);
						while ($row = oci_fetch_row($genres)) {
							echo "<h5>".$row[0]."</h5>";
						}
					?>
				</div>
				<div class="column column-33">
					<h3>Publisher</h3>
					<?php
						include 'conn.php';
						$statement = "select publishername from publishers where gameid ='" . $gameid . "'";
						$pub = oci_parse($conn, $statement);
						oci_execute($pub);
						$row = oci_fetch_row($pub);
						echo "<h5>".$row[0]."</h5>";
					?>
				</div>
			</div>
			<br>

			<h3>Developer</h3>
			<div class="row">
				<div class="column column-100">
					<?php
						// prints developer table
						include 'conn.php';
						$statement = "select * from MAIN_DEV_VIEW where gameid ='" . $gameid . "'";
						$dev = oci_parse($conn, $statement);
						oci_execute($dev);
						$row = oci_fetch_row($dev);
						echo "<table>";
							echo "<tr>";
								echo "<td width='33%'>Name</td>";
								echo "<td width='33%'>Designer</td>";
								echo "<td width='33%'>Producer</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>".($row[2] !== null ? htmlentities($row[2], ENT_QUOTES) : "Unknown")."</td>";
								echo "<td>".($row[3] !== null ? htmlentities($row[3], ENT_QUOTES) : "Unknown")."</td>";
								echo "<td>".($row[4] !== null ? htmlentities($row[4], ENT_QUOTES) : "Unknown")."</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Artist</td>";
								echo "<td>Composer</td>";
								echo "<td>Writer</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>".($row[5] !== null ? htmlentities($row[5], ENT_QUOTES) : "Unknown")."</td>";
								echo "<td>".($row[6] !== null ? htmlentities($row[6], ENT_QUOTES) : "Unknown")."</td>";
								echo "<td>".($row[7] !== null ? htmlentities($row[7], ENT_QUOTES) : "Unknown")."</td>";
							echo "</tr>";
						echo "</table>";
					?>
				</div>
			</div>
			<br>

			<h3>Reviews</h3>

			
			<div class="row">
				<div class="column column-50">
				<h4>User Reviews</h4>
					<?php
						include 'conn.php';
						$statement = "select * from userreviews where gameid ='" . $gameid . "'";
						$ureview = oci_parse($conn, $statement);
						oci_execute($ureview);
						while ($row = oci_fetch_row($ureview)) {
							echo "<blockquote>";
								echo "<p>Here is what ".($row[1] !== null ? htmlentities($row[1], ENT_QUOTES) : "<i>username unknown</i>")." thinks:</p>";
								echo "<p>".($row[3] !== null ? htmlentities($row[3], ENT_QUOTES) : "")."</p>";
							echo "</blockquote>";
						}
					?>
				</div>
				<div class="column column-50">
				<h4>Media Reviews</h4>
					<?php
						include 'conn.php';
						$statement = "select url from mediareviews where gameid ='" . $gameid . "'";
						$mreview = oci_parse($conn, $statement);
						oci_execute($mreview);
						while ($row = oci_fetch_row($mreview)) {
							echo "<a href='".$row[0]."'>".$row[0]."</a>";
						}
					?>
				</div>
			</div>

			<?php echo "<a class='button' href='review.php?gameid=".$gameid."'>Add Review</a>"?>

			<br><br>
			<h4>Game Stats</h4>
			<div class="row">
				<div class="column column-100">
					<?php
						include 'conn.php';
						$statement = "select avg(gamelength), avg(complexity), avg(difficulty) from gamestats where gameid ='" . $gameid . "'";
						$gamestats = oci_parse($conn, $statement);
						oci_execute($gamestats);
						$row = oci_fetch_row($gamestats);
						echo "<div class='column column-33'>";
							echo "Length: ".$row[0];
						echo "</div>";
						echo "<div class='column column-33'>";
							echo "Complexity: ".$row[1];
						echo "</div>";
						echo "<div class='column column-33'>";
							echo "Difficulty: ".$row[2];
						echo "</div>";
						echo "<br>";
						echo "<a class='button' href='stats.php?gameid=".$gameid."'>Vote Stats</a>";
					?>
				</div>
			</div>
		</div>

		<footer class="footer">
			<section class="container">
				<div class="blank"></div>
				<p>Designed with a modified version of Milligram CSS framework at <a href="https://milligram.io">https://milligram.io</a></p>
				<p>Password hashing framework by Solar Designer at <a href="http://openwall.com">openwall.com</a></p>
			</section>
		</footer>
	</body>
</html>