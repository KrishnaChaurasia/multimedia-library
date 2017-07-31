<?php
include 'header.php';
$userCode=$_GET['userCode'];

	if(isset($_POST['submit'])){
		if(!empty($_POST['currentPassword'])&&!empty($_POST['newPassword'])&&!empty($_POST['confirmPassword'])){
			$dbhost='localhost';
			$user='root';
			$password='root';
			$conn=mysql_connect($dbhost, $user, $password);
			if(! $conn){
				die('Couldnot connect to database : '.mysql_error());
			}

			$db='loginmedialab';
			mysql_select_db($db);

			$select='SELECT UserCode, UserName, Password, Branch from users';

			$retval=mysql_query($select, $conn);
			if(! $retval){
				die('Couldnot get data : '.mysql_error());
			}

			$faculty="";
			while($row=mysql_fetch_array($retval, MYSQL_ASSOC)){
				if($_POST['currentPassword']==$row['Password']){
					if($_POST['newPassword']==$_POST['confirmPassword']){
						$newPassword=md5($_POST['newPassword']);
						$userCode=strtoupper($_POST['userCode']);
						
						$query="UPDATE users SET Password=$newPassword WHERE UserCode='$userCode'";
						$retval=mysql_query($query, $conn);
						if(!$retval) echo 'Error updating the value';
						else echo 'Password changed successfully';
						die();
					}
					else {
						echo 'Password mismatch';
						die();
					}
				}
				//else {
				//	echo 'Wrong password';
				//	die();
				//}
			}
		}	
		else {
			echo 'Fill in all the fields';
			die();
		}			
	}

?>
<form action="/ML/ChangePassword.php" method=POST>
<center><br><br>
	Current Password: &nbsp;
	<input type="password" name="currentPassword">
	<br>&nbsp;New Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<input type="password" name="newPassword">
	<br>Confirm Password:&nbsp; 
	<input type="password" name="confirmPassword">
	<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="submit" value="Change">
	
	<input type="hidden" name="userCode" value="<?php echo $userCode ?>">
</center>
</form>
