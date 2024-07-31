# Etn License Manager

### Include File
Include the file `/etn-license-manager/etn-license-manager.php'`

### Initialize
```
$store_url = 'https://edd-store-url.com';
$product_id = '183';

\Etn\License\Missile\Etn_License_Manager::instance()->run( $store_url, $product_id );
```
