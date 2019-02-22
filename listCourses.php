<?php 
	include 'header.php';
?>
			<section id="main">
				<table>
					<tr>
						<th>Course</th><th>Meeting Info</th><th>Instructor</th><th>Dates</th><th>&nbsp;</th>
					</tr>
					<?php
					$class="odd";
					while ($row = mysqli_fetch_assoc($result)) {
						if ($class=="odd") {
							$class="even";
						} else {
							$class="odd";
						}
						echo("<tr class='$class'>");
						if (($row['killed'] == 'Y') or ($row['section']==0)) {
							echo "<td class='killed'>Killed: ";
						} else {
							echo "<td>";
						}
						echo($row['courseName']." ".$row['section']."<br>".$row['sectionTitle']);
						echo("</td><td>");
						echo($row['day']." ".$row['startTime']." - ".$row['endTime']." ".$row['room']." Cap: ".$row['capacity']);
						echo("<br>".$row['labInfo']);
						echo("</td><td>");
						echo($row['instructorId']."<br>".$row['firstName']." ".$row['lastName']);
						echo("</td><td>");
						$startDate = substr($row['startDate'],0,10);
						$endDate = substr($row['endDate'],0,10);
						echo($startDate." - ".$endDate);
						echo("</td>");
                        if (isset($row['primaryCourseId'])) {
                            $editId = $row['primaryCourseId'];
                        } else {
                            $editId = $row['courseId'];
                        }
						if ($row['killed']=="Y") { ?>
							<td><a href="index.php?action=restore&courseId=<?php echo $editId."&display=$display"; ?>">restore</a>
						<?php 
							} else {	?>
							<td><a href="index.php?action=delete&courseId=<?php echo $editId."&display=$display"; ?>">delete</a>
						<?php } ?>
							<a href="index.php?action=edit&courseId=<?php echo $editId."&display=$display"; ?>">edit</a>
							<a href="index.php?action=copy&courseId=<?php echo $editId."&display=$display"; ?>">copy</a>	
						<?php
						echo("</td></tr>");
						echo("<tr class='$class'><td colspan='5' class='anno'>");
						echo($row['annotation']."</tr>");
						if ($row['hasXlist']) {
						    echo("<tr class='$class'><td colspan='5'>Xlists: ");
							
						    if ((isset($row['primaryCourseId'])) and (!is_null($row['primaryCourseId']))) {
						        $primaryId = getPrimaryCourse($row['primaryCourseId']);
                                $xprimary = mysqli_fetch_assoc($primaryId);
                                echo $xprimary['courseName'].' '.$xprimary['section']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						    } else { 
							     $xlists = getXlists($row['courseId']);
							     while ($xcourse = mysqli_fetch_assoc($xlists)) {
							     	if ($xcourse['section']>0) {
								        echo($xcourse['courseName']." ".$xcourse['section']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
									}
								}
                            }
							echo("</td></tr>");
						} 
						echo("<tr class='$class'><td class='sep' colspan='5'>&nbsp;</td>");
					}
					?>
				</table>

			</section>
		</div>
	</body>
</html>
