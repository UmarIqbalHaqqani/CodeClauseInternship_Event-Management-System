<?php
namespace Elementor;
use Elementor\Widget_base;
use Elementor\Controls_Manager;
use Elementor\Core\Admin\Admin;
use Etn_Pro\Utils\Helper;

defined( "ABSPATH" ) || die();

class Etn_Pro_Recurring_Event extends Widget_Base {
    public function get_name() {
        return "etn-recurring-event";
    }

    public function get_title() {
        return esc_html__( "Recurring Event Ticket", "eventin-pro" );
    }

    public function get_categories() {
        return ["etn-event"];
    }

    public function get_icon() {
        return "eicon-user-circle-o";
    }

    public function register_controls() {
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__("Recurring Event Ticket", "eventin-pro")
            ]
        );
        $this->add_control(
            "event_id",
            [
                "label"     => esc_html__("Select Event", "eventin-pro"),
                "type"      => Controls_Manager::SELECT,
                "multiple"  => false,
                "options"   => Helper::get_events(null, false, true),
            ]
        );

        $this->end_controls_section();


        // Title style section
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Main Title Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__('Title color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-widget-title'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__('Title Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-widget-title',
            ]
        );

        $this->end_controls_section();

        // Title style section
        $this->start_controls_section(
            'post_title_section',
            [
                'label' => __('Post Title Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'etn_post_title_color',
            [
                'label'     => esc_html__('Title color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-title'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'post_title_typography',
                'label'    => esc_html__('Title Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-title',
            ]
        );

        $this->add_responsive_control(
            'post_title_margin',
            [
                'label'      => esc_html__('Title margin', 'eventin-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Title style section
        $this->start_controls_section(
            'desc_section',
            [
                'label' => __('Description Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__('Desc color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-title-wrap p'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'label'    => esc_html__('Desc Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-title-wrap p',
            ]
        );

        $this->add_responsive_control(
            'post_desc_margin',
            [
                'label'      => esc_html__('Desc margin', 'eventin-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-title-wrap p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Date style section
        $this->start_controls_section(
            'date_section',
            [
                'label' => __('Date Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'etn_date_color',
            [
                'label'     => esc_html__('Date color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-recurring-widget .etn-date-meta'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_date_color2',
            [
                'label'     => esc_html__('Sub Date color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-recurring-widget .etn-date-meta span'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography',
                'label'    => esc_html__('Date Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-date-meta',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'etn_date_border',
                'label'    => esc_html__('Border', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-date-meta',
            ]
        );
        $this->end_controls_section();


        // Date style section
        $this->start_controls_section(
            'meta_section',
            [
                'label' => esc_html__('Meta Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label'     => esc_html__('Meta color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-recurring-widget .recurring-content .etn-time-meta li, {{WRAPPER}} .etn-form-ticket-text'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'label'    => esc_html__('Meta Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-recurring-widget .recurring-content .etn-time-meta li, {{WRAPPER}} .etn-form-ticket-text',
            ]
        );
        $this->end_controls_section();

        // Date style section
        $this->start_controls_section(
            'price_section',
            [
                'label' => esc_html__('Price Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_label_color',
            [
                'label'     => esc_html__('Price Label Color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent label'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Price Color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-event-form-price, .etn-recurring-widget .etn-event-form-parent .etn-t-price'   => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'label'    => esc_html__('Price Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent label, .etn-recurring-widget .etn-event-form-parent .etn-event-form-price, .etn-recurring-widget .etn-event-form-parent .etn-t-price',
            ]
        );
        $this->end_controls_section();

      // Button style section
      $this->start_controls_section(
        'etn_btn_style',
        [
            'label'     => esc_html__('Button Style', 'eventin-pro'),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name'     => 'etn_btn_typography',
            'label'    => esc_html__('Button Typography', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-btn',
        ]
    );
    // tab controls start
    $this->start_controls_tabs(
        'etn_btn_tabs'
    );

    $this->start_controls_tab(
        'etn_btn_normal_tab',
        [
            'label' => esc_html__('Normal', 'eventin-pro'),
        ]
    );
    $this->add_control(
        'etn_btn_color',
        [
            'label'     => esc_html__('Button color', 'eventin-pro'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'     => 'btn_background',
            'label'    => esc_html__('Background Color', 'eventin-pro'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn',
        ]
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name'     => 'etn_btn_border',
            'label'    => esc_html__('Border', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn',
        ]
    );
    $this->add_responsive_control(
        'etn_btn_radius',
        [
            'label'      => esc_html__('Border Radius', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
        'etn_btn_hover_tab',
        [
            'label' => esc_html__('Hover', 'eventin-pro'),
        ]
    );
    $this->add_control(
        'etn_btn_hover_color',
        [
            'label'     => esc_html__('Button Hover color', 'eventin-pro'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn:hover' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'     => 'btn_background_hover',
            'label'    => esc_html__('Background Hover Color', 'eventin-pro'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn:hover',
        ]
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name'     => 'etn_btn_hover_border',
            'label'    => esc_html__('Border Hover', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn:hover',
        ]
    );

    $this->add_responsive_control(
        'etn_btn_hover_radius',
        [
            'label'      => esc_html__('Border Radius', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();
    // tabs control end

    $this->add_responsive_control(
        'etn_btn_padding',
        [
            'label'      => esc_html__('Button Padding', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-widget .etn-event-form-parent .etn-add-to-cart-btn .etn-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();

      // loadmore Button style section
      $this->start_controls_section(
        'etn_loadmore_btn_style',
        [
            'label'     => esc_html__('Lodmore Button Style', 'eventin-pro'),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name'     => 'etn_btnloadmore_typography',
            'label'    => esc_html__('Button Typography', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore',
        ]
    );
    // tab controls start
    $this->start_controls_tabs(
        'etn_btnloadmore_tabs'
    );

    $this->start_controls_tab(
        'etn_btnloadmore_normal_tab',
        [
            'label' => esc_html__('Normal', 'eventin-pro'),
        ]
    );
    $this->add_control(
        'etn_btnloadmore_color',
        [
            'label'     => esc_html__('Button color', 'eventin-pro'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'     => 'btnloadmore_background',
            'label'    => esc_html__('Background Color', 'eventin-pro'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore',
        ]
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name'     => 'etn_btnloadmore_border',
            'label'    => esc_html__('Border', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore',
        ]
    );
    $this->add_responsive_control(
        'etn_btnloadmore_radius',
        [
            'label'      => esc_html__('Border Radius', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
        'etn_btnloadmore_hover_tab',
        [
            'label' => esc_html__('Hover', 'eventin-pro'),
        ]
    );
    $this->add_control(
        'etn_btnloadmore_hover_color',
        [
            'label'     => esc_html__('Button Hover color', 'eventin-pro'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore:hover' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'     => 'btnloadmore_background_hover',
            'label'    => esc_html__('Background Hover Color', 'eventin-pro'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore:hover',
        ]
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name'     => 'etn_btnloadmore_hover_border',
            'label'    => esc_html__('Border Hover', 'eventin-pro'),
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore:hover',
        ]
    );

    $this->add_responsive_control(
        'etn_btnloadmore_hover_radius',
        [
            'label'      => esc_html__('Border Radius', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();
    // tabs control end

    $this->add_responsive_control(
        'etn_btnloadmore_padding',
        [
            'label'      => esc_html__('Button Padding', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .etn-recurring-event-wrapper #seeMore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->end_controls_section();

    // Title style section
    $this->start_controls_section(
        'advance_section',
        [
            'label' => __('Advance Style', 'eventin-pro'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'     => 'box_background',
            'label'    => esc_html__('Background Color', 'eventin-pro'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper',
        ]
    );
    $this->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'box_shadow',
            'label' => esc_html__( 'Box Shadow', 'eventin-pro' ),
            'selector' => '{{WRAPPER}} .etn-recurring-event-wrapper',
        ]
    );
    $this->add_responsive_control(
        'box_padding',
        [
            'label'      => esc_html__('box padding', 'eventin-pro'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}}  .etn-recurring-event-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->end_controls_section();


    }

    public function render() {
        $settings           = $this->get_settings();
        $single_event_ids   = !empty( $settings['event_id'] ) ? $settings['event_id'] : 0;
        
        include ETN_PRO_DIR . "/widgets/recurring-event/style/style-1.php";

    }
    
}

?>