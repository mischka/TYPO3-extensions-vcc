#
# Table structure for table 'tx_vcc_log'
#
CREATE TABLE tx_vcc_log (
	uid int(11) NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	be_user int(11) unsigned DEFAULT '0' NOT NULL,

	type tinyint(4) unsigned DEFAULT '0' NOT NULL,
	message text NOT NULL,
	log_data text NOT NULL,

	caller varchar(255) DEFAULT '' NOT NULL,
	hash varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hash (hash)
);
#
# Extend table structure of table 'sys_domain'
#
CREATE TABLE sys_domain (
	vcc_enabled tinyint(4) unsigned DEFAULT '0' NOT NULL
);