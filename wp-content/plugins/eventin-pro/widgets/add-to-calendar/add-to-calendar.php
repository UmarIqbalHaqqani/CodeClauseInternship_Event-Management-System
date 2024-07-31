<?php
namespace Elementor;
use Elementor\Widget_base;
use Elementor\Controls_Manager;
use Etn_Pro\Utils\Helper;

defined( "ABSPATH" ) || die();

class Etn_Pro_Add_To_Calendar extends Widget_Base {
    public function get_name() {
        return "add-to-calendar";
    }

    public function get_title() {
        return esc_html__( "Add to Calendar", "eventin-pro" );
    }

    public function get_categories() {
        return ["etn-event"];
    }

    public function get_icon() {
        return "eicon-calendar";
    }

    public function register_controls() {
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__("Add To calendar", "eventin-pro")
            ]
        );
        $this->add_control(
            "event_id",
            [
                "label"     => esc_html__("Select Event", "eventin-pro"),
                "type"      => Controls_Manager::SELECT2,
                "multiple"  => false,
                "options"   => Helper::get_events(),
            ]
        );
        $this->end_controls_section();
    }

    public function render() {
        $settings               = $this->get_settings();
        $single_event_id        = !empty( $settings['event_id'] ) ? $settings['event_id'] : 0;
        ?>
        <div class="etn-event-form-widget">
            <?php 
                etn_after_single_event_meta_add_to_calendar($single_event_id);
            ?>
        </div>
        <?php
    }
}
