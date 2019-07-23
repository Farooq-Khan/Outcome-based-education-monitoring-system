

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel = 'stylesheet' type='text/css' media='screen' href='index.css' />
    <link rel="stylesheet" type="text/css" href="courses.css" />  
    <script type='text/javascript' src="Chart.min.js"></script>
    
</head>

<?php 
    require_once 'connection.php';
    require('navbar.php');
    
	$semesterType = $_GET['semesterType'];
	$AssMax =0;
	$max=0;
	$taken=0;
	$marks = 0;
	$num=0;
	

    //echo $D_id;
?>

<?php 
	function myfunName($course_id,$i,$semesterType){

		require('connection.php');
	$sysDate = date('y/m/d');
	
	$courseSch = "SELECT COUNT(*) FROM schedule inner join course on course.course_id=schedule.course_id WHERE course.course_id = '$course_id' AND course.semester = '$semesterType'";
	//echo $courseSch;
	$result3 = mysqli_query($conn,$courseSch);
	$scheduleRow = mysqli_fetch_array($result3);
	$schNumRows =  $scheduleRow[0];
		
		if($schNumRows == 0){
			
			echo "Course is not scheduled yet";
			}
		else{
			echo"<div id='container'>

   					        <canvas id='myChart$i' style = 'width:280%; height:300px' >
        					</canvas>
							
    		</div>";
	/*
		This php code is for retreiving the quizez record.
	*/
	
	/*This query is for finding the number of rows of quizes required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'quiz' AND course_id = '$course_id' ";//AND sem_type='$semesterType' ";
	
	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_array($totalRows);
	$numRows =  $total[0];
	
	
	/*This query is for finding the quizes which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'quiz' AND course_id = '$course_id'";//" AND sem_type='$semesterType' ";
	$result = mysqli_query($conn,$date);
	
	
	/*missed represent the quizes which are not taken so far but the date is already passed*/
	$missed = 0;
	/*taken represent the quizes which are taken so far */
	$taken = 0;
	$max = $numRows;
	
	while($row = mysqli_fetch_assoc($result)){
	
	/*This query is for the scheduled courses in the mentioned semester and date*/
		$confirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		AND schedule.course_id = '$course_id' AND exam_type = 'quiz'";//"  AND schedule.sem_type='$semesterType' ";
		$result2 = mysqli_query($conn,$confirm);
		$r = mysqli_fetch_array($result2);
		$numRows =  $r[0];
		
		if($numRows > 0){
				$taken++;
			}
		
		else{
				$missed++;
			}	
	}
	/*
		This php code is for retreiving the assignment record.
	*/
		
	/*This query is for finding the number of rows of assignments required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'assignment' AND course_id = '$course_id' ";

	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_array($totalRows);
	$AssnumRows =  $total[0];
	
	/*This query is for finding the assignments which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'assignment' AND course_id = '$course_id' ";
	$assresult = mysqli_query($conn,$date);

	$AssTaken = 0;
	$AssMissed = 0;
	$AssMax = $AssnumRows;
	
	while($row = mysqli_fetch_assoc($assresult)){
		
		$AssignmentConfirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		 AND schedule.course_id = '$course_id' AND exam_type = 'assignment'";//"  AND schedule.sem_type='$semesterType' ";
		 
		 
		$assgResult = mysqli_query($conn,$AssignmentConfirm);
		$res = mysqli_fetch_array($assgResult);
		$AssNumRows =  $res[0];
		
		
		
		if($AssNumRows > 0){
				$AssTaken++;
				//echo "     Marks of Quiz on date {$row['date']} has been updated!";
			}
		
		else{
				$AssMissed++;
				//echo "      Marks of Quiz on date {$row['date']} has not been updated!";
		}	
	}

		//Collective result of quizzes and assignments
		$marks = $max + $AssMax;
		
		$takened = $taken + $AssTaken;
		echo "<script type='text/javascript' > javascriptFun('$marks','$takened','$i') </script>";
		}
		return;
	}
	
	?>
	


<body>
    <div id = 'course-container'>
        	
        <div id="colors">
        <button class="btn"  style="background-color:#f00; "> Zero Progress </button>
        	<button class="btn" style="background-color:brown;">  Worst </button>
            <button class="btn" style="background-color:yellow;">   Average </button>
            <button class="btn" style="background-color:green"> Best  </button>
        </div>
        <br/>
        <!-- <button class="teacher"><a href="teacher.php?id=<?php echo $D_id; ?>&semester=<?php echo $semesterType ?>" style="padding:2%">Click To See Teacher Performance</a></button> -->

 <script>
 	
	function javascriptFun(marks,takened , j){

		var percent = (takened/marks)*100;

		if(percent > 0 && percent < 40)
				{ var color = '#C63'}
		else if(percent >= 40 && percent <= 60)
				{var color = '#FF0'}
		else if(takened == 0)
			{ takened = marks;		color = '#f00';}
		else{var color = '#0C3'}
	
        let myChart = document.getElementById('myChart'+j).getContext('2d');
		j++;
        let scoreChart = new Chart(myChart, {
            type:'horizontalBar',
            data:{
                labels:['Achieved','Required'],
                datasets:[{
                    label: 'progress',
                    data:[takened,marks],
                    backgroundColor:[
                    color,
                    'green'
                ]
                }],
               
            },
            options:{
                scales: {
                xAxes: [{
                    barPercentage: 1,
                    categoryPercentage: 1,
                    ticks: {
                        beginAtZero: true
                    }
                   
                }],
                yAxes: [{
                barPercentage: 0.5,
                    categoryPercentage: 1,

                }]
            }
    }
            
        });
		return;
	}
    </script>
        <div id = 'select-courses'>
            <h1><?php echo $semesterType; ?> Faculty/Courses Progress Report </h1>
            <?php 
			
				 $sqll = "SELECT DISTINCT uname , ccode , course_name, course.course_id FROM course inner join assign_course ON course.course_id = assign_course.course_id INNER JOIN user ON assign_course.user_id= user.uid where course.department = 'CSE' AND course.semester='$semesterType'";
				//echo $sqll;

                $result = mysqli_query($conn,$sqll);
            echo "<div class = 'courses' ><table id='progresstable' border =1px solid #768>
            <tr>
                <th> Course Code </th>
                <th> Course Name </th>
                <th> Teacher Name </th>
				<th> Status </th>
				
                </tr>
                ";
            
               while($row = mysqli_fetch_assoc($result) ){
                   $code = $row['course_id'];
                   
				   
				   echo "<tr>
				   			
                            <td> {$row['ccode']} </td>
                            <td> {$row['course_name']} </td>
                            <td> {$row['uname']} </td>
							<td><a href = 'status.php?id=$code&semester=$semesterType' >";
							 myFunName($code,$num,$semesterType);
							$num++; 
							echo "</a></td>
							
                            </tr> 
                            ";
                   
                }    
				echo "</table></div>";
				
            ?>
        </div>

    </div>
<!--  /*********************************************************/ -->

</body>
</html>