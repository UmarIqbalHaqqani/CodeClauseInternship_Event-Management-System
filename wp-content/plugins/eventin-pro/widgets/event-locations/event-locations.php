<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use \Etn_Pro\Utils\Helper;

defined( 'ABSPATH' ) || exit;

// Exit if accessed directly

/**
 * @since 1.1.0
 */
class Etn_Pro_Event_Locations extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'event-locations';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Eventin Location List Pro', 'eventin-pro' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-list';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    protected function register_controls() {
        // Start of schedule section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'Location Search Style', 'eventin-pro' ),
            ]
        );
        $this->add_control(
            'event_location_style',
            [
                'label'   => esc_html__( 'Pick Search Style', 'eventin-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__( 'Location 1', 'eventin-pro' ),
                ],
            ]
        );
        $this->end_controls_section();
        // End of schedule section

        // Start of title section
        $this->start_controls_section(
            'Head_style',
            [
                'label'     => esc_html__( 'Form Style', 'eventin-pro' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'label' => esc_html__( 'Search Button Border', 'eventin-pro' ),
				'selector' => '{{WRAPPER}} .etn_loc_form .etn_loc_address',
			]
		);
        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => esc_html__( 'Input Padding', 'eventin-pro' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn_loc_form .etn_loc_address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'placeholder_typography',
                'label'    => esc_html__( 'Placeholder Typography', 'eventin-pro' ),
                'selector' => '{{WRAPPER}} .etn_loc_form .etn_loc_address::placeholder',
            ]
        );
        $this->add_control(
            'placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Color', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn_loc_form .etn_loc_address::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'location_picker_typography',
                'label'    => esc_html__( 'Location Picker Typography', 'eventin-pro' ),
                'selector' => '{{WRAPPER}} .etn_loc_my_position',
            ]
        );
		$this->add_control(
			'search_button_style',
			[
				'label' => esc_html__( 'Search Button Style', 'eventin-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
            'search_button_background',
            [
                'label'     => esc_html__( 'Search Button Background', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn_loc_form .etn_button_wrapper button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'search_button_hover_background',
            [
                'label'     => esc_html__( 'Search Button Hover Background', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn_loc_form .etn_button_wrapper button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'search_button_border',
				'label' => esc_html__( 'Search Button Border', 'eventin-pro' ),
				'selector' => '{{WRAPPER}} .etn_loc_form .etn_button_wrapper button',
			]
		);
        $this->end_controls_section();
        // Start of title section
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Content Style', 'eventin-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_location_title_typography',
                'label'    => esc_html__( 'Title Typography', 'eventin-pro' ),
                'selector' => '{{WRAPPER}} .etn-location-item-name',
            ]
        );

        $this->add_control(
            'etn_location_title_color',
            [
                'label'     => esc_html__( 'Title color', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-location-item-name a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_location_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-location-item-name a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'eventin-pro' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-location-item-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_control(
			'location_area_title_style',
			[
				'label' => esc_html__( 'Location Area Title', 'eventin-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'location_area_title',
                'label'    => esc_html__( 'Location Area Title', 'eventin-pro' ),
                'selector' => '{{WRAPPER}}  .location-area-title',
            ]
        );
        $this->add_control(
            'location_area_color',
            [
                'label'     => esc_html__( 'Location Area Color', 'eventin-pro' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .location-area-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        // End of title section
    }

    protected function render() {
        $settings = $this->get_settings();
        $style    = $settings["event_location_style"];
        include ETN_PRO_DIR . "/widgets/event-locations/style/{$style}.php";
    }
}
