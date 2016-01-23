<?php

class WP_ACF_CPT
{
  public function __construct( $id = null )
  {
    if( is_int( $id ) )
    {
      $new_fields = array();

      $fields = get_field_objects( $id );

      if( is_array( $fields ) )
      {
        foreach( $fields as $k => $field )
        {
          $new_fields[$k] = $field['key'];
        }

        if( !empty( $new_fields ) )
        {
          foreach( $new_fields as $field_id => $key )
          {
            $this->$field_id = get_field( $key, $id );
          }
        }
      }
    }
  }

  public function get_field( $slug )
  {
    return $this->$slug;
  }
}

