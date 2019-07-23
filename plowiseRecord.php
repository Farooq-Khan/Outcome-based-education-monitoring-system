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
	$batch = $_GET['batch'];
	require 'navbar.php';

	$selectAllPlo = "Select * from plo";
	$allPlo = mysqli_query($conn,$selectAllPlo);

	echo '<div id="student"> <h1 style= text-align:center;> Program Learning Outcomes </h1> <table  style="border: 1px solid blue" id="ploTable"><tr>
	<th>PLO Id </th>
	<th>PLO No </th>
	<th>Description </th>
	<th> Plo Record </th>
	</tr>';

	while($row = mysqli_fetch_assoc($allPlo)){
		echo "<tr>
			<td> $row[plo_id] </td>
			<td> $row[plo_no] </td>
			<td> $row[plo_desc] </td>
			<td><a href='singlePloRecord.php?plo=$row[plo_id]&batch=$batch'>Show Record </a></td>
			</tr>";
	}





?>