<?php
include("account.php");
print "<br>Runtime error reporting is turned on";
error_reporting(E_ERROR|E_WARNING|E_PARSE|E_NOTICE);
ini_set('display_errors', 1);


$db = mysqli_connect($hostname,$username,$password,$project);
if(mysqli_connect_errno())
{
  echo "Failed to connect to MySQL :".mysqli_connect_error();
  exit();
  
}

print "You have successfully connected to MySQL database.<br>";

mysqli_select_db($db, $project);

$ucid = $_GET["ucid"]; 
print "<br> The UCID is: $ucid";
$account = $_GET["account"]; 
print "<br> The account is $account";
$amount = $_GET["amount"];
print "<br> The amount is $amount";


$s = "select * from accounts
      where ucid='$ucid'
      and account='$account'
      and balance + '$amount'>=0.00";

print "<br>SQL overdraw prevention: $s<br>";

($t=mysqli_query($db, $s)) or die(mysqli_error($db));


$s = "insert into transactions value ('$ucid','$amount','$account',NOW())";

print "<br> SQL insert statment is $s<br>";
($t=mysqli_query($db, $s)) or die(mysqli_error($db));


$s = "update accounts
      set balance = balance + '$amount', mostRecentTrans=NOW()
      where ucid = '$ucid'
      and account = '$account'
      and balance + '$amount'>=0.00";

print "<br>SQL Update Statment IS: $s<br>";

($t=mysqli_query($db, $s)) or die(mysqli_error($db));


?>