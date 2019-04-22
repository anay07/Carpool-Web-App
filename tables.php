<?php

//<!--- ********************************** >
//<! DATABASE STRUCTURE >
//<! ********************************** >

include_once("php_includes/db_conx.php");

$tbl_users = "CREATE TABLE IF NOT EXISTS users (
      id INT(11) NOT NULL AUTO_INCREMENT,
     fname VARCHAR(16) NOT NULL,
     lname VARCHAR(16) NOT NULL,
     gender ENUM('m','f') NOT NULL,
      /*userlevel ENUM('a','b','c') NOT NULL DEFAULT 'a',*/
       email VARCHAR(255) NOT NULL,
       password VARCHAR(255) NOT NULL,
        signup DATETIME NOT NULL,
       lastlogin DATETIME NOT NULL,
       PRIMARY KEY (id)
             )";
$query = mysqli_query($db_conx, $tbl_users);
if ($query === TRUE) {
 echo "<h3>user table created OK :) </h3>"; 
} else {
 echo "<h3>user table NOT created :( </h3>"; 
}

////////////////////////////////////
$carpool="CREATE TABLE IF NOT EXISTS carpool(
                             user_id INT(11) NOT NULL,
                             carpool_id INT(11) NOT NULL AUTO_INCREMENT,
                             source VARCHAR(50) NOT NULL,  
                             destination VARCHAR(50) NOT NULL,                         
                             fuel ENUM('p','d') NOT NULL, 
                      car_license_no VARCHAR(20) NOT NULL,
                      on_date date NOT NULL,
                      on_time time NOT NULL,
                             /*available_seats INT(10) NOT NULL,*/
                             capacity INT(10) NOT NULL,
                             PRIMARY KEY(carpool_id),
                             FOREIGN KEY(user_id) REFERENCES users(id) 
                    )";
$query = mysqli_query($db_conx, $carpool);
if ($query === TRUE) {
 echo "<h3>carpool table created OK :) </h3>"; 
} else {
 echo "<h3>carpool table NOT created :( </h3>"; 
}
///////////////////////////////////// 0 if carpool is yet to happen and 1 if it is passed
$carpool_status = "CREATE TABLE IF NOT EXISTS carpool_status(
                                              carpool_id INT(11) NOT NULL AUTO_INCREMENT,
                                              available_seats INT(10) NOT NULL,
                                              status ENUM('0','1') NOT NULL DEFAULT '0',
                                              PRIMARY KEY(carpool_id),
                                              FOREIGN KEY(carpool_id) REFERENCES carpool(carpool_id) ON DELETE CASCADE
                                          )";
$query = mysqli_query($db_conx, $carpool_status);
if ($query === TRUE) {
 echo "<h3>carpool_status table created OK :) </h3>"; 
} else {
 echo "<h3>carpool_status table NOT created :( </h3>"; 
}


////////////////////////////////////
$requests = "CREATE TABLE IF NOT EXISTS requests( 
                request_id INT(11) NOT NULL AUTO_INCREMENT,
                carpool_id INT(16) NOT NULL,
                user VARCHAR(16) NOT NULL,
                datemade DATETIME NOT NULL, 
             /*accepted ENUM('0','1') DEFAULT '0', */ 
                PRIMARY KEY (request_id),
                FOREIGN KEY(carpool_id) REFERENCES carpool(carpool_id) ON DELETE CASCADE

                )"; 
$query = mysqli_query($db_conx, $requests); 
if ($query === TRUE) {
 echo "<h3>requests table created OK :) </h3>"; 
} else {
 echo "<h3>requests table NOT created :( </h3>"; 
}

/////////////////////////////////////////

$request_status = "CREATE TABLE IF NOT EXISTS request_status(
                           request_id INT(11) NOT NULL AUTO_INCREMENT,
                           status ENUM('0','1') NOT NULL DEFAULT '0',
                           PRIMARY KEY(request_id),
                           FOREIGN KEY(request_id) REFERENCES requests(request_id) ON DELETE CASCADE
                        )";
$query = mysqli_query($db_conx, $request_status);
if ($query === TRUE) {
 echo "<h3>request_status table created OK :) </h3>"; 
} else {
 echo "<h3>request_status table NOT created :( </h3>"; 
}

//////////////////////////////////////0 for accepted; 1 for rejected; 2 for quit; 3 for deleted

$tbl_notifications = "CREATE TABLE IF NOT EXISTS notifications ( 
                not_id INT(11) NOT NULL AUTO_INCREMENT,
                user INT(10) NOT NULL,
             receiver_id INT(10) NOT NULL,
                pool_id INT(10) NOT NULL,
                type ENUM('0','1','2','3'),    
                date_time DATETIME NOT NULL,
                PRIMARY KEY (not_id),
                FOREIGN KEY(user) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY(pool_id) REFERENCES carpool(carpool_id) ON DELETE CASCADE
                )"; 
$query = mysqli_query($db_conx, $tbl_notifications); 
if ($query === TRUE) {
 echo "<h3>notifications table created OK :) </h3>"; 
} else {
 echo "<h3>notifications table NOT created :( </h3>"; 
}
//////////////////////////////////////////
$trigg_1 = " DELIMITER $$
             CREATE TRIGGER cpstatus AFTER INSERT ON carpool  FOR EACH ROW
             BEGIN
             INSERT INTO carpool_status(carpool_id,available_seats,status) VALUES (NEW.carpool_id,NEW.capacity,'0');
             END$$
             DELIMITER ;";
$query = mysqli_query($db_conx, $trigg_1); 
if ($query === TRUE) {
 echo "<h3>trigger 1  created OK :) </h3>"; 
} else {
 echo "<h3>trigger 1 NOT created :( </h3>"; 
}


//////////////////////////////////////////
$trigg_2 = " DELIMITER $$
             CREATE TRIGGER reqstatus AFTER INSERT ON requests  FOR EACH ROW
             BEGIN
             INSERT INTO request_status(request_id,status) VALUES (NEW.request_id,'0');
             END$$
             DELIMITER ;";
$query = mysqli_query($db_conx, $trigg_2); 
if ($query === TRUE) {
 echo "<h3>trigger 2  created OK :) </h3>"; 
} else {
 echo "<h3>trigger 2 NOT created :( </h3>"; 
}
/////////////////////////////////////////
$carpool_members = "CREATE TABLE IF NOT EXISTS carpool_members(
                           carpool_id INT(11) NOT NULL ,
                           user_id INT(11) NOT NULL,
                           PRIMARY KEY(carpool_id,user_id),
                           FOREIGN KEY(carpool_id) REFERENCES carpool(carpool_id) ON DELETE CASCADE,
                           FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
                        )";
$query = mysqli_query($db_conx, $carpool_members);
if ($query === TRUE) {
 echo "<h3>carpool_members table created OK :) </h3>"; 
} else {
 echo "<h3>carpool_members table NOT created :( </h3>"; 
}
///////////////////////////////////////////
$trigg_3 = " DELIMITER $$
             CREATE TRIGGER carmem AFTER INSERT ON carpool FOR EACH ROW
             BEGIN
             INSERT INTO carpool_members(carpool_id,user_id) VALUES (NEW.carpool_id,NEW.user_id);
             END$$
             DELIMITER ;";
$query = mysqli_query($db_conx, $trigg_3); 
if ($query === TRUE) {
 echo "<h3>trigger 3  created OK :) </h3>"; 
} else {
 echo "<h3>trigger 3 NOT created :( </h3>"; 
}



$db_conx>close();
?>