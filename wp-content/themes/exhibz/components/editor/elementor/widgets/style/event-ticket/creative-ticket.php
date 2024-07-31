<?php

// $price_decimal = class_exists( 'WooCommerce' ) ? esc_attr( wc_get_price_decimals() ) : '2';
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

foreach ($ticket_variation as $key => $value) { 
    $etn_min_ticket   = !empty( $value['etn_min_ticket'] ) ? absint( $value['etn_min_ticket'] ) : 0 ;
    $etn_max_ticket   = !empty( $value['etn_max_ticket'] ) ? absint( $value['etn_max_ticket'] ) : 0 ;
    $sold_tickets  = absint( $value['etn_sold_tickets'] );
    $total_tickets = absint( $value['etn_avaiilable_tickets'] );

    $etn_cart_limit = 0;
    if (  !empty($cart_ticket) ) {
        $etn_cart_limit = !empty( $cart_ticket[$value['etn_ticket_slug']] ) ? $cart_ticket[$value['etn_ticket_slug']] : 0;
    }

    $etn_current_stock = absint( $total_tickets - $sold_tickets ); 
    $stock_outClass = ($etn_current_stock === 0) ? 'stock_out' : '';
   
    if ($etn_current_stock > 0) {
                       
    ?> 
        <div class="event-registration variation_<?php esc_attr_e($key)?> col-lg-4 col-md-6 col-sm-12">
            <div class="etn-form-wrap">
                        <div class="content">
                            <h2 class="pricing-title">
                                <?php esc_html_e($value['etn_ticket_name']);  ?>
                            </h2>
                            <p class="pricing-date">
                                <?php esc_html_e('Until ','exhibz'); echo esc_html($etn_deadline_value); ?>
                            </p>
                        </div>
                <div class="etn-single-ticket-item">
                    <div class="item">
                    <div class="ticket-price-item">
                            <div class="etn-ticket-price">
                                <?php 
                                    if(function_exists("get_woocommerce_currency_symbol")){
                                        echo esc_html(get_woocommerce_currency_symbol()); 
                                    }  esc_html_e($value['etn_ticket_price']);
                                ?>
                            </div>
                            <label><?php echo esc_html__("/ Seat","exhibz");?></label>
                        </div>
                    </div><!-- ./item -->
                    
                    <div class="item etn-variable-ticket-widget">
                    <!-- Min , Max and stock quantity checking start -->
                    <div class="ticket-price-item etn-quantity">
                        <button type="button" class="qt-btn qt-sub" data-multi="-1" data-key="<?php echo intval($key)?>">-</button>
                        <input name="ticket_quantity[<?php echo  $value['etn_ticket_name']?>]" type="number"
                        class="etn_ticket_variation ticket_<?php echo intval( $key ); ?>"
                        value="0" id="ticket-input_<?php echo intval( $key ); ?>"
                        data-price="<?php echo number_format( (float) $value['etn_ticket_price'], $price_decimal, '.', '' ); ?>"
                        data-etn_min_ticket="<?php echo absint( $etn_min_ticket ); ?>"
                        data-etn_max_ticket="<?php echo absint( $etn_max_ticket ); ?>"
                        data-etn_current_stock="<?php echo absint( $etn_current_stock ); ?>"
                        data-stock_out="<?php echo esc_attr__( "All ticket has has been sold", "eventin" ) ?>"
                        data-cart_ticket_limit="<?php echo esc_attr__( "You have already added 5 tickets. You can't purchase more than $etn_max_ticket tickets", "eventin" ) ?>"
                        data-stock_limit="<?php echo esc_attr__( "Stock limit $etn_current_stock. You can purchase within $etn_current_stock.", "eventin" ) ?>"
                        data-qty_message="<?php echo esc_attr__( "Total Ticket quantity must be greater than or equal ", "eventin" ) . $etn_min_ticket . esc_attr__( " and less than or equal ", "eventin" ) . $etn_max_ticket; ?>"
                        data-etn_cart_limit="<?php echo absint( $etn_cart_limit ); ?>"
                        data-etn_cart_limit_message="<?php echo esc_attr__( "You have already added $etn_cart_limit, Which is greater than maximum quantity $etn_max_ticket . You can add maximum $etn_max_ticket tickets. ", "eventin" ); ?>"/>
                        <button type="button" class="qt-btn qt-add" data-multi="1"
                                data-key="<?php echo intval( $key ) ?>">+
                        </button>
                        </div>

                        <!-- Min , Max and stock quantity checking start -->
                    </div><!-- ./item -->
                    
                    <div class="item">
                        <div class="etn-subtotal" data-subtotal="<?php esc_attr_e( number_format( (float) $value['etn_ticket_price'], $price_decimal, '.', '' ) ); ?>">
                            <label><?php echo esc_html__("Total :","exhibz");?></label> 
                            <strong>
                                <?php 
                                    $price = number_format( (float) $value['etn_ticket_price'], $price_decimal, $price_decimal_separator, $thousand_separator ); 
                                ?>
                                <?php
                                    $price = '<span class="_sub_total_' . floatval( $key ) . '"></span>';
                                    echo \Etn\Core\Event\Helper::instance()->currency_with_position( $price );
                                ?>   
                            </strong>

                        </div>
                    </div><!-- ./item -->

                </div><!-- ./col -->
                    <!-- price_btn -->
                    <?php do_action( 'etn_before_add_to_cart_button', $single_event_id); ?>
                        <?php
                            $show_form_button = apply_filters("etn_form_submit_visibility", true, $single_event_id);

                            if ($show_form_button === false) {
                                ?>
                                <small><?php echo esc_html__('Event already expired!', "exhibz"); ?></small>
                                <?php
                            } else {
                                if (!isset($event_options["etn_purchase_login_required"]) || (isset($event_options["etn_purchase_login_required"]) && is_user_logged_in())) {
                                    ?>
                                <button name="submit" class="pricing-btn whitespace--normal" type="submit">
                                        <span class="exhibz-button-text">
                                            <?php echo esc_html__("Register Today", 'exhibz');?> 
                                        </span>
                                        
                                        <i aria-hidden="true" class="icon icon-arrow-right"></i>
                                </button>

                                    
                                    <?php
                                } else {
                                    ?>
                                    <small>
                                    <?php echo esc_html__('Please', 'eventin'); ?> <a href="<?php echo wp_login_url( get_permalink( ) ); ?>"><?php echo esc_html__( "Login", "exhibz" ); ?></a> <?php echo esc_html__(' to buy ticket!', "exhibz"); ?>
                                    </small>
                                    <?php
                                }
                            }
                        ?>
                        <?php do_action( 'etn_after_add_to_cart_button', $single_event_id); ?>
                        <!-- price_btn -->
                    
                <div class="show_message show_message_<?php echo intval($key);?> quantity-error-msg"></div>
            </div>
        </div>
    <?php } else {
            ?>
            <h6><?php echo esc_html__('No Tickets Available!!', 'exhibz'); ?></h6>
            <?php
        } ?>
    <?php do_action( 'etn_before_add_to_cart_total_price', $single_event_id, $key, $value ); ?>
    <?php 
} 
?>