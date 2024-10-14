// Reorder Woocommerce my account tabs?
function change_my_account_menu_order() {
 	$menuOrder = array(
	'customer-cards'       => __( 'Cards', 'woocommerce' ),
	'dashboard'          => __( 'Dashboard', 'woocommerce' ),
 	'orders'             => __( 'Orders', 'woocommerce' ),
 	'purchased-courses'   => __( 'Your courses', 'woocommerce' ),
	'customer-support'   => __( 'Support', 'woocommerce' ),
        'downloads'   => __( 'Downloads', 'woocommerce' ),
 	'edit-address'       => __( 'My ddresses', 'woocommerce' ),
 	'edit-account'    	=> __( 'Account Details', 'woocommerce' ),
 	'customer-logout'    => __( 'Logout', 'woocommerce' )
 	);
 	return $menuOrder;
 }
 add_filter ( 'woocommerce_account_menu_items', 'change_my_account_menu_order' );
