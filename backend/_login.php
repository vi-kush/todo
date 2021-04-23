<?php

require("_dbconnect.php");
//echo "hello <br>";

$error="";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	// echo $username;
	// echo $password;
	if(empty($username) || empty($password)){
		$error="*Please fill details.. ";
	}
	else{
		$sql='select * from users where username ="'.$username.'"';
  	$result = mysqli_query($conn,$sql);
		if(!$result){
			echo mysqli_error($conn);
		}
		elseif(mysqli_num_rows($result)==0){
			$error="*Invalid UserName!!";
		}
		else {
			$row=mysqli_fetch_assoc($result);
			//print_r($row);
			if(password_verify($password,$row['password'])){
				// echo "verified";
				session_start();
				$_SESSION['login']=true;
				$_SESSION['username']=$row['username'];
				$_SESSION['id']=$row['User_ID'];
				
			}
			else{
				$error="*Sorry! Wrong password!!";
			}
		}
	}
}
if(isset($_SESSION['login']) && $_SESSION['login']==true){
	header('location: ./../main.php');
}
else{
	header('location: ./../main.php?login=&error='.$error);
	exit();
}	

?>