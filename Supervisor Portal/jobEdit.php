<?php
/**
 * Contains the function calls to create the page to edit a position
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

// Clear any session variables, as they will be unnecessary for this page
clearSessionVariables();

// Create a table of information for the page to display
$table = buildSupervisorEditPositionTable();

// Assemble all HTML into one variable to display once built

// Add the standard IU header
$page = buildSupervisorHeader();

// Add the left-side menu
$page .= buildSupervisorEditPositionMenu();

// Build the main body of the page -- right side content
$page .= buildSupervisorEditPositionPage($table);

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
