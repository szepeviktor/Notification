<?php
/**
 * Repeater API class
 *
 * @package notification
 */

namespace BracketSpace\Notification\Api;

/**
 * RepeaterAPI class
 *
 * @action
 */
class RepeaterAPI {

	/**
	 * Init rest api endpoint
	 *
	 * @action rest_api_init
	 * @return void
	 */
	public function rest_api_init() {

		register_rest_route( 'notification/v2', '/repeater-field/(?P<id>\d+)', [
			'methods'  => 'POST',
			'callback' => [ $this, 'send_response' ],
		]);
	}

	/**
	 * Form field data
	 *
	 * @since [Next]
	 * @param array $data Field data.
	 * @return array
	 */
	public function form_field_data( $data ) {

		$fields = [];

		foreach ( $data as $field ) {
			$sub_field = [];

			$sub_field['options']        = $field->options;
			$sub_field['pretty']         = $field->pretty;
			$sub_field['label']          = $field->label;
			$sub_field['checkbox_label'] = $field->checkbox_label;
			$sub_field['name']           = $field->name;
			$sub_field['description']    = $field->description;
			$sub_field['section']        = $field->section;
			$sub_field['disabled']       = $field->disabled;
			$sub_field['css_class']      = $field->css_class;
			$sub_field['id']             = $field->id;
			$sub_field['type']           = strtolower( str_replace( 'Field', '', $field->field_type_html ) );

			array_push( $fields, $sub_field );
		}

		return $fields;

	}

	/**
	 * Get field values
	 *
	 * @since [Next]
	 * @param int    $post_id Post id.
	 * @param string $carrier Carrier slug.
	 * @param string $field Field slug.
	 * @return array
	 */
	public function get_values( $post_id, $carrier, $field ) {
		$notification = notification_adapt_from( 'WordPress', $post_id );

		$carrier = $notification->get_carrier( $carrier );

		if ( $carrier ) {
			return $carrier->get_field_value( $field );
		}

		return [];
	}

	/**
	 * Send response
	 *
	 * @since [Next]
	 * @param \WP_REST_Request $request WP request instance.
	 * @return void
	 */
	public function send_response( \WP_REST_Request $request ) {

		$params  = $request->get_params();
		$post_id = intval( $params['id'] );
		$carrier = $params['fieldCarrier'];
		$field   = $params['fieldType'];

		$values = $this->get_values( $post_id, $carrier, $field );

		$carriers = notification_get_carriers();

		$field = $carriers[ $carrier ]->get_form_field( $field );

		$field = $this->form_field_data( $field->fields );

		$data = [
			'field'  => $field,
			'values' => $values,
		];

		$data = wp_json_encode( $data );

		wp_send_json( $data );
	}

}
