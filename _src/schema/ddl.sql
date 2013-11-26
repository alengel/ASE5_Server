create table t5_users(
        
    id int(11) auto_increment,
    email varchar(200) unique,
    passwd varchar(200),
    first_name varchar(200),
    last_name varchar(200),
    profile_image text,
    
    login_key varchar(200),
    login_timestamp varchar(20),
    last_login varchar(200),
    last_request varchar(20),
    min_distance int(10),
    logout_session_time int(10),
    geo_push_interval int(10),

    dated varchar(20),
    primary key(id)

)engine=innodb;

create table t5_locations(
	
	id int(11) auto_increment,
	
	foursquare_venue_id varchar(200) unique,
	foursquare_venue_name text,
	
	dated varchar(20),
	primary key(id)

)engine=innodb;

create table t5_users_reviews(
	
	id int(11) auto_increment,
	users_id int(11),
	locations_id int(11),
	
	rating int(2),
	review_title text,
	review_description text,
	review_picture text,
	
	total_vote_up int(10),
	total_vote_down int(10),
	spams int(10),
	
	dated varchar(20),
	primary key(id),
	unique key(users_id,locations_id),
    foreign key (users_id) references t5_users(id) on update cascade on delete cascade,
	foreign key (locations_id) references t5_locations(id) on update cascade on delete cascade

)engine=innodb;

create table t5_users_reviews_comments(
	
	id int(11) auto_increment,
	users_id int(11)
	users_reviews_id int(11),
	comment text,
		
	dated varchar(20),
	primary key(id),
    foreign key (users_id) references t5_users(id) on update cascade on delete cascade,
	foreign key (users_reviews_id) references t5_users_reviews(id) on update cascade on delete cascade

)engine=innodb;


create table t5_users_votes(

	id int(11) auto_increment,
	users_id int(11),
	users_reviews_id int(11),
	vote_flag boolean,
	dated varchar(20),
	
	primary key(id),
	unique key(users_id,users_reviews_id),
    foreign key (users_id) references t5_users(id) on update cascade on delete cascade,
	foreign key (users_reviews_id) references t5_users_reviews(id) on update cascade on delete cascade

)engine=innodb;


create table t5_reviews_spams(

	id int(11) auto_increment,
	users_id int(11),
	spam_id int(11),
	dated varchar(20),
	
	primary key(id),
	unique key(users_id,spam_id),
    foreign key (spam_id) references t5_users_review(id) on update cascade on delete cascade,
		
)engine=innodb;

create table t5_users_spams(

	id int(11) auto_increment,
	users_id int(11),
	spam_id int(11),
	dated varchar(20),
	
	primary key(id),
	unique key(users_id,spam_id),
    foreign key (spam_id) references t5_users_review(id) on update cascade on delete cascade,
		
)engine=innodb;

create table t5_checkins(

    id int(11) auto_increment,
    users_id int(11),
    locations_id int(11),
    
    dated varchar(20),
    primary key(id),
    unique key(users_id,locations_id),
    foreign key (users_id) references t5_users(id) on update cascade on delete cascade,
	foreign key (locations_id) references t5_locations(id) on update cascade on delete cascade

)engine=innodb;

create table t5_phonebook(
	
	id int(11) auto_increment,
	users_id int(11),
	email varchar(200),
	phone varchar(200),
	
	dated varchar(20),
	primary key(id),
    foreign key (users_id) references t5_users(id) on update cascade on delete cascade,
	
)engine=innodb;

create table t5_connections(

	id int(11) auto_increment,
	my_id int(11),
	friends_id int(11),
	status varchar(30),
	phonebook_status varchar(200),
	howdy_flag varchar(200),
	dated varchar(20),
	
	PRIMARY(id),
	unique key(users_id,connection_id),
    foreign key (my_id) references t5_users(id) on update cascade on delete cascade,
    foreign key (friends_id) references t5_users(id) on update cascade on delete cascade
		
)engine=innodb;

