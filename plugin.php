<?php
/*
Plugin Name: Feed Input
Plugin URI:
Description: Pulls a feed into your WordPress site to use them for various uses.
Author: New Signature
Version: 0.0.1A
Author URI: http://newsignature.com/
*/

define( 'FEEDINPUT_PLUGIN_FILE', __FILE__ );

// Auto load up the classes
$loadDirs = array( dirname(__FILE__).'/classes' );

while( ( $nextLoadDir = array_shift( $loadDirs ) ) ){
  foreach( glob( $nextLoadDir.'/*.class.php') as $filename ){
    require_once( $filename );
  }
  
  foreach( glob( $nextLoadDir.'/*', GLOB_ONLYDIR ) as $dir ){
    $loadDirs[] = $dir;
  }
  
}


//require_once( dirname(__FILE__).'/classes/feedInput.class.php' );
//require_once( dirname(__FILE__).'/classes/feedInput_Feed.class.php' );
//require_once( dirname(__FILE__).'/classes/feedInput_.class.php' );


$feedInput = FeedInput::singleton();