<?php
/**
 * Attachment direct URL merge tag
 *
 * @package notification
 */

namespace BracketSpace\Notification\Defaults\MergeTag\Media;

use BracketSpace\Notification\Defaults\MergeTag\UrlTag;


/**
 * Attachment direct URL merge tag class
 */
class AttachmentDirectUrl extends UrlTag {

	/**
	 * Trigger property to get the attachment data from
	 *
	 * @var string
	 */
	protected $property_name = 'attachment';

	/**
	 * Merge tag constructor
	 *
	 * @since 5.0.0
	 * @param array $params merge tag configuration params.
	 */
	public function __construct( $params = array() ) {

		if ( isset( $params['property_name'] ) && ! empty( $params['property_name'] ) ) {
			$this->property_name = $params['property_name'];
		}

		$args = wp_parse_args(
			$params,
			array(
				'slug'        => 'attachment_direct_url',
				'name'        => __( 'Attachment direct URL', 'notification' ),
				'description' => __( 'http://example.com/wp-content/uploads/2018/02/forest-landscape.jpg', 'notification' ),
				'example'     => true,
				'resolver'    => function() {
					return wp_get_attachment_url( $this->trigger->{ $this->property_name }->ID );
				},
				'group'       => __( 'Attachment', 'notification' ),
			)
		);

		parent::__construct( $args );

	}

	/**
	 * Function for checking requirements
	 *
	 * @return boolean
	 */
	public function check_requirements() {
		return isset( $this->trigger->{ $this->property_name }->ID );
	}

}
