<?php
/**
 * Contains all functions related to the SQL work for the
 * supervisor portal
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

// basic files required to make SQL connections start here
require 'credentials.php';

// global variable declarations go here

// functions start here
/**
 * Pulls the information from SQL to load the fields related to submitted positions
 */
function loadSubmitFields(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // make a counter -- for use with ArrayTable column
  $count = 0;

  // build and execute the statement
  $sql = 'SELECT * FROM SubmitFields ORDER BY Priority ASC';
  $result = mysqli_query($link, $sql);

  // gather the results
  while($row = mysqli_fetch_array($result)){
    $loadedTable[] = $row;
    // check to see if this row has a subarray that needs loaded
    if(!empty($row['ArrayTable'])){
      $tableLoadRows[] = array($count, $row['ArrayTable']);
    }
    $count++;
  }

  // every table entry that had a subarray needs to load that array now
  if(!empty($tableLoadRows)){
    foreach($tableLoadRows as $loadThis){
      $subTable = [];
      $sql = 'SELECT * FROM ' . $loadThis[1];
      $result = mysqli_query($link, $sql);

      // gather the results
      while($row = mysqli_fetch_array($result)){
        $subTable[] = $row;
      }

      // place the subArray into the main array
      $loadedTable[$loadThis[0]]['ArrayTable'] = $subTable;
    }
  }
  // close the connection and return results
  mysqli_close($link);
  return $loadedTable;
}

/**
 * Pulls the information from SQL to load the personal information fields from the
 * Employee Application
 */
function loadAppPersonalFields(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // make a counter -- for use with ArrayTable column
  $count = 0;

  // build and execute the statement
  $sql = 'SELECT * FROM PersonalFields ORDER BY Priority ASC';
  $result = mysqli_query($link, $sql);

  // gather the results
  while($row = mysqli_fetch_array($result)){
    $loadedTable[] = $row;
    // check to see if this row has a subarray that needs loaded
    if(!empty($row['ArrayTable'])){
      $tableLoadRows[] = array($count, $row['ArrayTable']);
    }
    $count++;
  }

  // every table entry that had a subarray needs to load that array now
  if(!empty($tableLoadRows)){
    foreach($tableLoadRows as $loadThis){
      $subTable = [];
      $sql = 'SELECT * FROM ' . $loadThis[1];
      $result = mysqli_query($link, $sql);

      // gather the results
      while($row = mysqli_fetch_array($result)){
        $subTable[] = $row;
      }

      // place the subArray into the main array
      $loadedTable[$loadThis[0]]['ArrayTable'] = $subTable;
    }
  }
  // close the connection and return results
  mysqli_close($link);
  return $loadedTable;
}

/**
 * Pulls the information from SQL to load the day of week fields from the
 * Employee Application
 */
function loadAppDayFields(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // make a counter -- for use with ArrayTable column
  $count = 0;

  // build and execute the statement
  $sql = 'SELECT * FROM AvailDays';
  $result = mysqli_query($link, $sql);

  // gather the results
  while($row = mysqli_fetch_array($result)){
    $loadedTable[] = $row;
    // check to see if this row has a subarray that needs loaded
    if(!empty($row['ArrayTable'])){
      $tableLoadRows[] = array($count, $row['ArrayTable']);
    }
    $count++;
  }

  // every table entry that had a subarray needs to load that array now
  if(!empty($tableLoadRows)){
    foreach($tableLoadRows as $loadThis){
      $subTable = [];
      $sql = 'SELECT * FROM ' . $loadThis[1];
      $result = mysqli_query($link, $sql);

      // gather the results
      while($row = mysqli_fetch_array($result)){
        $subTable[] = $row;
      }

      // place the subArray into the main array
      $loadedTable[$loadThis[0]]['ArrayTable'] = $subTable;
    }
  }
  // close the connection and return results
  mysqli_close($link);
  return $loadedTable;
}

/**
 * Pulls the information from SQL to load the personal information fields from the
 * Employee Application
 */
