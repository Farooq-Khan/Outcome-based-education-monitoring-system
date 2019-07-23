<script type="text/javascript">
	function gotopage(std_reg,plo){
		window.location.href = "plodetail.php?std_id="+std_reg+"&plo="+plo;
	}
</script><!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type='text/javascript' src="Chart.min.js"></script>
	<style>
		
		.container1{
            width:15%;
            border: 1px solid lightgreen;
            display: inline-block;
            margin-left: 1%;
		}
		.container1 canvas{
			width: 100%;
			height: auto;
		}

		.container1:hover{
			cursor: pointer;
			background-color: #EEE;
		}

		#main{
			border: 3px solid blue;
			margin-left: 0px;

		}
		#subMain1{
			width: 90%;
			margin-left: 7%;
		}
		#subMain2{
			width: 90%;
			margin-left: 7%;
		}
	</style>
</head>
<body>

	<script>
 	
	function javascriptFun(passPercent,failPercent , j , plo){
		
        let myChart = document.getElementById('myChart'+j).getContext('2d');
		
        let scoreChart = new Chart(myChart, {
            type:'bar',
            data:{
                labels:['Pass ' +passPercent+'%' ,'Fail '+failPercent+'%'],
                datasets:[{
                    label: 'PLO ' +plo,
                    data:[passPercent,failPercent],
                    backgroundColor:[
                    'green',
                    'red'
                ]
                }],
               
            },
  options:{
				maintainAspectRatio: false,
				scales:{
					xAxes: [{
            			barPercentage: 1
        					}],
					yAxes:[{
						ticks:{
							beginAtZero:true,
							max:100
							}
						}]}}
            
        });
		return;
	}
    </script>

</body>
</html>
<?php 

	require 'connection.php';
	require 'navbar.php';

	$std_id = $_GET['id'];
	$batch = $_GET['std_batch'];

	$SR = "SELECT std_regno,std_name FROM `student` WHERE std_id = '$std_id' "; //Student Registration
	$RR = mysqli_query($conn,$SR); //Registration Result
	if(!$RR){
		echo mysqli_error();
	}
	else
	{
		$rowOf_std_regno = mysqli_fetch_assoc($RR);
		$std_regno = $rowOf_std_regno['std_regno'];
		$std_name = $rowOf_std_regno['std_name'];
	}
	

		echo "<div id='main' ><h1 style='text-align:center;padding-top:10px;'>$std_name ($std_regno) PLO's Record</h1>";
		$plo = 0;
		
		for($div = 1; $div < 3 ; $div++){
			
			echo "<br><div id='subMain$div'>";
		for($i = 1; $i < 7 ; $i++){
			$plo++;
			$totalScore = 0;
		$aScoreTotal = 0;
		$SelectEnrolledCourses = "SELECT * FROM student_course WHERE std_id='$std_id'";
		$result2 = mysqli_query($conn,$SelectEnrolledCourses);
			$pass = 0;
			$fail = 0;
				
			while ($row2 = mysqli_fetch_assoc($result2)){
				
				$NLquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = $std_id AND clo.clo_level = 'low' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$std_regno' AND clo_plo.plo_id=$plo";
				
				$totalNl = mysqli_query($conn,$NLquery);
				$NL = mysqli_fetch_array($totalNl);
				

				$NMquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = $std_id AND clo.clo_level = 'medium' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$std_regno' AND clo_plo.plo_id=$plo";

				$totalNM = mysqli_query($conn,$NMquery);
				$NM = mysqli_fetch_array($totalNM);

			$NHquery = "SELECT count(*) FROM `student_course` INNER JOIN clo ON clo.course_id = student_course.course_id INNER JOIN clo_plo ON clo_plo.clo_id = clo.clo_id INNER JOIN clo_attainment on clo_attainment.clo_id = clo.clo_id INNER JOIN student on clo_attainment.std_regno = student.std_regno WHERE student_course.std_id = $std_id AND clo.clo_level = 'HIGH' AND student_course.course_id = $row2[course_id] AND clo_attainment.std_regno = '$std_regno' AND clo_plo.plo_id=$plo";

				$totalNH = mysqli_query($conn,$NHquery);
				$NH = mysqli_fetch_array($totalNH);

				$Tscore = 33 * $NL[0] + 67*$NM[0] + 100 * $NH[0];
				$totalScore += $Tscore; 

				
				//To find Ascore of CLO'S

				$Ascorequery = "SELECT sum(obtained_score) FROM clo_attainment INNER JOIN student ON clo_attainment.std_regno=student.std_regno  INNER JOIN clo_plo on clo_attainment.clo_id = clo_plo.clo_id WHERE clo_plo.plo_id = $plo AND student.std_id=$std_id AND clo_attainment.course_id=$row2[course_id]";
				$aScoreResult = mysqli_query($conn,$Ascorequery);
				$aScore = mysqli_fetch_array($aScoreResult);

				
				$aScoreTotal += $aScore[0];

				
				
			$passCLO = "SELECT * FROM clo_plo inner JOIN clo on clo_plo.clo_id=clo.clo_id WHERE plo_id='$plo' AND clo.course_id=$row2[course_id]";
			
			$result4 = mysqli_query($conn,$passCLO);

				while ($row4 = mysqli_fetch_assoc($result4)) {
					# code...
					$confirmPass = "SELECT * FROM clo_attainment inner join clo_plo on clo_attainment.clo_id = clo_plo.clo_id AND clo_plo.plo_id = $plo WHERE clo_attainment.course_id='{$row2['course_id']}' AND clo_attainment.std_regno='$std_regno' AND clo_plo.clo_id = {$row4['clo_id']}";
					
					$result5 = mysqli_query($conn,$confirmPass);
					$row5 = mysqli_fetch_assoc($result5);
					
						 if($row5['attainment_status'] == 'YES' ||$row5['attainment_status'] == 'yes')		{$pass =$pass +1;}
					else if($row5['attainment_status'] == 'NO' || $row5['attainment_status'] == 'no' )		{$fail = $fail +1 ;}
				}

			}
			
			if($totalScore != 0){
			$achieved = round(($aScoreTotal / $totalScore)*100);
			
			$failed = 100 - $achieved;}
			else{
				$achieved = 0;
				$failed = 0;
			}
			
			$totalClos = $pass + $fail;
			
			if($totalClos != 0){
			$passPercent = $achieved;
			$failPercent = $failed;
			}else{
				$passPercent= 0;
				$failPercent = 0;
			}
		
			
				echo"<div class='container1' onclick=gotopage('$std_regno','$plo')>

   					        <canvas id='myChart$div$i' style='height: 300px'>
        					</canvas>

        		<script type='text/javascript' > javascriptFun('$passPercent','$failPercent','$div$i','$plo') </script>
							
    		</div>";
			}

			echo "</div>";
		}

			echo "</div>";


?>