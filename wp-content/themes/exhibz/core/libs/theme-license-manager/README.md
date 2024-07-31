# Theme License Manager

### Include File
Include the file `/theme-license-manager/theme-license-manager.php'`

### Initialize
```
$store_url = 'https://edd-store-url.com';
$product_id = '183';

\Theme\License\Theme_License_Manager::instance()->run( $store_url, $product_id );
```
