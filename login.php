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
			include 'conn.php';
			include 'PasswordHash.php';
			$error='';

			// Login
			if (isset($_POST['login']) && !empty($_POST['USERNAME']) && !empty($_POST['PASSWORD'])) {
				$statement = 'SELECT * FROM ACCOUNTS WHERE username = :username_b';
				$stid = oci_parse($conn, $statement);
				
				$username = $_POST['USERNAME'];
				$password = $_POST['PASSWORD'];

				// Password hashing framework taken from http://www.openwall.com/phpass/
				// due to jasmine server php version being too low to use built-in password_hash()
				$phash = new PasswordHash(8,false);
				$hashedPass = '*';

				oci_bind_by_name($stid, ':username_b', $username);
				oci_execute($stid);
				if (!$row = oci_fetch_row($stid)) {
					$error = "Username does not exist.";
				} else if ($row[3]==1) {
					$error = "You're banned!";
				} else {
					// Check passhash against stored hash
					$hashedPass = $row[1];
					$check = $phash->CheckPassword($password, $hashedPass);
					if (!$check) {
						$error = "Invalid password.";
					} else {
						$_SESSION['username'] = $username;
						$_SESSION['admin'] = $row[2];

						$row[2];
						oci_close($conn);
						header('Refresh: 1; URL = index.php');
					}
				}
			}

			// Register
			else if (isset($_POST['register']) && !empty($_POST['USERNAME']) && !empty($_POST['PASSWORD'])) {
				$username = $_POST['USERNAME'];
				$password = $_POST['PASSWORD'];
				
				// Checks to see if username exists
				$statement = 'SELECT username from accounts where username = :username_b';
				$stid = oci_parse($conn, $statement);
				oci_bind_by_name($stid, ':username_b', $username);
				oci_execute($stid);

				if ($row = oci_fetch_row($stid)) {
					$error = "Username already exists.";
				} else {
					// Registers new account if username is fresh
					$statement = 'INSERT INTO ACCOUNTS VALUES (:username_b, :passwordhash_b, \'0\', \'0\')';
					$stid = oci_parse($conn, $statement);
	
					// Password hashing framework taken from http://www.openwall.com/phpass/
					// due to jasmine server php version being too low to use the built-in password_hash()
					$phash = new PasswordHash(8,false);
					$hasehedPassword = $phash->HashPassword($password);
					if (strlen($hasehedPassword) < 20) {
						echo "Password wasn't hashed successfully";
					} else {
						oci_bind_by_name($stid, ':username_b', $username);
						oci_bind_by_name($stid, ':passwordhash_b', $hasehedPassword);
						if (oci_execute($stid)) {
							oci_close($stid);
							header("Location: index.php");
						} else {
							$e = oci_error($stid); 
							echo $e['message']; 
						}
					}
				}
			}
		?>
		
		<section class="container" styles="width:30%">
            <form action="login.php" method="post">
				<label>Username</label>
				<input name="USERNAME" type="text" required pattern="^[a-zA-Z0-9]+$">
				<span class="validity"></span>
				<label>Password</label>
				<input name="PASSWORD" type="password" required pattern="^([a-zA-Z0-9@#]{3,15})$">
				<span class="validity"></span>
				<input class="button" value="Login" name ="login" type="submit">
				<input class="button" value="Register" name ="register" type="submit">
				<p><?php echo $error;?></p>
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