<?php
	require_once "connection.php";
	$code = $_GET['id'];
	$semesterType = $_GET['semester'];
	$sysDate = date('y/m/d');


	$CC = "SELECT ccode,course_name FROM `course` WHERE course_id = '$code' "; //Course Code 
	$CR = mysqli_query($conn,$CC); //Course Result
	
		$rowOf_course_code = mysqli_fetch_assoc($CR);
		$corseCode = $rowOf_course_code['ccode'];
		$courseName = $rowOf_course_code['course_name'];
	
	
	$courseSch = "SELECT COUNT(*) FROM schedule WHERE course_id = '$code'";
	$result3 = mysqli_query($conn,$courseSch);
	$scheduleRow = mysqli_fetch_row($result3);
		$schNumRows =  $scheduleRow[0];
		
		if($schNumRows == 0){
			echo "<script> alert('Course is not scheduled yet') </script>";
			}
		else{

	/*
		This php code is for retreiving the quizez record.
	*/
	
	/*This query is for finding the quizes which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'quiz' AND course_id = '$code'";//" AND sem_type = '$semesterType'";
	$result = mysqli_query($conn,$date);
	
	/*This query is for finding the number of rows of quizes required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'quiz' AND course_id = '$code'";//" AND sem_type = '$semesterType'";

	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_row($totalRows);
	$numRows =  $total[0];
	/*missed represent the quizes which are not taken so far but the date is already passed*/
	$missed = 0;
	/*taken represent the quizes which are taken so far */
	$taken = 0;
	$max = $numRows;
	
	while($row = mysqli_fetch_assoc($result)){
	
	/**/
		$confirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		AND schedule.course_id = '$code'";//" AND schedule.sem_type = '$semesterType'";
		$result2 = mysqli_query($conn,$confirm);
		$r = mysqli_fetch_row($result2);
		$numRows =  $r[0];
		
		if($numRows > 0){
				$taken++;
			}
		
		else{
				$missed++;
			}	
	}
	if($max != 0 ){
	$quizPercent = ($taken/$max)*100;}
	else{
		$quizPercent = 0;
		}
	
	
	/*
		This php code is for retreiving the assignment record.
	*/
	
	/*This query is for finding the assignment which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'assignment' AND course_id = '$code'";//" AND sem_type = '$semesterType'";
	$result = mysqli_query($conn,$date);
	
	/*This query is for finding the number of rows of assignment required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'assignment' AND course_id = '$code'";//" AND sem_type = '$semesterType'";
	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_row($totalRows);
	$numRows =  $total[0];
	$AssMissed = 0;
	$AssTaken = 0;
	$AssMax = $numRows;
	
	while($row = mysqli_fetch_assoc($result)){
		
		$AssignmentConfirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		 AND schedule.course_id = '$code'";//" AND schedule.sem_type = '$semesterType'";
	
		$assgResult = mysqli_query($conn,$AssignmentConfirm);
		$res = mysqli_fetch_row($assgResult);
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
	if($AssMax != 0 ){
	$AssPercent = ($AssTaken/$AssMax)*100;}
	else{
		$AssPercent = 0;
		}
		
		/*
		This php code is for retreiving the midterm record.
	*/
	
	/*This query is for finding the midterm which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'Mid term' AND course_id = '$code'";//" AND sem_type = '$semesterType'";
	$result = mysqli_query($conn,$date);
	
	/*This query is for finding the number of rows of midterm required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'Mid term' AND course_id = '$code'";//" AND sem_type = '$semesterType'";

	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_row($totalRows);
	$numRows =  $total[0];
	/*missed represent the midterm which is not taken so far but the date is already passed*/
	$Midmissed = 0;
	/*taken represent the midterm which is taken so far */
	$Midtaken = 0;
	$Midmax = $numRows;
	
	while($row = mysqli_fetch_assoc($result)){
	
	/**/
		$confirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		AND schedule.course_id = '$code'";//" AND schedule.sem_type = '$semesterType'";
		$result2 = mysqli_query($conn,$confirm);
		$r = mysqli_fetch_row($result2);
		$numRows =  $r[0];
		
		if($numRows > 0){
				$Midtaken++;
			}
		
		else{
				$Midmissed++;
			}	
	}
	if($Midmax != 0 ){
	$MidPercent = ($Midtaken/$Midmax)*100;
	}
	else{
		$MidPercent = 0;}
		
		


			/*
		This php code is for retreiving the final term record.
	*/
	
	/*This query is for finding the final term which date has been passed.*/
	$date = "SELECT * FROM schedule WHERE date < '$sysDate' AND exam_type = 'final term' AND course_id = '$code'";//" AND sem_type = '$semesterType'";
	$result = mysqli_query($conn,$date);
	
	/*This query is for finding the number of rows of final term required*/
	$dateNum = "SELECT count(*) FROM schedule WHERE date < '$sysDate' AND exam_type = 'final term' AND course_id = '$code'";//" AND sem_type = '$semesterType'";

	$totalRows = mysqli_query($conn,$dateNum);
	$total = mysqli_fetch_row($totalRows);
	$numRows =  $total[0];
	/*missed represent the final term which is not taken so far but the date is already passed*/
	$finalmissed = 0;
	/*taken represent the final term which is taken so far */
	$finaltaken = 0;
	$finalmax = $numRows;
	
	while($row = mysqli_fetch_assoc($result)){
	
	/**/
		$confirm = "SELECT count(*) FROM `exam_score` INNER JOIN `schedule` 
		ON exam_score.schedule_no = schedule.s_no AND schedule.date = '$row[date]'
		AND schedule.course_id = '$code'";//" AND schedule.sem_type = '$semesterType'";
		$result2 = mysqli_query($conn,$confirm);
		$r = mysqli_fetch_row($result2);
		$numRows =  $r[0];
		
		if($numRows > 0){
				$finaltaken++;
			}
		
		else{
				$finalmissed++;
			}	
	}
	if($finalmax != 0 ){
	$finalPercent = ($finaltaken/$finalmax)*100;}
	else{
		$finalPercent = 0;
	}
}
	
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel = 'stylesheet' type='text/css' media='screen' href='index.css' />
    <script type='text/javascript' src="Chart.min.js"></script>

    <style>
    #course-container{
        text-align:center;
    }
	
	 #container{
	 	margin-top: 1%;
            width:20%;
            display: inline-block;
            border: 3px solid lightgreen;
        }

        	 #container2{
            width:20%;
            margin-top: 1%;
            display: inline-block;
            border: 3px solid lightgreen;

        }
		
		#container3{
            width:20%;
            margin-top: 1%;
            display: inline-block;
            border: 3px solid lightgreen;

        }

        #container4{
            width:20%;
            margin-top: 1%;
            display: inline-block;
            border: 3px solid lightgreen;

        }
        #main-container h1{
        	margin-top: 20px;
        }
        canvas{
        	width: 100%;
        }
		
        canvas:hover{
    border: 3px solid green;
    background: lightgrey;
}

