<?php
	require_once "php/db_connect.php";
	session_start();
	
	$login='';	//things displayed to the user depending on their log-in status
	$pics='';	//pictures loaded from the database
	
	$showAmt=30;	//how many items will show per page
	
	if(isset($_GET['page']))
		$page=$_GET['page'];
	else
		$page=1;
	
	$form='<form action="index.php" method="post">'
			.'	Username: <input type="text" name="username" placeholder="Username" id="name" required> '
			.'	Password: <input type="password" name="password" placeholder="Password" id="pass" required> '
			.'<input type="submit" name="login" value="login">'
			.'</form>'
			.'<p><button id="showForm">New user</button></p>';
	
	if(isset($_POST['newName'])&&isset($_POST['newPass'])&&isset($_POST['register'])){	//create a new user
		//encrypt the password and store the username and the password in the database
		$_SESSION['username']=$_POST['newName'];
		$hiddenPass=hash('ripemd128',$_POST['newName'].'!$#'.$_POST['newPass']);	//alter the password so it is encrypted when stored
		$db->query('INSERT INTO USERS(userID,password) VALUES(\''.$_POST['newName'].'\',\''.$hiddenPass.'\')');	//add the username and password to the database
	}
	
	if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['login'])){	//log in
		//compare the username and password to the database and log in if correct, otherwise return error
		$hiddenPass=hash('ripemd128',$_POST['username'].'!$#'.$_POST['password']);
		$pass=$db->query('select password from USERS where userID=\''.$_POST['username'].'\';')->fetch_assoc()['password'];	//extract password to compare
		if($hiddenPass==$pass){
			$_SESSION['username']=$_POST['username'];
		}else{
			$login='<p>Your username and/or password did not match any in our records.  Please try again.</p>';
		}
	}
	
	$nameList=[];	//all names contained on the table
	$select=$db->query("select userID from USERS;");
	if($select->num_rows>0)
		while($row=$select->fetch_assoc())
			$nameList[]=$row['userID'];
			
	if(isset($_SESSION['username'])){	
		if(isset($_GET['logout'])){	//if the user just logged out
			$login='<h4>Logout successful.</h4>'
				."Goodbye, ".$_SESSION['username'].".";	//tell the user goodbye
			$login.=$form;
			session_destroy();
		}else{
			$login='Hello, '.$_SESSION['username'].'.<br>'.PHP_EOL
				.'<a href="wall.php">Account</a><br>'.PHP_EOL
				.'<a href="?logout">Log Out</a>'.PHP_EOL;
		}
	}else{
		$login.='<h4>You are not logged in.</h4>';
		$login.=$form;
	}
	
	$first=$page*$showAmt-$showAmt;	
	$last=$page*$showAmt-1;
	$imageInfo=$db->query('select * from WALL order by TIME_STAMP desc limit '.$first.','.$last.';');
	
	$toShow=mysqli_num_rows($imageInfo);
		
	while($row=$imageInfo->fetch_assoc())	//populate with the last n images and the option to delete them
		$pics.='<div class="imgPost">
				'.$row['USER_USERNAME'].': '.date("m-d-y",$row['TIME_STAMP']).'<br>
				<a href="images/uploads/'.$row['IMAGE_NAME'].'">
					<img src="images/uploads/'.$row['IMAGE_NAME'].'" alt="'.$row['STATUS_TITLE'].'" />
				</a><br>
				'.$row['STATUS_TITLE'].'<br>
				'.$row['STATUS_TEXT'].'<br>
			</div>
			<hr>';
?>

<!DOCTYPE html>
	<head>
		<title>Project 7</title>
		<link href="../resources/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
		<link href="../resources/css/stylesheet.css" rel="stylesheet">
		<script src="../resources/script/jquery/jquery-3.1.1.js"></script>
		<script type="text/javascript">
			var names = <?php echo json_encode($nameList); ?>;	//encode the array for the javascript to be able to read
		</script>
	</head>
	<body>
		<header>
			<div class="pageHead">
				<img src="images/banner.png" alt="Project 7">
			</div>
			<h1>Image Uploader</h1>
			<script src="../resources/script/navBar.js"></script>
		</header>
		
		<?php
			echo $login;
			echo $pics;
			echo '<div class="pageNav">'
				.($page>1?'<a href="wall.php?page='.($page-1).'">Previous</a>':'Previous').'|'
				.($toShow>=$showAmt?'<a href="wall.php?page='.($page+1).'">Next</a>':'Next')
				.'</div>';
		?>
		
		<div id="popUp" class="popUp">
			<form action="index.php" method="post" id="hiddenForm">
				<h3>Register:</h3>
				Username: <input type="text" name="newName" placeholder="Username" id="newName" required> <font id="nameCheck"></font><br>
				Password: <input type="password" name="newPass" placeholder="Password" id="newPass" required><br>
				<input type="submit" name="register" value="Register" id="register">
			</form>
		</div>
		
		<script src="script/hiddenForm.js"></script>
		<script src="script/createUsr.js"></script>	
		
		<script src="../resources/script/footer.js"></script>	
	</body>
</html>