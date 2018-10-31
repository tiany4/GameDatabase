<html>
    <?php include 'session.php';
    if (!isset($_SESSION['username'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
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
                $statement = "INSERT INTO GAMESTATS (GAMEID, GAMELENGTH, COMPLEXITY, DIFFICULTY)
                    VALUES (:gameid_b, :gamelength_b, :complexity_b, :difficulty_b)";

                $stid = oci_parse($conn, $statement);
                
                $gameid = $_GET['gameid'];
                $length = $_POST['LENGTH'];
                $complexity = $_POST['COMPLEXITY'];
                $difficulty = $_POST['DIFFICULTY'];

                oci_bind_by_name($stid, ':gameid_b', $gameid);
                oci_bind_by_name($stid, ':gamelength_b', $length);
                oci_bind_by_name($stid, ':complexity_b', $complexity);
                oci_bind_by_name($stid, ':difficulty_b', $difficulty);

				if (oci_execute($stid)) {
				} else {
					$e = oci_error($stid); 
					echo $e['message']; 
				}

				oci_close($conn);
				header('Location: game.php?gameid=' . $gameid);
            }
        ?>
		
        <section class="container">
            <form action="stats.php?<?php echo "gameid=".$_GET['gameid']."" ?>" method="post">
				<label>Game Length</label>
                <select name="LENGTH">
                    <option value="" selected disabled hidden>Select Value</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <label>Game Complexity</label>
                <select name="COMPLEXITY">
                    <option value="" selected disabled hidden>Select Value</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <label>Game Difficulty</label>
                <select name="DIFFICULTY">
                    <option value="" selected disabled hidden>Select Value</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <span class="validity"></span>
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