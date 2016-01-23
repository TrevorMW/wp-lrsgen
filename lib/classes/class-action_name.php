<?php

class Action_Name
{
  public $type;

  public function __construct( $post )
  {
    if( is_object( $post ) )
    {
      if( $post->post_type == 'page' )
      {
        $type = str_replace( '-', '_', $post->post_name ).'_';
      }
      else
      {
        $type = str_replace( '-', '_', $post->post_type ).'_';
      }

      is_front_page() ? $type = 'front_' : '';

      $this->type = $type;
    }
  }
}
