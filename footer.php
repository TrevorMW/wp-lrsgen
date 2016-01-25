<?php
/**
 * @package WordPress
 * @subpackage themename
 */

?>

      </div>
    </div>

  </main>

<?php wp_footer(); ?>

<?php if( !is_admin() ) { ?>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_KEY ?>&callback=load_map"></script>
<?php } ?>

</body>
</html>