function loadAppWorkHistoryFields(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // make a counter -- for use with ArrayTable column
  $count = 0;

  // build and execute the statement
  $sql = 'SELECT * FROM WorkHistoryFields ORDER BY Priority ASC';
  $result = mysqli_query($link, $sql);

  // gather the results
  while($row = mysqli_fetch_array($result)){
    $loadedTable[] = $row;
    // check to see if this row has a subarray that needs loaded
    if(!empty($row['ArrayTable'])){
      $tableLoadRows[] = array($count, $row['ArrayTable']);
    }
    $count++;
  }

  // every table entry that had a subarray needs to load that array now
  if(!empty($tableLoadRows)){
    foreach($tableLoadRows as $loadThis){
      $subTable = [];
      $sql = 'SELECT * FROM ' . $loadThis[1];
      $result = mysqli_query($link, $sql);

      // gather the results
      while($row = mysqli_fetch_array($result)){
        $subTable[] = $row;
      }

      // place the subArray into the main array
      $loadedTable[$loadThis[0]]['ArrayTable'] = $subTable;
    }
  }
  // close the connection and return results
  mysqli_close($link);
  return $loadedTable;
}

/**
 * Takes the data and returns it escaped to prevent SQL Injection attacks
 *
 * @param mysqli_connect $link -- connection to the MySQL database
 * @param $data -- data to be escaped
 */
function dataMassage($link, $data){
  return mysqli_real_escape_string($link, $data);
}

/**
 * Returns the sql data needed to build the HR home page, in an HTML Table format
 */
