<?php 

	require('navbar.php');
	require('connection.php');

	$semesterType =  $_GET['semesterType'];

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title></title>
		<style>
			#pastCourses{
				width:60%;
    text-align: center;
    margin-left: 20%;
    margin-top: 5%;
    

}
	
   #table1{
      width: 100%;
   }
   #table1 td{
      border: 2px solid lightblue;
      padding:5px;

   }

   #table1 th{
         border: 2px solid blue;
      padding:5px;
   }
   #table1 a {
      display: block;
      text-decoration: none;
   }

   #table1 tr:hover{
      background: lightblue;
      border: 2px solid blue;
   }

		</style>
	</head>
	<body>
		
		
		 <div id = 'pastCourses'>
        <?php 

        $q = "SELECT DISTINCT uname , ccode , course.department, course_name, course.course_id FROM course inner join assign_course ON course.course_id = assign_course.course_id INNER JOIN user ON assign_course.user_id= user.uid where course.department = 'CSE' AND course.semester='$semesterType'";
        	//echo $q;
  
	$result = mysqli_query($conn,$q);
	echo "<h1> Courses of ".$semesterType."</h1>";
	echo '<table id="table1" border="2px solid red">';
	echo '<tr><th> Course Name </th>';
	echo '<th> Teacher Name </th>';
	echo "<th> Clo's Record </th>";
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr>';
		echo "<td>{$row['course_name']}</td>";
		echo "<td>{$row['uname']}</td>";
		echo "<td> <a href='clo_result.php?sem=$semesterType&id={$row['course_id']}'>Check Record</a> </td>";
		
		echo '</tr>';
	}
	echo "</table>";
	?>
    </div>

	</body>
	</html>
