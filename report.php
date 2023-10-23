<?php require_once('Connections/convo.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_report = 2;
$pageNum_report = 0;
if (isset($_GET['pageNum_report'])) {
  $pageNum_report = $_GET['pageNum_report'];
}
$startRow_report = $pageNum_report * $maxRows_report;

mysql_select_db($database_convo, $convo);
$query_report = "SELECT attendance.idnumber, attendance.referenceid, attendance.attendstatus, graduate.studname, graduate.icnumber, graduate.studtelno, graduate.course, graduate.yearintake, receipt.paystatus FROM attendance INNER JOIN graduate ON graduate.idnumber = attendance.idnumber INNER JOIN receipt ON receipt.referenceid = attendance.referenceid WHERE attendance.attendstatus = 'Attend' ORDER BY attendance.idnumber";
$query_limit_report = sprintf("%s LIMIT %d, %d", $query_report, $startRow_report, $maxRows_report);
$report = mysql_query($query_limit_report, $convo) or die(mysql_error());
$row_report = mysql_fetch_assoc($report);

if (isset($_GET['totalRows_report'])) {
  $totalRows_report = $_GET['totalRows_report'];
} else {
  $all_report = mysql_query($query_report);
  $totalRows_report = mysql_num_rows($all_report);
}
$totalPages_report = ceil($totalRows_report/$maxRows_report)-1;

$maxRows_reportnt = 2;
$pageNum_reportnt = 0;
if (isset($_GET['pageNum_reportnt'])) {
  $pageNum_reportnt = $_GET['pageNum_reportnt'];
}
$startRow_reportnt = $pageNum_reportnt * $maxRows_reportnt;

mysql_select_db($database_convo, $convo);
$query_reportnt = "SELECT attendance.idnumber, attendance.referenceid, attendance.attendstatus, graduate.studname, graduate.icnumber, graduate.studtelno, graduate.course, graduate.yearintake, receipt.paystatus FROM attendance INNER JOIN graduate ON graduate.idnumber = attendance.idnumber INNER JOIN receipt ON receipt.referenceid = attendance.referenceid WHERE attendance.attendstatus = 'Not Attend' ORDER BY attendance.idnumber";
$query_limit_reportnt = sprintf("%s LIMIT %d, %d", $query_reportnt, $startRow_reportnt, $maxRows_reportnt);
$reportnt = mysql_query($query_limit_reportnt, $convo) or die(mysql_error());
$row_reportnt = mysql_fetch_assoc($reportnt);

if (isset($_GET['totalRows_reportnt'])) {
  $totalRows_reportnt = $_GET['totalRows_reportnt'];
} else {
  $all_reportnt = mysql_query($query_reportnt);
  $totalRows_reportnt = mysql_num_rows($all_reportnt);
}
$totalPages_reportnt = ceil($totalRows_reportnt/$maxRows_reportnt)-1;

$queryString_report = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_report") == false && 
        stristr($param, "totalRows_report") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_report = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_report = sprintf("&totalRows_report=%d%s", $totalRows_report, $queryString_report);

$queryString_reportnt = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_reportnt") == false && 
        stristr($param, "totalRows_reportnt") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_reportnt = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_reportnt = sprintf("&totalRows_reportnt=%d%s", $totalRows_reportnt, $queryString_reportnt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Attendance Information Report | CRS</title>
</head>

<body>
<form id="form1" name="form1" method="get" action="report.php">
  <table width="328" border="0" align="center">
    <tr>
      <td width="581" align="center" bgcolor="#FFCC66"><p><img src="banner.png" width="585" height="260" /></p></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFCC66"><blockquote>
        <p>ATTENDANCE INFORMATION REPORT</p>
      </blockquote></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFCC66"><a href="adminmenu.php">MAIN MENU</a></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFCC66"><label for="attendstatus"></label>
      <label for="attendstatus">
        <button onclick="myFunction()">Print</button>
        <script>
function myFunction() {
  window.print();
}
        </script>
      </label></td>
    </tr>
  </table>
</form>
<form id="form2" name="form2" method="post" action="">
  <p>&nbsp;</p>
  <table width="200" border="2" align="center">
    <tr>
      <td colspan="10" align="center" bgcolor="#FFCC66">ATTENDANCE INFORMATION REPORT (ATTEND)</td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">&nbsp;</td>
      <td bgcolor="#FFCC66">ID Number</td>
      <td bgcolor="#FFCC66">Name</td>
      <td bgcolor="#FFCC66">IC Number</td>
      <td bgcolor="#FFCC66">Tel Number</td>
      <td bgcolor="#FFCC66">Course</td>
      <td bgcolor="#FFCC66">Year Intake</td>
      <td bgcolor="#FFCC66">Reference ID</td>
      <td bgcolor="#FFCC66">Payment Status</td>
      <td bgcolor="#FFCC66">Attendance Status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_report['idnumber']; ?></td>
        <td><?php echo $row_report['studname']; ?></td>
        <td><?php echo $row_report['icnumber']; ?></td>
        <td><?php echo $row_report['studtelno']; ?></td>
        <td><?php echo $row_report['course']; ?></td>
        <td><?php echo $row_report['yearintake']; ?></td>
        <td><?php echo $row_report['referenceid']; ?></td>
        <td><?php echo $row_report['paystatus']; ?></td>
        <td><?php echo $row_report['attendstatus']; ?></td>
      </tr>
      <?php } while ($row_report = mysql_fetch_assoc($report)); ?>
  </table>
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
                        <p>Total Attend:&nbsp;<?php echo $totalRows_report ?> </p>
                        <p><a href="<?php printf("%s?pageNum_report=%d%s", $currentPage, 0, $queryString_report); ?>">First</a> <a href="<?php printf("%s?pageNum_report=%d%s", $currentPage, max(0, $pageNum_report - 1), $queryString_report); ?>">Previous</a> <a href="<?php printf("%s?pageNum_report=%d%s", $currentPage, min($totalPages_report, $pageNum_report + 1), $queryString_report); ?>">Next</a> <a href="<?php printf("%s?pageNum_report=%d%s", $currentPage, $totalPages_report, $queryString_report); ?>">Last</a></p>
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
  <table width="200" border="2" align="center">
    <tr>
      <td colspan="10" align="center" bgcolor="#FFCC66">ATTENDANCE INFORMATION REPORT (NOT ATTEND)</td>
    </tr>
    <tr>
      <td bgcolor="#FFCC66">&nbsp;</td>
      <td bgcolor="#FFCC66">ID Number</td>
      <td bgcolor="#FFCC66">Name</td>
      <td bgcolor="#FFCC66">IC Number</td>
      <td bgcolor="#FFCC66">Tel Number</td>
      <td bgcolor="#FFCC66">Course</td>
      <td bgcolor="#FFCC66">Year Intake</td>
      <td bgcolor="#FFCC66">Reference ID</td>
      <td bgcolor="#FFCC66">Payment Status</td>
      <td bgcolor="#FFCC66">Attendance Status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_reportnt['idnumber']; ?></td>
        <td><?php echo $row_reportnt['studname']; ?></td>
        <td><?php echo $row_reportnt['icnumber']; ?></td>
        <td><?php echo $row_reportnt['studtelno']; ?></td>
        <td><?php echo $row_reportnt['course']; ?></td>
        <td><?php echo $row_reportnt['yearintake']; ?></td>
        <td><?php echo $row_reportnt['referenceid']; ?></td>
        <td><?php echo $row_reportnt['paystatus']; ?></td>
        <td><?php echo $row_reportnt['attendstatus']; ?></td>
      </tr>
      <?php } while ($row_reportnt = mysql_fetch_assoc($reportnt)); ?>
  </table>
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
                        <p>Total Not Attend:&nbsp;<?php echo $totalRows_reportnt ?> </p>
                        <p><a href="<?php printf("%s?pageNum_reportnt=%d%s", $currentPage, 0, $queryString_reportnt); ?>">First</a> <a href="<?php printf("%s?pageNum_reportnt=%d%s", $currentPage, max(0, $pageNum_reportnt - 1), $queryString_reportnt); ?>">Previous</a> <a href="<?php printf("%s?pageNum_reportnt=%d%s", $currentPage, min($totalPages_reportnt, $pageNum_reportnt + 1), $queryString_reportnt); ?>">Next</a> <a href="<?php printf("%s?pageNum_reportnt=%d%s", $currentPage, $totalPages_reportnt, $queryString_reportnt); ?>">Last</a></p>
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
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($report);

mysql_free_result($reportnt);
?>
