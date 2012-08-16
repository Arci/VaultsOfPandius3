<?php

require 'db.php';

// connessione all'host
$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ('Unable to connect. Check your connection parameters.');

//delete the existing database if exists
if(mysql_select_db(MYSQL_DB)){
    $sql = 'DROP DATABASE '.MYSQL_DB.';';
    mysql_query($sql, $db) or die(mysql_error($db));
    echo 'database dropped<br />';
}

// creazione del DB
$sql = 'CREATE DATABASE IF NOT EXISTS '.MYSQL_DB.';';
mysql_query($sql, $db) or die(mysql_error($db));

// connessione al DB
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

//creazione tabella "access_levels"
$sql = 'CREATE TABLE IF NOT EXISTS access_levels (
        level	TINYINT UNSIGNED	NOT NULL AUTO_INCREMENT,
        name	VARCHAR(50)		NOT NULL DEFAULT "",
        PRIMARY KEY (level)
    )
    ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

//creazione tabella "users"
$sql = 'CREATE TABLE IF NOT EXISTS users (
        id		INTEGER UNSIGNED	NOT NULL AUTO_INCREMENT,
        email		VARCHAR(100)		NOT NULL UNIQUE,
        password     	CHAR(41)		NOT NULL,
        name         	VARCHAR(100)		NOT NULL,
        access_level 	TINYINT UNSIGNED	NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        FOREIGN KEY (access_level) REFERENCES access_levels(level)
	  ON DELETE NO ACTION
	  ON UPDATE CASCADE
    )
    ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

//creazione tabella "temp_users"
$sql = 'CREATE TABLE IF NOT EXISTS temp_users (
        id		INTEGER UNSIGNED	NOT NULL AUTO_INCREMENT,
        email		VARCHAR(100)		NOT NULL UNIQUE,
        password     	CHAR(41)		NOT NULL,
        name         	VARCHAR(100)		NOT NULL,
        access_level 	TINYINT UNSIGNED	NOT NULL DEFAULT 1,
        code		VARCHAR(100)		NOT NULL UNIQUE, 
        PRIMARY KEY (id),
        FOREIGN KEY (access_level) REFERENCES access_levels(level)
	  ON DELETE NO ACTION
	  ON UPDATE CASCADE
    )
    ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

// creazione tabella "contentPage"
$sql = 'CREATE TABLE IF NOT EXISTS content_page (
	id		INTEGER UNSIGNED	NOT NULL AUTO_INCREMENT,
	href		VARCHAR(100)		NOT NULL UNIQUE,
	title		VARCHAR(255)		NOT NULL,
        source            VARCHAR(100),
	is_published	BOOLEAN          	NOT NULL DEFAULT FALSE,
        submit_date	DATE,
        publish_date	DATE,        
        text		MEDIUMTEXT,	
	PRIMARY KEY (id),
        FULLTEXT INDEX (title)
  )
  ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

//creazione tabella "content_page_author"
$sql = 'CREATE TABLE IF NOT EXISTS content_page_author (
	contentPage            INTEGER UNSIGNED        NOT NULL,
	author		INTEGER UNSIGNED	NOT NULL,
	FOREIGN KEY (author) REFERENCES users(id)
          ON DELETE CASCADE
	  ON UPDATE CASCADE,
        FOREIGN KEY (contentPage) REFERENCES content_page(id)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE
  )
  ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

//creazione tabella "indexPage"
$sql = 'CREATE TABLE IF NOT EXISTS index_page (
	id		INTEGER UNSIGNED	NOT NULL AUTO_INCREMENT,
	href		VARCHAR(100)		NOT NULL UNIQUE,
	title		VARCHAR(255)		NOT NULL,
	author		INTEGER UNSIGNED,
	text		MEDIUMTEXT,
        menu            BOOLEAN                 NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	FOREIGN KEY (author) REFERENCES users(id)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE,
        FULLTEXT INDEX (title)
  )
  ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

//creazione tabella "index2content"
$sql = 'CREATE TABLE IF NOT EXISTS index_2_content (
	link_id				INTEGER UNSIGNED	NOT NULL AUTO_INCREMENT,
	id_start_index_page		INTEGER UNSIGNED	NOT NULL,
	id_target_content_page		INTEGER UNSIGNED,
	id_target_index_page		INTEGER UNSIGNED,
	link_name			VARCHAR(255)		NOT NULL,
	link_comment			TEXT,			
	PRIMARY KEY (link_id),
	UNIQUE (id_start_index_page, id_target_content_page),
	UNIQUE (id_start_index_page, id_target_index_page),
	FOREIGN KEY (id_start_index_page) REFERENCES index_page(id)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE,
        FOREIGN KEY (id_target_content_page) REFERENCES content_page(id)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE,
	FOREIGN KEY (id_target_index_page) REFERENCES index_page(id)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE,
	CHECK (id_start_index_page <> id_target_index_page),  
	CHECK ((	(id_target_content_page IS NOT NULL) AND (id_target_index_page IS NULL)	
		      OR
			(id_target_content_page IS NULL) AND (id_target_index_page IS NOT NULL)		))  
  )
  ENGINE=MyISAM';
mysql_query($sql, $db) or die(mysql_error($db));

// inserimento valori iniziali
$sql = 'INSERT IGNORE INTO access_levels
        (level, name)
    VALUES
        (1, "User"),
        (2, "Old User"),
        (3, "Administrator")';
mysql_query($sql, $db) or die(mysql_error($db));

$sql = 'INSERT IGNORE INTO users 
        (email, password, name, access_level)
    VALUES
        ("unknown@unknown.com", PASSWORD("12345678"), "Unknown", 1)';
mysql_query($sql, $db) or die(mysql_error($db));

$sql = 'INSERT IGNORE INTO users 
        (email, password, name, access_level)
    VALUES
        ("admin@admin.com", PASSWORD("12345678"), "Administrator", 3)';
mysql_query($sql, $db) or die(mysql_error($db));

echo 'success';

?>
