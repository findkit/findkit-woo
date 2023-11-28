<?php


add_filter('findkit_page_meta', 'findkit_woo_page_meta', 10, 2);
function findkit_woo_page_meta($meta, $post) {
	$product = wc_get_product($post->ID);

	if (!$product) {
		return $meta;
	}

	$meta['customFields']['productId'] = [
		'type' => 'number',
		'value' =>  $post->ID,
	];

	$meta['customFields']['productPrice'] = [
		'type' => 'number',
		'value' =>  $product->get_price(),
	];

	$meta['customFields']['productDescription'] = [
		'type' => 'keyword',
		'value' =>  $product->get_short_description(),
	];

	$image_id  = $product->get_image_id();
	$meta['customFields']['productImageURL'] = [
		'type' => 'keyword',
		'value' =>  wp_get_attachment_url($image_id),
	];

    return $meta;
}


add_action('wp_enqueue_scripts', 'findkit_woo_register_script');
function findkit_woo_register_script() {
	wp_register_script('findkit-woo', get_template_directory_uri() . '/findkit-woo/findkit-woo.js', [], '1.0.0', true);
	wp_enqueue_script('findkit-woo');
}

add_filter('script_loader_tag', 'findkit_woo_make_module_script', 10, 2);
function findkit_woo_make_module_script($tag, $handle) {
	if ($handle === 'findkit-woo') {
		return str_replace("type='text/javascript'", "type='module'", $tag);
	}

	return $tag;
}

