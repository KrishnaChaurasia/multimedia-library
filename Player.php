<?php
include "header.php";

$branch=$_COOKIE['branch'];
$video=$_GET['videoLink'];

function brk($a){
	for($i=0; $i<$a; $i++)
		echo '<br>';
}

function space($a){
	for($i=0; $i<$a; $i++)
		echo '&nbsp;';
}
?>

<?php

if(check()){
$video=$_POST['videoLink'];
$linkArray=explode('/', $video);

$lecture=$linkArray[count($linkArray)-1];
$course=$linkArray[count($linkArray)-3].$linkArray[count($linkArray)-2];

$dbhost='localhost';
$user='root';
$password='root';
$conn=mysql_connect($dbhost, $user, $password);
if(! $conn){
	die('Could not connect to database : '.mysql_error());
}

mysql_select_db($branch);

$select='SELECT * from '.$course;

$retval=mysql_query($select, $conn);
if(! $retval){
	die('Could not get data : '.mysql_error());
}

while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
	$lecture1=explode('/',$row['Link1']);
	$lecture2=explode('/',$row['Link2']);
	$lecture3=explode('/',$row['Link3']);
	
	if($lecture==$lecture1[count($lecture1)-1]){
		$userRating=calculateRating();
		$newRating1=round(($row['Rating1']*$row['Counter1']+$userRating)/($row['Counter1']+1), 2);
		$newCounter1=$row['Counter1']+1;
		$LectureName=$row['LectureName'];
	
		$query="UPDATE $course SET Counter1=$newCounter1,Rating1=$newRating1 WHERE LectureName='$LectureName'";
		$retval=mysql_query($query, $conn);
		if(!$retval) echo 'Error updating the value';
		break;
	}
	if($lecture==$lecture2[count($lecture2)-1]){
		$userRating=calculateRating();
		$newRating2=round(($row['Rating2']*$row['Counter2']+$userRating)/($row['Counter2']+1), 2);
		$newCounter2=$row['Counter2']+1;
		$LectureName=$row['LectureName'];
	
		$query="UPDATE $course SET Counter2=$newCounter2,Rating2=$newRating2 WHERE LectureName='$LectureName'";
		$retval=mysql_query($query, $conn);
		if(!$retval) echo 'Error updating the value';
		break;
	}
	if($lecture==$lecture3[count($lecture3)-1]){
		$userRating=calculateRating();
		$newRating3=round(($row['Rating3']*$row['Counter3']+$userRating)/($row['Counter3']+1), 2);
		$newCounter3=$row['Counter3']+1;
		$LectureName=$row['LectureName'];
	
		$query="UPDATE $course SET Counter3=$newCounter3,Rating3=$newRating3 WHERE LectureName='$LectureName'";
		$retval=mysql_query($query, $conn);
		if(!$retval) echo 'Error updating the value';
		break;
	}
}
	//$rating=calculateRating();
	echo '<b> Your feedback has been submitted successfully</b>';
}
else if($_POST['videoLink']!=NULL){
$video=$_POST['videoLink'];
echo '<b>Please select all the fields for submitting your feedback.</b>';
}
?>

<?php
function calculateRating(){
	$rating=$_POST['group1']+$_POST['group2']+$_POST['group3']+$_POST['group4']+$_POST['group5']+$_POST['group6']+$_POST['group7']+$_POST['group8']+$_POST['group9']+$_POST['group10'];
return $rating/10;
}

function check(){
	if(isset($_POST['group1'])&&isset($_POST['group2'])&&isset($_POST['group3'])&&isset($_POST['group4'])&&isset($_POST['group5'])&&isset($_POST['group6'])&&isset($_POST['group7'])&&isset($_POST['group8'])&&isset($_POST['group9'])&&isset($_POST['group10'])){
		return true;
	}
return false;
}
?>

<html>
	<br><br>
	<body>
		<center> 
			<embed src=<?php echo $video ?> height=600 width=1000> 
		</center>
	</body>
</html>

<form action="<?php echo '/ML/Player.php?videoLink='.urlencode($video) ?>" method=POST>
<br><br>
<u><b>Rate this video</b></u><br>
1. Understandability-----------------------------------
	5<input type="radio" name="group1" value="5"> 
	4<input type="radio" name="group1" value="4"> 
	3<input type="radio" name="group1" value="3"> 
	2<input type="radio" name="group1" value="2">  
	1<input type="radio" name="group1" value="1"> 
<br>
2. Informative------------------------------------------
	5<input type="radio" name="group2" value="5">
	4<input type="radio" name="group2" value="4">
	3<input type="radio" name="group2" value="3">
	2<input type="radio" name="group2" value="2">
	1<input type="radio" name="group2" value="1">
<br>
3. Well motivated-------------------------------------&nbsp;
	5<input type="radio" name="group3" value="5"> 
	4<input type="radio" name="group3" value="4"> 
	3<input type="radio" name="group3" value="3"> 
	2<input type="radio" name="group3" value="2">  
	1<input type="radio" name="group3" value="1"> 
<br>
4. Examples explained--------------------------------&nbsp;
	5<input type="radio" name="group4" value="5"> 
	4<input type="radio" name="group4" value="4"> 
	3<input type="radio" name="group4" value="3"> 
	2<input type="radio" name="group4" value="2">  
	1<input type="radio" name="group4" value="1"> 
<br>
5. Delivery of concepts-------------------------------&nbsp;
	5<input type="radio" name="group5" value="5"> 
	4<input type="radio" name="group5" value="4"> 
	3<input type="radio" name="group5" value="3"> 
	2<input type="radio" name="group5" value="2">  
	1<input type="radio" name="group5" value="1"> 
<br>
6. Level of abstraction is appropriate----------------
	5<input type="radio" name="group6" value="5"> 
	4<input type="radio" name="group6" value="4"> 
	3<input type="radio" name="group6" value="3"> 
	2<input type="radio" name="group6" value="2">  
	1<input type="radio" name="group6" value="1"> 
<br>
7. Appropriateness of points delivered---------------
	5<input type="radio" name="group7" value="5"> 
	4<input type="radio" name="group7" value="4"> 
	3<input type="radio" name="group7" value="3"> 
	2<input type="radio" name="group7" value="2">  
	1<input type="radio" name="group7" value="1"> 
<br>
8. Flow of thought--------------------------------------
	5<input type="radio" name="group8" value="5"> 
	4<input type="radio" name="group8" value="4"> 
	3<input type="radio" name="group8" value="3"> 
	2<input type="radio" name="group8" value="2">  
	1<input type="radio" name="group8" value="1"> 
<br>
9. Good coverage of concepts-------------------------
	5<input type="radio" name="group9" value="5"> 
	4<input type="radio" name="group9" value="4"> 
	3<input type="radio" name="group9" value="3"> 
	2<input type="radio" name="group9" value="2">  
	1<input type="radio" name="group9" value="1"> 
<br>
10. Good for beginners---------------------------------
	5<input type="radio" name="group10" value="5"> 
	4<input type="radio" name="group10" value="4"> 
	3<input type="radio" name="group10" value="3"> 
	2<input type="radio" name="group10" value="2">  
	1<input type="radio" name="group10" value="1"> 
<br>
	<input type="hidden" name="videoLink" value="<?php echo $video ?>">
	<br><input type="submit" name="submit" value="Submit Feedback">
</form>

