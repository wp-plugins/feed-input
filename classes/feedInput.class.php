<?php

// TODO: Add additional column to the tracking table for the FeedInput_Feed
// that the feed item was processed by. This way a feed can be digested by multiple
// feed objects which would be useful if a single feed was being digested in 
// multiple manners.

/**
 * FeedInput 
 */

class FeedInput {
  
  const NAMESPACE = 'feedinput';
  private static $instance;
  
  /**
   * Get the singlenton instance of FeedInput
   */
  public static function singleton(){
    if(!isset(self::$instance)){
      $c = __CLASS__;
      self::$instance = new $c;
    }
    
    return self::$instance;
  }
  
  /**
   *
   */
  private function __construct(){
    global $wpdb;
    
    // TODO: pull the information from user settings
    

    $this->feeds = array(
      // new FeedInput_Feed( 'delicious', 'http://feeds.delicious.com/v2/rss/', new FeedInput_ToPostProcessor('news') ),
    );
    
    
    
    // tracking database table name
    $this->trackingDbTableName = $wbpd->prefix .self::NAMESPACE.'_tracking';
    
    // Setup actions
    register_activation_hook( FEEDINPUT_PLUGIN_FILE, array( $this, 'registerPlugin' ) );
    register_deactivation_hook( FEEDINPUT_PLUGIN_FILE, array( $this, 'deregisterPlugin' ) );
    add_action( self::NAMESPACE.'_cron', array( $this, 'cron' ) );

  }
  
  
  
  /**
   * Initial registration of plugin
   */
  public function registerPlugin(){
    global $wpdb;
    
    // create database table
    if( $wpdb->get_var("SHOW TABLES LIKE '{$this->trackingDbTableName}'") != $this->trackingDbTableName ){
      $sql = "CREATE TABLE " . $this->trackingDbTableName . " (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                feed_name VARCHAR(512) NOT NULL,
                pulled TIMESTAMP DEFAULT NOW(),
                uid VARCHAR(512) NOT NULL,
                feed_uri VARCHAR(512) NOT NULL,
                UNIQUE KEY id (id)
              )";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    // cron
    wp_schedule_event(time(), 'hourly', self::NAMESPACE.'_cron' );
    add_action( 'init', array( $this, 'cron' ), 0, 50 );
    
    
  }
  
  
  /**
   * Deregistration of plugin
   */
  public function deregisterPlugin(){
    wp_clear_scheduled_hook( self::NAMESPACE.'_cron' );
  }
  
  
  
  /**
   * Deletes the data associated with this plugin
   * - Tracking database table
   * - Options and settings
   *
   */
  static public function uninstall(){
    global $wpdb;
    // TODO Testing for the uninstall
    $sql = "DROP TABLE IF_EXISTS ".$wbpd->prefix .self::NAMESPACE.'_tracking'.";";
    $e = $wpdb->query($sql);
  }
  
  
  /**
   * Cron
   */
  public function cron(){
    global $wpdb;
    // TODO: keep track of the posts pulled elsewhere so that if the user
    // deletes the resulting post that the post is not recreated from the feed
    foreach( $this->feeds as $feed ){
      $feed_uris = $feed->getUris();
      
      foreach( $feed_uris as $feed_uri ){
        $feedResults = fetch_feed( $feed_uri );
        $items = $feedResults->get_items();
      
        // Check if the items have already been processed
        $uids = array();
        foreach( $items as $item ){
          $uids[] = $wpdb->escape($item->get_id());
        }
        $query = "SELECT uid 
                  FROM {$this->trackingDbTableName}
                  WHERE feed_uri = '".$wpdb->escape($feed_uri)."' 
                    AND uid IN ('".implode("','",$uids)."') 
                    AND feed_name = '".$wpdb->escape($feed->getName())."'";
        $alreadyPulled = $wpdb->get_col( $query );
        
        foreach( $items as $item ){
          // $item documentation: http://simplepie.org/wiki/reference/start#simplepie_item
          $item_id = $item->get_id();
                  // skip if already pulled
          if( !in_array( $item_id, $alreadyPulled ) ){
            // add to the tracking DB table
            $wpdb->insert( $this->trackingDbTableName, array( 'uid' => $item_id, 'feed_uri' => $feed_uri, 'feed_name' => $feed->getName()) );
            
            $feed->processFeedItem( $item, $feed_uri );
            
          }
        }
      }
    }
    
  }
  
}