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
$page = buildHRHeader();

// Add the left-side menu
$page .= buildHRHomeMenu();

// Build the right-hand side of the page
// Check to make sure the session has an ID and is initiated
if(!empty($_SESSION['ID'])){
  $page .= buildHRApproveEditPage();
}
else{
  $page .= buildHRApproveErrorPage();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
