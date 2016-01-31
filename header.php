<?php
/**
 * @package WordPress
 * @subpackage themename
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>LRSGen</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/media/favicon.ico">
<?php wp_head(); do_action( 'add_globals', $post );  ?>
<script type="text/javascript">
  var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>',
      load_map = function(){}
</script>
</head>
<?php global $page_type; ?>
<body <?php body_class(); ?>>
<?php //echo get_current_template(); ?>
<div class="wrap table body-wrap">

  <div class="table-cell utility-bar">
    <header>
      <div class="logo">
        <a href="/dashboard">
          <h3>LrsGEN</h3>
        </a>
      </div>
      <div class="navigation">
        <nav class="main-nav">
          <ul><?php do_action('main_navigation'); do_action( $page_type.'main_navigation'); //var_dump( $page_type.'main_navigation' ); ?></ul>
        </nav>
      </div>
    </header>
  </div>

  <main class="table-cell main-content">

    <div class="main-content-window">
      <div data-overlay-parent><div data-overlay><i class="fa fa-fw fa-spin fa-spinner"></i></div></div>

      <div class="window-utility-box">
        <?php do_action('content_header'); do_action( $page_type.'content_header'); //var_dump( $page_type.'content_header' ); ?>
        <div class="page-tools"><?php do_action('page_tools'); do_action( $page_type.'page_tools'); //var_dump( $page_type.'page_tools' ); ?></div>
      </div>

      <div class="main-content-area">
        <?php do_action('flash_messages'); do_action( $page_type.'flash_messages'); //var_dump( $page_type.'flash_messages' );?>

        <?php do_action('page_forms'); do_action( $page_type.'page_forms'); //var_dump( $page_type.'page_tools' ); ?>

        <?php do_action('main_content'); do_action( $page_type.'main_content');  //var_dump( $page_type.'main_content' ) ?>

