<?php
/**
 * General functions
 *
 * @package notificaiton
 */

/**
 * Adds handlers for doc hooks to an object
 *
 * @since  5.2.2
 * @param  object $object Object to create the hooks.
 * @return object
 */
function notification_add_doc_hooks( $object ) {
	$dochooks = new BracketSpace\Notification\Utils\DocHooks();
	$dochooks->add_hooks( $object );
	return $object;
}

/**
 * Checks if the story should be displayed.
 *
 * @since  5.2.2
 * @return boolean
 */
function notification_display_story() {

	$counter = wp_count_posts( 'notification' );
	$count   = 0;
	$count  += isset( $counter->publish ) ? $counter->publish : 0;
	$count  += isset( $counter->draft ) ? $counter->draft : 0;

	return ! notification_is_whitelabeled() && ! get_option( 'notification_story_dismissed' ) && $count > 2;

}

/**
 * Creates new View object.
 *
 * @since  [Next]
 * @return View
 */
function notification_create_view() {
	return notification_runtime()->view();
}

/**
 * Creates new AJAX Handler object.
 *
 * @since  [Next]
 * @return BracketSpace\Notification\Utils\Ajax
 */
function notification_ajax_handler() {
	return new BracketSpace\Notification\Utils\Ajax();
}

/**
 * Throws a deprecation notice from deprecated class
 *
 * @since  [Next]
 * @param  string $class       Deprecated class name.
 * @param  string $version     Version since deprecated.
 * @param  string $replacement Replacement class.
 * @return void
 */
function notification_deprecated_class( $class, $version, $replacement = null ) {

	// phpcs:disable
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		if ( function_exists( '__' ) ) {
			if ( ! is_null( $replacement ) ) {
				/* translators: 1: Class name, 2: version number, 3: alternative function name */
				trigger_error( sprintf( __('Class %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.'), $class, $version, $replacement ) );
			} else {
				/* translators: 1: Class name, 2: version number */
				trigger_error( sprintf( __('Class %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.'), $class, $version ) );
			}
		} else {
			if ( ! is_null( $replacement ) ) {
				trigger_error( sprintf( 'Class %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.', $class, $version, $replacement ) );
			} else {
				trigger_error( sprintf( 'Class %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', $class, $version ) );
			}
		}
	}
	// phpcs:enable

}
