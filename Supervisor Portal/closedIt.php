<?php
/**
 * Contains the function calls to close the position
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP file contains all functions to build page
require "pageBuilder.php";

// Initialize everything needed for the page
initializePage();

// Set the session variables from the previous post
if(!empty($_POST)){
  setSessionVariables();
}
// If there was nothing posted, redirect to the main page
else{
  header('Location: main.php');
  exit();
}

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorHomeMenu();

// Build the main body of the page -- right side content
// Grab the ID being passed
if(!empty($_SESSION['PostingID'])){
  $id = fixData($_SESSION['PostingID']);
  $results = getPostingInformation($id);
  // $results will be empty if the ID was invalid -- the rest of the logic makes sure it's ok for this user to see the applicants
  if(!empty($results) && $results['Approved'] == TRUE && $results['Owner'] == $_ENV["REMOTE_USER"] && $results['Enabled'] == TRUE){
    // steps to be performed:
    // 1) Mark all applicants to send E-Mail to as "Not Hired"
    markAppsForClosing($id);
    // 2) Archive this posting and log errors
    $error = archivePosting($id);
    // 3) Display page with information to be copy/pasted for the rejection E-Mail
    if(!$error){
      header('Location: closedPosting.php');
      exit();
    }
    else{
      echo $error;
    }
  }
  else{
    $page .= buildPositionErrorPage();
  }
}
else{
  $page .= buildPositionErrorPage();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
