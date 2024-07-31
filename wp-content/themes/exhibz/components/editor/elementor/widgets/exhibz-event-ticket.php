<?php
namespace Elementor;
use \Etn\Utils\Helper;

if (!defined('ABSPATH')) exit;


class Exhibz_Event_Ticket_Widget extends Widget_Base
{


    public $base;

    public function get_name()
    {
        return 'exhibz-event-ticket';
    }

    public function get_title()
    {
        return esc_html__('Exhibz Event Variation Ticket', 'exhibz');
    }

    public function get_icon()
    {
        return 'eicon-slider-3d';
    }

    public function get_categories()
    {
        return ['exhibz-elements'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Pricing settings', 'exhibz'),
            ]
        );

        $this->add_control(
            "event_id",
            [
                "label"     => esc_html__("Select Event", "exhibz"),
                "type"      => Controls_Manager::SELECT2,
                "multiple"  => false,
                "options"   => Helper::get_events(),
            ]
        );

        $this->add_control(
            "event_style",
            [
                "label"     => esc_html__("Select Style", "exhibz"),
                "type"      => \Elementor\Controls_Manager::SELECT,
                "options"   => [
                    'style-1' => esc_html__("Style One", "exhibz"),
                    'style-2' => esc_html__("Style Two", "exhibz"),
                ],
                "default" => "style-1"
            ]
        );

        $this->add_control(
            "show_title",
            [
                "label" => esc_html__("Show Title", "exhibz"),
                "type"  => Controls_Manager::SWITCHER,
                "label_on"  => esc_html__("Show", "exhibz"),
                "label_on"  => esc_html__("Hide", "exhibz"),
                "default"   => "yes"
            ]
        );

