<?php
include 'header.php';
?>

<?php
$found=0;
if(isset($_POST['submit'])){
	if(!empty($_POST['user']) && !empty($_POST['password'])){
		$inputCode=strtolower($_POST['user']);
		$inputPassword=$_POST['password'];

		$dbhost='localhost';
		$user='root';
		$password='root';
		$conn=mysql_connect($dbhost, $user, $password);
		if(! $conn){
			die('Could not connect to database : '.mysql_error());
		}

		$db='medialab';
		mysql_select_db($db);

		$select='SELECT * from users';

		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}

		$faculty=""; $branch="";
		while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
			if($inputCode==strtolower($row['UserCode']) &&	$inputPassword==$row['Password']){
				$faculty=$row['UserName'];
				$branch=$row['Branch'];
			}	
		$i++;
		}
		if($faculty==""){
			echo '<center><b>Wrong user name or password</b></center>';
			$found=0;
		}
		else{
			$dbhost='localhost';
			$user='root';
			$password='root';
			$conn=mysql_connect($dbhost, $user, $password);
			if(! $conn){
				die('Could not connect to database : '.mysql_error());
			}
			mysql_select_db($branch);
			$select='SELECT * from courses';
			$retval=mysql_query($select, $conn);
			if(! $retval){
				die('Could not get data : '.mysql_error());
			}
			$size=1000;
			$courseName[$size]; $courseFolderName[$size]; 
			$i=0;
			while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
				$courseName[$i]=$row['CourseName'];
				$courseFolderName[$i]=$row['CourseFolderName'];
				$i++;
			}
						
			// Content after logged in
			$found=1;

			echo'
			<a href='."/ML/ChangePassword.php?userCode=".urlencode($inputCode).'><p align="right">Change Password</p></a>';
			
			echo "Welcome, ".$faculty.'<br><br>';
			
			// VIDEO UPLOAD
			echo '<b><br>Video Upload <hr></b>';
			echo '
			<form action="/ML/uploadFile.php" method="post" enctype="multipart/form-data">'.
			'<input type="hidden" name="branch" value='.$branch.'>Under* : <select name="folderName">';
			
			for($i=0; $i<count($courseName); $i++){
				echo '<option value='.$courseFolderName[$i].'/videos'.'>'
				.$courseName[$i].'</option>';
			}
			
			echo '
			</select>
			Offered By* : <input type="text" name="offeredBy"> Under Lecture: <input type="text" name="underLectureName"> Lecture Name: <input type="text" name="lectureName"><br><br>'
			.'<label for="file"> </label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
			</form>';
			
			//Notes UPLOAD
			echo '<br><br><b>Lecture Notes Upload <hr></b>';
			echo '
			<form action="/ML/uploadnotesorassignments.php" method="post" enctype="multipart/form-data">'.
			'<input type="hidden" name="branch" value='.$branch.'>
			<input type="hidden" name="notesOrAssignment" value="notes">Under: <select name="folderName">';
			
			for($i=0; $i<count($courseName); $i++){
				echo '<option value='.$courseFolderName[$i].'/notes'.'>'
				.$courseName[$i].'</option>';
			}
			
			echo '
			</select><br><br>
			<label for="file"> </label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
			</form>';
			
			
			//Assignment UPLOAD
			echo '<br><br><b>Assignments Upload <hr></b>';
			echo '
			<form action="/ML/uploadnotesorassignments.php" method="post" enctype="multipart/form-data">'.
			'<input type="hidden" name="branch" value='.$branch.'>
			<input type="hidden" name="notesOrAssignment" value="assignment">Under: <select name="folderName">';
			
			for($i=0; $i<count($courseName); $i++){
				echo '<option value='.$courseFolderName[$i].'/assignments'.'>'.$courseName[$i].'</option>';
			}
			
			echo '
			</select><br><br>
			<label for="file"> </label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
			</form>';
		}
	}
	else echo '<center><b>Fill in all the fields</b></center>';
}
if($found==1)
	die();
?>


<html>
	<head> <title> Log in </title> </head>
</html>

<form action="/ML/FacultyLogIn.php" method="POST">
	<center><br>
	<b>User Name:</b> <input type="text" name="user" autofocus><br>
	<b>&nbsp;Password: </b>&nbsp;&nbsp;&nbsp;<input type="password" name="password">
	<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="submit" value="SUBMIT">
	</center>
</form>
