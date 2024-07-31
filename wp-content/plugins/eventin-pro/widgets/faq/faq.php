<?php

namespace Elementor;

use Elementor\Widget_Base;
use Etn_Pro\Utils\Helper;

defined( "ABSPATH" ) || die();

class Etn_Pro_Faq extends Widget_Base {

    public function get_categories() {
        return ['etn-event'];
    }

    public function get_name() {
        return "etn-faq";
    }

    public function get_title() {
        return esc_html__( "Event FAQ Pro", "eventin-pro" );
    }

    public function get_icon() {
        return "eicon-sort-amount-desc";
    }

    public function register_controls() {
        $this->start_controls_section(
            "section_tab",
            [
                "label" => esc_html__( "Event FAQ Pro", 'eventin-pro' ),
            ]
        );

        $this->add_control(
            "event_id",
            [
                "label"    => esc_html__( "Select Event Id", "eventin-pro" ),
                "type"     => Controls_Manager::SELECT2,
                "multiple" => false,
                "options"  => Helper::get_events(),
            ]
        );

        $this->end_controls_section();

         // Title style section
         $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title Style', 'eventin-pro'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'ent_title_typography',
                'label'    => esc_html__('Title Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-accordion-wrap .etn-content-item .etn-accordion-heading',
            ]
        );

        // tab controls start
        $this->start_controls_tabs(
            'etn_title_tabs'
        );

        $this->start_controls_tab(
            'etn_title_normal_tab',
            [
                'label' => __('Normal', 'eventin-pro'),
            ]
        );
        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__('Title color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-accordion-wrap .etn-content-item .etn-accordion-heading'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_title_hover_tab',
            [
                'label' => __('Hover', 'eventin-pro'),
            ]
        );
        $this->add_control(
            'etn_title_hover_color',
            [
                'label'     => esc_html__('Title Hover color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-accordion-wrap .etn-content-item .etn-accordion-heading:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etn-accordion-wrap .etn-content-item .etn-accordion-heading.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Title margin', 'eventin-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-accordion-wrap .etn-content-item .etn-accordion-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // designation style section
        $this->start_controls_section(
            'desc_section',
            [
                'label'     => esc_html__('Description Style', 'eventin-pro'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_description_typography',
                'label'    => esc_html__('Description Typography', 'eventin-pro'),
                'selector' => '{{WRAPPER}} .etn-accordion-wrap .etn-acccordion-contents',
            ]
        );

        $this->add_control(
            'etn_desc_color',
            [
                'label'     => esc_html__('Description color', 'eventin-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-accordion-wrap .etn-acccordion-contents' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_desc_margin',
            [
                'label'      => esc_html__('Description margin', 'eventin-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-accordion-wrap .etn-acccordion-contents' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings        = $this->get_settings();
        $single_event_id = !empty( $settings['event_id'] ) ? $settings['event_id'] : 0;

        $etn_faqs = get_post_meta( $single_event_id, 'etn_event_faq', true );
        ?>
        <div class="etn-accordion-wrap etn-event-single-content-wrap">
       
            <?php
                if ( is_array( $etn_faqs ) && !empty( $etn_faqs ) ) {
                    foreach ( $etn_faqs as $key => $faq ) {
                        $acc_class = ( $key == 0 ) ? 'active' : '';
                        ?>
                        <div class="etn-content-item">
                            <h4 class="etn-accordion-heading <?php echo esc_attr( $acc_class ); ?>">
                                <?php echo esc_html( $faq["etn_faq_title"] ); ?>
                                <?php 
                                    if($acc_class){
                                        echo '<i class="etn-icon etn-minus"></i>';
                                    } else {
                                        echo '<i class="etn-icon etn-plus"></i>';
                                    }
                                ?>
                            </h4>
                            <p class="etn-acccordion-contents <?php echo esc_attr( $acc_class ); ?>">
                                <?php echo esc_html( $faq["etn_faq_content"] ); ?>
                            </p>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="etn-event-faq-body">
                        <?php echo esc_html__( "No FAQ found!", "eventin-pro" ); ?>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }

}
