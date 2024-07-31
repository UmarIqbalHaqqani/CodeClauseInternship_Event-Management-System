<?php
/**
 * Speaker Payload
 * 
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Speaker Payload Class
 */
class SpeakerPayload implements PayloadInterface {
    /**
     * Get payload
     *
     * @param   mixed  $args
     *
     * @return  array
     */
    public function get_data( $post_id ) {
        $company_logo_id = get_post_meta( $post_id, 'etn_speaker_company_logo', true );
        $company_logo_id = ! empty( $company_logo_id ) ? $company_logo_id : 0;

        $speaker = [
            'id'               => $post_id,
            'name'             => get_post_meta( $post_id, 'etn_speaker_title', true ),
            'designation'      => get_post_meta( $post_id, 'etn_speaker_designation', true ),
            'email'            => get_post_meta( $post_id, 'etn_speaker_website_email', true ),
            'summary'          => get_post_meta( $post_id, 'etn_speaker_summery', true ),
            'socials'          => get_post_meta( $post_id, 'etn_speaker_socials', true ),
            'company_url'      => get_post_meta( $post_id, 'etn_speaker_url', true ),
            'company_logo'     => $company_logo_id,
            'company_logo_url' => wp_get_attachment_image_url( $company_logo_id ),
            'category'         => wp_get_object_terms( $post_id, 'etn_speaker_category' ),
        ];

        return $speaker;
    }
}
