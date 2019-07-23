<?php 

	require('navbar.php');
	require('connection.php');
    $val = $_GET['val'];

	 $typeselecter = "SELECT DISTINCT semester FROM `course` WHERE 1";
                $semType2 = mysqli_query($conn,$typeselecter);

                $semesterArray = "";
                while($row2 = mysqli_fetch_array($semType2)){
                 
                    $semesterArray .= '<option value = "'.$row2['semester'].'">'.$row2['semester'].'</option>';
                }

        // To find out all the batches of software department

             $batchselecter = "SELECT DISTINCT std_batch FROM `student` WHERE 1";
                $batch = mysqli_query($conn,$batchselecter);

                $batchesArray = "";
                while($rowOfBatchs = mysqli_fetch_array($batch)){
                 
                   
                     $batchesArray .= '<option value = "'.$rowOfBatchs['std_batch'].'">'.$rowOfBatchs['std_batch'].'</option>';

                }

                if($val == 'progress'){
                

?>
<div class="container mt-sm-5">
<h2>Select Semester</h2>
<form action="progressRecord.php" method="GET">
    <div class="form-group">
    <label for="semesterType">Select Semester</label>
    <select name="semesterType" class="form-control">
    	<?php echo $semesterArray; ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php 
}
else if($val == 'past'){ ?>
    <div class="container mt-sm-5">
<h2>Select Semester For Past Record</h2>
<form action="pastSemesterRecord.php" method="GET">
    <div class="form-group">
    <label for="semesterType">Select Semester </label>
    <select name="semesterType" class="form-control">
        <?php echo $semesterArray; ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php
}else if($val == 'ploRecord'){

?>
 <div class="container mt-sm-5">
<h2>Select Batch</h2>
<form action="ploData.php" method="GET">
    <div class="form-group">
    <label for="batch">Batch</label>
    <select name="batch" class="form-control">
        <?php echo $batchesArray; ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php 
}else{

?>
<div class="container mt-sm-5">
<h2>Select Batch For Single PLO Record</h2>
<form action="plowiseRecord.php" method="GET">
    <div class="form-group">
    <label for="batch">Batch</label>
    <select name="batch" class="form-control">
        <?php echo $batchesArray; ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php } ?>