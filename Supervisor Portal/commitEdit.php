<?php
/**
 * Contains the function calls to edit the approved position in SQL
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

// Commit the edits, if any
if(!empty($_SESSION['Edits'])){
  commitEdits();
}

// Pass to approved page for approval completion
header('Location: edited.php');
exit();

?>
