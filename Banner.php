<div id="main">
    <div id="header">
        <div id="logobar">
			<a href="indexPage.php">
            <img src="images/logo_1.png" id="logo">
			</a>
        </div>		
        <div id="menu">
            <ul>
                <li id="liHome"><a href="indexPage.php">Home</a></li>
                <li id="liAtlas"><a href="Atlas.php">Atlas</a></li>
                <li id="liRules"><a href="Rules.php">Rules</a></li>
                <li id="liResources"><a href="Resources.php">Resources</a></li>
                <li id="liAdventures"><a href="Adventures.php">Adventures</a></li>
                <li id="liStories"><a href="Stories.php">Stories</a></li>						
                <li id="liSearch"><a href="Search.php">Search</a></li>
                <li id="liFAQ"><a href="FAQ.php">FAQ</a></li>
                <li id="liLinks"><a href="Links.php">Links</a></li>
            </ul>
        </div>
        <div class="blackSpace">			
        </div>
        <div id="hrdiv">
        </div>

        <div class="blackSpace">
        </div>
        <div id="logreg">
            <?php
            if (isset($_SESSION['id'])) {
                echo '<ul>
				    <li><span>Welcome ' . $_SESSION['name'] . '!<span></li>' .
                '<li id="liMyPage"><a href="UserPageCMS.php">MyPage</a></li>';
                if ($_SESSION['access_level'] == 3) {
                    echo '<li id="liAdminPage"><a href="AdminPageCMS.php">AdminPage</a></li>';
                }
		echo '<li id="liUpload"><a href="uploadFile.php">Upload File</a></li>';
                echo '<li><a id="aLogout" href="#" onclick="logout()">Logout</a></li></ul>';
            } else {

                echo '<ul>
				  <li><a id="aLogin" href="#" onclick="showLogin()">Login</a></li>' .
                '<li><a href="RegisterCMS.php">Registration</a></li>
				</ul>';
            }
            ?>			
        </div>
    </div>
    <div id="underline">
    </div>	
</div>	  
<script type="text/javascript">
    function selectMenu(id){
        document.getElementById(id).setAttribute("style","color:white;");
    }
</script>