.btn {
   	display: inline-block;
   	border-radius: 12px;
   	 	width: 10%;
   	 	height: 50px;
   	 	color: black;
   	 	font-weight: bold;
   	 	border: 0px;
   }
   #colors{
   	margin-top: 1%;
   }
    </style>

    </style>
</head>

<body>

	<div id = 'main-container'>
    <?php include "navbar.php"; ?>
        <div id="colors">
        <button class="btn"  style="background-color:#f00"> Zero Progress </button>
        	<button class="btn" style="background-color:#C63">  Worst </button>
            <button class="btn" style="background-color:#FF0">   Average </button>
            <button class="btn" style="background-color:#0C3"> Best  </button>
        </div>
        <h1> <?php echo $corseCode." ".$courseName ?> Progress Report</h1>
     
    <div id="container">
   
        <canvas id="myChart" style = "height:300px">
        </canvas> 
    </div>
    <div id="container2">
   
        <canvas id="myChart2" style = "height:300px">
        </canvas> 
    </div>
    
    <div id="container3">
   
        <canvas id="myChart3" style = "height:300px">
        </canvas> 
    </div>

    <div id="container4">
   
        <canvas id="myChart4" style = "height:300px">
        </canvas> 
    </div>

    <script>
    	var maxi = <?php echo $max; ?>;
		var taken = <?php echo $taken; ?>;
		var percent = <?php echo $quizPercent; ?>;
		
		if(percent >0 && percent <= 40)
		{ var color = '#C63'}
		else if(percent > 40 && percent <= 60){var color = '#FF0'}
		else if(percent == 0 ){ var color = '#f00'; taken = maxi;}
		else{var color = '#0C3'}
		
		
		
        let myChart2 = document.getElementById('myChart').getContext('2d');
        let scoreChart2 = new Chart(myChart2, {
            type:'bar',
            data:{
                labels:['Required','Taken'],
                datasets:[{
                    
                    data:[maxi,taken],
                    backgroundColor:['#006400',color],
					label: 'Quizzes Record'
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
							max:6
							}
						}]}}
        });

    </script>

    <script>
    	
    	var assMaxi = <?php echo $AssMax; ?>;
		var asstaken = <?php echo $AssTaken ?>;
		var asspercent = <?php echo $AssPercent ?> ;

		/*Assignments colors*/
		if(asspercent >0 && asspercent <= 40)
		{ var asscolor = '#C63'}
		else if(asspercent > 40 && asspercent <= 60)
			{var asscolor = '#FF0'}
		else if(asspercent == 0 )
			{ var asscolor = '#f00'; asstaken = assMaxi;}
		else{var asscolor = '#0C3'}


        let myChart = document.getElementById('myChart2').getContext('2d');
        let scoreChart = new Chart(myChart, {
            type:'bar',
            data:{
                labels:['Required','Taken'],
                datasets:[{
                    
                    data:[assMaxi,asstaken],
                    backgroundColor:['#006400',asscolor],
					label: 'Assignments Record'
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
							max:6
							}
						}]}}
        });		

    </script>
    
    <script>
    	var maxi = <?php echo $Midmax; ?>;
		var taken = <?php echo $Midtaken; ?>;
		var percent = <?php echo $MidPercent; ?>;

		
		if(percent >0 && percent<= 40)
		{ var color = '#C63';}
		else if(percent > 40 && percent <= 60)
			{var color = '#FF0';}
		else if(percent == 0 )
			{ var color = '#f00'; taken = maxi;}
		else{var color = '#0C3';}
		
		
		
        let myChart3 = document.getElementById('myChart3').getContext('2d');
        let scoreChart3 = new Chart(myChart3, {
            type:'bar',
            data:{
                labels:['Midterm','Midterm Taken'],
                datasets:[{
                    
                    data:[maxi,taken],
                    backgroundColor:['#006400',color],
					label: 'Mid Record'
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
							max:1
							}
						}]}}
        });

    </script>

    <script>
    	var maxi = <?php echo $finalmax; ?>;
		var taken = <?php echo $finaltaken; ?>;
		var percent = <?php echo $finalPercent; ?>;

		
		if(percent >0 && percent<= 40)
		{ var color = '#C63';}
		else if(percent > 40 && percent <= 60)
			{var color = '#FF0';}
		else if(percent == 0 )
			{ var color = '#f00'; taken = maxi;}
		else{var color = '#0C3';}
		
		
		
        let myChart4 = document.getElementById('myChart4').getContext('2d');
        let scoreChart4 = new Chart(myChart4, {
            type:'bar',
            data:{
                labels:['Finalterm','Finalterm Taken'],
                datasets:[{
                    
                    data:[maxi,taken],
                    backgroundColor:['#006400',color],
					label: 'Final Record'
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
							max:1
							}
						}]}}
        });

    </script>
    

    </div>
</body>
</html>

