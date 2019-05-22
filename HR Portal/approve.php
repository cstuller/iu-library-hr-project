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
require 'pageBuilder.php';

// Initialize everything needed for the page
initializePage();

// Add the standard IU header
$page = buildHRHeader();

// Add the left-side menu
$page .= buildHRHomeMenu();

// Build the right-hand side of the page
// Grab the ID being passed
if(!empty($_GET['ID'])){
  $id = fixData($_GET['ID']);
  $results = getPositionInformation($id);
  if(!empty($results)){
    // set the session ID for use later
    $_SESSION['ID'] = $id;
    $page.= buildHRApprovePage($results);
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
