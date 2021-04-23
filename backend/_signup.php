<?php

require("_dbconnect.php");
//echo "hello <br>";
$success=false;
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign'])){
	$username = $_POST['username'];
	$pass = $_POST['password'];
	$cpass = $_POST['cpassword'];

	// echo $username;
	// echo $password;
	if(empty($username)){
		$error='*Enter valid Username...';
	}
	elseif(empty($pass) || empty($cpass)){
		$error='*Enter password...';	
	}
	elseif($pass===$cpass){
		
		$password=password_hash($pass,$algo=PASSWORD_DEFAULT);
		$sql="insert into users(user_id,username,Password) values (NULL, '$username', '$password')";
		$result = mysqli_query($conn,$sql);
		
		if($result==true){
			$success="Successful.. Please login to continue :)";
		}
		else { 
			//echo mysqli_error($conn);
			$error=mysqli_error($conn);
			if(substr($error,0,15)=="Duplicate entry"){
				$error= '*UserID Exits.. Please select another :!';
			}
		//(substr($exist,0,15)=="Duplicate entry")?$error= '*UserID Exits.. Please select another :!':$error=mysqli_error($conn);
		}
	}
	else{
		$error= '*Password do not match :!';
	}
}
if($success){
	header('location: ./../main.php?login=&success='.$success);
	exit();
}
else{
	header('location: ./../main.php?signup=&error='.$error);
	exit();
}
//echo password_hash($password,$algo=PASSWORD_DEFAULT);

?>
