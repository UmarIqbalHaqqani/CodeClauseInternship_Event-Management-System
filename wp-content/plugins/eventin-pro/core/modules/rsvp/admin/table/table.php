<?php

namespace Etn_Pro\Core\Modules\Rsvp\Admin\Table;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/inclueds/class-wp-list-table.php';
}

class Table extends \WP_List_Table {

    public $textdomain = 'eventin-pro';
    public $singular_name;
    public $plural_name;
    public $id      = '';
    public $columns = [];

    /**
     * Show list
     */
    function __construct( $all_data_of_table ) {

        $this->singular_name = $all_data_of_table['singular_name'];
        $this->plural_name   = $all_data_of_table['plural_name'];
        $this->id            = $all_data_of_table['event_id'];
        $this->columns       = $all_data_of_table['columns'];

        parent::__construct( [
            'singular' => $this->singular_name,
            'plural'   => $this->plural_name,
            'ajax'     => true,
        ] );
    }

    /**
     * Get column header function
     */
    public function get_columns() {

        return $this->columns;
    }

    /**
     * Sortable column function
     */
    public function get_sortable_columns() {

        return $this->columns;
    }

    /**
     * Display all row function
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
        case $column_name:
            return $item[$column_name];
        default:
            isset( $item[$column_name] ) ? $item[$column_name] : '';
            break;
        }
    }

    /**
     * Add action link
     */
    protected function row_actions( $actions, $always_visible = false ) {
        $action_count = count( $actions );

        if ( ! $action_count ) {
            return '';
        }

        $mode = get_user_setting( 'posts_list_mode', 'list' );

        if ( 'excerpt' === $mode ) {
            $always_visible = true;
        }

        $out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';

        $i = 0;

        foreach ( $actions as $action => $link ) {
            ++$i;

            $sep = ( $i < $action_count ) ? ' | ' : '';

            $out .= "<span class='$action'>$link$sep</span>";
        }

        $out .= '</div>';

        $out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . esc_html__( 'Show more details', 'eventin-pro' ) . '</span></button>';

        return $out;
    }

    /**
     * Main query and show function
     */

    public function preparing_items() {
        $per_page              = 10;
        $column                = $this->get_columns();
        $hidden                = [];
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = [$column, $hidden, $sortable];
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page - 1 ) * $per_page;

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'];
        }

        $args['limit']  = $per_page;
        $args['offset'] = $offset;

        $get_data = $this->get_all_data( $this->id, $args );

        $this->set_pagination_args( [
            'total_items' => $this->total_data( $this->id ),
            'per_page'    => $per_page,
        ] );

        $this->items = $get_data;
    }

	/**
	 * Get all RSVP rows
	 */
	private function get_all_data( $id ) {
		$all_data = array();
		$etn_rsvp = \Etn_Pro\Core\Modules\Rsvp\Helper::instance()->data_query( $id );

		if ( count($etn_rsvp)>0 ) {
			foreach ($etn_rsvp as $key => $value) {
				if ( !empty($value) && !empty($value->ID) ) {

					$total_attendee = get_post_meta( $value->ID, 'number_of_attendee' , true) !== "" ?  esc_html__('No of Attendee : ','eventin-pro'). get_post_meta( $value->ID, 'number_of_attendee' , true ) : "";
					$email = get_post_meta( $value->ID, 'attendee_email' , true) !== "" ?  esc_html__('Email : ','eventin-pro'). get_post_meta( $value->ID, 'attendee_email' , true ) : "";
					$status = get_post_meta( $value->ID, 'etn_rsvp_value' , true);
					if ( $status !=="" ) {
						$status = strtolower(str_replace('_',' ',$status));
					}
					$all_data[$key]['responser'] =
					esc_html__(' Name : ','eventin-pro'). get_the_title($value->ID )
					."<br/>". $email;
					$all_data[$key]['total_attendees'] = $total_attendee == "" ? "-" : $total_attendee;

					// get child events in 'attendees' column
					$all_data[$key]['attendees'] = $this->child_data_query( $id , $value->ID );
					$all_data[$key]['status'] = $status;
				}
			}
		}


		return $all_data;
	}

	/**
	 * Total RSVP rows
	 */
	private function total_data( $id ) {
		$etn_rsvp = \Etn_Pro\Core\Modules\Rsvp\Helper::instance()->data_query($id);

		return count( $etn_rsvp );
	}


	/**
	 * Get child attendees
	 */
	private function child_data_query( $id , $post_parent  ) {
		$etn_child_rsvp = \Etn_Pro\Core\Modules\Rsvp\Helper::instance()->data_query( $id , $post_parent);
		$etn_rsvp_value = get_post_meta( $post_parent, 'etn_rsvp_value' , true );
		$html = "";
		if ( !empty($etn_child_rsvp) && $etn_rsvp_value != 'not_going' ) {
			foreach ($etn_child_rsvp as $key => $child) {
				$html .= esc_html__( 'Name:', 'eventin-pro' ) .' '. get_the_title( $child->ID ).'<br/>';
				$html .= esc_html__( 'Email:', 'eventin-pro' ) ." ". get_post_meta( $child->ID , 'attendee_email' , true ). "<br/>";
			}
		} else {
			$html .= esc_html__( 'Reason: ', 'eventin-pro' );
			$html .= ( get_post_meta( $post_parent, 'rsvp_not_going_reason' , true ) ) ? get_post_meta( $post_parent, 'rsvp_not_going_reason' , true ) : esc_html__( 'Unspecified by the responder.', 'eventin-pro' );
			$html .= '<br/>';
		}
		
		return $html == "" ? "-" : $html ;
	}

}
