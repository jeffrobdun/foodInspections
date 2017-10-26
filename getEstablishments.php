<?php
require_once(__DIR__ . '/../extensions/rb.php');
require_once('../config/config.php');

R::setup('mysql:host=' . DB_SERVER . ';dbname=' . ESTABLISHMENTS_DB_NAME, DB_USER, DB_PASS);

$opts = getopt( '', [ 'add:', 'list', 'delete' ] );

if ( isset( $opts['add'] ) ) {
  $w = R::dispense( 'whisky' );
  $w->name = $opts['add'];
  $id = R::store( $w );
  die( "OK.\n" );
}

if ( isset( $opts['list'] ) ) {
  $bottles = R::find( 'whisky' );
  if ( !count( $bottles ) ) die( "The cellar is empty!\n" );
  foreach( $bottles as $b ) {
    echo "* #{$b->id}: {$b->name}\n";
  }
  exit;
}

if ( isset( $opts['delete'] ) ) {
  R::trash( 'whisky', $opts['delete'] );
  die( "Threw the bottle away!\n" );
}
