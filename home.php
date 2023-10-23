<?php require_once('Connections/convo.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_convo, $convo);
$query_login = "SELECT * FROM graduate";
$login = mysql_query($query_login, $convo) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['idnumber'])) {
  $loginUsername=$_POST['idnumber'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "usertype";
  $MM_redirectLoginSuccess = "adminmenu.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_convo, $convo);
  	
  $LoginRS__query=sprintf("SELECT idnumber, password, usertype FROM graduate WHERE idnumber=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $convo) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'usertype');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HOME | Convocation Registration System</title>
</head>

<body background="background.jpg">
<p>&nbsp;</p>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="294" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFFFFF"><blockquote>
        <blockquote>
          <p><img src="banner.png" width="585" height="260" /></p>
        </blockquote>
      </blockquote></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><strong>WELCOME TO CONVOCATION REGISTRATION SYSTEM</strong></td>
    </tr>
    <tr>
      <td width="101" bgcolor="#FFCC66">ID</td>
      <td width="183" bgcolor="#FFCC66"><label for="idnumber"></label>
      <input type="text" name="idnumber" id="idnumber" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Password</td>
      <td bgcolor="#FFCC66"><label for="password"></label>
      <input type="password" name="password" id="password" /></td>
    </tr>
    <tr align="center">
      <td colspan="2" bgcolor="#FFCC66"><input type="submit" name="button" id="button" value="Login" /></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($login);
?>
