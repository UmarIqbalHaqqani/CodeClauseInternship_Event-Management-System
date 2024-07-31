<?php
namespace Etn_Pro\Core\Event\Sales;

defined('ABSPATH') || exit;

if ( ! class_exists( 'WP_List_Table' )){
    require_once ABSPATH . 'wp-admin/inclueds/class-wp-list-table.php';
}

class Attendee_Sales_Report extends \WP_List_Table{

    public $textdomain = 'eventin-pro';
    public $singular_name;
    public $plural_name;
    public $id = '';
    
    /**
     * Show list
     */
    function __construct($all_data_of_table){

        $this->singular_name = $all_data_of_table['singular_name'];
        $this->plural_name   = $all_data_of_table['plural_name'];
        $this->id            = $all_data_of_table['event_id'];

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

        return [
            'invoice'        => esc_html__( 'Invoice',  'eventin-pro' ),
            'full_name'      => esc_html__( 'Purchaser Name',  'eventin-pro' ),
            'email'          => esc_html__( 'Purchaser Email',  'eventin-pro' ),
            'payment_gateway'=> esc_html__( 'Payment Type',  'eventin-pro' ),
            'ticket_qty'     => esc_html__( 'Total Tickets', 'eventin-pro' ),
            'ticket_details' => esc_html__( 'Ticket Details', 'eventin-pro' ),
            'amount'         => esc_html__( 'Amount', 'eventin-pro' ),
            'status'         => esc_html__( 'Status',  'eventin-pro' ),
            'date_time'      => esc_html__( 'Date',  'eventin-pro' ),
            'send_ticket'    => esc_html__( 'Resend Email(s)',  'eventin-pro' ),
        ];
    }
    

    /**
     * Sortable column function
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
          'invoice'        => array('invoice',true),
          'full_name'      => array('full_name',true),
          'email'          => array('email',true),
          'payment_gateway'=> array('payment_gateway',true),
          'no_of_tickets'  => array('no_of_tickets' , true ),
          'status'         => array('status',false),
        );

        return $sortable_columns;
    }

    /**
     * Display all row function
     */
    protected function column_default( $item , $column_name ){

        switch( $column_name ) { 
            case $column_name:
                return  isset( $item->$column_name ) ? $item->$column_name : '';
            default:
                isset( $item->column_name ) ? $item->column_name : '';
            break;
          }
    }

    /**
     * Show checkbox function
     */
    protected function column_cb( $item ){

        return sprintf(
            '<input type="checkbox" name="event_id[]" value="">', esc_url(admin_url('#', $item->event_id ))
       );
    }

    /**
     * Main query and show function
     */
    
    public function preparing_items(){
        $per_page = 20;
        $column   = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [ $column , $hidden , $sortable ];
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1) * $per_page;
      
        if ( isset( $_REQUEST['orderby']) && isset( $_REQUEST['order']) ){
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $args['limit']  = $per_page;
        $args['offset'] = $offset;

        $etn_pro_all_sales = \Etn_Pro\Core\Action::instance()->attendee_list( $this->id, $args );

        $this->set_pagination_args( [
            'total_items' => \Etn_Pro\Core\Action::instance()->total_attendee($this->id),
            'per_page' => $per_page,
        ] );
        
        $this->items =  $etn_pro_all_sales;
    }

}