<?php require_once('Connections/convo.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "user";
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

$MM_restrictGoTo = "adminmenu.php";
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
<?php $_SESSION["MM_Username"];
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
  $updateSQL = sprintf("UPDATE graduate SET password=%s, studname=%s, icnumber=%s, studaddress=%s, studtelno=%s, course=%s, yearintake=%s, usertype=%s WHERE idnumber=%s",
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['studname'], "text"),
                       GetSQLValueString($_POST['icnumber'], "text"),
                       GetSQLValueString($_POST['studaddress'], "text"),
                       GetSQLValueString($_POST['studtelno'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['yearintake'], "int"),
                       GetSQLValueString($_POST['usertype'], "text"),
                       GetSQLValueString($_POST['idnumber'], "text"));

  mysql_select_db($database_convo, $convo);
  $Result1 = mysql_query($updateSQL, $convo) or die(mysql_error());

  $updateGoTo = "maintaingraduateuser.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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

$colname_edituser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_edituser = $_SESSION['MM_Username'];
}
mysql_select_db($database_convo, $convo);
$query_edituser = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_edituser, "text"));
$edituser = mysql_query($query_edituser, $convo) or die(mysql_error());
$row_edituser = mysql_fetch_assoc($edituser);
$totalRows_edituser = mysql_num_rows($edituser);

$colname_editgraduate = "-1";
if (isset($_GET['idnumber'])) {
  $colname_editgraduate = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_editgraduate = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_editgraduate, "text"));
$editgraduate = mysql_query($query_editgraduate, $convo) or die(mysql_error());
$row_editgraduate = mysql_fetch_assoc($editgraduate);

mysql_select_db($database_convo, $convo);
$query_displaydelete = "SELECT * FROM graduate";
$displaydelete = mysql_query($query_displaydelete, $convo) or die(mysql_error());
$row_displaydelete = mysql_fetch_assoc($displaydelete);

$colname_displaydelete = "-1";
if (isset($_GET['idnumber'])) {
  $colname_displaydelete = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_displaydelete = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_displaydelete, "text"));
$displaydelete = mysql_query($query_displaydelete, $convo) or die(mysql_error());
$row_displaydelete = mysql_fetch_assoc($displaydelete);

$colname_searchingdisplay = "-1";
if (isset($_GET['idnumber'])) {
  $colname_searchingdisplay = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_displaydelete = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_displaydelete, "text"));
$displaydelete = mysql_query($query_displaydelete, $convo) or die(mysql_error());
$row_displaydelete = mysql_fetch_assoc($displaydelete);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Graduate Info | CRS</title>
</head>

<body background="background.jpg">
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
   <?php $_SESSION["MM_Username"]; ?><table width="426" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p>EDIT GRADUATE INFORMATION
      </p></td>
    </tr>
    <tr>
      <td width="108" bgcolor="#FFCC66">ID Number:</td>
      <td width="308" bgcolor="#FFCC66"><label for="idnumber">
        <input name="idnumber" type="text" id="idnumber" value="<?php echo $row_edituser['idnumber']; ?>" readonly="readonly" />
      </label></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Password:</label></td>
      <td bgcolor="#FFCC66"><input name="password" type="text" id="password" value="<?php echo $row_edituser['password']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Name:</td>
      <td bgcolor="#FFCC66"><input name="studname" type="text" id="studname" value="<?php echo $row_edituser['studname']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>IC Number:</label></td>
      <td bgcolor="#FFCC66"><input name="icnumber" type="text" id="icnumber" value="<?php echo $row_edituser['icnumber']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Address :</label></td>
      <td bgcolor="#FFCC66"><textarea name="studaddress" id="studaddress"><?php echo $row_edituser['studaddress']; ?></textarea></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Tel Number:</label></td>
      <td bgcolor="#FFCC66"><input name="studtelno" type="text" id="studtelno" value="<?php echo $row_edituser['studtelno']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Course:</td>
      <td bgcolor="#FFCC66"><input name="course" type="text" id="course" value="<?php echo $row_edituser['course']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Year Intake: </td>
      <td bgcolor="#FFCC66"><p>
        <input name="yearintake" type="text" id="yearintake" value="<?php echo $row_edituser['yearintake']; ?>" />
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
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><a href="usermenu2.php">MAIN MENU</a></p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_update" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($edituser);
?>
