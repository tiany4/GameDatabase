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
                $statement = "INSERT INTO USERREVIEWS (USERNAME, GAMEID, REVIEWTEXT)
                    VALUES (:username_b, :gameid_b, :reviewtext_b)";

                $stid = oci_parse($conn, $statement);
                
                $username = $_SESSION['username'];
                $gameid = $_GET['gameid'];
                $reviewtext = $_POST['REVIEW'];

                oci_bind_by_name($stid, ':username_b', $username);
                oci_bind_by_name($stid, ':gameid_b', $gameid);
                oci_bind_by_name($stid, ':reviewtext_b', $reviewtext);

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
            <form action="review.php?<?php echo "gameid=".$_GET['gameid']."" ?>" method="post">
				<label>What do you think?</label>
                <textarea name="REVIEW" required pattern="^[a-zA-Z0-9_: ]+$"></textarea>
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