<?php
/**
 * Contains the function calls mark applicant as hired in Supervisor Portal
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// External PHP file contains all functions to build page
require "pageBuilder.php";

// Initialize everything needed for the page
initializePage();

// Build the main body of the page -- right side content
// Grab the ID being passed
if(!empty($_GET['ID'])){
  $id = fixData($_GET['ID']);
  $results = getAppInformation($id);
  // $results will be empty if the ID was invalid -- the rest of the logic makes sure it's ok for this user to see the applicants
  if(!empty($results) && $results['Approved'] == TRUE && $results['Owner'] == $_ENV["REMOTE_USER"]){
    setApplicantNotHired($id);
    header('Location: appdetails.php?ID=' . $id);
    exit();
  }
  else{
    $page = buildSupervisorHeader();
    $page .= buildSupervisorHomeMenu();
    $page .= buildPositionErrorPage();
    $page .= buildFooter();
  }
}
else{
  $page = buildSupervisorHeader();
  $page .= buildSupervisorHomeMenu();
  $page .= buildPositionErrorPage();
  $page .= buildFooter();
}

// Add the standard IU footer


// Display the HTML
print $page;

?>
