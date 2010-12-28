<?php


class FeedInput_ToPostProcessor implements FeedInput_Processor  {
  
  protected $type;
  protected $options;
  
  public function __construct( $type, $options=array() ){
    $this->type = $type;
    $this->options = array_merge( array(
        'status' => 'publish',
      ), $options );
  }
  
  
  public function process( $item, $feedUri ){
    
    // TODO: add categories to post data
    $post = array(
      'post_status' => $this->options['status'],
      'post_content' => $item->get_content(),
      'post_title' => $item->get_title(),
      'post_type' => $this->type,
      'post_date' => $item->get_date( 'Y-m-d H:i:s' ),
    );
    
    $post_id = wp_insert_post( $post );
    
    // save meta data
    add_post_meta( $post_id, 'uid', $item->get_id() );
    add_post_meta( $post_id, 'copyright', $item->get_copyright() );
    add_post_meta( $post_id, 'link', $item->get_link() );
    add_post_meta( $post_id, 'permalink', $item->get_permalink() );
    add_post_meta( $post_id, 'feed_uri', $feedUri );
    // TODO: add enclosures to the meta
  }
}