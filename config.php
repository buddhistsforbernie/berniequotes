<?php 

session_start(); 

$username="bquotes57";
$password="bquotes5718";
$database="bquotes";
$host="localhost";

$adminpass="6b1e0r2n)-(";

$isAdmin = isset($_SESSION['isAdmin']);

$con = mysqli_connect($host,$username,$password, $database);

if (mysqli_connect_errno() || $con == null)
	die("Failed to connect to MySQL: " . mysqli_connect_error());

function banIP() {
	$sql="INSERT INTO banned (ip) VALUES('".$_SERVER["REMOTE_ADDR"]."')";
	$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 

}

$sql="SELECT id FROM banned WHERE ip='".$_SERVER["REMOTE_ADDR"]."'";
$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 

if(mysqli_num_rows($query) > 0 ) {
		error_log("banned: ".$_SERVER["REMOTE_ADDR"]);
		$_SESSION['error'] = "IP banned - contact admin";
	   die('{"error":"'.$_SESSION['error'].'"}');
}
