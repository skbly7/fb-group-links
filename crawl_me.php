<?php

$links=Array();
$from=Array();
$ids=Array();
$names=Array();
$k=0;

require 'login/src/facebook.php';

class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open('database/test.db');
  }
}
$db = new MyDB();
if(!$db){
  echo "Go tell administrator, that error #".$db->lastErrorMsg()." came. Lets see if he can save you.";
} else {
  echo "Opened database successfully\n";
}

$facebook = new Facebook(array(
  'appId'  => '296121763865327',
  'secret' => '77969da8c1b603110dfb119cc4038fc8',
));

if(isset($_SESSION['fb_296121763865327_access_token']))
{
	$token=$_SESSION['fb_296121763865327_access_token'];
	$url="https://graph.facebook.com/327559000672561/feed?fields=link,name,message,from&limit=9&access_token=".$token."&__paging_token=153631204680678_700381043339022";


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

			// See let us be clear, I don't care even if you hack this database.
			// I seriously don't give even a flying fuck to you

			// But lets play hide and seek. I know you are better, and if you think you do
			// then just make a pull request with security patch, as I am damn too lazy.

			$query = 'INSERT INTO data (link, id, name, by, name_id) VALUES ("';
                        $query .= $i['link'].'","';
                        $query .= $i['id'].'","';
                        $query .= $i['name'].'","';
			$query .= $i['from']['name'].'","';
                        $query .= $i['from']['id'].'")';
			$i = $db->exec($query);
#			echo $i['link']."<br>";
#			echo "<br>---------------------------------------------<br>";
			}
		}
		if(isset($output['paging']['next']))
			$url= $output['paging']['next'];
		else
			break;
	}
#	if($k>100)
#	{
#		/* well end of script in short.. :P */
#		file_put_contents("database.txt", json_encode($links));
#		file_put_contents("id.txt", json_encode($ids));
#		file_put_contents("from.txt", json_encode($from));
#		file_put_contents("name.txt", json_encode($names));
		if (!empty($_SERVER['HTTP_REFERER']))
		header("Location: ".$_SERVER['HTTP_REFERER']."?msg=Synced Successfully");
		else
		header("Location: index.php?msg=Synced Successfully");
#	//	echo 'Written too';
#	}
#	else
#	{
#	//	echo 'Not written';
#		if (!empty($_SERVER['HTTP_REFERER']))
#		header("Location: ".$_SERVER['HTTP_REFERER']."?msg=There was problem in updating");
#		else
#		header("Location: index.php?msg=There was problem in updating");
#	}

}
else
{
//	echo 'Please login first..!!';
	if (!empty($_SERVER['HTTP_REFERER']))
    header("Location: ".$_SERVER['HTTP_REFERER']."?msg=You are not logged in");
	else
	header("Location: index.php?msg=You are not logged in");
}

#?> 
