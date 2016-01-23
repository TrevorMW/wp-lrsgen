<?php
/**
 * @package WordPress
 * @subpackage themename
 */


if( have_posts() ){ // START THE LOOP. IF THERE ARE POSTS

while ( have_posts() ) : the_post();  // THEN LOOP THROUGH THEM 

if( is_search() ){
	$title = ucfirst( str_replace( '-', ' ', $post->post_type ) );
	$title = $title.': '.get_the_title( $post->ID ); 
} else {
	$title = get_the_title( $post->ID );
} ?>

	<article class="post" role="article">
		<header class="post-header">
            <h1 class="post-title" role="heading">
            	<a href="<?php the_permalink(); ?>" title="Read more of <?php the_title(); ?>"><?php echo $title; ?></a>
            </h1>

			<div class="post-meta">
				<?php printf( __( '<span class="sep">Posted on </span>
									<a href="%1$s" role="link"><time class="entry-date" datetime="%2$s">%3$s</time></a> 
									<span class="sep">', 'themename' ),
						get_permalink(),
						get_the_date( 'c' ),
						get_the_date()
				);?>
			</div>
            
		</header>

		<?php if ( is_archive() || is_search() ) : // Only display Excerpts for archives & search ?>
        
          <div class="entry summary">
              <?php the_excerpt(); ?>
          </div>
          
		<?php else : ?>
        
          <div class="entry content">
              <?php the_content( ); ?>
          </div>
          
		<?php endif; ?>

		<footer class="entry-meta">
			<span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'themename' ); ?></span><?php the_category( ', ' ); ?></span>
			<span class="meta-sep"> | </span>
			<?php the_tags( '<span class="tag-links">' . __( 'Tagged ', 'themename' ) . '</span>', ', ', '<span class="meta-sep"> | </span>' ); ?>
		</footer>
        
	</article>

<?php endwhile; // END LOOP

}  // END LOOP CONDITIONAL 

if (  $wp_query->max_num_pages > 1 ) : ?>
<nav class="post-nav" role="navigation">
  <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older Posts', 'themename' ) ); ?></div>
  <div class="nav-next"><?php previous_posts_link( __( 'Newer Posts <span class="meta-nav">&rarr;</span>', 'themename' ) ); ?></div>
</nav>
<?php endif; ?>
