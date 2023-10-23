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

$MM_restrictGoTo = "error.php";
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

mysql_select_db($database_convo, $convo);
$query_searchuser = "SELECT * FROM attendance ORDER BY idnumber ASC";
$searchuser = mysql_query($query_searchuser, $convo) or die(mysql_error());
$row_searchuser = mysql_fetch_assoc($searchuser);
$totalRows_searchuser = mysql_num_rows($searchuser);

$colname_displaystatus = "-1";
if (isset($_GET['idnumber'])) {
  $colname_displaystatus = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_displaystatus = sprintf("SELECT * FROM attendance WHERE idnumber = %s", GetSQLValueString($colname_displaystatus, "text"));
$displaystatus = mysql_query($query_displaystatus, $convo) or die(mysql_error());
$row_displaystatus = mysql_fetch_assoc($displaystatus);
$totalRows_displaystatus = mysql_num_rows($displaystatus);

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
<title>View Status | CRS</title>
</head>

<body background="background.jpg">
<form id="form1" name="form1" method="get" action="viewstatus.php">
  <table width="328" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <p>VIEW ATTENDANCE STATUS</p>
      </blockquote></td>
    </tr>
    <tr>
      <td width="228" bgcolor="#FFCC66">ID Number</td>
      <td width="353" bgcolor="#FFCC66"><label for="idnumber"></label>
        <label for="idnumber"></label>
        <select name="idnumber" id="idnumber">
          <?php
do {  
?>
          <option value="<?php echo $row_searchuser['idnumber']?>"><?php echo $row_searchuser['idnumber']?></option>
          <?php
} while ($row_searchuser = mysql_fetch_assoc($searchuser));
  $rows = mysql_num_rows($searchuser);
  if($rows > 0) {
      mysql_data_seek($searchuser, 0);
	  $row_searchuser = mysql_fetch_assoc($searchuser);
  }
?>
        </select>
      <input type="submit" name="button" id="button" value="SEARCH" /></td>
    </tr>
  </table>
</form>
<form method="POST" name="form1" id="form1">
  <table width="591" border="0" align="center">
    <tr>
      <td width="228" bgcolor="#FFCC66">ID Number:</td>
      <td width="353" bgcolor="#FFCC66"><label for="idnumber"></label>
      <input name="idnumber" type="text" id="idnumber" value="<?php echo $row_displaystatus['idnumber']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Attendance Status:</label></td>
      <td bgcolor="#FFCC66"><input name="password" type="text" id="password" value="<?php echo $row_displaystatus['attendstatus']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><a href="adminmenu.php">MAIN MENU</a></p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
<?php
mysql_free_result($searchuser);

mysql_free_result($displaystatus);
?>
