<?php
    global $post;
?>

<?php do_action( 'dokan_dashboard_wrap_start' ); ?>

<div class="dokan-dashboard-wrap">
    <?php
        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php
            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */

            do_action( 'dokan_eventin_content_inside_before' );

        ?>

        <article class="etn-content-area">

            <!-- Show response message -->
            <div class="dokan-ajax-response">
                <?php do_action( 'dokan_settings_load_ajax_response' ); ?>
            </div>
            
        </article>
        <!-- .dashboard-content-area -->


        <!-- Start Attendee List Markup -->
       
            <div id="etn_multivendor_attendee_list" class="etn-multivendor-attendee-list">
            <?php 
                // wp_enqueue_media();
                // wp_enqueue_style('etn-frontend-submission');
                // wp_enqueue_script('etn-frontend-submission');
            ; ?>
            </div>
            <!-- <h2>Attendee List</h2> -->
        
        <!-- End Attendee List Markup -->


        <?php
            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>


    </div><!-- .dokan-dashboard-content -->

    <?php
        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

    </div><!-- .dokan-dashboard-wrap -->
    
<?php do_action( 'dokan_dashboard_wrap_end' ); ?>