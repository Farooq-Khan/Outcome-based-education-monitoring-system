<?php
		$host = 'localhost';
		$username = 'root';
		$password = '';
		$database = 'uet_db';
		
		$conn = mysqli_connect($host , $username , $password,$database);
		
		if(!$conn){
			die("connection failed ".mysqli_error());
		}
		else
		{
			echo "<script> console.log('database Connected')</script>";
		}

	
?>