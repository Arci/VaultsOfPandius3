<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
http://www.w3.org/TR/html4/loose.dtd>
<?php session_start(); ?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <title>Vaults of Pandius Rules</title>
    <link rel="stylesheet" href="Style/base.css"/>
    <style type="text/css" media="all">@import "Style/style.css";</style>
    <style>
    
	 #menu li#liRules a{
		  color:white;
	  }
	  
	  #content {
	      overflow-y: scroll;
	      height: 93%;
	      padding-left:10px;
	      padding-right:10px;
	      margin-top:20px;
	  }
	
	h3 {
	    padding-left: inherit;
	    margin-bottom: inherit;
	}

	h2 {
	    padding-left: inherit;
	    margin-bottom: inherit;
	}
	
	#LoginMail{
	    padding-left: 7%;
	    margin-bottom: 0px;
	}
	
	#LoginPassword{
	    padding-left: 7%;
	    margin-bottom: 0px;
	}
	
	#content a {
	    text-decoration:none;
	    color:#08c;
	    font-weight: bold;
	  }
	
    </style>
    <script type="text/javascript" src="zxml.js"></script>
    <script type="text/javascript" src="Script/Xml.js"></script>
    <script type="text/javascript" src="Script/Login.js"></script>
    <script type="text/javascript" src="Script/Post.js"></script>
</head>
<body>
  <?php include 'Banner.php' ?>  
  <div id="mainStaticContent" class="shadowbox">
    <div id=content >
      <?php include 'RulesContent.html' ?> 
    </div>
  </div>
  <?php include 'Footer.php' ?> 
</body>
</html>