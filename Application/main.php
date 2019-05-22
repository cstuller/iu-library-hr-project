<?php
/**
 * Contains the function calls to create the position selection page for the
 * student application portal
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

// Build table for current open positions
$table = buildOpenPostingsTable();

// Add the standard IU header
$page = buildApplicationHeader();

// Add the left-side menu
$page .= buildApplicationHomeMenu('0');

// Build the right-hand side of the page
$page .= buildApplicationJobSelectionPage($table);

// Add the standard IU footer
$page .= buildFooter();

// Display the HTML
print $page;

?>
