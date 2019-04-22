<!-- ********************************** -->
<!-- HOMEPAGE -->
<!-- ********************************** -->

<?php
include_once("check_login_status.php");
?>
<html>
<head>
<link rel="stylesheet" href="style/style.css">	
<title>Home</title>
<style>
#section
{
width :57%;
background-color:#E0E0E0;
border-radius: 5px;
font-family : "handwriting";
font-size: 18px;
float:left;
margin:0.5%;
text-align:left;
color: #663300;
font-weight: 900;
}
h3
{
color:#260066;
}
</style>
</head>

<body style="margin:0; padding:0; height:700px; background-color:#E0E0E0 ">
<?php include_once("templates/template_header.php"); ?>
<?php include_once("templates/nav.php"); ?>

<div id="sectionhead">
<h2 text-align:center; style="color:#660066; text-shadow: 5px 5px #c0c0c0;">Why Carpooling</h2>
<br><br><br><br>
</div>

<?php include_once("templates/optionhead.php"); ?>

<?php include_once("templates/navd.php");  ?>

<div id="section">
<ol style="list-style-type:circle">
<h3>The obvious</h3>

<li> Save on gas</li><br>

<li> Save the environment and reduce your carbon offset </li><br>

<h3>The not-so-obvious </h3>

<li>  Save your energy - Driving is a complex activity that requires decision making, hand eye co-ordination, good reflexes, split-second decision making and an alert mind. Even if you don't realize it, is an activity which can contribute to making you tired. Ride a few days instead of driving and feel the difference for yourself. </li><br>

<li>  Have better control of your work schedule - This is easy to miss, but in the daily grind your work can control some of your schedule. To lead a richer and more fulfilling life, experts agree you need to be in control of your schedule instead. Carpooling is a commitment which may help your resolve to be on top of your work schedule.</li><br>

<li>  Reduced traffic congestion if more people carpool - This is a long term benefit and would be visible if a large number of people start carpooling. The more people carpool, the lesser will be the heavy congestion on the roads. You can join the campaign today, start carpooling.</li><br>

<li>  Reduce dependency on oil - Oil is a precious and finite resource on this planet. We will run out of this resource at some point of time, the only question we do not have a definitive answer for is when. This is also a benefit that would be visible if a large number of people start carpooling.</li><br>

<li>  It's fun! It's easy! - Daily commutes if you drive alone can be boring. Same old highway traffic advisory and eye in the sky reports to watch traffic may become routine, habitual and even addictive. Take a break from these and step into the carpooling world which forces you to relax and be more patient by virtue of other people traveling with you, and yet HOV and Express lanes reduce your commute time.</li><br>
</ol>
</div>

<?php include_once("templates/template_options.php");  ?>

</body>

</html>
