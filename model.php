<?php
	
    
	function getXlists($courseId) {
		global $dbConn, $primary, $secondary;
		$query = "select ID, courseName, section,sectionTitle,annotation from $secondary where primaryCourseId = $courseId";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getBooks($courseId) {
		global $dbConn, $semCode;
		$bookTable = "coursebooks".$semCode;
		$booksTable = "books".$semCode;
		$query = "select * from $booksTable where ISBN in (select ISBN from $bookTable where courseId=$courseId)";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getBook($isbn) {
		global $dbConn, $semCode;
		$bookTable = "books".$semCode;
		$query = "select * from $bookTable where ISBN =$isbn";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getBookList() {
		global $dbConn, $semCode;
		$bookTable = "books".$semCode;
		$query = "select ISBN, Title from $bookTable order by Title";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getBookOrders() {
		global $dbConn, $semCode, $primary, $secondary;
		$bookTable = "books".$semCode;
		$courseBookTable = "coursebooks".$semCode;
		$query="(Select courseName, section, sectionTitle, capacity, lastname, b.ISBN as IsbnNo, Title, Author, Publisher, edition, copyright, required, recommended, choice, comments, digital, newEdition from $primary c join infoinstructor i using(instructorId) join $courseBookTable cb on c.courseID = cb.courseId join $bookTable b on cb.isbn = b.ISBN where killed is null) union (Select s.courseName, s.section, s.sectionTitle, capacity,  lastname, b.ISBN as IsbnNo, Title, Author, Publisher, edition, copyright, required, recommended, choice, comments, digital, newEdition from $secondary s join $primary c on s.primaryCourseId = c.courseId join infoinstructor i using(instructorId) join $courseBookTable cb on c.courseID = cb.courseId join $bookTable b on cb.isbn = b.ISBN where s.section !=0) order by courseName, isbnNo, section";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function insertBook($isbn,$title,$author, $publisher,$edition,$copy,$req,$rec,$choice,$comments,$digital,$new) {
		global $dbConn, $semCode;
		$bookTable = "books".$semCode;
		$title=mysqli_real_escape_string($dbConn, $title);
		$author=mysqli_real_escape_string($dbConn, $author);
		$publisher=mysqli_real_escape_string($dbConn, $publisher);
		$comments=mysqli_real_escape_string($dbConn, $comments);
		$query="insert into $bookTable values('$isbn','$title','$author','$publisher','$edition','$copy',$req,$rec,$choice,'$comments',$digital,$new)";
		mysqli_query($dbConn,$query);
		if (mysqli_affected_rows($dbConn)!=1) {
   			die (mysqli_error($dbConn));
		}
		return true;
	}
	function updateBook($isbn,$title,$author, $publisher,$edition,$copy,$req,$rec,$choice,$comments,$digital,$new) {
		global $dbConn, $semCode;
		$bookTable = "books".$semCode;
		$title=mysqli_real_escape_string($dbConn, $title);
		$author=mysqli_real_escape_string($dbConn, $author);
		$publisher=mysqli_real_escape_string($dbConn, $publisher);
		$comments=mysqli_real_escape_string($dbConn, $comments);
		$query="update $bookTable set Title='$title',Author='$author',Publisher='$publisher',edition='$edition',copyright='$copy',required=$req,recommended=$rec,choice=$choice,comments='$comments',digital=$digital,newEdition=$new where ISBN='$isbn'";
		mysqli_query($dbConn,$query);
		if (mysqli_affected_rows($dbConn)!=1) {
   			die (mysqli_error($dbConn));
		}
		return true;
	}
	function deleteBook($isbn) {
		global $dbConn, $semCode;
		$courseBookTable = "coursebooks".$semCode;
		$bookTable = "books".$semCode;
		$query="Delete from $courseBookTable where isbn='$isbn'";
		$query2 = "Delete from $bookTable where ISBN='$isbn'";
		mysqli_query($dbConn,$query2);
		if (mysqli_affected_rows($dbConn)!=1) {
			die(mysqli_error($dbConn));
		}
	}
	function addCourseBook($courseId, $isbn) {
		global $dbConn, $semCode;
		$bookTable = "coursebooks".$semCode;
		$query = "insert into $bookTable values(default, $courseId, '$isbn')";
		mysqli_query($dbConn,$query);
		if (mysqli_affected_rows($dbConn)!=1) {
   			die (mysqli_error($dbConn));
		}
		return true;
	}
	function removeCourseBook($courseId, $isbn) {
		global $dbConn, $semCode;
		$bookTable = "coursebooks".$semCode;
		$query = "delete from $bookTable where courseId=$courseId and isbn='$isbn'";
		mysqli_query($dbConn,$query);
		/* if (mysqli_affected_rows($dbConn)!=1) {
   			return false;
		} */
		return true;
	}
	function getCourseByName($courseName) {
		global $dbConn, $primary;
		$query = "select courseID from $primary where courseName='$courseName' and killed is null";
		$result=mysqli_query($dbConn,$query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getAllPrimary() {
		global $dbConn, $primary, $secondary;
		$query="Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName FROM `$primary` left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId order by killed, courseName, section";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getOwnerPrimary($owner) {
		global $dbConn, $primary, $secondary;
		$query="Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName FROM `$primary` left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId where owner = '$owner' order by killed, courseName, section";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getInstructorPrimary($lname) {
        global $dbConn, $primary, $secondary;
        $query="Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName FROM `$primary` left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId where lastName = '$lname' order by killed, courseName, section";
        $result = mysqli_query($dbConn, $query);
        if (!$result) {
            die (mysqli_error($dbConn));
        }
        return $result;
    }
	function getRubricPrimary($rubric) {
		global $dbConn, $primary, $secondary;
		$query="Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName FROM `$primary` left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId where courseName like '$rubric%' order by killed, courseName, section";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getSectionsPrimary($courseName) {
		global $dbConn, $primary, $secondary;
		$query="Select courseId, courseName, section FROM $primary where courseName = '$courseName' and killed is null";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function getSchedulePrimary() {
		global $dbConn, $primary, $secondary;
		$query="Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName FROM $primary left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId where killed is null order by day, SUBSTRING(startTime, 7, 4), SUBSTRING(REPLACE(startTime,'12:','00:'), 1, 5), room,startDate, courseName, section";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return $result;
	}
	function killCourse($courseId) {
		global $dbConn, $primary, $secondary;
		$updateDate=date('Y-m-d H:i:s');
		$owner=$_COOKIE['login'];
		$query = "Select hasXlist from $primary where courseId = '$courseId'";
		$result=mysqli_query($dbConn, $query);
		$row = mysqli_fetch_assoc($result);
		if ($row['hasXlist']) {
			$query = "Update $secondary set section='kill' where primaryCourseId = '$courseId'";
			mysqli_query($dbConn, $query);
		}
		$query = "Update $primary set killed = 'Y',updatedBy='$owner', lastUpdated='$updateDate' where courseId = '$courseId'";
		echo $query;
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function restoreCourse($courseId) {
		global $dbConn, $primary, $secondary;
		$updateDate=date('Y-m-d H:i:s');
		$owner=$_COOKIE['login'];
		$query = "Update $primary set killed = NULL,updatedBy='$owner', lastUpdated='$updateDate' where courseId = '$courseId'";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function checkForCourse($courseName, $sect) {
		global $dbConn, $primary, $secondary;
		$query = "Select courseId from $primary where courseName = '$courseName' and section='$sect'";
		$result = mysqli_query($dbConn, $query);
		if (!$result) {
			die(mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)>0) {
			return true;
		} else {
			$query = "Select ID from $secondary where courseName = '$courseName' and section='$sect'";
			$result = mysqli_query($dbConn, $query);
			if (!$result) {
				die(mysqli_error($dbConn));
			}
			if (mysqli_affected_rows($dbConn)>0) {
				return true;
			} else {
				return false;
			}
		}
	}
	function insertCourse($course, $sect, $title, $instr, $sTime, $eTime, $room, $day, $sDate, $eDate, $cap, $anno, $xlist, $lab) {
		global $dbConn, $primary, $secondary;
		$updateDate=date('Y-m-d H:i:s');
		$owner=$_COOKIE['login'];
		$title=mysqli_real_escape_string($dbConn,$title);
		$anno=mysqli_real_escape_string($dbConn,$anno);
		$query = "Insert into $primary (startTime, endTime, room, day, courseName, section, sectionTitle, instructorId, startDate, endDate, capacity, annotation, hasXlist, labInfo, lastUpdated, updatedBy, owner) values('$sTime','$eTime', '$room','$day','$course','$sect','$title','$instr','$sDate','$eDate','$cap','$anno','$xlist','$lab','$updateDate','$owner','$owner')";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function insertXlist($pId, $course, $sect, $title, $anno) {
		global $dbConn, $primary, $secondary;
		$title=mysqli_real_escape_string($dbConn,$title);
		$anno=mysqli_real_escape_string($dbConn,$anno);
		$query = "Insert into $secondary (primaryCourseId, courseName, section, sectionTitle, annotation) values($pId,'$course','$sect','$title','$anno')";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function getPrimaryCourse($courseId) {
		global $dbConn, $primary, $secondary;
		$query = "Select startTime, endTime, room, day, courseName, section, sectionTitle, $primary.instructorId, firstName, lastName, startDate, endDate, capacity, annotation, hasXlist, labInfo, lastUpdated, updatedBy, owner from $primary left outer join infoinstructor on $primary.instructorId = infoinstructor.instructorId where courseId=$courseId";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
	function getCourse($courseId) {
		global $dbConn, $primary;
		$query = "Select courseName, section, sectionTitle from $primary where courseId=$courseId";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
	function getCourses() {
		global $dbConn, $primary;
		$query = "Select courseID, courseName, section, sectionTitle from $primary order by courseName";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) <1) {
			return false;
		} else {
			return $result;
		}
	}
	function getEnrollment() {
		global $dbConn, $primary, $secondary;
		$query = "Select courseId, courseName, section, sectionTitle, enrollment from $primary where killed is null order by courseName, section";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
	function updateCourse($course, $sect, $title, $instr, $sTime, $eTime, $room, $day, $sDate, $eDate, $cap, $anno, $xlist, $lab, $owner) {
		global $dbConn, $primary, $secondary;
		$updateDate=date('Y-m-d H:i:s');
		$user=$_COOKIE['login'];
		$title=mysqli_real_escape_string($dbConn,$title);
		$anno=mysqli_real_escape_string($dbConn,$anno);
		$query = "Update $primary set startTime='$sTime', endTime='$eTime', room='$room', day='$day', section='$sect', sectionTitle='$title', instructorId='$instr', startDate='$sDate', endDate='$eDate', capacity='$cap', annotation='$anno', hasXlist='$xlist', labInfo='$lab', lastUpdated='$updateDate', updatedBy='$user', owner='$owner' where courseID=$course";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function updateEnroll($course, $enroll) {
		global $dbConn, $primary;
		$query = "Update $primary set enrollment=$enroll where courseID=$course";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		return true;
	}
	function deleteSecondary($courseId) {
		global $dbConn, $primary, $secondary;
		$query = "Update $secondary set section=0 where ID = '$courseId'";
		$result=mysqli_query($dbConn, $query);
		if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function updateSecondary($courseId, $courseNum, $section, $title, $anno) {
		global $dbConn, $primary, $secondary;
		$title=mysqli_real_escape_string($dbConn,$title);
		$anno=mysqli_real_escape_string($dbConn,$anno);
		$query = "Update $secondary set courseName='$courseNum', section='$section', sectionTitle='$title',  annotation='$anno' where ID=$courseId";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
	function getPrimarySecondary() {
		global $dbConn, $primary, $secondary;
		$query = "(Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName, Null as primaryCourseId FROM $primary left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId) union (SELECT ID,  $secondary.courseName, $secondary.section, $secondary.sectionTitle, day, startTime, endTime, room,capacity,labInfo, startDate, endDate, $secondary.annotation, hasXlist, killed, $primary.instructorId, firstName, lastName, primaryCourseId from $primary inner join $secondary on $secondary.primaryCourseId = $primary.courseId left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId) order by killed, courseName, section";
		$result = mysqli_query($dbConn,$query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		
		return $result;
	}
	function getKilled() {
		global $dbConn, $primary, $secondary;
		$query = "Select courseId, courseName, section, sectionTitle, day, startTime, endTime, room, capacity, labInfo, startDate, endDate, annotation, hasXlist, killed, $primary.instructorId, firstName, lastName, Null as primaryCourseId FROM $primary left outer join infoInstructor on $primary.instructorId = infoInstructor.instructorId where killed='y' order by courseName, section";
		$result = mysqli_query($dbConn,$query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		
		return $result;
	}
	function getDates($dbConn,$semCode) {
		global $dbConn;
		if ($semCode == "?") {
			$query = "Select * from semesters where semId = (select max(semId) from semesters)";
		} else {			
			$query = "Select * from semesters where semCode = '$semCode'";
		}
		$result = mysqli_query($dbConn, $query);
		if (!$result) {
			die (mysqli_error($dbConn));
		}
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	
	function getSemesters() {
		global $dbConn;
		$query = "Select semCode, semester from semesters order by semId desc";
		$result = mysqli_query($dbConn,$query);
		if (!$result) {
			die(mysqli_error($dbConn));
		}
		return $result;
	}
	function getContractInfo() {
		global $dbConn, $primary, $secondary;
		$query = "Select courseId, courseName, section, $primary.instructorId, firstName, lastName, startDate, endDate, enrollment, hasXlist, contractResp from $primary left outer join infoinstructor on $primary.instructorId = infoinstructor.instructorId where killed is null order by contractResp, $primary.instructorId, courseName";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
function insertInstr($pId, $id3x4, $lname, $fname, $email, $altEmail, $office, $bus, $home, $mobile, $other, $supv, $resp) {
		global $dbConn;
		$query = "Insert into infoInstructor values('$pId', '$id3x4', '$lname', '$fname', '$email', '$altEmail', '$office', '$bus', '$home', '$mobile', '$other', '$supv', '$resp')";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function getInstr() {
		global $dbConn;
		$query = "Select * from infoinstructor order by lastName, firstName";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
function updateInstr($pId, $id3x4, $lname, $fname, $email, $altEmail, $office, $bus, $home, $mobile, $other, $supv, $resp) {
		global $dbConn;
		$query = "update infoInstructor set 3x4='$id3x4', lastName='$lname', firstName='$fname', email='$email', altemail='$altEmail', office='$office', businessPhone='$bus', homePhone='$home', mobilePhone='$mobile', other='$other',supervisor='$supv', contractResp='$resp' where instructorId='$pId'";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function insertCourselist($cnum, $xlist, $desc, $chrs, $resp) {
		global $dbConn;
		$query = "Insert into courselist values('$cnum', '$xlist', '$desc', '$chrs', null, null, '$resp')";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function getCourselist() {
		global $dbConn;
		$query = "Select * from courselist order by courseNum";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
function updateCourselist($cnum, $xlist, $desc, $chrs, $resp) {
		global $dbConn;
		$query = "update courselist set xLIST='$xlist', description='$desc', creditHours='$chrs', responsible='$resp' where courseNum='$cnum'";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function getSemesterList() {
		global $dbConn;
		$query = "Select * from semesters order by semId desc";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
function updateSemesters($semId, $semCode, $semester, $start, $end, $min, $max, $rate) {
		global $dbConn;
		$query = "update semesters set semCode='$semCode', semester='$semester', startDate='$start', endDate='$end', sectMin='$min', sectMax='$max', adjunctRate=$rate where semId='$semId'";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function insertSemester($semCode, $semester, $start, $end, $min, $max, $rate) {
		global $dbConn;
		$query = "Insert into semesters values(default, '$semCode', '$semester', '$start', '$end', '$min', '$max', $rate)";
		$result = mysqli_query($dbConn, $query);
    	if (!$result) {
   			die (mysqli_error($dbConn));
		}
		if (mysqli_affected_rows($dbConn)!=1) {
			return false;
		} else {
			return true;
		}
	}
function copySemester($semCode, $oldSemCode, $start, $end, $oldStart, $oldEnd) {
	global $dbConn;
	$query = "CREATE TABLE primary".$semCode." as SELECT * FROM primary".$oldSemCode;
	if (!mysqli_query($dbConn, $query)) {
		die (mysqli_error($dbConn));
	}
	$query = "ALTER TABLE primary".$semCode." modify courseID int(3) auto_increment primary key";
    mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to create primary key on primary";
	}
	$query = "Update primary".$semCode." set startDate = '$start' where startDate='$oldStart'";
	mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to update start dates";
	}
	$query = "Update primary".$semCode." set endDate = '$end' where endDate='$oldEnd'";
	mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to update end dates";
	}
	$query = "CREATE TABLE secondary".$semCode." as SELECT * FROM secondary".$oldSemCode;
	if (!mysqli_query($dbConn, $query)) {
		die (mysqli_error($dbConn));
	}
    $query = "ALTER TABLE secondary".$semCode." modify ID int(3) auto_increment primary key";
    mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to create primary key on secondary";
	}
	$query = "CREATE TABLE books".$semCode." as SELECT * FROM books".$oldSemCode;
	if (!mysqli_query($dbConn, $query)) {
		die (mysqli_error($dbConn));
	}
    $query = "ALTER TABLE books".$semCode." modify ISBN char(13) auto_increment primary key";
    mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to create primary key for books";
	}
	$query = "CREATE TABLE coursebooks".$semCode." as SELECT * FROM coursebooks".$oldSemCode;
	if (!mysqli_query($dbConn, $query)) {
		die (mysqli_error($dbConn));
	}
    $query = "ALTER TABLE coursebooks".$semCode." modify id int(11) auto_increment primary key";
    mysqli_query($dbConn,$query);
	if (mysqli_affected_rows($dbConn) <= 0) {
		echo "Unable to create primary key";
	}
	return true;
}

?>