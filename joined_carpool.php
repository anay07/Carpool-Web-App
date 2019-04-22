<!-- ********************************** -->
<!-- PAGE TO DISPLAY DETAILS OF THE CARPOOL THE USER IS A MEMBER OF -->
<!-- ********************************** -->

<?php
include_once("check_login_status.php");
if($user_ok == false)
	header("location:signup.php");
$userid = $_SESSION["userid"];
?>

<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<style>
#section
{
width :57%;
height :500px;
border-radius: 5px;
font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Geneva, Verdana, sans-serif;
font-size: 26px;
float:left;
margin:0.5%;
text-align:center;
color: #FF3D66;
font-weight: 900;
}
</style>
<title>Home</title>
</head>

<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 text-align:center;></h2>
<br><br><br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">

<?php
echo $userid;
$sql=mysqli_query($db_conx, "SELECT user_id FROM carpool WHERE user_id=$userid LIMIT 1");
if(!$sql)
	echo("Error description: " . mysqli_error($db_conx));
$result=mysqli_num_rows($sql);
if($result>0){ 
	header("location:owner_carpool.php");
}
$query=mysqli_query($db_conx,"SELECT * FROM requests NATURAL JOIN request_status WHERE user=$userid AND status='1'");
$result=mysqli_num_rows($query);
if($result>0)
{	$q=mysqli_fetch_array($query);
	$pid= $q['carpool_id'];
	header("location:viewpool.php?carpool=".$pid."");
}
else
	echo "You have not joined any car pool yet !!!"
?>


<br><br>Click on <a href="available_carpool.php">Available Carpools</a> to join one.

</div>

<?php include_once("templates/template_options.php");  ?>

</body>

</html>