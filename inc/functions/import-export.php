<?php
/**
 * Import/Export functions
 *
 * @package notificaiton
 */

/**
 * Adds extra data to the notification JSON export
 *
 * @since  [Next]
 * @param  string   $key      String for export.
 * @param  callable $exporter Callable exporter.
 * @return void
 */
function notification_export_extra( $key, callable $exporter ) {

	// @todo Extras API #h1k0k.
	add_filter( 'notification/post/export/extras', function( $extras, $notification ) use ( $key, $exporter ) {

		if ( ! isset( $extras[ $key ] ) ) {
			$extras[ $key ] = call_user_func( $exporter, $notification );
		}

		return $extras;

	}, 10, 2 );

}

/**
 * Processes extra data for the notification JSON import
 *
 * @since  [Next]
 * @param  string   $key      String for export.
 * @param  callable $importer Callable importer.
 * @return void
 */
function notification_import_extra( $key, callable $importer ) {
	// @todo Extras API #h1k0k.
	add_action( 'notification/post/import/extras/' . $key, function( $data, $notification ) use ( $importer ) {
		call_user_func( $importer, $data, $notification );
	}, 10, 2 );
}

/**
 * Enables the notification syncing
 * By default path used is current theme's `notifiations` dir.
 *
 * @since  [Next]
 * @throws \Exception If provided path is not a directory.
 * @param  mixed $path full json directory path or null to use default.
 * @return void
 */
function notification_sync( $path = null ) {

	if ( ! $path ) {
		$path = trailingslashit( get_stylesheet_directory() ) . 'notifications';
	}

	if ( ! file_exists( $path ) ) {
		mkdir( $path );
	}

	if ( ! is_dir( $path ) ) {
		throw new \Exception( 'Synchronization path must be a directory.' );
	}

	if ( ! file_exists( trailingslashit( $path ) . 'index.php' ) ) {
		file_put_contents( trailingslashit( $path ) . 'index.php', '<?php' . "\r\n" . '// Keep this file here.' . "\r\n" ); // phpcs:ignore
	}

	add_filter( 'notification/sync/dir', function( $dir ) use ( $path ) {
		return $path;
	} );

}

/**
 * Gets the synchronization path.
 *
 * @since [Next]
 * @return mixed Path or false.
 */
function notification_get_sync_path() {
	return apply_filters( 'notification/sync/dir', false );
}

/**
 * Checks if synchronization is active.
 *
 * @since [Next]
 * @return boolean
 */
function notification_is_syncing() {
	return (bool) notification_get_sync_path();
}

