# Alan Huang and Leonard Law

# Person Table
CREATE TABLE person (
    pid varchar(50),
    firstname varchar(50),
    lastname varchar(50),
    passwd varchar(50),
    d_privacy varchar(50),

    primary key (pid)
    );


# Event Table
CREATE TABLE event (
    e_id int,
    start_time time,
    duration time,
    description varchar(50),

    primary key (e_id)
    );


# Organize Table
CREATE TABLE organize (
    pid varchar(50),
    e_id int,

    primary key (pid, e_id),
    foreign key (pid) references person(pid)
        on delete cascade,
    foreign key (e_id) references event(e_id)
        on delete cascade
    );


# Invited Table
CREATE TABLE invited (
    pid varchar(50),
    e_id int,
    response int,
    visibility int,

    primary key (pid, e_id),
    foreign key (pid) references person(pid)
        on delete cascade,
    foreign key (e_id) references event(e_id)
        on delete cascade
    );


# Friend Table
CREATE TABLE friend_of (
    sharer_id varchar(50),
    viewer_id varchar(50),
    level int,

    primary key (sharer_id, viewer_id),
    foreign key (sharer_id) references person(pid)
        on delete cascade,
    foreign key (viewer_id) references person(pid)
        on delete cascade
    );


# Event Date Table
CREATE TABLE event_date (
    e_id int,
    date date,

    primary key (e_id),
    foreign key (e_id) references event(e_id)
    );

