<?php
	include "header.php";
	
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

			$faculty=""; //$branch="";
			while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
				if($inputCode==strtolower('Admin') && $inputPassword==$row['Password']){
					$faculty=$row['UserName'];
					//$branch=$row['Branch'];
				}	
			$i++;
			}
			if($faculty==""){
				echo '<center><b>Wrong user name or password</b></center>';
				$found=0;
			}
			else{
				$found=1;
				
				echo '<br>
				<form action="/ML/AddDiscipline.php" method="POST">
				<input type="submit" name="addDisciplineButton" value="Add Discipline">
				</form>';
				
				echo '<form action="/ML/AdminLogIn.php" method="POST">
				<input type="submit" name="feedbackButton" value="Show Feedbacks">
				</form>';
				
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
		
				echo '<b>Select a branch:</b><br>
				<form action="/ML/AddCourse.php" method="POST">';
				for($i=0; $i<count($courseCode); $i++){
					echo '<input type="radio" name="branch" value="'.$courseCode[$i].'">'.$courseCode[$i];
				}
				echo '&nbsp;&nbsp;<input type="submit" name="addCourseButton" value="Add Course">
				</form>';
				
				echo '<b>Select a branch:</b><br>
				<form action="/ML/Delete.php" method="POST">';
				for($i=0; $i<count($courseCode); $i++){
					echo '<input type="radio" name="branch" value="'.$courseCode[$i].'">'.$courseCode[$i];
				}
				echo '<input type="hidden" name="adminName" value='.$faculty.'>&nbsp;&nbsp;<input type="submit" name="deleteButton" value="Delete Content">
				</form>';
				
				echo '<b>Select a branch:</b><br>
				<form action="/ML/AdminLogIn.php" method="POST">';
				for($i=0; $i<count($courseCode); $i++){
					echo '<input type="radio" name="branch" value="'.$courseCode[$i].'">'.$courseCode[$i];
				}
				echo '&nbsp;&nbsp;<input type="submit" name="uploadButton" value="Upload Content">
				</form>';
			}
		}
		else echo '<center><b>Fill in all the fields</b></center>';
	}
	if($found==1)die();	
		
	if(isset($_POST['feedbackButton'])){		
		$dbhost='localhost';
		$user='root';
		$password='root';
		$conn=mysql_connect($dbhost, $user, $password);
		if(! $conn){
			die('Could not connect to database : '.mysql_error());
		}
		$db='medialab';
		mysql_select_db($db);

		$select="SELECT * from feedback";
		$retval=mysql_query($select, $conn);
		if(! $retval){
			die('Could not get data : '.mysql_error());
		}
		
		echo '<table border=1 width=100%>
			<tr><td><b>Email</b></td>
				<td><b>Feedback</b></td>
			</tr>
		';
		while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
			echo '
				<tr><td>'.$row['Email'].'</td>
					<td>'.$row['Content'].'</td>
				</tr>';
		}
		echo '</table>';
		die();
	}
	if(isset($_POST['uploadButton'])){
		$branch=$_POST['branch'];
		upload($branch);
		die();
	}
?>

<form action="/ML/AdminLogIn.php" method="POST">
	<center><br>
	<b>User Name:</b> <input type="text" name="user" autofocus><br>
	<b>&nbsp;Password: </b>&nbsp;&nbsp;&nbsp;<input type="password" name="password">
	<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="submit" value="SUBMIT">
	</center>
</form>

<?php
function upload($branch){
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
?>