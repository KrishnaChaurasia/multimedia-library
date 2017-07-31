<?php
include 'header.php';

	$underLectureName=$_POST['underLectureName'];
	$lectureName=$_POST['lectureName'];
	$offeredBy=$_POST['offeredBy'];
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

			if($underLectureName==NULL){
				$select="INSERT INTO $table (LectureName,OfferedBy1,Link1,Rating1) values('$lectureName','$offeredBy','$link',0)";

				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
			}
			else{				
				$select="SELECT * from $table WHERE LectureName='$underLectureName'";
				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
				$row=mysql_fetch_array($retval, MYSQL_ASSOC);

				if($row['Link2']==NULL){
					//echo $table;
					//echo $offeredBy;
					//echo $link;
					//echo $underLectureName;
					$select="UPDATE $table SET OfferedBy2='$offeredBy',Link2='$link',Rating2=0 WHERE LectureName='$underLectureName'";
					
					$retval=mysql_query($select, $conn);
					if(! $retval){
						die('Could not get data : '.mysql_error());
					}
				}
				else if($row['Link3']==NULL){
					$select="UPDATE $table SET OfferedBy3='$offeredBy',Link3='$link',Rating3=0 WHERE LectureName='$underLectureName'";

					$retval=mysql_query($select, $conn);
					if(! $retval){
						$fail=2;
						die('Could not get data : '.mysql_error());
					}
				}
				else{
					$found=1;
						echo '<b>Error updating, contact administrator</b>';
				}
			}
			if($found==0){
				move_uploaded_file($_FILES["file"]["tmp_name"],$location.$fileName);
				//echo "Stored in: " . "C:/xampp/htdocs/ML/" . $_FILES["file"]["name"];
				echo "<br><center><b>File Uploaded Successfully</center></b>";
			}
		}
    }
?>