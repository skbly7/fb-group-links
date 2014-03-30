<?php

require 'src/facebook.php';
$facebook = new Facebook(array(
  'appId'  => '296121763865327',
  'secret' => '7aac4747f052196b40047e1d3529b2ad',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.


// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl();
}

?>
<!doctype html>
  <head>
    <title>Click to continue</title>
  </head>
  <body>
    <?php if (!$user) { ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">Login</a>
      </div>
    <?php
	}
	else {
	?>
	    <a href="../crawl_me.php?token=">Refresh</a>
	<?php } ?>

    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>
  </body>
</html>
