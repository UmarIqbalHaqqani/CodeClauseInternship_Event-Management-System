<?php   

$banner_image         = EXHIBZ_IMG . '/banner/banner_bg.jpg';  // Default page banner static image
$exhibz_banner_settings = exhibz_option('event_banner_setting'); 
$exhibz_show_breadcrumb =  (isset($exhibz_banner_settings['event_show_breadcrumb'])) ? $exhibz_banner_settings['event_show_breadcrumb'] : true;

// Banner image can be overwritten from page meta option
$banner_image         = !empty($exhibz_banner_settings['banner_event_image']['url']) ? $exhibz_banner_settings['banner_event_image']['url'] : $banner_image;
$meta_banner_image = exhibz_meta_option(get_the_ID(), 'event_banner_image');
if(!empty($meta_banner_image['url'])) {
	$banner_image = $meta_banner_image['url'];
}



if( isset($banner_image)){
   $banner_image = 'style="background-image:url('.esc_url( $banner_image ).');"';
}
?>



<div class="banner-area banner-solid single-event-banner" <?php echo exhibz_kses( $banner_image ); ?>>
   <div class="container">
         <div class="row">
            <div class="col-md-12">
               <?php if(isset($exhibz_show_breadcrumb) && $exhibz_show_breadcrumb == true): ?>
                  <?php exhibz_get_breadcrumbs('/'); ?>
               <?php endif; ?>
            </div>
            <div class="col-md-7">
               <div class="banner-title-des">
                  <?php $terms = get_the_terms( get_the_ID(), 'etn_category' );
                  if ( $terms && ! is_wp_error( $terms ) ){ ?>
                     <ul class="list-unstyled event-categories">
                        <?php

                        if( \Etn\Utils\Helper::get_child_events(get_the_ID()) !== false) {
                           // It's recurring event
                           ?>
                            <li class="recurring-tools"><?php echo esc_html__( "Attend Event", 'exhibz' )?></a></li>
                           <?php                       }
                        foreach ( $terms as $term ) {
                           $term_link = get_term_link( $term, array( 'etn_category') );
                           ?>
                           <li><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?><span>,</span></a></li>
                           <?php                       }
                        ?>
                     </ul>
                  <?php } ?>
                  <h2 class="banner-title">
                     <?php the_title(); ?> 
                  </h2>

                     <div class="date-location">
                        <?php
                        $etn_event_location = '';
                        if( \Wpeventin::version() >= '4.0.0' && class_exists( 'Wpeventin' ) ) {  
                           $etn_event_location = \Etn\Core\Event\Helper::instance()->display_event_location( get_the_ID() );
                        }
                        $etn_start_date     = get_post_meta(get_the_ID(), 'etn_start_date', true);
                        $data           = \Etn\Utils\Helper::single_template_options( get_the_ID() );
                        
                        ?>
                        <span><i class="far fa-calendar-alt"></i> <?php echo  esc_html($data['event_start_date'] ); ?></span>
                        <?php if($etn_event_location != ''){ ?>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($etn_event_location); ?></span>
                        <?php } ?>
                     </div>
               </div>

            </div>
            <div class="col-md-5">
               <?php              $etn_start_date   = get_post_meta( get_the_ID(), 'etn_start_date', true );
               $event_start_time = get_post_meta( get_the_ID(), 'etn_start_time', true );
               exhibz_countdown_markup($etn_start_date, $event_start_time);
               ?>
            </div>
         </div>
   </div>
</div>