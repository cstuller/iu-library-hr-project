<?php
/**
 * Contains the function calls to create the position approval page for the HR
 * portal.
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP files
// pageBuilder.php -- Builds all of the HTML for the page
// queryBuilder.php -- Takes care of the SQL work for the page
require 'pageBuilder.php';

// Initialize everything needed for the page
initializePage();

// If you somehow get to this page without submitting anything, it should automatically redirect you to the home page
if(empty($_SESSION)){
  header('Location: main.php');
  exit();
}

// Grab the ID of the approved position from session variables
$id = $_SESSION['ID'];

// Approve the position as-is in the database
// if statement for back button protection, $errorList to catch SQL problems
if(!empty($_SESSION)){
  $errorList = approvePosition($id);
}

// Add the standard IU header
$page = buildHRHeader();

// Add the left-side menu
$page .= buildHRHomeMenu();

// Build the right-hand side of the page

// If $_SESSION is empty, it means that this job has already been approved
// This code is for protection to make sure the job doesn't get approved twice
// Note -- THIS CODE SHOULD NEVER EXECUTE because of the redirect code above.
if(empty($_SESSION)){
  $page .= buildSafetyPage();
}
// If there were errors with the SQL, errors are listed here
elseif(!empty($errorList)){
  $page .= buildErrorPage($errorList);
}
else{
  $page .= buildApproveSuccessPage();
  // Now that the confirmation page is completed, clear the session
  clearSessionVariables();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
