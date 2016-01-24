<?php
/**
 * @package WordPress
 * @subpackage themename
 */
?>

<!DOCTYPE html>
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="IE ie8"> <![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="IE ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<!--[if IE 8 ]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<title><?php echo site_global_description(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/media/favicon.ico">
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<script type="text/javascript">
  var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<div class="wrap login-wrap">

  <div class="wrapper table login-page">
    <div data-overlay-parent><div data-overlay><i class="fa fa-fw fa-spin fa-spinner"></i></div></div>

