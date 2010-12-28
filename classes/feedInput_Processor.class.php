<?php 


interface FeedInput_Processor {
  
  /**
   * 
   * @param $item SimplePie_Item
   */
  public function process( $item, $feedUri );
}