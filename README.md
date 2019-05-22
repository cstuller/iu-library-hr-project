# iu-library-hr-project
PHP code for the Temporary Employee Application project for the IU Libraries

This is the code for the nearly complete project for IU Libraries HR.
Basic Functionality is in place, all that is missing is archival features.

Setting up the Database:

Run the SQL statements in these files, in the SQL folder in this order:
createTables.sql–Creates all of the tables for the actual application/applicant/position etc data for the site
testData.sql–Some quick test data created in mockaroo for the site
constantTables.sql–Tables for all of the dropdown menus and radio buttons that need to be easily editable

All functions required for the website to run are inside these two files, in the PHPFiles folder:
pageFunctions.php–All functions related to building the site HTML
SQLFunctions.php–All functions related to building and executing SQL queries on the database

Inside the PHPFiles folder is also:
tableStyling.css and clickableRows.js–Two small scripts related to formatting tables to make the rows clickable, and to highlight on mouseover
credentials.php–holds the credentials for connecting to the database. Change these to connect to wherever you put the data

The three folders: Application, HR Portal, and Supervisor Portal -- contain the PHP files to build each page of their respective portals. They also each contain:
IULogo.jpg–Just the IU logo for the footer.
pageBuilder.php–Contains the paths to where the main pageFunctions.php and SQLFunctions.php are kept. If those two files are moved, the 3 pageBuilder.php files will need to be updated to their new locations

For each Portal, main.php is designed to be the landing page. All functionality of each portal can be accessed from there.

Webserve has several environment variables automatically set.  The one that is used for this site is:

$_ENV[‘REMOTE_USER’]

This variable grabs the current CAS authenticated username from Webserve. As the site has not yet been tested on web, as part of the InitializePage() function at the top of every page, I have been manually setting this variable to the username I am testing (currently line 2117 of pageFunctions.php). Your environment will need to either use this variable as well, or change any instances (currently 7 instances in SQLFunctions.php) to wherever you hold the username. The current user is required mostly for the Supervisor Portal, as it is used to decide which positions and postings the current user has access to. This line can then be removed once the site is live/in testing
