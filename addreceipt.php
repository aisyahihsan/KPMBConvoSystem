<?php require_once('Connections/convo.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO receipt (referenceid, paystatus, datetransaction, accno, receivebank, amount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['referenceid'], "text"),
                       GetSQLValueString($_POST['paystatus'], "text"),
                       GetSQLValueString($_POST['datetransaction'], "text"),
                       GetSQLValueString($_POST['accno'], "text"),
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

$colname_addreceipt = "-1";
if (isset($_GET['referenceid'])) {
  $colname_addreceipt = $_GET['referenceid'];
}
mysql_select_db($database_convo, $convo);
$query_addreceipt = sprintf("SELECT * FROM receipt WHERE referenceid = %s", GetSQLValueString($colname_addreceipt, "text"));
$addreceipt = mysql_query($query_addreceipt, $convo) or die(mysql_error());
$row_addreceipt = mysql_fetch_assoc($addreceipt);
$totalRows_addreceipt = mysql_num_rows($addreceipt);

$colname_graduate = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_graduate = $_SESSION['MM_Username'];
}
mysql_select_db($database_convo, $convo);
$query_graduate = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_graduate, "text"));
$graduate = mysql_query($query_graduate, $convo) or die(mysql_error());
$row_graduate = mysql_fetch_assoc($graduate);
$totalRows_graduate = mysql_num_rows($graduate);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO graduate (paymentstatus, referenceid, datetransaction, accountnumber, receivebank, amount) VALUES (%s, %s, %s, %s, %s, %s)",
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
<title>Add Receipt Info | CRS</title>
</head>

<body background="background.jpg">	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <p>
    <?php $_SESSION["MM_Username"]; ?>
  </p>
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
      </blockquote>
    </blockquote>
  </blockquote>
  <table width="474" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <blockquote>
          <blockquote>
            <p><img src="banner.png" width="585" height="260" /></p>
          </blockquote>
        </blockquote>
      </blockquote></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66">ADD RECEIPT INFORMATION</td>
    </tr>
    <tr>
      <td width="170" align="left" bgcolor="#FFCC66">Payment Status:</td>
      <td width="411" align="left" bgcolor="#FFCC66" id="paymentstatus"><label for="paystatus"></label>
        <select name="paystatus" size="1" id="paystatus">
          <option>Select Below:</option>
          <option>Paid</option>
          <option>Not Paid</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Reference ID:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="referenceid" type="text" id="referenceid" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Date Transaction:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="datetransaction" type="text" id="datetransaction" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66">Account Number:</td>
      <td align="left" bgcolor="#FFCC66"><label>
        <input name="accno" type="text" id="accno" />
      </label></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Receive Bank:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="receivebank" type="text" id="receivebank" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66">Amount:</td>
      <td align="left" bgcolor="#FFCC66"><input name="amount" type="text" id="amount" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><input type="submit" name="button" id="button" value="SAVE" /></td>
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
  <input type="hidden" name="MM_insert" value="form1" />
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
mysql_free_result($addreceipt);

mysql_free_result($graduate);
?>
