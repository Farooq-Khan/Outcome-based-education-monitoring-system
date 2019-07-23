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

	require 'connection.php';
	require 'navbar.php';

	$batch = $_GET['batch'];
	$plo = $_GET['plo'];
	

	$selectStudents = "Select * from student where std_batch = $batch";
	$result = mysqli_query($conn,$selectStudents);

	echo "<div id='student'><h1 style='text-align:center;margin-top:-40px;margin-bottom:15px'>PLO No $plo Record</h1> <table  style='border: 1px solid blue' id='ploTable'><tr>
	<th>Student Name </th>
	<th>Registration No </th>
	<th>Plo No </th>
	<th> Pass Percent </th>
	<th> Fail Percent </th>
	</tr>";

	while($row = mysqli_fetch_assoc($result)){
		$totalScore = 0;
	$aScoreTotal = 0;
		$SelectEnrolledCourses = "SELECT * FROM student_course WHERE std_id='$row[std_id]'";
		//echo $SelectEnrolledCourses;
		$result2 = mysqli_query($conn,$SelectEnrolledCourses);
			$pass = 0;
			$fail = 0;
				
			while ($row2 = mysqli_fetch_assoc($result2)) {
				
			
			$NLquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = '$row[std_id]' AND clo.clo_level = 'low' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$row[std_regno]' AND clo_plo.plo_id=$plo";
				//echo $NLquery;
				$totalNl = mysqli_query($conn,$NLquery);
				$NL = mysqli_fetch_array($totalNl);
				//echo "VALUE OF NL ".$NL[0];

				$NMquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = '$row[std_id]' AND clo.clo_level = 'medium' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$row[std_regno]' AND clo_plo.plo_id=$plo";

				$totalNM = mysqli_query($conn,$NMquery);
				$NM = mysqli_fetch_array($totalNM);

			$NHquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = '$row[std_id]' AND clo.clo_level = 'HIGH' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$row[std_regno]' AND clo_plo.plo_id=$plo";

				$totalNH = mysqli_query($conn,$NHquery);
				$NH = mysqli_fetch_array($totalNH);

				$Tscore = 33 * $NL[0] + 67*$NM[0] + 100 * $NH[0];
				$totalScore += $Tscore; 

				//echo $Tscore." ";
				//echo $totalScore;
				//To find Ascore of CLO'S

				$Ascorequery = "SELECT sum(obtained_score) FROM clo_attainment INNER JOIN student ON clo_attainment.std_regno=student.std_regno  INNER JOIN clo_plo on clo_attainment.clo_id = clo_plo.clo_id WHERE clo_plo.plo_id = $plo AND student.std_id='$row[std_id]' AND clo_attainment.course_id=$row2[course_id]";
				$aScoreResult = mysqli_query($conn,$Ascorequery);
				$aScore = mysqli_fetch_array($aScoreResult);

				
				$aScoreTotal += $aScore[0];

				//echo $aScoreTotal." ";
				
			$passCLO = "SELECT * FROM clo_plo inner JOIN clo on clo_plo.clo_id=clo.clo_id WHERE plo_id='$plo' AND clo.course_id=$row2[course_id]";
			//echo $passCLO;
			$result4 = mysqli_query($conn,$passCLO);

				while ($row4 = mysqli_fetch_assoc($result4)) {
					# code...
					$confirmPass = "SELECT * FROM clo_attainment inner join clo_plo on clo_attainment.clo_id = clo_plo.clo_id AND clo_plo.plo_id = $plo WHERE clo_attainment.course_id='{$row2['course_id']}' AND clo_attainment.std_regno='$row[std_regno]' AND clo_plo.clo_id = {$row4['clo_id']}";
					//echo $confirmPass;
					$result5 = mysqli_query($conn,$confirmPass);
					$row5 = mysqli_fetch_assoc($result5);
					
						 if($row5['attainment_status'] == 'YES' ||$row5['attainment_status'] == 'yes')		{$pass =$pass +1;}
					else if($row5['attainment_status'] == 'NO' || $row5['attainment_status'] == 'no' )		{$fail = $fail +1 ;}
				}

			}
			// echo "Total score is ".$totalScore;
			// 	echo "Obtained score ".$aScoreTotal;
			if($totalScore != 0){
			$achieved = round(($aScoreTotal / $totalScore)*100);
			//echo $achieved;
			$failed = 100 - $achieved;}
			else{
				$achieved = 0;
				$failed = 0;
			}
			//echo $failed;
			$totalClos = $pass + $fail;
			//echo $totalClos;
			if($totalClos != 0){
			$passPercent = $achieved;
			$failPercent = $failed;
			}else{
				$passPercent= 0;
				$failPercent = 0;
			}
			echo "<tr>
			<td> $row[std_name] </td>
			<td> $row[std_regno] </td>
			<td> $plo </td>
			<td> $passPercent% </td>
			<td> $failPercent% </td>
			</tr>";

	}

	echo '</table></div>';

?>