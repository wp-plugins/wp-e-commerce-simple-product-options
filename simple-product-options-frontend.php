<?php



class wpec_simple_product_options_frontend {



	function __construct() {

		add_action ( 'wpsc_product_form_fields', array ( &$this, 'display_product_options' ) );
		add_filter ( 'wpsc_add_to_cart_product_id', array ( &$this, 'parse_product_options' ) );

	}



	function parse_product_options( $product_id ) {

		if ( isset ( $_POST['wpec-product-option'] ) && count ( $_POST['wpec-product-option'] ) ) {

			// Flag the product as personalisable
			$_POST['is_customisable'] = 'true';

			// Construct the options into a string
			$cnt = 0;

			foreach ( $_POST['wpec-product-option'] as $parent_term => $term ) {

				$parent = get_term_by ( 'id', $parent_term, 'wpec_product_option' );
				$child = get_term_by ( 'id', $term, 'wpec_product_option' );

				if ( $cnt )
					$custom_text .= '; ';

				$custom_text .= $parent->name . ': ' . $child->name;

				$cnt++;
			}

			$_POST['custom_text'] = $custom_text;

		}

		return $product_id;
	}



	function display_product_options() {

		// Retrieve the product options for this product
		$product_id = wpsc_the_product_id();
		
		$options = wp_get_object_terms ( $product_id, 'wpec_product_option', array ( 'orderby' => 'parent', 'order' => 'asc' ) );
		
		// Re-arrange to an array structure suitable for output
		foreach ($options as $option) {

			if ( $option->parent == 0 )
				continue;

			// Add to array
			if ( isset ( $output_array[$option->parent] ) ) {

				// Already have the parent info - just add the child
				$output_array[$option->parent]['options'][] = $option; 

			} else {

				// Grab the parent info AND add this as a child
				$parent = get_term_by ( 'id', $option->parent, 'wpec_product_option' );

				// No sensible way to structure multi-level options
				if ( $parent->parent != 0 )
					continue;

				$output_array[$parent->term_id] = Array ( 'option_set_info' => $parent,
				                                          'options' => Array ( $option )
														  );
			}

		}

		foreach ( $output_array as $option_set ) {

			if ( ! empty ( $option_set['options'] ) ) {

				$option_set_info = &$option_set['option_set_info'];

				echo "<span class=\"wpec-product-option-title\">".esc_html($option_set_info->name).": </span>";
				echo "<select class=\"wpec-product-option-select\" name=\"wpec-product-option[".esc_attr($option_set_info->term_id)."]\">";

				foreach ( $option_set['options'] as $option ) {

					echo "<option value=\"".esc_attr($option->term_id)."\">".esc_html($option->name)."</option>";

				}

				echo "</select><br/>";
			}
		}

	}


}

$wpec_simple_product_options_frontend = new wpec_simple_product_options_frontend();

?>
