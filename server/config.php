<?php
ini_set("display_errors","on");
$docRoot  = realpath(dirname(__FILE__));

if( !isset($dbh) ){

  $dbh = pg_connect("host=localhost port=5433 dbname=ap1tnde user=tnde password=root");
  /**
   * Change The Credentials to connect to database.
   */
}
?>