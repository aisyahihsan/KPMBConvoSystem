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

$colname_viewreceipt = "-1";
if (isset($_GET['referenceid'])) {
  $colname_viewreceipt = $_GET['referenceid'];
}
mysql_select_db($database_convo, $convo);
$query_viewreceipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_viewreceipt, "text"));
$viewreceipt = mysql_query($query_viewreceipt, $convo) or die(mysql_error());
$row_viewreceipt = mysql_fetch_assoc($viewreceipt);
$totalRows_viewreceipt = mysql_num_rows($viewreceipt);

mysql_select_db($database_convo, $convo);
$query_referenceid = "SELECT referenceid FROM receipt ORDER BY referenceid ASC";
$referenceid = mysql_query($query_referenceid, $convo) or die(mysql_error());
$row_referenceid = mysql_fetch_assoc($referenceid);
$totalRows_referenceid = mysql_num_rows($referenceid);

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
<title>View Receipt | CRS</title>
</head>

<body background="background.jpg">
<form id="form1" name="form1" method="get" action="viewreceipt.php">
  <table width="328" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <p>VIEW RECEIPT INFORMATION</p>
      </blockquote></td>
    </tr>
    <tr>
      <td width="228" bgcolor="#FFCC66">Reference ID</td>
      <td width="353" bgcolor="#FFCC66"><label for="idnumber"></label>
        <label for="referenceid"></label>
        <select name="referenceid" id="referenceid" title="<?php echo $row_search['referenceid']; ?>">
          <?php
do {  
?>
          <option value="<?php echo $row_referenceid['referenceid']?>"><?php echo $row_referenceid['referenceid']?></option>
          <?php
} while ($row_referenceid = mysql_fetch_assoc($referenceid));
  $rows = mysql_num_rows($referenceid);
  if($rows > 0) {
      mysql_data_seek($referenceid, 0);
	  $row_referenceid = mysql_fetch_assoc($referenceid);
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
      <td width="228" bgcolor="#FFCC66">Reference ID:</td>
      <td width="353" bgcolor="#FFCC66"><label for="referenceid"></label>
      <input name="referenceid" type="text" id="referenceid" value="<?php echo $row_viewreceipt['referenceid']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Payment Status:</td>
      <td bgcolor="#FFCC66"><input name="paystatus" type="text" id="paystatus" value="<?php echo $row_viewreceipt['paystatus']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Date Transaction:</td>
      <td bgcolor="#FFCC66"><label for="datetransaction"></label>
      <input name="datetransaction" type="text" id="datetransaction" value="<?php echo $row_viewreceipt['datetransaction']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Account Number:</td>
      <td bgcolor="#FFCC66"><label for="accno"></label>
      <input name="accno" type="text" id="accno" value="<?php echo $row_viewreceipt['accno']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">Receive Bank:</td>
      <td bgcolor="#FFCC66"><label for="receivebank"></label>
      <input name="receivebank" type="text" id="receivebank" value="<?php echo $row_viewreceipt['receivebank']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66"><label>Amount:</label></td>
      <td bgcolor="#FFCC66"><label for="amount"></label>
      <input name="amount" type="text" id="amount" value="<?php echo $row_viewreceipt['amount']; ?>" readonly="readonly" /></td>
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
mysql_free_result($viewreceipt);

mysql_free_result($referenceid);
?>
