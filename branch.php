<?php
$branch=$_POST['branch'];
setcookie('branch',$branch);

include "header.php";

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
$size=1000; $i=0;
$courseName[$size]; $courseFolderName[$size]; 
while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
		$courseName[$i]=$row['CourseName'];
		$courseFolderName[$i]=$row['CourseFolderName'];
		$i++;
}
?>

<html>
	<head> <title> <?php echo $branch; ?> </title> </head>
		<table border=1 width=100%>
			<?php
			//courseName shud be in the same order as the tables
			for($i=0; $i<count($courseName); $i++){
			echo '
				<tr id="even">
				<td>'.
					$courseName[$i]
				.'</td>
				<td>
					<a href='."/ML/course.php?selectedCourse=".urlencode($courseFolderName[$i]).'>Videos</a>
				</td>
				<td>
					<a href='."/ML/notes.php?selectedCourse=".urlencode($courseFolderName[$i]).'>Lecture Notes</a>
				</td>
				<td>
					<a href='."/ML/assignments.php?selectedCourse=".urlencode($courseFolderName[$i]).'>Assignments</a>
				</td>
				</tr>';
			}
			?>
		</table>
</html>