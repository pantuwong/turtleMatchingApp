<?php
  session_start();
  print_r($_POST);

  $turtleName = $_POST['turtleName'];
  $turtleType =  $_POST['turtleType'];
  $leftFile = $_POST['filenameLeft'];
  $rightFile = $_POST['filenameRight'];
  $profileFile = $_POST['filenameProfile'];
  $ageYear = $_POST['ageYear'];
  $ageMonth = $_POST['ageMonth'];
  $microchipCode = $_POST['microchipCode'];
  $microchipPos = $_POST['microchipPos'];
  $tagCode = $_POST['tagCode'];
  $tagPos = $_POST['tagPost'];
  $width = $_POST['width'];
  $length = $_POST['length'];
  $weight = $_POST['weight'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  $userid = $_SESSION['user_id'];

$sql1 = "INSERT INTO turtle (turtle_name, turtle_left, turtle_right, turtle_profile, turtle_type, turtle_age_moth, turtle_age_year, turtle_microchip_code, turtle_microchip_pos, turtle_tag_code, turtle_tag_pos)
VALUES ('".$turtleName."', '".leftFile."', '".$rightFile."' , '".$profileFile."', '".$turtleType."', '".ageMonth."', '".$ageYear."', '".$microchipCode."','".$microchipPos."',".$tagCode."','".$tagPos."')";


if (mysqli_query($conn, $sql1)) {
    $last_id = mysqli_insert_id($conn);
    echo "New record created successfully. Last inserted ID is: " . $last_id;
    
    $sql2 = "INSERT INTO found (turtle_id, user_id, found_weight, found_width, found_length, found_lat, found_lng ) VALUES ('".$last_id."', '".$userid."', '".$weight."', '".length."'".$latitude."'".$longitude."'") ";
    
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}




?>