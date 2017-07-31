<?php
include "header.php";
$found=0;
	$adminName=$_POST['adminName'];
	$branch=$_POST['branch'];
	if($adminName){
		$found=1;
		
		$dbhost='localhost';
		$user='root';
		$password='root';
		$conn=mysql_connect($dbhost, $user, $password);
		if(! $conn){
			die('Could not connect to database : '.mysql_error());
		}
		mysql_select_db($branch);
		$select="Select * from courses";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		
		$size=1000; $courseName[$size]; $courseFolderName[$size]; $i=0;
		while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
			$courseName[$i]=$row['CourseName'];
			$courseFolderName[$i]=$row['CourseFolderName'];
			$i++;
		}
		
		if(isset($_POST['delete']) && isset($_POST['toDelete'])){
			$lectureName=$_POST['lectureName'];
			$course=$_POST['course'];
			$offeredBy=$_POST['offeredBy'];
			$choice=$_POST['toDelete'];
			$table=$course.$choice;
		
			mysql_select_db($branch);
			$select="Select * from $table";
			$retval=mysql_query($select, $conn);
			if(! $retval){
				die('Could not get data : '.mysql_error());
			}
			
			if($choice=='videos'){
				$select="SELECT * from $table WHERE LectureName='$lectureName'";
				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
				$row=mysql_fetch_array($retval, MYSQL_ASSOC);
					
				$location="";	
				if($row['OfferedBy1']==$offeredBy){
					$location='C:/xampp/htdocs'.$row['Link1'];
				}	
				else if($row['OfferedBy2']==$offeredBy){
					$location='C:/xampp/htdocs'.$row['Link2'];
				}	
				else if($row['OfferedBy3']==$offeredBy){
					$location='C:/xampp/htdocs'.$row['Link3'];
				}
			}
			if($choice=='notes'){
				$select="SELECT * from $table WHERE LectureNotesName='$lectureName'";
				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
				$row=mysql_fetch_array($retval, MYSQL_ASSOC);
				$location='C:/xampp/htdocs'.$row['LectureNotesLink'];	
			}
			else if($choice=='assignments'){
				$select="SELECT * from $table WHERE AssignmentName='$lectureName'";
				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
				$row=mysql_fetch_array($retval, MYSQL_ASSOC);
				$location='C:/xampp/htdocs'.$row['AssignmentLink'];
			}
		
			if(unlink($location)){
				if($choice=='videos'){
					$select="Select * from $table WHERE LectureName='$lectureName'";
					$retval=mysql_query($select, $conn);
					if(! $retval){
						die('Could not get data : '.mysql_error());
					}
					
					$row=mysql_fetch_array($retval, MYSQL_ASSOC);
					if($row['Link2']==NULL){
						$select="DELETE FROM $table WHERE LectureName='$lectureName'";
					}
					else{
						$offeredBy1=$row['OfferedBy1']; $offeredBy2=$row['OfferedBy2']; $offeredBy3=$row['OfferedBy3'];
						$link1=$row['Link1']; $link2=$row['Link2']; $link3=$row['Link3'];
						$rating1=$row['Rating1']; $rating2=$row['Rating2']; $rating3=$row['Rating3'];
						$counter1=$row['Counter1'];	$counter2=$row['Counter1'];	$counter3=$row['Counter1'];
						$null=NULL;
						
						if($offeredBy==$offeredBy3){	
							$select="UPDATE $table SET OfferedBy3='$null', Link3='$null', Rating3=0, Counter3=0 WHERE LectureName='$lectureName'";
						}
						else if($offeredBy==$offeredBy2){
							if($link3==NULL){
									$select="UPDATE $table SET OfferedBy2='$null', Link2='$null', Rating2=0, Counter2=0 WHERE LectureName='$lectureName'";
								}
							else{
								$select="UPDATE $table SET OfferedBy2='$offeredBy3', Link2='$link3', Rating2='$rating3', Counter2='$counter3' WHERE LectureName='$lectureName'";
								$retval=mysql_query($select, $conn);
								if(! $retval){
									die('Could not get data : '.mysql_error());
								}
								$select="UPDATE $table SET OfferedBy3='$null', Link3='$null', Rating3=0, Counter3=0 WHERE LectureName='$lectureName'";
							}
						}
						else if($offeredBy==$offeredBy1){
							if($link3==NULL){
								$select="UPDATE $table SET OfferedBy1='$offeredBy2', Link1='$link2', Rating1='$rating2', Counter1='$counter2' WHERE LectureName='$lectureName'";
								$retval=mysql_query($select, $conn);
								if(! $retval){
									die('Could not get data : '.mysql_error());
								}
								$select="UPDATE $table SET OfferedBy2='$null', Link2='$null', Rating2=0, Counter2=0 WHERE LectureName='$lectureName'";
							}
							else{
								$select="UPDATE $table SET OfferedBy1='$offeredBy2', Link1='$link2', Rating1='$rating2', Counter1='$counter2' WHERE LectureName='$lectureName'";
								$retval=mysql_query($select, $conn);
								if(! $retval){
									die('Could not get data : '.mysql_error());
								}
								$select="UPDATE $table SET OfferedBy2='$offeredBy3', Link2='$link3', Rating2='$rating3', Counter2='$counter3' WHERE LectureName='$lectureName'";
								$retval=mysql_query($select, $conn);
								if(! $retval){
									die('Could not get data : '.mysql_error());
								}
								$select="UPDATE $table SET OfferedBy3='$null', Link3='$null', Rating3=0, Counter3=0 WHERE LectureName='$lectureName'";
							}
						}
					}
				}
				else if($choice=='notes'){
					$select="DELETE FROM $table WHERE LectureNotesName='$lectureName'";
				}
				else{
					$select="DELETE FROM $table WHERE AssignmentName='$lectureName'";
				}
				$retval=mysql_query($select, $conn);
				if(! $retval){
					die('Could not get data : '.mysql_error());
				}
				echo '<center><b>File deleted successfully</b></center>';
			}
		}
	}
	//else redirect to home page
if($found==0)
	die();
?>
<form action="/ML/Delete.php" method="POST">
	<center><br>
	Delete: <input type="text" name="lectureName" value="Lecture Name">
	From: <select name="course">
		<?php
			for($i=0; $i<count($courseName);$i++){
				echo '<option value='.$courseFolderName[$i].'>'.$courseName[$i].'</option>';
			}
		?>
	</select>
	Offered By: <input type="text" name="offeredBy">
	<br><br>
	<input type="radio" name="toDelete" value="videos">Video
	<input type="radio" name="toDelete" value="notes">Notes
	<input type="radio" name="toDelete" value="assignments">Assignment
	<br><br>
	<input type="hidden" name="adminName" value="<?php echo $adminName ?>">
	<input type="hidden" name="branch" value="<?php echo $branch ?>">
	<input type="submit" name="delete" value="Delete">
</form>