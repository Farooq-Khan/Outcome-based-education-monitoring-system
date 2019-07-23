<!DOCTYPE html>
<html>
<head>
	<title> PLO Details </title>
	 <link rel="stylesheet" type="text/css" href="courses.css" />  
</head>
<body>

</body>
</html>

<?php
	require_once "connection.php";
	require 'navbar.php';

	$std_reg = $_GET['std_id'];
	$plo = $_GET['plo'];
	//echo $plo;

	$registeredCourses = "SELECT * FROM student inner join student_course on student.std_id = student_course.std_id WHERE student.std_regno = '$std_reg'";
	$result = mysqli_query($conn,$registeredCourses);

	echo "<div id='student'><h1 style='text-align:center;'> PLO $plo Targeted CLO's Record</h1><table  style='border: 1px solid blue' id='ploTable'><tr>
	<th>Student Registration </th>
	<th>Student Name </th>
	<th>Course Code </th>
	<th>Course Name </th>
	<th>Clo No </th>
	<th>CLO Level </th>
	<th>Obtain Marks </th>
	</tr>";
	while($rowOfCourses = mysqli_fetch_assoc($result))
	{
		//echo $rowOfCourses['C_code']."	   ";
		$findCLO = "SELECT * FROM clo INNER JOIN clo_plo ON clo.clo_id = clo_plo.clo_id WHERE clo.course_id = '$rowOfCourses[course_id]' AND clo_plo.plo_id = '$plo' ";
		//echo $findCLO;

		$cloPlo = mysqli_query($conn,$findCLO);

		while ($rowOfcloplo = mysqli_fetch_assoc($cloPlo)) {
			# code...
			//echo $rowOfcloplo['clo_id'];
			$cloAssessment = "SELECT * FROM `clo` INNER JOIN clo_attainment ON clo.clo_id = clo_attainment.clo_id WHERE clo.clo_id = '$rowOfcloplo[clo_id]' AND clo_attainment.std_regno = '$std_reg' AND clo_attainment.course_id = '$rowOfCourses[course_id]' ";
			//echo $cloAssessment;
			$cloStatus = mysqli_query($conn,$cloAssessment);
			while ($rowOfStatus = mysqli_fetch_assoc($cloStatus)) {
				# code...
				$courseName = "SELECT DISTINCT course_name FROM course WHERE course_id = '$rowOfCourses[course_id]' ";
				//echo $courseName;
				$cName = mysqli_query($conn,$courseName);
				$rowOfCourseName = mysqli_fetch_assoc($cName);
				//echo $rowOfCourseName['C_name'];

				$courseCode = "SELECT DISTINCT ccode FROM course WHERE course_id = '$rowOfCourses[course_id]' ";
				//echo $courseName;
				$cCode = mysqli_query($conn,$courseCode);
				$rowOfCourseCode = mysqli_fetch_assoc($cCode);

				$stdName = "SELECT DISTINCT std_name FROM student WHERE std_regno = '$std_reg' ";
				$sName = mysqli_query($conn,$stdName);
				$rowOfStdName = mysqli_fetch_assoc($sName);

			echo "<tr>
			<td> $std_reg </td>
			<td> $rowOfStdName[std_name] </td>
			<td> $rowOfCourseCode[ccode] </td>
			
			<td> $rowOfCourseName[course_name] </td>
			<td> $rowOfStatus[clo_no] </td>
			
			<td> $rowOfStatus[clo_level] </td>
			<td> $rowOfStatus[obtained_score] </td>
			</tr>";
			}

			
		}

	}
	echo "</table></div>";

?>
