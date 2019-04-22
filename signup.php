<?php
// SIGNUP AND LOGIN PAGE -->
//<!-- ********************************** -->

include_once("check_login_status.php");
// If user is already logged in, header them away
if($user_ok == true){
	header("location: homepage.php");
    exit();
}

// Ajax calls this EMAIL CHECK code to execute
if(isset($_POST["emailcheck"]))
{
	require_once("php_includes/db_conx.php");
	$email =mysqli_real_escape_string($db_conx, $_POST['emailcheck']);
	$sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
    $email_check = mysqli_num_rows($query);
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
	    echo '<strong style="color:#F00;">Invalid email format</strong>';
	    exit();
    }
	if (is_numeric($email[0])) {
	    echo '<strong style="color:#F00;">Email must begin with a letter</strong>';
	    exit();
    }
    if ($email_check < 1) {
	    echo '<strong style="color:#009900;">OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $email . ' already has an account associated with it.</strong>';
	    exit();
    }
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
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["fu"]))
{
	// CONNECT TO THE DATABASE
	require_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$fu = preg_replace('#[^a-z]#i', '', $_POST['fu']);
	$lu = preg_replace('#[^a-z]#i', '', $_POST['lu']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	$p2=$_POST['p2'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	// DUPLICATE DATA CHECKS EMAIL
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($fu == "" || $e == "" || $p == ""|| $p2 == "" || $g == "" || $lu == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($e_check > 0){ 
        echo "An account already exists with this email address";
        exit();
	}else if (strlen($p) < 6 || strlen($p) > 16) {
        echo "Password must be between 3 and 16 characters";
        exit(); 
    }else if ($p!=$p2) {
        echo "Passwords do not match";
        exit(); 
    } else  if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$e)) {
	    echo "Invalid email format";
	    exit();
	}else if (is_numeric($e[0])) {
        echo 'Email cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$p_hash = md5($p);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (fname,lname, email, password, gender, signup, lastlogin) VALUES('$fu','$lu','$e','$p_hash','$g',now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		if($query!=true) echo("Error description: " . mysqli_error($db_conx));
		$uid = mysqli_insert_id($db_conx);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$e")) {
			mkdir("user/$e", 0755,true);
		}
     		echo 'Signup successful'."<br>";
     		echo 'Login to continue';
		exit();
	}
	exit();
}

// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["el"])){
	// CONNECT TO THE DATABASE
	require_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$e = mysqli_real_escape_string($db_conx, $_POST['el']);
	$p = md5($_POST['pl']);
	// FORM DATA ERROR HANDLING
	if($e == "" || $p == ""){
		 	echo 'login_failed';
        exit();
	} else {
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, email, password FROM users WHERE email='$e' LIMIT 1"; //AND activated='1' add later
        $query = mysqli_query($db_conx, $sql);
	$q=mysqli_num_rows($query);
	if($q<1)
	{ echo 'login_failed'; exit();}
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_email = $row[1];
        $db_pass_str = $row[2];
		if($p != $db_pass_str){
				echo 'login_failed';
            exit();
		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['email'] = $db_email;
			$_SESSION['password'] = $db_pass_str;
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET lastlogin=now() WHERE email='$db_email' LIMIT 1"; // ip='$ip',
            $query = mysqli_query($db_conx, $sql);
		    exit();
		}
	}
	exit();
}

?>


<!DOCTYPE HTML>
<html>
<head>
<style>

@-webkit-keyframes mymove {
    from {background-color: #F8F8F8 ;
          color: #101010 ;
            }
    to {background-color: #202020  ;
         color: #E8E8E8 ;
         }
}

@keyframes mymove {
    from {background-color: #F8F8F8 ;
          color: #101010 ;
          }
    to {background-color: #202020  ;
         color: #E8E8E8 ;
         }
}

#top
{
float :left;
background-color: #C0C0C0 ;
opacity: 0.6;
height: 80px;
text-align:center;
width: 100%;
font-family:"ALGERIAN";
font-size: 400%;
-webkit-animation: mymove 7s infinite; /* Chrome, Safari, Opera */
animation: mymove 7s infinite;
margin :5px;
}

