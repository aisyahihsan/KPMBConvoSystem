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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO graduate (idnumber, password, studname, icnumber, studaddress, studtelno, course, yearintake, usertype) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['idnumber'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['studname'], "text"),
                       GetSQLValueString($_POST['icnumber'], "text"),
                       GetSQLValueString($_POST['studaddress'], "text"),
                       GetSQLValueString($_POST['studtelno'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['yearintake'], "int"),
                       GetSQLValueString($_POST['usertype'], "text"));

  mysql_select_db($database_convo, $convo);
  $Result1 = mysql_query($insertSQL, $convo) or die(mysql_error());

  $insertGoTo = "maintainginfo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_addata = "-1";
if (isset($_GET['idnumber'])) {
  $colname_addata = $_GET['idnumber'];
}
mysql_select_db($database_convo, $convo);
$query_addata = sprintf("SELECT * FROM graduate WHERE idnumber = %s", GetSQLValueString($colname_addata, "text"));
$addata = mysql_query($query_addata, $convo) or die(mysql_error());
$row_addata = mysql_fetch_assoc($addata);
$totalRows_addata = mysql_num_rows($addata);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Graduate Info | CRS</title>
</head>

<body background="background.jpg">	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
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
      <td colspan="2" align="center" bgcolor="#FFCC66">ADD GRADUATE INFORMATION</td>
    </tr>
    <tr>
      <td width="108" align="left" bgcolor="#FFCC66">ID Number:</td>
      <td width="356" align="left" bgcolor="#FFCC66"><label>
        <input name="idnumber" type="text" id="idnumber" />
      </label></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Password:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="password" type="text" id="password" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Name:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="studname" type="text" id="studname" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66">IC Number:</td>
      <td align="left" bgcolor="#FFCC66"><label>
        <input name="icnumber" type="text" id="icnumber" />
      </label></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Address :</label></td>
      <td align="left" bgcolor="#FFCC66"><textarea name="studaddress" id="studaddress"></textarea></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Tel Number:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="studtelno" type="text" id="studtelno" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66"><label>Course:</label></td>
      <td align="left" bgcolor="#FFCC66"><input name="course" type="text" id="course" /></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#FFCC66">Year Intake: </td>
      <td align="left" bgcolor="#FFCC66"><input name="yearintake" type="text" id="yearintake" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFCC66"><p>
        <input name="usertype" type="hidden" id="usertype" value="user" />
      </p></td>
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
mysql_free_result($addata);
?>
