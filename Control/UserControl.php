<?php

header("Content-Type: text/xml");
require 'db.php';
require 'Mail.php';
require 'CodeGenerator.php';
// connect to DB
$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));
//status message
$sResponse = "<XML>";

if (isset($_GET['request'])) {

    switch ($_GET['request']) {

        case 'logout':
            session_start();
            session_unset();
            session_destroy();
            break;

        case 'logoutredirect':
            session_start();
            session_unset();
            session_destroy();
            break;

        case 'listusers':
            $level = (isset($_GET['level'])) ? $_GET['level'] : '';
            $sql = 'SELECT
		    id, name
		FROM
		    users
		WHERE
		    access_level="' . $level . '"
                ORDER BY name';
            $result = mysql_query($sql, $db);

            while ($row = mysql_fetch_array($result)) {
                $sResponse .= '<USER id=\'' . $row['id'] . '\'>' . $row['name'] . '</USER>';
            }
            mysql_free_result($result);
            break;

        case 'getuser':
            $id = (isset($_GET["id"])) ? $_GET["id"] : '';

            $sql = 'SELECT
		    id, name, email, access_level
		FROM
		    users
		WHERE
		    id="' . $id . '"';
            $result = mysql_query($sql, $db) or die(mysql_error($db));
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                extract($row);
                $sResponse .= '<INFO><ID>' . $id . '</ID><NAME>' . $name . '</NAME><MAIL>' . $email . '</MAIL><ACCESS_LEVEL>' . $access_level . '</ACCESS_LEVEL></INFO>';
            }
            mysql_free_result($result);
            break;

        case 'verify':
            $sResponse .= '<USERS>';
            if (isset($_GET['name'])) {
                $sName = trim($_GET['name']);
                $sql = 'SELECT
			id
		    FROM
			users
		    WHERE
			name="' . $sName . '"';
                $result = mysql_query($sql, $db);
                while ($row = mysql_fetch_array($result)) {
                    $sResponse .= '<USER>' . $row['id'] . '</USER>';
                }
                mysql_free_result($result);
                $sResponse .= '</USERS>';
            } else if (isset($_GET['mail'])) {
                $sMail = $_GET['mail'];
                $sql = 'SELECT
			id
		    FROM
			users
		    WHERE
			email="' . $sMail . '"';
                $result = mysql_query($sql, $db);
                while ($row = mysql_fetch_array($result)) {
                    $sResponse .= '<USER>' . $row['id'] . '</USER>';
                }
                mysql_free_result($result);
                $sResponse .='</USERS>';
            }
        case 'confirm':
            $codeGetted = (isset($_GET['code'])) ? $_GET['code'] : '';

            $sql = 'SELECT
		    email, password, name, access_level
		FROM
		    temp_users
		WHERE
		    code = "' . $codeGetted . '"';
            $result = mysql_query($sql, $db) or die(mysql_error($db));
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                extract($row);

                $sql = 'INSERT INTO users
			(email, password, name, access_level)
		    VALUES
		    ("' . mysql_real_escape_string($email, $db) . '",
		    PASSWORD("' . mysql_real_escape_string($password, $db) . '"), 
		    "' . mysql_real_escape_string($name, $db) . '",
		    "1")';
                mysql_query($sql, $db) or die(mysql_error($db));

                session_start();
                $_SESSION['id'] = mysql_insert_id($db);
                $_SESSION['access_level'] = $access_level;
                $_SESSION['name'] = $name;

                $sql = 'DELETE FROM			
			temp_users
		    WHERE
			code = "' . $codeGetted . '"';
                $result = mysql_query($sql, $db) or die(mysql_error($db));

                //$sResponse .= "<STATUS><p>Registration completed with success!</p></STATUS>
                //		<LOGIN><NAME>" . $name . "</NAME><ACCESS_LEVEL>" . $access_level . "</ACCESS_LEVEL></LOGIN>";
                header('Location: /UserPageCMS.php');
                return;
            }
            break;
    }
}