        $this->add_control(
            "plan_title",
            [
                "label" => esc_html__("Plan Title", "exhibz"),
                "type"  => Controls_Manager::TEXT,           
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'slider_setting_section',
            [
                'label' => esc_html__('Slider Settings', 'exhibz'),
                'condition' =>["event_style"=>["style-1"] ]
            ]
        );

        $this->add_control(
            'ticket_slider_loop',
            [
                'label' => esc_html__( 'Loop', 'bascart' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'bascart' ),
                'label_off' => esc_html__( 'No', 'bascart' ),
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'ticket_slider_speed',
            [
                'label' => esc_html__( 'Slider speed', 'bascart' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1500
            ]
        );

        $this->add_control(
            'ticket_slider_autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'bascart' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'bascart' ),
                'label_off' => esc_html__( 'No', 'bascart' ),
                'return_value' => 'yes',
                'default' => 'no'
            ]
        );

        $this->add_control(
            'show_navigation',
            [
                'label'       => esc_html__('Show Navigation', 'bascart'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => esc_html__('Yes', 'bascart'),
                'label_off'   => esc_html__('No', 'bascart'),
                'default'     => 'yes'
            ]
        );

        $this->add_control(
			'left_arrow_icon',
			[
				'label' => esc_html__( 'Left Arrow Icon', 'bascart' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-left-arrow1',
					'library' => 'solid',
				],
                'condition' => ['show_navigation' => 'yes']
			]
		);

        $this->add_control(
			'right_arrow_icon',
			[
				'label' => esc_html__( 'Right Arrow Icon', 'bascart' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-arrow-right',
					'library' => 'solid',
				],
                'condition' => ['show_navigation' => 'yes']
			]
		);

        $this->end_controls_section();

         //style
        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style Section', 'exhibz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
        ); 
        
        $this->add_control(
			'plan_title_color',
			[
				'label' => esc_html__( 'Plan Title Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plan-title' => 'color: {{VALUE}}',
				],
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'plan_typography',
                'label' => esc_html__( 'Plan Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .plan-title',
			]
		);

        $this->add_control(
			'plan_end_color',
			[
				'label' => esc_html__( 'Plan Date Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .end-date' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'price_image',
				'label' => esc_html__( 'Background', 'exhibz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .price-image',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'event_form_price_typography',
                'label' => esc_html__( 'Price Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .etn-price-field .etn-event-form-price',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
                'label' => esc_html__( 'Total Price Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .exhibz-ticket-widget .etn-total-price',
			]
		);

        $this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Button Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .exhibz-btn' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
                'label' => esc_html__( 'Button Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .exhibz-ticket-widget .exhibz-btn',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_image',
				'label' => esc_html__( 'Wrapper Background', 'exhibz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .exhibz-ticket-widget',
			]
		);

        $this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'slider_style_section',
			[
				'label' => esc_html__( 'Slider Settings Style', 'exhibz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_responsive_control(
			'arrow_width',
			[
				'label' => esc_html__( 'Width', 'exhibz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' , '%' ],
				'range' => [
					'%' => [
						'min' => -100,
						'max' => 200,
					],
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .slider-nav-item' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->add_responsive_control(
			'arrow_height',
			[
				'label' => esc_html__( 'Height', 'exhibz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' , '%' ],
				'range' => [
					'%' => [
						'min' => -100,
						'max' => 200,
					],
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .slider-nav-item' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->start_controls_tabs(
            'navigation_style_tabs'
        );

        $this->start_controls_tab(
            'navigation_style_normal_tab',
            [
              'label' => __( 'Normal', 'exhibz' ),
            ]
        );

        $this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .slider-nav-item' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Icon Background Color', 'exhibz' ),
                'default' => '#E4E8F8',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.exhibz-ticket-widget .slider-nav-item' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_style_hover_tab',
            [
              'label' => __( 'Hover', 'exhibz' ),
            ]
        );

        $this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Hover Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .slider-nav-item:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'icon_bg_color_hover',
			[
				'label' => esc_html__( 'Icon Background Hover Color', 'exhibz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.exhibz-ticket-widget .slider-nav-item:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
			'divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'label' => esc_html__( 'Typography', 'exhibz' ),
				'selector' => '.exhibz-ticket-widget .slider-nav-item',
                'separator' => true
			]
		);

        $this->add_responsive_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Nav Border Radius', 'exhibz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .exhibz-ticket-widget .slider-nav-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'slide_prev_position',
			[
				'label'       => esc_html__('Previous Button Position (x-axis)', 'exhibz'),
				'description' => esc_html__('(-) Negative values are allowed', 'exhibz'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'%'  => [
						'min' => -10,
						'max' => 100,
					],
					'px' => [
						'min' => -500,
						'max' => 1100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'slide_next_position',
			[
				'label'       => esc_html__('Next Button Position (x-axis)', 'exhibz'),
				'description' => esc_html__('(-) Negative values are allowed', 'exhibz'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'%'  => [
						'min' => -100,
						'max' => 200,
					],
					'px' => [
						'min' => -300,
						'max' => 300,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    } //Register control end

    protected function render() {
        $settings              = $this->get_settings();
        $single_event_id       = !empty( $settings['event_id'] ) ? $settings['event_id']: 0;
        $plan_title            = $settings["plan_title"];
        $style                 = $settings["event_style"];
        $slider_loop           = $settings["ticket_slider_loop"];
        $slider_autoplay       = $settings["ticket_slider_autoplay"];
        $navigation            = $settings["show_navigation"];
        $slider_speed          = $settings["ticket_slider_speed"];
        $settings['widget_id'] = $this->get_id();

        $slide_controls    = [
            'slider_loop'     => $slider_loop,
            'autoplay_slide' => $slider_autoplay,
            'navigation'      => $navigation,
            'speed'           => $slider_speed,
            'widget_id' => $this->get_id()
        ];

        $slide_controls = \json_encode($slide_controls);


        if ( class_exists( 'WooCommerce' ) ) {
            if( function_exists('wc_print_notices') ){
             wc_print_notices();
            }
         }
        ?>
        <div class="exhibz-ticket-widget etn-event-form-widget">
            <?php

                $sells_engine="";
                if ( class_exists('Wpeventin_Pro') ) {
                    $sells_engine = \Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->check_sells_engine();
                }
                if(class_exists('WooCommerce') && 'woocommerce' === $sells_engine) {
                    $price_decimal      =  esc_attr( wc_get_price_decimals() );
                    $thousand_separator =  esc_attr( wc_get_price_thousand_separator() );
                    $price_decimal_separator = esc_attr( wc_get_price_decimal_separator() );
                } else {
                    $price_decimal      =  '2';
                    $thousand_separator =  ',';
                    $price_decimal_separator =  '.';
                }
            
            if( !empty( $settings["show_title"] ) ){
                ?>
                <div>
                    <h3 class="etn-event-form-widget-title"><?php echo esc_html( get_the_title( $single_event_id ) );?></h3>
                </div>
                <?php
            }
            
            ?>
            <?php
            if( class_exists('WooCommerce') ){
                 
                $data = \Etn\Utils\Helper::single_template_options( $single_event_id );
                $etn_left_tickets = !empty( $data['etn_left_tickets'] ) ? $data['etn_left_tickets'] : 0;
                $etn_ticket_unlimited = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
                $etn_ticket_price = isset( $data['etn_ticket_price']) ? $data['etn_ticket_price'] : '';
                $etn_deadline_value = isset( $data['etn_deadline_value']) ? $data['etn_deadline_value'] : '';
                $total_sold_ticket = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
                $ticket_qty = get_post_meta( $single_event_id, "etn_sold_tickets", true );
                $is_zoom_event = get_post_meta( $single_event_id, 'etn_zoom_event', true );
                $event_options = !empty( $data['event_options']) ? $data['event_options'] : [];
                $event_title = get_the_title( $single_event_id );
                $min_purchase_qty       = !empty(get_post_meta( $single_event_id, 'etn_min_ticket', true )) ? get_post_meta( $single_event_id, 'etn_min_ticket', true ) : 1;
                $max_purchase_qty       = !empty(get_post_meta( $single_event_id, 'etn_max_ticket', true )) ? get_post_meta( $single_event_id, 'etn_max_ticket', true ) : $etn_left_tickets;
                $max_purchase_qty       =  min($etn_left_tickets, $max_purchase_qty);

                $ticket_variation = get_post_meta($single_event_id,"etn_ticket_variations",true);
                $etn_ticket_availability = get_post_meta($single_event_id,"etn_ticket_availability",true);

 
                ?>
              
                <div class="etn-widget etn-ticket-widget ticket-widget-banner etn-single-event-ticket-wrap">

                    <h4 class="etn-widget-title etn-title etn-form-title"> <?php echo esc_html__(" Register Now:", 'exhibz'); ?>
                    </h4>
                    <?php
                    $attendee_reg_enable = !empty( \Etn\Utils\Helper::get_option( "attendee_registration" ) ) ? true : false;
                    ?>
                    <form method="post" class="etn-event-form-parent etn-ticket-variation"
                    ata-decimal-number-points="<?php echo esc_attr( $price_decimal ); ?>"
                    data-thousand-separator="<?php echo esc_attr( $thousand_separator ); ?>"
                    data-decimal-separator="<?php echo esc_attr( $price_decimal_separator ); ?>"
                    >
                    <input name="event_id" type="hidden" value="<?php echo intval($single_event_id); ?>" />
                    <?php  wp_nonce_field('ticket_purchase_next_step_two','ticket_purchase_next_step_two'); ?>
                    <?php
                        if( $attendee_reg_enable ){
                            ?>
                            
                            <input name="ticket_purchase_next_step" type="hidden" value="two" />
                            
                            <input name="event_name" type="hidden" value="<?php echo esc_html($event_title); ?>" />
                            <?php
                        }else{
                            ?>
                            <input name="add-to-cart" type="hidden" value="<?php echo intval($single_event_id); ?>" />
                            <input name="event_name" type="hidden" value="<?php echo esc_html($event_title); ?>" />
                            <?php
                        }
                        ?>
                    <!-- Ticket Markup Starts Here -->
                    <?php
                    $ticket_variation = get_post_meta($single_event_id,"etn_ticket_variations",true);
                    $etn_ticket_availability = get_post_meta($single_event_id,"etn_ticket_availability",true);


                    if ( is_array($ticket_variation) && count($ticket_variation) > 0 ) { 
                        $cart_ticket = [];
                        if ( class_exists('Woocommerce') && !is_admin()){
                            global $woocommerce;
                            $items = $woocommerce->cart->get_cart();

                            foreach($items as $item => $values) { 
                                if ( !empty( $values['etn_ticket_variations']) ) {
                                    $variations = $values['etn_ticket_variations'];
                                    if ( !empty($variations) && !empty($variations[0]['etn_ticket_slug'])) {
                                        if ( !empty($cart_ticket[$variations[0]['etn_ticket_slug']])) {
                                            $cart_ticket[$variations[0]['etn_ticket_slug']] += $variations[0]['etn_ticket_qty'];
                                        }else {
                                            $cart_ticket[$variations[0]['etn_ticket_slug']] = $variations[0]['etn_ticket_qty'];
                                        }
                                    }
                                }
                            }
                        }
                        
                        $number = !empty($i) ? $i : 0;
                        ?>  
                            
                            <div class="variations_<?php echo intval($number);?>">
                                
                                <?php 
                                    if($style === 'style-2'){ 
                                        include (get_template_directory() . '/components/editor/elementor/widgets/style/event-ticket/event-ticket.php');

                                    }  elseif($style === 'style-1') { ?>
                                    
                                    <?php $swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';?>
                                    <div class="ticket-variation-slider" data-controls="<?php echo esc_attr($slide_controls); ?>">
                                        <div class="<?php echo esc_attr($swiper_class); ?>">
                                            <div class="swiper-wrapper">
                                                <?php 
                                                    include (get_template_directory() . '/components/editor/elementor/widgets/style/event-ticket/event-ticket.php');
                                                ?>
                                            </div>
                                        </div>

                                        <?php if($settings['show_navigation'] == 'yes'){ ?>
                                            <div class="slider-nav-item swiper-button-prev swiper-prev-<?php echo esc_attr($this->get_id()); ?>">
                                                <?php \Elementor\Icons_Manager::render_icon( $settings['left_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </div>
                                            <div class="slider-nav-item swiper-button-next swiper-next-<?php echo esc_attr($this->get_id()); ?>"> 
                                                <?php \Elementor\Icons_Manager::render_icon( $settings['right_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                            </div>
                        <?php 
                    } 
                    ?>
                    </form>
                    <?php

                    // show if this is a zoom event
                    if( isset( $is_zoom_event ) && "on" == $is_zoom_event){
                    ?>
                        <div class="etn-zoom-event-notice">
                            <?php echo esc_html__("[Note: This event will be held on zoom. Attendee will get zoom meeting URL through email]", 'exhibz');?>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                
           <?php 
            }
           ?>
        </div>
        <?php
        
    }
    protected function content_template(){}
}
