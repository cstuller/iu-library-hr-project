CREATE TABLE SubmitFields(
  Priority INT UNIQUE NOT NULL,         /* Determines order in which fields are displayed and processed */
  Type VARCHAR(20) NOT NULL,            /* Data Type of the row */
  Label VARCHAR(50) NOT NULL,           /* Data Label of the row when used in form */
  VarName VARCHAR(20) NOT NULL,         /* Variable Name to be used in the form */
  ColName VARCHAR(20) NOT NULL,         /* Column Name in the LIBPOSITION table */
  ReqField BOOLEAN NOT NULL,            /* True/False for if Field is required */
  AppField BOOLEAN NOT NULL,            /* True/False for if Field is seen by applying students */
  NumRows INT DEFAULT NULL,             /* Needed for textarea data type for the number of rows */
  Step FLOAT(7, 6) DEFAULT NULL,        /* Needed for number data type for the number of decimals */
  ArrayTable VARCHAR(20) DEFAULT NULL,  /* Needed for checkbox, radio, and dropdown data types -- name of table containing data info */
  HROverride VARCHAR(20) DEFAULT NULL   /* Override type -- if HR needs more options for editing, this field changes the default type for them */
) ENGINE=INNODB;

/* Statement to seed all the fields. Giving priority in order fields should appear in form */
INSERT INTO SubmitFields (Priority, Type, Label, VarName, ColName, ReqField, AppField, NumRows, Step, ArrayTable, HROverride) VALUES
(10, 'text', 'Position Title', 'title', 'Title', TRUE, TRUE, NULL, NULL, NULL, NULL),
(50, 'text', 'Department', 'dept', 'Department', TRUE, TRUE, NULL, NULL, NULL, NULL),
(70, 'radio', 'Classification', 'class', 'Classification', FALSE, FALSE, NULL, NULL, 'ClassChoices', NULL), /* radio type needs a table */
(90, 'email', 'Contact E-Mail', 'email', 'EMail', TRUE, TRUE, NULL, NULL, NULL, NULL),
(110, 'radio', 'Pay Rate', 'pay', 'Pay', TRUE, TRUE, NULL, NULL, 'PayChoices', NULL),                      /* radio type needs a table */
(130, 'number', 'Hours Per Week', 'hours', 'Hours', TRUE, TRUE, NULL, '1', NULL, NULL),                    /* number type requires a step */
(150, 'yesno', 'Evenings/Weekends?', 'eve', 'EveWeekend', TRUE, TRUE, NULL, NULL, NULL, NULL),
(170, 'yesno', 'Students Only?', 'stud', 'StudOnly', TRUE, TRUE, NULL, NULL, NULL, NULL),
(190, 'textarea', 'Job Description', 'desc', 'Description', TRUE, TRUE, '5', NULL, NULL, NULL),            /* textarea type requires a numrows */
(210, 'textarea', 'Qualifications', 'qual', 'Qualifications', TRUE, TRUE, '5', NULL, NULL, NULL),          /* textarea type requires a numrows */
(230, 'checkbox', 'Required Documents', 'docs', 'Documents', FALSE, TRUE, NULL, NULL, 'DocBoxes', NULL),   /* checkbox type needs a table */
(250, 'textarea', 'Special Instructions', 'spec', 'SpecialInst', FALSE, TRUE, '5', NULL, NULL, NULL);      /* textarea type requires a numrows */

CREATE TABLE PayChoices(
  ChoiceID INT UNIQUE NOT NULL AUTO_INCREMENT,
  Label VARCHAR(20) NOT NULL,
  VarName VARCHAR(10) NOT NULL,
  Value VARCHAR(10) NOT NULL
) ENGINE=INNODB;

INSERT INTO PayChoices (Label, VarName, Value) VALUES
('10.15', 'baserate', '10.15'),
('11.25 (LCT II)', 'lct2rate', '11.25'),
('12.75 (LCT III)', 'lct3rate', '12.75'),
('Exception Filed', 'exception', '0.00');

CREATE TABLE ClassChoices(
  ChoiceID INT UNIQUE NOT NULL AUTO_INCREMENT,
  Label VARCHAR(20) NOT NULL,
  VarName VARCHAR(10) NOT NULL,
  Value VARCHAR(20) NOT NULL
) ENGINE=INNODB;

INSERT INTO ClassChoices (Label, VarName, Value) VALUES
('IU Press', 'iupress', 'IU Press'),
('LT', 'lt', 'LT'),
('LCT I', 'lct1', 'LCT I'),
('LCT II', 'lct2', 'LCT II'),
('LCT III', 'lct3', 'LCT III'),
('DUSTER', 'duster', 'DUSTER'),
('LC OvernightSecurity', 'lcos', 'LC OvernightSecurity');

CREATE TABLE DocBoxes(
  DocID INT UNIQUE NOT NULL AUTO_INCREMENT,
  Label VARCHAR(25) NOT NULL,
  VarName VARCHAR(10) NOT NULL,
  ColName VARCHAR(20) NOT NULL
) ENGINE=INNODB;

INSERT INTO DocBoxes (Label, VarName, ColName) VALUES
('Resume', 'resume', 'ResumeReq'),
('Cover Letter', 'cover', 'CoverReq'),
('Unofficial Transcript', 'transcript', 'TransReq'),
('Writing Samples', 'writing', 'WritingReq'),
('Other Documents', 'other', 'OtherReq');

