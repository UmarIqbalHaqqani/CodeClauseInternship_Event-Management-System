<?php

namespace Etn_Pro\Core\Migration;

defined('ABSPATH') || exit;

class Migration {
    use \Etn_Pro\Traits\Singleton;

    /**
     * Main Function 
     *
     * @return void
     */
    public function init(){

        $this->migrate_attendee_extra_fields_restructure();
    }


    /**
     * Restructure attendee extra fields in 2d formation
     *
     * @return void
     */
    public function migrate_attendee_extra_fields_restructure(){
        
        $migration_done = !empty( get_option( "etn_attendee_extra_fields_restructure_done" ) ) ? true : false;

        if( !$migration_done ){

            $settings                     = \Etn\Core\Settings\Settings::instance()->get_settings_option();
            // $settings = get_option('etn_event_options');
            
            $attendee_extra_labels        = isset( $settings['attendee_extra_label'] ) ? $settings['attendee_extra_label'] : [];
            $attendee_extra_types         = isset( $settings['attendee_extra_type'] ) ? $settings['attendee_extra_type'] : [];
            $attendee_extra_place_holders = isset( $settings['attendee_extra_place_holder'] ) ? $settings['attendee_extra_place_holder'] : [];
            $attendee_extra_checkboxes    = isset( $settings['attendee_extra_checkbox'] ) ? $settings['attendee_extra_checkbox'] : [];

            // to save previous structured data so that can test if require
            $etn_old_attendee_extra_field_data = [
                'attendee_extra_labels'        => $attendee_extra_labels,
                'attendee_extra_types'         => $attendee_extra_types,
                'attendee_extra_place_holders' => $attendee_extra_place_holders,
                'attendee_extra_checkboxes'    => $attendee_extra_checkboxes,
            ];

            $attendee_extra_fields = [];
            if( is_array( $attendee_extra_types ) && !empty( $attendee_extra_types ) ){
                $auto_index = 0;

                foreach( $attendee_extra_types as $index => $attendee_extra_type ){
                    $attendee_extra_label = isset( $attendee_extra_labels[$index] ) ? $attendee_extra_labels[$index] : '';

                    if( !empty( $attendee_extra_label ) && !empty( $attendee_extra_type ) ){
                        $attendee_extra_fields[$auto_index]['label']        = $attendee_extra_label;
                        $attendee_extra_fields[$auto_index]['type']         = $attendee_extra_type;
                        $attendee_extra_fields[$auto_index]['place_holder'] = isset( $attendee_extra_place_holders[$index] ) ? $attendee_extra_place_holders[$index] : '';

                        if( in_array( $index, $attendee_extra_checkboxes ) ){
                            $attendee_extra_fields[$auto_index]['show_in_dashboard'] = '';
                        }

                        $auto_index++;
                    }
                }
            }
            
            $settings['attendee_extra_fields'] = $attendee_extra_fields;
       
            // previous structured extra fields key(with data) removing from settings
            // now those data are in option table. key name: 'etn_old_attendee_extra_field_data' 
            $extra_fields = [
                'attendee_extra_label',
                'attendee_extra_type', 
                'attendee_extra_place_holder', 
                'attendee_extra_checkbox'
            ];

            foreach( $extra_fields as $extra_field ) {
                if( isset( $settings[$extra_field] ) ){
                    // unset will be later if everthing is woking fine
                    // unset( $settings[$extra_field] ); 
                }
            }
 
            update_option( "etn_event_options", $settings );
            
            // update_option( "etn_temp_event_options_2", $settings );
            // update_option( "etn_temp_attendee_extra_fields", serialize( $attendee_extra_fields ) );
         
            update_option( "etn_old_attendee_extra_field_data", serialize( $etn_old_attendee_extra_field_data ) );

            update_option( "etn_attendee_extra_fields_restructure_done", true );
        }

    }

}