<?php
require 'connect.php';
session_start();
if( !isset($_SESSION["user_id"]) ){
    header("location:login.php");
}

  
  $leftX = $_POST['leftX'];
  $leftY = $_POST['leftY'];
  $turtle_id = $_POST['turtleId'];
  
  $leftName = "".$turtle_id."_Left";
$rightImage = $_POST['rightName'];
  //echo $rightImage;
  //echo "<br>";
$retsult = exec("python delaunay2D_plotDemo.py \"".$leftX."\" \"".$leftY."\" ".$leftName);

 // echo $result;
 // echo "<br>";
  
  $leftExists = file_exists("./Turtle/".$leftName.".png");
    $url = "selectPointRight.php?name=".$rightImage."&id=".$turtle_id;
     
if($leftExists)
    header('Location: '.$url);
else
    header("location:error.php");
//  echo "sudo python3 delaunay2D_plotDemo.py \"".$leftX."\" \"".$leftY."\" ".$leftName;    
?>
