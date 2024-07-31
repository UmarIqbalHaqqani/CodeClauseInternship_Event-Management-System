<?php

use \Etn_Pro\Utils\Helper;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\ManageGroupAccess as GroupAccess;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Helper as BuddyBossHelper;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\Frontend as Frontend;

$current_user_profile = Frontend::instance()->get_current_user_profile();

$group_id = null;
$access_create = true;
if(bp_is_groups_component() && function_exists('bp_get_current_group_id')){
	$group_id = bp_get_current_group_id();
}
if($group_id != null){
	// group wise manage role access
	$access_create = GroupAccess::instance()->bp_etn_is_user_can_event_change($group_id);
}

?>
<div class="etn-bb-parent-wrapper">
    <div class="etn-bb-event-area">
        <div class="etn-bb-sidebar-area">
            <div class="etn-bb-sidebar-heading">
                <h3><?php echo esc_html__('Events List', 'eventin-pro'); ?></h3>
                <div class="hide_item" id="reload_BuddyBoss_sidebar" aria-hidden="true">
                    <?php __('Hidden element for sidebar reload', 'eventin-pro'); ?>
                </div>
                <?php if( ( null !== $group_id && true == $access_create ) || ( null == $group_id  &&  $current_user_profile &&  GroupAccess::instance()->current_user_can_submit_event()) ): ?>
                <button class="etn-bb-create-event-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <?php echo esc_html__('Event Dashboard', 'eventin-pro'); ?>
                </button>
                <?php endif; ?>
            </div>
            <div class="etn-bb-sidebar-content">
                <div class="etn-bb-event-filter-wrapper">
                    <form action="" class="etn-bb-event-search-form" method="get" data-bp-search="etn-events" id="etn_buddyboss_search_event" id="bp_etn_events_search_form">
                        <input type="search" name="event-bb-search" id="bp_etn_events_search" placeholder="<?php echo esc_attr__('Search Event', 'eventin-pro'); ?>">
                        <button type="submit" class="etn-bb-search-action-btn" aria-label="<?php echo esc_html__('Event Search Button', 'eventin-pro'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </form>
                    <?php
                    echo Helper::get_etn_taxonomy_ids('etn_category', 'event_filter_search_cat_ids', false);
                    ?>
                </div>
                <?php
                $data = BuddyBossHelper::instance()->group_event_list( $limit = "-1" );
                if ( !empty( $data ) ) {
                    ?>
                    <div class="etn-bb-event-list-item-wrapper" id="eventss-list">
                        <?php foreach( $data as $post ) { ?>
                            <a href="#" onclick="return etn_bp_events_load('<?php echo esc_attr( $post->ID ); ?>');">
                                <div class="events-item etn-bb-list-item" id="event-show-row-<?php echo esc_attr($post->ID); ?>" data-events-id="<?php echo esc_attr($post->ID); ?>">
                                    <div class="etn-bb-event-list-title">
                                        <h4 class="events-title">
                                            <?php echo get_the_title( $post->ID ); ?>
                                        </h4>
                                    </div>
                                    <div class="etn-bb-event-list-desc">
                                        <ul class="etn-bb-list-meta">
                                            <li>
                                                <p>
                                                    <?php echo Helper::etn_display_date($post->ID, 'yes', 'yes'); ?>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <h4 class="events-title"><?php echo esc_html__('No events found', 'eventin-pro'); ?></h4>
                <div class="etn-bb-event-list-item-wrapper">
                    <?php foreach( $data as $post ) { ?>
                        <div class="events-item etn-bb-list-item" id="event-show-row-<?php echo esc_attr($post->ID); ?>" data-events-id="<?php echo esc_attr($post->ID); ?>">
                            <div class="etn-bb-event-list-title">
                                <h4>
                                <a href="#" class="events-title" onclick="return etn_bp_Events_Load('<?php echo esc_attr( $post->ID ); ?>');">
																<?php echo get_the_title( $post->ID ); ?>
																</a>
                                </h4>
                            </div>
                            <div class="etn-bb-event-list-desc">
                                <ul class="etn-bb-list-meta">
                                    <li>
                                        <p>
                                            <?php
                                                echo Helper::etn_display_date($post->ID, 'yes', 'yes');
                                            ?>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="etn-bb-create-event-area">
            <div class="etn-event-single-wrap" id="etn_default_event_list">
                <?php
                
                    $data = BuddyBossHelper::instance()->group_event_list( $limit = "1" );
                    if ( !empty( $data ) && !empty($data[0] )) :
                        $event_details = $data[0];
                        include ETN_PRO_MODULES . "integrations/buddyboss/frontend/views/single-event-details.php";
                    else: ?>
                        <h4><?php echo esc_html__('No events found', 'eventin-pro'); ?></h4>
                    <?php endif;
                ?>
            </div>
						<?php if( ( null !== $group_id && true == $access_create ) || ( null == $group_id &&  GroupAccess::instance()->current_user_can_submit_event()) ): ?>
                <?php include \Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/frontend/views/create_event.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>