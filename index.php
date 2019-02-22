<?php
ob_start();
require_once ('model.php');
dbConnect();
require_once ('function.php');
authUser();

if (isset($_POST['action'])) {
	$action = $_POST['action'];
} elseif (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "menu";
}
switch ($action) {
	case 'menu' :
		require ("menu.php");
		break;
	case 'listall' :
		$display = 'listall';
		$displayTitle = "Full Schedule";
		$result = getAllPrimary();
		require ("listCourses.php");
		break;
	case 'listallx':
		$display = 'listallx';
		$displayTitle = "Full Schedule including Xlists";
		$result=getPrimarySecondary();
		require ("listCourses.php");
		break;
	case 'print':
		$display = 'print';
		$displayTitle = "Print Schedule";
		$result=getPrimarySecondary();
		require ("printCourses.php");
		break;
	case 'killList':
		$display = 'killList';
		$displayTitle = "Print Kill List";
		$result=getKilled();
		require ("printKillList.php");
		break;
	case 'contracts':
		$display = 'contracts';
		$displayTitle = "Contracts";
		$result=getContractInfo();
		require ("printContracts.php");
		break;
	case 'enrollment':
		$display = 'enrollment';
		$displayTitle = "Enrollment";
		require ("enrollment.php");
		break;
	case 'listsched' :
		$display = 'listsched';
		$displayTitle = "Full Schedule by Schedule";
		$result = getSchedulePrimary();
		require ("listSched.php");
		break;
	case 'listowner' :
		$display = 'listowner';
		$owner = $_COOKIE['login'];
		$displayTitle = "Schedule for $owner";
		$result = getOwnerPrimary($owner);
		require ("listCourses.php");
		break;
   case 'listinstructor' :
        $display = 'listinstructor';
        $lastname = $_GET['lname'];
        $displayTitle = "Schedule for $lastname";
        $result = getInstructorPrimary($lastname);
        require ("listCourses.php");
        break;
	case 'listrubric' :
		$rubric = $_GET['rubric'];
		$display = 'listrubric&rubric=' . $rubric;
		$displayTitle = "Schedule for rubric $rubric";
		$result = getRubricPrimary($rubric);
		require ("listCourses.php");
		break;
	case 'addInstr' :
		require ("addInstr.php");
		break;
	case 'updateInstr' :
		$display = 'updateInstr';
		$displayTitle = "Instructor Update";
		$result = getInstr();
		require ("updateInstr.php");
		break;
	case 'addCourse' :
		require ("addCourselist.php");
		break;
	case 'updateCourse' :
		$display = 'updateCourse';
		$displayTitle = "Course Master Update";
		$result = getCourselist();
		require ("updateCourselist.php");
		break;
	case 'semester' :
		$display = 'semester';
		$displayTitle = "Add / Update Semester";
		$result = getSemesters();
		require ("updateSemesters.php");
		break;
	case 'removeBook':
		$displayTitle = "Edit Course";
		$courseId=$_POST['courseId'];
		$isbn = $_POST['ISBN'];
		if (!removeCourseBook($courseId, $isbn)) {
			echo "Unable to remove book";
		}
		$result = getPrimaryCourse($courseId);
		$row = mysqli_fetch_assoc($result);
		//display edit page
		require ('editCourse.php');
		break;	
	case 'addBook':
		$displayTitle = "Edit Course";
		$courseId=$_POST['courseId'];
		$isbn = $_POST['isbn'];
		if (!addCourseBook($courseId, $isbn)) {
			echo "Unable to add book";
		}
		$result = getPrimaryCourse($courseId);
		$row = mysqli_fetch_assoc($result);
		//display edit page
		require ('editCourse.php');
		break;	
	case 'createBook':
		require('createBook.php');
		break;
	case 'insertBook':
		require('insertBook.php');
		break;
	case 'updateBook':
		require('updateBook.php');
		break;
	case 'listBooks':
		require('listBooks.php');
		break;
	case 'deleteBook':
		require('deleteBook.php');
		break;
	case 'printBooks':
		$result=getBookOrders();
		require('printBookOrders.php');
		break;
	case 'changeBook':
		$displayTitle = "Change Book";
		if (isset($_POST['courseId'])) {
			$courseId=$_POST['courseId'];
		}
		$isbn = $_POST['ISBN'];
		$result = getBook($isbn);
		$row = mysqli_fetch_assoc($result);
		$title=$row['Title'];
		$author=$row['Author'];
		$pub=$row['Publisher'];
		$edition=$row['edition'];
		$copyright=$row['copyright'];
		$req=$row['required'];
		$rec=$row['recommended'];
		$choice=$row['choice'];
		$comments=$row['comments'];
		$digital=$row['digital'];
		$new=$row['newEdition'];
		//display edit page
		require ('changeBook.php');
		break;	
	case 'add' :
		require ("addCourse.php");
		break;
	case 'delete' :
		if (killCourse($_GET['courseId'])) {
			$msg = "Course deleted";
		} else {
			$msg = "Unable to delete course";
		}

		if ($_GET['display'] == 'listrubric') {
			$display = $_GET['display'] . "&rubric=" . $_GET['rubric'] . "&msg=$msg";
		} else {
			$display = $_GET['display'] . "&msg=$msg";
		}
		header('location:index.php?action=' . $display);
		exit();
	case 'restore' :
		if (restoreCourse($_GET['courseId'])) {
			$msg = "Course restored";
		} else {
			$msg = "Unable to restore course";
		}
		if ($_GET['display'] == 'listrubric') {
			$display = $_GET['display'] . "&rubric=" . $_GET['rubric'] . "&msg=$msg";
		} else {
			$display = $_GET['display'] . "&msg=$msg";
		}
		header('location:index.php?action=' . $display);
		exit();
	case 'insert' :
		$valid = true;
		$msg = "";
		//get fields from form
		$courseNum = $_POST['courseNum'];
		$sect = $_POST['section'];
		if (!validateSection($sect)) {
			$msg .= "Invalid section number<br>";
			$valid = false;
		}
		//verify doesn't already exist
		if (checkForCourse($courseNum, $sect)) {
			$msg .= "This course name and section already exist. Select a different section number<br>";
			$valid = false;
		}
		$courseStart = $_POST['startDate'];
		$courseEnd = $_POST['endDate'];
		if (!validateCourseDates($courseStart, $courseEnd)) {
			$msg .= "Invalid course dates.<br>";
			$valid = false;
		}
		$title = $_POST['courseTitle'];
		$days = $_POST['days'];
		$startTime = $_POST['startTime'];
		$startAmpm = $_POST['startAmpm'];
		$endTime = $_POST['endTime'];
		$endAmpm = $_POST['endAmpm'];
		$room = $_POST['room'];
		$cap = $_POST['cap'];
		$lab = $_POST['lab'];
		$labroom = $_POST['labroom'];
		$instructor = $_POST['instructor'];
		$instructorName = $_POST['instructorName'];
		$annotation = $_POST['annotation'];
		if (isset($_POST['xlist'])) {
			if (isset($_POST['xNum1']) AND ($_POST['xNum1'] != "")) {
				$xcourse1 = $_POST['xNum1'];
				$xsect1 = $_POST['xsect1'];
				$xtitle1 = $_POST['xTitle1'];
				$xanno1 = $_POST['xanno1'];
				$xlist = true;
				if (checkForCourse($xcourse1, $xsect1)) {
					$msg .= "Cross list $xcourse1 $xsect1: course name and section already exist. Select a different section number<br>";
					$valid = false;
				}
			} else {
				$msg .= "Please select crosslisted course";
				$valid = false;
			}
			if (isset($_POST['xNum2']) AND ($_POST['xNum2'] != "")) {
				$xcourse2 = $_POST['xNum2'];
				$xsect2 = $_POST['xsect2'];
				$xtitle2 = $_POST['xTitle2'];
				$xanno2 = $_POST['xanno2'];
				if (checkForCourse($xcourse2, $xsect2)) {
					$msg .= "Cross list $xcourse2 $xsect2: course name and section already exist. Select a different section number<br>";
					$valid = false;
				}
			}
			if (isset($_POST['xNum3']) AND ($_POST['xNum3'] != "")) {
				$xcourse3 = $_POST['xNum3'];
				$xsect3 = $_POST['xsect3'];
				$xtitle3 = $_POST['xTitle3'];
				$xanno3 = $_POST['xanno3'];
				if (checkForCourse($xcourse3, $xsect3)) {
					$msg .= "Cross list $xcourse3 $xsect3: course name and section already exist. Select a different section number<br>";
					$valid = false;
				}
			}
			if (isset($_POST['xNum4']) AND ($_POST['xNum4'] != "")) {
				$xcourse4 = $_POST['xNum4'];
				$xsect4 = $_POST['xsect4'];
				$xtitle4 = $_POST['xTitle4'];
				$xanno4 = $_POST['xanno4'];
				if (checkForCourse($xcourse4, $xsect4)) {
					$msg .= "Cross list $xcourse4 $xsect4: course name and section already exist. Select a different section number<br>";
					$valid = false;
				}
			}
		} else {
			$xlist = "";
		}
		if ($valid) {
			//insert
			$sTime = $startTime . ' ' . $startAmpm;
			$eTime = $endTime . ' ' . $endAmpm;
			$labInfo = $lab . " " . $labroom;
			if (insertCourse($courseNum, $sect, $title, $instructor, $sTime, $eTime, $room, $days, $courseStart, $courseEnd, $cap, $annotation, $xlist, $labInfo)) {
				$msg = "$courseNum $sect inserted";
				if (isset($_POST['xlist'])) {
					$pCourseId = mysqli_insert_id($dbConn);
					if (checkForCourse($xcourse1, $xsect1)) {
						$msg = "<br>Course $xcourse1 $xsect1 already exists, please select another section number.";
					} else {
						if (insertXlist($pCourseId, $xcourse1, $xsect1, $xtitle1, $xanno1)) {
							$msg .= "<br>Crosslist $xcourse1 $xsect1 added";
						} else {
							$msg .= "<br>Unable to insert crosslist for $xcourse1 $xsect1";
						}
					}
					if (isset($_POST['xNum2']) AND ($_POST['xNum2'] != "")) {
						$xcourse2 = $_POST['xNum2'];
						$xsect2 = $_POST['xsect2'];
						$xtitle2 = $_POST['xTitle2'];
						$xanno2 = $_POST['xanno2'];
						if (checkForCourse($xcourse2, $xsect2)) {
							$msg = "<br>Course $xcourse2 $xsect2 already exists, please select another section number.";
						} else {
							if (insertXlist($pCourseId, $xcourse2, $xsect2, $xtitle2, $xanno2)) {
								$msg .= "<br>Crosslist $xcourse2 $xsect2 added";
							} else {

								$msg .= "<br>Unable to insert crosslist for $xcourse2 $xsect2";
							}
						}
					}
					if (isset($_POST['xNum3']) AND ($_POST['xNum3'] != "")) {
						$xcourse3 = $_POST['xNum3'];
						$xsect3 = $_POST['xsect3'];
						$xtitle3 = $_POST['xTitle3'];
						$xanno3 = $_POST['xanno3'];
						if (checkForCourse($xcourse3, $xsect3)) {
							$msg = "<br>Course $xcourse3 $xsect3 already exists, please select another section number.";
						} else {
							if (insertXlist($pCourseId, $xcourse3, $xsect3, $xtitle3, $xanno3)) {
								$msg .= "<br>Crosslist $xcourse3 $xsect3 added";
							} else {
								$msg .= "<br>Unable to insert crosslist for $xcourse3 $xsect3";
							}
						}
					}
					if (isset($_POST['xNum4']) AND ($_POST['xNum4'] != "")) {
						$xcourse4 = $_POST['xNum4'];
						$xsect4 = $_POST['xsect4'];
						$xtitle4 = $_POST['xTitle4'];
						$xanno4 = $_POST['xanno4'];
						if (checkForCourse($xcourse4, $xsect4)) {
							$msg = "<br>Course $xcourse4 $xsect4 already exists, please select another section number.";
						} else {
							if (insertXlist($pCourseId, $xcourse4, $xsect4, $xtitle4, $xanno4)) {
								$msg .= "<br>Crosslist $xcourse4 $xsect4 added";
							} else {
								$msg .= "<br>Unable to insert crosslist for $xcourse4 $xsect4";
							}
						}
					}
				}
				unset($courseNum, $sect, $courseStart, $courseEnd, $title, $days, $startTime, $startAmpm, $endTime, $endAmpm, $room, $cap, $lab, $labroom, $instructor, $annotation, $xlist);
			} else {
				$msg = "Unable to insert course";
			}
		}
		//reload add
		require ("addCourse.php");
		break;
	case 'edit' :
		//retrieve record from database
		$courseId = $_GET['courseId'];
		$result = getPrimaryCourse($courseId);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			//display edit page
			require ('editCourse.php');
		} else {
			$msg = "Unable to retrieve course";
			if ($_GET['display'] == 'listrubric') {
				$display = $_GET['display'] . "&rubric=" . $_GET['rubric'] . "&msg=$msg";
			} else {
				$display = $_GET['display'] . "&msg=$msg";
			}
			header('location:index.php?action=' . $display);
			exit();
		}
		break;
	case 'update' :
		//validate
		$valid = true;
		$msg = "";
		//get fields from form
		$courseId = $_POST['courseId'];
		$courseNum = $_POST['courseNum'];
		$sect = $_POST['section'];
		if (!validateSection($sect)) {
			$msg .= "Invalid section number<br>";
			$valid = false;
		}
		$courseStart = $_POST['startDate'];
		$courseEnd = $_POST['endDate'];
		if (!validateCourseDates($courseStart, $courseEnd)) {
			$msg .= "Invalid course dates.<br>";
			$valid = false;
		}
		$title = $_POST['courseTitle'];
		$days = $_POST['days'];
		$startTime = $_POST['startTime'];
		$startAmpm = $_POST['startAmpm'];
		$endTime = $_POST['endTime'];
		$endAmpm = $_POST['endAmpm'];
		$room = $_POST['room'];
		$cap = $_POST['cap'];
		$lab = $_POST['lab'];
		$instructor = $_POST['instructor'];
		if (isset($_POST['instructorName'])) {
			$instructorName = $_POST['instructorName'];
		}
		$owner = $_POST['owner'];
		$annotation = $_POST['annotation'];
		if (isset($_POST['xlist'])) {
			$xlist = true;
		} else {
			$xlist = "";
		}
		if ($valid) {
			//update record
			$sTime = $startTime . ' ' . $startAmpm;
			$eTime = $endTime . ' ' . $endAmpm;
			if (updateCourse($courseId, $sect, $title, $instructor, $sTime, $eTime, $room, $days, $courseStart, $courseEnd, $cap, $annotation, $xlist, $lab, $owner)) {
				$msg = "$courseNum $sect updated";
			} else {
				$msg = "Unable to update course";
			}
		}
		//retrieve record from database
		$result = getPrimaryCourse($courseId);
		$row = mysqli_fetch_assoc($result);
		//display edit page
		require ('editCourse.php');
		break;
	case 'updateXlist' :
		$primaryId = $_POST['primary'];
		$courseId = $_POST['courseId'];
		$courseNum = $_POST['courseNum'];
		$section = $_POST['section'];
		$title = $_POST['title'];
		$anno = $_POST['annotation'];
		if (isset($_POST['delxlist'])) {
			if (deleteSecondary($courseId)) {
				$msg = "$courseNum $section crosslist deleted.";
			} else {
				$msg = "Unable to delete crosslist: $courseNum $section";
			}
		} else {
			if (updateSecondary($courseId, $courseNum, $section, $title, $anno)) {
				$msg = "$courseNum $section crosslist updated.";
			} else {
				$msg = "Unable to update crosslist: $courseNum $section";
			}
		}
		//retrieve record from database
		$result = getPrimaryCourse($primaryId);
		$row = mysqli_fetch_assoc($result);
		$courseId = $primaryId;
		//display edit page
		require ('editCourse.php');
		break;
	case 'addxlist' :
		$pCourseId = $_POST['primary'];
		$xcourse = $_POST['courseName'];
		$xsect = $_POST['section'];
		$xtitle = $_POST['title'];
		$xanno = $_POST['annotation'];
		if (checkForCourse($xcourse, $xsect)) {
			$msg = "<br>Course $xcourse $xsect already exists, please select another section number.";
		} else {
			if (insertXlist($pCourseId, $xcourse, $xsect, $xtitle, $xanno)) {
				$msg = "<br>Crosslist $xcourse $xsect added";
			} else {
				$msg = "<br>Unable to insert crosslist for $xcourse $xsect";
			}
		}
		//retrieve record from database
		$result = getPrimaryCourse($pCourseId);
		$row = mysqli_fetch_assoc($result);
		$courseId = $pCourseId;
		//display edit page
		require ('editCourse.php');
		break;
	case 'copy' :
		//retrieve record from database
		$courseId = $_GET['courseId'];
		$result = getPrimaryCourse($courseId);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			$courseNum = $row['courseName'];
			$sect = $row['section'];
			$title = $row['sectionTitle'];
			$courseStart = trim(substr($row['startDate'], 0, 10));
			$courseEnd = trim(substr($row['endDate'], 0, 10));
			$days = $row['day'];
			$startAmpm = substr($row['startTime'], 5);
			$endAmpm = substr($row['endTime'], 5);
			$startTime = trim(substr($row['startTime'], 0, 5));
			$endTime = trim(substr($row['endTime'], 0, 5));
			$room = $row['room'];
			$cap = $row['capacity'];
			$instructor = $row['instructorId'];
			$instructorName = $row['firstName'] . ' ' . $row['lastName'];
			$lab = $row['labInfo'];
			$xlist = $row['hasXlist'];
			$annotation = $row['annotation'];
			if ($xlist) {
				$xlists = getXlists($courseId);
				$xcourse = mysqli_fetch_assoc($xlists);
				$xcourse1 = $xcourse['courseName'];
				$xsect1 = $xcourse['section'];
				$xtitle1 = $xcourse['sectionTitle'];
				$xanno1 = $xcourse['annotation'];
				if ($xcourse = mysqli_fetch_assoc($xlists)) {
					$xcourse2 = $xcourse['courseName'];
					$xsect2 = $xcourse['section'];
					$xtitle2 = $xcourse['sectionTitle'];
					$xanno2 = $xcourse['annotation'];
					if ($xcourse = mysqli_fetch_assoc($xlists)) {
						$xcourse3 = $xcourse['courseName'];
						$xsect3 = $xcourse['section'];
						$xtitle3 = $xcourse['sectionTitle'];
						$xanno3 = $xcourse['annotation'];
						if ($xcourse = mysqli_fetch_assoc($xlists)) {
							$xcourse4 = $xcourse['courseName'];
							$xsect4 = $xcourse['section'];
							$xtitle4 = $xcourse['sectionTitle'];
							$xanno4 = $xcourse['annotation'];
						}
					}
				}
			}
			//display add page
			require ('addCourse.php');
		} else {
			$msg = "Unable to retrieve course";
			if ($_GET['display'] == 'listrubric') {
				$display = $_GET['display'] . "&rubric=" . $_GET['rubric'] . "&msg=$msg";
			} else {
				$display = $_GET['display'] . "&msg=$msg";
			}
			header('location:index.php?action=' . $display);
			exit();
		}
		//display copy page

		break;

	default :
		require ("menu.php");
		break;
}
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3164246-2', 'dcccd.edu');
  ga('send', 'pageview');

</script>
