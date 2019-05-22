<?php
/**
 * Contains all functions related to the creation of HTML pages for the
 * portal
 *
 * @author Craig Stuller <cstuller@indiana.edu>
 */

require 'SQLFunctions.php';

// SUPERMENU has all of the fields for the right-hand menu of the supervisor portal
const SUPERMENU = array(
  array('main.php', 'Home'),
  array('newPosting.php', 'New Job Posting'),
  array('jobEdit.php', 'Edit Position'),
  array('jobSubmit.php', 'Submit New Position'),
  array('archivedJobs.php', 'Archived Postings')
);

// HRMENU has all of the fields for the right-hand menu of the HR portal
const HRMENU = array(
  array('main.php', 'Home'),
  array('current.php', 'Active Postings'),
  array('archivedJobs.php', 'Archived Jobs'),
  array('archivedApps.php', 'Archived Applicants')
);

// STUDENTMENU has all of the fields for the right-hand menu of the HR portal
const STUDENTMENU = array(
  array('main.php', 'Job Information'),
  array('personalInfo.php', 'Personal Information'),
  array('availability.php', 'Availability'),
  array('workHistory.php', 'Work History'),
  array('confirmApp.php', 'Submit Application')
);

// functions start here

/**
 * Checks to see if the $var name has a value associated with it in session storage
 * If so, it returns the value stored in the session
 *
 * Function used for text and date form objects
 *
 * @param string $var -- the name of the variable to be checked
 */
function sessionCheck($var) {
  if(!empty($_SESSION[$var])) {
    return ' value="' . $_SESSION[$var] . '"';
  }
  return '';
}

/**
 * Checks to see if the $var name has a value associated with it in session storage
 * If so, it returns the value stored in the session to be placed in the textarea
 *
 * Function used for textarea form objects
 *
 * @param string $var -- the name of the variable to be checked
 */
function sessionTextArea($var) {
  if(!empty($_SESSION[$var])) {
    return $_SESSION[$var];
  }
  return '';
}

/**
 * Checks to see if the $var name has a value associated with it in session storage
 * If so, it checks that box
 *
 * Function used for checkbox form objects
 *
 * @param string $var -- the name of the variable to be checked
 */
function sessionCheckbox($var) {
  if(!empty($_SESSION[$var])) {
    return ' checked';
  }
  return '';
}

/**
 * Checks to see if the $var name has a value associated with it in session storage
 * If so, it selects that radio button
 *
 * Function used for radio form objects
 *
 * @param string $var -- the name of the variable to be checked
 * @param string $value -- the value associated with the button being checked
 */
function sessionRadio($var, $value) {
  if(!empty($_SESSION[$var]) and $_SESSION[$var] == $value) {
    return ' checked';
  }
  return '';
}

/**
 * Returns an HTML text box
 *
 * @param array $field -- Array for the field being built
 */
function formText($field) {
  // start of the html for the field
  $formPiece = '<div class="form-item">
    <div class="form-item-label">
      <label for="' . $field['VarName'] . '">' . $field['Label'] . ':</label>
    </div>
    <div class="form-item-input">
      <input type="text" name="' . $field['VarName'] . '" id="' . $field['VarName'] . '"';
  // sessionCheck will populate previously used data when user leaves and returns to the page
  $formPiece .= sessionCheck($field['VarName']);
  // check if field is required
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  $formPiece .= '>
    </div>
  </div>';
  return $formPiece;
}

/**
 * Returns an HTML number box
 *
 * @param array $field -- Array for the field being built
 */
function formNumber($field) {
  $formPiece = '<div class="form-item">
    <div class="form-item-label">
      <label for="' . $field['VarName'] . '">' . $field['Label'] . ':</label>
    </div>
    <div class="form-item-input">
      <input type="number" name="' . $field['VarName'] . '" id="' . $field['VarName'] . '" step="' . (float)$field['Step'] . '"';
  // sessionCheck will populate previously used data when user leaves and returns to the page
  $formPiece .= sessionCheck($field['VarName']);
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  $formPiece .='>
    </div>
  </div>';
  return $formPiece;
}

/**
 * Returns an HTML text box formatted for E-Mail addresses
 *
 * @param array $field -- Array for the field being built
 */
function formEmail($field) {
  $formPiece = '<div class="form-item">
    <div class="form-item-label">
      <label for="' . $field['VarName'] . '">' . $field['Label'] . ':</label>
    </div>
    <div class="form-item-input">
      <input type="email" name="' . $field['VarName'] . '" id="' . $field['VarName'] . '"';
  // sessionCheck will populate previously used data when user leaves and returns to the page
  $formPiece .= sessionCheck($field['VarName']);
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  $formPiece .= '>
    </div>
  </div>';
  return $formPiece;
}

/**
 * Returns an HTML radio button with Yes and No labels.  These buttons will assign
 * $name a value of 'Y' or 'N' based on the selection
 *
 * @param array $field -- Array for the field being built
 */
function formYesNo($field) {
  $formPiece = '<div class="form-item">
    <fieldset class="bare">
      <legend class="label">' . $field['Label'] . ':</legend>
      <div class="form-item-input">
        <input type="radio" name="' . $field['VarName'] . '" value="1" id="' . $field['VarName'] . 'yes"';
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  // selects the yes button if it was previously selected and the user leaves and returns to the page
  if(!empty($_SESSION[$field['VarName']]) and $_SESSION[$field['VarName']] == '1'){
    $formPiece .= " checked";
  }
  $formPiece .= '><label for="' . $field['VarName'] .'yes">Yes</label>
        <input type="radio" name="' . $field['VarName'] . '" value="0" id="' . $field['VarName'] . 'no"';
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  // selects the no button if it was previously selected and the user leaves and returns to the page
  if(isset($_SESSION[$field['VarName']]) && empty($_SESSION[$field['VarName']])){
    $formPiece .= " checked";
  }
  $formPiece .= '><label for="' . $field['VarName'] . 'no">No</label>
      </div>
    </fieldset>
  </div>';
  return $formPiece;
}

/**
 * Returns an HTML textarea object
 *
 * @param array $field -- Array for the field being built
 */
function formTextArea($field) {
  $formPiece = '<div class="form-item">
    <div class="form-item-label">
      <label for="' . $field['VarName'] . '">' . $field['Label'] . ':</label>
    </div>
    <div class="form-item-input">
      <textarea name="' . $field['VarName'] . '" id="' . $field['VarName'] . '" rows=' . $field['NumRows'];
  if($field['ReqField'] == TRUE) {
    $formPiece .= ' required';
  }
  $formPiece .= '>';
  // sessionTextArea will populate previously used data when user leaves and returns to the page
  $formPiece .= sessionTextArea($field['VarName']);
  $formPiece .= '</textarea>
    </div>
  </div>';
  return $formPiece;
}

/**
 * Returns an HTML date form object
 *
 * @param array $field -- Array for the field being built
 */
function formDate($field) {
  $formPiece = '<div class="form-item">
    <div class="form-item-label">
      <label for="' . $field['VarName'] . '">' . $field['Label'] . ':</label>
    </div>
    <div class="form-item-input">
      <input type="date" name="' . $field['VarName'] . '" id="' . $field['VarName'] . '"';
  // sessionCheck will populate previously used data when user leaves and returns to the page
  $formPiece .= sessionCheck($field['VarName']);
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  $formPiece .= '>
    </div>
  </div>';
  return $formPiece;
}

/**
 * Returns HTML checkbox buttons based on passed parameters
 *
 * @param array $field -- Array for the field being built
 */
