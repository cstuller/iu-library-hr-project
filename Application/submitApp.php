<?php
/**
 * Contains the function to check documents for consistency and upload them to
 * a safe place
 *
 * this page will perform the function then redirect to the approved page
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
 if(empty($_SESSION['ID'])){
   header('Location: main.php');
   exit();
 }

// This part will include the logic for checking documents and recreating the page if it fails
// for now, just pass to the approved and submitted page
// Pass to approved page for approval completion
header('Location: submitted.php');
exit();

?>
