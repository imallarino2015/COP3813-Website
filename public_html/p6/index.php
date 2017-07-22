<?php
	require_once './php/db_connect.php';
	
	if(isset($_POST['name'])&&isset($_POST['sex'])){
		$name=$_POST['name'];	//submitted name
		$sex=$_POST['sex'];	//submitted sex
		
		$select=$db->query('SELECT * FROM nameVotes WHERE name=\''.$name.'\' AND sex=\''.$sex.'\';'); //find specific name and sex
		if($select->num_rows>0)
			$db->query('UPDATE nameVotes SET votes=votes+1 WHERE name=\''.$name.'\' AND sex=\''.$sex.'\';');
		else
			$db->query('INSERT INTO nameVotes(name,sex,votes) VALUES(\''.$name.'\',\''.$sex.'\',1)');
		$votes=($select->fetch_assoc())['votes']+1;	//current votes
		$notification='<p>'.$votes.($votes!=1?' people have':' person has').' voted on '	//how many votes (including the current user) for this name
			.$name.' as their favorite for '
			.($sex=='M'?'Male':'Female').' names.</p>';
	}else
		$notification='';
	
	$nameList=[];	//all names contained on the table
	$select=$db->query('SELECT name FROM nameVotes');
	if($select->num_rows>0)
		while($row=$select->fetch_assoc())
			$nameList[]=$row['name'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Project 6: Baby Names</title>
		<script src="../resources/script/jquery/jquery-3.1.1.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript">
			var names = <?php echo json_encode($nameList); ?>;	//encode the array for the javascript to be able to read
		</script>
		<link href="../resources/css/stylesheet.css" rel="stylesheet">
	</head>
	<body>
		<header>
			<div class="pageHead">
				<img src="images/banner.png" alt="Project 6">
			</div>
			<h1>Baby Names</h1>
			<script src="../resources/script/navBar.js"></script>
		</header>
		
		<form action="index.php" method="post" id="vote">
			<p>Name: <input type="text" name="name" placeholder="Name" id="name"></p>
			<p>Suggestions: <p id="suggestions"></p></p>
			<p>Male: <input type="radio" name="sex" value="M">
				Female: <input type="radio" name="sex"value="F"></p>
			<p><input type="submit" value="Cast vote"></p>
		</form>
		<script src="script/suggest.js"></script>
		
		<?php
			echo $notification;	//Notify the user that their vote has been collected
		?>
		
		<div class="container">
			<h3>Top Names</h3>
			<table>
				<tr>
					<td><h4>Female</h4></td>
					<td><h4>Male</h4></td>
				</tr>
				<tr>
					<td><?php	//Display top 10 (or all, if fewer) female names
						$selectStmt = 'SELECT * FROM nameVotes WHERE sex=\'F\' ORDER BY votes DESC LIMIT 10;';
						$result = $db->query($selectStmt);
						if($result->num_rows > 0){
							echo '<div class="alert alert-success">' . PHP_EOL;
							while($row = $result->fetch_assoc())
								echo '<p>Name: ' . $row["name"] . ' - Votes: ' . $row["votes"] . '</p>' . PHP_EOL;
							echo '</div>' . PHP_EOL;
						}else
							echo '<div class="alert alert-success">No Results</div>' . PHP_EOL;
					?></td>
					<td><?php	//Display top 10 (or all, if fewer) male names
						$selectStmt = 'SELECT * FROM nameVotes WHERE sex=\'M\' order by votes desc LIMIT 10;';
						$result = $db->query($selectStmt);
						if($result->num_rows > 0){
							echo '<div class="alert alert-success">' . PHP_EOL;
							while($row = $result->fetch_assoc())
								echo '<p>Name: ' . $row["name"] . ' - Votes: ' . $row["votes"] . '</p>' . PHP_EOL;
							echo '</div>' . PHP_EOL;
						}else
							echo '<div class="alert alert-success">No Results</div>' . PHP_EOL;
					?></td>
				</tr>
			</table>
		</div>
		<script src="../resources/script/footer.js"></script>
	</body>
</html>

