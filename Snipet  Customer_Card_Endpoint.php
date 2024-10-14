// Add Woocommerce My Account tab for cards
// 1. Register new customer-cards endpoint (URL) for My Account page
add_action( 'init', 'add_cards_endpoint' );
function add_cards_endpoint() {
    add_rewrite_endpoint( 'customer-cards', EP_ROOT | EP_PAGES );
}
  
// 2. Add new query var
add_filter( 'query_vars', 'cards_query_vars', 0 );  
function cards_query_vars( $vars ) {
    $vars[] = 'customer-cards';
    return $vars;
}
  
// 3. Insert the new endpoint into the My Account menu
add_filter( 'woocommerce_account_menu_items', 'add_new_cards_tab' );
function add_new_cards_tab( $items ) {
    $items['customer-cards'] = 'Cards';
    return $items;
}
  
// 4. Add content to the added tab
add_action( 'woocommerce_account_customer-cards_endpoint', 'cards_tab_content' ); // Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format

function cards_tab_content() {
   echo '<h4><strong>Welcome to your cards page</strong></h4>';
   echo do_shortcode( '[page id="22930"]' ); // Here goes your shortcode if needed
}

// 5. Go to Settings >> Permalinks and re-save permalinks. Otherwise you will end up with 404 Page not found error
