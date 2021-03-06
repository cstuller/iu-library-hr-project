<?php
/**
 * Contains the function calls to create the landing page for the HR
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

// Clear any session variables, as they will be unnecessary for the main page
clearSessionVariables();

// Create the table displayed on the home page
$table = buildHRHomeTable();

// Add the standard IU header
$page = buildHRHeader();

// Add the left-side menu
$page .= buildHRHomeMenu();

// Build the right-hand side of the page
$page .= buildHRHomePage($table);

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
