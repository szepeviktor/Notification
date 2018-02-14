<?php
/**
 * Attachment direct URL merge tag
 *
 * @package notification
 */

namespace underDEV\Notification\Defaults\MergeTag\Media;

use underDEV\Notification\Defaults\MergeTag\UrlTag;


/**
 * Attachment direct URL merge tag class
 */
class AttachmentDirectUrl extends UrlTag {

	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct( array(
			'slug'        => 'attachment_direct_url',
			'name'        => __( 'Attachment direct URL' ),
			'description' => __( 'Will be resolved to an attachment direct URL' ),
			'resolver'    => function() {
				return $this->trigger->attachment->guid;
			},
		) );

	}

	/**
	 * Function for checking requirements
	 *
	 * @return boolean
	 */
	public function check_requirements( ) {
		return isset( $this->trigger->attachment->guid );
	}

}