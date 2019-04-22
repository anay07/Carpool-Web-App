<?php 

//<!-- ********************************** -->
//<!-- PAGE TO MAKE A NEW CARPOOL -->
//<!-- ********************************** -->



include_once("check_login_status.php");
if($user_ok == false)
	header("location:signup.php");
$userid = $_SESSION["userid"];
?>
<?php

$query = mysqli_query($db_conx, "SELECT * FROM carpool WHERE user_id = '$userid'");
if(mysqli_num_rows($query)>0)
	{
		header("location:owner_carpool.php");
	}
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$capacity = $source = $destination = $license = $fuel  =$ondate= $license=$ontime= "";
		function test_input($data)
		{
		$data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
               return $data;
		}
		if (empty($_POST["source"]))
			$sourceErr = "Source area name is required";
                else
                {
                   $source = test_input($_POST["source"]);
                    // check if name only contains letters and whitespace
                    if (!preg_match("/^[a-zA-Z]*$/",$source))
                        $sourceErr = "Only letters allowed";
                    else
                    	$sourceErr="none";
                }

        if (empty($_POST["destination"]))
			$destinationErr = "Destination area name is required";
                else
                {
                    $destination = test_input($_POST["destination"]);
                    // check if name only contains letters and whitespace
                    if (!preg_match("/^[a-zA-Z]*$/",$destination))
                        $destinationErr = "Only letters allowed";
                    else
                    	$destinationErr="none";
                }
        if (empty($_POST["capacity"]))
			$capacityErr = "Capacity value is required";
                else
                {
                    $capacity =($_POST["capacity"]);
	$available=($_POST["capacity"])-1;
                    $capacityErr="none";
                }
		if (empty($_POST["fuel"]))
			$fuelErr = "Fuel value is required";
                else
                {
                    $fuel =($_POST["fuel"]);
                    $fuelErr="none";
                }
        if (empty($_POST["license"]))
			$licenseErr = "car license no is required";
                else
                {
                    $license = test_input($_POST["license"]);
                    // check if name only contains letters, numbers and whitespace
                    if (!preg_match("/^[a-zA-Z0-9]*$/",$license))
                        $licenseErr = "Only letters allowed";
                    else
                    	$licenseErr="none";
                }
		if (empty($_POST["ondate"]))
			$dateErr = "date is required";
		else
		{   $ondate =($_POST["ondate"]);
			$ondate=date('Y-m-d',strtotime($ondate));
			//echo $ondate;
		}
        if (empty($_POST["ontime"]))
			$dateErr = "time is required";
		else{
			$ontime =($_POST["ontime"]);
			$ontime=date('H:i:s',strtotime($ontime));
			//echo $ontime;
		}        
               	
		$query = "INSERT INTO carpool(user_id, source,destination,capacity, fuel, car_license_no,on_date,on_time) VALUES ('$userid', '$source','$destination','$capacity', '$fuel', '$license','$ondate','$ontime')";
		$query_result = mysqli_query($db_conx, $query);
		if($query_result == FALSE)
		{     echo("Error description: " . mysqli_error($db_conx));
		echo 'exit';
			exit();
		}
		/*$que_car = "CREATE TABLE carpool_$userid (userid INT(5) UNIQUE)";
-		$query_result = mysqli_query($db_conx, $que_car);
-		$query= " INSERT INTO carpool_$userid(userid) VALUES ('$userid')";
-		$result=mysqli_query($db_conx, $query);
-		if($query_result == FALSE)
-			exit();
-		if($result==FALSE)
-			exit();*/
header("location: owner_carpool.php");
	} 
?>

<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<style>

#left
{
color: #663300;
font-weight: 900;
font-size: 20px;
padding-left: 5%;
float:left;
margin-right:5%;
text-align:right;
width:19%;
}
#right
{
width:28%;
float:left;
text-align:left;
padding-left: 1%;
}
button, input, select, textarea 
{
font-size: 20px;
font-family : "handwriting";
background-color:#DBFFED;

}
</style>
<title>Make Your CarPool</title>
</head>

<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 style="color:#660066; text-shadow: 5px 5px #c0c0c0;">Create Your Own CarPool</h2>
<br><br><br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<form name="carpoolinfo" id ="carpoolinfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="left">
<br>
SOURCE: <br><br>

DESTINATION: <br><br>

CAPACITY: <br><br>

FUEL TYPE: <br><br>

DATE : <br><br>

TIME : <br><br>

CAR LICENSE NO: <br><br>
<br>
</div>
<div id="right">
<br>
<input type="text" name="source" val="src"><br/><br/>
<input type="text" name="destination" val="src"><br/><br/>
<select name="capacity">
		<option value="1">ONE</option>
		<option value="2">TWO</option>
		<option value="3">THREE</option>
		<option value="4">FOUR</option>
		<option value="5">FIVE</option>
	</select><br/><br/>
<select name="fuel">
		<option value="p">Petrol</option>
		<option value="d">Diesel</option>
	</select><br/><br/>
<input type="date" name="ondate" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" ><br/><br/>
<input type="time" name="ontime" ><br><br>
<input type="text" name="license" value=""><br><br>
<br><br>
<input type="submit" name="submit" value="SUBMIT">

</div>

</form>

<?php include_once("templates/template_options.php");  ?>

</body>
</html>