<?php
	// Do not change the following two lines.
	$teamURL = dirname($_SERVER['PHP_SELF']) . DIRECTORY_SEPARATOR;
	$server_root = dirname($_SERVER['PHP_SELF']);

	// You will need to require this file on EVERY php file that uses the database.
	// Be sure to use $db->close(); at the end of each php file that includes this!

	$dbhost = 'localhost';  // Most likely will not need to be changed
	$dbname = 'imallarino2015';   // Needs to be changed to your designated table database name
	$dbuser = 'imallarino2015';   // Needs to be changed to reflect your LAMP server credentials
	
	if(isset($_POST['password'])){
		$dbpass = $_POST['password']; // Needs to be changed to reflect your LAMP server credentials
	
		$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

		if($db->connect_errno > 0)
			die('Unable to connect to database [' . $db->connect_error . ']');
		else
			echo 'Votes reset.';
	
		//Remove current table
		$db->query('DROP TABLE nameVotes;');
	
		// Create table
		$createStmt = 'CREATE TABLE nameVotes (' . PHP_EOL
			. '  id int(10) UNSIGNED AUTO_INCREMENT,' . PHP_EOL
			. '  name varchar(20) DEFAULT \'\',' . PHP_EOL
			. '  sex varchar(1) DEFAULT \'\',' . PHP_EOL
			. '  votes int(10) UNSIGNED,' . PHP_EOL
			. '  PRIMARY KEY (id)' . PHP_EOL
			. ') ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
		$db->query($createStmt);
	}
?>

<!DOCTYPE html>
	<head>
		<title>Reset Votes</title>
	</head>
	<body>
		<form method="post" action="resetVotes.php">
			Password: 			
			<input type="password" name="password">
			<input type="submit" value="Reset">
		</form>
	</body>
</html>