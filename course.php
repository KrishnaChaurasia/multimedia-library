<?php
include "header.php";

$branch=$_COOKIE['branch'];
$selectedCourse=$_GET['selectedCourse'].'videos';

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
$lectureName[$size]; 
$offeredBy1[$size]; $offeredBy2[$size]; $offeredBy3[$size];
$link1[$size]; $link2[$size]; $link3[$size];
$rating1[$size]; $rating2[$size]; $rating3[$size];

while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
	$lectureName[$i]=$row['LectureName'];
	$offeredBy1[$i]=$row['OfferedBy1']; $offeredBy2[$i]=$row['OfferedBy2']; $offeredBy3[$i]=$row['OfferedBy3'];
	$link1[$i]=$row['Link1']; $link2[$i]=$row['Link2'];$link3[$i]=$row['Link3'];
	$rating1[$i]=$row['Rating1']; $rating2[$i]=$row['Rating2']; $rating3[$i]=$row['Rating3'];
$i++;
}
?>
<html>
	<br><br>
	<table border=1 width=100%>
		<tr>
		<td><center><b> Lecture Name </b></center></td>
		<td><center><b> Offered By </b></center></td>
		<td><center><b> Ratings </b></center></td>
		</tr>
		
		<?php
			for($i=0; $i<count($lectureName); $i++){
				if($link1[$i] && $link2[$i] && $link3[$i]){
					echo '
						<tr id="even">
						<td><center>'.$lectureName[$i].'</center></td>
						<td><center><a href='."Player.php?videoLink=".urlencode($link1[$i]).'>'.$offeredBy1[$i].'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link2[$i]).'>'.$offeredBy2[$i] .'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link3[$i]).'>'.$offeredBy3[$i] .'</a></center>
						</td>
						<td><center>'.$rating1[$i].'<br>'.$rating2[$i].'<br>'.$rating3[$i].'</center></td>
						</tr>';
				}	
				else if($link1[$i] && $link2[$i]){
					echo '
						<tr id="even">
						<td><center>'.$lectureName[$i].'</center></td>
						<td><center><a href='."Player.php?videoLink=".urlencode($link1[$i]).'>'.$offeredBy1[$i].'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link2[$i]).'>'.$offeredBy2[$i] .'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link3[$i]).'>'.$offeredBy3[$i] .'</a></center>
						</td>
						<td><center>'.$rating1[$i].'<br>'.$rating2[$i].'</center></td>
						</tr>';
				}	
				else if($link1[$i]){
					echo '
						<tr id="even">
						<td><center>'.$lectureName[$i].'</center></td>
						<td><center><a href='."Player.php?videoLink=".urlencode($link1[$i]).'>'.$offeredBy1[$i].'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link2[$i]).'>'.$offeredBy2[$i] .'</a></center>
						<center><a href='."Player.php?videoLink=".urlencode($link3[$i]).'>'.$offeredBy3[$i] .'</a></center>
						</td>
						<td><center>'.$rating1[$i].'<br></center></td>
						</tr>';	
				}
			}
		?>
	</table>
</html>
