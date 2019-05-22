<?php
/**
 * Contains the function calls to create the page to close a posting
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

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorHomeMenu();

// Build the main body of the page -- right side content
// Grab the ID being passed
if(!empty($_GET['ID'])){
  $id = fixData($_GET['ID']);
  $results = getPostingInformation($id);
  // $results will be empty if the ID was invalid -- the rest of the logic makes sure it's ok for this user to see the applicants
  if(!empty($results) && $results['Approved'] == TRUE && $results['Owner'] == $_ENV["REMOTE_USER"] && $results['Enabled'] == TRUE){
    $rejList = getRejectionResults($id);
    $page.= buildSupervisorClosingPage($results['Title'], $rejList, $id);
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
