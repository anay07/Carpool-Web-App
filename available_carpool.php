<?php 

//<!-- ********************************** -->
//<!--PAGE TO DISPLAY AVAILABLE CARPOOLS -->
//<!-- ********************************** -->



include_once("check_login_status.php");
if($user_ok == false)
	header("location:signup.php");
$userid = $_SESSION["userid"];
?>
<?php
global $query;
global $flag;
$GLOBALS['flag']=0;
if($_SERVER["REQUEST_METHOD"] == "POST")
{
        $source = $destination =$ondate1= $ontime1=$ondate2= $ontime2= ""; 
        if (empty($_POST["source"])||empty($_POST["destination"])||empty($_POST["ondate1"])||empty($_POST["ontime1"]))
        {
        	echo "Fill full data";
        }
        else
        { $source = $_POST["source"];
          $destination = $_POST["destination"];
          $ondate1= $_POST["ondate1"];
          $ontime1 = $_POST["ontime1"];
          $ondate2= $_POST["ondate2"];
          $ontime2 = $_POST["ontime2"]; 
         $query1 = "SELECT * from carpool where source like '%$source%' and destination like '%$destination%' and on_date BETWEEN '$ondate1' AND '$ondate2' and on_time BETWEEN '$ontime1' AND '$ontime2' and carpool_id in (select carpool_id from carpool_status where available_seats>0)";
         //global $query;
         $GLOBALS['query'] = mysqli_query($db_conx, $query1);
         $GLOBALS['flag']=1;
         if($query == FALSE)
		{     echo("Error description: " . mysqli_error($db_conx)); 
        }
    }
 }
 ?>
<html>
<head>

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
z-index: 1;
}
button, input, select, textarea 
{
font-size: 20px;
font-family : "handwriting";
background-color:#DBFFED;
z-index: 1;
}
#section
{
width :57%;
height :500px;
border-radius: 5px;
font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Geneva, Verdana, sans-serif;
float:left;
margin:0.5%;
text-align:center;
}

table {
border-collapse: collapse;
width:85%;
background: -webkit-linear-gradient(#6600CC, #99CCFF ); /* For Safari 5.1 to 6.0 */
background: -o-linear-gradient(#6600CC, #99CCFF); /* For Opera 11.1 to 12.0 */
background: -moz-linear-gradient(#6600CC, #99CCFF); /* For Firefox 3.6 to 15 */
background: linear-gradient(#6600CC, #99CCFF); /* Standard syntax */
margin-top:9%;
}
th{

border: 2px solid black;
background-color:#52005C;
color:white;
}
tr{
border:1px solid black;
}
td{
border:1px solid black;
}	

</style>
<link rel="stylesheet" href="style/style.css">
<title  >Available Carpools</title>
</head>

<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0; text-align:center; ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 style="color:#660066; text-shadow: 5px 5px #c0c0c0;">Available Carpools</h2>
<br><br><br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>
<?php include_once("templates/navd.php");  ?>
<form name="search" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="left">
<br>
<p style="margin-bottom:20px; margin-top:0px">SOURCE:</p>

<p style="margin-bottom:30px; margin-top:0px">DESTINATION:</p>

<p style="margin-bottom:32px; margin-top:0px">FROM DATE:</p>

<p style="margin-bottom:25px; margin-top:0px">FROM TIME:</p>

<p style="margin-bottom:30px; margin-top:0px">TILL DATE: </p>

TILL TIME: <br><br>


</div>
<!-- <div id="right"> -->
  <div id="right">
<br>
<input type="text" name="source" val="src"><br/><br/>
<input type="text" name="destination" val="src"><br/><br/>
<input type="date" name="ondate1" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" ><br/><br/>
<input type="time" name="ontime1" ><br><br>
<input type="date" name="ondate2" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" ><br/><br/>
<input type="time" name="ontime2" ><br><br>
<br><br>
<input type="submit" name="submit" value="SUBMIT">

</div>

</form>
<br><br><br>
<form name="join_carpool" method="POST" action="viewpool.php">


<?php
if($GLOBALS['flag']==1)
{
echo '<table id="pool" align="center">';
echo '<tr>';
echo '<th>Checkbox</th>';
echo '<th>Source</th>';
echo '<th>Destination</th>';
echo '<th>Capacity</th>';
echo '<th>Fuel</th>';
echo '<th>Car License No</th>';
echo '</tr>';

/*$query = mysqli_query($db_conx,"SELECT * FROM carpool where carpool_id in (select carpool_id from carpool_status where available_seats>0)");*/
while($result=mysqli_fetch_array($GLOBALS['query']))
{
	echo '<tr>';
	echo '<td><center><input type="radio" name="carpool" value="'.$result["carpool_id"].'"></center></td>';
	echo '<td style="text-align:center;">'.$result["source"].'</td>';
	echo '<td style="text-align:center;">'.$result["destination"].'</td>';
	echo '<td style="text-align:center;">'.$result["capacity"].'</td>';
	if($result["fuel"]=='p')
		$t="Petrol";
	else
		$t="Diesel";
	echo '<td style="text-align:center;">'.$t.'</td>';
	echo '<td style="text-align:center;">'.$result["car_license_no"].'</td>';
	echo '</tr>';
}
echo '</table>';
echo '<input type="submit" value="View Details" align="centre" >';
}
?>
<br>
<br>

</form>
<!-- <?php //include_once("templates/template_options.php"); ?> -->
</body>
</html>
