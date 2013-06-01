<?php
/**
 * Templating. Lifted from bbpress... kind of
 * @since 0.3.8
 * @package IT_Exchange
*/

/**
 * Retrieves a template part
 *
 * @since 0.3.8
 * @param string $slug
 * @param string $name Optional. Default null
 * @return mixed template
 */

function it_exchange_get_template_part( $slug, $name=null, $load=true ) {
    // Execute code for this part
    do_action( 'get_template_part_' . $slug, $slug, $name );

    // Setup possible parts
    $templates = array();
    if ( isset( $name ) )
        $templates[] = $slug . '-' . $name . '.php';
    $templates[] = $slug . '.php';

    // Return the part that is found
    return it_exchange_locate_template( $templates, $load, false, true );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the lib/templates folder last.
 *
 * Taken from bbPress
 *
 * @since 0.3.8
 * @param mixed $template_names Template file(s) to search for, in order.
 * @param boolean $load If true the template file will be loaded if it is found.
 * @param boolean $require_once Whether to require_once or require. Default true.
 * @return string The template filename if one is located.
 */
function it_exchange_locate_template( $template_names, $load=false, $require_once=true, $template_part=false ) {
    // No file found yet
    $located = false;

	// If template_names is product, add core single-it_exchange_prod to array
	$template_names = (array) $template_names;
	if ( in_array( 'product.php', $template_names ) && ! in_array( 'single-it_exchange_prod.php', $template_names ) )
		$template_names[] = 'single-it_exchange_prod.php';

	// Add exchange to list of template names
	if ( ! in_array( 'exchange.php', $template_names ) )
		$template_names[] = 'exchange.php';

	// Define possible template paths
	$possible_template_paths = array( 
		// Exchange directory
		trailingslashit( get_stylesheet_directory() ) . 'exchange',
		trailingslashit( get_template_directory() ) . 'exchange',

		// Theme directories
		untrailingslashit( get_stylesheet_directory() ),
		untrailingslashit( get_template_directory() ),
	);
	
	// Allow addons to add a template path for template parts
	if ( $template_part )
		$possible_template_paths = apply_filters( 'it_exchange_possible_template_paths', $possible_template_paths, $template_names );

	// If looking for a template part, add core iThemes Exchange template folder. Also, force to be last in array
	if ( $template_part ) {
		$core_template_path = dirname( dirname( __FILE__ ) ) . '/templates/';
		if ( $key = array_search( $core_template_path, $possible_template_paths ) )
			unset( $possible_template_paths[$key] );
		if ( $key = array_search( untrailingslashit( $core_template_path ), $possible_template_paths ) )
			unset( $possible_template_paths[$key] );
		$possible_template_paths[] = $core_template_path;
	}

	// Make sure we don't have multiple elements for the same path
	$possible_template_paths = array_unique( $possible_template_paths );

    // Try to find a template file
    foreach ( (array) $template_names as $template_name ) { 

        // Continue if template is empty
        if ( empty( $template_name ) ) 
            continue;

        // Trim off any slashes from the template name
        $template_name = ltrim( $template_name, '/' );

		// Loop through possible paths and use first one that is located
		foreach( $possible_template_paths as $path ) {

			// Don't look for single-it_exchange_prod inside /exchange folder
			if ( '/exchange/' == substr( trailingslashit( $path ), -10 ) && in_array( $template_name, array( 'single-it_exchange_prod.php', 'exchange.php' ) ) )
				continue;
			
			// If file doesn't exist, keep looking
			if ( ! is_file( trailingslashit( $path ) . $template_name ) )
				continue;

			// If we made it here, the file was found
			$located = trailingslashit( $path ) . $template_name;
			break 2;
		}
    }   

    if ( ( true == $load ) && ! empty( $located ) ) {
        load_template( $located, $require_once );
		it_exchange_unset_template_part_args( rtrim( $template_name, '.php' ) );
	}

    return $located;
}

/**
 * Sets some variables for use in template parts
 *
 * Stores them in globals, keyed by the template part slug / name
 *
 * @since 0.3.8
 * @param array $args args for the template part.
 * @param string $slug template part slug
 * @param string $name optional name of template part
 * @return void
*/
function it_exchange_set_template_part_args( $args, $slug, $name=false ) {

	// Set the slug
	$key = empty( $name ) ? $slug : $slug . '-' . $name;

	// Store the options
	$GLOBALS['it_exchange']['template_part_args'][$key] = $args;
}

/**
 * Retrieves args for template parts
 *
 * @since 0.3.8
 * @param $template_part key for the template part. File name without .php
 * @return mixed
*/
function it_exchange_get_template_part_args( $template_part ) {
	$args = empty( $GLOBALS['it_exchange']['template_part_args'][$template_part] ) ? false : $GLOBALS['it_exchange']['template_part_args'][$template_part] ;

	return apply_filters( 'it_exchange_template_part_args_' . $template_part, $args );
}

/**
 * This unsets the template part args for a specific template
 *
 * @since 0.3.8
 * @param string $template_name name of template part
 * @return void
*/
function it_exchange_unset_template_part_args( $template_name ) {
	if ( isset( $GLOBALS['it_exchange']['template_part_args'][$template_name] ) )
		unset( $GLOBALS['it_exchange']['template_part_args'][$template_name] );
}
