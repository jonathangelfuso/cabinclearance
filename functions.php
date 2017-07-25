<?php
// put if(validUser($conn) == true) at top of every page.
function validUser($conn){ // returns true if user/pass combo in database, otherwise just goes to login page.
	$username = $_SESSION['$username'] ; // the login page makes a session. 
	$password = $_SESSION['$password'];
	$user_type = $_SESSION['$user_type'] ;

	$sql0 = "SELECT * from users where username = '$username' and  password = '$password'";
	$users = $conn->prepare($sql0);
	$users-> execute();
	$passcheck = false;
	foreach($users as $row0){//If the username and password do not match it will not even enter this foreach because the query won't return anything.
		if($row0['username'] == $username && $row0['password'] == $password){//this just double checks 
			$passcheck = true;
			return true;
		}
	}
	if ($passcheck != true){ //send user back to login page after showing error message
		echo "wrong username/password combination";
		sleep(1);?>
		<meta http-equiv="refresh" content="5; url=/cabinclearance/login.php">
	<?php
	}

}
function getVendorID($conn,$vend){	// takes vendor name, returns vendor ID... this is used a lot
	$sql = "SELECT id from vendors WHERE name = '$vend'";// if query fails there will be an error
	$vendIDQuery = $conn->prepare($sql);
	$vendIDQuery->execute();
	foreach($vendIDQuery as $row){
		$vendID = $row['id'];
	}
	return $vendID;

}
function startConnection(){// say $conn = startConnection() on every page
	$db_host = "localhost";
	$db_name = "cabinclearance";
	$db_username = "root";
	$db_password = "";
	$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $conn;

}

?>
