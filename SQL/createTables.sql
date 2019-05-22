/* LibPosition table is the actual jobs.
Instead of a submissions table, will use the
"Approved" switch to determine if HR approved it */
CREATE TABLE LibPosition(
  JobID INT UNIQUE NOT NULL AUTO_INCREMENT,
  Title VARCHAR(50) NOT NULL,
  Department VARCHAR(50) NOT NULL,
  Classification VARCHAR(50) DEFAULT 'UNSPECIFIED',
  EMail VARCHAR(50) NOT NULL, /* When putting E-Mail in SQL table, it MUST be wrapped in quotes */
  Pay DECIMAL(6,2) DEFAULT '0.00',
  Hours INT NOT NULL,
  EveWeekend BOOLEAN NOT NULL, /* Evenings/Weekends */
  StudOnly BOOLEAN NOT NULL, /* Students Only */
  Description TEXT, /* Change to VARCHAR when character limit is known */
  Qualifications TEXT, /* change to VARCHAR when character limit is known */
  CoverReq BOOLEAN NOT NULL DEFAULT 0, /* These 5 variables for the required documents  */
  ResumeReq BOOLEAN NOT NULL DEFAULT 0,
  TransReq BOOLEAN NOT NULL DEFAULT 0,
  WritingReq BOOLEAN NOT NULL DEFAULT 0,
  OtherReq BOOLEAN NOT NULL DEFAULT 0,
  SpecialInst TEXT, /* Instructions for the "Other documents" */
  Owner VARCHAR(50), /* To be implemented with ADS integration -- name of the owner/creator of the position */
  Approved BOOLEAN NOT NULL DEFAULT 0 /* Switch for if HR approved the position */
  Enabled BOOLEAN NOT NULL DEFAULT 1 /* Switch for if HR has Enabled/Disabled position */
) ENGINE=INNODB;

/* Posting takes a Position from the Positions table, and posts it to the public
page for hiring */
CREATE TABLE LibPosting(
  PostingID INT AUTO_INCREMENT UNIQUE NOT NULL,
  JobID INT NOT NULL,
  PostEndDate DATE NOT NULL,
  FOREIGN KEY(JobID) REFERENCES LibPosition(JobID) ON DELETE CASCADE
) ENGINE=INNODB;

/* LibApplicant table is people who have applied for any position using the app */
CREATE TABLE LibApplicant(
  ApplicantID INT AUTO_INCREMENT UNIQUE NOT NULL,
  UserName VARCHAR(10) NOT NULL, /* Pulled from $_ENV['REMOTE_USER']*/
  FirstName VARCHAR(50) NOT NULL,
  LastName VARCHAR(50) NOT NULL,
  StudEMail VARCHAR(50) NOT NULL, /* When putting E-Mail in SQL table, it MUST be wrapped in quotes */
  Enrollment VARCHAR(20) NOT NULL,
  PhoneNum VARCHAR(20) NOT NULL, /* VARCHAR to allow for formatting and international numbers */
  RegHours INT NOT NULL, /* Hours registered this semester */
  IUEmployed BOOLEAN NOT NULL,
  WorkStudy BOOLEAN NOT NULL,
  Languages TEXT,
  WorkHistory BOOLEAN NOT NULL /* Flag to determine if Work History was entered */
) ENGINE=INNODB;

/* Application takes an ApplicantID, Posting ID, and shows availability.
Approved/Rejected for future Supervisor Portal functionality */
CREATE TABLE LibApplication(
  ApplicationID INT AUTO_INCREMENT UNIQUE,
  ApplicantID INT NOT NULL,
  PostingID INT NOT NULL,
  AvailHours INT NOT NULL,
  MonStart1 TIME,
  MonEnd1 TIME,
  MonStart2 TIME,
  MonEnd2 TIME,
  TueStart1 TIME,
  TueEnd1 TIME,
  TueStart2 TIME,
  TueEnd2 TIME,
  WedStart1 TIME,
  WedEnd1 TIME,
  WedStart2 TIME,
  WedEnd2 TIME,
  ThuStart1 TIME,
  ThuEnd1 TIME,
  ThuStart2 TIME,
  ThuEnd2 TIME,
  FriStart1 TIME,
  FriEnd1 TIME,
  FriStart2 TIME,
  FriEnd2 TIME,
  SatStart1 TIME,
  SatEnd1 TIME,
  SatStart2 TIME,
  SatEnd2 TIME,
  SunStart1 TIME,
  SunEnd1 TIME,
  SunStart2 TIME,
  SunEnd2 TIME,
  Hired BOOLEAN NOT NULL,
  NotHired BOOLEAN NOT NULL,
  HireDate DATE,
  FOREIGN KEY(ApplicantID) REFERENCES LibApplicant(ApplicantID) ON DELETE CASCADE,
  FOREIGN KEY(PostingID) REFERENCES LibPosting(PostingID) ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE ArchivedPosting (
  PostingID INT UNIQUE NOT NULL,
  JobID INT NOT NULL,
  PostEndDate DATE NOT NULL,
  FOREIGN KEY(JobID) REFERENCES LibPosition(JobID) ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE ArchivedApps (
  ApplicationID INT UNIQUE NOT NULL,
  ApplicantID INT NOT NULL,
  PostingID INT NOT NULL,
  AvailHours INT NOT NULL,
  MonStart1 TIME,
  MonEnd1 TIME,
  MonStart2 TIME,
  MonEnd2 TIME,
  TueStart1 TIME,
  TueEnd1 TIME,
  TueStart2 TIME,
  TueEnd2 TIME,
  WedStart1 TIME,
  WedEnd1 TIME,
  WedStart2 TIME,
  WedEnd2 TIME,
  ThuStart1 TIME,
  ThuEnd1 TIME,
  ThuStart2 TIME,
  ThuEnd2 TIME,
  FriStart1 TIME,
  FriEnd1 TIME,
  FriStart2 TIME,
  FriEnd2 TIME,
  SatStart1 TIME,
  SatEnd1 TIME,
  SatStart2 TIME,
  SatEnd2 TIME,
  SunStart1 TIME,
  SunEnd1 TIME,
  SunStart2 TIME,
  SunEnd2 TIME,
  Hired BOOLEAN NOT NULL,
  NotHired BOOLEAN NOT NULL,
  HireDate DATE,
  FOREIGN KEY(ApplicantID) REFERENCES LibApplicant(ApplicantID) ON DELETE CASCADE,
  FOREIGN KEY(PostingID) REFERENCES ArchivedPosting(PostingID) ON DELETE CASCADE
) ENGINE=INNODB;
