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
  $updateSQL = sprintf("UPDATE attendance SET referenceid=%s, attendstatus=%s WHERE idnumber=%s",
                       GetSQLValueString($_POST['referenceid'], "text"),
                       GetSQLValueString($_POST['attendstatus'], "text"),
                       GetSQLValueString($_POST['idnumber'], "text"));

  mysql_select_db($database_convo, $convo);
  $Result1 = mysql_query($updateSQL, $convo) or die(mysql_error());

  $updateGoTo = "maintainattendance.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_editattendance = "-1";
if (isset($_GET['idnumber'])) {
  $colname_editattendance = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_editattendance = sprintf("SELECT * FROM attendance WHERE idnumber = %s", GetSQLValueString($colname_editattendance, "text"));
$editattendance = mysql_query($query_editattendance, $convo) or die(mysql_error());
$row_editattendance = mysql_fetch_assoc($editattendance);
$totalRows_editattendance = mysql_num_rows($editattendance);

mysql_select_db($database_convo, $convo);
$query_searchuser = "SELECT idnumber FROM graduate ORDER BY idnumber ASC";
$searchuser = mysql_query($query_searchuser, $convo) or die(mysql_error());
$row_searchuser = mysql_fetch_assoc($searchuser);
$totalRows_searchuser = mysql_num_rows($searchuser);

$varidnumber_Recordset1 = "-1";
if (isset($_GET["idnumber"])) {
  $varidnumber_Recordset1 = $_GET["idnumber"];
}


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
<title>Edit Attendance Info | CRS</title>
</head>

<body background="background.jpg">	
<form id="form1" name="form1" method="get" action="editattendanceinfo.php">
  <table width="328" border="0" align="center">
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <p>EDIT ATTENDANCE INFORMATION</p>
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
<input type="submit" name="button2" id="button2" value="SEARCH" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
 
  <table width="588" border="0" align="center">
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>ID Number:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="idnumber" type="text" id="idnumber" value="<?php echo $row_editattendance['idnumber']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Reference ID:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="referenceid" type="text" id="referenceid" value="<?php echo $row_editattendance['referenceid']; ?>" /></td>
    </tr>
    <tr>
      <td width="226" align="left" bgcolor="#FFCC66">Status:</td>
      <td width="352" align="left" bgcolor="#FFCC66"><label for="attendstatus"></label>
        <select name="attendstatus" id="attendstatus" title="<?php echo $row_editattendance['attendstatus']; ?>">
          <option>Select Below:</option>
          <option>Attend</option>
          <option>Not Attend</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><input type="submit" name="button" id="button" value="SAVE" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><blockquote>
        <blockquote>
          <blockquote>
            <blockquote>
              <p><a href="adminmenu.php">MAIN MENU</a></p>
            </blockquote>
          </blockquote>
        </blockquote>
      </blockquote></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_update" value="form1" />
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
mysql_free_result($editattendance);

mysql_free_result($searchuser);

?>
