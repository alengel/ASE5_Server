create table t5_users(
	
	id int(11) auto_increment,
	email varchar(200) unique,
	passwd varchar(200),
	first_name varchar(200),
	last_name varchar(200),
	login_key varchar(200),
	login_timestamp varchar(20),
	last_login varchar(200),
	last_request varchar(20),
	logout_session_time int(10),
	gps_push_time int(10),

	dated varchar(20),
	primary key(id)

)engine=innodb;

create table t5_locations(
	
	id int(11) auto_increment,
	users_id int(11),
	latitude varchar(100),
	longitude varchar(100),
	
	dated varchar(20),
	primary key(id),
	foreign key (users_id) references t5_users(id) on update cascade on delete cascade

)engine=innodb;
