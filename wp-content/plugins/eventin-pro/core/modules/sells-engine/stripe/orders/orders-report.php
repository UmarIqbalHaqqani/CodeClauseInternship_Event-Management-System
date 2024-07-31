<?php
namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders;
use Etn_Pro\Utils\Helper;

defined('ABSPATH') || exit;

if ( ! class_exists( 'WP_List_Table' )){
    require_once ABSPATH . 'wp-admin/inclueds/class-wp-list-table.php';
}

class Orders_Report extends \WP_List_Table{

    public $singular_name;
    public $plural_name;
    public $id = '';
    
    /**
     * Show list
     */
    function __construct($all_data_of_table){

        $this->singular_name = $all_data_of_table['singular_name'];
        $this->plural_name   = $all_data_of_table['plural_name'];

        parent::__construct( [
            'singular' => $this->singular_name ,
            'plural'   => $this->plural_name ,
            'ajax'     => true ,
        ]);
    }
    
    /**
     * Get column header function
     */
    public function get_columns(){

        $country_currency = \Etn_Pro\Utils\Helper::retrieve_country_currency();
        return [
            'cb'              => '<input type="checkbox"/>',
            'order_id'        => esc_html__( 'Order ID',  'eventin-pro' ),
            'invoice'         => esc_html__( 'Invoice',  'eventin-pro' ),
            'puchaser_info'   => esc_html__( 'Purchaser',  'eventin-pro' ),
            'email'           => esc_html__( 'Email',  'eventin-pro' ),
            'payment_type'    => esc_html__( 'Payment Gateway',  'eventin-pro' ),
            'event_amount'    => esc_html__( 'Amount',  'eventin-pro' ) . ' [' . esc_html( $country_currency['currency'] ) . ']',
            'status'          => esc_html__( 'Status',  'eventin-pro' ),
            'date_time'       => esc_html__( 'Date',  'eventin-pro' ),
            'details'         => esc_html__( 'Action',  'eventin-pro' ),
        ];
    }

    /**
     * Display all row function
     */
    protected function column_default( $item , $column_name ){
        switch( $column_name ) { 
            case $column_name:
                return $item[ $column_name ];
            default:
                isset( $item[ $column_name ] ) ? $item[ $column_name ]: '';
            break;
          }
    }

    /**
     * purchaser information
     */
    protected function column_puchaser_info( $item ){
        $stripe_order_id = $item['form_id'];
        $first_name      = get_post_meta( $stripe_order_id, '_billing_first_name', true );
        $last_name       = get_post_meta( $stripe_order_id, '_billing_last_name', true );
        return $first_name . ' ' . $last_name;
    }

    /**
     * Show order id link
     */
    public function column_order_id( $item ){
        $order_url      = admin_url( 'admin.php?page=etn_stripe_orders_report&order_id=' . $item['form_id'] );
        $order_url_href = "<a href='". $order_url ."' target='_blank'>". $item['form_id'] ."</a>";

        return $order_url_href;
    }

    /**
     * Show order deatils link
     */
    public function column_details( $item ){
        $order_details_url = admin_url( 'admin.php?page=etn_stripe_orders_report&order_id=' . $item['form_id'] );
        $refund_url = admin_url( 'admin.php?page=etn_stripe_orders_report&action=etn-stripe-refund&order_id=' . $item['form_id'] );
        $status = get_post_meta( $item['form_id'], 'etn_payment_status', true );
        ob_start();
        ?>
        <div class="etn-event-report-action">
            <div class="etn-event-report-view-report"><a target="" class="etn-btn-text btn-primary" href='<?php echo esc_url( $order_details_url ) ;?>'> <?php echo esc_html__('Details', 'eventin-pro') ?></a></div>

            <?php if ( 'Completed' === $status ): ?>
                <div class="etn-event-report-view-report"><a class="etn-btn-text btn-primary" href='<?php echo esc_url( $refund_url ) ;?>'> <?php echo esc_html__('Refund', 'eventin-pro') ?></a></div>
            <?php endif; ?>

        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Show order deatils link
     */
    public function column_status( $item ){
        return get_post_meta( $item['form_id'], 'etn_payment_status', true );
    }
   
    /**
     * Main query and show function
     */
    
    public function preparing_items(){
        $per_page = 20;
        $column   = $this->get_columns();
        $hidden   = [];
        $this->_column_headers = [ $column , $hidden ];
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $per_page;

        $args = [];
        if ( isset( $_REQUEST['orderby']) && isset( $_REQUEST['order']) ){
            $args['orderby'] = sanitize_text_field( $_REQUEST['orderby'] );
            $args['order']   = sanitize_text_field( $_REQUEST['order'] );
        } 
        // search result
        $args['limit']          = $per_page;
        $args['offset']         = $offset;
        $order_history       = \Etn_Pro\Core\Action::instance()->order_history( $args );

        $this->set_pagination_args( [
            'total_items'   => $order_history['count'],
            'per_page'      => $per_page,
        ] );
        $this->items =  $order_history['data'];

        // $this->process_bulk_actions();
    }


    /**
     * Get bulk actions
     *
     * @return  array
     */
    // public function get_bulk_actions() {
    //     return [
    //         'export-csv' => __( 'Export CSV', 'eventin' ),
    //         'export-json' => __( 'Export JSON', 'eventin' ),
    //     ];
    // }

    /**
     * Set column Cb
     *
     * @param   array  $item
     *
     * @return  string
     */
    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="stripe-orders[]" value="%s" />', $item['form_id'] );
    }

    /**
     * Process bulk actions
     *
     * @return void
     */
    public function process_bulk_actions() {
        $action = ! empty( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : ''; 
        $orders = ! empty( $_POST['stripe-orders'] ) ? $_POST['stripe-orders'] : ''; 

        $actions = [
            'export-csv',
            'export-json'
        ];

        
        if ( ! in_array( $action, $actions ) ) {
            return;
        }

        $export_type = 'csv';

        if ( 'export-json' == $action ) {
            $export_type = 'json';
        }

        $schedule_exporter = new Order_Exporter();
        $schedule_exporter->export( $orders, $export_type );

        wp_redirect( admin_url('admin.php?page=etn_stripe_orders_report') );

        exit();
    }

    /**
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {

        $url      = admin_url( 'admin.php?post_type=etn-stripe-order');
        $json_url = $url . '&etn-action=export&format=json';
        $csv_url  = $url . '&etn-action=export&format=csv';
        $nonce_action = 'etn_data_export_nonce_action';
        $nonce_name   = 'etn_data_export_nonce';

            printf( '
                <div class="dropdown">
                    <a href="#" class="button etn-post-export">%s</a>
                        <div class="dropdown-content">
                            <a href="%s">%s</a>
                            <a href="%s">%s</a>
                        </div>
                </div>
            ', __( 'Export', 'eventin-pro' ), wp_nonce_url( $json_url, $nonce_action, $nonce_name ),  __( 'Export JSON Format', 'eventin-pro' ),wp_nonce_url( $csv_url, $nonce_action, $nonce_name ), __( 'Export CSV Format', 'eventin-pro' ) );

            printf( '<a href="%s" class="button etn-post-import" data-post_type="etn-stripe-order">%s</a>', $url . '&action=import', __( 'Import', 'eventin-pro' ) );
	}
}