#right
{
float :right;
  /*background: -webkit-linear-gradient(#000000, #C0C0C0 ); 
  background: -o-linear-gradient(#000000, #C0C0C0); 
  background: -moz-linear-gradient(#000000, #C0C0C0);*/ 
  background: #C5C5C2;
opacity: 1;
height: 150px;
width: 28%;
border-radius: 5px;
color: #000000;
font-weight: 900;
font-family: 'Rockwell', 'Courier Bold', Courier, Georgia, Times, 'Times New Roman', serif;
font-size: 18px;
text-align:center;
}

#right:hover
{
/*background: -webkit-linear-gradient(#000000, #300000  );
  background: -o-linear-gradient(#000000, #300000 ); 
  background: -moz-linear-gradient(#000000, #300000 );*/
  background: #A9A9A9 ; 
}

#left
{height: 250px;
width: 72%;
}

#rightmost 
{
float :right;
/*  background: -webkit-linear-gradient(#000000, #C0C0C0 );*/ /* For Safari 5.1 to 6.0 */
/*  background: -o-linear-gradient(#000000, #C0C0C0);*/ /* For Opera 11.1 to 12.0 
  /*background: -moz-linear-gradient(#000000, #C0C0C0); *//* For Firefox 3.6 to 15 */
  background: #C5C5C2; /* Standard syntax */
opacity: 0.5PX;
height :520px;
width :28%;
border-radius: 5px;
font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Geneva, Verdana, sans-serif;
font-size: 16px;
text-align:center;
color: #000000;
font-weight: 900;

}

#rightmost:hover
{
/*background: -webkit-linear-gradient(#000000, #300000  ); 
  background: -o-linear-gradient(#000000, #300000 ); 
  background: -moz-linear-gradient(#000000, #300000 );*/
  /*background: linear-gradient(#06beb6,#48b1bf);*/
   background: #A9A9A9; 
}

.hide
{
	display:none;
}
.hide_1
{
        display:none; 
}
</style>

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function restrict(elem)
{
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "fname"){
		rx = /[^a-z]/gi;
	}else if(elem == "lname"){
		rx = /[^a-z]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x)
{
	_(x).innerHTML = "";
}
function checkemail()
{
	var e = _("email").value;
	if(e != ""){
		_("emailstatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("emailstatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("emailcheck="+e);
	}
}
function checkpass()
{
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	if(p2 != ""){
		_("passstatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "signup.php");
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
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("passlen").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("pass1="+p1);
	}
}
function signup()
{
	var fu = _("fname").value;
	var lu = _("lname").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var g = _("gender").value;
	if(fu == "" || e == "" || p1 == "" || p2 == "" || lu == "" || g == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
		_("signupbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	           if(ajax.responseText != "signup_success")
				{
					_("status").innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				} else
                {   _("signupbtn").style.display = "none";
					window.scrollTo(0,0);
					_("signupform").innerHTML = "OK "+fu+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
				}
	        }
        }
        ajax.send("fu="+fu+"&lu="+lu+"&e="+e+"&p="+p1+"&p2="+p2+"&g="+g);
	}
}

function login(){
	var el = _("lemail").value;
	var pl = _("lpassword").value;
	if(el == "" || pl == ""){
		_("logstatus").innerHTML = "Fill out all of the form data!";
	} else {
		_("loginbtn").style.display = "none";
		_("logstatus").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
			            if(ajax.responseText == "login_failed"){
					_("logstatus").innerHTML = "Login unsuccessful,please try again";
					_("loginbtn").style.display = "inline";
				} else {
					window.location = "homepage.php";
				}
	        }
        }
        ajax.send("el="+el+"&pl="+pl);
	}
}

</script>
 

<title>CAR POOL V.1</title>
</head>

<body bgcolor="black">
<video autoplay loop id="bgvid"
style=" position: fixed; left: 22px; bottom: 30px;top:100px;
min-width: 70%; min-height: 80%;
width: auto; height: auto; z-index: -100;background: url(C:\Users\Admin\Desktop\Greentipcarpooling.mp4) no-repeat;
background-size: cover;" muted>
<source src="Greentipcarpooling.mp4" type="video/mp4">
</video> 

<div id="top"> 
   CAR POOL V.1
