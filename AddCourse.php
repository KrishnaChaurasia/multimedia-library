<?php
include "header.php";
$branch=$_POST['branch'];
	if($branch && isset($_POST['addCourse'])){
		$dbhost='localhost';
		$user='root';
		$password='root';
		$conn=mysql_connect($dbhost, $user, $password);
		if(! $conn){
			die('Could not connect to database : '.mysql_error());
		}
		mysql_select_db($branch);
		
		$courseName=$_POST['courseName'];
		$courseFolderName=strtolower(preg_replace('/\s+/','',$courseName));
		
		$select="INSERT INTO courses values('$courseName','$courseFolderName')";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		
		$courseVideoTable=$courseFolderName.'videos';
		$courseNotesTable=$courseFolderName.'notes';
		$courseAssignmentTable=$courseFolderName.'assignments';
		
		// Video Table
		$select="CREATE TABLE $courseVideoTable(
			LectureName varchar(100),
			OfferedBy1 varchar(100),
			OfferedBy2 varchar(100),
			OfferedBy3 varchar(100),
			Link1 varchar(100),
			Link2 varchar(100),
			Link3 varchar(100),
			Rating1 varchar(100) default 0,
			Rating2 varchar(100) default 0,
			Rating3 varchar(100) default 0,
			Counter1 int default 0,
			Counter2 int default 0,
			Counter3 int default 0,
			PRIMARY KEY(LectureName)
		)";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		//Notes Table
		$select="CREATE TABLE $courseNotesTable(
			LectureNotesName varchar(100),
			LectureNotesLink varchar(100),
			PRIMARY KEY(LectureNotesName)
		)";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		//Assignment Table
		$select="CREATE TABLE $courseAssignmentTable(
			AssignmentName varchar(100),
			AssignmentLink varchar(100),
			PRIMARY KEY(AssignmentName)
		)";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		
		$location='C:/xampp/htdocs/ML/'.$branch.'/'.$courseFolderName;
		mkdir($location);
		mkdir($location.'/videos');
		mkdir($location.'/notes');
		mkdir($location.'/assignments');
		
		echo '<center><b>Course Created Successfully</b></center>';
	die();
	}
	else if(!$branch) die();
?>
<form action="/ML/AddCourse.php" method="POST">
	<center>
	Course Name: <input type="text" name="courseName">
	<input type="hidden" name="branch" value="<?php echo $branch; ?>">
	<input type="submit" name="addCourse" value="Add">
	</center>
</form>