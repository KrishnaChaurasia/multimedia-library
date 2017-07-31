<?php
include "header.php";

$branch=$_COOKIE['branch'];
$selectedCourse=$_GET['selectedCourse'].'assignments';

$dbhost='localhost';
$user='root';
$password='root';
$conn=mysql_connect($dbhost, $user, $password);
if(! $conn){
	die('Could not connect to database : '.mysql_error());
}

mysql_select_db($branch);

$select="SELECT * from $selectedCourse";

$retval=mysql_query($select, $conn);
if(! $retval){
	die('Could not get data : '.mysql_error());
}


$size=1000; $i=0;
$assignmentName[$size]; $assignmentLink[$size];
while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
	$assignmentName[$i]=$row['AssignmentName'];
	$assignmentLink[$i]=$row['AssignmentLink'];
$i++;
}
?>
<html>
	<br><br>
	<table border=1 width=100%>
		<tr>
		<td><center><b> Assignments </b></center></td>
		</tr>
		<?php
			for($i=0; $i<count($assignmentName); $i++){
				echo '
					<tr id="even">
					<td><center><a href='.$assignmentLink[$i].'>'
					.$assignmentName[$i]
					.'</a></center></td>
					</tr>';
			}	
		?>
	</table>
</html>