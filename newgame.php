<html>
	<?php include 'session.php'?>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="milligram.css"/>
    <body>
        <nav class = "navigation">
			<section class = "container">
			<a class="navigation-title" href="index.php"><h1 class="title">Game Stuff</h1></a>
				<ul class="navigation-list float-right">
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
			if (isset($_POST['submit'])) {
				include 'conn.php';
			    $statement = 'INSERT INTO GAMES (TITLE, RELEASEDATE, SERIES, ENGINE, GAMEMODE, ESRB, PEGI) VALUES (:title_b, to_date(:releasedate_b, \'YYYY-MM-DD\'), :series_b, :engine_b, :gamemode_b, :esrb_b, :pegi_b)';
                $stid = oci_parse($conn, $statement);
				
                $title = $_POST['TITLE'];
                $releasedate = $_POST['RELEASEDATE'];
                $series = $_POST['SERIES'];
                $engine = $_POST['ENGINE'];
                $gamemode = $_POST['GAMEMODE'];
                $esrb = $_POST['ESRB'];
                $pegi = $_POST['PEGI'];
				
                oci_bind_by_name($stid, ':title_b', $title);
                oci_bind_by_name($stid, ':releasedate_b', $releasedate);
                oci_bind_by_name($stid, ':series_b', $series);
                oci_bind_by_name($stid, ':engine_b', $engine);
                oci_bind_by_name($stid, ':gamemode_b', $gamemode);
                oci_bind_by_name($stid, ':esrb_b', $esrb);
                oci_bind_by_name($stid, ':pegi_b', $pegi);

				if (oci_execute($stid)) {
				} else {
					$e = oci_error($stid); 
					echo $e['message']; 
				}
				
				$statement = 'INSERT INTO GENRES (GENRES) VALUES (:genres_b)';
				$stid = oci_parse($conn, $statement);

				$genres = array_map('trim',preg_split("/,/", $_POST['GENRE']));
				foreach ($genres as $genre) {
					oci_bind_by_name($stid, ':genres_b', $genre);
					oci_execute($stid);
				}

				$statement = 'INSERT INTO CHARACTERS (CHARACTERNAME) VALUES (:charactername_b)';
				$stid = oci_parse($conn, $statement);

				$characters = array_map('trim',preg_split("/,/", $_POST['CHARACTERS']));
				foreach ($characters as $character) {
					oci_bind_by_name($stid, ':charactername_b', $character);
					oci_execute($stid);
				}

				oci_close($conn);
				header("Location: index.php");
            }
        ?>
		
        <section class="container">
            <form action="newgame.php" method="post">
				<label>Game Title</label>
				<input placeholder="Some Game" name="TITLE" type="text" value="" required pattern="^[a-zA-Z0-9_: ]+$">
				<span class="validity"></span>
				<label>Release Date</label>
				<input placeholder="YYYY-MM-DD" name="RELEASEDATE" type="text" value="" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"> 
				<span class="validity"></span>
				<label>Series</label>
				<input placeholder="Buncha Games" name="SERIES" type="text" value="">
				<label>Engine</label>
				<input placeholder="Not Real Engine" name="ENGINE" type="text" value="">
				<label>Game Mode</label>
				<select name="GAMEMODE">
					<option value="1">Single Player</option>
					<option value="2">Multiplayer</option>
					<option value="3">Single and Multiplayer</option>
				</select>
				<label>ESRB Rating</label>
				<select name="ESRB" value="">
					<option value="0">Does not exist</option>
					<option value="1">Early Childhood</option>
					<option value="2">Everyone</option>
					<option value="3">Everyone 10+</option>
					<option value="4">Teen</option>
					<option value="5">Mature</option>
					<option value="6">Adults Only</option>
				</select>
				<label>PEGI Rating</label>
				<select name="PEGI" value="">
					<option value="0">Does not exist</option>
					<option value="1">PEGI 3</option>
					<option value="2">PEGI 7</option>
					<option value="3">PEGI 12</option>
					<option value="4">PEGI 16</option>
					<option value="5">PEGI 18</option>
				</select>
				<label>Genre(s)</label>
				<textarea placeholder="Split different genres by a comma(,)" name="GENRE"></textarea>
				<label>Character(s)</label>
				<textarea placeholder="Split different characters by a comma(,)" name="CHARACTER"></textarea>
				<input class="button" value="Add" name ="submit" type="submit">
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