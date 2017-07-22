<?php
function currencyConverter($currency_from,$currency_to,$currency_input){
    $amount = urlencode($currency_input);
    $from_Currency = urlencode($currency_from);
    $to_Currency = urlencode($currency_to);
    $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
    $get = explode("<span class=bld>",$get);
    $get = explode("</span>",$get[1]);  
    $currency_output = preg_replace("/[^0-9\.]/", null, $get[0]);
    
    return $currency_output;
}

$options="";	//the options available in the dropdown boxes
$output_statement="";	//what appears after the user has input the necessary values

$currency_from = "";
$currency_to = "";
$currency_input = 0;

if($currencyList=file_get_contents("https://developers.google.com/ad-exchange/seller-rest/appendix/currencies.csv")){	//attempt to load the file from the server and check that it exists
	$line=explode(PHP_EOL,$currencyList);	//split the file in to an array by its lines
	$currencyData=array();	//initialize the array for possible currencies
	foreach($line as $current)
		$currencyData[]=str_getcsv($current);	//split the individual elements in to sub-arrays
	
	for($a=1;$a<sizeof($currencyData)-1;$a++)	//skip the first line, since there is no value on it.
		$options.='\n<option value='.$currencyData[$a][0].'>'.$currencyData[$a][1].'</option>';	//set up the users' conversion options
}else
	$output_statement='File not found.';	//no list of currencies found

if (isset($_POST['currency_from']) && isset($_POST['currency_to']) && isset($_POST['currency_input']))
{
	$currency_from = $_POST['currency_from'];
	$currency_to = $_POST['currency_to'];
	$currency_input = $_POST['currency_input'];

	$currency = currencyConverter($currency_from,$currency_to,$currency_input);
	
	$output_statement="<p>$currency_input $currency_from is equal to $currency $currency_to</p>";
}
			
echo<<<END
<!DOCTYPE html>
	<head>
		<title>Project 5: Currency Converter</title>
		<script src="../resources/script/jquery/jquery-3.1.1.js"></script>
		<link href="../resources/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
		<link href="../resources/css/stylesheet.css" type="text/css" rel="stylesheet"/>
		
	</head>
	<body>
		<header>
			<div class="pageHead">
				<img src="images/banner.png" alt="Project 5">
			</div>
			<h1>Currency Converter</h1>
			<script src="../resources/script/navBar.js"></script>
		</header>

		<form method="post" action="index.php" id="converter">
			<label>Enter amount:</label>
			<input type="text" name="currency_input" placeholder="Amount" />
			<label>Select currency (from):</label>
			<select name="currency_from" id="from">
				$options
			</select>
			<label>Select currency (to):</label>
			<select name="currency_to" id="to">
				$options
			</select>
			<input type="submit" value="Convert!" />
			$output_statement
		</form>

		<script src="script/qol.js"></script>
		<script src="../resources/script/footer.js"></script>
	</body>
</html>
END;
?>