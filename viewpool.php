<?php

//<!-- ********************************** -->
//<!-- PAGE TO SHOW THE DETAILS OF A CARPOOL -->
//<!-- ********************************** -->



include_once("check_login_status.php");

$p = "";

// Make sure the _GET carpool id is set, and sanitize it
if(isset($_GET["carpool"])){
	$p = preg_replace('#[^a-z0-9]#i', '', $_GET['carpool']);
} else if(isset($_POST["carpool"])) {
		$p = preg_replace('#[^a-z0-9]#i', '', $_POST['carpool']);
	}else
   		{ header("location: available_carpool.php");
    		exit();	
		}
// Select the carpool entry from the carpool table
$sql = "SELECT * FROM carpool WHERE carpool_id='$p' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That carpool does not exist, press back";
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
$sql=mysqli_query($db_conx, "SELECT user_id FROM carpool WHERE carpool_id='$p' LIMIT 1");
$result=mysqli_fetch_row($sql);
$owner=$result[0];
if($owner== $log_id  && $user_ok == true){ 
	{ $isOwner = "yes";
	header("location:owner_carpool.php");
	}
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$carpool_id = $row["carpool_id"];
	$source = $row["source"];
	$userid = $row["user_id"];
	$capacity = $row["capacity"];
	/* = $row["available_seats"];*/
	$fuel = $row["fuel"];
	$car_license_no = $row["car_license_no"];
}
?><?php
$isMember = false;
if($owner != $log_id && $user_ok == true){
	$request_check = "SELECT request_id FROM requests NATURAL JOIN request_status WHERE user='$log_id' AND carpool_id='$p' AND status='1' LIMIT 1";
	$query=mysqli_query($db_conx, $request_check);
	$q=mysqli_num_rows($query);
	if($q > 0){
        $isMember = true;
    }
}
$alreadyMember=false; 				//IN SOME OTHER CARPOOL
if($owner != $log_id && $user_ok == true){
	$request_check = "SELECT request_id FROM requests NATURAL JOIN request_status WHERE user='$log_id' AND status='1' LIMIT 1";
	$query=mysqli_query($db_conx, $request_check);
	$q=mysqli_num_rows($query);
	if($q > 0){
        $alreadyMember = true;
    }
}
$pending=false;					//PENDING REQUEST
if($owner != $log_id && $user_ok == true){
	$request_check = "SELECT request_id FROM requests NATURAL JOIN request_status WHERE user='$log_id' AND status='0' LIMIT 1";
	$query=mysqli_query($db_conx, $request_check);
	$q=mysqli_num_rows($query);
	if($q > 0){
        $pending = true;
    }
}
?><?php 
$request_button = '<button disabled>Join pool</button>';
// LOGIC FOR JOIN BUTTON

if($isMember == true){
	$request_button = '<button onclick="requestToggle(\'quit\',\''.$p.'\',\'requestBtn\')">Quit Pool</button>';
	//echo 111;
} else if($alreadyMember == true){
	$request_button = '<button disabled>Join pool</button>';
	///echo 222;
	} else if($pending == true){
		$request_button = '<button disabled>Join pool</button>';
		//echo 333;
		}else if($user_ok == true && $owner != $log_id){ 
		$request_button = '<button onclick="requestToggle(\'join\',\''.$p.'\',\'requestBtn\')">Join Pool</button>';
///echo 444;
}

?>
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="style/style.css">	

<meta charset="UTF-8">
<title>Carpool - <?php echo $p; ?></title>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
function requestToggle(type,id,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action for carpool: <?php echo $p; ?>.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "requests.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "request_sent"){
				_(elem).innerHTML = 'OK Request Sent';
			} else if(ajax.responseText == "quit_ok"){
				_(elem).innerHTML = '<button onclick="requestToggle(\'join\',\'<?php echo $p; ?>\',\'requestBtn\')">Join Pool</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = ajax.responseText ;
			}
		}
	}
	ajax.send("type="+type+"&id="+id);
}

</script>

<style>
#section
{
background: -webkit-linear-gradient(#FF8F73, #FFFFCC ); /* For Safari 5.1 to 6.0 */
  background: -o-linear-gradient(#FF8F73, #FFFFCC); /* For Opera 11.1 to 12.0 */
  background: -moz-linear-gradient(#FF8F73, #FFFFCC); /* For Firefox 3.6 to 15 */
  background: linear-gradient(#FF8F73, #FFFFCC); /* Standard syntax */
opacity:.8;
width :57%;
height :700px;
border-radius: 5px;
font-family : "handwriting";
font-size: 26px;
float:left;
margin:0.5%;
text-align:center;
color: #660033;
font-weight: 900;
}
#left
{
padding-right: 6%;
float:left;
text-align:right;
color: #990000;
width:44%;
}
#right
{
color: #AF3737;
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
<h2 style="color:#660066; text-shadow: 5px 5px #c0c0c0;"> <?php 
$resu = mysqli_fetch_array(mysqli_query($db_conx, "SELECT * FROM users WHERE id=$userid"));
echo $resu["fname"]." ".$resu["lname"];
?>
's Carpool</h2>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">
<div id="left">
<br>
Owner name: <br><br>
Source: <br><br>
Capacity:<br><br>
Fuel: <br><br>
Car name: <br><br>
<!-- Available Seats: <br><br> -->

</div>
<div id="right">
<br>
<?php $res = mysqli_fetch_array(mysqli_query($db_conx, "SELECT fname, lname, id FROM users WHERE id=$userid"));
			echo $res["fname"]." ".$res["lname"];?> <br><br>
<?php echo $source; ?><br><br>
<?php echo $capacity; ?><br><br>
<?php if($fuel=='p')
		$t="Petrol";
	else
		$t="Diesel"; ?>
<?php echo $t; ?><br><br>
<?php echo $car_license_no; ?><br><br>
<!-- <?php //echo $available; ?><br><br> -->

</div>
Members of the carpool :- <br><br>
<?php 
$tmp = $res["id"];
//echo $tmp;
	$query = mysqli_query($db_conx, "SELECT * FROM carpool_members where carpool_id in (select carpool_id from carpool where user_id = $tmp)");
	$num = mysqli_num_rows($query);
	if($num == FALSE)
		echo 'No one has joined this carpool';
	else
	{
		 while($result = mysqli_fetch_array($query))
		{
			$tmp = $result["user_id"];
			$res = mysqli_fetch_array(mysqli_query($db_conx, "SELECT * FROM users WHERE id=$tmp"));
			echo '<a href="profile.php?userid='.$tmp.'">'. $res["fname"]." ".$res["lname"].'</a><br> ';
		}
	}
?>
<br>Send Request:    <span id="requestBtn"><?php echo $request_button; ?></span>

</div>

<?php include_once("templates/template_options.php");  ?>

</body>
</html>