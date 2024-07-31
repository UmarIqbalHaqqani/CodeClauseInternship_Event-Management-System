<?php
/**
 * Eventin external shortcdoes events template
 * 
 * @package EventinPro
 */
defined( 'ABSPATH' ) || exit;
wp_head();

?>
<main id="eventin-external-event-list">
<?php
    if ( have_posts() ) {

        while ( have_posts() ) {
            the_post();
            the_content();
        }
    }
wp_footer();
?>

</main>
