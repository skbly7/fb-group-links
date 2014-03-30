<?php
$links=Array();
$from=Array();
$ids=Array();
$names=Array();
$k=0;
require 'login/src/facebook.php';
$facebook = new Facebook(array(
  'appId'  => '296121763865327',
  'secret' => 'You_read_its_secret_so_i_cant_give_it_to_you',
));
if(isset($_SESSION['fb_296121763865327_access_token']))
{
	$token=$_SESSION['fb_296121763865327_access_token'];
	$url="https://graph.facebook.com/153631204680678/feed?fields=link,name,message,from&limit=20000&access_token=".$token."&__paging_token=153631204680678_700381043339022";
	for($ii=0;$ii<10;$ii++)
	{
		if(( $jsondata= file_get_contents($url) )==false)
		{
			echo 'error, would be thankful, please inform me @ facebook.com/skbly7 Shivam Khandelwal';
			break;
		}
		$jsondata= utf8_encode($jsondata);
		$output = json_decode($jsondata,true);
		$results = $output['data'];

		foreach($results as $i)
		{
			if(isset($i['link']))
			{
			$links[$k]=$i['link'];
			$ids[$k]=$i['id'];
			$names[$k]=$i['name'];
			$from[$k++]=$i['from'];
			echo $i['link']."<br>";
			echo "<br>---------------------------------------------<br>";
			}
		}
		if(isset($output['paging']['next']))
			$url= $output['paging']['next'];
		else
			break;
	}
//	echo '<br><br>EOS<br><br>';
	if($k>100)
	{
		/* well end of script in short.. :P */
		file_put_contents("database.txt", json_encode($links));
		file_put_contents("id.txt", json_encode($ids));
		file_put_contents("from.txt", json_encode($from));
		file_put_contents("name.txt", json_encode($names));
		if (!empty($_SERVER['HTTP_REFERER']))
		header("Location: ".$_SERVER['HTTP_REFERER']."?msg=Synced Successfully");
		else
		header("Location: index.php?msg=Synced Successfully");
	//	echo 'Written too';
	}
	else
	{
	//	echo 'Not written';
		if (!empty($_SERVER['HTTP_REFERER']))
		header("Location: ".$_SERVER['HTTP_REFERER']."?msg=There was problem in updating");
		else
		header("Location: index.php?msg=There was problem in updating");
	}
}
else
{
//	echo 'Please login first..!!';
	if (!empty($_SERVER['HTTP_REFERER']))
    header("Location: ".$_SERVER['HTTP_REFERER']."?msg=You are not logged in");
	else
	header("Location: index.php?msg=You are not logged in");
}
?> 