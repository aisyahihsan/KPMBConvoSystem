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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Maintain Graduate Information | CRS</title>
</head>

<body background="background.jpg">	
 	<form id="form1" name="form1" method="post" action="">
 	  <blockquote>
 	    <blockquote>
 	      <blockquote>
 	        <blockquote>
 	          <blockquote>
 	            <p>&nbsp;</p>
 	          </blockquote>
 	        </blockquote>
 	      </blockquote>
 	    </blockquote>
 	  </blockquote>
 	  <table width="352" border="0" align="center">
 	    <tr>
 	      <td width="346" align="center" bgcolor="#FFCC66"><blockquote>
 	        <blockquote>
 	          <p><img src="banner.png" width="585" height="260" /></p>
 	        </blockquote>
 	      </blockquote>
 	        <blockquote>
 	          <blockquote>
 	            <blockquote>&nbsp;
              </blockquote>
            </blockquote>
          </blockquote></td>
        </tr>
 	    <tr>
 	      <td height="58" align="center" valign="middle" bgcolor="#FFCC66"><blockquote>
 	        <blockquote>
 	          <p>MAINTAIN GRADUATE</p>
 	          <p>INFORMATION              </p>
 	        </blockquote>
 	      </blockquote></td>
        </tr>
 	    <tr>
 	      <td align="center" valign="middle" bgcolor="#FFCC66"><a href="addginfo.php">Add Graduate Information</a></td>
        </tr>
 	    <tr>
 	      <td align="center" valign="middle" bgcolor="#FFCC66"><a href="search &amp; delete grad.php">Delete Graduate Information</a></td>
        </tr>
 	    <tr>
 	      <td align="center" valign="middle" bgcolor="#FFCC66"><a href="search &amp; update grad.php">Update  Graduate Information</a></td>
        </tr>
 	    <tr>
 	      <td align="center" valign="middle" bgcolor="#FFCC66"><a href="adminmenu.php">MAIN MENU</a></td>
        </tr>
      </table>
 	  <p>&nbsp;</p>
 	  <blockquote>
 	    <p>&nbsp;</p>
 	  </blockquote>
 	  <p>&nbsp;</p>
</form>
</body>
</html>