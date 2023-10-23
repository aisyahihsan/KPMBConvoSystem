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

$colname_searchreceipt = "-1";
if (isset($_GET['referenceid'])) {
  $colname_searchreceipt = $_GET['referenceid'];
}
mysql_select_db($database_convo, $convo);
$query_searchreceipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_searchreceipt, "text"));
$searchreceipt = mysql_query($query_searchreceipt, $convo) or die(mysql_error());
$row_searchreceipt = mysql_fetch_assoc($searchreceipt);
$totalRows_searchreceipt = mysql_num_rows($searchreceipt);

$colname_displayreceipt = "-1";
if (isset($_GET['referenceid'])) {
  $colname_displayreceipt = $_GET['referenceid'];
}
mysql_select_db($database_convo, $convo);
$query_displayreceipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_displayreceipt, "text"));
$displayreceipt = mysql_query($query_displayreceipt, $convo) or die(mysql_error());
$row_displayreceipt = mysql_fetch_assoc($displayreceipt);
$totalRows_displayreceipt = mysql_num_rows($displayreceipt);

$colname_displayreceipt = "-1";
if (isset($_GET['referenceid'])) {
  $colname_displayreceipt = $_GET['referenceid'];
}
mysql_select_db($database_convo, $convo);
$query_displayreceipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_displayreceipt, "text"));
$displayreceipt = mysql_query($query_displayreceipt, $convo) or die(mysql_error());
$row_displayreceipt = mysql_fetch_assoc($displayreceipt);

$colname_receipt = "-1";
if (isset($_SESSION['MM_Reference'])) {
  $colname_receipt = $_SESSION['MM_Reference'];
}
mysql_select_db($database_convo, $convo);
$query_receipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_receipt, "text"));
$receipt = mysql_query($query_receipt, $convo) or die(mysql_error());
$row_receipt = mysql_fetch_assoc($receipt);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO receipt (paymentstatus, referenceid, datetransaction, accountnumber, receivebank, amount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['paymentstatus'], "text"),
                       GetSQLValueString($_POST['referenceid'], "text"),
                       GetSQLValueString($_POST['datetransaction'], "text"),
                       GetSQLValueString($_POST['accountnumber'], "text"),
                       GetSQLValueString($_POST['receivebank'], "text"),
                       GetSQLValueString($_POST['amount'], "text"));
  mysql_select_db($database_convo, $convo);
  $Result1 = mysql_query($insertSQL, $convo) or die(mysql_error());

  $insertGoTo = "maintainreceipt.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Receipt Info | CRS</title>
</head>

<body background="background.jpg">	
<form id="form1" name="form1" method="get" action="deletereceipt.php">
  <table width="328" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <p>DELETE RECEIPT INFORMATION </p>
      </blockquote></td>
    </tr>
    <tr>
      <td width="171" bgcolor="#FFCC66">Reference ID: </td>
      <td width="410" bgcolor="#FFCC66"><label for="referenceid"></label>
        <input type="text" name="referenceid" id="referenceid" />
      <input type="submit" name="button2" id="button2" value="SEARCH" /></td>
    </tr>
  </table>
</form>
<form action="deletereceipt2.php" method="get" name="form1" id="form1">
  <table width="594" border="0" align="center">
    <tr>
      <td width="173" align="left" bgcolor="#FFCC66">Payment Status:</td>
      <td width="411" align="left" bgcolor="#FFCC66" id="paymentstatus"><label for="paystatus"></label>        <input name="paystatus" type="text" id="paystatus" value="<?php echo $row_displayreceipt['paystatus']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Reference ID:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="referenceid" type="text" id="referenceid" value="<?php echo $row_displayreceipt['referenceid']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Date Transaction:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="datetransaction" type="text" id="datetransaction" value="<?php echo $row_displayreceipt['datetransaction']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66">Account Number:</td>
      <td align="left" bgcolor="#FFCC66"><label>
        <input name="accno" type="text" id="accno" value="<?php echo $row_displayreceipt['accno']; ?>" readonly="readonly" />
      </label></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Receive Bank:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="receivebank" type="text" id="receivebank" value="<?php echo $row_displayreceipt['receivebank']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Amount:</label></td>
      <td align="left" bgcolor="#FFCC66"><p>
        <input name="amount" type="text" id="amount" value="<?php echo $row_displayreceipt['amount']; ?>" readonly="readonly" />
      </p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><input type="submit" name="button" id="button" value="DELETE" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <blockquote>
          <blockquote>
            <blockquote>
              <p><a href="usermenu2.php">MAIN MENU</a></p>
            </blockquote>
          </blockquote>
        </blockquote>
      </blockquote></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<blockquote>
  <blockquote>
    <blockquote>
      <blockquote>
        <blockquote>
          <blockquote>
            <blockquote>
              <blockquote>
                <blockquote>
                  <blockquote>
                    <blockquote>
                      <blockquote>
                        <blockquote>&nbsp;</blockquote>
                      </blockquote>
                    </blockquote>
                  </blockquote>
                </blockquote>
              </blockquote>
            </blockquote>
          </blockquote>
        </blockquote>
      </blockquote>
    </blockquote>
  </blockquote>
</blockquote>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($searchreceipt);

mysql_free_result($displayreceipt);
?>
