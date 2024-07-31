<?php
defined( 'ABSPATH' ) || exit;

use \Etn\Utils\Helper;

if ( !empty( $objective ) ) {
    ?>		
    <p><?php echo esc_html(trim( $objective )); ?></p>
    <?php
}