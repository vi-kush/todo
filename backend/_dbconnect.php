<?php
  $server = "localhost";
  $username = "root";
  $password = "";
  $database = "crud";
  

  $conn = mysqli_connect($server,$username,$password,$database);
  
  if(!$conn){
    echo "error occured ". mysqli_connect_error();
    // $strr=mysqli_connect_error();
    // if(substr($strr,0,16)=='Unknown database'){
    //   $conn = mysqli_connect($server,$username,$password);
    //   $sql='create database crud1';
    //   $result = mysqli_query($conn,$sql);
    //   $sql='CREATE TABLE `crud1`.`task` ( `task_id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(100) NOT NULL , `task` TEXT NOT NULL , `user` VARCHAR(16) NOT NULL DEFAULT 'anormus' , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`task_id`)) ENGINE = InnoDB;';
    //   $result = mysqli_query($conn,$sql);
    //   header("location: _dbconnect.php")
    // }
   }
   
  function CMD($var){
    global $conn;
    $r = mysqli_query($conn,$var);
    if(!$r){
      return false;
    }
    return true;
  }
?>