function buildHRHomeTable(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // create the SQL
  $sql = 'SELECT JobID, Title, Department, Owner FROM libposition WHERE Approved = FALSE';

  // run the query and return any errors
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<table class="mobile-labels">
      <thead>
        <tr>
          <th scope="col">Position</th>
          <th scope="col">Department</th>
          <th scope="col">Submitted By</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="approve.php?ID='. $row['JobID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Position">';
      $tableRow .= $row['Title'];
      $tableRow .= '</td>
              <td data-label="Department">';
      $tableRow .= $row['Department'];
      $tableRow .= '</td>
              <td data-label="Submitted By">';
      $tableRow .= $row['Owner'];
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return '0 results<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Returns the sql data needed to build the Supervisor home page, in an HTML Table format
 */
function buildSupervisorHomeTable(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // create the SQL
  $sql = 'SELECT libposting.PostingID as PostingID, libposition.JobID as JobID, libposition.Title as Title, libposting.PostEndDate as PostEndDate ';
  $sql .= 'FROM libposition, libposting ';
  $sql .= 'WHERE libposition.JobID = libposting.JobID ';
  $sql .= 'AND libposition.Owner = "' . mysqli_real_escape_string($link, $_ENV['REMOTE_USER']). '" ';
  // If the sorting needs changed, just change this line
  $sql .= 'ORDER BY PostEndDate, Title';

  // run the query and return any errors
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<table class="mobile-labels">
      <thead>
        <tr>
          <th scope="col">Job ID</th>
          <th scope="col">Job Title</th>
          <th scope="col">Posting End Date</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="showApps.php?ID='. $row['PostingID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Job ID">';
      $tableRow .= $row['JobID'];
      $tableRow .= '</td>
              <td data-label="Job Title">';
      $tableRow .= $row['Title'];
      $tableRow .= '</td>
              <td data-label="Posting End Date">';
      // If the Post End Date has passed, list posting as CLOSED
      if($row['PostEndDate'] < date('Y-m-d')){
        $tableRow .= '<i>PASSED -- ACTION REQUIRED</i>';
      }
      else{
        $tableRow .= $row['PostEndDate'];
      }
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table><br/><br/><br/>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return '0 results<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Returns the sql data needed to build the open postings page, in an HTML Table format
 */
function buildOpenPostingsTable(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // create the SQL
  $sql = 'SELECT libposting.PostingID as PostingID, libposition.JobID as JobID, libposition.Title as Title, libposition.Department as Department, libposting.PostEndDate as PostEndDate ';
  $sql .= 'FROM libposition, libposting ';
  $sql .= 'WHERE libposition.JobID = libposting.JobID ';
  $sql .= 'AND libposting.PostEndDate >= "' . date('Y-m-d') . '" ';
  // If the sorting needs changed, just change this line
  $sql .= 'ORDER BY Title';


  // run the query and return any errors
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<table class="mobile-labels">
      <thead>
        <tr>
          <th scope="col">Job Title</th>
          <th scope="col">Department</th>
          <th scope="col">Closing Date</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="jobDetails.php?ID='. $row['PostingID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Job Title">';
      $tableRow .= $row['Title'];
      $tableRow .= '</td>
              <td data-label="Department">';
      $tableRow .= $row['Department'];
      $tableRow .= '</td>
              <td data-label="Closing Date">';
      $tableRow .= $row['PostEndDate'];
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table><br/><br/><br/>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return '0 results<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Returns the sql data needed to build the open postings page, in an HTML Table format
 *
 * @param string $id -- The ID of the posting from which the applications are being pulled
 */
function buildSupervisorApplicantTable($id){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // create the SQL
  $sql = 'SELECT libapplication.ApplicationID as ApplicationID, CONCAT(libapplicant.FirstName, " ", libapplicant.LastName) as Name, libapplicant.StudEMail as EMail, libapplication.Hired as Hired ';
  $sql .= 'FROM libapplicant, libapplication ';
  $sql .= 'WHERE libapplication.PostingID = ' . mysqli_real_escape_string($link, $id) . ' ';
  $sql .= 'AND libapplication.ApplicantID = libapplicant.ApplicantID ';
  // If the sorting needs changed, just change this line
  $sql .= 'ORDER BY libApplication.Hired DESC, libApplicant.LastName, libApplicant.FirstName';

  // run the query and return any errors
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<form><table class="mobile-labels">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">E-Mail</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="appDetails.php?ID='. $row['ApplicationID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Name">';
      if($row['Hired'] == TRUE){
        $tableRow .= '<b><i>***Hired*** ' . $row['Name'] . '</i></b>';
      }
      else{
        $tableRow .= $row['Name'];
      }
      $tableRow .= '</td>
              <td data-label="E-Mail">';
      if($row['Hired'] == TRUE){
        $tableRow .= '<i>' . $row['EMail'] . '</i>';
      }
      else{
        $tableRow .= $row['EMail'];
      }
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return 'No one has applied for this position.<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Returns the sql data needed to build the Supervisor new posting page,
 * as an HTML table
 */
function buildSupervisorPositionTable(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // grab and sterilize the username
  $userName = mysqli_real_escape_string($link, $_ENV['REMOTE_USER']);

  // create the SQL
  $sql = 'SELECT JobID, Title FROM libposition WHERE Approved = TRUE AND Enabled = TRUE AND Owner = "' . $_ENV['REMOTE_USER'] . '" ORDER BY Title';
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<table class="mobile-labels">
      <caption>Please select a position below:</caption>
      <thead>
        <tr>
          <th scope="col">Job ID</th>
          <th scope="col">Job Title</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="createPosting.php?ID='. $row['JobID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Job ID">';
      $tableRow .= $row['JobID'];
      $tableRow .= '</td>
              <td data-label="Job Title">';
      $tableRow .= $row['Title'];
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return 'You have no approved positions with which to create postings.<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Returns the sql data needed to build the Supervisor edit position page,
 * as an HTML table
 */
function buildSupervisorEditPositionTable(){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // grab and sterilize the username
  $userName = mysqli_real_escape_string($link, $_ENV['REMOTE_USER']);

  // create the SQL
  $sql = 'SELECT JobID, Title FROM libposition WHERE Approved = TRUE AND Enabled = TRUE AND Owner = "' . $_ENV['REMOTE_USER'] . '" ORDER BY Title';
  $result = mysqli_query($link, $sql);

  // If there are results, build a table.  Otherwise, return no results.
  if(mysqli_num_rows($result) > 0) {
    $table = '<table class="mobile-labels">
      <caption>Please select a position below:</caption>
      <thead>
        <tr>
          <th scope="col">Job ID</th>
          <th scope="col">Job Title</th>
        </tr>
      </thead>
      <tbody>';
    // build each row of the table
    while($row = mysqli_fetch_array($result)){
      $tableRow = '<tr class="clickable-row" data-href="editPosition.php?ID='. $row['JobID'] .'">
              <!-- each td should have a data-label to match the th -->
              <td data-label="Job ID">';
      $tableRow .= $row['JobID'];
      $tableRow .= '</td>
              <td data-label="Job Title">';
      $tableRow .= $row['Title'];
      $tableRow .= '</td>
            </tr>';
      // once the row is constructed, add it to the table
      $table .= $tableRow;
    }
    $table .= '</tbody>
        </table>';
    // close the connection
    mysqli_close($link);
    return $table;
  }
  else{
    // close the connection
    mysqli_close($link);
    return 'You have no approved positions to edit.<br/><br/><br/><br/><br/><br/>';
  }
}

/**
 * Takes the submitted Posting ID and retrieves the results.  Returns the results
 * formatted in HTML for review.
 *
 * If the ID does not exist in the database, returns an HTML error page to that effect
 *
 * @param string $id -- the ID number to be searched in the database
 */
function getPostingInformation($id) {
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // escape the id for injection protection
  $safeID = mysqli_real_escape_string($link, $id);

  // build and execute the SQL query
  $sql = 'SELECT * FROM LibPosition, LibPosting WHERE LibPosition.JobID = LibPosting.JobID AND LibPosting.PostingID = "' . $safeID . '"';
  $result = mysqli_query($link, $sql);

  // there should be only one row, or zero
  $row = mysqli_fetch_array($result);

  // close the link
  mysqli_close($link);

  return $row;
}

/**
 * Takes the submitted Posting ID, checks all applicants in session, and marks them as "Not Hired"
 * if they were checked on previous page
 *
 * @param string $id -- the ID number to be searched in the database
 */
function markAppsForClosing($id) {
  // create array to hold IDs of unchecked applicants
  $unchecked = array();

  // array to hold rejected applicants
  $rejList = getRejectionResults($id);

  // run through the rejection list and see if any are set to unchecked
  foreach($rejList as $rejected){
    // build Session Variable to be checked
    $sessionString = $rejected['ApplicationID'] . 'app';
    // if the box is unchecked, add the ID to unchecked array
    if($_SESSION[$sessionString] == FALSE){
      $unchecked[] = $rejected['ApplicationID'];
    }
  }

  // start the mySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // build the SQL statement
  $sql = 'UPDATE LibApplication SET NotHired = 1 WHERE Hired != 1 AND PostingID = ' . mysqli_real_escape_string($link, $id);
  // add to the SQL statement all exceptions found above
  foreach($unchecked as $appID){
    $sql .= ' AND ApplicationID != ' . mysqli_real_escape_string($link, $appID);
  }

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Takes the Submitted PostingID and Archives the position.
 *
 * @param string $id -- the ID number to be searched in the database
 */
function archivePosting($id){
  // start the mySQL session and sanitize the ID
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);
  $safeID = mysqli_real_escape_string($link, $id);

  // build all queries for all steps to be executed at once
  // Copy the Posting to ArchivedPosting Table
  $sql = 'INSERT INTO archivedposting SELECT * FROM libposting WHERE libposting.PostingID = ' . $safeID . '; ';
  // Copy all Applications to ArchivedApps Table
  $sql .= 'INSERT INTO archivedapps SELECT * FROM libapplication WHERE PostingID = ' . $safeID . '; ';
  // Delete all Applications to this job from LibApplication Table
  $sql .= 'DELETE FROM libapplication WHERE PostingID = ' . $safeID . '; ';
  // Delete the posting from LibPosting Table
  $sql .= 'DELETE FROM libposting WHERE PostingID = ' . $safeID . '; ';

  // run all the queries at once
  if (!mysqli_multi_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Takes the submitted Posting ID and retrieves the list of applicants who are not marked as hired
 *
 *
 * @param string $id -- the ID number to be searched in the database
 */
function getRejectionResults($id) {
  // empty array to hold rejections
  $rejList = array();

  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // create the SQL
  $sql = 'SELECT * ';
  $sql .= 'FROM libapplicant, libapplication ';
  $sql .= 'WHERE libapplication.PostingID = ' . mysqli_real_escape_string($link, $id) . ' ';
  $sql .= 'AND libapplication.ApplicantID = libapplicant.ApplicantID ';
  $sql .= 'AND libapplication.Hired = FALSE ';
  // If the sorting needs changed, just change this line
  $sql .= 'ORDER BY libApplicant.LastName, libApplicant.FirstName';

  // execute the SQL query
  $result = mysqli_query($link, $sql);

  // If there are results, add them to the list
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)){
      $rejList[] = $row;
    }
  }

  // close the connection and return the list
  mysqli_close($link);
  return $rejList;
}

/**
 * Takes the submitted Application ID and retrieves the results.
 *
 * If the ID does not exist in the database, returns an error to that effect
 *
 * @param string $id -- the ID number to be searched in the database
 */
function getAppInformation($id) {
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // escape the id for injection protection
  $safeID = mysqli_real_escape_string($link, $id);

  // build and execute the SQL query
  $sql = 'SELECT * FROM LibApplication, LibApplicant, LibPosting, LibPosition WHERE LibPosition.JobID = LibPosting.JobID AND LibApplication.PostingID = LibPosting.PostingID AND LibApplicant.ApplicantID = LibApplication.ApplicantID AND LibApplication.ApplicationID = "' . $safeID . '"';
  $result = mysqli_query($link, $sql);

  // there should be only one row, or zero
  $row = mysqli_fetch_array($result);

  // close the link
  mysqli_close($link);

  return $row;
}

/**
 * Takes the submitted Position ID and retrieves the results.  Returns the results
 * formatted in HTML for review.
 *
 * If the ID does not exist in the database, returns an HTML error page to that effect
 *
 * @param string $id -- the ID number to be searched in the database
 */
function getPositionInformation($id) {
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // escape the id for injection protection
  $safeID = mysqli_real_escape_string($link, $id);

  // build and execute the SQL query
  $sql = 'SELECT * FROM LibPosition WHERE JobID = "' . $safeID . '"';
  $result = mysqli_query($link, $sql);

  // there should be only one row, or zero
  $row = mysqli_fetch_array($result);

  // close the link
  mysqli_close($link);

  return $row;
}

/**
 * Approves the position of the passed JobID
 *
 * @param string id -- The JobID to be approved
 */
function approvePosition($id){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // escape the ID to prevent injection
  $safeID = mysqli_real_escape_string($link, $id);

  // build the SQL query
  $sql = 'UPDATE LibPosition SET Approved = 1 WHERE JobID = "' . $safeID . '"';

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Takes the current session-stored JobID, commits the edits to the position
 */
function commitEdits(){
  $submitFields = loadSubmitFields();
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // start the MySQL query
  $sql = 'UPDATE LibPosition SET ';

  // create array of escaped data, and build the sql statement for the update
  foreach($submitFields as $field){
    switch($field['Type']){
      // checkboxes require their own logic
      case 'checkbox':
        foreach($field['ArrayTable'] as $row){
          if(array_key_exists($row['VarName'], $_SESSION['Edits'])){
            $sql .= $row['ColName'] . ' = "' . $_SESSION['Edits'][$row['VarName']] . '",';
          }
        }
        break;
      // everything else can be committed as-is
      default:
        if(array_key_exists($field['VarName'], $_SESSION['Edits'])) {
          $sql .= $field['ColName'] . ' = "' . $_SESSION['Edits'][$field['VarName']] . '",';
        }
    }
  }

  // replace the last comma of the statement with a space
  $sql = substr_replace($sql, ' ', -1);

  // end the MySQL query
  $sql .= 'WHERE JobID="' . mysqli_real_escape_string($link, $_SESSION['ID']) . '"';

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Takes the stored session variables, validates the data, and posts the Position
 * to the LibPosition table
 */
function submitPosition() {
  $submitFields = loadSubmitFields();
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // grab username from environment variables
  $userName = mysqli_real_escape_string($link, $_ENV['REMOTE_USER']);

  // strings to hold MySQL pieces
  $sqlHeaders = '(Owner,';
  $sqlValues = '("' . $userName . '",';

  // create array of escaped data, and build the sql statement for the insert
  foreach($submitFields as $field){
    switch($field['Type']){
      // checkboxes require their own logic
      case 'checkbox':
        foreach($field['ArrayTable'] as $row){
          if(array_key_exists($row['VarName'], $_SESSION)){
            $sqlHeaders .= $row['ColName'] . ',';
            $sqlValues .= '"' . mysqli_real_escape_string($link, $_SESSION[$row['VarName']]) . '",';
          }
        }
        break;
      // everything else can be committed as-is
      default:
        if(array_key_exists($field['VarName'], $_SESSION)) {
          $sqlHeaders .= $field['ColName'] . ',';
          $sqlValues .= '"' . mysqli_real_escape_string($link, $_SESSION[$field['VarName']]) . '",';
        }
    }
  }

  // replace the last comma of statement with a )
  $sqlHeaders = substr_replace($sqlHeaders, ')', -1);
  $sqlValues = substr_replace($sqlValues, ')', -1);

  // build the query from the above pieces
  $sql = 'INSERT INTO LibPosition ' . $sqlHeaders . ' VALUES ' . $sqlValues;

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;

}

/**
 * Takes the stored session variables and submits the application.
 */
function submitApplication(){
  // load personal information & day information fields
  $personalFields = loadAppPersonalFields();
  $dayFields = loadAppDayFields();

  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // grab username from environment variables
  $userName = mysqli_real_escape_string($link, $_ENV['REMOTE_USER']);

  // strings to hold MySQL pieces
  $sqlHeaders = '(UserName,';
  $sqlValues = '("' . $userName . '",';

  foreach($personalFields as $field){
    switch($field['Type']){
      // checkboxes require their own logic
      case 'checkbox':
        foreach($field['ArrayTable'] as $row){
          if(array_key_exists($row['VarName'], $_SESSION)){
            $sqlHeaders .= $row['ColName'] . ',';
            $sqlValues .= '"' . mysqli_real_escape_string($link, $_SESSION[$row['VarName']]) . '",';
          }
        }
        break;
      // everything else can be committed as-is
      default:
        if(array_key_exists($field['VarName'], $_SESSION)) {
          $sqlHeaders .= $field['ColName'] . ',';
          $sqlValues .= '"' . mysqli_real_escape_string($link, $_SESSION[$field['VarName']]) . '",';
        }
    }
  }

  // replace the last comma of statement with a )
  $sqlHeaders = substr_replace($sqlHeaders, ')', -1);
  $sqlValues = substr_replace($sqlValues, ')', -1);

  // build the query from the above pieces
  $sql = 'INSERT INTO LibApplicant ' . $sqlHeaders . ' VALUES ' . $sqlValues;

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    echo $error;
    mysqli_close($link);
    return $error;
  }

  // if there are no errors, continue
  $sql = 'SELECT ApplicantID FROM LibApplicant WHERE UserName = "' . $userName . '" ORDER BY ApplicantID DESC';
  $result = mysqli_query($link, $sql);

  // there should be only one row
  $row = mysqli_fetch_array($result);

  // Set the applicant ID for the next insert
  $appID = $row['ApplicantID'];
  $postingID = mysqli_real_escape_string($link, $_SESSION['ID']);

  // strings to hold MySQL pieces
  $sqlHeaders = '(ApplicantID,PostingID,AvailHours,';
  $sqlValues = '("' . $appID . '","' . $postingID . '","' . mysqli_real_escape_string($link, $_SESSION['availhours']) . '",';

  // loop through all the available times
  foreach($dayFields as $day){
    $timeFields = array('start1', 'end1', 'start2', 'end2');
    foreach($timeFields as $time)
      if(array_key_exists($day['Abbrev'] . $time, $_SESSION) && !empty($_SESSION[$day['Abbrev'] . $time])) {
        $sqlHeaders .= $day['Abbrev'] . $time . ',';
        $sqlValues .= '"' . mysqli_real_escape_string($link, $_SESSION[$day['Abbrev'] . $time]) . '",';
      }
  }

  // replace the last comma of statement with a )
  $sqlHeaders = substr_replace($sqlHeaders, ')', -1);
  $sqlValues = substr_replace($sqlValues, ')', -1);

  // build the query from the above pieces
  $sql = 'INSERT INTO LibApplication ' . $sqlHeaders . ' VALUES ' . $sqlValues;

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    echo $error;
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Sets the application as hired in the Supervisor Portal
 *
 * @param string $id -- id number of the application to be set as hired
 */
function setApplicantHired($id){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // sanitize the data
  $safeID = mysqli_real_escape_string($link, $id);

  // build the sql statement
  $sql = 'UPDATE LibApplication SET Hired = 1, HireDate = "' . date('Y-m-d') . '" WHERE ApplicationID = "' . $safeID . '"';

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Sets the application as hired in the Supervisor Portal
 *
 * @param string $id -- id number of the application to be set as hired
 */
function setApplicantNotHired($id){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // sanitize the data
  $safeID = mysqli_real_escape_string($link, $id);

  // build the sql statement
  $sql = 'UPDATE LibApplication SET Hired = 0 WHERE ApplicationID = "' . $safeID . '"';

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

/**
 * Creates a new job posting based on the jobID and date submitted
 *
 * @param string $id -- id number of the job for the position to be posted
 * @param string $date -- ending date for the position
 */
function createNewPosting($id, $date){
  // start the MySQL session
  $link = mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

  // sanitize the data
  $safeID = mysqli_real_escape_string($link, $id);
  $safeDate = mysqli_real_escape_string($link, $date);

  // build the sql statement
  $sql = 'INSERT INTO LibPosting (JobID, PostEndDate) VALUES ("' . $safeID . '", "' . $safeDate . '")';

  // run the query and return any errors
  if(!mysqli_query($link, $sql)) {
    $error = mysqli_error($link);
    mysqli_close($link);
    return $error;
  }

  // close the connection
  mysqli_close($link);
  return;
}

?>
