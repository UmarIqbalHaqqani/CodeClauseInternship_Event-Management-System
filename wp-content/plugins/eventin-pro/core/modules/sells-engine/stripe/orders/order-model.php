<?php
/**
 * Stripe Order Model Class
 * 
 * @package Eventin
 */
namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders;

use Etn\Base\Post_Model;

/**
 * Stripe Model
 */
class Order_Model extends Post_Model {
    protected $post_type = 'etn-stripe-order';
}