function formCheckbox($field) {
  $formPiece = '<div class="form-item">
    <fieldset class="bare">
      <legend class="label">' . $field['Label'] . ':</legend>
      <div class="form-item-input">';
  // loop through every checkbox and create it
  foreach($field['ArrayTable'] as $row){
    // checkbox hack -- autosets every checkbox name to 0 by default, will change to a 1 when checked and submitted
    $formPiece .= '<input type="hidden" name=' . $row['VarName'] . ' value="0" />';
    $formPiece .= '<input type="checkbox" name="' . $row['VarName'] . '" id="' . $row['VarName'] . '" value="1"';
    // sessionCheckbox will re-check the box if it was checked previously and user leaves and returns to the page
    $formPiece .= sessionCheckbox($row['VarName']);
    $formPiece .= '><label for="' . $row['VarName'] . '">' . $row['Label'] . '</label>';
  }
  $formPiece .= '
      </div>
    </fieldset>
  </div>';
  return $formPiece;
}

/**
 * Returns HTML radio buttons based on passed parameters
 *
 * @param array $field -- Array for the field being built
 */
function formRadio($field) {
  $formPiece = '<div class="form-item">
    <fieldset class="bare">
      <legend class="label">' . $field['Label'] . ':</legend>
      <div class="form-item-input">';
  // for loop creates all buttons for radio based on passed $radioArray
  foreach($field['ArrayTable'] as $row) {
    $formPiece .= '<input type="radio" name="' . $field['VarName'] . '" value="' . $row['Value'] . '" id="' . $row['VarName'] . '"';
    if($field['ReqField'] == TRUE){
      $formPiece .= ' required';
    }
    // sessionRadio selects the radio button if it was previously selected, and user leaves and returns to the page
    $formPiece .= sessionRadio($field['VarName'], $row['Value']);
    $formPiece .= '><label for="' . $row['VarName'] . '">' . $row['Label'] . '</label>';
  }
  $formPiece .= '</div>
    </fieldset>
  </div>';
  return $formPiece;
}

/**
 * Returns HTML dropdown menu based on passed parameters
 *
 * @param array $field -- Array for the field being built
 */
function formDropdown($field) {
  $formPiece = '<div class="form-item">
    <fieldset class="bare">
      <legend class="label">' . $field['Label'] . ':</legend>
      <div class="form-item-input">
      <select name="' . $field['VarName']. '" id="' . $field['VarName'] . '"';
  if($field['ReqField'] == TRUE){
    $formPiece .= ' required';
  }
  $formPiece .= '><option>Choose an option...</option>';
  // for loop creates all buttons for radio based on passed $radioArray
  foreach($field['ArrayTable'] as $row) {
    $formPiece .= '<option value="' . $row['Value'] . '"';
    // check to see if this is set in session, then load as default
    if(!empty($_SESSION[$field['VarName']]) and $_SESSION[$field['VarName']] == $row['Value']) {
      $formPiece .= ' selected';
    }
    $formPiece .= '>' . $row['Label'] . '</option>';
  }
  $formPiece .= '</select>
        </div>
    </fieldset>
  </div>';
  return $formPiece;
}

/**
 * Displays the information stored in session for the set variable name
 * for the confirmation page.
 *
 * Function works for most basic form types
 *
 * @param array $field -- Array for the field being built
 */
function confirmBasic($field) {
  if(isset($_SESSION[$field['VarName']])){
    return '<li><strong>' . $field['Label'] . ':</strong> ' . $_SESSION[$field['VarName']] . '</li>';
  }
  return '';
}

/**
 * Displays the information stored in session for the set variable name
 * for the confirmation page.
 *
 * Function for textarea form types
 *
 * @param array $field -- Array for the field being built
 */
function confirmTextArea($field) {
  if(!empty($_SESSION[$field['VarName']])){
    return '<li><strong>' . $field['Label'] . ':</strong><br/><div class="forceReturns">' . $_SESSION[$field['VarName']] . '</div></li>';
  }
  return '';
}

/**
 * Displays the information stored in session for the set variable name
 * for the confirmation page.
 *
 * Function works for Yes/No Radio Button types
 *
 * @param array $field -- Array for the field being built
 */
function confirmYesNo($field) {
 $yesNoInfo = '';
  if(isset($_SESSION[$field['VarName']])){
    $yesNoInfo .= '<li><strong>' . $field['Label'] . ':</strong> ';
    if(!empty($_SESSION[$field['VarName']])) {
      $yesNoInfo .= 'Yes';
    }
    else {
      $yesNoInfo .= 'No';
    }
    $yesNoInfo .= '</li>';
  }
  return $yesNoInfo;
}

/**
 * Displays the information stored in session for the set variable name
 * for the confirmation page.
 *
 * Function for radio form types
 *
 * @param array $field -- Array for the field being built
 */
function confirmRadio($field) {
  $radioInfo = '';
  if(isset($_SESSION[$field['VarName']])){
    $radioChoice = '';
    //checks the array for the value stored, then pulls the associated label
    foreach($field['ArrayTable'] as $row) {
      if($_SESSION[$field['VarName']] == $row['Value']) {
        $radioChoice = $row['Label'];
        break;
      }
    }
    $radioInfo .= '<li><strong>' . $field['Label'] . ':</strong> ' . $radioChoice . '</li>';
  }
  return $radioInfo;
}

/**
 * Displays the information stored in session for the set variable name
 * for the confirmation page.
 *
 * Function for checkbox form types
 *
 * @param array $field -- Array for the field being built
 */
function confirmCheckbox($field) {
  $checkInfo = '<li><strong>' . $field['Label'] . ':</strong>';
  // initialize variable to hold all checked boxes
  $checkedBoxes = '';
  // iterate over entire checkArray to find checked boxes
  foreach($field['ArrayTable'] as $row) {
    if(isset($_SESSION[$row['VarName']]) and $_SESSION[$row['VarName']] == 1){
      $checkedBoxes .= '<br/>' . $row['Label'];
    }
  }
  if(empty($checkedBoxes)){
    $checkedBoxes = '<br/>None';
  }
  $checkInfo .= $checkedBoxes;
  $checkInfo .= '</li>';
  return $checkInfo;
}

/**
 * Takes the $number input and returns the number of decimal places
 *
 * @param string $number -- number to be checked
 */
function decimalCheck($number) {
  return strlen(substr(strrchr((float)$number, '.'), 1));
}

/**
 * Takes the $text input and decides if it is valid data
 *
 * Returns TRUE if data is valid, FALSE otherwise
 *
 * @param string $text -- text data to be validated
 */
