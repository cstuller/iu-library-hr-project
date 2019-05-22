<?php
/**
 * Contains the function calls to create the page to create a new job posting
 * in the supervisor portal
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP file contains all functions to build page
require "pageBuilder.php";

// Initialize everything needed for the page
initializePage();

// Fix and store the date
$date = fixData($_POST['PostEndDate']);
// This validates that it is both a correct date, and a date later than today's date
if(!validateDate($date) || $date <= date('Y-m-d')){
  $_SESSION['PostEndDate'] = $date;
  $errorList[] = 'Invalid Date -- Please choose a date after today.';
}

// Assemble all HTML into one variable to display once built
// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorNewPostingMenu();

// Build the main body of the page -- right side content
// double-check ID data and take care of background information
if(!empty($_SESSION['ID'])){
  $id = fixData($_SESSION['ID']);
  $results = getPositionInformation($id);
  if(!empty($results) && $results['Approved'] == TRUE && $results['Owner'] == $_ENV["REMOTE_USER"]){
    // Make one last check to make sure the date is ok/no other errors found
    if(empty($errorList)){
      // if all of the above checks are correct, post the position!
      createNewPosting($id, $date);
      // clear session variables so we don't post it twice
      clearSessionVariables();
      $page .= buildPostingSuccessPage();
    }
    // If only the date is incorrect, re-print the previous page and display an error
    else{
      $page .= buildSupervisorPostingPage($results, $errorList);
    }
  }
  else{
    $page .= buildPositionErrorPage();
  }
}
// if the ID is empty, re-route to main page
else{
  header('Location: main.php');
  exit();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
