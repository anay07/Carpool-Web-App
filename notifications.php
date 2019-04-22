<?php
include_once("check_login_status.php");
if($user_ok != true || $log_id == ""){
	header("location: signup.php");
    exit();
}
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE receiver_id LIKE BINARY '$log_id' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$notification_list = "You do not have any notifications";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["not_id"];
		$user = $row["user"];
		$pool = $row["pool_id"];
		$type = $row["type"];
		if($type=='0')
			$note="Accepted your membership request";
		else if($type=='1')
			$note="Rejected your membership request";
		else if($type=='2')
			$note="Quit your carpool";
		else if($type=='3')
			$note="Deleted their carpool";
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$res=mysqli_fetch_array(mysqli_query($db_conx,"SELECT * FROM users where id=$user"));
		$fname=$res["fname"];
		$lname=$res["lname"];
		$notification_list .= "<p>$date_time || <a href='profile.php?userid=$user'>$fname $lname</a> <br/> Car pool id : $pool<br />$note</p>";
	}
}
//mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE id='$log_id' LIMIT 1");
?><?php
$member_requests = "";
$sql=mysqli_num_rows(mysqli_query($db_conx,"SELECT * FROM carpool WHERE user_id='$log_id'"));
if($sql>0){
$res=mysqli_fetch_array(mysqli_query($db_conx,"SELECT * FROM carpool WHERE user_id='$log_id'"));
$id=$res["carpool_id"];
$query = mysqli_query($db_conx,"SELECT * FROM requests NATURAL JOIN request_status WHERE carpool_id='$id' AND status='0' ORDER BY datemade ASC");
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$member_requests = 'No requests';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["request_id"];
		$user = $row["user"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$res=mysqli_fetch_array(mysqli_query($db_conx,"SELECT * FROM users where id=$user"));
		$fname=$res["fname"];
		$lname=$res["lname"];
			/*$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE id='$user' LIMIT 1");		//use to display profile picture along with request
			$thumbrow = mysqli_fetch_row($thumbquery);
			$user1avatar = $thumbrow[0];
			$user1pic = '<img src="user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
			if($user1avatar == NULL){
			$user1pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
			}*/
	
		//$member_requests = '<div id="memberreq_'.$reqID.'" class="memberrequests">';
		//$member_requests .= '<a href="profile.php?userid='.$user.'"></a>';
		$member_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="profile.php?userid='.$user.'">'.$fname.' '.$lname.'</a> requests membership<br /><br />';
		$member_requests .= '<button onclick="memberReqHandler(\'accept\',\''.$reqID.'\',\''.$id.'\',\''.$user.'\',\'user_info_'.$reqID.'\')">Accept</button> or ';
		$member_requests .= '<button onclick="memberReqHandler(\'reject\',\''.$reqID.'\',\''.$id.'\',\''.$user.'\',\'user_info_'.$reqID.'\')">Reject</button><br/><br/>';
		//$member_requests .= '</div>';
		$member_requests .= '</div>';
		}
}
}
else { $member_requests = 'No requests';}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<meta charset="UTF-8">
<title>Notifications and Friend Requests</title>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
function memberReqHandler(action,reqid,carpool_id,user,elem){
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "requests.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />You now have a new member in your carpool<br/><hr/>";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject the membership of this user<br/><hr/>";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user="+user+"&carpool_id="+carpool_id);
}
</script>

</head>
<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 text-align:center; style="color:#660066; text-shadow: 5px 5px #c0c0c0;">Notifications and Requests</h2>
<br><br><br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">  
<!-- START Page Content -->
  <div id="notesBox"><h2 >Notifications</h2><?php echo $notification_list; ?></div>
  <div id="friendReqBox"><h2>Requests</h2><?php echo $member_requests; ?></div>
  <div style="clear:left;"></div>
</div>

<?php include_once("templates/template_options.php");  ?>

</body>
</html>