function validateText($text){
  // Regular expression for text box allows only alphanumeric characters and spaces
  if(preg_match("/^[\w!-~ ]*$/", $text)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Takes the $textarea input and decides if it is valid data
 *
 * Returns TRUE if data is valid, FALSE otherwise
 *
 * @param string $text -- text data to be validated
 */
function validateTextArea($textArea){
  // Regular Expression for text area allows alphanumeric characters, newlines,
  // carriage returns, tabs, punctuation, and spaces
  if(preg_match("/^[\w\n\r\t!-~ ]*$/", $textArea)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Takes the $email input and decides if it is valid data
 *
 * Returns TRUE if data is valid, FALSE otherwise
 *
 * @param string $email -- email address to be validated
 */
function validateEmail($email) {
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Takes the $number input and decides if it is valid data
 *
 * Returns TRUE if data is valid, FALSE otherwise
 *
 * @param string $number -- numerical data to be validated
 */
function validateNumber($number){
  // Regular expression for text box allows only alphanumeric characters and spaces
  if(is_numeric($number)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Takes the $date input and decides if it is valid data
 *
 * Returns TRUE if data is valid, FALSE otherwise
 *
 * @param string $date -- date to be validated
 */
function validateDate($date){
  // Regular expression for date checks format is valid
  // Date should be in format YYYY-MM-DD
  // This guarantees 4 digit year which starts with 1 or 2, 2 digit month 01-12, and 2 digit day 01-31
  if(preg_match("([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))", $date)) {
    // If date is in correct format, check that the date is actually valid
    // Readability variables
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
    // Months which have 30 days
    $days30 = array('04', '06', '09', '11');
    // Check february
    if($month == '02') {
      // Leap year logic
      if($day <= 28 || $day == 29 && $year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0)){
        return TRUE;
      }
      return FALSE;
    }
    // Check 30 day months
    elseif(in_array($month, $days30)){
      if($day <= 30){
        return TRUE;
      }
      return FALSE;
    }
    // Check the rest
    else{
      if($day <= 31){
        return TRUE;
      }
      return FALSE;
    }
  }
  return FALSE;
}

/**
 * Checks all entries in $checkArray for valid data
 *
 * Appends any errors to the array
 *
 * NOTE -- THE & in &$errorList is REQUIRED, as the function is being passed
 * the address of the array so more things can be added to it, otherwise this
 * function does nothing.
 *
 * @param array &$errorList -- array where any checkbox errors can be appended
 * @param array $checkArray -- array containing group checkbox information
 */
function validateCheckbox(&$errorList, $checkArray){
  // For loop to iterate over them
  foreach($checkArray as $checkbox){
    if(empty($_SESSION[$checkbox['VarName']]) && $_SESSION[$checkbox['VarName']] != '0'){
      // This field should NEVER be empty -- it should always have a 0 or 1 in it
      $errorList[] = 'Something went wrong with the ' . $checkbox['Label'] . ' field.  Please try again.';
    }
    // all values in the session variables should be 1 or 0
    elseif($_SESSION[$checkbox['VarName']] != '1' && $_SESSION[$checkbox['VarName']] != '0') {
      $errorList[] = 'Invalid entry in ' . $checkbox['Label'] . ' field.';
    }
  }
}

/**
 * Verifies the entry in $radio matches one of the values in $radioArray
 *
 * @param string $radio -- radio value to be validated
 * @param array $radioArray -- array of possible values in radioArray
 */
function validateRadio($radio, $radioArray){
  if(in_array($radio, $radioArray)){
    return TRUE;
  }
  return FALSE;
}

/**
 * Takes the input and performs several data validation steps to prevent scripting attacks
 *
 * @param $data -- data to be validated
 */
function fixData($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/**
 * Takes the Y-m-d date and returns it in m-d-Y
 *
 * @param string $date -- string date that needs to be fixed
 */
function fixDate($date){
  return substr($date, 5, 5) . '-' . substr($date, 0, 4);
}

/**
 *  Takes the 24-hr time HTML object and prints out a 12-hr version
 *
 * @param time $time -- HTML time object to be fixed
 */
function fixTime($time){
  // formatting of AM times
  if($time < '12:00'){
    if(substr($time, 0, 2) != '00'){
      return substr($time, 0, 5) . ' AM';
    }
    else{
      return '12' . substr($time, 2, 3) . ' AM';
    }
  }
  // formatting of PM times
  elseif($time < '24:00'){
    if(substr($time, 0, 2) == '12'){
      return substr($time, 0, 5) . ' PM';
    }
    else{
      return (substr($time, 0, 2) - 12) . substr($time, 2, 3) . ' PM';
    }
  }
  return 'Out of range error';
}

/**
 * Takes all variables that have been posted to a page, and saves them as
 * session variables that can be used across multiple pages
 *
 * This function writes no HTML, and as such returns nothing related to page building
 * This function exists simply for page functionality
 *
 * This function calls fixData for form validation purposes
 */
function setSessionVariables() {
  foreach($_POST as $key=>$value) {
    $_SESSION[$key] = fixData($value);
  }
}

/**
 * Takes all variables that have been posted to a page, and saves them as
 * session variables that can be used across multiple pages
 *
 * Includes code for any edits made, to be used to alter the current job on
 * resubmission
 *
 * This function writes no HTML, and as such returns nothing related to page building
 * This function exists simply for page functionality
 *
 * This function calls fixData for form validation purposes
 */
function setSessionVariablesWithEdits() {
  if(empty($_SESSION['Edits'])){
    $_SESSION['Edits'] = array();
  }
  foreach($_POST as $key=>$value) {
    if($_SESSION[$key] != $value) {
      $_SESSION['Edits'][$key] = fixData($value);
    }
    $_SESSION[$key] = fixData($value);
  }
}

/**
 * Creates VarName/ColName pairs for all fields in a form
 *
 * @param array $fieldArray -- array containing all fields in the form
 *
 * CURRENTLY NOT IN USE, MARKED FOR DELETION -- CS
 */
function buildFieldMap($fieldArray){
  // create variable
  $map = array();
  foreach($fieldArray as $field){
    switch($field['Type']){
      // checkboxes must be handled seperately
      case 'checkbox':
        foreach($field['ArrayTable'] as $row){
          $map[] = array($row['VarName'], $row['ColName']);
        }
        break;
      default:
        $map[] = array($field['VarName'], $field['ColName']);
    }
  }
  return $map;
}

/**
 * Sets the session variables from a received SQL array results rather than a post
 *
 * @param array $resultArray -- array containing all data that needs to be set to session
 * @param array $fieldArray -- array containing all fields in the form
 */
function setSessionVariablesFromArray($resultArray, $fieldArray) {
  foreach($fieldArray as $field){
    switch($field['Type']){
      // checkbox must be handled seperately
      case 'checkbox':
        foreach($field['ArrayTable'] as $row){
          $_SESSION[$row['VarName']] = $resultArray[$row['ColName']];
        }
        break;
      default:
        if(isset($resultArray[$field['ColName']])){
          $_SESSION[$field['VarName']] = $resultArray[$field['ColName']];
        }
    }
  }
}

/**
 * Clears all currently set session variables
 *
 * Increases site security and lowers chances of data being submitted to the database twice.
 */
function clearSessionVariables() {
  $_SESSION = array();
}

/**
 * Server side validation for form data submitted
 *
 * Returns an array of strings with all errors found from the data validation,
 * or an empty string if no errors found
 *
 * @param array $fieldArray -- the Array containing all of the fields to be validated
 */
function validatePostedData($fieldArray) {
  // array to store any errors found
  $errorList = array();
  foreach($fieldArray as $field){
    // Check to see if a non-checkbox variable is empty
    if($field['Type'] != 'checkbox' && !isset($_SESSION[$field['VarName']])){
      //if the variable is empty, first check to see if it is required before throwing error
      if($field['ReqField'] == TRUE) {
        $errorList[] = $field['Label'] . ' is a required field.';
      }
    }
    else {
      switch($field['Type']) {
        case 'text':
          if(!validateText($_SESSION[$field['VarName']])){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'textarea':
          if(!validateTextArea($_SESSION[$field['VarName']])){
           $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'number':
          if(!validateNumber($_SESSION[$field['VarName']])){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          // check to ensure it has no more than the correct number of decimal places
          elseif(decimalCheck($_SESSION[$field['VarName']]) > decimalCheck($field['Step'])){
            $errorList[] = 'Invalid number of decimal places in ' . $field['Label'] . ' field.';
          }
          break;
        case 'email':
          if(!validateEmail($_SESSION[$field['VarName']])){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'checkbox':
          validateCheckbox($errorList, $field['ArrayTable']);
          break;
        case 'date':
          if(!validateDate($_SESSION[$field['VarName']])){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'radio':
          if(!validateRadio($_SESSION[$field['VarName']], array_column($field['ArrayTable'], 'Value'))){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'dropdown':
          if(!validateRadio($_SESSION[$field['VarName']], array_column($field['ArrayTable'], 'Value'))){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        case 'yesno':
          // Yes/No is a radio button set to a 0 for no or a 1 for yes
          if(!validateRadio($_SESSION[$field['VarName']], array('0', '1'))){
            $errorList[] = 'Invalid entry in ' . $field['Label'] . ' field.';
          }
          break;
        default:
          throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
      }
    }
  }
  return $errorList;
}

/**
 * Builds an HTML form from the parameters provided
 *
 * @param string $title -- the title of the form to be created
 * @param string $target -- the name of the page to which the form will be submitted
 * @param array $fieldArray -- the array of fields to be used in building the form
 * @param array $errorList -- array containing any errors found on last form submission
 */
function buildForm($title, $target, $fieldArray, $errorList) {
  // html to start the right side content div and beginning of the form
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">';
  // if there were any errors passed in, display them here
  if(!empty($errorList)){
    $body .= '<div id="error" style="color:#990000;"><strong>The following errors occurred: <br/>';
    foreach($errorList as $error){
      $body .= $error . '<br/>';
    }
    $body .= '</strong><br/></div>';
  }
  $body .= '
        <h2 class="section-title">' . $title . '</h2>
        <form action="' . htmlspecialchars($target) . '" method="post">';

  // for loop to iterate over every item to be added to the form
  foreach($fieldArray as $field){
    switch($field['Type']) {
      case 'text':
        $body .= formText($field); break;
      case 'textarea':
        $body .= formTextArea($field); break;
      case 'number':
        $body .= formNumber($field); break;
      case 'email':
        $body .= formEmail($field); break;
      case 'checkbox':
        $body .= formCheckbox($field); break;
      case 'date':
        $body .= formDate($field); break;
      case 'radio':
        $body .= formRadio($field); break;
      case 'dropdown':
        $body .= formDropdown($field); break;
      case 'yesno':
        $body .= formYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }

  // HTML to end the form and right side content div
  $body .= '
          <input type="submit" value="Next Page" class="button" title="Inline button">
        </form>
      </div>
    </div>
  </div>';

  // Return all of this HTML to the main function
  return $body;
}

/**
 * Builds an HTML confirmation page from the parameters provided
 *
 * @param string $title -- the title of the confirmation page to be created
 * @param string $target -- the name of the page to which the data will be submitted
 * @param string $previous -- the name of the previous page, for back button creation
 * @param array $fieldArray -- the array of fields to be used in building the confirmation page
 */
function buildConfirmation($title, $target, $previous, $fieldArray) {
  // HTML to start the right-side content DIV
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">' . $title . '</h2>
        <h5>Please verify all information is correct before clicking Submit below.</h5>
        <hr>
        <dl>
          <dd>
            <ul>';
  // for loop to iterate over every item to be added to the form
  foreach($fieldArray as $field){
    switch($field['Type']) {
      case 'text':
        $body .= confirmBasic($field); break;
      case 'textarea':
        $body .= confirmTextArea($field); break;
      case 'number':
        $body .= confirmBasic($field); break;
      case 'email':
        $body .= confirmBasic($field); break;
      case 'checkbox':
        $body .= confirmCheckbox($field); break;
      case 'date':
        $body .= confirmBasic($field); break;
      case 'radio':
        $body .= confirmRadio($field); break;
      case 'dropdown':
        $body .= confirmRadio($field); break;
      case 'yesno':
        $body .= confirmYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }
  // HTML to end the right-side content DIV
  $body .= '</ul>
            </dd>
            </dl>
            <a href="' . $target . '" class="button" title="Inline button">Confirm Information and Submit</a><br/>
            <a href="' . $previous . '" class="button" title="Inline button">Return to Last Page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML safety page -- to be used when a page is reached in error
 */
function buildSafetyPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Error Page</h2>
        <h5>This page has been reached in error, likely by using the back button during the submission process.  Please use the button below to return to your home page.</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML success page for the job submission page
 */
function buildSubmitSuccessPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Job Submitted</h2>
        <h5>This position has been submitted to HR for approval.  You should receive a confirmation E-Mail shortly.<br/><br/>Thank you!</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML success page for the job approval page
 */
function buildApproveSuccessPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Position Approved</h2>
        <h5>This position has been approved.  An E-Mail confirmation has been sent to the position submitter.<br/><br/>Thank you!</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML success page for the position edit page
 */
function buildEditSuccessPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Position Edited</h2>
        <h5>This position has been edited.</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML success page for the application page
 */
function buildApplicationSuccessPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Application Submitted</h2>
        <h5>Your application has been submitted.  You should receive a confirmation E-Mail shortly.<br/><br/>Thank you!</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to the Job Selection menu</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an HTML success page for the job posting page
 */
function buildPostingSuccessPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Job Posting Created</h2>
        <h5>This posting has been created.  It should now appear on your open postings on your home page.</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds the standard IU header
 *
 * Includes links to all necessary CSS and minified JS required for the page to
 * be styled and functional
 *
 * @param string $site -- Label for which site is calling this function
 */
function buildHeader($site) {
  // standard IU header, CSS, and JS
  $header = '<!DOCTYPE html>
  <html lang="en" dir="ltr" prefix="content: http://purl.org/rss/1.0/modules/content/  dc: http://purl.org/dc/terms/  foaf: http://xmlns.com/foaf/0.1/  og: http://ogp.me/ns#  rdfs: http://www.w3.org/2000/01/rdf-schema#  schema: http://schema.org/  sioc: http://rdfs.org/sioc/ns#  sioct: http://rdfs.org/sioc/types#  skos: http://www.w3.org/2004/02/skos/core#  xsd: http://www.w3.org/2001/XMLSchema# ">
  <head>
    <link rel="stylesheet" href="https://assets.uits.iu.edu/css/rivet/1.1.0/rivet.min.css">
    <link href="https://fonts.iu.edu/style.css?family=BentonSans:regular,bold%7CBentonSansCond:regular%7CGeorgiaPro:regular" rel="stylesheet">
    <link href="https://assets.iu.edu/web/fonts/icon-font.css" rel="stylesheet">
    <link href="https://assets.iu.edu/web/3.x/css/iu-framework.min.css" rel="stylesheet" type="text/css">
    <link href="https://assets.iu.edu/brand/3.x/brand.css" rel="stylesheet" type="text/css">
    <link href="https://assets.iu.edu/search/3.x/search.css" rel="stylesheet" type="text/css">
    <link href="https://assets.iu.edu/libs/highlight/styles/github.css" rel="stylesheet" type="text/css">
    <link href="https://styleguide.iu.edu/_assets/css/site.css" rel="stylesheet" type="text/css">
    <link href="..\PHPFiles\tableStyling.css" rel="stylesheet" type="text/css">
    <script src="https://assets.iu.edu/web/1.5/libs/modernizr.min.js"></script>
    <link href="https://assets.iu.edu/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <meta charset="utf-8">
    <title>' . $site . '</title>
  </head>
  <body class="mahogany no-banner has-page-title">
    <div id="branding-bar" class="iu" itemscope="itemscope" itemtype="http://schema.org/CollegeOrUniversity" role="complementary" aria-labelledby="campus-name">
      <div class="row pad">
        <img src="https://assets.iu.edu/brand/3.x/trident-large.png" alt="IU" />
        <p id="iu-campus">
          <a href="https://www.iu.edu" title="Indiana University">
            <span id="campus-name" class="show-on-desktop" itemprop="name">Indiana University Bloomington</span>
            <span class="show-on-tablet" itemprop="name">Indiana University Bloomington</span>
            <span class="show-on-mobile" itemprop="name">IU Bloomington</span>
          </a>
        </p>
      </div>
    </div>';
  return $header;
}

/**
 * Builds the left-hand menu for the supervisor portal
 *
 * @param string $site -- the cite calling this page, for labelling
 * @param int $current -- the current page, for styling
 * @param array $menuArray -- array containing page target and menu label pairs
 * @param int $stop -- the point at which the menu should stop building (default NULL means entire menu is built)
 */
function buildMenu($site, $current, $menuArray, $stop = NULL) {
  $menu = '<header class="site-header" itemscope="itemscope" itemtype="http://schema.org/CollegeOrUniversity" role="banner">
    <div class="row pad">
      <h1><a class="title" href="main.php" itemprop="department">' . $site . '</a></h1>
    </div>
  </header>
  <hr>
  <div class="section-nav show-for-large" id="section-nav">
    <div class="row">
      <nav itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" aria-label="Section navigation" data-parent-url="/visual-style/index">
        <ul>';
  // code to stop the menu early, used when building application menu a piece at a time
  if($stop == NULL){
    $loopLength = count($menuArray);
  }
  else{
    $loopLength = $stop + 1;
  }
  // for loop to iterate over menu
  for($x = 0; $x < $loopLength; $x++){
    $menuItem = '<li class=""><a href="' . $menuArray[$x][0] . '" itemprop="url" class="';
    if($x == $current){
      $menuItem .= 'current-trail current';
    }
    $menuItem .= '"><span itemprop="name">' . $menuArray[$x][1] . '</span></a></li>';
    $menu .= $menuItem;
  }
  $menu .= '</ul>
      </nav>
    </div>
  </div>';
  return $menu;
}

/**
 * Builds the standard IU footer required on every page
 *
 * Includes JS files required for page functionality
 */
function buildFooter() {
  $footer = '  <footer id="footer" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/CollegeOrUniversity">
      <div class="row pad">

        <p class="tagline">Fulfilling <span>the</span> Promise</p>

        <p class="signature">
          <a href="https://www.iu.edu" class="signature-link signature-img"><img src="IUlogo.jpg" alt="Indiana University" /></a>
        </p>

        <p class="copyright">
          <a href="https://www.iu.edu/copyright/index.html">Copyright</a> &#169; 2018 <span class="line-break-small">The Trustees of <a href="https://www.iu.edu/" itemprop="url"><span itemprop="name">Indiana University</span></a></span><span class="hide-on-mobile"> | </span><span class="line-break"><a href="/privacy" id="privacy-policy-link">Privacy Notice</a> | <a href="https://accessibility.iu.edu/help" id="accessibility-link" title="Having trouble accessing this web page because of a disability? Visit this page for assistance.">Accessibility Help</a></span>
        </p>
      </div>
    </footer>
    <!-- Include Javascript -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="..\PHPFiles\clickablerows.js"></script>
    <script src="https://assets.iu.edu/web/3.x/js/iu-framework.min.js"></script>
    <script src="https://assets.iu.edu/search/3.x/search.js"></script>
    <script src="https://styleguide.iu.edu/_assets/js/site.js"></script>
    <script src="https://assets.uits.iu.edu/javascript/rivet/1.1.0/rivet.min.js"></script>
  </body>
  </html>';
  return $footer;
}

/**
 * Builds the right-side data for the HR Home page
 *
 * @param string $table -- HTML for the table to be printed into the page
 */
function buildHRHomePage($table) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Positions Pending Approval</h2>';
  $body .= $table;
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor Home page
 *
 * @param string $table -- HTML for the table to be printed into the page
 */
function buildSupervisorHomePage($table) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Your Open Job Postings</h2>';
  $body .= $table;
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor Applicant List page
 *
 * @param string $position -- Name of the position
 * @param string $table -- HTML for the table to be printed into the page
 * @param string $id -- jobID for current position
 */
function buildSupervisorApplicantList($position, $table = '0 results<br/><br/><br/><br/><br/><br/>', $positionID) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">' . $position . '</h2>';
  $body .= $table;
  $body .= '<a href="closePosting.php?ID=' . $positionID .  '" class="button" title="Inline button">Close this Posting</a><br/>';
  $body .= '<a href="main.php" class="button" title="Inline button">Return to Open Postings</a><br/></form>';
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor Applicant List page
 *
 * @param array $results -- array containing result data for applicant
 */
function buildSupervisorApplicantData($results) {
  // HTML to start the right-side content DIV
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">' . $results["FirstName"] . ' ' . $results["LastName"] . '</h2>
          <h5>Application for: ' . $results["Title"] . '</h5>';
  if($results['Hired'] == TRUE){
    $body .= '<h3><font color="red">You hired this applicant on ' . fixDate($results["HireDate"]) . '.</font></h3>';
  }
  $body .= '
          <hr>';
  // Load the fields needed for personal information
  $personalFields = loadAppPersonalFields();
  // Set some session variables to allow confirmation pieces to work
  setSessionVariablesFromArray($results, $personalFields);
  $body .= '<dl>
           <dt><u>Personal Information</u></dt>
           <dd>
             <ul class="no-bullet">';
  // Build the Personal Information section
  foreach($personalFields as $field){
    switch($field['Type']) {
      case 'text':
        $body .= confirmBasic($field); break;
      case 'textarea':
        $body .= confirmTextArea($field); break;
      case 'number':
        $body .= confirmBasic($field); break;
      case 'email':
        $body .= confirmBasic($field); break;
      case 'checkbox':
        $body .= confirmCheckbox($field); break;
      case 'date':
        $body .= confirmBasic($field); break;
      case 'radio':
        $body .= confirmRadio($field); break;
      case 'dropdown':
        $body .= confirmRadio($field); break;
      case 'yesno':
        $body .= confirmYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }
  $body .= '</ul>
            </dd>
            </dl>';
  // Availability section
  $body .= '<hr><dl>
             <dt><u>Availability Information</u></dt>
             <dd>
             <ul class="no-bullet">';
  if(isset($results['AvailHours'])){
     $body .= '<li><strong>Available Hours:</strong> ' . $results['AvailHours'] . '</li>';
  }
  // Individual Day Availability
  $dayFields = loadAppDayFields();
  foreach($dayFields as $day){
    if(isset($results[$day['Abbrev'] . 'Start1']) && !empty($results[$day['Abbrev'] . 'Start1'])){
      $body .= '<li><strong>' . $day['Day'] . ':</strong> ' . fixtime($results[$day['Abbrev'] . 'Start1']) .  '-' . fixtime($results[$day['Abbrev'] . 'End1']);
      if(isset($results[$day['Abbrev'] . 'Start2']) && !empty($results[$day['Abbrev'] . 'Start2'])){
        $body .= ', ' . fixtime($results[$day['Abbrev'] . 'Start2']) .  '-' . fixtime($results[$day['Abbrev'] . 'End2']);
      }
      $body .= '</li>';
    }
  }
  $body .= '</ul>
            </dd>
            </dl>';
  // Work History Section
  $body .= '<hr><dl>
             <dt><u>Work History</u></dt>
             <dd>';
  if($results['WorkHistory'] == FALSE){
    $body .= 'No Work History entered -- see uploaded Resume';
  }
  $body .= '</dd>
            </dl>';
  // Documents section
  $body .= '<hr><dl>
             <dt><u>Documents</u></dt>
             <dd>';
  // TO DO -- Documents logic
  $body .= 'This is where the Documents will be when the logic is complete.';
  $body .= '</dd>
            </dl>';
  // Buttons to do things with this information
  $body .= '<hr>';
  // Mark student as hired/not hired
  if($results['Hired'] == FALSE){
    $body .= '<a href="hired.php?ID=' . $results['ApplicationID'] . '" class="button" title="Inline button">Mark Applicant as Hired</a><br/>';
  }
  else if($results['Hired'] == TRUE){
    $body .= '<a href="notHired.php?ID=' . $results['ApplicationID'] . '" class="button" title="Inline button">Mark Applicant as Not Hired</a><br/>';
  }
  $body .= '<a href="showApps.php?ID=' . $results['PostingID'] . '" class="button" title="Inline button">Return to Applicant List</a><br/>';
  // Ending div tags
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the page to confirm a posting closing
 *
 * @param string $title -- Title of the job being closed
 * @param array $rejList -- List of applicants from which to choose for rejection letter
 * @param string $id -- The postingID of the posting that is closing
 */
function buildSupervisorClosingPage($title, $rejList, $id){
  // build the HTML
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Closing Posting for:<br/>' . $title . '</h2>
          <h4>A list of E-Mail addresses and a form letter will be generated.</h4>
          <hr>';
  $body .= '<form action="closedIt.php" method="post">';
  $body .= '<div class="form-item">
    <fieldset class="bare">
      <legend class="label"><h5>Applicants to be E-Mailed:</h5></legend>
      <div class="form-item-input">';
  // iterate over list of rejected applicants
  foreach($rejList as $rejected){
    // checkbox hack -- autosets every checkbox name to 0 by default, will change to a 1 when checked and submitted
    $body .= '<input type="hidden" name="' . $rejected['ApplicationID'] . 'app" value="0" />';
    $body .= '<input type="checkbox" name="' . $rejected['ApplicationID'] . 'app" id="' . $rejected['ApplicationID'] . '" value="1" checked>';
    $body .= '<label for="' . $rejected['ApplicationID'] . '">' . $rejected['FirstName'] . ' ' . $rejected['LastName'] . '</label><br/>';
  }
  // set hidden variable with the postingID
  $body .= '<input type="hidden" name="PostingID" value="' . $id .  '"';
  $body .= '<br/><input type="submit" value="Close Posting and Generate E-Mail" class="button" title="Inline button">';
  $body .= '</form>';
  $body .= '
      </div>
    </fieldset>
  </div>';

  if(empty($rejList)){
    $x = 1;
  }
  $body .= '</div></div></div>';
  return $body;
}

/**
 * Builds the right-side data for the closing success page
 *
 * @param string $id -- The postingID of the posting that is closing
 */
function buildClosingSuccessPage($id){
  $title = 'TestJob';
  // build the HTML
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Closed Posting for:<br/>' . $title . '</h2>';
  $body .= '</div></div></div>';
  return $body;
}

/**
 * Builds the right-side data for the HR approval page
 *
 * @param array $results -- associative array containing all data pulled from
 *                          the ID to be approved
 */
function buildHRApprovePage($results) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();

  // take the array of results and set them into session variables
  setSessionVariablesFromArray($results, $submitFields);

  // build the HTML
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Approve Position</h2>
          <hr>
          <dl>
            <dd>
              <ul>';
  foreach($submitFields as $field){
    switch($field['Type']) {
      case 'text':
        $body .= confirmBasic($field); break;
      case 'textarea':
        $body .= confirmTextArea($field); break;
      case 'number':
        $body .= confirmBasic($field); break;
      case 'email':
        $body .= confirmBasic($field); break;
      case 'checkbox':
        $body .= confirmCheckbox($field); break;
      case 'date':
        $body .= confirmBasic($field); break;
      case 'radio':
        $body .= confirmRadio($field); break;
      case 'dropdown':
        $body .= confirmRadio($field); break;
      case 'yesno':
        $body .= confirmYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }
  $body .= '</ul>
            </dd>
            </dl>
            <a href="approved.php" class="button" title="Inline button">Approve Position</a><br/>
            <a href="approveEdit.php" class="button" title="Inline button">Edit Position Before Approval</a></div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the HR approval page
 *
 * @param array $results -- associative array containing all data pulled from
 *                          the ID to be approved
 */
function buildApplicationPostingPage($results) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();

  // take the array of results and set them into session variables
  setSessionVariablesFromArray($results, $submitFields);

  // build the HTML
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Job Details</h2>
          <hr>
          <dl>
            <dd>
              <ul>';
  foreach($submitFields as $field){
    // only add this field if it is supposed to be seen by the applicant
    if($field['AppField'] == TRUE){
      switch($field['Type']) {
        case 'text':
          $body .= confirmBasic($field); break;
        case 'textarea':
          $body .= confirmTextArea($field); break;
        case 'number':
          $body .= confirmBasic($field); break;
        case 'email':
          $body .= confirmBasic($field); break;
        case 'checkbox':
          $body .= confirmCheckbox($field); break;
        case 'date':
          $body .= confirmBasic($field); break;
        case 'radio':
          $body .= confirmRadio($field); break;
        case 'dropdown':
          $body .= confirmRadio($field); break;
        case 'yesno':
          $body .= confirmYesNo($field); break;
        default:
          // this case should only come up in testing
          throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
      }
    }
  }
  $body .= '</ul>
            </dd>
            </dl>
            <form action="personalinfo.php" method=post>
            <input type="submit" value="Apply for this Position" class="button" title="Inline button">
            </form>
            <a href="main.php" class="button" title="Inline button">Return to Open Positions</a></div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Application documents page
 *
 * @param array $results -- associative array containing all data pulled from
 *                          the ID to be approved
 */
function buildApplicationAvailabilityPage($errorList = []){
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">';
  // if there were any errors passed in, display them here
  if(!empty($errorList)){
    $body .= '<div id="error" style="color:#990000;"><strong>The following errors occurred: <br/>';
    foreach($errorList as $error){
      $body .= $error . '<br/>';
    }
    $body .= '</strong><br/></div>';
  }
  $body .= '
        <h2 class="section-title">Your Availability</h2>
        <form action="workHistory.php" method="post">
        <div class="form-item">
              <div class="form-item-label">
                <label for="availhours">Number of hours available to work per week:</label>
              </div>
              <div class="form-item-input">
                <input type="number" name="availhours" id="availhours" required';
  $body .= sessionCheck('availhours');
  $body .= '>
              </div>
            </div>';
  // build the table
  $body .= '<table class="mobile-labels">
                <caption>Available Work Hours<br>(Two time periods provided for those who wish to work two shifts in a day)</caption>
                <thead>
                    <tr>
                        <th scope="col">Days</th>
                        <th scope="col">Start 1</th>
                        <th scope="col">End 1</th>
                        <th scope="col">Start 2</th>
                        <th scope="col">End 2</th>
                    </tr>
                </thead>
                <tbody>';

  // Iterate over days of the week
  $dayFields = loadAppDayFields();
  foreach($dayFields as $day){
    $body .= '<tr>
                        <!-- each td should have a data-label to match the th -->
                        <td data-label="Days">' . $day['Day'] . '</td>
                        <td data-label="Start 1">
                          <div class="form-item">
                            <div class="form-item-input">
                              <input type="time" name="' . $day['Abbrev'] . 'start1" id="' . $day['Abbrev'] . 'start1"';
    $body .= sessionCheck($day['Abbrev'] . 'start1');
    $body .= '>
                            </div>
                          </div>
                        </td>
                        <td data-label="End 1">
                          <div class="form-item">
                            <div class="form-item-input">
                              <input type="time" name="' . $day['Abbrev'] . 'end1" id="' . $day['Abbrev'] . 'end1"';
    $body .= sessionCheck($day['Abbrev'] . 'end1');
    $body .= '>
                            </div>
                          </div>
                        </td>
                        <td data-label="Start 2">
                          <div class="form-item">
                            <div class="form-item-input">
                              <input type="time" name="' . $day['Abbrev'] . 'start2" id="' . $day['Abbrev'] . 'start2"';
    $body .= sessionCheck($day['Abbrev'] . 'start2');
    $body .= '>
                            </div>
                          </div>
                        </td>
                        <td data-label="End 2">
                          <div class="form-item">
                            <div class="form-item-input">
                              <input type="time" name="' . $day['Abbrev'] . 'end2" id="' . $day['Abbrev'] . 'end2"';
    $body .= sessionCheck($day['Abbrev'] . 'end2');
    $body .= '>
                            </div>
                          </div>
                        </td>
                    </tr>
';
  }

  $body .= '</tbody>
            </table>
            <br>';

  // HTML to end the form and right side content div
  $body .= '
          <input type="submit" value="Next Page" class="button" title="Inline button">
        </form>
      </div>
    </div>
  </div>';
  return $body;
}

/**
 * Builds and Returns the HTML for the work history page of the student application form
 * buildForm function requires only the header, the target page, and the array of
 * fields to be used in the form
 *
 * If there is an error list submitted, will include the errors in red at the top
 */
function buildApplicationWorkHistoryPage($errorList = []){
  $workHistoryFields = loadAppWorkHistoryFields();
  // html to start the right side content div and beginning of the form
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Work History</h2>';
  $body .= '
        <h5>Please enter your work history below -- maximum three jobs, most recent first.</h5>
        <form action="confirmApp.php" method="post">';
  // We are creating three sections for job history
  for($x = 1; $x <= 3; $x++){
    $body .= '<hr><h4>Job ' . $x . '</h4>';
    // for loop to iterate over every item to be added to the form
    foreach($workHistoryFields as $field){
      // each field in the array must be appended with a number indicating which job it is
      $field['VarName'] = $field['VarName'] . $x;
      // after appending, then apply
      switch($field['Type']) {
        case 'text':
          $body .= formText($field); break;
        case 'textarea':
          $body .= formTextArea($field); break;
        case 'number':
          $body .= formNumber($field); break;
        case 'email':
          $body .= formEmail($field); break;
        case 'checkbox':
          $body .= formCheckbox($field); break;
        case 'date':
          $body .= formDate($field); break;
        case 'radio':
          $body .= formRadio($field); break;
        case 'dropdown':
          $body .= formRadio($field); break;
        case 'yesno':
          $body .= formYesNo($field); break;
        default:
          // this case should only come up in testing
          throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
      }
    }
  }
  // HTML to end the form and right side content div
  $body .= '
          <input type="submit" value="Next Page" class="button" title="Inline button">
        </form>
      </div>
    </div>
  </div>';

  // Return all of this HTML to the main function
  return $body;
  }

/**
 * Builds the right-side data for the Supervisor Portal page to confirm a new posting
 *
 * @param array $results -- associative array containing all data pulled from
 *                          the ID to be approved
 * @param array $errorList -- array of any errors detected while building page
 *                            defaults to empty if no errors are passed
 */
function buildSupervisorPostingPage($results, $errorList = []) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();

  // take the array of results and set them into session variables
  setSessionVariablesFromArray($results, $submitFields);

  // build the HTML
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">';
  // if there were any errors passed in, display them here
  if(!empty($errorList)){
    $body .= '<div id="error" style="color:#990000;"><strong>The following errors occurred: <br/>';
    foreach($errorList as $error){
      $body .= $error . '<br/>';
    }
    $body .= '</strong><br/></div>';
  }
  $body .= '
          <h2 class="section-title">New Job Posting</h2>
          <hr>
          <dl>
            <dd>
              <ul>';
  foreach($submitFields as $field){
    switch($field['Type']) {
      case 'text':
        $body .= confirmBasic($field); break;
      case 'textarea':
        $body .= confirmTextArea($field); break;
      case 'number':
        $body .= confirmBasic($field); break;
      case 'email':
        $body .= confirmBasic($field); break;
      case 'checkbox':
        $body .= confirmCheckbox($field); break;
      case 'date':
        $body .= confirmBasic($field); break;
      case 'radio':
        $body .= confirmRadio($field); break;
      case 'dropdown':
        $body .= confirmRadio($field); break;
      case 'yesno':
        $body .= confirmYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }
  $body .= '</ul>
            </dd>
            </dl>
            <hr>
            <form action="postIt.php" method="post">';
  // create array to use to call formDate
  $dateArray = array(
    'Label' => 'Posting End Date',
    'VarName' => 'PostEndDate',
    'ReqField' => TRUE
  );
  $body .= formDate($dateArray);
  $body .= '<input type="submit" value="Create New Job Posting" class="button" title="Inline button">
          </form>
        </div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor Portal page to confirm a new posting
 *
 * @param array $results -- associative array containing all data pulled from
 *                          the ID to be approved
 * @param array $errorList -- array of any errors detected while building page
 *                            defaults to empty if no errors are passed
 */
function buildApplicationConfirmPage() {
  // Load the fields needed for confirmation page
  $personalFields = loadAppPersonalFields();
  // Availability Info goes here
  // HTML to start the right-side content DIV
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Confirm Application</h2>
        <h5>Please verify all information is correct before clicking Submit below.</h5>
        <hr>
        <dl>
          <dt>Personal Information</dt>
          <dd>
            <ul class="no-bullet">';
  // Build the Personal Information section
  foreach($personalFields as $field){
    switch($field['Type']) {
      case 'text':
        $body .= confirmBasic($field); break;
      case 'textarea':
        $body .= confirmTextArea($field); break;
      case 'number':
        $body .= confirmBasic($field); break;
      case 'email':
        $body .= confirmBasic($field); break;
      case 'checkbox':
        $body .= confirmCheckbox($field); break;
      case 'date':
        $body .= confirmBasic($field); break;
      case 'radio':
        $body .= confirmRadio($field); break;
      case 'dropdown':
        $body .= confirmRadio($field); break;
      case 'yesno':
        $body .= confirmYesNo($field); break;
      default:
        // this case should only come up in testing
        throw new Exception('One of your fields does not have the correct formatting -- please check your array.');
    }
  }
  $body .= '</ul>
            </dd>
            </dl>';
  // Availability section
  $body .= '<hr>
             <dl>
             <dt>Availability Information</dt>
             <dd>
             <ul class="no-bullet">';
  if(isset($_SESSION['availhours'])){
     $body .= '<li><strong>Available Hours:</strong> ' . $_SESSION['availhours'] . '</li>';
  }
  // Individual Day Availability
  $dayFields = loadAppDayFields();
  foreach($dayFields as $day){
    if(isset($_SESSION[$day['Abbrev'] . 'start1']) && !empty($_SESSION[$day['Abbrev'] . 'start1'])){
      $body .= '<li><strong>' . $day['Day'] . ':</strong> ' . fixtime($_SESSION[$day['Abbrev'] . 'start1']) .  '-' . fixtime($_SESSION[$day['Abbrev'] . 'end1']);
      if(isset($_SESSION[$day['Abbrev'] . 'start2']) && !empty($_SESSION[$day['Abbrev'] . 'start2'])){
        $body .= ', ' . fixtime($_SESSION[$day['Abbrev'] . 'start2']) .  '-' . fixtime($_SESSION[$day['Abbrev'] . 'end2']);
      }
      $body .= '</li>';
    }
  }
  $body .= '</ul>
            </dd>
            </dl>';
  // Work History section
  $body .= '<hr>
             <dl>
             <dt>Work History</dt>
             <dd>
             <ul class="no-bullet">';
  // If resume is required, Work History is skipped
  if($_SESSION['ResumeReq'] == TRUE){
    $body .= '<li><strong>To be included on submitted resume</strong></li>';
  }
  // Otherwise, logic for loading Work History
  else{
    $workHistoryFields = loadAppWorkHistoryFields();

  }
  $body .= '</ul>
            </dd>
            </dl>';
  // HTML to end the right-side content DIV
  $body .= '<a href="submitApp.php" class="button" title="Inline button">Confirm Information and Submit</a><br/>
            <a href="personalInfo.php" class="button" title="Inline button">Return to Start of Application</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds an error page for the HR Approval process if an invalid ID is sent to the approval page
 */
function buildPositionErrorPage() {
  $body = '<div class="bg-none section" id="content">
    <div class="row">
      <div class="layout">
        <h2 class="section-title">Error Page</h2>
        <h5>This page has been reached in error -- the Position ID is invalid or missing.<br/><br/>Please use the button below to return to your home page.</h5>
        <hr>
            <a href="main.php" class="button" title="Inline button">Return to your home page</a>
          </div>
        </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor New Posting page
 *
 * @param string $table -- HTML for the table to be printed into the page
 */
function buildSupervisorNewPostingPage($table) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">New Job Posting</h2>';
  $body .= $table;
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Supervisor Edit Posting page
 *
 * @param string $table -- HTML for the table to be printed into the page
 */
function buildSupervisorEditPositionPage($table) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Edit Position</h2>';
  $body .= $table;
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds the right-side data for the Application Job Selection page
 *
 * @param string $table -- HTML for the table to be printed into the page
 */
function buildApplicationJobSelectionPage($table) {
  $body = '  <div class="bg-none section" id="content">
      <div class="row">
        <div class="layout">
          <h2 class="section-title">Open Library Positions</h2>';
  $body .= $table;
  $body .= '</div>
      </div>
    </div>';
  return $body;
}

/**
 * Builds and Returns the HTML for the first page of the student application form
 * buildForm function requires only the header, the target page, and the array of
 * fields to be used in the form
 *
 * If there is an error list submitted, will include the errors in red at the top
 */
function buildApplicationPersonalPage($errorList = []){
  $personalFields = loadAppPersonalFields();
  return buildForm('Personal Information', 'availability.php', $personalFields, $errorList);
}

/**
 * Builds and Returns the HTML for the Job Submission Form
 * buildForm function requires only the header, the target page, and the array of
 * fields to be used in the form
 *
 * If there is an error list submitted, will include the errors in red at the top
 */
function buildSubmitForm($errorList = []) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildForm('Submit a Job', 'jobSubmit2.php', $submitFields, $errorList);
}

/**
 * Build and Returns the HTML for the Position Edit form in the HR Portal
 */
function buildHRApproveEditPage($errorList = []) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildForm('Approve Position', 'approveEditConfirm.php', $submitFields, $errorList);
}

/**
 * Builds and Returns the HTML for the Job Submission Confirmation Page
 * buildConfirmation function requires header, target page when submitted,
 * address of previous page, and array of fields to be displayed
 */
function buildSubmitConfirm() {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildConfirmation('Submit a Job', 'jobSubmit3.php', 'jobSubmit.php', $submitFields);
}

/**
 * Builds and Returns the HTML for the Job Submission Confirmation Page
 * buildConfirmation function requires header, target page when submitted,
 * address of previous page, and array of fields to be displayed
 */
function buildHRApproveEditConfirm() {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildConfirmation('Approve Position', 'approvedEdit.php', 'approveEdit.php', $submitFields);
}

/**
 * Build and Returns the HTML for the Position Edit form in the Supervisor Portal
 */
function buildSupervisorEditPage($errorList = []) {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildForm('Edit Position', 'editConfirm.php', $submitFields, $errorList);
}

/**
 * Builds and Returns the HTML for the Job Submission Confirmation Page
 * buildConfirmation function requires header, target page when submitted,
 * address of previous page, and array of fields to be displayed
 */
function buildSupervisorEditConfirm() {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return buildConfirmation('Edit Position', 'commitEdit.php', 'editPosition.php', $submitFields);
}

/**
 * Checks the data posted to the Job Submission form to ensure data is
 * correctly formatted based on the data type sent
 */
function validateJobSubmitData() {
  // load the job submit fields into a variable from a MySQL table
  $submitFields = loadSubmitFields();
  return validatePostedData($submitFields);
}

/**
 * Checks the personal data posted to the Job Application form to ensure data is
 * correctly formatted based on the data type sent
 */
function validateApplicationPersonalInfo() {
  // load the job submit fields into a variable from a MySQL table
  $personalFields = loadAppPersonalFields();
  return validatePostedData($personalFields);
}

/**
 * Checks the work history data posted to the Job Application form to ensure data is
 * correctly formatted based on the data type sent
 */
function validateApplicationWorkHistory() {
  // load the job submit fields into a variable from a MySQL table
  $workHistoryFields = loadAppWorkHistoryFields();

  // TO DO -- Logic for Work History
  return array();
}

/**
 * Builds the HTML Header for the Application portal
 */
function buildApplicationHeader(){
  return buildHeader('Temporary Employee Job Application');
}

/**
 * Builds the HTML Header for the Supervisor portal
 */
function buildSupervisorHeader(){
  return buildHeader('Supervisor Portal');
}

/**
 * Builds the HTML Header for the HR portal
 */
function buildHRHeader(){
  return buildHeader('HR Portal');
}

/**
 * Builds the left-hand menu for the Application Portal Home Page
 */
function buildApplicationHomeMenu($stop = NULL){
  return buildMenu('Temporary Employee Job Application', '0', STUDENTMENU, $stop);
}

/**
 * Builds the left-hand menu for the Application Portal Personal Information Page
 */
function buildApplicationPersonalMenu($stop = NULL){
  return buildMenu('Temporary Employee Job Application', '1', STUDENTMENU, $stop);
}

/**
 * Builds the left-hand menu for the Application Portal Availability Page
 */
function buildApplicationAvailabilityMenu($stop = NULL){
  return buildMenu('Temporary Employee Job Application', '2', STUDENTMENU, $stop);
}

/**
 * Builds the left-hand menu for the Application Portal Work History Page
 */
function buildApplicationWHMenu($stop = NULL){
  return buildMenu('Temporary Employee Job Application', '3', STUDENTMENU, $stop);
}

/**
 * Builds the left-hand menu for the Application Portal Work History Page
 */
function buildApplicationConfirmMenu($stop = NULL){
  return buildMenu('Temporary Employee Job Application', '4', STUDENTMENU, $stop);
}

/**
 * Builds the left-hand menu for the HR Portal Home Page
 */
function buildHRHomeMenu(){
  return buildMenu('HR Portal', '0', HRMENU);
}

/**
 * Builds the left-hand menu for the Supervisor Portal Home Page
 */
function buildSupervisorHomeMenu(){
  return buildMenu('Supervisor Portal', '0', SUPERMENU);
}

/**
 * Builds the left-hand menu for the Supervisor Portal New Posting Page
 */
function buildSupervisorNewPostingMenu(){
  return buildMenu('Supervisor Portal', '1', SUPERMENU);
}

/**
 * Builds the left-hand menu for the Supervisor Portal Edit Posting Page
 */
function buildSupervisorEditPositionMenu(){
  return buildMenu('Supervisor Portal', '2', SUPERMENU);
}

/**
 * Builds the left-hand menu for the Submission pages of the Supervisor Portal
 */
function buildSupervisorSubmitMenu(){
  return buildMenu('Supervisor Portal', '3', SUPERMENU);
}

/**
 * Performs all functions necessary to get pages started.
 */
function initializePage() {
  session_start();

  // TESTING ONLY -- Set a variable for the current userName
  $_ENV["REMOTE_USER"] = 'cstuller';
}

?>
