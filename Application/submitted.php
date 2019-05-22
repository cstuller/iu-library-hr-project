<?php
/**
 * Contains the function calls to create the page to submit an application
 * to a position
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

// If you somehow get to this page without submitting anything, it should automatically redirect you to the submission page
if(empty($_SESSION)){
  header('Location: main.php');
  exit();
}

// Submit the position to the database
// if statement for back button protection, $errorList to catch SQL problems
if(!empty($_SESSION)){
  $errorList = submitApplication();
}

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildApplicationHeader();

// Add the left-side menu
$page .= buildApplicationHomeMenu('0');

// If $_SESSION is empty, it means that this job has already been submitted
// This code is for protection to make sure the job doesn't get submitted twice
// Note -- THIS CODE SHOULD NEVER EXECUTE because of the redirect code above.
if(empty($_SESSION)){
  $page .= buildSafetyPage();
}
// If there were errors with the SQL, errors are listed here
elseif(!empty($errorList)){
  $page .= buildErrorPage($errorList);
}
else{
  $page .= buildApplicationSuccessPage();
  // Now that the confirmation page is completed, clear the session
  clearSessionVariables();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
