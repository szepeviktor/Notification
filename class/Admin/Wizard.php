<?php
/**
 * Wizard class
 *
 * @package notification
 */

namespace BracketSpace\Notification\Admin;

use BracketSpace\Notification\Utils\Files;
use BracketSpace\Notification\Utils\View;

/**
 * Wizard class
 */
class Wizard {

	/**
	 * Files class
	 *
	 * @var object
	 */
	private $files;

	/**
	 * Wizard page hook.
	 *
	 * @var string
	 */
	public $page_hook = 'none';

	/**
	 * Option name for dismissed Wizard.
	 *
	 * @var string
	 */
	protected $dismissed_option = 'notification_wizard_dismissed';

	/**
	 * Wizard constructor
	 *
	 * @param Files $files Files class.
	 */
	public function __construct( Files $files ) {
		$this->files = $files;
	}

	/**
	 * Register Wizard invisible page.
	 *
	 * @action admin_menu 30
	 *
	 * @return void
	 */
	public function register_page() {

		$this->page_hook = add_submenu_page(
			null,
			__( 'Wizard', 'notification' ),
			__( 'Wizard', 'notification' ),
			'manage_options',
			'wizard',
			[ $this, 'wizard_page' ]
		);

	}

	/**
	 * Redirects the user to Wizard screen.
	 *
	 * @action current_screen
	 *
	 * @return void
	 */
	public function maybe_redirect() {

		if ( ! notification_display_wizard() ) {
			return;
		}

		$screen = get_current_screen();

		if ( isset( $screen->post_type ) && 'notification' === $screen->post_type && 'notification_page_wizard' !== $screen->id ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=notification&page=wizard' ) );
			exit;
		}

	}

	/**
	 * Displays the Wizard page.
	 *
	 * @return void
	 */
	public function wizard_page() {

		$view = notification_create_view();
		$view->set_var( 'sections', $this->get_settings() );
		$view->get_view( 'wizard' );

	}

	/**
	 * Gets settings for Wizard page.
	 *
	 * @return array List of settings groups.
	 */
	public function get_settings() {

		return [
			[
				'name'  => __( 'Common Notifications', 'notification' ),
				'items' => [
					[
						'name'        => __( 'Post published', 'notification' ),
						'slug'        => 'post_published_admin',
						'description' => __( 'An email to administrator when post is published', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Administrator', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Post published', 'notification' ),
						'slug'        => 'post_published_subscribers',
						'description' => __( 'An email to all Subscribers when post is published', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Subscribers (role)', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Post pending review', 'notification' ),
						'slug'        => 'post_review',
						'description' => __( 'An email to administrator when post has been sent for review', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Administrator', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Post updated', 'notification' ),
						'slug'        => 'post_updated',
						'description' => __( 'An email to administrator when post is updated', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Administrator', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Welcome email', 'notification' ),
						'slug'        => 'welcome_email',
						'description' => __( 'An email to registered user', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'User', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Comment added', 'notification' ),
						'slug'        => 'comment_added',
						'description' => __( 'An email to post author about comment to his article', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Post author', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Comment reply', 'notification' ),
						'slug'        => 'comment_reply',
						'description' => __( 'An email to comment autor about the reply', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Comment author', 'notification' ),
							],
						],
					],
				],
			],
			[
				'name'  => __( 'WordPress emails', 'notification' ),
				'items' => [
					[
						'name'        => __( 'New user', 'notification' ),
						'slug'        => 'new_user',
						'description' => __( 'An email to administrator when new user is created', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Administrator', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Your account', 'notification' ),
						'slug'        => 'your_account',
						'description' => __( 'An email to registered user, with password reset link', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'User', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Password reset request', 'notification' ),
						'slug'        => 'password_forgotten',
						'description' => __( 'An email to user when password reset has been requested', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'User', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Password reset', 'notification' ),
						'slug'        => 'password_reset',
						'description' => __( 'An email with info that password has been reset', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'User', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Comment awaiting moderation', 'notification' ),
						'slug'        => 'comment_moderation',
						'description' => __( 'An email to administrator and post author that comment is awaiting moderation', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Administrator', 'notification' ),
							],
							[
								'name' => __( 'Post author', 'notification' ),
							],
						],
					],
					[
						'name'        => __( 'Comment has been published', 'notification' ),
						'slug'        => 'comment_published',
						'description' => __( 'An email to post author that comment has been published', 'notification' ),
						'recipients'  => [
							[
								'name' => __( 'Post author', 'notification' ),
							],
						],
					],
				],
			],
		];

	}

	/**
	 * Saves Wizard settings.
	 *
	 * @action admin_post_save_notification_wizard
	 *
	 * @return void
	 */
	public function save_settings() {

		$data = $_POST; // phpcs:ignore

		if ( wp_verify_nonce( $data['_wpnonce'], 'notification_wizard' ) === false ) {
			wp_die( 'Can\'t touch this' );
		}

		if ( ! isset( $data['skip-wizard'] ) ) {

			$notifications = isset( $data['notification_wizard'] ) ? $data['notification_wizard'] : [];
			$this->add_notifications( $notifications );

		}

		$this->save_option_to_dismiss_wizard();

		wp_safe_redirect( admin_url( 'edit.php?post_type=notification' ) );
		exit;

	}

	/**
	 * Adds predefined notifications.
	 *
	 * @action admin_post_save_notification_wizard
	 *
	 * @param  array $notifications List of notifications template slugs.
	 * @return void
	 */
	private function add_notifications( $notifications ) {

		$dir_path = $this->files->plugin_path() . 'inc/wizard/';

		foreach ( $notifications as $notify_slug ) {

			$json_path = $dir_path . $notify_slug . '.json';
			if ( ! is_readable( $json_path ) ) {
				continue;
			}

			$json         = file_get_contents( $json_path ); // phpcs:ignore
			$json_adapter = notification_adapt_from( 'JSON', $json );
			$wp_adapter   = notification_swap_adapter( 'WordPress', $json_adapter );
			$wp_adapter->save();

		}

	}

	/**
	 * Saves option to dismiss auto-redirect to Wizard page.
	 *
	 * @return void
	 */
	private function save_option_to_dismiss_wizard() {

		if ( get_option( $this->dismissed_option ) !== false ) {
			update_option( $this->dismissed_option, true );
		} else {
			add_option( $this->dismissed_option, true, '', 'no' );
		}

	}

}
