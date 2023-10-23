<?php require_once('Connections/convo.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usermenu2.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE graduate SET password=%s, studname=%s, icnumber=%s, studaddress=%s, studtelno=%s, course=%s, yearintake=%s WHERE idnumber=%s",
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['studname'], "text"),
                       GetSQLValueString($_POST['icnumber'], "text"),
                       GetSQLValueString($_POST['studaddress'], "text"),
                       GetSQLValueString($_POST['studtelno'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['yearintake'], "int"),
                       GetSQLValueString($_POST['idnumber'], "text"));

  mysql_select_db($database_convo, $convo);
  $Result1 = mysql_query($updateSQL, $convo) or die(mysql_error());

  $updateGoTo = "maintainginfo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_searchingUser = "-1";
if (isset($_GET['username'])) {
  $colname_searchingUser = $_GET['username'];
}
mysql_select_db($database_convo, $convo);
$query_searchingUser = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_searchingUser, "text"));
$searchingUser = mysql_query($query_searchingUser, $convo) or die(mysql_error());
$row_searchingUser = mysql_fetch_assoc($searchingUser);

$colname_searchuser = "-1";
if (isset($_GET['idnumber'])) {
  $colname_searchuser = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_searchuser = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_searchuser, "text"));
$searchuser = mysql_query($query_searchuser, $convo) or die(mysql_error());
$row_searchuser = mysql_fetch_assoc($searchuser);

$colname_update = "-1";
if (isset($_GET['idnumber'])) {
  $colname_update = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_update = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_update, "text"));
$update = mysql_query($query_update, $convo) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);

$colname_searchuser = "-1";
if (isset($_GET['idnumber'])) {
  $colname_searchuser = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_searchuser = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_searchuser, "text"));
$searchuser = mysql_query($query_searchuser, $convo) or die(mysql_error());
$row_searchuser = mysql_fetch_assoc($searchuser);
$totalRows_searchuser = mysql_num_rows($searchuser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Graduate Info | CRS</title>
</head>

<body background="background.jpg">
<form id="form1" name="form1" method="get" action="search &amp; update grad.php">
  <table width="328" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <p>UPDATE GRADUATE INFORMATION </p>
      </blockquote></td>
    </tr>
    <tr>
      <td width="110" bgcolor="#FFCC66">ID Number</td>
      <td width="471" bgcolor="#FFCC66"><label for="idnumber"></label>
        <input type="text" name="idnumber" id="idnumber" />
        <input type="submit" name="button2" id="button2" value="SEARCH" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
  <table width="590" border="0" align="center">
    <tr>
      <td width="108" bgcolor="#FFCC66">ID Number:</td>
      <td width="472" bgcolor="#FFCC66"><label for="idnumber"></label>
      <input name="idnumber" type="text" id="idnumber" value="<?php echo $row_update['idnumber']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Password:</label></td>
      <td bgcolor="#FFCC66"><input name="password" type="text" id="password" value="<?php echo $row_update['password']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Name:</td>
      <td bgcolor="#FFCC66"><input name="studname" type="text" id="studname" value="<?php echo $row_update['studname']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>IC Number:</label></td>
      <td bgcolor="#FFCC66"><input name="icnumber" type="text" id="icnumber" value="<?php echo $row_update['icnumber']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Address :</label></td>
      <td bgcolor="#FFCC66"><textarea name="studaddress" id="studaddress"><?php echo $row_update['studaddress']; ?></textarea></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Tel Number:</label></td>
      <td bgcolor="#FFCC66"><input name="studtelno" type="text" id="studtelno" value="<?php echo $row_update['studtelno']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Course:</td>
      <td bgcolor="#FFCC66"><input name="course" type="text" id="course" value="<?php echo $row_update['course']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Year Intake: </td>
      <td bgcolor="#FFCC66"><p>
        <input name="yearintake" type="text" id="yearintake" value="<?php echo $row_update['yearintake']; ?>" />
        <br />
      </p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><input name="usertype" type="hidden" id="usertype" value="user" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><input type="submit" name="button" id="button" value="SAVE" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><a href="adminmenu.php">MAIN MENU</a></p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_update" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($update);

mysql_free_result($searchuser);
?>
