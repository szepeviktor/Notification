<?php
/**
 * Comment content merge tag
 *
 * @package notification
 */

namespace underDEV\Notification\Defaults\MergeTag\Comment;

use underDEV\Notification\Defaults\MergeTag\StringTag;


/**
 * Comment content merge tag class
 */
class CommentContent extends StringTag {

	/**
     * Merge tag constructor
     *
     * @since [Next]
     * @param array $params merge tag configuration params.
     */
    public function __construct( $params = array() ) {

		$args = wp_parse_args( $params, array(
			'slug'        => 'comment_content',
			'name'        => __( 'Comment content' ),
			'description' => __( 'Great post!' ),
			'example'     => true,
			'resolver'    => function() {
				return $this->trigger->comment->comment_content;
			},
		) );

    	parent::__construct( $args );

	}

	/**
	 * Function for checking requirements
	 *
	 * @return boolean
	 */
	public function check_requirements( ) {
		return isset( $this->trigger->comment->comment_content );
	}

}
