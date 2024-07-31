<?php

namespace Etn_Pro\Core\Modules\Rsvp;

defined( 'ABSPATH' ) || die();
class Helper {

	use \Etn_Pro\Traits\Singleton;

	public function get_attendee_email() {
		$html = '';
		$args = array(
			'post_type' => 'etn-attendee',
			'numberposts'   => 10,
			'meta_query' => array(
				array(
					'key' => 'etn_email',
					'compare' => 'EXISTS'
				)
			)                     
			);
		$attendees = get_posts($args);

		foreach( $attendees as $attendee){
			$email  = get_post_meta( $attendee->ID, 'etn_email', true );

			if(!empty($email) && $email != ''){
				$html  .= $email.PHP_EOL;
			}			
		}

		return $html;
	}
	public function get_attendee_name() {
		$html = '';
		$args = array(
			'post_type' => 'etn-attendee',
			'numberposts'   => 10,
			'meta_query' => array(
				array(
					'key' => 'etn_email',
					'compare' => 'EXISTS'
				)
			)              
		);

		$attendees = get_posts($args);

		foreach( $attendees as $attendee){
			$name   = get_post_meta( $attendee->ID, 'etn_name', true );
			
			if(!empty($name) && $name != ''){
				$html  .= $name.PHP_EOL;
			}
		}

		return $html;
	}

	/**
	 * RSVP count
	 */
	public function get_rsvp() {
		$get_rsvp = get_posts( array( 'post_type' => 'etn_rsvp' ) );
		return ! empty( $get_rsvp );
	}

	/**
	 * RSVP get going attendee list
	 */
	public function get_rsvp_going_attendee( $event_id, $attendee_limit = -1 ) {
		$get_rsvp       = $this->data_query( $event_id, 0 , [], $attendee_limit ); // get all rsvp for this event
		$child_event    = array();
		if ( !empty($get_rsvp) ) {
			foreach ($get_rsvp as $key => $value) {
				$etn_rsvp_value = get_post_meta( $value->ID , 'etn_rsvp_value' , true );
				if ( "going" == $etn_rsvp_value ) {
					$child_rsvp = $this->data_query( $event_id , $value->ID , $etn_rsvp_value, $attendee_limit ); // get child rsvp / attendee for this event

					if ( !empty( $child_rsvp ) ) {
						foreach ($child_rsvp as $key => $item) {		
							$child_event[$item->ID ]['name'] = get_the_title( $item->ID );
						}
					}
				}
			}
		}

		return $child_event;
	}

	/**
	 * Get rsvp attendee
	 */
	public function data_query( $id, $post_parent = 0, $args = array(), $post_per_page = -1 ) {

		$args = array(
			'posts_per_page' => $post_per_page,
			'post_type'      => 'etn_rsvp',
			'post_parent'    => $post_parent,
		);

		if ( 0 !== $id ) {
			$args['meta_query'] = 
			array(
				array(
					'key'     => 'event_id',
					'value'   => $id,
					'compare' => '=',
				),
			);

			// check going status
			if ( !empty( $args['etn_rsvp_value'] ) ) {
				$going_value = 	array(
					'key'     => 'etn_rsvp_value',
					'value'   => $args['etn_rsvp_value'],
					'compare' => '='
				);

				array_push( $args['meta_query'] , $going_value );
			}
		}

		$etn_rsvp = get_posts( $args );

		return $etn_rsvp;
	}

}


?>