<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
http://www.w3.org/TR/html4/loose.dtd>

<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/zoom_out_magnifier_32.png" />
<link rel="stylesheet" href="Style/base.css"/>
<!--<link rel="stylesheet" href="stylesheet.css"/>-->
<title>Home</title>
<style>
  
  #menu li#liHome a{
	  color:white;
  }
  
  #welcomecontent{
	float:left;
	width:60%;
	padding-left:20px;	
  }  

  #updatecontent{
	float:right;
	width:25%;
	padding-right:20px;
  }
  
  #updatecontent ul{
	list-style-type:none;
	margin:0px;
	padding:0px;
	font-family:"Myriad Pro", Arial, sans-serif;
	padding-top:5px;
	font-size:16px;	  
  }

  #updatecontent li{
  }
  
  #updatecontent li a{
	text-decoration:none;
	color: #666;
	font-weight: normal;
	font-size: 0.9em;
	font-family:sans-serif;
  }

  #updatecontent a:hover{
	text-decoration: underline;
  }

  #newscontent{	
	float:left;
	/*height: 30%;*/
	padding-top:45px;			
  }
  
  div.hr {
	height: 1px;
	margin: 2px auto;
	border-top: 1px solid #999;
	width: 90%;
  }
  
  div.hr_news {
	height: 1px;
	margin: 2px auto;
	border-top: 1px dashed #999;
	width: 100%;
	clear:both;
  }

  .infobox {
	margin:2% 5%;		
  }
  
  .newsbox {
	margin:1% 5% 2% 5%;
  }
  
  .news_header{
	width:90%;
	margin:1% auto;
  }
  
  .news_data{
	float:right;
	color:#07b;
	font-family:"Myriad Pro", Arial, sans-serif;
	font-size:16px;	
  }

  .news_title{
	padding-left:5px;
	float:left;
	font-family:"Myriad Pro", Arial, sans-serif;
	font-size:16px;	
	color: #07b;
  }
  
  .news_title a{
	text-decoration:none;
  }
  
    
</style>
<script type="text/javascript" src="Script/zxml.js"></script>
<script type="text/javascript" src="Script/Login.js"></script>
<script type="text/javascript" src="Script/Xml.js"></script>
<script type="text/javascript" src="Script/Post.js"></script>
<script type="text/javascript" src="Script/Login.js"></script>
<script type="text/javascript" src="Script/Index.js"></script>
</head>

<body onload="getLast(5)">
<?php include 'Banner.php' ?>
    <div id="central">
 		<div id=welcomecontent class="shadowbox">
 			<h3>Welcome to the Officia Mystara Homepage</h3>
 			<div class="hr"></div>
 			<div class="infobox">
				<p>Welcome to the official website for the Dungeons and Dragons setting of Mystara.
 				Inside you will find a great deal of new material which furthers the Mystara setting.
 				The official websites for the other Dungeons and Dragons settings are accessible through
 				the Wizards of the Coast Other Worlds page and are discussed on the Other Published Worlds Message Board.
 				</p><!--<br/><br/>--><p>
				You may become a part of the process by joining in on discussions at the Mystara Message Boards, on the Mystara Mailing List or in a more active forum at The Piazza. Alternatively you can directly email any submissions to me at
				webmaster@pandius.com</p>
			</div>
 		</div>
		<div id=updatecontent class="shadowbox">
			<h3>Last Update</h3>
			<div class="hr"></div>
                        <div id="last" class="infobox">
				
			</div>
 		</div>

 		<div id=newscontent>
			<h2>News</h2>
			<div class="hr"></div>
			<div class="news_header">
				<div class="news_title">Info</div>
				<div class="news_data">01-04-2011</div>
				<div class="hr_news"></div>
			</div>
			<div class="newsbox">As part of the Geocities relocation project email me your Mystaran webpages and they can be hosted here on the Vaults.
				webmaster	@	pandius.com
				Teaser sample of M3E, the combined Mystara 3E tome.
			</div>
				<div class="news_header">
						<div class="news_title">News</div>
						<div class="news_data">25-03-2011</div>
						<div class="hr_news"></div>
				</div>
			<div class="newsbox">The Mystara ESDs are no longer available. Wizards are reviewing the situation.
				D&D Alumni from WotC, includes a reprint of Basic Set's Castle Mistamere, and Bargle as a 4E 3rd level controller.
				Video - Introduction to Mystara: The Known World by Damon Brown.
				The authors page links to separate pages for each author listing all the works for that author.
				A list of files on the Vaults of Pandius. This list of direct links was requested for those with download managers so that they could download the whole of the Vaults with relative ease. The option of making available a zipped version of the Vaults which might be preferred is not currently an option. Updated 27 January 2009.
			</div>
				<div class="news_header">
						<div class="news_title">News</div>
						<div class="news_data">25-03-2011</div>
						<div class="hr_news"></div>
				</div>
			<div class="newsbox">The Mystara ESDs are no longer available. Wizards are reviewing the situation.
				D&D Alumni from WotC, includes a reprint of Basic Set's Castle Mistamere, and Bargle as a 4E 3rd level controller.
				Video - Introduction to Mystara: The Known World by Damon Brown. Video - Introduction to Mystara: The Known World by Damon Brown.
			</div>
		</div>
</div>
<?php include 'Footer.php' ?>	
</body>
</html>