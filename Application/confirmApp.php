<?php
/**
 * Contains the function calls to create the confirmation page for the student
 * application portal
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP files
// pageBuilder.php -- Builds all of the HTML for the page
require 'pageBuilder.php';

// Initialize everything needed for the page
initializePage();

// Store all of the variables submitted by the last page
setSessionVariables();

// If you somehow get to this page without submitting anything, it should automatically redirect you to the main page
if(empty($_SESSION)){
  header('Location: main.php');
  exit();
}

// Data has only been verified client side
// Server side data validation takes place here
// If Session variables are empty, it is caught earlier and an error is displayed
if(!empty($_SESSION)){
  $personalInfoErrorList = validateApplicationPersonalInfo();
  if(!empty($personalInfoErrorList))
    $personalInfoFailure = TRUE;
  $workHistoryErrorList = validateApplicationWorkHistory();
  if(!empty($workHistoryErrorList))
    $workHistoryFailure = TRUE;
}

// Add the standard IU header
$page = buildApplicationHeader();

// Add the left-side menu
$page .= buildApplicationConfirmMenu();

// Build the right-hand side of the page
// If there are errors, display them and redisplay the form
if(isset($personalInfoFailure) && $personalInfoFailure == TRUE) {
  $page .= buildApplicationPersonalPage($personalInfoErrorList);
}
elseif(isset($workHistoryFailure) && $workHistoryFailure == TRUE) {
  $page .= buildApplicationWorkHistoryPage($workHistoryErrorList);
}
elseif(!empty($_SESSION['ID'])){
  $page .= buildApplicationConfirmPage();
}
else{
  $page .= buildPositionErrorPage();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
