<link rel = 'stylesheet' type='text/css' media='screen' href='index.css' />
    <link rel="stylesheet" type="text/css" href="courses.css" /> 
<?php 

	require('connection.php');
	require('navbar.php');

	$batch = $_GET['batch'];
	
	

	$studentSelection = "SELECT * FROM student WHERE std_batch = '$batch'";
	$result = mysqli_query($conn,$studentSelection);
	echo "<div id='student'> <h1 style='text-align:center;'> Students of Batch $batch</h1> <table style='border: 1px solid blue' id='ploTable'> <tr> <th> Student Registration </th> <th> Student Name </th> <th> Check PLO's Status </th></tr>";
	while ($row = mysqli_fetch_assoc($result)) {

		//students Names	
		echo "<tr> <td>{$row['std_regno']}</td><td> {$row['std_name']} </td><td> <a href='ploRecord.php?std_batch=$batch&id=$row[std_id]'> Check Status </a></tr>";
	}

	echo '</table></div>';
	?>