
<?php
$belowRoot = true;
$isLoggedIn = true;
$isTeacher = false;
$isStudent = true;
$displayClass=true; //display the class name after the prof selects section from dropdown
$showNav = true; //don't display navigation if teacher hasn't selected class from drowpdown

$thisPage = "ExcelDownload";
include '../../header.php';
include '../../config.php';
include 'PHPExcel.php';

$userID =$_SESSION['login_userID'];

    // Make sure an ID was passed
    if(true) {
        date_default_timezone_set("America/New_York");
        $currentDateTime = date("Y-m-d H:i:sa");
        $currentDate = date("Y-m-d");
        $usercell =$userID . $currentDate;
        $assignmentID=$_GET['assignmentID'];
		//$assignmentID = 824708;
           $query = "SELECT * from excel_assignment where assignmentID='$assignmentID'";
		   //echo $assignmentID;
           $result = mysqli_query($conn,$query);  
             if($result) { 
			 
                if($result->num_rows == 1) {
		
                    $row = mysqli_fetch_assoc($result);
					
					$promptFileData=$row['promptFile'];
                    //$promptFileType=$row['promptFileType']; 		
					$promptFileSize=$row['promptFileSize'];
                    $promptFilePath =$row['promptFilePath'];
					$filename ='Assignment.xlsx';
                    $userVariableCell=$row['userVariableCell'];

                    $inputFileType = PHPExcel_IOFactory::identify($promptFilePath);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($promptFilePath);    
                    $styleArray = array(
                        'font'  => array(
                            'color' => array('rgb' => 'ffffff'),
                        ));
                    $rownum = filter_var($userVariableCell, FILTER_SANITIZE_NUMBER_INT);
		            $objPHPExcel->getActiveSheet()->setCellValue($userVariableCell, $usercell);
                    $objPHPExcel->getActiveSheet()->getStyle($userVariableCell)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rownum)->setVisible(false);
                    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
                    $writer->save(dirname($promptFilePath). "/" .$filename); 
                    


                    $query = "insert into excel_promptFileInfo(userID,assignmentID,promptFile,uniqueVariable,lastDownload) 
                    values('$userID','$assignmentID','','$usercell','$currentDateTime')";
                    $conn->query($query);
$promptFileSize = filesize(dirname($promptFilePath). "/" .$filename);

$promptFileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
$promptFileData = file_get_contents(dirname($promptFilePath). "/" .$filename);
header("Content-length: $promptFileSize");
header("Content-type: $promptFileType");
header("Content-Disposition: attachment; filename=$filename");
header('Content-Transfer-Encoding: binary');	
header('Pragma: public');
ob_end_clean();
flush();
echo $promptFileData;                       
         exit;
	}
				}
			 }
  
 

?>