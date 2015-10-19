<?php
/*
To check for results.. Levenshtein distance and exact matching both are used in it....
*/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Facebook Group Search - Demo</title>
    <style>
    body {
        background: #555 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAB9JREFUeNpi/P//PwM6YGLAAuCCmpqacC2MRGsHCDAA+fIHfeQbO8kAAAAASUVORK5CYII=);
        font: 13px 'Lucida sans', Arial, Helvetica;
        color: #eee;
        text-align: center;
    }
    
    a {
        color: #ccc;
    }
    
    
    .cf:before, .cf:after{
      content:"";
      display:table;
    }
    
    .cf:after{
      clear:both;
    }

    .cf{
      zoom:1;
    }
    
    .form-wrapper {
        width: 450px;
        padding: 15px;
        margin: 150px auto 50px auto;
        background: #444;
        background: rgba(0,0,0,.2);
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border-radius: 10px;
        -moz-box-shadow: 0 1px 1px rgba(0,0,0,.4) inset, 0 1px 0 rgba(255,255,255,.2);
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.4) inset, 0 1px 0 rgba(255,255,255,.2);
        box-shadow: 0 1px 1px rgba(0,0,0,.4) inset, 0 1px 0 rgba(255,255,255,.2);
    }
    
    .form-wrapper input {
        width: 330px;
        height: 20px;
        padding: 10px 5px;
        float: left;    
        font: bold 15px 'lucida sans', 'trebuchet MS', 'Tahoma';
        border: 0;
        background: #eee;
        -moz-border-radius: 3px 0 0 3px;
        -webkit-border-radius: 3px 0 0 3px;
        border-radius: 3px 0 0 3px;      
    }
    
    .form-wrapper input:focus {
        outline: 0;
        background: #fff;
        -moz-box-shadow: 0 0 2px rgba(0,0,0,.8) inset;
        -webkit-box-shadow: 0 0 2px rgba(0,0,0,.8) inset;
        box-shadow: 0 0 2px rgba(0,0,0,.8) inset;
    }
    
    .form-wrapper input::-webkit-input-placeholder {
       color: #999;
       font-weight: normal;
       font-style: italic;
    }
    
    .form-wrapper input:-moz-placeholder {
        color: #999;
        font-weight: normal;
        font-style: italic;
    }
    
    .form-wrapper input:-ms-input-placeholder {
        color: #999;
        font-weight: normal;
        font-style: italic;
    }    
    
    .form-wrapper button {
		overflow: visible;
        position: relative;
        float: right;
        border: 0;
        padding: 0;
        cursor: pointer;
        height: 40px;
        width: 110px;
        font: bold 15px/40px 'lucida sans', 'trebuchet MS', 'Tahoma';
        color: #fff;
        text-transform: uppercase;
        background: #d83c3c;
        -moz-border-radius: 0 3px 3px 0;
        -webkit-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;      
        text-shadow: 0 -1px 0 rgba(0, 0 ,0, .3);
    }   
      
    .form-wrapper button:hover{		
        background: #e54040;
    }	
      
    .form-wrapper button:active,
    .form-wrapper button:focus{   
        background: #c42f2f;    
    }
    
    .form-wrapper button:before {
        content: '';
        position: absolute;
        border-width: 8px 8px 8px 0;
        border-style: solid solid solid none;
        border-color: transparent #d83c3c transparent;
        top: 12px;
        left: -6px;
    }
    
    .form-wrapper button:hover:before{
        border-right-color: #e54040;
    }
    
    .form-wrapper button:focus:before{
        border-right-color: #c42f2f;
    }    
    
    .form-wrapper button::-moz-focus-inner {
        border: 0;
        padding: 0;
    }
    #mains {
	width:90%;
	color:black;
	margin-left:4.5%;
	height:auto;
	background:rgba(255,255,255,0.8);
	border-radius:15px;
	padding:20px;
    }
#poster{
border-radius:100%;
margin-bottom:-100px;
width:100px;
height:100px;
margin-left:50px;

}
.link2{
background:white;
width:120%;
padding-right:15%;
margin-right:15%;
color:black;
min-height:50px;
line-height:50px;
font-size:15px;
text-decoration:none;
padding-left:15%;
}
.link{
background:black;
width:120%;
padding-right:15%;
margin-right:15%;
text-decoration:none;
line-height:50px;
font-size:15px;
padding-left:15%;
}
.k{
text-align:right;
background:white;
border-radius:10px;
text-align:left;
margin-top:10px;
margin-bottom:10px;
overflow:hidden;
padding:10px;
}
.link a{
text-decoration:none;
}
.link2 a{
text-decoration:none;
color:black;
}
#next_button{
display:none;
font-size: 20px;
cursor: pointer;
}
    </style>
</head>

<body>

