<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');

/**
 * Databases config
 * You can use dns on url field if you need
 */
$config['databases']['host']        = 'localhost';
// $config['databases']['port']        = '3306';
$config['databases']['user']        = 'phpmyadmin';
$config['databases']['password']    = 'Rescuetrack123';
$config['databases']['dbname']      = 'db_pendaki_track';
/**
 * pdo_mysql: A MySQL driver that uses the pdo_mysql PDO extension.
 * mysqli: A MySQL driver that uses the mysqli extension.
 * pdo_sqlite: An SQLite driver that uses the pdo_sqlite PDO extension.
 * pdo_pgsql: A PostgreSQL driver that uses the pdo_pgsql PDO extension.
 * pdo_oci: An Oracle driver that uses the pdo_oci PDO extension. Note that this driver caused problems in our tests. Prefer the oci8 driver if possible.
 * pdo_sqlsrv: A Microsoft SQL Server driver that uses pdo_sqlsrv PDO
 * sqlsrv: A Microsoft SQL Server driver that uses the sqlsrv PHP extension.
 * oci8: An Oracle driver that uses the oci8 PHP extension.
 */
$config['databases']['driver']      = 'pdo_mysql';
/**
 * URL or DNS
 * The scheme names representing the drivers are either the regular driver names (see below) with any underscores in their name replaced with a hyphen (to make them legal in URL scheme names), or one of the following simplified driver names that serve as aliases:
 *      db2: alias for ibm_db2
 *      mssql: alias for pdo_sqlsrv
 *      mysql/mysql2: alias for pdo_mysql
 *      pgsql/postgres/postgresql: alias for pdo_pgsql
 *      sqlite/sqlite3: alias for pdo_sqlite
 * Example : mysql://localhost:4486/foo?charset=UTF8
 * Example : sqlite://ignored:ignored@ignored:1234/somedb.sqlite
 */
$config['databases']['url']         = NULL;
