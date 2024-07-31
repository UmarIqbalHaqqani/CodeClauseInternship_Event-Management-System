<?php

namespace Etn_Pro\Core\Shortcodes;

use Etn_Pro\Utils\Helper;
use Mpdf\Tag\Em;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    /**
     * Call hooks function
     *
     * @return void
     */
    public function init() {

        add_filter( 'eventin/shortcode/pro_shortcode', [$this, 'add_pro_shortcodes'] );

        //[etn_pro_organizers cat_id='19' order='asc' limit='3' url='yes' logo='yes' /]
        add_shortcode( "etn_pro_organizers", [$this, "organizers_widget"] );

        // [speakers_classic speaker_count ="10" speaker_order="DESC" speakers_category ="2" show_designation="yes" show_social ="yes" ]
        add_shortcode( "etn_pro_speakers_classic", [$this, "speakers_classic"] );
        add_shortcode( "etn_pro_speakers_standard", [$this, "speakers_standard"] );
        add_shortcode( "etn_pro_speakers_sliders", [$this, "speakers_sliders"] );

        // [etn_pro_events_classic event_count ="10" order="DESC" desc_limit="yes" ]
        add_shortcode( "etn_pro_events_classic", [$this, "events_classic"] );
        add_shortcode( "etn_pro_events_standard", [$this, "events_standard"] );
        add_shortcode( "etn_pro_events_sliders", [$this, "events_sliders"] );
        add_shortcode( "etn_pro_events_tab", [$this, "events_tab"] );
        add_shortcode( "etn_pro_events_one_line", [$this, "events_one_line"] );
        
        // [etn_pro_countdown event_id="18"/]
        add_shortcode( "etn_pro_countdown", [$this, "event_countdown"] );

        // [etn_pro_schedules_tab ids="16,33" order='asc'/]
        add_shortcode( "etn_pro_schedules_tab", [$this, "schedules_tab"] );

        // [etn_pro_schedules_list id="16" /]
        add_shortcode( "etn_pro_schedules_list", [$this, "schedules_list"] );

        // [etn_pro_ticket_form id="16" show_title="yes" /]
        add_shortcode( "etn_pro_ticket_form", [$this, "event_ticket_form"] );

        // [etn_pro_related_events id="16" /]
        add_shortcode( "etn_pro_related_events", [$this, "related_events"] );

        add_shortcode( "etn_pro_add_calendar", [$this, "external_calendar"] );

        // [etn_pro_attendee_list id="16" /]
        add_shortcode( "etn_pro_attendee_list", [$this, "attendee_list"] );

        // [etn_event_recurring single_event_ids="16" /]
        add_shortcode( "etn_event_recurring", [$this, "event_recurring"] );

        //[etn_pro_calendar_standard limit='1' event_cat_ids='1,2' event_tag_ids='1,2' /]
        add_shortcode( "etn_pro_calendar_standard", [$this, "calendar_standard_widget"] );

        //[etn_pro_calendar_standard limit='1' event_cat_ids='1,2' event_tag_ids='1,2' /]
        add_shortcode( "etn_pro_calendar_list", [$this, "calendar_list_widget"] );
        add_shortcode( "etn_pro_event_location_list", [$this, "event_location_list"] );

        //[etn_pro_event_title /]
        add_shortcode( "etn_pro_event_title", [$this, "get_event_title"] );

        //[etn_pro_event_date /]
        add_shortcode( "etn_pro_event_date", [$this, "get_event_date"] );

        //[etn_pro_attendee_name /]
        add_shortcode( "etn_pro_attendee_name", [$this, "get_attendee_name"] );

        //[etn_pro_ticket_id /]
        add_shortcode( "etn_pro_ticket_id", [$this, "get_ticket_id"] );
        //[etn_pro_dashboard /]
        add_shortcode( "etn_pro_dashboard", [$this, "etn_dashboard"] );

     }
    
    /**
     *  get ticket ID
     *
     * @return void
     */
    public function get_ticket_id( ) {
        $attendee_id = !empty($_GET['attendee_id']) ? absint( $_GET['attendee_id'] ) : '';
        $unique_id   = get_post_meta( $attendee_id, 'etn_unique_ticket_id', true );

        ob_start(); 
        ?>
        <span class="etn-attendee-id">
            <?php 
               echo esc_html('#'. $unique_id );
             ?>
        </span>
        <?php 
        return ob_get_clean();
    }
    /**
     *  get attendee title
     *
     * @return void
     */
    public function get_attendee_name( ) {
        $attendee_id = !empty($_GET['attendee_id']) ? absint( $_GET['attendee_id'] ) : '';

        ob_start(); 
        ?>
        <span class="etn-attendee-name">
            <?php 
                if(!empty($attendee_id)){ 
                    echo esc_html(  get_the_title($attendee_id) );
                }else{
                    echo esc_html(  get_the_title() );
                }
             ?>
        </span>
        <?php 
        return ob_get_clean();
    }
    /**
     *  get event title 
     *
     * @return void
     */
    public function get_event_date( $atts ) {
        // shortcdoe param
        $atts = extract( shortcode_atts( ['event_id' => 0], $atts ) );

        // get param from url
        $get_event_id = ! empty( $_GET['event_id'] ) ? absint( $_GET['event_id'] ) : '';


        if( empty( $get_event_id ) ){
            $get_event_id = $event_id;
        }  

        $event_data    = Helper::single_template_options( $get_event_id );
        
        ob_start(); 

        ?>
        <span class="event-date">
            <?php  
                echo esc_html( $event_data['event_start_date'] ); 
                if( ! empty( $event_data['event_end_date'] ) ){
                    echo esc_html(  ' - ' . $event_data['event_end_date'] );
                } 
             
            ?>
        </span>
        <?php 
        return ob_get_clean();
    }
    /**
     *  get event title 
     *
     * @return void
     */
    public function get_event_title( $atts ) {
        // shortcdoe param
        $atts = extract( shortcode_atts( ['event_id' => 0], $atts ) );

        // get param from url
        $get_event_id = !empty($_GET['event_id']) ? absint( $_GET['event_id'] ) : '';

 
        if(empty($get_event_id)){
            $get_event_id = $event_id;
        } 

        ob_start(); 
        ?>
        <span class="event-title">
            <?php echo esc_html( get_the_title( $get_event_id ) ); ?>
        </span>
        <?php  

        return ob_get_clean();
    }

    /**
     * Add pro settings to general-settings tab
     *
     * @return void
     */
    public function add_pro_shortcodes( $shortcode_view_array ) {
        $shortcode_view_array['pro_shortcode']  = ETN_PRO_DIR . "/core/shortcodes/views/shortcode-settings.php";
        return $shortcode_view_array;
    }


    /**
     * Event location list
     */
    public function event_location_list($atts){

			// shortcode option
			$atts = extract(shortcode_atts(['style' => 'style-1', 'location_limit' => 5],
					$atts
			));

			ob_start();

			// render template.
			if ( file_exists( ETN_PRO_DIR . "/widgets/event-locations/style/".$style.".php" ) ) {
				?>
				<div class="etn-menu-location-wrap etn-location-list-<?php echo esc_attr( $style );?>">
					<?php include ETN_PRO_DIR . "/widgets/event-locations/style/".$style.".php";?>
				</div>
				<?php
			}

			return ob_get_clean();
		}

    /**
     * Organizer list function
     */
    public function organizers_widget( $attributes ) {
        $category_id              = isset( $attributes["cat_id"] ) && ( "" != $attributes["cat_id"] ) ? explode( ',', $attributes["cat_id"] ) : [];
        $etn_speaker_count        = isset( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) ? intval( $attributes["limit"] ) : 3;
        $etn_speaker_order        = !empty( $attributes["order"] ) ? $attributes["order"] : 'DESC';

        // etn_speaker_order
        // sorting order 
        
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby            = !empty( $attributes["orderby"] ) ? $attributes["orderby"] : 'title';
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';
    
        $show_url                 = isset( $attributes["url"] )  && ( "" != $attributes["url"] ) ? $attributes["url"] : 'yes';
        $etn_speaker_company_logo = isset( $attributes["logo"] ) && ( "" != $attributes["logo"] ) ? $attributes["logo"] : 'yes';
        $etn_speaker_col          = !empty( $attributes["etn_speaker_col"] ) ? $attributes["etn_speaker_col"] : '3';
        if ( $etn_speaker_col == 6 ) {
            $etn_speaker_col = 2;
        } else if ( $etn_speaker_col == 5 ) {
            $etn_speaker_col = 2;
        } else if ( $etn_speaker_col == 4 ) {
            $etn_speaker_col = 3;
        } else if ( $etn_speaker_col == 3 ) {
            $etn_speaker_col = 4;
        } else if ( $etn_speaker_col == 2 ) {
            $etn_speaker_col = 6;
        } else if ( $etn_speaker_col == 1 ) {
            $etn_speaker_col = 12;
        }

        ob_start();

        if ( file_exists( ETN_PRO_DIR . "/widgets/organizers/style/organizer-2.php" ) ) {
            include ETN_PRO_DIR . "/widgets/organizers/style/organizer-2.php";
        }

        return ob_get_clean();
    }

    /**
     * Speaker classic style
     */
    public function speakers_classic( $atts ) {
        $atts = extract( shortcode_atts( [
            'style'             => 'style-2',
            'speaker_count'     => 6,
            'order'             => 'DESC',
            'orderby'           => 'title',
            'speaker_col'       => 4,
            'speakers_category' => '',
            'show_designation'  => 'yes',
            'show_social'       => 'yes',
        ], $atts ) );
             
        $speaker_order      = $order ;
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';
        

        if ( $speaker_col == 6 ) {
            $speaker_col = 2;
        } else if ( $speaker_col == 5 ) {
            $speaker_col = 2;
        } else if ( $speaker_col == 4 ) {
            $speaker_col = 3;
        } else if ( $speaker_col == 3 ) {
            $speaker_col = 4;
        } else if ( $speaker_col == 2 ) {
            $speaker_col = 6;
        } else if ( $speaker_col == 1 ) {
            $speaker_col = 12;
        }
        
        $categories_id = [];
        if ( $speakers_category !== '' ) {
            $categories_id = explode( ',', $speakers_category );
        }

        $file_name = "";

        switch ( $style ) {
            case "style-1":
                $file_name = "speaker-2";
                break;
            case "style-2":
                $file_name = "speaker-5";
                break;
            case "style-3":
                $file_name = "speaker-3";
                break;
            default:
                $file_name = "speaker-3";
        }

        ob_start();
        if ( ETN_PRO_DIR . "/widgets/speakers/style/$file_name.php" ) {
            include ETN_PRO_DIR . "/widgets/speakers/style/$file_name.php";
        }
        return ob_get_clean();
    }

    /**
     * Speaker standard style
     */
    public function speakers_standard( $atts ) {
        $atts = extract( shortcode_atts( [
            'speaker_count'     => 6,
            'order'             => 'DESC',
            'orderby'           => 'title',
            'speakers_category' => '',
            'show_designation'  => 'yes',
            'speaker_col'       => 4,
            'show_social'       => 'yes',
        ], $atts ) );

        $speaker_order      = $order ;
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';

        if ( $speaker_col == 6 ) {
            $speaker_col = 2;
        } else if ( $speaker_col == 5 ) {
            $speaker_col = 2;
        } else if ( $speaker_col == 4 ) {
            $speaker_col = 3;
        } else if ( $speaker_col == 3 ) {
            $speaker_col = 4;
        } else if ( $speaker_col == 2 ) {
            $speaker_col = 6;
        } else if ( $speaker_col == 1 ) {
            $speaker_col = 12;
        }

        $categories_id = [];
        if ( $speakers_category !== '' ) {
            $categories_id = explode( ',', $speakers_category );
        }

        ob_start();
        if ( file_exists( ETN_PRO_DIR . "/widgets/speakers/style/speaker-4.php" ) ) {
            include ETN_PRO_DIR . "/widgets/speakers/style/speaker-4.php";
        }
        return ob_get_clean();
    }

    /**
     * Speaker slider style
     */
    public function speakers_sliders( $atts ) {
        $atts = extract( shortcode_atts(
            [
                'style'            => 'style-1',
                'speaker_count'    => 6,
                'slider_count'     => 3,
                'auto_play'        => 'false',
                'order'            => 'DESC',
                'orderby'          => 'title',
                'categories_id'    => '',
                'spaceBetween'     => 30,
                'show_designation' => 'yes',
                'slider_nav_show'  => 'yes',
                'slider_dot_show'  => 'yes',
                'show_social'      => 'yes',
            ],
            $atts
        ) );

        wp_enqueue_style('swiper-bundle-min');
        wp_enqueue_script('swiper-bundle-min');

        $speaker_order      = $order ;
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';
        $speaker_category   = [];
        if ( $categories_id !== '' ) {
            $speaker_category = explode( ',', $categories_id );
        }

        $file_name = "";

        switch ( $style ) {
            case "style-1":
                $file_name = "speaker-1";
                break;
            case "style-2":
                $file_name = "speaker-2";
                break;
            case "style-3":
                $file_name = "speaker-3";
                break;
            case "style-4":
                $file_name = "speaker-4";
                break;
            case "style-5":
                $file_name = "speaker-5";
                break;
            default:
                $file_name = "speaker-1";
        }
        ob_start();
        ?>
        <div class='speaker_shortcode_slider'>
            <?php
            if ( file_exists( ETN_PRO_DIR . "/widgets/speakers-slider/style/$file_name.php" ) ) {
                include ETN_PRO_DIR . "/widgets/speakers-slider/style/$file_name.php";
            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Events standard style
     */
    public function events_standard( $atts ) {
        $atts = extract( shortcode_atts( [
            'event_count'         => 6,
            'event_col'           => 2,
            'event_cat_ids'       => '',
            'event_tag_ids'       => '',
            'order'               => 'DESC',
            'show_desc'           => 'yes',
            'show_category'       => 'yes',
            'show_attendee_count' => 'no',
            'show_btn'            => 'yes',
            'show_thumb'          => 'yes',
            'btn_text'            => 'Attend',
            'desc_limit'          => '25',
            'orderby'             => 'title',
            'filter_with_status'  => '',
            'show_end_date'       => 'no',
            'show_child_event'    => 'no',
            'show_parent_event'   => 'no',
            'show_event_location' => 'yes'
        ], $atts ) );

        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta = "meta_value";
        } else {
            $orderby_meta  = null;
        }
        $show_end_date  = !empty($show_end_date) ? $show_end_date : 'no';
        $event_cat = [];
        $event_tag = [];

        if ( $event_cat_ids !== '' ) {
            $event_cat = explode( ',', $event_cat_ids );
        }

        if ( $event_tag_ids !== '' ) {
            $event_tag = explode( ',', $event_tag_ids );
        }

        if ( $event_col == 6 ) {
            $event_col = 2;
        } else if ( $event_col == 5 ) {
            $event_col = 2;
        } else if ( $event_col == 4 ) {
            $event_col = 3;
        } else if ( $event_col == 3 ) {
            $event_col = 4;
        } else if ( $event_col == 2 ) {
            $event_col = 6;
        } else if ( $event_col == 1 ) {
            $event_col = 12;
        }

        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        ob_start();

        include ETN_PRO_DIR . "/widgets/events-pro/style/event-2.php";

        return ob_get_clean();
    }

    /**
     * Events One Line style
     */
    public function events_one_line( $atts ) {
        $atts = extract( shortcode_atts( [
            'event_count'         => 3,
            'event_cat_ids'       => '',
            'event_tag_ids'       => '',
            'order'               => 'DESC',
            'show_category'       => 'yes',
            'show_btn'            => 'yes',
            'show_thumb'          => 'yes',
            'btn_text'            => 'See Details',
            'orderby'             => 'title',
            'filter_with_status'  => '',
            'show_end_date'       => 'no',
            'show_child_event'    => 'no',
            'show_parent_event'   => 'no',
            'show_event_location' => 'yes'
        ], $atts ) );

        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta = "meta_value";
        } else {
            $orderby_meta  = null;
        }
        $show_end_date  = !empty($show_end_date) ? $show_end_date : 'no';
        $event_cat = [];
        $event_tag = [];

        if ( $event_cat_ids !== '' ) {
            $event_cat = explode( ',', $event_cat_ids );
        }

        if ( $event_tag_ids !== '' ) {
            $event_tag = explode( ',', $event_tag_ids );
        }

        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        ob_start();

        include ETN_PRO_DIR . "/widgets/events-one-line/style/event-1.php";

        return ob_get_clean();
    }

    /**
     * Events classic style
     */
    public function events_classic( $atts ) {

        $atts = extract( shortcode_atts( [
            'style'               => 'style-3',
            'event_count'         => 6,
            'event_col'           => 2,
            'event_cat_ids'       => '',
            'event_tag_ids'       => '',
            'order'               => 'DESC',
            'show_desc'           => 'yes',
            'show_category'       => 'yes',
            'show_attendee_count' => 'no',
            'show_btn'            => 'yes',
            'show_thumb'          => 'yes',
            'btn_text'            => 'Attend',
            'desc_limit'          => '25',
            'orderby'             => 'title',
            'filter_with_status'  => '',
            'show_end_date'       => 'no',
            'show_child_event'    => 'yes',
            'show_parent_event'   => 'no',
            'show_event_location' => 'yes'
        ], $atts ) );

        $event_cat = [];
        $event_tag = [];
        $show_end_date  = !empty($show_end_date) ? $show_end_date : 'no';

        if ( $event_cat_ids !== '' ) {
            $event_cat = explode( ',', $event_cat_ids );
        }

        if ( $event_tag_ids !== '' ) {
            $event_tag = explode( ',', $event_tag_ids );
        }

        if ( $event_col == 6 ) {
            $event_col = 2;
        } else if ( $event_col == 5 ) {
            $event_col = 2;
        } else if ( $event_col == 4 ) {
            $event_col = 3;
        } else if ( $event_col == 3 ) {
            $event_col = 4;
        } else if ( $event_col == 2 ) {
            $event_col = 6;
        } else if ( $event_col == 1 ) {
            $event_col = 12;
        }
        
        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta       = "meta_value";
        } else {
            $orderby_meta       = null;
        }

        ob_start();
        $file_name = "";

        switch ( $style ) {
            case "style-1":
                $file_name = "event-1";
                break;
            case "style-2":
                $file_name = "event-4";
                break;
            case "style-3":
                $file_name = "event-3";
                break;
            default:
                $file_name = "event-3";
        }

        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        if ( file_exists( ETN_PRO_DIR . "/widgets/events-pro/style/$file_name.php" ) ) {
            include ETN_PRO_DIR . "/widgets/events-pro/style/$file_name.php";
        }

        return ob_get_clean();
    }
    /**
     * Events classic style
     */
    public function events_tab( $atts ) {

        $atts = extract( shortcode_atts( [
            'style'               => 'event-4',
            'event_count'         => 6,
            'event_col'           => 2,
            'event_cat_ids'       => '',
            'event_tag_ids'       => '',
            'order'               => 'DESC',
            'show_desc'           => 'yes',
            'show_category'       => 'yes',
            'show_attendee_count' => 'no',
            'show_btn'            => 'yes',
            'show_thumb'          => 'yes',
            'btn_text'            => 'Attend',
            'desc_limit'          => '25',
            'orderby'             => 'title', 
            'filter_with_status'  => '',
            'show_end_date'     => 'no',
            'show_child_event'    => 'yes',
            'show_parent_event'   => 'no',
            'show_event_location' => 'yes'
        ], $atts ) );

        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta = "meta_value";
        } else {
            $orderby_meta  = null;
        }

        $event_cats = [];
        $event_tag = [];
        $show_end_date = !empty($show_end_date) ? $show_end_date : 'no';

        if ( $event_cat_ids !== '' ) {
            $event_cats = explode( ',', $event_cat_ids );
        }
        $widget_id     = uniqid();
        if ( $event_tag_ids !== '' ) {
            $event_tag = explode( ',', $event_tag_ids );
        }

        if ( $event_col == 6 ) {
            $event_col = 2;
        } else if ( $event_col == 5 ) {
            $event_col = 2;
        } else if ( $event_col == 4 ) {
            $event_col = 3;
        } else if ( $event_col == 3 ) {
            $event_col = 4;
        } else if ( $event_col == 2 ) {
            $event_col = 6;
        } else if ( $event_col == 1 ) {
            $event_col = 12;
        }
        
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        ob_start();

        if ( file_exists( ETN_PRO_DIR . "/widgets/event-tab/style/tab-1.php" ) ) {
            include ETN_PRO_DIR . "/widgets/event-tab/style/tab-1.php";
       }

        return ob_get_clean();
    }

    /**
     * Events sliders
     */
    public function events_sliders( $atts ) {

        $atts = extract( shortcode_atts( [
            'style'               => 'event-1',
            'event_count'         => 6,
            'event_slider_count'  => 2,
            'event_cat_ids'       => '',
            'event_tag_ids'       => '',
            'auto_play'           => 'no',
            'order'               => 'DESC',
            'show_desc'           => 'yes',
            'show_category'       => 'yes',
            'show_attendee_count' => 'no',
            'show_btn'            => 'yes',
            'show_thumb'          => 'yes',
            'slider_nav_show'     => 'yes',
            'slider_dot_show'     => 'yes',
            'btn_text'            => 'Attend',
            'desc_limit'          => '25',
            'orderby'             => 'title',
            'filter_with_status'  => '',
            'show_end_date'       => 'no',
            'show_parent_event'   => 'no',
            'show_child_event'    => 'yes',
            'show_event_location' => 'yes'
        ], $atts ) );

        wp_enqueue_style('swiper-bundle-min');
        wp_enqueue_script('swiper-bundle-min');

        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta = "meta_value";
        } else {
            $orderby_meta  = null;
        }

        $event_cat = [];
        $event_tag = [];

        if ( $event_cat_ids !== '' ) {
            $event_cat = explode( ',', $event_cat_ids );
        }

        if ( $event_tag_ids !== '' ) {
            $event_tag = explode( ',', $event_tag_ids );
        }

        $style = ( isset( $style ) && $style != '' ) ? $style : 'event-1';
        $show_end_date = ( isset( $show_end_date ) && $show_end_date != '' ) ? $show_end_date : 'no';

        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        ob_start();

        ?>
        <div class="event-slider-shortcode">
            <?php include ETN_PRO_DIR . "/widgets/events-slider/style/{$style}.php";?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Events countdown
     */
    public function event_countdown( $atts ) {

        $atts = extract( shortcode_atts( ['event_id' => 0], $atts ) );

        $etn_start_date   = get_post_meta( $event_id, 'etn_start_date', true );
        $event_start_time = get_post_meta( $event_id, 'etn_start_time', true );
        $etn_timezone = get_post_meta( $event_id, 'event_timezone', true );

        ob_start();
        ?>
        <h2 class="event-cowntdown-title"><?php echo esc_html( get_the_title( $event_id ) ); ?></h2>
        <?php
        Helper::countdown_markup( $etn_start_date, $event_start_time, $etn_timezone );
        return ob_get_clean();
    }

    

    /**
     * Events countdown
     */
    public function event_ticket_form( $atts ) {

        $atts = extract( shortcode_atts( ["id" => 0, "show_title" => "yes"], $atts ) );

        if ( class_exists( 'WooCommerce' ) ) {

            if ( function_exists( 'wc_print_notices' ) ) {
                wc_print_notices();
            }
        }

        ob_start();

        ?>
            <div class="etn-single-event-ticket-wrap">
                <?php if ( isset( $show_title ) && $show_title === "yes" ) : ?>
                    <h3 class="etn-event-form-widget-title">
                        <?php echo esc_html( get_the_title( $id ) ); ?>
                    </h3>
                <?php endif; ?>

                <?php
                    $event_options = get_option( "etn_event_options" );
                    if ( ( !empty( $event_options["sell_tickets"] ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) || !empty( $event_options["etn_sells_engine_stripe"] ) ) {
                        Helper::eventin_ticket_widget( $id );
                    }
                ?>
            </div>
        <?php
        return ob_get_clean();
    }

    /**
     * recurring event
     */
    public function event_recurring( $atts ) {

        $atts = extract( 
            shortcode_atts(
                 [
                     "single_event_ids" => 0,
                ],
                 $atts )
        );
        ob_start();

        include ETN_PRO_DIR . "/widgets/recurring-event/style/style-1.php";

        return ob_get_clean();
    }

    /**
     * Events countdown
     */
    public function related_events( $atts ) {

        $atts = extract( shortcode_atts( ["id" => 0], $atts ) );
        ob_start();
        ?>
        <div class="etn-related-event-widget">
            <?php
            Helper::related_events_widget( $id );
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Add to Calendar
     */
    public function external_calendar( $atts ) {

        $atts = extract( shortcode_atts( ["id" => 0], $atts ) );
        ob_start();

        etn_after_single_event_meta_add_to_calendar( $id );
        
        return ob_get_clean();
    }

    /**
     * Schedule tab style
     */
    public function schedules_tab( $atts ) {

        $atts = extract( shortcode_atts( [
            'style'              => 'style-2',
            'ids'                => 16,
            'order'              => 'DESC',
            'show_time_duration' => 'yes',
            'show_location'      => 'yes',
            'show_speaker'       => 'yes',

        ], $atts ) );

        $schedule_ids = [];

        if ( $ids !== '' ) {
            $schedule_ids = explode( ',', $ids );
        }

        $widget_id     = uniqid();
        $event_options = get_option( "etn_event_options" );
        $col           = ( $show_speaker == 'yes' ) ? 'etn-col-lg-6' : 'etn-col-lg-9';

        ob_start();

        $file_name = "";

        switch ( $style ) {
        case "style-1":
            $file_name = "schedule-1";
            break;
        case "style-2":
            $file_name = "schedule-2";
            break;
        default:
            $file_name = "schedule-2";
        }

        if ( file_exists( ETN_PRO_DIR . "/widgets/schedule-tab/style/$file_name.php" ) ) {
            include ETN_PRO_DIR . "/widgets/schedule-tab/style/$file_name.php";
        }

        return ob_get_clean();
    }

    /**
     * Schedule tab style
     */
    public function schedules_list( $atts ) {
        $atts = extract( shortcode_atts( [
            'style'              => 'style-1',
            'ids'                 => 16,
            'show_time_duration' => 'yes',
            'show_location'      => 'yes',
            'show_desc'          => 'yes',
            'show_speaker'       => 'yes',
        ], $atts ) );
        $col = ( $show_speaker == 'yes' ) ? 'etn-col-lg-6' : 'etn-col-lg-9';

        ob_start();
        $schedule_id = ( !empty( $ids ) ) ? $ids : '';

        $file_name = "";

        switch ( $style ) {
            case "style-1":
                $file_name = "schedule-1";
                break;
            case "style-2":
                $file_name = "schedule-2";
                break;
            default:
                $file_name = "schedule-1";
        }

        if ( file_exists( ETN_PRO_DIR . "/widgets/schedule-list/style/$file_name.php" ) ) {
            include ETN_PRO_DIR . "/widgets/schedule-list/style/$file_name.php";
        }

        return ob_get_clean();
    }

    /**
     * Events countdown
     */
    public function attendee_list( $atts ) {

        $atts = extract( shortcode_atts(
            [
                "id"          => 0,
                'show_avatar' => 'yes',
                'show_email'  => 'yes',
            ],
            $atts ) );
        ob_start();
        ?>
        <div class="etn-attendee-list-widget">
            <?php
            Helper::attendee_list_widget( $id, $show_avatar, $show_email );
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

      /**
     * Events Calendar
     */
    public function calendar_standard_widget($attributes){
        wp_enqueue_style('etn-app-index');
        wp_enqueue_script('etn-app-index');

        $event_cat = null;

        if ( isset( $attributes['event_cat_ids'] ) && $attributes['event_cat_ids'] !== '' ) {
            $event_cat = explode( ',', $attributes['event_cat_ids'] );
        }
        $calendar_view = isset($attributes['calendar_view']) && $attributes['calendar_view'] !=='' ? $attributes['calendar_view'] : 'dayGridMonth';
        $all_day_slot = isset($attributes['all_day_slot']) && $attributes['all_day_slot'] !=='' ? $attributes['all_day_slot'] : true;
 
   

        if(is_array($event_cat)){
            $cats = $event_cat;
        }else{
    
            $args_cat = array(
                'taxonomy'     => 'etn_category',
                'number' => 50,
            );
            $cats = get_categories( $args_cat );
        }
        $cat_json = [];
        foreach($cats as $value){
            $term = get_term($value,'etn_category');
             
            $info = [
                'category' => $term->name, 
                'category_ids' => $term->term_id, 
            ];
            array_push($cat_json,  $info );
        }
        
        $show_parent_event = ! empty( $attributes['show_parent_event'] ) ? $attributes['show_parent_event'] : 'no';
        $show_child_event  = ! empty( $attributes['show_child_event'] ) ? $attributes['show_child_event'] : 'yes';
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        $extra_controls = [
             'btn_text' => esc_html__('View Details', 'eventin-pro'),
             'select_cat_text' => esc_html__('All Categories', 'eventin-pro'),
             'calendar_view' => $calendar_view,
             'all_day_slot' => $all_day_slot,
             'post_parent' => $post_parent
         ];

        $data_controls = json_encode( $extra_controls );
        $data_cat_list = json_encode( $cat_json );  
 
        ob_start();
        ?>
           <div class="eventin-shortcode-wrapper">
              <div class="events_calendar_standard view-<?php echo esc_attr($calendar_view); ?>" data-cat="<?php echo esc_attr( $data_cat_list ); ?>" data-controls="<?php echo esc_attr( $data_controls ); ?>"></div>
           </div>
        <?php

        return ob_get_clean(); 
    }

      /**
     * Events Calendar
     */
    public function calendar_list_widget($attributes){
        wp_enqueue_style('etn-app-index');
        wp_enqueue_script('etn-app-index');
        $event_cat = null;

        if ( isset( $attributes['event_cat_ids'] ) && $attributes['event_cat_ids'] !== '' ) {
            $event_cat = explode( ',', $attributes['event_cat_ids'] );
        }
        $calendar_view = isset($attributes['calendar_view']) && $attributes['calendar_view'] !=='' ? $attributes['calendar_view'] : 'dayGridMonth';
        $all_day_slot = isset($attributes['all_day_slot']) && $attributes['all_day_slot'] !=='' ? $attributes['all_day_slot'] : true;
 
   

        if(is_array($event_cat)){
            $cats = $event_cat;
        }else{
    
            $args_cat = array(
                'taxonomy'     => 'etn_category',
                'number' => 50,
            );
            $cats = get_categories( $args_cat );
        }
        $cat_json = [];
        foreach($cats as $value){
            $term = get_term($value,'etn_category');
             
            $info = [
                'category' => $term->name, 
                'category_ids' => $term->term_id, 
            ];
            array_push($cat_json,  $info );
        }

        $show_parent_event = ! empty( $attributes['show_parent_event'] ) ? $attributes['show_parent_event'] : 'no';
        $show_child_event  = ! empty( $attributes['show_child_event'] ) ? $attributes['show_child_event'] : 'yes';
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );
        
        $extra_controls = [
             'btn_text' => esc_html__('View Details', 'eventin-pro'),
             'select_cat_text' => esc_html__('All Categories', 'eventin-pro'),
             'calendar_view' => $calendar_view,
             'all_day_slot' => $all_day_slot,
             'post_parent' => $post_parent
         ];

        $data_controls = json_encode( $extra_controls );
        $data_cat_list = json_encode( $cat_json );
        
        $show_parent_event = ! empty( $attributes['show_parent_event'] ) ? $attributes['show_parent_event'] : 'no';
        $show_child_event  = ! empty( $attributes['show_child_event'] ) ? $attributes['show_child_event'] : 'yes';
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );
 
        ob_start();
        ?>
           <div class="eventin-shortcode-wrapper">
              <div class="events_calendar_list view-<?php echo esc_attr($calendar_view); ?>" data-cat="<?php echo esc_attr( $data_cat_list ); ?>" data-controls="<?php echo esc_attr( $data_controls ); ?>"></div>
           </div>
        <?php

        return ob_get_clean(); 
    }
      /**
     * Event User dashboard
     */
    public function etn_dashboard(){ 
        ob_start();
        if(0 !== get_current_user_id()){ 

            if(( is_user_logged_in()  &&  ( current_user_can('manage_etn_attendee') || current_user_can('seller') || current_user_can( 'author' ) ) )){
                wp_enqueue_media(); 
                wp_enqueue_style('etn-frontend-submission');
                wp_enqueue_script('etn-frontend-submission');
                
                ?>
                
                <div class="etn-frontend-dashboard" id="etn-frontend-dashboard">
                    <div class="frontend-attendee-list" id="frontend-attendee-list"></div>
                </div>
                <?php
               } else{
                ?>
                <div class="etn-frontend-login"> 
                    <?php echo esc_html__('Opps! You do not have any permission to manage events.', 'eventin-pro') ?>
                </div>
                <?php
                }
            }else{
                ?>
                <div class="etn-frontend-login"> 
                    <?php echo esc_html__('In order to create a new event, please ', 'eventin-pro') ?>
                    <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e( 'Login', 'eventin-pro' ); ?>">
                        <?php esc_html_e( 'login', 'eventin-pro' ); ?>
                    </a>
                </div>
                <?php
            }
        return ob_get_clean(); 
    }

}
