<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>CIT Schedule </title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0">
		<link rel="stylesheet" href="jsstyles.css">
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="menu.js"></script></head>

	<body>
		<div>
			<header>
				<h1>CIT Schedule - <?php echo($semester); ?></h1>
			</header>
			<section id="main">
			<h3>View / Update Schedule</h3>
				<ul>
					<li><a href="index.php?action=add">Add a course section</a></li>
					<li><a href="index.php?action=listall">Show full course schedule</a></li>
					<li><a href="index.php?action=listowner">Show only my courses</a></li>
					<li><a href="index.php?action=listinstructor&lname=staff" id="instr">Show by instructor</a> Last Name: <input type="text" id="lname" name="lname"><span id="lnameErr" class="err"> </span></li>
					<li><a href="index.php?action=listrubric&rubric=COSC" id="listRubric">Show courses in a rubric: </a><input type="text" id="rubric" name="rubric" size="5" pattern="[A-z]{4}"><span id="rubricErr" class="err"> </span></li>
					<li><a href="index.php?action=listsched">Show all by schedule</a></li>
					<li><a href="index.php?action=listallx">Show full schedule with crosslists</a></li>
					<li><a href="index.php?action=listall">Change semester: </a>
						<select id="semester" value="semester">
						<option value="?" selected>Select a semester</option>
						<?php
							$result = getSemesters($dbConn);
							while ($row = mysqli_fetch_assoc($result)) {
								echo '<option value="'.$row['semCode'].'">'.$row['semester'].'</option>';
							}
						?>
						</select>
					</li>
				</ul>
				<hr>
				<h3>Administrative</h3>
				<ul>
					<li><a href="index.php?action=print">Print schedule</a></li>
					<li><a href="index.php?action=killList">Print Kill List</a></li>
					<li><a href="index.php?action=contracts">Print contracts</a></li>
					<li><a href="index.php?action=enrollment">Add/update enrollment</a></li>
					<li><a href="index.php?action=addInstr">Add Instructor</a></li>
					<li><a href="index.php?action=updateInstr">Update Instructor</a></li>
					<li><a href="index.php?action=addCourse">Add Course to Master</a></li>
					<li><a href="index.php?action=updateCourse">Update Course Master</a></li>
					<li><a href="index.php?action=semester">Add/Update semester</a></li>
					<li><a href="index.php?action=listBooks">List all books</a></li>
					<li><a href="index.php?action=createBook">Create new book</a></li>
					<li><a href="index.php?action=printBooks">Print book orders</a></li>
				</ul>

			</section>
		</div>
	</body>
</html>