CREATE TABLE PersonalFields(
  Priority INT UNIQUE NOT NULL,         /* Primary Key -- Determines order in which fields are displayed and processed */
  Type VARCHAR(20) NOT NULL,            /* Data Type of the row */
  Label VARCHAR(75) NOT NULL,           /* Data Label of the row when used in form */
  VarName VARCHAR(20) NOT NULL,         /* Variable Name to be used in the form */
  ColName VARCHAR(20) NOT NULL,         /* Column Name in the LIBPOSITION table */
  ReqField BOOLEAN NOT NULL,            /* True/False for if Field is required */
  NumRows INT DEFAULT NULL,             /* Needed for textarea data type for the number of rows */
  Step FLOAT(7, 6) DEFAULT NULL,        /* Needed for number data type for the number of decimals */
  ArrayTable VARCHAR(20) DEFAULT NULL   /* Needed for checkbox, radio, and dropdown data types -- name of table containing data info */
) ENGINE=INNODB;

INSERT INTO PersonalFields (Priority, Type, Label, VarName, ColName, ReqField, NumRows, Step, ArrayTable) VALUES
(10, 'text', 'First Name', 'fname', 'FirstName', TRUE, NULL, NULL, NULL),
(30, 'text', 'Last Name', 'lname', 'LastName', TRUE, NULL, NULL, NULL),
(50, 'email', 'E-Mail Address', 'studemail', 'StudEMail', TRUE, NULL, NULL, NULL),
(70, 'radio', 'Enrollment Status', 'status', 'Enrollment', TRUE, NULL, NULL, 'EnrollChoices'),  /* radio type needs a table */
(90, 'text', 'Phone Number', 'phone', 'PhoneNum', TRUE, NULL, NULL, NULL),
(110, 'number', 'Hours registered this semester', 'reghours', 'RegHours', FALSE, NULL, '1', NULL),
(130, 'yesno', 'I am currently employed on the IU Bloomington Campus', 'emp', 'IUEmployed', TRUE, NULL, NULL, NULL),
(150, 'yesno', 'Federal Work-Study Award', 'workstud', 'WorkStudy', TRUE, NULL, NULL, NULL),
(170, 'text', 'Any languages spoken other than English', 'languages', 'Languages', FALSE, NULL, NULL, NULL);

CREATE TABLE EnrollChoices(
  EnrollID INT UNIQUE NOT NULL AUTO_INCREMENT,
  Label VARCHAR(20) NOT NULL,
  VarName VARCHAR(10) NOT NULL,
  Value VARCHAR(1) NOT NULL
) ENGINE=INNODB;

INSERT INTO EnrollChoices (Label, VarName, Value) VALUES
('Undergraduate', 'undergrad', 'U'),
('Graduate', 'grad', 'G'),
('Not Enrolled', 'noenroll', 'N');

CREATE TABLE AvailDays(
  Day VARCHAR(10) NOT NULL,
  Abbrev VARCHAR(5) NOT NULL
) ENGINE=INNODB;

INSERT INTO AvailDays (Day, Abbrev) VALUES
('Monday', 'Mon'),
('Tuesday', 'Tue'),
('Wednesday', 'Wed'),
('Thursday', 'Thu'),
('Friday', 'Fri'),
('Saturday', 'Sat'),
('Sunday', 'Sun');

CREATE TABLE WorkHistoryFields(
  Priority INT UNIQUE NOT NULL,         /* Primary Key -- Determines order in which fields are displayed and processed */
  Type VARCHAR(20) NOT NULL,            /* Data Type of the row */
  Label VARCHAR(75) NOT NULL,           /* Data Label of the row when used in form */
  VarName VARCHAR(20) NOT NULL,         /* Variable Name to be used in the form */
  ColName VARCHAR(20) NOT NULL,         /* Column Name in the LIBPOSITION table */
  ReqField BOOLEAN NOT NULL,            /* True/False for if Field is required */
  NumRows INT DEFAULT NULL,             /* Needed for textarea data type for the number of rows */
  Step FLOAT(7, 6) DEFAULT NULL,        /* Needed for number data type for the number of decimals */
  ArrayTable VARCHAR(20) DEFAULT NULL   /* Needed for checkbox, radio, and dropdown data types -- name of table containing data info */
) ENGINE=INNODB;

/* This table only contains the needed fields for one such job -- the website takes this data and makes 3 jobs out of it.
   If any further fields are created, THREE fields will be needed in the corresponding SQL table -- CS */
INSERT INTO WorkHistoryFields (Priority, Type, Label, VarName, ColName, ReqField, NumRows, Step, ArrayTable) VALUES
(10, 'text', 'Company', 'jobcompany', 'Company', FALSE, NULL, NULL, NULL),
(30, 'text', 'Title', 'jobtitle', 'Title', FALSE, NULL, NULL, NULL),
(50, 'date', 'Start Date', 'jobstart', 'JobStartDate', FALSE, NULL, NULL, NULL),
(70, 'date', 'End Date', 'jobend', 'JobEndDate', FALSE, NULL, NULL, NULL),
(90, 'textarea', 'Duties (Max 500 Characters)', 'jobduties', 'Duties', FALSE, '5', NULL, NULL);   /* textarea type requires a numrows */
