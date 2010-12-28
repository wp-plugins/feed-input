<?php 



class FeedInput_Feed {
  
  private $uris;
  private $processor;
  private $name;
  
  
  
  /**
   * @param $uris string|array - the uri of the feed
   * @param $processor FeedInput_Processor - the processor for handling new items
   */
  function __construct( $name, $uris, FeedInput_Processor $processor ){
    if( !is_array( $uris ) ){
      $uris = array( $uris );
    }
    $this->uris = $uris;
    
    $this->processor = $processor;
    
    $this->name = $name;
  }
  
  
  
  /**
   * @return string - the name
   */
  function getName(){
    return $this->name;
  }
  
  
  
  /**
   * @return string - the uri for the feed
   */
  function getUris(){
    return $this->uris;
  }
  
  
  
  /**
   * @param $uri string - the uri to  add
   * @param $validate boolean (option) - flag to validate the URI first, and only update if valid
   * @return FeedInput_FeedValidation
   */
  function addUri( $uri, $validate=true ){
    // TODO validation
    if( !in_array($uri, $this->uris) ){
      $this->uris[] = $uri;
    }
    
    /*if( !$validate ) {
      $this->uris[] = $uri;
    } else {
      
    } */
  }
  
  
  
  /**
   * @param $uri string - the uri to remove
   */
  function removeUri( $uri ){
    $key = array_search( $uri, $this->uris );
    if( $key !== false ){
      unset( $this->uris[$key] );
    }
  }
  
  
  /**
   * @param $uri string - the uri to validate
   * @return FeedInput_FeedValidation 
   */
  static function validateFeed( $uri ){
    // TODO
  }
  
  
  
  /**
   * @return FeedInput_Processor
   */
  function getProcessor(){
    return $this->processor;
  }
  
  
  
  /**
   * @param $processor FeedInput_Processor
   */
  function setProcessor( FeedInput_Processor $processor ){
    $this->processor = $processor;
  }
  
  
  
  /**
   * This process the new items from a feed
   * 
   * @param $item SimplePie_Item
   *
   */
  function processFeedItem( $item, $feedUri ){
    $this->processor->process( $item, $feedUri );
  }
  
}
