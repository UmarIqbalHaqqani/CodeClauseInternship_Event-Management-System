<?php 
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper;

use function PHPSTORM_META\type;

defined( 'ABSPATH' ) || exit;

class Etn_Pro_Event_Calendar_List extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-event-calendar-list-pro';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Event Calendar List Pro', 'eventin-pro' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-calendar';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    /**
     * Register the widget controls.
     * @access protected
     */
    protected function register_controls() {

        // Start of event section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'Event With Calendar Pro', 'eventin-pro' ),
            ]
        ); 
        $this->add_control(
            'etn_event_cat',
            [
                'label'    => esc_html__( 'Event Category', 'eventin-pro' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_category(),
                'multiple' => true,
            ]
        );
        
      

        $this->add_control(
            'calendar_view',
            [
                'label'   => esc_html__( 'Calendar View', 'eventin-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'listMonth',
                'options' => [
                    'listMonth' => esc_html__( 'Monthly', 'eventin-pro' ),
                    'listWeek' => esc_html__( 'Weekly', 'eventin-pro' ),
                    'listDay'  => esc_html__( 'Daily', 'eventin-pro' ),
                 ],
             ]
        );
        $this->add_control(
            'show_parent_event',
            [
                    'label'   => esc_html__( 'Show Recurring Parent Events', 'eventin-pro' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'eventin-pro' ),
                    'label_off' => esc_html__( 'No', 'eventin-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
        );

        $this->add_control(
            'show_child_event',
            [
                    'label'   => esc_html__( 'Show Recurring Child Event', 'eventin-pro' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'eventin-pro' ),
                    'label_off' => esc_html__( 'No', 'eventin-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
        );
         $this->end_controls_section(); 

    }

    protected function render() {
        $settings           = $this->get_settings();
        $event_cat          = (isset($settings["etn_event_cat"]) ? $settings["etn_event_cat"] : []);
        $calendar_view      = (isset($settings["calendar_view"]) ? $settings["calendar_view"] : 'listMonth');
        $catsIds           =  !empty($event_cat) ?  implode(",",$event_cat) : '';
        $show_child_event   = $settings["show_child_event"];
        $show_parent_event  = $settings["show_parent_event"];
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        echo do_shortcode("[etn_pro_calendar_list event_cat_ids='$catsIds' show_parent_event='$show_parent_event' show_child_event='$show_child_event' calendar_view=$calendar_view /]");
     }
     public function get_event_category() {
        return Helper::get_event_category();
    }
    
}
 