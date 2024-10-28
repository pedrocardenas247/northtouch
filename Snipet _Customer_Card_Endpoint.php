// 1. Register new endpoint for cards
add_action('init', 'add_cards_endpoint');
function add_cards_endpoint() {
    add_rewrite_endpoint('cards', EP_ROOT | EP_PAGES);
}

// 2. Add new query var
add_filter('query_vars', 'cards_query_vars', 0);
function cards_query_vars($vars) {
    $vars[] = 'cards';
    return $vars;
}

// 3. Reorganize and filter My Account menu items
add_filter('woocommerce_account_menu_items', 'reorganize_my_account_menu', 999);
function reorganize_my_account_menu($menu_items) {
    // Define new menu structure
    $new_menu_items = array(
        'cards'           => __('Cards', 'woocommerce'),
        'share'          => __('Share', 'woocommerce'),
        'contacts'       => __('Contacts', 'woocommerce'),
        'analytics'      => __('Analytics', 'woocommerce'),
        'upgrade-account' => __('Upgrade Account', 'woocommerce'),
        'customer-logout' => __('Logout', 'woocommerce')
    );
    
    return $new_menu_items;
}

// 4. Add content to the cards tab (mantiene el contenido existente)
add_action('woocommerce_account_cards_endpoint', 'cards_tab_content');
function cards_tab_content() {
    echo '<h4><strong>Welcome to your cards page</strong></h4>';
    echo do_shortcode('[page id="22930"]');
}

// 5. Register all new endpoints
add_action('init', 'register_new_tabs_endpoints');
function register_new_tabs_endpoints() {
    $endpoints = ['share', 'contacts', 'analytics', 'upgrade-account'];
    
    foreach($endpoints as $endpoint) {
        add_rewrite_endpoint($endpoint, EP_ROOT | EP_PAGES);
    }
}

// 6. Add new endpoints to query vars
add_filter('query_vars', 'add_new_tabs_query_vars', 0);
function add_new_tabs_query_vars($vars) {
    $new_vars = ['share', 'contacts', 'analytics', 'upgrade-account'];
    return array_merge($vars, $new_vars);
}

// 7. Conectar con el contenido del tab Share
add_action('woocommerce_account_share_endpoint', 'custom_share_tab_content');
function custom_share_tab_content() {
    $share_tab_path = get_stylesheet_directory() . '/inc/my-account/share-tab.php';
    
    // Debug
    echo "<!-- Debug: Intentando cargar archivo desde: " . $share_tab_path . " -->";
    
    if (file_exists($share_tab_path)) {
        echo "<!-- Debug: Archivo encontrado, cargando contenido -->";
        require_once $share_tab_path;
    } else {
        echo "<!-- Debug: Archivo no encontrado en la ruta especificada -->";
        echo "Share tab file is missing. Please check the path.";
    }
}
