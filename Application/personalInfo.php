<?php
/**
 * Contains the function calls to create the personal info page for the student
 * application portal
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
setSessionVariables();

// Add the standard IU header
$page = buildApplicationHeader();

// Add the left-side menu
$page .= buildApplicationPersonalMenu('1');

// Build the right-hand side of the page
if(!empty($_SESSION['ID'])){
  $page .= buildApplicationPersonalPage();
}
else{
  $page .= buildPositionErrorPage();
}

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
