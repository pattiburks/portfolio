<?php 
	$displayTitle = "Edit Course";
	include 'header.php';
?>
<section id="main">
<form action="index.php" method="post">
<?php 
	if (isset($msg)) {
		echo "<p class='err'>$msg</p>";
	}
?>
<p>Course Number: <input type="text" name="courseNum" value="<?php 
	echo $row['courseName'];
	?>" readonly>
	<label>Section: <input type="text" name="section" id="section" maxlength="5" size="5" required pattern="[0-9]{5}" value="<?php echo $row['section'];	?>" ></label>
	 <label>Course Title: <input type="text" name="courseTitle" id="courseTitle" size="50" required value="<?php echo $row['sectionTitle'];
	?>" ></label> <label>Start Date: <input type="text" name="startDate" id="startDate" value="<?php echo trim(substr($row['startDate'],0,10));
	 ?>" size="10" pattern="[0-1]{1}[0-9]{1}/[0-9]{2}/[0-9]{4}"></label> 
<label>End Date: <input type="text" name="endDate" id="endDate" value="<?php 
			echo trim(substr($row['endDate'],0,10));
	 ?>" size="10" pattern="[0-1]{1}[0-9]{1}/[0-9]{2}/[0-9]{4}"></label>

</p><p>
<label>Days: <select id="days" name="days">
	<option selected>
<?php 
	echo $row['day'];
	include('dayoptions.php') ?>
	</option>
</select> </label> 
<label>Start-time: <input type="text" name="startTime" id="startTime" size="3" pattern="[0-9]{1,2}[:][0-9]{2}" placeholder="hh:mm" value="<?php 
	echo trim(substr($row['startTime'],0,5));
	?>" > <select id="startAmpm" name="startAmpm">
		<option selected>
	<?php 		
			echo substr($row['startTime'],5);
	?></option><option>a.m.</option><option>p.m.</option></select></label> <label>End-time: <input type="text" name="endTime" id="endTime" size="3" pattern="[0-9]{1,2}[:][0-9]{2}" placeholder="hh:mm" value="<?php 
	echo trim(substr($row['endTime'],0,5));
	?>" > <select id="endAmpm" name="endAmpm">
		<option selected>
	<?php 
		echo substr($row['endTime'],5);
	?></option><option>a.m.</option><option>p.m.</option></select></label> <label>Room: <select id="room" name="room">
			<option selected> <?php echo $row['room']; 
			echo "</option>";
	include('roomoptions.php'); ?>
</select> <label>Capacity: <input type="text" id="cap" name="cap" size="3" required value="<?php 
		echo $row['capacity'];
	?> " ></label> <label>Instructor: <select id="instructor" name="instructor">
<?php  
		echo "<option selected value='".$row['instructorId']."'>".$row['firstName'].' '.$row['lastName']."</option>";
include('instructoroptions.php'); ?></select></label>
</p>
<p><label>Lab: <input type="text" name="lab" id="lab" value="<?php 
		echo $row['labInfo'];
		?>"></label> Crosslisted courses? <input type="checkbox" value="y" name="xlist" id="xlist" <?php if ($row['hasXlist']) {
		echo " checked "; 
		$xlist=true;
} else {$xlist=false;} ?>>
		</p>
<p><label>Annotation: <textarea cols="120" rows="2" id="annotation" name="annotation"><?php echo $row['annotation']; 
	?></textarea></label>
</p>
<p><input type="hidden" name="action" value="update">
<input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
<input type="submit" value="update course"> <label>Owner <input type="text" id="owner" name="owner" value="<?php echo $row['owner']; ?>"</label> Last Update: <?php echo $row['lastUpdated']." by ".$row['updatedBy']; ?></p>
</form>
<h3>Cross Listed Courses: </h3>
<table>
<?php

