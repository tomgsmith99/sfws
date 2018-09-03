<?php
/*
 * This script is intended as an educational tool.
 * Please look at the PHP SDK if you are looking for somthing suited to a new project.
 * https://github.com/janrain/Janrain-Sample-Code/tree/master/php/janrain-engage-php-sdk
 */

ob_start();
/*
 Below is a very simple and verbose PHP 5 script that implements the Engage token URL processing and some popular Pro/Enterprise examples.
 The code below assumes you have the CURL HTTP fetching library with SSL.  
*/

// PATH_TO_API_KEY_FILE should contain a path to a plain text file containing only
// your API key. This file should exist in a path that can be read by your web server,
// but not publicly accessible to the Internet.

// $rpx_api_key = trim( file_get_contents( "PATH_TO_API_KEY_FILE" ) );

// $rpx_api_key = trim( file_get_contents( "/home/users/web/b1854/moo.stylus/janrain/apiKey.txt" ) );

$pathToAPIkey = "/home/janrain/apiKey.txt";

/*
if (file_exists($pathToAPIkey)) { echo "<p>Found the api key file!"; }
else { echo "<p>Can't find the api key file!"; }
*/

$rpx_api_key = trim( file_get_contents($pathToAPIkey) );

/*
 Set this to true if your application is Pro or Enterprise.
 Set this to false if your application is Basic or Plus.
*/
$engage_pro = true;

/* STEP 1: Extract token POST parameter */
$token = $_POST['token'];

//Some output to help debugging
// echo "SERVER VARIABLES:\n";
// var_dump($_SERVER);
// echo "HTTP POST ARRAY:\n";
// var_dump($_POST);

if(strlen($token) == 40) {//test the length of the token; it should be 40 characters

  /* STEP 2: Use the token to make the auth_info API call */
  $post_data = array('token'  => $token,
                     'apiKey' => $rpx_api_key,
                     'format' => 'json',
                     'extended' => 'true'); //Extended is not available to Basic.

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//  curl_setopt($curl, CURLOPT_FAILONERROR, true);

  $result = curl_exec($curl);
  
  if ($result === false){
    echo "\n".'Curl error: ' . curl_error($curl);
    echo "\n".'HTTP code: ' . curl_errno($curl);
    echo "\n"; var_dump($post_data);
  }
  curl_close($curl);

  /* STEP 3: Parse the JSON auth_info response */
  $auth_info = json_decode($result, true);

  if ($auth_info['stat'] == 'ok') {
    echo "\n auth_info:";
    echo "\n"; var_dump($auth_info);

    /* STEP 4: Use the identifier as the unique key to sign the user into your system.
       This will depend on your website implementation, and you should add your own
       code here. The user profile is in $auth_info.
    */

    session_start(); 

    $_SESSION['user'] = $auth_info["profile"]["identifier"];
    $_SESSION["fname"] = $auth_info["profile"]["name"]["givenName"];

    echo "<P>this is the userID: " . $_SESSION['user'];

    $_SESSION['auth_info'] = $auth_info;

    echo "<P>this is the userID, extracted deeper from within the Session variable: " . $_SESSION['auth_info']["profile"]["identifier"];

    echo "<p>this is the URL, according to the parameter passed by the URL: " . $destURL;

    echo "<P>this is the URL, according to the Session variable: " . $_SESSION['destURL'];

    echo "<p><a href = '" . $_SESSION['url'] . "'>Click here to continue.</a></p>";

    } else {
      // Gracefully handle auth_info error.  Hook this into your native error handling system.
      echo "\n".'An error occured: ' . $auth_info['err']['msg']."\n";
      var_dump($auth_info);
      echo "\n";
      var_dump($result);
    }
} else {
  // Gracefully handle the missing or malformed token.  Hook this into your native error handling system.
  echo 'Authentication canceled.';
}

$debug_out = ob_get_contents();

ob_end_clean();

// header( 'Location: http://www.yoursite.com/new_page.html' ) ;

echo "<p>the value for the destURL is: " . $_SESSION['destURL'];

$redirectString = 'Location: ' . $_SESSION['destURL'];

header( $redirectString ) ;

?>


<html>
<head>
<title>Janrain Engage example</title>
</head>
<body>
<!-- content -->
<pre>
<?php echo $debug_out; ?>
</pre>
<!-- javascript -->
</body>
</html>
