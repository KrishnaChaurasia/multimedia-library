<?php
include "header.php";
if(isset($_POST['submit'])){
	$dbhost='localhost';
	$user='root';
	$password='root';
	$conn=mysql_connect($dbhost, $user, $password);
	if(! $conn){
		die('Could not connect to database : '.mysql_error());
	}
	$disciplineName=$_POST['disciplineName'];
	$disciplineCode=$_POST['disciplineCode'];
	$select="CREATE DATABASE $disciplineCode";
	$retval=mysql_query($select, $conn);
	if(! $retval){
		die('Could not get data : '.mysql_error());
	}
	
	mysql_select_db($disciplineCode);
	$select="CREATE TABLE courses(
		CourseName varchar(100),
		CourseFolderName varchar(100),
		PRIMARY KEY(CourseName)
	)";
	$retval=mysql_query($select, $conn);
	if(! $retval){
		die('Could not get data : '.mysql_error());
	}
	
	mysql_select_db('medialab');
	$select="INSERT INTO branches VALUES('$disciplineCode','$disciplineName')";
	$retval=mysql_query($select, $conn);
	if(! $retval){
		die('Could not get data : '.mysql_error());
	}
	
	$location='C:/xampp/htdocs/ML/'.$disciplineCode;
	mkdir($location);
	
	echo '<center><b>Discipline created successfully</b></center>';
}

if(!$_POST['addDisciplineButton']){
	die();
}
?>
<form action="/ML/AddDiscipline.php" method="POST">
	<br><center><b>
	Discipline Name: <input type="text" name="disciplineName"><br>
	Discipline Code: &nbsp;<input type="text" name="disciplineCode"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="submit" value="Add">
	</b></center>
</form>