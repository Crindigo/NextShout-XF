<?php

class NextShout_Install_Data_Mysql {

	public function getTables() {
		$tables = array();

$tables['nextshout_shouts'] = '
CREATE TABLE nextshout_shouts (
	shout_id      int unsigned NOT NULL AUTO_INCREMENT,
	shout_channel int          NOT NULL,
	shout_user    int unsigned NOT NULL,
	shout_time    int unsigned NOT NULL,
	shout_raw     text         NOT NULL,
	shout_parsed  text         NOT NULL,
	shout_me      tinyint      NOT NULL,
	shout_msgto   int unsigned NOT NULL,
	shout_ip      varchar(15)  NOT NULL,
	shout_noedit  tinyint      NOT NULL,
	shout_gagged  tinyint      NOT NULL,

	PRIMARY KEY       (shout_id),
	KEY shout_time    (shout_time),
	KEY shout_user    (shout_user),
	KEY shout_msgto   (shout_msgto),
	KEY shout_channel (shout_channel)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_channels'] = '
CREATE TABLE nextshout_channels (
	channel_id      int unsigned NOT NULL AUTO_INCREMENT,
	channel_ident   varchar(16)  NOT NULL,
	channel_name    varchar(32)  NOT NULL,
	channel_color   varchar(32)  NOT NULL,
	channel_anyone  tinyint      NOT NULL,
	channel_groups  text         NOT NULL,
	channel_include text         NOT NULL,
	channel_exclude text         NOT NULL,

	PRIMARY KEY (channel_id)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_commands'] = '
CREATE TABLE nextshout_commands (
	command_id      int unsigned NOT NULL AUTO_INCREMENT,
	command_name    varchar(32)  NOT NULL,
	command_anyone  tinyint      NOT NULL,
	command_groups  text         NOT NULL,
	command_include text         NOT NULL,
	command_exclude text         NOT NULL,

	PRIMARY KEY (command_id)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_commandlog'] = '
CREATE TABLE nextshout_commandlog (
	cmdlog_id      int unsigned NOT NULL AUTO_INCREMENT,
	cmdlog_user    int unsigned NOT NULL,
	cmdlog_time    int unsigned NOT NULL,
	cmdlog_command varchar(32)  NOT NULL,
	cmdlog_args    text         NOT NULL,

	PRIMARY KEY     (cmdlog_id),
	KEY cmdlog_time (cmdlog_time)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_macros'] = '
CREATE TABLE nextshout_macros (
	macro_id       int unsigned     NOT NULL AUTO_INCREMENT,
	macro_user     int unsigned     NOT NULL,
	macro_name     varchar(32)      NOT NULL,
	macro_content  text             NOT NULL,
	macro_argcount tinyint unsigned NOT NULL,

	PRIMARY KEY                 (macro_id),
	UNIQUE KEY userid_macroname (macro_user, macro_name)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_notices'] = '
CREATE TABLE nextshout_notices (
	notice_id      int unsigned NOT NULL AUTO_INCREMENT,
	notice_type    varchar(32)  NOT NULL,
	notice_type_id int unsigned NOT NULL,
	notice_channel int          NOT NULL,
	notice_options text         NOT NULL,

	PRIMARY KEY        (notice_id),
	UNIQUE KEY type_id (notice_type, notice_id)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';

$tables['nextshout_deltas'] = '
CREATE TABLE nextshout_deltas (
	delta_id   int unsigned      NOT NULL AUTO_INCREMENT,
	delta_time int unsigned      NOT NULL,
	delta_type smallint unsigned NOT NULL,
	delta_data text              NOT NULL,

	PRIMARY KEY    (delta_id),
	KEY delta_time (delta_time)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
';
	
		return $tables;
	}

	public function getData() {
		$data = array();

		return $data;
	}
}

