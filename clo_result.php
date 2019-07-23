<!DOCTYPE html>
<?php 
	
	 require_once 'connection.php';
	 require_once 'navbar.php';
	$semester = $_GET['sem'];
	$course_id = $_GET['id'];
?>
<html>
<head>
	<title></title>
	<script type='text/javascript' src="Chart.min.js"></script>

	 <style>
	 	*{
	 		margin:0px;
	 		padding: 0px;
	 	}
	
	 .chart-container{
	 		margin-top: 3%;
			 margin-left:15%;
            width:70%;
			height:400px;
            display: inline-block;
            border: 3px solid lightgreen;
        }
        .chart-container canvas{
        	width: 100%;
        	height: 400px;

        }

        canvas:hover{
        	width: 100%;
    border: 3px solid green;
    background: lightgrey;
}

	
    </style>

</head>
<body>

	<div class="chart-container">
		<canvas id="bar-chartcanvas"></canvas>
	</div>

	<!-- javascript -->
    <script src="jquery.min.js"></script>
    <script src="Chart.min.js"></script>
</body>
</html>
    <?php
    $query = "select * from course join clo on course.course_id = clo.course_id where course.course_id = '".$_REQUEST['id']."'";
    $rs = mysqli_query($conn,$query);
    //echo $query;
    //$row = mysqli_fetch_assoc($rs);
    //print_r($row);
    $course_title = "";
    $clos = array();
    $data1 = array();
    $data2 = array();
    $bg1 = array();
    $bg2 = array();
    $fg = array();
    //$clos[] = $row['clo_no'];
    while($row = mysqli_fetch_assoc($rs)){
    if($course_title == "")
    $course_title = $row['ccode']." ".$row['course_name'].": CLO Attainment Graph";
    $clos[] = $row['clo_no'];
    $query = "select clo_id, (select count(*) from clo_attainment where clo_id = ct.clo_id and attainment_status = 'YES') as attained, 
    (select count(*) from clo_attainment where clo_id = ct.clo_id and attainment_status = 'NO') as not_attained from clo_attainment as ct where ct.clo_id = '".$row['clo_id']."' GROUP BY ct.clo_id";
    //echo $query;
    $r = mysqli_fetch_assoc(mysqli_query($conn,$query)); 
    $No_of_passed_students = $r['attained'];
    $No_of_failed_students = $r['not_attained'];
    $totalStudents = $No_of_passed_students + $No_of_failed_students;
    if($totalStudents != 0){
    $data1[] = round(($No_of_passed_students/$totalStudents)*100);
    $data2[] = round(($No_of_failed_students/$totalStudents)*100);
    $bg1[] = "#006400";
    $bg2[] = "red";
    $fg[] = "rgba(10, 20, 30, 1)";}
    //$data1[] = $r['attained'];
    //$data2[] = $r['not_attained'];
    }


    
    ?>

    
    <script>
    $(document).ready(function () {

    var ctx = $("#bar-chartcanvas");

    var data = {
        labels : <?php echo json_encode($clos); ?>,
        datasets : [
            {
                label : "Percentage of CLO Attained",
                data : <?php echo json_encode($data1); ?>,
                backgroundColor : <?php echo json_encode($bg1); ?>,
                borderColor : <?php echo json_encode($fg); ?>,
                borderWidth : 1
            },
            {
                label : "Percentage of CLO Not Attained",
                data : <?php echo json_encode($data2); ?>,
                backgroundColor : <?php echo json_encode($bg2); ?>,
                borderColor : <?php echo json_encode($fg); ?>,
                borderWidth : 1
            }
        ]
    };

    var options = {
        title : {
            display : true,
            position : "top",
            text : "<?php echo $course_title; ?>",
            fontSize : 18,
            fontColor : "#111"
        },
        legend : {
            display : true,
            position : "bottom"
        },
        scales : {
            yAxes : [{
                ticks : {
                    min : 0
                }
            }]
        }
    };

    var chart = new Chart( ctx, {
        type : "bar",
        data : data,
        options : options
    });

    });
    </script>