</div>
  
 <div id="right">
  
 <!-- LOGIN FORM -->
   <form id="loginform" name= "loginform"  onsubmit="return false" action="signup.php" method="post">
   <br><label for="lemail">Email:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
   <input type="text" id="lemail" onfocus="emptyElement('status')" maxlength="88" name="lemail" placeholder="Email" autocomplete="on"><br><br>
   <label for="password">Password:&nbsp&nbsp</label>
    <input type="password" id="lpassword" onfocus="emptyElement('status')" maxlength="100" name="lpassword" placeholder="Your Secret Syllabe" ><br><br>
   <button id="loginbtn" onclick="login()" style="font-family:'Rockwell'; -moz-border-radius: 5px;
  -webkit-border-radius: 5px;  box-shadow: 0px 0px 1px #777;background-color: #C0C0C0;  padding: .1em;" >Log &nbspIn</button><br> 
   <label id="logstatus"></label>
    <!-- <a href="#">Forgot Your Password?</a> -->
   </form>
</div>

<div id="left">
</div>

<div id="rightmost">
<h2 style="font-family: 'Rockwell';">NEW &nbsp TO &nbsp CAR &nbsp POOL?</h2>
<h3 style="font-family: 'Rockwell';">Register here</h3><br>

 <!-- SIGNUP FORM -->

<form name="signupform" id="signupform" onsubmit="return false" input type="text" value="signin" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<div><div style="float:left; padding-left: 40px;"><label for="fname" style="font-family:'Rockwell';">First Name:<br></label></div>
<div style="float:right; padding-right: 60px;"><input id="fname" type="text" name="fname" onkeyup="restrict('fname')" maxlength="16" required></div>
</div>
<div style="line-height:50%;">
	<label id = "ferror"></label>
    <br><br>
</div><br>
<div>
<div style="float:left; padding-left: 40px;" ><label style="font-family:'Rockwell';" for="lname">Last Name:</label></div>
<div style="float:right; padding-right: 60px;"><input id="lname" type="text" name="lname" onkeyup="restrict('lname')" maxlength="16" required></div>
</div>
<div style="line-height:50%;">
    <label id = "lerror"></label>
    <br><br>
</div><br>
<div>
<div style="float:left; padding-left: 40px;"><label for="email" style="font-family:'Rockwell';" >Email:</label></div>
<div style="float:right; padding-right: 60px;"><input id="email" type="text" onblur="checkemail()" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88" name="email" required></div>
</div>
<br>
<span id="emailstatus" style='padding-left : 162px'></span><br><div style="line-height:50%;">
    
</div>
<div>
<div style="float:left; padding-left: 40px;"><label for="gender" style="font-family:'Rockwell';" >Gender:</label></div>
<div style="float:right; padding-right: 60px;"><select id="gender" onfocus="emptyElement('status')"><
      <option value=""></option>
      <option value="m">Male</option>
      <option value="f">Female</option>
    </select></div>
<br><div style="line-height:50%;">
    <br><br>
</div>
<div>
<div style="float:left; padding-left: 40px;"><label for="password" style="font-family:'Rockwell';" >Password:</label></div>
<div style="float:right; padding-right: 60px;"><input id="pass1" type="password" onblur="passlen()"onfocus="emptyElement('status')" maxlength="16" name="pass1" reqiured><br></div>
</div>
<span id="passlen" style='padding-left : 200px'></span><br><div style="line-height:50%;">
    
</div>
<div>
<div style="float:left; padding-left: 40px;"><label for="rpassword" style="font-family:'Rockwell';"  >Retype Password:</label></div>
<div style="float:right; padding-right: 60px;"><input id="pass2" type="password" onblur="checkpass()" onfocus="emptyElement('status')" maxlength="16" name="pass2" reqiured><br></div>
</div>
<span id="passstatus" style='padding-left : 170px'></span> <br><br>
<button id="signupbtn" onclick="signup()"  style="font-family:'Rockwell'; -moz-border-radius: 5px;
  -webkit-border-radius: 5px;  box-shadow: 0px 0px 1px #777;background-color: #C0C0C0; padding: .3em;">Create Account</button> <span id="status"></span>
</form>

<!-- SIGNUP FORM -->

</div>

</body>
</html>
