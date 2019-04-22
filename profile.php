<?php
include_once("check_login_status.php");

$id = "";

if(isset($_GET["userid"])){
	$id = preg_replace('#[^a-z0-9]#i', '', $_GET['userid']);
}else
   		{ exit(1);
	}
?>



<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<title>Profile</title>
<style>
#left
{
padding-right: 6%;
float:left;
text-align:right;
color: #330066;
width:44%;
}
#right
{
color: #6633CC ;
width:44%;
float:left;
text-align:left;
padding-left: 6%;
}
</style>
</head>

<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 style="color:#660066; text-shadow: 5px 5px #c0c0c0;" text-align:center;> 
Profile
</h2>
<br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">
<?php $resu = mysqli_fetch_array(mysqli_query($db_conx, "SELECT * FROM users WHERE id=$id")); ?>
<?php if($resu["gender"]=='m')
		$t="Male";
	else
		$t="Female"; 
$sql = "SELECT * FROM carpool WHERE user_id=$id ";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
$resum = mysqli_fetch_array(mysqli_query($db_conx, $sql));
?>
<div id="left">
Name :<br><br>
Gender :<br><br>
SignUp :<br><br>
Car Pool Owned:<br><br>
</div>
<div id="right">
<?php 
echo $resu["fname"]." ".$resu["lname"]."<br><br>".$t."<br><br>".$resu["signup"];
?>
<br><br>
<?php
if($numrows < 1)
	echo "Does Not Own a Car Pool";
else
        echo "Carpool ".$resum["carpool_id"];
?>
</div>
</div>

<?php include_once("templates/template_options.php");  ?>

</body>

</html>