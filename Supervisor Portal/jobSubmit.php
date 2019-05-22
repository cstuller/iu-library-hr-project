<?php
/**
 * Contains the function calls to create the page to submit a job to HR for
 * approval.
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
$page .= buildSupervisorSubmitMenu();

// Build the main body of the page -- right side content
$page .= buildSubmitForm();

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
