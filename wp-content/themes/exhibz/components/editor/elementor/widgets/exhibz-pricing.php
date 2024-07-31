<?php
namespace Elementor;
use \Etn\Utils\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;


class Exhibz_Cretive_Pricing_Widget extends Widget_Base {


  public $base;

    public function get_name() {
        return 'exhibz-cretive-pricing';
    }

    public function get_title() {

        return esc_html__( 'Exhibz Creative Pricing', 'exhibz' );

    }

    public function get_icon() { 
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'exhibz-elements' ];
    }

    protected function register_controls() {

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
					'{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .content .pricing-title' => 'color: {{VALUE}}',
				],
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'plan_typography',
                'label' => esc_html__( 'Plan Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .content .pricing-title',
			]
		);

        $this->add_control(
			'plan_end_color',
			[
				'label' => esc_html__( 'Plan Date Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .content .pricing-date' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_background',
				'label' => esc_html__( 'Wrapper Background', 'exhibz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .pricing-ticket-wrapper .event-registration',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'event_form_price_typography',
                'label' => esc_html__( 'Price Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .ticket-price-item .etn-ticket-price',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
                'label' => esc_html__( 'Total Price Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .etn-form-wrap .etn-single-ticket-item .etn-subtotal *',
			]
		);

        $this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Button Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .pricing-btn' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'btn_Hover_color',
			[
				'label' => esc_html__( 'Button Hover Color', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .pricing-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
                'label' => esc_html__( 'Button Typo', 'exhibz' ),
				'selector' => '{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .pricing-btn',
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Button Background', 'exhibz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .pricing-btn',
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'label' => esc_html__( 'Button Hover Background', 'exhibz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .pricing-ticket-wrapper .event-registration .etn-form-wrap .pricing-btn:hover',
			]
		);

        $this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Button Padding', 'exhibz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etn-form-wrap .pricing-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etn-form-wrap .pricing-btn',
			]
		);

        $this->end_controls_section();

       
    } 


    protected function render() {
        $settings              = $this->get_settings();
        $single_event_id       = !empty( $settings['event_id'] ) ? $settings['event_id']: 0;
       
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
                    <?php  wp_nonce_field('ticket_purchase_next_step_two','ticket_purchase_next_step_two'); ?>
                    <input name="event_id" type="hidden" value="<?php echo intval($single_event_id); ?>" />
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
                            
                            <div class="variations_<?php echo intval($number);?> pricing-ticket-wrapper row">
                                <?php 
                                    include (get_template_directory() . '/components/editor/elementor/widgets/style/event-ticket/creative-ticket.php');
                                 ?>

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

