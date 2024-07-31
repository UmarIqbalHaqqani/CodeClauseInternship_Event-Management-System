<?php
/*
 * Template Name: Eventin Certificate
 *  */

if( wp_is_block_theme() ){
    block_header_area();
    wp_head();
}else{
    get_header();
}

// Required scripts to generate Certificate PDF
wp_enqueue_script('html-to-image');
wp_enqueue_script('etn-pdf-gen');

while (have_posts()) : the_post(); ?>

<!-- Viewport meta overwritten because of pdf layout. It should not be changed even in mobile device. -->
<meta name="viewport" content="width=900 initial-scale=1">

<!-- Layout wrapper -->
<div class="etn-certificate-container">

    <div class="etn-pdf-content" id="etn-pdf-container">
        <?php the_content(); ?>
    </div>
    <div class="etn-control-header">
        <button id="etn-certificate-download" class="etn-certificate-download etn-btn">
            <?php echo esc_html__('Download Certificate', 'eventin-pro'); ?>
        </button>
    </div>

</div>
<!-- Layout wrapper end -->
<?php     
endwhile;
if( wp_is_block_theme() ){
    block_footer_area();
    wp_footer();
}else{
    get_footer();
}
?>