if ($xlist) {
	$xlists = getXlists($courseId);

	while ($xcourse = mysqli_fetch_assoc($xlists)) {
		?>
		<tr><td>
		<form method="post" action="index.php">
			<input type="hidden" name="primary" value="<?php echo $courseId ?>">
			<input type="hidden" name="action" value="updateXlist">
		<p>Course Number: <input type="text" name="courseNum" value="<?php 
	echo $xcourse['courseName'];
	?>" readonly 
	<?php
				if ($xcourse['section'] == 0) {
					echo "class='killed'";
				} 
				?>
>
		<input type="hidden" name="courseId" value="<?php	echo $xcourse['ID']; ?>">
		<label>Section: <input 
			<?php
				if ($xcourse['section'] == 0) {
					echo " class='killed' ";
				} 
				?>
			type="text" name="section" id="section" maxlength="5" size="5" required pattern="[0-9]{5}" value="<?php echo $xcourse['section']; ?>" ></label>
	<label>Course Title: <input type="text" name="title" id="title" size="50" value="<?php echo $xcourse['sectionTitle'];
	?>" ></label> <br>
	<label>Annotation: <textarea cols="100" rows="2" id="annotation" name="annotation"><?php echo $xcourse['annotation']; 
	?></textarea></label></td><td>
	<input type="submit" value="update" name="editxlist"> <input type="submit" value="delete" name="delxlist"></p>
	</form></td></tr>
	<tr class='$class'><td class='sep' colspan='2'>&nbsp;</td></tr>
	<?php } 
	} ?>
	<tr><td>
	<form method="post" action="index.php">
			<input type="hidden" name="primary" value="<?php echo $courseId ?>">
			<input type="hidden" name="action" value="addxlist">
	<p><label>Course: <select name="courseName" id="courseName">
		<option value="">--Please select a course--</option>
<?php  
	include('courseoptions.php'); ?>
</select></label>
		<label>Section: <input type="text" name="section" id="section" maxlength="5" size="5" required pattern="[0-9]{5}"></label>
	<label>Course Title: <input type="text" name="title" id="title" size="50"></label> <br>
	<label>Annotation: <textarea cols="100" rows="2" id="annotation" name="annotation"></textarea></td><td>
	<input type="submit" value="add">
	</form></td></tr>				
</table>
<h3>Books: </h3>
<table id="booktable">
	<tr><th>ISBN</th><th>Title</th><th>Author</th><th>Publisher</th><th>Edition</th><th>Type</th><th></th></tr>
<?php
	$books = getBooks($courseId);

	while ($book = mysqli_fetch_assoc($books)) {
		?>
		<tr><td><?php 
	echo $book['ISBN'];
	?>
	</td><td><?php echo $book['Title']; ?> </td>
	<td><?php echo $book['Author']; ?></td>
	<td><?php echo $book['Publisher']; ?></td>	
	<td><?php echo $book['edition']; ?></td>	
	<td><?php 
		if ($book['required']) {
			echo "required";
		}
		if ($book['recommended']) {
			echo "recommended";
		}
		if ($book['choice']) {
			echo "choice";
		}
		?></td>	
		<td><form action="index.php" method="post">
			<input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
			<input type="hidden" name="ISBN" value="<?php echo $book['ISBN']; ?>">
			<input type="hidden" name="action" value="changeBook">
			<input type="submit" value="Update book">
		</form><form action="index.php" method="post">
			<input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
			<input type="hidden" name="ISBN" value="<?php echo $book['ISBN']; ?>">
			<input type="hidden" name="action" value="removeBook">
			<input type="submit" value="Remove book">
		</form></td>	</tr>
		<?php } ?>
</table>
<p><form action="index.php" method="post">
	<input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
			<select name="isbn">
				<option value="">*** Please select a Book ***</option>
				<?php
				$booklist=getBookList();
				while ($list = mysqli_fetch_assoc($booklist)) {
					echo "<option value='".$list['ISBN']."'>".$list['Title'].' '.$list['ISBN']."</option>";
				}
				?>
			</select>
			<input type="hidden" name="action" value="addBook">
			<input type="submit" value="Select book">
</form></p>
<p><form action="index.php" method="post">
	<input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
			<input type="hidden" name="action" value="createBook">
			<input type="submit" value="Create new book">
</form></p>
</section>
</body>
</html>