if (isset($_POST['request'])) {

    switch ($_POST['request']) {

        case 'login':
            $email = (isset($_POST['mail'])) ? $_POST['mail'] : '';
            $password = (isset($_POST['passw'])) ? $_POST['passw'] : '';
            $sql = 'SELECT
		    id, access_level, name
		FROM
		    users
		WHERE
		    email = "' . mysql_real_escape_string($email, $db) . '" AND
		    password = PASSWORD("' . mysql_real_escape_string($password, $db) . '")';
            $result = mysql_query($sql, $db) or die(mysql_error($db));
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                extract($row);
                session_start();
                $_SESSION['id'] = $id;
                $_SESSION['access_level'] = $access_level;
                $_SESSION['name'] = $name;
                $sResponse .= "<LOGIN><NAME>" . $name . "</NAME><ACCESS_LEVEL>" . $access_level . "</ACCESS_LEVEL></LOGIN>";
            }
            mysql_free_result($result);
            //sendmail("marzorati.andrea@gmail.com", "Vaults Of Pandius", "da cestinare");
            break;

        case 'register':
            $name = (isset($_POST['name'])) ? $_POST['name'] : '';
            $email = (isset($_POST['mail'])) ? $_POST['mail'] : '';
            $password_1 = (isset($_POST['passw'])) ? $_POST['passw'] : '';
            $password_2 = (isset($_POST['rpassw'])) ? $_POST['rpassw'] : '';
            $password = ($password_1 == $password_2) ? $password_1 : '';
            if (!empty($name) && !empty($email) && !empty($password)) {

                $code = random_string(30);

                $sql = 'INSERT INTO temp_users
			(email, password, name, access_level, code)
		    VALUES
		    ("' . mysql_real_escape_string($email, $db) . '",
		    PASSWORD("' . mysql_real_escape_string($password, $db) . '"), 
		    "' . mysql_real_escape_string($name, $db) . '",
		    "1",
		    "' . $code . '")';
                mysql_query($sql, $db) or die(mysql_error($db));

                //mail($email,"Registration to Vaults Of Pandius",$code);

                sendmail($email, "Registration to Vaults Of Pandius", "code: " . $_SERVER['SERVER_NAME'] . "Control/UserControl.php?request=confirm&code=$code");

                $sResponse .= "<STATUS><p>Step completed with success!</p><p>Now you will receive a mail with a text code.</p>
				  <p>Type it in the box below to complete the registration!</p></STATUS>";
            }
            break;

        case 'confirm':
            $codeGetted = (isset($_POST['code'])) ? $_POST['code'] : '';

            $sql = 'SELECT
		    email, password, name, access_level
		FROM
		    temp_users
		WHERE
		    code = "' . $codeGetted . '"';
            $result = mysql_query($sql, $db) or die(mysql_error($db));
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                extract($row);

                $sql = 'INSERT INTO users
			(email, password, name, access_level)
		    VALUES
		    ("' . mysql_real_escape_string($email, $db) . '",
		    PASSWORD("' . mysql_real_escape_string($password, $db) . '"), 
		    "' . mysql_real_escape_string($name, $db) . '",
		    "1")';
                mysql_query($sql, $db) or die(mysql_error($db));

                session_start();
                $_SESSION['id'] = mysql_insert_id($db);
                $_SESSION['access_level'] = $access_level;
                $_SESSION['name'] = $name;

                $sql = 'DELETE FROM			
			temp_users
		    WHERE
			code = "' . $codeGetted . '"';
                $result = mysql_query($sql, $db) or die(mysql_error($db));

                $sResponse .= "<STATUS><p>Registration completed with success!</p></STATUS>
				<LOGIN><NAME>" . $name . "</NAME><ACCESS_LEVEL>" . $access_level . "</ACCESS_LEVEL></LOGIN>";
            }
            break;

        case 'modify':
            $id = (isset($_POST['id'])) ? $_POST['id'] : '';
            $name = (isset($_POST['name'])) ? $_POST['name'] : '';
            $email = (isset($_POST['mail'])) ? $_POST['mail'] : '';
            $password_1 = (isset($_POST['passw'])) ? $_POST['passw'] : '';
            $password_2 = (isset($_POST['rpassw'])) ? $_POST['rpassw'] : '';
            $password = ($password_1 == $password_2) ? $password_1 : '';
            if (!empty($name) && !empty($email) && !empty($password)) {
                $sql = 'UPDATE users SET
			email = "' . mysql_real_escape_string($email, $db) . '", 
			password = PASSWORD("' . mysql_real_escape_string($password, $db) . '"), 
			name = "' . mysql_real_escape_string($name, $db) . '"
		    WHERE
			id="' . $id . '"';
                mysql_query($sql, $db) or die(mysql_error($db));
            }
            break;

        case 'modifyadmin':
            $id = (isset($_POST['id'])) ? $_POST['id'] : '';
            $name = (isset($_POST['name'])) ? $_POST['name'] : '';
            $email = (isset($_POST['mail'])) ? $_POST['mail'] : '';
            $level = (isset($_POST['level'])) ? $_POST['level'] : '';
            if (!empty($name) && !empty($email) && !empty($level)) {
                $sql = 'UPDATE users SET
			email = "' . mysql_real_escape_string($email, $db) . '", 
			access_level = "' . $level . '", 
			name = "' . mysql_real_escape_string($name, $db) . '"
		    WHERE
			id="' . $id . '"';
                mysql_query($sql, $db) or die(mysql_error($db));
            }
            $password = random_string(7);
            if (isset($_POST['passw'])) {
                $sql = 'UPDATE users SET
			password = PASSWORD("' . $password . '")
		    WHERE
			id="' . $id . '"';
                sendMail($email, "Reset password pandius.com", "Your new password is $password");
                mysql_query($sql, $db) or die(mysql_error($db));
            }

            break;
    }
}

$sResponse .= "</XML>";
echo $sResponse;

function redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        die('Could not redirect; Output was already sent to the browser.');
    }
}

?>