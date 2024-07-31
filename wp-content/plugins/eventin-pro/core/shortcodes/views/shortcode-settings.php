<?php
    use Etn\Utils\Helper;
?>
   <!-- calendar with event -->
   <div class="shortcode-generator-wrap">
        <div class="shortcode-generator-main-wrap">
            <div class="shortcode-generator-inner">
                <div class="shortcode-popup-close">x</div>
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Select Calendar Event', 'eventin-pro'); ?></h3>
                            <?php
                                $event_list = [
                                    'etn_pro_calendar_standard' => esc_html__('Event With Calendar', 'eventin-pro'),
                                ];
                            echo Helper::get_option_range($event_list);
                            ?>
                        </div>
                    </div>
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                            <?php
                            echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                            ?>
                        </div>
                    </div> 
                </div>
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                         <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('All Day Slot Enable', 'eventin-pro'); ?></h3>
                            <?php 
                            $slot = [
                                "all_day_slot='true'" => esc_html__('Yes', 'eventin-pro'),
                                "all_day_slot='false'" => esc_html__('No', 'eventin-pro'),
                                ];
                            echo Helper::get_option_range($slot);
                        ?>
                        </div>
                    </div>
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Display Calendar', 'eventin-pro'); ?></h3>
                                <?php 
                                $view = [
                                    'calendar_view="dayGridMonth"' => esc_html__('Monthly View', 'eventin-pro'),
                                    'calendar_view="timeGridWeek"' => esc_html__('Weekly View', 'eventin-pro'),
                                    'calendar_view="timeGridDay"' => esc_html__('Daily View', 'eventin-pro'),
                                 ];
                                echo Helper::get_option_range($view);
                            ?>
                            </div>
                    </div>
                </div>

                <div class="etn-row">
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_child_event'); ?>
                        </div>
                    </div>
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
														<?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                        </div>
                    </div>
                </div>
               
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                        <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                        <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                         <input type="hidden" class="script-name" value="events-with-calendar-pro">
                    </div>
                </div>
                <div class="attr-form-group etn-label-item copy_shortcodes">
                    <div class="etn-meta">
                        <input type="text" readonly name="etn_event_label" id="events-calendar-pro-shortcode" value="[events]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                        <button type="button" onclick="copyTextData('events-calendar-pro-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="attr-form-group etn-label-item">
            <div class="etn-label">
                <label><?php esc_html_e('Events With Calendar (Pro)', 'eventin-pro'); ?> </label>
                <div class="etn-desc"> <?php esc_html_e('Display "events in calendar premium view" in any specific location.', 'eventin-pro'); ?> </div>
            </div>
            <div class="etn-meta">
                <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
            </div>
        </div>
    </div>

   <!-- calendar with event -->
   <div class="shortcode-generator-wrap">
        <div class="shortcode-generator-main-wrap">
            <div class="shortcode-generator-inner">
                <div class="shortcode-popup-close">x</div>
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Select Calendar Event', 'eventin-pro'); ?></h3>
                            <?php
                                $event_list = [
                                    'etn_pro_calendar_list' => esc_html__('Event With Calendar', 'eventin-pro'),
                                ];
                            echo Helper::get_option_range($event_list);
                            ?>
                        </div>
                    </div>
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                            <?php
                            echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                            ?>
                        </div>
                    </div> 
                </div>
                <div class="etn-row">
                 
                    <div class="etn-col-lg-12">
                        <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Display Calendar', 'eventin-pro'); ?></h3>
                                <?php 
                                $view = [
                                    'calendar_view="listMonth"' => esc_html__('Monthly List View', 'eventin-pro'),
                                    'calendar_view="listWeek"' => esc_html__('Weekly List View', 'eventin-pro'),
                                    'calendar_view="listDay"' => esc_html__('Daily List View', 'eventin-pro'),
                                 ];
                                echo Helper::get_option_range($view);
                            ?>
                            </div>
                    </div>
                </div>
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                                <?php echo Helper::get_show_hide('show_child_event'); ?>
                        </div>
                    </div>
                    <div class="etn-col-lg-6">
                        <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
														<?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                        </div>
                    </div>
                </div>
                <div class="etn-row">
                    <div class="etn-col-lg-6">
                         <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                         <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                        <input type="hidden" class="script-name" value="events-with-calendar-list-pro">
                    </div>
                </div>
                <div class="attr-form-group etn-label-item copy_shortcodes">
                    <div class="etn-meta">
                        <input type="text" readonly name="etn_event_label" id="events-calendar-list-pro-shortcode" value="[events]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                        <button type="button" onclick="copyTextData('events-calendar-list-pro-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="attr-form-group etn-label-item">
            <div class="etn-label">
                <label><?php esc_html_e('Events With Calendar List (Pro)', 'eventin-pro'); ?> </label>
                <div class="etn-desc"> <?php esc_html_e('Add "events in calendar premium list style view" to show it in any specific location.', 'eventin-pro'); ?> </div>
            </div>
            <div class="etn-meta">
                <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
            </div>
        </div>
    </div>

  <!-- speakers start -->
  <div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <?php
                            $etn_pro_speakers_classic = [
                                'etn_pro_speakers_classic' => esc_html__('Speaker Classic', 'eventin-pro'),
                                'etn_pro_speakers_standard' => esc_html__('Speaker Standard', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($etn_pro_speakers_classic, 'get_template');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap speaker_style">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(3,'style', 'style-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_speaker_category', 'speakers_category');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Column', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_option_style(4, 'speaker_col', '', 'Column '); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                            <?php
                                $orderby = [
                                    "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                    "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                    "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                    "orderby='name'" => esc_html__('name', 'eventin-pro'),
                                ];
                                echo Helper::get_option_range($orderby);
                            ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>

                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Designation', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_designation'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Social', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_social'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-field-wrap">
                <h3><?php echo esc_html__('Limit', 'eventin-pro'); ?></h3>
                <input type="number" data-count ="<?php echo esc_attr('speaker_count') ?>" class="post_count etn-setting-input" value="20">
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="speakers-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="speaker-pro-shortcode" value="[etn_pro_speakers_classic ]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('speaker-pro-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Speakers (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "premium style speakers profile" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

 <!-- organizer start -->
 <div class="shortcode-generator-wrap">
            <div class="shortcode-generator-main-wrap">
                <div class="shortcode-generator-inner">
                    <div class="shortcode-popup-close">x</div>

                    <div class="etn-row">
                        <div class="etn-col-lg-6">
                            <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Select Organizer', 'eventin-pro'); ?></h3>
                                <select  class="get_template etn-setting-input">
                                    <option value="etn_pro_organizers"> <?php echo esc_html__('Organizer', 'eventin-pro'); ?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="etn-col-lg-6">
                            <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Column', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_option_style(4, 'etn_speaker_col', '', 'Column '); ?>
                        </div>
                    </div>
                    </div>
                    <div class="etn-row">
                        <div class="etn-col-lg-12">
                            <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                                <?php
                                echo Helper::get_etn_taxonomy_ids('etn_speaker_category', 'cat_id');
                                ?>
                            </div>
                        </div>
                      
                    </div>
                    
                    <div class="etn-row">
                        <div class="etn-col-lg-6">
                            <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                                <?php
                                $orderBy = [
                                    "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                    "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                    "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                    "orderby='name'" => esc_html__('name', 'eventin-pro'),
                                ];
                                echo Helper::get_option_range($orderBy);
                            ?>
                            </div>
                        </div>
                        <div class="etn-col-lg-6">
                            <div class="etn-field-wrap">
                                <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                                <?php echo Helper::get_order('order'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Post Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('limit') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                    <div class="etn-row">
                        <div class="etn-col-lg-6">
                            <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                            <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                            <input type="hidden" class="script-name" value="organizer-list-pro">
                        </div>
                    </div>
                   
                   
                    <div class="attr-form-group etn-label-item copy_shortcodes">
                        <div class="etn-meta">
                            <input type="text" readonly name="etn_event_label" id="organizer-shortcode" value="[etn_pro_organizers]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                            <button type="button" onclick="copyTextData('organizer-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="attr-form-group etn-label-item">
                <div class="etn-label">
                    <label><?php esc_html_e('Organizer List (Pro)', 'eventin-pro'); ?> </label>
                    <div class="etn-desc"> <?php esc_html_e('A "premium style organizers profile" to show it in any specific location.', 'eventin-pro'); ?> </div>
                </div>
                <div class="etn-meta">
                    <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
                </div>
            </div>
        </div>

     <!-- speakers start -->
  <div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <select  class="get_template etn-setting-input">
                            <option value="etn_pro_speakers_sliders"> <?php echo esc_html__(' Speaker Slider', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(5,'style', 'style-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_speaker_category', 'categories_id');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Auto Play', 'eventin-pro'); ?></h3>
                        <?php
                            $auto_play = [
                                "auto_play='no'" => esc_html__('No', 'eventin-pro'),
                                "auto_play='yes'" => esc_html__('Yes', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($auto_play, 'auto_play');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                        <?php
                            $orderBy = [
                                "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                "orderby='name'" => esc_html__('name', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($orderBy);
                        ?>

                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Designation', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_designation'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Social', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_social'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Slider Nav', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('slider_nav_show'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Slider Dot', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('slider_dot_show'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('speaker_count') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Slider Count', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('slider_count') ?>" class="post_count etn-setting-input" value="3">
                    </div>
                </div>
            </div>
         
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="etn-speakers-slider-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="speaker-pro-slider-shortcode" value="[etn_pro_speakers_sliders ]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('speaker-pro-slider-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Speakers Slider (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('A "premium style speakers profile as a slider" to show it in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event start -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_events_classic"> <?php echo esc_html__('Event Classic', 'eventin-pro'); ?> </option>
                            <option value="etn_pro_events_standard" selected> <?php echo esc_html__('Event Standard', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap event_pro_style">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(3,'style', 'style-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Tags', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_tags', 'event_tag_ids');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                        <?php
                            $orderBy = [
                                "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                "orderby='name'" => esc_html__('name', 'eventin-pro'),
                                "orderby='etn_start_date'" => esc_html__('Event Start Date', 'eventin-pro'),
                                "orderby='etn_end_date'" => esc_html__('Event End Date', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($orderBy);
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Filter By Status', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_event_status('filter_with_status'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Column', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_option_style(4, 'event_col', '', 'Column '); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Description', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_desc'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('desc_limit') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                 
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attendee Count', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_attendee_count'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attend Button', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_btn'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Thumbnail', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_thumb'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Category', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_category'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('event_count') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show End Date', 'eventin-pro'); ?></h3>
                        <?php
                            $show_end_date = [
                                "show_end_date='yes'" => esc_html__('Yes', 'eventin-pro'),
                                "show_end_date='no'" => esc_html__('No', 'eventin-pro')
                            ];
                        echo Helper::get_option_range($show_end_date);
                        ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_child_event'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
												<?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Event Location', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_event_location'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="speakers-slider-pro">
                </div>
            </div>
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_events_classic-shortcode" value="[etn_pro_events_classic]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_events_classic-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Events (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "premium style event details" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- One_line_event Shortcode start -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_events_one_line" selected> <?php echo esc_html__('Event One line', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Tags', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_tags', 'event_tag_ids');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                        <?php
                            $orderBy = [
                                "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                "orderby='name'" => esc_html__('name', 'eventin-pro'),
                                "orderby='etn_start_date'" => esc_html__('Event Start Date', 'eventin-pro'),
                                "orderby='etn_end_date'" => esc_html__('Event End Date', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($orderBy);
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Filter By Status', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_event_status('filter_with_status'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Button', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_btn'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Thumbnail', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_thumb'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Category', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_category'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('event_count') ?>" class="post_count etn-setting-input" value="3">
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show End Date', 'eventin-pro'); ?></h3>
                        <?php
                            $show_end_date = [
                                "show_end_date='yes'" => esc_html__('Yes', 'eventin-pro'),
                                "show_end_date='no'" => esc_html__('No', 'eventin-pro')
                            ];
                        echo Helper::get_option_range($show_end_date);
                        ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_child_event'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Event Location', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_event_location'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="etn_pro_events_one_line">
                </div>
            </div>
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_events_one_line-shortcode" value="[etn_pro_events_one_line]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_events_one_line-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Events One Line (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "premium style event details" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event tab start -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_events_tab"> <?php echo esc_html__('Event Tab', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(4,'style', 'event-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Tags', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_tags', 'event_tag_ids');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                        <?php
                            $orderby = [
                                "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                "orderby='etn_start_date'" => esc_html__('Event Start Date', 'eventin-pro'),
                                "orderby='etn_end_date'" => esc_html__('Event End Date', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($orderby);
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Filter By Status', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_event_status('filter_with_status'); ?>

                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Column', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_option_style(4, 'event_col', '', 'Column '); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Description', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_desc'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('desc_limit') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                 
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attendee Count', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_attendee_count'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attend Button', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_btn'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Thumbnail', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_thumb'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Category', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_category'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('event_count') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show End Date', 'eventin-pro'); ?></h3>
                        <?php
                            $show_end_date = [
                                "show_end_date='yes'" => esc_html__('Yes', 'eventin-pro'),
                                "show_end_date='no'" => esc_html__('No', 'eventin-pro')
                            ];
                        echo Helper::get_option_range($show_end_date);
                        ?>
                    </div>
                </div>
            </div>           
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_child_event'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
												<?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Event Location', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_event_location'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="events-pro">
                </div>
            </div>
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_events_tab-shortcode" value="[etn_pro_events_tab]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_events_tab-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Events Tab (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "premium style event details as a tab" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event tab start -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_events_sliders"> <?php echo esc_html__('Event Slider', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(3,'style', 'event-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_category', 'event_cat_ids');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Tags', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_etn_taxonomy_ids('etn_tags', 'event_tag_ids');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order By', 'eventin-pro'); ?></h3>
                        <?php
                            $orderby = [
                                "orderby='title'" => esc_html__('Title', 'eventin-pro'),
                                "orderby='ID'" => esc_html__('ID', 'eventin-pro'),
                                "orderby='post_date'" => esc_html__('Post Date', 'eventin-pro'),
                                "orderby='etn_start_date'" => esc_html__('Event Start Date', 'eventin-pro'),
                                "orderby='etn_end_date'" => esc_html__('Event End Date', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($orderby);
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Filter By Status', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_event_status('filter_with_status'); ?>

                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('event_count') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Event Slider Count', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('event_slider_count') ?>" class="post_count etn-setting-input" value="3">
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Description', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_desc'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('desc_limit') ?>" class="post_count etn-setting-input" value="20">
                    </div>
                 
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attendee Count', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_attendee_count'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Attend Button', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_btn'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Thumbnail', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_thumb'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                     <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Category', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_category'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Slider Nav', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('slider_nav_show'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Slider Dot', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('slider_dot_show'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Auto Play', 'eventin-pro'); ?></h3>
                        <?php
                            $auto_play = [
                                "auto_play='no'" => esc_html__('No', 'eventin-pro'),
                                "auto_play='yes'" => esc_html__('Yes', 'eventin-pro'),
                            ];
                            echo Helper::get_option_range($auto_play, 'auto_play');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show End Date', 'eventin-pro'); ?></h3>
                        <?php
                            $show_end_date = [
                                "show_end_date='yes'" => esc_html__('Yes', 'eventin-pro'),
                                "show_end_date='no'" => esc_html__('No', 'eventin-pro')
                            ];
                        echo Helper::get_option_range($show_end_date);
                        ?>
                    </div>
                </div>                
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Recurring Child Events', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_child_event'); ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Recurring Parent Events', 'eventin-pro'); ?></h3>
												<?php echo Helper::get_show_hide_recurring('show_parent_event', 1 ); ?>
                    </div>
                </div>
            </div>
          
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Show Event Location', 'eventin-pro'); ?></h3>
                            <?php echo Helper::get_show_hide('show_event_location'); ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="events-tab-pro">
                </div>
            </div>
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_events_sliders-shortcode" value="[etn_pro_events_sliders]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_events_sliders-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Events Slider (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Add "premium style event details as a slider" to show it in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- coudown List -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <select  class="get_template etn-setting-input">
                            <option value="etn_pro_countdown"> <?php echo esc_html__('Countdown Timer', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                            <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                            <?php
                            echo Helper::get_posts_ids('etn', 'event_id' ,' ');
                            ?>
                        </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="events-slider-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_countdown-shortcode" value="[etn_pro_countdown]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_countdown-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Event Countdown (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Add "event starting time as countdown" to display it in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>


<!-- schedules -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Schedule View', 'eventin-pro'); ?></h3>
                        <select  class="get_template get_schedule_template etn-setting-input">
                            <option value="etn_pro_schedules_list"> <?php echo esc_html__('Schedules List View', 'eventin-pro'); ?> </option>
                            <option value="etn_pro_schedules_tab"> <?php echo esc_html__('Schedule Tab View', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Order', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_order('order'); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Schedule(s)', 'eventin-pro'); ?></h3>
                        <?php
                          echo Helper::get_posts_ids('etn-schedule','ids' ,' ');
                        ?>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Schedule Style', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_option_style(2, 'style', 'style-', 'Style '); ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="event-countdown-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_schedules_tab-shortcode" value="[etn_pro_schedules_tab]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_schedules_tab-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Schedules Pro', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "premium style schedules" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event ticket -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select ticket form', 'eventin-pro'); ?></h3>
                        <select  class="get_template  etn-setting-input">
                            <option value="etn_pro_ticket_form"> <?php echo esc_html__('Event Ticket Form', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_posts_ids('etn', 'id' ,' ');
                        ?>
                    </div>
                </div>
            </div>
            <div class="etn-row">
                 <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Title', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_title'); ?>
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="schedules-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_ticket_form-shortcode" value="[etn_pro_ticket_form]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_ticket_form-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Event Ticket Form (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('The "events ticketing form" is for the ticketing selling option at a specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- related event  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select template', 'eventin-pro'); ?></h3>
                        <select  class="get_template  etn-setting-input">
                            <option value="etn_pro_related_events"> <?php echo esc_html__('Related Event', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_posts_ids('etn', 'id' ,' ');
                        ?>
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="event-ticket-form-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_related_events-shortcode" value="[etn_pro_related_events]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_related_events-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Related Event widget (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Display "related events" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- Event Add to Calendar -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select template', 'eventin-pro'); ?></h3>
                        <select  class="get_template  etn-setting-input">
                            <option value="etn_pro_add_calendar"> <?php echo esc_html__('Add to Calendar', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_posts_ids('etn', 'id' ,' ');
                        ?>
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="event-ticket-form-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_add_calendar-shortcode" value="[etn_pro_add_calendar]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_add_calendar-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Add to Calendar (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Display "Event Add to Calendar Options"', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- related event  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select template', 'eventin-pro'); ?></h3>
                        <select  class="get_template etn-setting-input">
                            <option value="etn_pro_attendee_list"> <?php echo esc_html__('Attendee List', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <?php
                        echo Helper::get_posts_ids('etn', 'id' ,' ');
                        ?>
                    </div>
                </div>
            </div>

            <div class="etn-row">
                 <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Avater', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_avatar'); ?>
                    </div>
                </div>
                 <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Show Email', 'eventin-pro'); ?></h3>
                        <?php echo Helper::get_show_hide('show_email'); ?>
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="related-event-widget-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_attendee_list-shortcode" value="[etn_pro_attendee_list]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_attendee_list-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Attendee List (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "all of the attendees of the event" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- recurring event  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select template', 'eventin-pro'); ?></h3>
                        <select  class="get_template etn-setting-input">
                            <option value="etn_event_recurring"> <?php echo esc_html__('Recurring Event', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Event', 'eventin-pro'); ?></h3>
                        <?php
                        $query_args = [
                            'post_type'             => 'etn',
                            'post_status'           => 'publish',
                            'posts_per_page'        => -1,
                            'post_parent__not_in'   => [ 0 ],
                            'suppress_filters'      => false,
                        ];
                        echo Helper::get_posts_ids('etn', 'single_event_ids' ,' ', $query_args);
                        ?>
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="attendee-list-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_event_recurring-shortcode" value="[etn_event_recurring]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_event_recurring-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Recurring Event (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Display "recurring events" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event location  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_event_location_list"> <?php echo esc_html__('Event Location', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'eventin-pro'); ?></h3>
                        <?php Helper::get_option_style(1,'style', 'style-', 'style ' ); ?>
                    </div>
                </div>
                
            </div>

            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Limit', 'eventin-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('location_limit') ?>" class="post_count etn-setting-input" value="5">
                    </div>
                </div>
            </div>
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="recurring-event-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_event_location_list-shortcode" value="[etn_pro_event_location_list]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_event_location_list-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Event Location (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Show "events locations" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div> 
</div>
<!-- event location  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_dashboard"> <?php echo esc_html__('Event Frontend Dashboard', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div> 
                
            </div> 
        
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="event-location-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_dashboard-shortcode" value="[etn_pro_dashboard]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_dashboard-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Event Frontend Dashboard (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e('Add the "front-end event management option" in any specific location.', 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>

<!-- event Certificate  -->

<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="etn-row">
                <div class="etn-col-lg-12">
                    <div class="etn-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'eventin-pro'); ?></h3>
                        <select class="get_template etn-setting-input">
                            <option value="etn_pro_event_title"> <?php echo esc_html__('Event Title', 'eventin-pro'); ?> </option>
                            <option value="etn_pro_event_date"> <?php echo esc_html__('Event Date', 'eventin-pro'); ?> </option>
                            <option value="etn_pro_attendee_name"> <?php echo esc_html__('Attendee Name', 'eventin-pro'); ?> </option>
                            <option value="etn_pro_ticket_id"> <?php echo esc_html__('Ticket ID', 'eventin-pro'); ?> </option>
                        </select>
                    </div>
                </div>  
            </div> 
            <div class="etn-row">
                <div class="etn-col-lg-6">
                    <button type="button" class="etn-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'eventin-pro'); ?></button>
                    <button type="button" class="etn-btn shortcode-script-btn"><?php echo esc_html__('Get Script', 'eventin-pro'); ?></button>
                    <input type="hidden" class="script-name" value="event-certificate-pro">
                </div>
            </div>
            
            <div class="attr-form-group etn-label-item copy_shortcodes">
                <div class="etn-meta">
                    <input type="text" readonly name="etn_event_label" id="etn_pro_event_info-shortcode" value="[etn_pro_event_title]" class="etn-setting-input etn_include_shortcode" placeholder="<?php esc_attr_e('Label Text', 'eventin-pro'); ?>">
                    <button type="button" onclick="copyTextData('etn_pro_event_info-shortcode');" class="etn_copy_button etn-btn"><span class="dashicons dashicons-category"></span></button>
                </div>
            </div>
        </div>
    </div> 
		<div class="attr-form-group etn-label-item">
        <div class="etn-label">
            <label><?php esc_html_e('Event Certificate (Pro)', 'eventin-pro'); ?> </label>
            <div class="etn-desc"> <?php esc_html_e("You can use generate shortcode", 'eventin-pro'); ?> </div>
        </div>
        <div class="etn-meta">
            <button type="button" class="etn-btn s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'eventin-pro'); ?></button>
        </div>
    </div>
</div>
<?php
	echo apply_filters('eventin_pro/shortcode_option',null);
?>


<?php return; ?>