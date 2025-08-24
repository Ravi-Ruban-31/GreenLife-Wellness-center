<?php $dbhost = 'localhost';
  $dbuser = 'root'; 
  $dbpass = '';
  $db='green life';
   $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);//It opens a connection to a MySQLi Server
    if(!$conn ) { 
   die('Could not connect: ' . mysqli_error($conn));
    } echo 'Connected successfully';
	
	echo '<br>';	
	 
	 	  //mysqli_close($conn);
	 ?> 
