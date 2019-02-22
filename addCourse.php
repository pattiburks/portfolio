<?php 
	$displayTitle = "Add Course";
	include 'header.php';
?>
<section id="main">
<form action="index.php" method="post">
<?php 
	if (isset($msg)) {
		echo "<p class='err'>$msg</p>";
	}
?>
<p><label>Course Number: <select name="courseNum" id="courseNum">
	<?php if (isset($courseNum)) {
		echo "<option selected>$courseNum</option>";
	} else {
		echo '<option value="">--Please select a course--</option>';
	}
  include('courseoptions.php'); ?>
</select></label> <label>Section: <input type="text" name="section" id="section" maxlength="5" size="5" required pattern="[0-9]{5}"
	<?php if(isset($sect)) {
		echo " value='$sect' ";
	}
	?>
	></label><label>Start Date: <input type="text" name="startDate" id="startDate" value="<?php 
	if (isset($courseStart)) {
		echo $courseStart;
	} else {
		echo $startDate;
	} ?>" size="10" pattern="[0-1]{1}[0-9]{1}/[0-9]{2}/[0-9]{4}"></label> 
<label>End Date: <input type="text" name="endDate" id="endDate" value="<?php 
	if (isset($courseEnd)) {
		echo $courseEnd;
	} else {
		echo $endDate;
	} ?>" size="10" pattern="[0-1]{1}[0-9]{1}/[0-9]{2}/[0-9]{4}"></label><br>
<label>Course Title: <input type="text" name="courseTitle" id="courseTitle" size="50" required <?php if(isset($title)) {
		echo " value='$title' ";
	}
	?>></label>
</p><p>
<label>Days: <select id="days" name="days">
	
<?php 
if (isset($days)) {
		echo "<option selected>$days</option>";
	} 
include('dayoptions.php') ?>
</select> </label> 
<label>Start-time: <input type="text" name="startTime" id="startTime" size="3" pattern="[0-9]{1,2}[:][0-9]{2}" placeholder="hh:mm" <?php if(isset($startTime)) {
		echo " value='$startTime' ";
	}
	?> > <select id="startAmpm" name="startAmpm">
	<?php 
	if (isset($startAmpm)) {
		echo "<option selected>$startAmpm</option>";
	} 
	?><option></option><option>a.m.</option><option>p.m.</option></select></label> <label>End-time: <input type="text" name="endTime" id="endTime" size="3" pattern="[0-9]{1,2}[:][0-9]{2}" placeholder="hh:mm" <?php if(isset($endTime)) {
		echo " value='$endTime' ";
	}
	?> > <select id="endAmpm" name="endAmpm"><?php 
	if (isset($endAmpm)) {
		echo "<option selected>$endAmpm</option>";
	} 
	?><option></option><option>a.m.</option><option>p.m.</option></select></label> <label>Room: <select id="room" name="room">
	<?php 
		if (isset($room)) {
		echo "<option selected>$room</option>";
	} 
	include('roomoptions.php'); ?>
</select> <label>Capacity: <input type="text" id="cap" name="cap" size="3" required <?php if(isset($cap)) {
		echo " value='$cap' ";
	} else {
		echo " value='20' ";
	}
	?>></label
</p>
<p><label>Lab Day/Time: <input type="text" name="lab" id="lab" <?php if(isset($lab)) {
		echo " value='$lab' ";
	} else {
		echo " value='LAB'";
	}
	?>></label> <label>Lab Room: <select id="labroom" name="labroom"> <?php if (isset($labroom)) {
		echo "<option selected>$labroom</option>";
	} 
	include('lablist.php') ?></select></label> <label>Instructor: <select id="instructor" name="instructor">
<?php  
if (isset($instructor)) {
		echo "<option selected value='$instructor'>$instructorName</option>";
	} else {
		echo '<option value="null">--Please select an instructor--</option>';
	}
