<?php

class FeedInput_FeedValidation {
  
  private $valid;
  private $message;
  
  
  /**
   * @param $valid boolean optional - is the feed valid, defaults to true
   * @param $message string optional - a message of the error
   */
  public function __construct( $valid=true, $message='' ){
    $this->valid = $valid;
    $this->message = $message;
  }
  
  
  
  /**
   * @return Boolean
   */
  public function isValid(){
    return $this->valid;
  }
  
  
  
  /**
   * @return String
   */
  public function getMessage(){
    return $this->message;
  }
}