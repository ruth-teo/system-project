<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if($conn ) {
  echo "Connected successfully.<br>";
} else {
   die('Could not connect: ' . mysql_error());
 }

 mysql_select_db('Carifund');

# Form: New Project
if(!empty($_POST['new-project'])) {
  $projectTitle = str_replace("'", "''", $_POST['projectTitle']);
  $beneficiary = str_replace("'", "''", $_POST['beneficiary']);
  $beneficiaryLocation = str_replace("'", "''", $_POST['beneficiaryLocation']);
  $projectTags = str_replace("'", "''", $_POST['projectTags']);
  $projectDesc = str_replace("'", "''", $_POST['projectDesc']);
  $donationGoal = $_POST['donationGoal'];
  $category = $_POST['category'];
  $ownerID = $_POST['ownerID'];
  
  if(empty($_POST['projectImg'])){
    $projectImg = "NONE";
  } else {
    $projectImg = $_POST['projectImg'];
  }

  $sql = "insert into cfProjects (projectTitle, beneficiary,".
        " beneficiaryLocation, donationGoal, donationProgress,".
        " projectTags, projectCategory, projectDesc, ownerID, projectImg)".
        " values ('{$projectTitle}','{$beneficiary}','{$beneficiaryLocation}',".
        " {$donationGoal},0,'{$projectTags}','{$category}','{$projectDesc}', '{$ownerID}', '{$projectImg}');";

  $retval = mysql_query($sql, $conn);

  if($retval){
    echo "Entered successfully.<br>";
  } else {
    echo "An error occured.<br>" . mysql_error();
  } 
}

  #Form: Member Sign-up
  // add new user when email is not used 
if(!empty($_POST['create'])) {
  
  // check if email already being used; prevent duplicate user email
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
      exit("Invalid email address"); 
  $select = mysql_query("SELECT `email` FROM `cfUsers` WHERE `email` = '".$_POST['email']."'") or exit(mysql_error());
  if(mysql_num_rows($select)){
      echo "This email is already being used.";
      header("Location: signup.html");
      exit;
  }   
  
  $sql = "insert into cfUsers (firstname, lastname, email, password)".
   "values ('{$_POST['firstname']}','{$_POST['lastname']}','{$_POST['email']}','{$_POST['password']}');";

   $retval = mysql_query($sql, $conn);

   if($retval){
     echo "New member added!<br>";
     header("Location: login.html");
   } else {
     echo "Error!<br>" . mysql_error();
     header("Location: signup.html");
   }

}
  
  #Donation History
if(!empty($_POST['donate'])){
  
  $sql  = "insert into cfDonations (donorID, projectID, amount)".
  "values ('{$_POST['userID']}', '{$_POST['projID']}', {$_POST['amount']});";
  
  $retval = mysql_query($sql, $conn);
  
  $sql  = "UPDATE cfProjects SET donationProgress = donationProgress + {$_POST['amount']}".
  " WHERE projectID='{$_POST['projID']}';";
  
  $retval = mysql_query($sql, $conn);
  
  if($retval){
    header('Location: project.php?id='.$_POST['projID']);
  } else {
    echo "Error!<br>" . mysql_error();
  }
}
 ?>
