<?php
	require_once "php/db_connect.php";
	session_start();
	
	$accountOps='';
	$newPicForm='';
	$picOps='';
	
	$showAmt=30;	//how many items will show per page
	
	if(isset($_GET['page']))
		$page=$_GET['page'];
	else
		$page=1;
	
	if(isset($_SESSION['username'])){
		$accountOps='<div>
				Hello, '.$_SESSION['username'].'.<br>
				<a href="index.php">Main page</a>|
				<a href="index.php?logout">Log out</a><br>
				<button id="showForm">Upload image</button>
			</div>';
		
		$newPicForm='<div id="popUp" class="popUp">
				<form method="post" action="wall.php" id="hiddenForm" enctype="multipart/form-data">
					Select an image: <input type="file" id="selection" name="selection" accept="image/*" required><br>
					<img id="preview" name="preview" src="" height="100" width="100"><br>
					Picture title: <input type="text" id="title" name="title" maxlength="140" required><br>
					Caption: <textarea id="caption" name="caption" maxlength="140"></textarea><font id="count">140</font><br>
					<input type="submit" value="Upload" id="upload" name="upload">
				</form>
			</div>';
		
		if(isset($_FILES['selection'])&&isset($_POST['title'])){	//save the image to images/uploads and record that image in the database
			$time=$_SERVER['REQUEST_TIME'];
			
			//permission denied
			//copy($_FILES['selection']['tmp_name'],'images/uploads/'.$time.$_FILES['selection']['name']);
			move_uploaded_file($_FILES['selection']['tmp_name'],'images/uploads/'.$time.$_FILES['selection']['name']);
			
			$db->query('INSERT INTO WALL(USER_USERNAME,STATUS_TEXT,STATUS_TITLE,IMAGE_NAME,TIME_STAMP) 
				VALUES(\''.$_SESSION['username'].'\',\''.$_POST['caption'].'\',\''.$_POST['title'].'\',\''.$time.$_FILES['selection']['name'].'\',\''.$time.'\')');
		}
		
		if(isset($_GET['del'])){	//delete posted image
			//delete corresponding image
			if(file_exists('images/uploads/'.$_GET['del']))
				unlink('images/uploads/'.$_GET['del']);
			
			//delete corresponding table row
			$db->query('DELETE FROM WALL WHERE IMAGE_NAME=\''.$_GET['del'].'\';');
		}
		
		//display images and corresponding information
		$first=$page*$showAmt-$showAmt;	
		$last=$page*$showAmt-1;
		$imageInfo=$db->query('select * from WALL where USER_USERNAME=\''.$_SESSION['username'].'\' order by TIME_STAMP desc limit '.$first.','.$last.';');
		
		$toShow=mysqli_num_rows($imageInfo);
		
		if($toShow>0)
			while($row=$imageInfo->fetch_assoc())	//populate with the last n images and the option to delete them
				$picOps.='<form class="imgPost" method="post" action="wall.php?del='.$row['IMAGE_NAME'].'&page='.$page.'">
						'.date("m-d-y",$row['TIME_STAMP']).'<br>
						<a href="images/uploads/'.$row['IMAGE_NAME'].'">
							<img src="images/uploads/'.$row['IMAGE_NAME'].'" alt="'.$row['STATUS_TITLE'].'"/>
						</a><br>
						'.$row['STATUS_TITLE'].'<br>
						'.$row['STATUS_TEXT'].'<br>
						<input type="submit" name="delete" id="del'.$row['IMAGE_NAME'].'" value="Delete">
					</form>
					<hr>';
		else
			$picOps="<div class='imgPost'>You don't seem to have anything here.</div>";
	}else{
		header('Location: index.php');	//send unlogged users back to the main page
	}
?>

<!doctype html>
	<head>
		<title>My wall</title>
		<link href="../resources/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
		<link href="../resources/css/stylesheet.css" rel="stylesheet">
		<script src="../resources/script/jquery/jquery-3.1.1.js"></script>
	</head>
	<body>
		
		<?php
			echo $accountOps;
			echo $newPicForm;
			echo $picOps;
			echo '<div class="pageNav">'
					.($page>1?'<a href="wall.php?page='.($page-1).'">Previous</a>':'Previous').'|'
					.($toShow>=$showAmt?'<a href="wall.php?page='.($page+1).'">Next</a>':'Next')
				.'</div>';
		?>
		
	<script src="script/hiddenForm.js"></script>
	<script src="script/picOps.js"></script>
	</body>
</html>