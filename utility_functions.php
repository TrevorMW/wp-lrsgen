<?php

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START UTILITY FUNCTIONS ///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////


function var_template_include( $t )
{
  $GLOBALS['current_theme_template'] = basename($t);
  return $t;
}
add_filter( 'template_include', 'var_template_include', 1000 );

function get_current_template( $echo = false )
{
  if( !isset( $GLOBALS['current_theme_template'] ) )
      return false;
  if( $echo )
      echo $GLOBALS['current_theme_template'];
  else
      return $GLOBALS['current_theme_template'];
}

function get_excerpt_by_id( $post_id, $length = 35 )
{
  $new_post       = get_post( $post_id ); //Gets post ID
  $the_excerpt    = strip_tags( $new_post->post_content ); //Strips tags and images
  $words          = explode(' ', $the_excerpt, $length + 1);

  if(count($words) > $length) :

    array_pop($words);
    array_push($words, '…');
    $the_excerpt = implode(' ', $words);

  endif;

  return apply_filters( 'the_content', $the_excerpt );
}

function formatted_pagination( $data )
{
  $html = '';

  if( count( $data ) > 0 )
  {
    $html .= '<ul class="pagination">';

    foreach( $data as $link )
    {
      $class = '';
      strpos( $link, 'current' ) != false ? $class = 'active' : '' ;
      $html .= '<li class="'.$class.'">'.$link.'</li>';
    }

    $html .= '</ul>';
  }

  return $html;
}

function get_template_part_with_data( $path = null, $slug = null, $name = null, array $params = null )
{
  global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

  do_action("get_template_part_{$slug}", $slug, $name);

  $templates = array();
  if ( isset( $name ) )
    $templates[] = "/views/{$path}/{$slug}-{$name}.php";

  $templates[] = "/views/{$path}/{$slug}.php";

  $_template_file = locate_template($templates, false, false);

  if ( is_array( $wp_query->query_vars ) )
    extract( $wp_query->query_vars, EXTR_SKIP );

  if( is_array( $params ) )
    extract( $params, EXTR_SKIP );

  require( $_template_file );
}

function bk_is_blog()
{
  global $post;
  $posttype = get_post_type($post );
  return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ( $posttype == 'post')  ) ? true : false ;
}

function get_posts_array( $args = null )
{
  $list = array();

  if( is_array( $args ) )
  {
    $loop = new WP_Query( $args );

    if( $loop->have_posts() )
    {
      while( $loop->have_posts() ) : $loop->the_post();

        $list[$loop->post->ID] = $loop->post->post_title;

      endwhile;
    }
  }

  wp_reset_postdata();

  return $list;
}


/**
 *
 * ENQUEUE CSS, LESS, & SCSS STYLESHEETS
 *
 */
function add_style_sheets()
{
	if( !is_admin() )
	{
		wp_enqueue_style( 'reset', get_template_directory_uri().'/style.css', 'screen'  );
		wp_enqueue_style( 'font', '//fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800', 'screen'  );
		wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', 'screen'  );
		wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/style.less', 'screen' );
	}
}
add_action('wp_enqueue_scripts', 'add_style_sheets');



/**
 *
 * ENQUEUE JAVASCRIPT FILES
 *
 */
function add_javascript()
{
  global $post;

	if( !is_admin() )
	{
		wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', null, null, true );
		wp_enqueue_script( 'genericJS', get_template_directory_uri().'/assets/js/general.js', array('jquery'), null, true);

		if( file_exists( get_template_directory()."/assets/js/$post->post_name.js" )  )
		{
  		wp_enqueue_script( $post->post_name, get_template_directory_uri()."/assets/js/$post->post_name.js", array('jquery'), null, true );
		}

		wp_enqueue_script( 'cc-validation', get_template_directory_uri()."/assets/js/cc-validation.js", array('jquery'), null, true );
	}
}
add_action('wp_enqueue_scripts', 'add_javascript');



/**
 *
 * TAKE GLOBAL DESCRIPTION OUT OF HEADER.PHP AND GENERATE IT FROM A FUNCTION
 *
 */
function site_global_description()
{
	global $page, $paged;
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
}


/**
 * REMOVE UNWANTED CAPITAL <P> TAGS
 */
remove_filter( 'the_content', 'capital_P_dangit' );
remove_filter( 'the_title', 'capital_P_dangit' );
remove_filter( 'comment_text', 'capital_P_dangit' );


/**
 * REGISTER NAV MENUS FOR HEADER FOOTER AND UTILITY
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'themename' ),
	'footer' => __( 'Footer Menu', 'themename' ),
	'utility' => __( 'Utility Menu', 'themename' )
) );


/**
 * DEFAULT COMMENTS & RSS LINKS IN HEAD
 */
add_theme_support( 'automatic-feed-links' );


/**
 * THEME SUPPORTS THUMBNAILS
 */
add_theme_support( 'post-thumbnails' );


/**
 *	THEME SUPPORTS EDITOR STYLES
 */
add_editor_style("/css/layout-style.css");


/**
 *	ADD TINY IMAGE SIZE FOR ACF FIELDS, BETTER USABILITY
 */
add_image_size( 'mini-thumbnail', 50, 50 );


/**
 *	REPLACE THE HOWDY
 */
function admin_bar_replace_howdy($wp_admin_bar)
{
  $account = $wp_admin_bar->get_node('my-account');
  $replace = str_replace('Howdy,', 'Welcome,', $account->title);
  $wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $replace));
}
add_filter('admin_bar_menu', 'admin_bar_replace_howdy', 25);


function change_search_form( $form )
{
  global $post;

  $form = '<form role="search" method="get" data-search-form action="' . esc_url( home_url( '/' ) ) . '">
              <input type="hidden" name="page_identifier" value="'.$post->ID.'" />
              <ul class="inline">
                <li class="inline-input">
                  <label class="acc">' . _x( 'Search for:', 'label' ) . '</label>
                  <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />
                </li>
                <li class="inline-trigger">
                  <button type="submit"><i class="fa fa-fw fa-search"></i></button>
                </li>
              </ul>
      		 </form>';

  return $form;
}
add_filter( 'get_search_form', 'change_search_form' );
