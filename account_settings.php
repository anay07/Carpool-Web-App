<?php
// ACCOUNT SETTINGS -->
//<!-- ********************************** -->

include_once("check_login_status.php");
// If user is already logged in, header them away
if($user_ok !=true){
	header("location: signup.php");
    exit();
}
if(isset($_POST["pass1"])) //checks length of password
{
	$p1=$_POST["pass1"];
	if (strlen($p1) < 6 || strlen($p1) > 16) {
	    echo '<strong style="color:#F00;">6 - 16 characters please</strong>';
	    exit();
	}else { echo ''; exit();}
}
if(isset($_POST["p1"])) // checks if retyped password matches
{
	$p1=$_POST["p1"];
	$p2=$_POST["p2"];
	    if($p1==$p2) {
	    echo '<strong style="color:#009900;">OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">Passwords dont match.</strong>';
	    exit();
    }
}
?><?php
if(isset($_POST["op"])){
	// CONNECT TO THE DATABASE
	require_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$p1=$_POST['pass'];
	$p2=$_POST['pass2'];
	$p = md5($_POST['op']);
	$np = md5($_POST['pass']);
	// FORM DATA ERROR HANDLING
	if($p == ""|| $p2 == "" || $p1 == ""){
		echo "The form submission is missing values.";
        exit();
	}
 else if (strlen($p1) < 6 || strlen($p1) > 16) {
        echo "Password must be between 6 and 16 characters";
        exit(); 
    }else if ($p1!=$p2) {
        echo "Passwords do not match";
        exit(); }
	// FORM DATA ERROR HANDLING

	$sql = "SELECT password FROM users WHERE id='$log_id' LIMIT 1"; //AND activated='1' add later
        $query = mysqli_query($db_conx, $sql);
	$q=mysqli_num_rows($query);
	if($q<1)
	{ echo 'change_failed'; exit();}
        $row = mysqli_fetch_row($query);
		$db_pass = $row[0];
		if($p != $db_pass){
				echo 'Old password does not match';
            exit();
		} else {
			$sql = "UPDATE users SET password='$np' WHERE id='$log_id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
	echo "change_success";
		    exit();
		}
	exit();
}

?>

<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<title>Home</title>
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
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function checkpass()
{
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	if(p2 != ""){
		_("passstatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "account_settings.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("passstatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("p1="+p1+"&p2="+p2);
	}
}
function passlen()
{
	var p1 = _("pass1").value;
	if(p1 != ""){
		_("passlen").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "account_settings.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("passlen").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("pass1="+p1);
	}
}
function dialogue(){
		var conf = confirm("Please log-in again to continue ");
		if(conf != true){
		return false;
		}
		window.location = "signup.php";
}
function change(){
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var op = _("oldpass").value;
	if(p1 == "" || p2 == "" || op == ""){
		_("status").innerHTML = "Fill out all of the form data!";
	} else {
		_("changebtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "account_settings.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
			            if(ajax.responseText == "change_success"){
					_("status").innerHTML = "Password changed";
					dialogue();
				} else {
					_("status").innerHTML = ajax.responseText;
					_("changebtn").style.display = "block";
				}
	        }
        }
        ajax.send("op="+op+"&pass="+p1+"&pass2="+p2);
	}
}
function emptyElement(x)
{
	_(x).innerHTML = "";
}
</script>
</head>
<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php");  ?>

<div id="sectionhead">
<h2>Settings</h2>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">
<form name="account" id="account" onsubmit="return false" input type="text" value="account" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<br>
<div id="left">
<label for="opassword">Old Password:</label><br><br>	
<label for="password">Password:</label><br><br>	
<label for="rpassword">Retype Password:</label><br><br>	
</div>

<div id="right">
<input id="oldpass" type="password" onblur="passlen()"onfocus="emptyElement('status')" maxlength="16" name="oldpass" ><br>
<br>
<input id="pass1" type="password" onblur="passlen()"onfocus="emptyElement('status')" maxlength="16" name="pass1" ><span id="passlen"></span><br>
<br>
<input id="pass2" type="password" onblur="checkpass()" onfocus="emptyElement('status')" maxlength="16" name="pass2" ><span id="passstatus"></span> <br><br>
</div>
<button id="changebtn" onclick="change()">Change Password</button> <span id="status"></span>
</form>
</div>

<?php include_once("templates/template_options.php");  ?>

</body>

</html>