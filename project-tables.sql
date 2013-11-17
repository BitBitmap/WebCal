CREATE TABLE person (
    pid VARCHAR( 50 ) PRIMARY KEY,
    passwd CHAR( 32 ) NOT NULL,
    fname VARCHAR( 50 ), 
    lname VARCHAR( 50 ),
    d_privacy INT NOT NULL  -- change from part 1 spec, which said varchar
);

CREATE TABLE event (
     eid  INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
     start_time  TIME NOT NULL,
     duration    TIME NOT NULL,
     description VARCHAR(50),
     pid         VARCHAR(50),
     FOREIGN KEY (pid) REFERENCES person(pid)
);

CREATE TABLE eventdate (
     eid INT NOT NULL,
     edate DATE, -- changed name to avoid confusion of attribute name w/ type
     PRIMARY KEY (eid, edate),
     FOREIGN KEY (eid) REFERENCES event(eid)
);

CREATE TABLE invited (	
   pid  VARCHAR(50) NOT NULL,
   eid  INT NOT NULL,
   response INT,
   visibility INT,
   PRIMARY KEY (pid, eid),
   FOREIGN KEY (pid) REFERENCES person(pid),
   FOREIGN KEY (eid) REFERENCES event(eid)
);

CREATE TABLE friend_of (
   sharer VARCHAR(50) NOT NULL,
   viewer VARCHAR(50) NOT NULL,
   level INT,
   PRIMARY KEY (sharer, viewer),
   FOREIGN KEY (sharer) REFERENCES person(pid),
   FOREIGN KEY (viewer) REFERENCES person(pid)
);

