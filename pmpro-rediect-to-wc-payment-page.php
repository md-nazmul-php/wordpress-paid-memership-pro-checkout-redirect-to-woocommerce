<?php   
add_action('template_redirect','pmpro_woocommerce_redirect_checkout');
function pmpro_woocommerce_redirect_checkout(){
  $id = get_option('pmpro_checkout_page_id');
  global $post;
  if($post->ID == $id && !empty($_REQUEST['level'])){
    global $wpdb;
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT pm.post_id FROM {$wpdb->postmeta} as pm LEFT JOIN {$wpdb->posts} as p ON p.ID=pm.post_id WHERE pm.meta_key = %s AND pm.meta_value = %d AND p.post_status='publish'",'_membership_product_level',$_REQUEST['level']));
    if(!empty($product_id)){
        $checkout_url = WC()->cart->get_checkout_url();
        if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
          foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
            $_product = $values['data'];
            if ( $_product->id == $product_id )
                $found = true;
          }
          // if product not found, add it
          if ( ! $found )
            WC()->cart->add_to_cart( $product_id );
          wp_redirect( $checkout_url);  
        }else{
          // if no products in cart, add it
          WC()->cart->add_to_cart( $product_id );
          wp_redirect( $checkout_url);  
        }
        exit();
    }
    
  }
}

 ?>