<?php

  require("./backend/_dbconnect.php");

  $empty_title = false;
  $empty_del = false;
  $is_del = false;
  $is_ins = false;
  $is_upd = false;
  $hide_form= false;
  $edit = array('id'=>0, 'selected'=>false);

  session_start();

  if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=='POST' ){
    
      $title = $_POST['title'];
      $task = $_POST['task'];
      if($title==""){
        $empty_title = true;
      }
      else{
        if(isset($_SESSION['login']) && $_SESSION['login']==true){
          $id=$_SESSION['id'];
          if( CMD("INSERT INTO `tasks` (`task_id`, `title`, `task`, `user`, `timestamp`) VALUES (NULL, '$title', '$task', $id, CURRENT_TIMESTAMP)")){
          $is_ins=true;
          }
          else{
            echo mysqli_error($conn);
          }   
        }
        else{
          if( CMD("INSERT INTO `tasks` (`task_id`, `title`, `task`, `user`, `timestamp`) VALUES (NULL, '$title', '$task', NULL, CURRENT_TIMESTAMP)")){
            $is_ins=true;
          }
          else{
            echo mysqli_error($conn);
          }
        }
      }
  }

  if(isset($_POST['show']) && $_SERVER['REQUEST_METHOD']=='POST' ){
    
    if($hide_form){
      $hide_form=false;
    }
  }

  elseif($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['delete'])){
    if(!isset($_POST['mark'])){
      $empty_del = true;
    }
    else{
      $del=$_POST['mark'];
      //print_r ($del);
      foreach($del as $id){
        $sql='delete from tasks where task_id='.$id;
        $result=mysqli_query($conn,$sql);
        echo mysqli_error($conn);
      }
      if($result){
        $is_del=true;
      }
    }
  } 

  elseif($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['del'])){
    $del=$_POST['del'];
    //print_r ($del);
    $sql='delete from tasks where task_id='.$del;
    $result=mysqli_query($conn,$sql);
    echo mysqli_error($conn);
    if($result){
      $is_del=true;
    }
  }
  
  elseif(isset($_POST['edit'])){

    $edit['id'] = $_POST['edit'];
    // echo $edit;
    $edit['selected'] = true;
    $sql='select title, task from tasks where task_id='.$edit['id'];
    $result=mysqli_query($conn,$sql);
    //var_dump(mysqli_fetch_row($result));
    list(0=>$edit['title'], 1=>$edit['task'])=mysqli_fetch_row($result);
    //var_dump($edit);
    
  }
  
  elseif($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['update'])){
    $title=$_POST['title'];
    $task=$_POST['task'];
    $id=$_POST['update'];

    $sql = "UPDATE `tasks` SET `title` = '$title', `task` = '$task' WHERE `tasks`.`task_id` = ".$id;
    $result=mysqli_query($conn,$sql);
    //echo $sql;
    if($result){
      $is_upd=true;
    }
  }
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Vikush</title>
  </head>
  <body>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script>

      // Modal initialize 
      $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
      })

      // toast initialize
      $(document).ready(function(){
        $('.toast').toast('show');
      });
    </script>

  <?php  
  if(isset($_SESSION['login']) && $_SESSION['login']==true){
    $user=$_SESSION['username'];
    echo '
  <nav class="navbar navbar-expand-sm navbar-dark bg-secondary ">
  <a class="navbar-brand py-0" href="#">'.ucfirst($user).'</a> ';}
  else{
    echo '
  <nav class="navbar navbar-expand-sm navbar-dark bg-secondary ">
  <a class="navbar-brand py-0" href="#">Tasks</a> ';}
  ?>
  <button class="navbar-toggler py-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
    <form class="form-inline my-1 my-sm-0">
      <div class="input-group">
        <input class="form-control ml-sm-2 " type="search" name="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-outline-light  my-0" type="submit" name="srch" formmethod="get" formaction="../schedule_mgmt/main.php">Search</button>
        </div>
      </div>
    </form>
    <ul class="navbar-nav">
      <li class="nav-item">
      <form class="form-inline" method="get" action="./main.php">
        <div class="btn-group ml-1 ml-sm-3">
        <?php
          if(isset($_SESSION['login']) && $_SESSION['login']==true){
            echo '<button class="btn btn-secondary px-1" type="submit" name="logout" >logout</button>';
          }
          else{
            echo '
              <button class="btn btn-secondary px-1" type="submit" name="login" >login</button>
              <button class="btn btn-secondary px-1 ml-1" type="submit" name="signup" >Signup</button>
            ';
          }
        ?>
        </div>
      </form>
      </li>
      <!-- add avtar -->
    </ul>
  </div>
	</nav>

    <?php
    if(isset($_GET['login']) && $_SERVER['REQUEST_METHOD']=='GET'){
      echo
          '<div class="position-fixed top-0 right-0 p-3" style="z-index: 10; right: 0; top: 10;">
            <div class="toast" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
              <strong class="mr-auto">login</strong>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="toast-body">
            <form class="form-inline">
              <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text px-2">@</span>
              </div>
                <input type="text" name="username" class="form-control" placeholder="Username">
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
              <button class="btn btn-sm btn-outline-primary px-5 mx-auto mt-1" type="submit" name="log" formmethod="post" formaction="./backend/_login.php">Login</button>
              </form>';
              if(isset($_GET['success'])){ echo '<span class="text-success ml-1">'.$_GET['success'].'</span>';}
              if(isset($_GET['error'])){ echo '<span class="text-danger ml-1">'.$_GET['error'].'</span>';}
              echo'
              </div>
            </div>
          </div>';                    #log
      }
      if(isset($_GET['signup'])&&$_SERVER['REQUEST_METHOD']=='GET'){
        //global $error;
        echo
            '<div class="position-fixed top-0 right-0 p-3" style="z-index: 5; right: 0; top: 10;">
              <div class="toast" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <strong class="mr-auto">Sign up</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="toast-body">
              <form class="form-inline">
                <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text px-2">@</span>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Username" size="35">
                </div>       
                <div class="input-group">
                  <input type="password" name="password" class="form-control" placeholder="Password" maxlength="16">
                  <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password">
                </div>
                <button class="btn btn-sm btn-outline-primary px-5 mx-auto mt-1" type="submit" name="sign" formmethod="post" formaction="./backend/_signup.php">Sign in</button>
                </form>';if(isset($_GET['error'])){ echo '<span class="text-danger ml-1">'.$_GET['error'].'</span>';}
                echo'
                </div>
              </div>
            </div>';                  #sign
        }
        if(isset($_GET['logout']) && $_SERVER['REQUEST_METHOD']=='GET'){
          echo
              '<div class="position-fixed top-0 right-0 p-3" style="z-index: 5; right: 0; top: 10;">
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                  <strong class="mr-auto">Logged out sucessfully!</strong>
                  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                </div>
              </div>';
          if(isset($_SESSION['login']) && $_SESSION['login']==true){  
            session_unset();
            session_destroy();   
            header('location: ./main.php?logout='); 
          }
        }
    ?>

    <?php
      if($empty_title && isset($_POST['title'])){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>ERROR!</strong> Please enter a valid Title.!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
      }
      if($empty_del && !isset($_POST['mark'])){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>ERROR!</strong> Please Select items to delete.!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>';
      }
      if($is_del){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Deleted!</strong> Tasks are deleted from List!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
      }
      if($is_ins){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Inserted!</strong> Task inserted into List!!
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
      }
      if($is_upd){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Updated!</strong> Task updated successfully!!
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
      }
      if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['srch'])){

        $count=0;
        $search = $_GET['search'];
        if(!empty($search)){
          if(isset($_SESSION['login']) && $_SESSION['login']==true){
            $id=$_SESSION['id'];
            $sql = "select task_id, title, task from tasks where (user = '$id' AND (task like '%$search%' OR title like '%$search%'))";
          }
          else{
            $sql = "select task_id, title, task from tasks where (user is NULL AND (task like '%$search%' OR title like '%$search%'))";
          }
          $result=mysqli_query($conn,$sql);
          if(!$result){
            echo mysqli_error($conn);
          }
          else{
            if(mysqli_num_rows($result) == 0 ){
            // $hee=mysqli_num_rows($result); 
            //   var_dump($hee);
              echo '<div class="d-flex justify-content-center p-2">
              <div class="toast" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                  <strong class="mr-auto ml-1">Search results '.$count.'</strong>
                  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="toast-body">
                  <strong class="ml-3"> No Results Found :( </strong> 
                </div>
              </div> 
            </div>';
            }
            while($row=mysqli_fetch_assoc($result)){
              //print_r($row);
              echo' 
              <div class="d-flex justify-content-center p-2">
                <div class="toast" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="toast-header">
                    <strong class="mr-auto ml-1">Search results '.++$count.'</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="toast-body">
                    <strong class="ml-3"> Title : </strong> '.$row['title'].'<br>
                    <strong class="ml-3"> Task : </strong> '.$row['task'].'
                  </div>
                </div> 
              </div>';
            }
            $hide_form=true;
          }
        }
      }
    ?>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form  action="./main.php" method="post">
              <div class="container mt-1">
                <div class="form-group" >
                  <label for="title"> Title: </label>
                  <input type="text" class="form-control" value="<?php
                  print $edit['title']; 
                  ?>" name="title" id="title">
                </div>
                <div class="form-group">
                  <label for="task"> Task: </label>
                  <textarea class="form-control" name="task" id="task" rows="3"><?php
                  echo $edit['task']; 
                  ?> </textarea>
                </div>
               </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="update" value="<?php
                  echo $edit['id']; 
                  ?>" id="update">Save changes</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <form  action="./main.php" method="post">
    <?php 
      if(!$hide_form){
        echo '
            <div class="container mt-1">
              <div class="form-group" >
                <label for="title"> Title: </label>
                <input type="text" class="form-control" placeholder="Title of task" name="title" id="title">
              </div>
              <div class="form-group">
                <label for="task"> Task: </label>
                <input type="text" class="form-control" placeholder="Describe Task" name="task" id="task">
              </div>
              <input type="submit" class="btn btn-outline-dark" value="Add"  name="submit" id="sumbit">
              <input type="reset" class="btn btn-outline-dark" value="clear"  name="reset" id="sumbit">
            </div>';
      }
      else{
        echo '<div class="container mt-2 ">
        <button type="submit" name="show" class="btn btn-secondary" >close</button>
        </div>';
      }
    ?>
    </form>
    <form action="./main.php" method='post'>
      <div class="container-fluid mt-3" style="background-color: rgb(220, 255, 251);" >
        <div class="row ">
          <div class="col-1 my-1"><!-- <input type="checkbox" id="marked" name="" value="markall"> --></div>  
          <div class="col-1 my-1 " style="font-weight: bold; ">S.No</div>  
          <div class="col-2 my-1 " style="font-weight: bold; ">Title</div>  
          <div class="col-4 my-1 " style="font-weight: bold; ">Task</div>  
          <div class="col-2 my-1 " style="font-weight: bold; ">Date</div>   
          <div class="col-2 my-1"></div>   
        </div>
        <hr>
        <?php
        if(isset($_SESSION['login']) && $_SESSION['login']==true){
          $id=$_SESSION['id'];
          $q = "select * from tasks where user=".$id;
        }
        else{
          $q = "select * from tasks where user is NULL";
        }
          $result = mysqli_query($conn,$q);
          $sno=0;
          while($row = mysqli_fetch_assoc($result)){
            ++$sno;
            echo '<div class="row mb-1"><div class="col-1 mt-1">
            <input type="checkbox" id="marked" name="mark[]" value="'.$row['task_id'].'">
          </div> <div class="col-1 " >'.$sno.'</div>
            <div class="col-2 " >'.$row['title'].'</div>  
            <div class="col-4 " >'.$row['task'].'</div>  
            <div class="col-4 col-md-2 " >'.substr($row['timestamp'],8,2)."-".substr($row['timestamp'],5,2)."-".substr($row['timestamp'],0,4)." |".substr($row['timestamp'],10,6).'</div>  
            <div class="col-3 col-md-2 " >
              <div class="btn-group btn-group-sm " role="group">';
               if(!($edit['selected'] && $edit['id'] == $row['task_id'])){ 
                echo '
                <button name="edit" value="'.$row['task_id'].'" class=" btn btn-sm btn-outline-warning " >
                  select</button>'; }
                if($edit['selected'] && $edit['id'] == $row['task_id']){
                echo '
                <button type="button" data-toggle="modal" data-target="#exampleModal" class=" btn btn-sm btn-warning p-1" >
                  Update</button>
                <button name="del" value="'.$row['task_id'].'" class=" btn btn-sm btn-danger p-1" >
                  delete</button>'; }
                echo ' 
              </div>
            </div>    
          </div>';
          }
        ?>
        </div>
        <div class="container mt-2">
          <input type="submit" name="delete" class="btn btn-outline-dark" value="delete">
        </div>
    </form>
  </body>
</html>


<?php mysqli_close($conn); ?>
