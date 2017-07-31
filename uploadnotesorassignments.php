<?php
include 'header.php';
	$value=$_POST['notesOrAssignment'];
	$folderName=$_POST['folderName'];
	$branch=$_POST['branch'];
	$originalFileName=$_FILES['file']['name'];
	$fileName=preg_replace('/\s+/','',$_FILES["file"]["name"]);
	$table=preg_replace('/\//','',$folderName);
		
	if ($_FILES["file"]["error"] > 0){
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	}
	else{
		//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		//echo "Type: " . $_FILES["file"]["type"] . "<br>";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		if (file_exists("C:/xampp/htdocs/ML/" . $fileName)){
			echo $fileName . " already exists. ";
		}
		else{
			$found=0;
			$location='C:/xampp/htdocs/ML/'.$branch.'/'.$folderName.'/';
			$link='/ML/'.$branch.'/'.$folderName.'/'.$fileName;
			$dbhost='localhost';
			$user='root';
			$password='root';
			$conn=mysql_connect($dbhost, $user, $password);
			if(! $conn){
				die('Could not connect to database : '.mysql_error());
			}
			mysql_select_db($branch);
		
			if($value=='assignment'){
				$select="INSERT INTO $table (AssignmentName,AssignmentLink) values('$originalFileName','$link')";
			}
			else{
				$select="INSERT INTO $table (LectureNotesName,LectureNotesLink) values('$originalFileName','$link')";
			}
			$retval=mysql_query($select, $conn);
			if(! $retval){
				die('Could not get data : '.mysql_error());
			}
		
			move_uploaded_file($_FILES["file"]["tmp_name"],$location.$fileName);
			//echo "Stored in: " . "C:/xampp/htdocs/ML/" . $_FILES["file"]["name"];
			echo "<br><center><b>File Uploaded Successfully</center></b>";
		}
    }
?>