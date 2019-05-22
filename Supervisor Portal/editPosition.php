<?php
/**
 * Contains the function calls to create the position approval editing page for the HR
 * portal.
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

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorEditPositionMenu();

// Build the right-hand side of the page
// Check to make sure the session has an ID and is initiated
if(!empty($_GET['ID'])){
  $id = fixData($_GET['ID']);
  $results = getPositionInformation($id);
  // $results will be empty if the ID was invalid -- the rest of the logic makes sure it's ok for this user to see and post the position
  if(!empty($results) && $results['Approved'] == TRUE && $results['Owner'] == $_ENV["REMOTE_USER"]){
    // set the session variables for use later
    $submitFields = loadSubmitFields();
    $_SESSION['ID'] = $id;
    setSessionVariablesFromArray($results, $submitFields);
    $page.= buildSupervisorEditPage();
  }
  else{
    $page .= buildPositionErrorPage();
  }
}
// it's possible we came from the next page back to this one, in which case the session ID and variables are already set
elseif(!empty($_SESSION['ID'])){
  $page .= buildSupervisorEditPage();
}
else{
  $page .= buildPositionErrorPage();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