<form class="form-wrapper cf" action="check.php">
<?php
if(isset($_GET['url']))
{
?>
	<input type="text" name="url" placeholder="<?php echo $_GET['url'];?>" required>
<?php
}
else
{
?>
	<input type="text" name="url" placeholder="Search here..." required>
<?php
}
?>
	<button type="submit">Search</button>
</form>


<div id="player"></div>
<a id="next_button" nohref onClick="change_video()">(Next)</a>
<script src="http://www.youtube.com/player_api"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


<script>


	var youtube_id = [];
	$('.link').each(function(){
		var inner = $(this).text();
		var yt_id = inner.split('v=')[1];
		if(yt_id) {
			youtube_id.push(yt_id);
		}
	});
	
        // create youtube player
        var context;
	var youtube_id = [];
	var first_video = youtube_id[0];
        function onYouTubePlayerAPIReady() {
            $('.link').each(function(){
                    var inner = $(this).text();
                    var yt_id = inner.split('v=')[1];
                    if(yt_id) {
                            youtube_id.push(yt_id);
                    }
            });
            youtube_id.reverse();
            // create youtube player
	    var player;
	    var first_song = youtube_id.pop();
	    if(!first_song || first_song=='')
		return;
            player = new YT.Player('player', {
              height: '390',
              width: '640',
              videoId: youtube_id.pop(),
              events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
              }
            });
	    $('#next_button')[0].style.display="block";
	    context = player;
        }

        // autoplay video
        function onPlayerReady(event) {
            event.target.playVideo();
        }

        // when video ends
        function onPlayerStateChange(event) {  
		if(event.data === 0) {
			change_video();
			event.target.playVideo();
		}
        }

	function change_video() {
		if(youtube_id.length == 0) {
			$('#next_button')[0].style.display="none";
			alert("Lets go somewhere else, and listen those songs. This search query is done.");
			return;
		}
		context.loadVideoById(youtube_id.pop());
	}

</script>

<br/><br/>
<div id="mains">
Matching links are as follows :<br>



<?php
function print_new($id,$link,$name,$name_id,$by)
{

echo '
<div class="k">
<a href="http://www.facebook.com/'.$name_id.'" target="_blank"><img id="poster" src="https://graph.facebook.com/'.$name_id.'/picture?type=large"></img></a>
<div class="link2"><a href="http://www.facebook.com/'.$id.'" target="_blank">'.$name.'</a></div>
<div class="link"><a href="http://www.facebook.com/'.$id.'" target="_blank">'.$link.'</a></div>
</div>
';
}
$k=0;
$input = $_GET['url'];
$check='ss'.$input;
if(strpos($check,'http://www')==2)
{
$input=substr($input,10);
$check="";
}
if(strpos($check,'https://www')==2)
{
$input=substr($input,11);
$check="";
}
if(strpos($check,'http://')==2)
{
$input=substr($input,7);
$check="";
}
if(strpos($check,'https://')==2)
{
$input=substr($input,8);
$check="";
}
$input_old=$input;
$inputs = explode(" ",$input);
class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open('database/test.db');
  }
}
$db = new MyDB();
$no_result = TRUE;
foreach($inputs as $input) {
	$output = $db->query('SELECT * FROM data WHERE name LIKE "%'.$input.'" OR name LIKE "'.$input.'%" OR name LIKE "'.$input.'" OR name LIKE "%'.$input.'%" LIMIT 10');
	while($row = $output->fetchArray()) {
		$no_result = FALSE;
		print_new($row['id'], $row['link'], $row['name'],$row['name_id'],$row['by']);
	}
}
if($no_result) {
	echo '<div class="k">
	<div class="link2"><h2>No result found for the given keywords.</h2></div>
	</div>';
}
echo "Input word: $input_old\n<br>";


?>



<script type='text/javascript'>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36157444-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



</div>
<?php
require 'login/src/facebook.php';
$facebook = new Facebook(array(
  'appId'  => '296121763865327',
  'secret' => '77969da8c1b603110dfb119cc4038fc8',
));
$user = $facebook->getUser();
if ($user) {
?>
<div style="line-height:50px;background:rgba(80,80,80,0.5);margin:0 auto;font-size:30px;width:30%;">
<img src="http://graph.facebook.com/<?php echo $user; ?>/picture"></img><span style="margin:10px;">Welcome</span>
</div>
<a href="crawl_me.php"><img src="sync.png" alt="Sync"/></a><br>
<?php if(isset($_GET['msg'])){?>
<div style="background-color:white;color:black;"><?php echo $_GET['msg']; ?></div>
<?php }?>
<?php
}
else
{
?>
<a href="<?php echo $facebook->getLoginUrl(array("scope" => "user_groups")); ?>"><img src="fb.png" alt="Login" style="width:15%;"/></a>
<?php
}
?>

</body>
</html>
