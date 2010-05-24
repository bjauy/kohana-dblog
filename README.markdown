# Status

## pre-alpha

# Requirements

-	Database module
-	Pagination module, if default view is used

# Installation

Register the module in bootstrap.php:

	Kohana::modules(array(
		[...]
		'dblog'      => MODPATH.'dblog',
	));

Create a database table, based on default.sql. Additional fields can be added.

If you want to store all Kohana::$log messages in the database too:

1.	In bootstrap.php change

		Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

	to

		Kohana::$log->attach(new Kohana_Log_Db());

	**Make sure this line appears after the call of Kohana::modules().**

2.	In the same file add

		Kohana_Log::$timestamp = 'U';

# Configuration

If you don't use the default database table name "log", then copy the file *modules/dblog/config/dblog.php* to *application/config/dblog.php* and edit the config key "db_table_name".

# Using additional fields

## Setting a default value (e.g. in bootstrap.php)

	DBlog::setAdditionalField('ip', getenv('REMOTE_ADDR'));

## Setting values on a per entry basis

	DBlog::add('category', 'title', 'details', array(
		'myVarDump' => $myVar,
	));

# License

(c) 2010 Bastian Bräu
[Released under the ISC License](http://opensource.org/licenses/isc-license.txt)