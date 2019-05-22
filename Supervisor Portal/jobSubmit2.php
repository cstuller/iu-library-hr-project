<?php
/**
 * Contains the function calls to create the page to confirm a job
 * submission to HR for approval
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP file contains all functions to build page
require "pageBuilder.php";

// Initialize everything needed for the page
initializePage();

// This page receives a POST command
// This function saves the variables as session variables to be used elsewhere
setSessionVariables();

// If you somehow get to this page without submitting anything, it should automatically redirect you to the submission page
if(empty($_SESSION)){
  header('Location: jobSubmit.php');
  exit();
}

// Data has only been verified client side
// Server side data validation takes place here
// If Session variables are empty, it is caught later and an error is displayed
if(!empty($_SESSION)){
  $errorList = validateJobSubmitData();
}

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorSubmitMenu();

// Build the main body of the page -- right side content

// If Session variables are empty, likely culprit is the back button on the browser
// Print a safety page to prevent double-submission of data
// Note -- THIS CODE SHOULD NEVER EXECUTE because of the redirect code above.
if(empty($_SESSION)){
  $page .= buildSafetyPage();
}
// If there are errors, display them and redisplay the form
elseif(!empty($errorList)) {
  $page .= buildSubmitForm($errorList);
}
// Otherwise, show the job confirmation page
else{
  $page .= buildSubmitConfirm();
}
// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
