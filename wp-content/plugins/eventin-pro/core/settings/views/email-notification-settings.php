<?php

use Etn\Core\Settings\Settings as SettingsFree;

$settings                            = SettingsFree::instance()->get_settings_option();
$remainder_message                   = isset( $settings['remainder_message'] ) ? $settings['remainder_message'] : '';
$purchase_email_message              = html_entity_decode( isset( $settings['purchase_email_message'] ) ? $settings['purchase_email_message'] : "" );
$remainder_email_sending_day         = isset( $settings['remainder_email_sending_day'] ) ? $settings['remainder_email_sending_day'] : '';
$remainder_email_sending_time        = isset( $settings['remainder_email_sending_time'] ) ? $settings['remainder_email_sending_time'] : '';
$invoice_include_event_details       = !empty( $settings['invoice_include_event_details'] ) ? 'checked' : '';
$invoice_include_event_link          = !empty( $settings['invoice_include_event_link'] ) ? 'checked' : '';
$off_remainder                       = (!empty( $settings['off_remainder'] ) && $settings['off_remainder'] == "yes" ) ? $settings['off_remainder'] : '';

?>
<div id="email_remainder_block">
	<div class="attr-form-group etn-label-item">
		<div class="etn-label">
			<label>
				<?php esc_html_e( 'Enabel Remainder Email', 'eventin-pro' ); ?>
			</label>
			<div class="etn-desc mb-2"> <?php esc_html_e( 'Do you want to send remainder email to event attendee?', 'eventin-pro' ); ?> </div>
		</div>
		<div class="etn-meta">
			<input id="off_remainder" type="checkbox" <?php esc_attr_e($off_remainder == "yes" ? "checked" : "", 'eventin-pro' ); ?> class="etn-admin-control-input" name="off_remainder"  value="yes" />
			<label for="off_remainder" data-left="Yes" data-right="No" class="etn_switch_button_label"></label>
		</div>
	</div>
	<div class="attr-form-group etn-label-item">
		<div class="etn-label">
			<label>
				<?php esc_html_e( 'Reminder Email Sending Day', 'eventin-pro' );?>
			</label>
			<div class="etn-desc"> <?php esc_html_e( 'Reminder email will be sent X day(s) before event starting time', 'eventin-pro' );?> </div>
		</div>
		<div class="etn-meta">
			<input id='remainder_email_sending_day' type="number" value="<?php echo esc_attr( $remainder_email_sending_day ); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" name="remainder_email_sending_day" placeholder="1" min="1" max="365"/>
		</div>
	</div>
	<div class="attr-form-group etn-label-item">
		<div class="etn-label">
			<label>
				<?php esc_html_e( 'Set Reminder Email Time', 'eventin-pro' ); ?>
			</label>
			<div class="etn-desc"> <?php esc_html_e( 'Select the time to send the reminder email', 'eventin-pro' );?> </div>
		</div>
		<div class="etn-meta">
			<input id='remainder_email_sending_time' type="text" value="<?php echo esc_attr( $remainder_email_sending_time ); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" name="remainder_email_sending_time" placeholder="12:00 PM" />
		</div>
	</div>

	<?php

	$remainder_email_markup_items = [
		'remainder_message' => [
			'item' => [
				'label'    => esc_html__( 'Reminder Email Message', 'eventin-pro' ),
				'desc'     => esc_html__( 'Reminder email message text', 'eventin-pro' ),
				'type'     => 'wp_editor',
				'attr'     => [ 'class' => 'attr-form-group etn-label-item etn-label-top' ],
				'settings' => [
					'textarea_name' => 'remainder_message',
					'media_buttons' => true,
					'wpautop'       => true
				]
			],
			'data' => [ 'remainder_message' => $remainder_message ],
		]
	];

	foreach ( $remainder_email_markup_items as $key => $info ) {
		$this->get_field_markup( $info['item'], $key, $info['data'] );
	}

	?>
</div>
<!-- Supported Template Tags -->
<div class="attr-form-group etn-label-item etn-label-top">
    <div class="etn-label">
        <label>
			<?php esc_html_e( 'Template Tags', 'eventin-pro' ); ?>
        </label>
        <div class="etn-desc"> <?php esc_html_e( 'Use the following tags to automatically add event information to the emails', 'eventin-pro' );?> </div>
        <?php
            $tag_list = array (
                array(
                    "tag_name" => '{site_name}',
                    "description" => esc_html__('The name of this website' , 'eventin-pro'),
                ),
                array(
                    "tag_name" => '{site_link}',
                    "description" => esc_html__('A link to this website' , 'eventin-pro'),
                ),
                array(
                    "tag_name" => '{site_logo}',
                    "description" => esc_html__('Logo of the website' , 'eventin-pro'),
                ),
                array(
                    "tag_name" => '{event_title}',
                    "description" => esc_html__('Purchased event title.' , 'eventin-pro'),
                ),
            );
            foreach ($tag_list as $key => $value) : ?>
                <div class="etn-template-tags-box">
                    <strong><?php echo esc_html( $value['tag_name'] ); ?></strong> <?php echo esc_html( $value['description'] ); ?>
                </div>
        <?php endforeach; ?>                 
    </div>
</div>

<!-- TODO: Adding Purchase email message -->
<?php
$setting_markup_items = [
	'purchase_email_message' => [
		'item' => [
			'label'    => esc_html__( 'Ticket Purchase Email Body', 'eventin-pro' ),
			'desc'     => esc_html__( 'Body of email that will be sent to user when a they purchase an event.', 'eventin-pro' ),
			'type'     => 'wp_editor',
			'attr'     => [ 'class' => 'attr-form-group etn-label-item' ],
			'settings' => [
				'textarea_name' => 'purchase_email_message',
				'media_buttons' => true,
				'wpautop'       => true
			]
		],
		'data' => [ 'purchase_email_message' => $purchase_email_message ],
	]
];
foreach ( $setting_markup_items as $key => $info ) {
	$this->get_field_markup( $info['item'], $key, $info['data'] );
}
?>

<div class="attr-form-group etn-label-item">
    <div class="etn-label">
        <label for="invoice_include_event_details"><?php esc_html_e( 'Include Event Details in Cart, Checkout & Invoice E-mail', 'eventin-pro' ); ?></label>
        <div class="etn-desc"> <?php esc_html_e( "Include event details page link, starting and ending date-time details in cart, checkout & invoice e-mail.", 'eventin-pro' ); ?> </div>
    </div>
    <div class="etn-meta">
        <input id="invoice_include_event_details"
               type="checkbox" <?php echo esc_html( $invoice_include_event_details ); ?>
               class="etn-admin-control-input"
               name="invoice_include_event_details"/>
        <label for="invoice_include_event_details" class="etn_switch_button_label"></label>
    </div>
</div>
