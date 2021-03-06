<?php
session_start();
require_once __DIR__ . '/Facebook/autoload.php';
require 'connect.php';

$fb = new Facebook\Facebook([
  'app_id' => '161713021336907',
  'app_secret' => 'e4dbd79e0e6da4d75019803b487214d2', 
  'default_graph_version' => 'v2.10',
  ]);


$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
//echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('161713021336907'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  //echo '<h3>Long-lived</h3>';
  //var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;


$json = file_get_contents("https://graph.facebook.com/me?fields=id,first_name,last_name,picture,email&access_token=".$accessToken->getValue());
$obj = json_decode($json);

$_SESSION['user_id'] = $obj->id;
$_SESSION['user_email'] = $obj->email;
$_SESSION['user_picture'] = $obj->picture;
$_SESSION['user_firstname'] = $obj->first_name;
$_SESSION['user_lastname'] = $obj->last_name;


if($conn->connect_error){
	die("Connection failed: ". $conn->connect_error);
}else{
	$sql =  "SELECT * FROM users WHERE user_id='".$obj->id."'";
	$result = mysqli_query($conn, $sql);
	if( mysqli_num_rows($result) > 0)
	{
        $row = $result->fetch_assoc();
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_firstname'] = $row['user_firstname'];
        $_SESSION['user_lastname'] = $row['user_lastname'];
        $_SESSION['user_nickname'] = $row['user_nickname'];
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_phone'] =$row['user_phone'];
        $_SESSION['user_role'] = $row['user_role'];
        
    
		header('Location: index.php');
	}
	else{
		header('Location: register.php');
	}
}

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>
