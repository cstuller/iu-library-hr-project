<?php
/**
 * Contains the function calls to create the edit landing page for the
 * Supervisor portal.
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

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorEditPositionMenu();

// Build the right-hand side of the page

// If $_SESSION is empty, it means that this job has already been approved
// This code is for protection to make sure the job doesn't get approved twice
// Note -- THIS CODE SHOULD NEVER EXECUTE because of the redirect code above.
if(empty($_SESSION)){
  $page .= buildSafetyPage();
}
else{
  // If everything's fine, build the success page, and clear the session to prevent double-posting 
  $page .= buildEditSuccessPage();
  clearSessionVariables();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
