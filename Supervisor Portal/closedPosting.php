<?php
/**
 * Contains the function calls to create the page with the form E-Mail information
 * for a recently closed position
 *
 * $page will contain all HTML that will be displayed on the page
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

 // External PHP file contains all functions to build page
 require "pageBuilder.php";

// Initialize everything needed for the page
initializePage();

// If you somehow get to this page without submitting anything, it should automatically redirect you to the submission page
if(empty($_SESSION)){
  header('Location: main.php');
  exit();
}

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorHomeMenu();

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
  $page .= buildClosingSuccessPage($_SESSION['PostingID']);
  // Now that the confirmation page is completed, clear the session
  // clearSessionVariables();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
