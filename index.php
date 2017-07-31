<?php
include "header.php";

function space($a){
	for($i=0; $i<$a; $i++){
		echo '&nbsp';
	}
}
$dbhost='localhost';
$user='root';
$password='root';
$conn=mysql_connect($dbhost, $user, $password);
if(! $conn){
	die('Could not connect to database : '.mysql_error());
}
$db='medialab';
mysql_select_db($db);
$select="SELECT * from branches";
$retval=mysql_query($select, $conn);
if(! $retval){
	die('Could not get data : '.mysql_error());
}
$size=1000; $i=0;
$courseCode[$size]; $courseName[$size];  
while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
		$courseCode[$i]=$row['BranchCode'];
		$courseName[$i]=$row['BranchName'];
		$i++;
}
?>
<html>
	<head> <title> Media Lab </title> </head>
	<body>	
		<a href="/ML/AdminLogIn.php">
		<p align="right">Admin Login</p></a>

		<a href="/ML/FacultyLogIn.php">
		<p align="right">Faculty Login</p></a>
		<b> Welcome to IIITD&M Media Lab. It is an archieve of Video Lectures, Lecture Notes and Assignments. </b>		
	</body>
</html>

<br>
<form action="/ML/branch.php" method="POST">
<center>
	<select name="branch">
		<option value="default"> Select a branch </option>
			<?php
				for($i=0; $i<count($courseCode); $i++){
					$name=$courseName[$i];
					echo '
						<option value="'.$courseCode[$i].'">'.$name.'</option>';
				}
			?>
	</select>
	<input type="submit" name="submit" value="Submit">
</center>
</form>


<form action="/ML/index.php" method="POST">
<br><br>
	
	<u><b>Feedback Form</b></u><br><br>
	Email *: <br><input type="text" name="email"><br><br>
	Feedback *: <br>
	<textarea maxlength="500" rows="12" cols="48" name="feedback"></textarea>
	<br><br>
	<input type="submit" name="submit" value="Submit Feedback">
</form>


<?php
	if(isset($_POST['submit']) && !empty($_POST['feedback']) && !empty($_POST['email'])){
		$email=$_POST['email'];
		$content=$_POST['feedback'];
		
		$dbhost='localhost';
		$user='root';
		$password='root';
		$conn=mysql_connect($dbhost, $user, $password);
		if(! $conn){
			die('Could not connect to database : '.mysql_error());
		}
		$db='medialab';
		mysql_select_db($db);

		$select="INSERT INTO feedback (Email,Content) values('$email','$content')";
			
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		$to = "coe11b016@iiitdm.ac.in";
		$subject = "Multimedia Library Feedback";
		$headers = "From: $email";

		if(mail($to, $subject, $content, $headers)){
		echo "<b>Thank you for sending us feedback</b>";
		}
		else{
		echo "<b>Error sending feedback</b>";
		}
	}
	else if(isset($_POST['submit']) && (empty($_POST['email']) || !empty($_POST['feedback']))){
		echo '<b>Fill all the fields</b>';
	}
?>
<html>
	<br> Mohit Singhaniya 
	<br> Krishna Chaurasia
	<br><br><b>Disclaimer:</b>
	<p align="right"><u>Page Count </u>: <img src="/ML/counter/counter.php" alt="page counter" style="width:80px;">
	<hr>
</html>