include('instructoroptions.php'); ?></select></label><input type="hidden" name="instructorName" id="instructorName" value="<?php 
if (isset($instructorName)) {
	echo $instructorName;
} ?>"></p>
<p><label>Annotation: <textarea cols="100" rows="2" id="annotation" name="annotation"><?php if (isset($annotation)) { echo $annotation; } ?></textarea></label>
</p>
<h3>Cross Listed Courses: <input type="checkbox" value="y" name="xlist" id="xlist" <?php if (isset($xlist) && ($xlist)) {
		echo " checked "; 
	} ?>></h3><p id="note">(Course Title and Annotation will default to main course title and annotation if omitted)</p>
<p><label>Course Number: <select name="xNum1" id="xNum1">
<?php if (isset($xcourse1)) {
		echo "<option selected>$xcourse1</option>";
	} else {
		echo '<option value="">--Please select a course--</option>';
	}
  include('courseoptions.php'); ?>
</select></label> <label>Section: <input type="text" name="xsect1" id="xsect1" maxlength="5" size="5" pattern="[0-9]{5}"
	<?php if(isset($xsect1)) {
		echo " value='$xsect1' ";
	}
	?>
	></label>
<label>Title: <input type="text" name="xTitle1" id="xTitle1" size="50"
	<?php if(isset($xtitle1)) {
		echo " value='$xtitle1' ";
	}
	?>
	></label>
<br><label>Annotation: <textarea cols="100" rows="2" id="xanno1" name="xanno1"><?php if (isset($xanno1)) { echo $xanno1; } ?></textarea></label>
</p>

<p><label>Course Number: <select name="xNum2" id="xNum2">
<?php if (isset($xcourse2)) {
		echo "<option selected>$xcourse2</option>";
	} else {
		echo '<option value="">--Please select a course--</option>';
	}
  include('courseoptions.php'); ?>
</select></label> <label>Section: <input type="text" name="xsect2" id="xsect2" maxlength="5" size="5" pattern="[0-9]{5}"
	<?php if(isset($xsect2)) {
		echo " value='$xsect2' ";
	}
	?>
	></label>
<label>Title: <input type="text" name="xTitle2" id="xTitle2" size="50"
	<?php if(isset($xtitle2)) {
		echo " value='$xtitle2' ";
	}
	?>
	></label>
<br><label>Annotation: <textarea cols="100" rows="2"  id="xanno2" name="xanno2"><?php if (isset($xanno2)) { echo $xanno2; } ?></textarea></label>
</p>

<p><label>Course Number: <select name="xNum3" id="xNum3">
<?php  
	if (isset($xcourse3)) {
		echo "<option selected>$xcourse3</option>";
	} else {
		echo '<option value="">--Please select a course--</option>';
	}
	include('courseoptions.php'); ?>
</select></label> <label>Section: <input type="text" name="xsect3" id="xsect3" maxlength="5" size="5" pattern="[0-9]{5}"
	<?php if(isset($xsect3)) {
		echo " value='$xsect3' ";
	}
	?>
	></label>
<label>Title: <input type="text" name="xTitle3" id="xTitle3" size="50"
	<?php if(isset($xtitle3)) {
		echo " value='$xtitle3' ";
	}
	?>
	></label>
<br><label>Annotation: <textarea cols="100" rows="2"   id="xanno3" name="xanno3"><?php if (isset($xanno3)) { echo $xanno3; } ?></textarea></label>
</p>

<p><label>Course Number: <select name="xNum4" id="xNum4">
<?php  
	if (isset($xcourse4)) {
		echo "<option selected>$xcourse4</option>";
	} else {
		echo '<option value="">--Please select a course--</option>';
	}
	include('courseoptions.php'); ?>
</select></label> <label>Section: <input type="text" name="xsect4" id="xsect4" maxlength="5" size="5" pattern="[0-9]{5}"
	<?php if(isset($xsect4)) {
		echo " value='$xsect4' ";
	}
	?>
	></label>
<label>Title: <input type="text" name="xTitle4" id="xTitle4" size="50"
	<?php if(isset($xtitle4)) {
		echo " value='$xtitle4' ";
	}
	?>
	></label>
<br><label>Annotation: <textarea cols="100" rows="2"  id="xanno4" name="xanno4"><?php if (isset($xanno4)) { echo $xanno4; } ?></textarea></label>
</p>
<input type="hidden" name="action" value="insert">
<p><input type="submit" value="add course"></p>
</form>
</section>
</body>
</html>
