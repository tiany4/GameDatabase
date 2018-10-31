<?php
    include 'session.php';
    if ($_SESSION['admin'] == 0) {
        header("Location:index.php");
        die();
    }
?>

<html>
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
                echo "<table align='center'>";
                for ($i = 1; $i <= $numberOfCols; $i++) {
                    $column_name  = oci_field_name($stid, $i);
                    echo "<td>$column_name</td>";
                }
                echo "<td></td>"; // extra column for delete icon
                while ($row = oci_fetch_row($stid)) {
                    echo "<tr>";
                        echo "<td>";
                            echo $row[0];
                        echo "</td>";
                        echo "<td>";
                            switch ($row[1]) {
                                case 0: echo "Regular User"; break;
                                case 1: echo "Admin"; break;
                            }
                        echo "</td>";
						echo "<td>";
							$banned = getBanned($row[0]);
							switch ($banned) {
								case 0: echo "<a href='ban.php?name=".$row[0]."&banned=".$banned."'>Ban</a>"; break;
								case 1: echo "<a href='ban.php?name=".$row[0]."&banned=".$banned."'>Unban</a>"; break;
							}
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

            function getBanned($name) {
				include 'conn.php';
                $statement = "select ban from accounts where username = '".$name."'";
                $stid = oci_parse($conn, $statement);
				oci_execute($stid);
				$row = oci_fetch_row($stid);
				$banned = $row[0];
				return $banned;
            }
			include 'conn.php';
			$error = '';
			$statement = "select username, accounttype from accounts order by username asc";
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