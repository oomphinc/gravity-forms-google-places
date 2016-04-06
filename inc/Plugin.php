<?php

namespace OomphInc\GFormsGooglePlaces;

/**
 * Extensions for Gravity Forms
 */
class Plugin {

	/**
	 * Hook some stuffs!
	 */
	static function init() {
		add_filter( 'gform_add_field_buttons', [ __CLASS__, 'add_field_buttons' ] );
		add_filter( 'gform_field_type_title', [ __CLASS__, 'field_title' ] );
		add_filter( 'gform_field_input', [ __CLASS__, 'field' ], 10, 5 );
		add_action( 'gform_field_advanced_settings', [ __CLASS__, 'advanced_settings' ], 10, 2 );
		add_filter( 'gform_tooltips', [ __CLASS__, 'add_tooltips' ] );
		add_action( 'gform_editor_js_set_default_values', [ __CLASS__, 'field_edit_defaults' ] );
		add_action( 'gform_editor_js', [ __CLASS__, 'editor_js' ] );
		add_action( 'gform_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ], 10, 2 );
		add_filter( 'gform_field_content', [ __CLASS__, 'modify_fields' ], 10, 5 );
	}

	/**
	 * Add a new button to the field types add panel
	 * @param  array $field_groups array of field groups which each contain buttons for fields
	 * @gform_add_field_buttons
	 */
	static function add_field_buttons( $field_groups ) {
		foreach ( $field_groups as &$group ) {
			if ( $group['name'] === 'advanced_fields' ) {
				$group['fields'][] = [
					'class' => 'button',
					'data-type' => 'places-api',
					'value' => 'Google Places',
				];
			}
		}
		return $field_groups;
	}

	/**
	 * Filter the title for the field as it appears in the editor
	 * @param  string $type field type
	 * @filter gform_field_type_title
	 */
	static function field_title( $type ) {
		if ( $type === 'places-api' ) {
			return 'Google Places Lookup';
		}
	}

	/**
	 * Filter the HTML markup for a particular field
	 * @param  string $input   original markup (at this point, always an empty string)
	 * @param  object $field   field object
	 * @param  string $value   default value
	 * @param  int    $lead_id lead id
	 * @param  int    $form_id form id
	 * @filter gform_field_input
	 */
	static function field( $input, $field, $value, $lead_id, $form_id ) {
		$html_id = 'input_' . ( is_admin() || !$form_id ? '' : $form_id . '_' ) . $field->id;
		if ( $field->type === 'places-api' ) {
			return '<div class="ginput_container"><input type="text" class="medium geo-complete" name="input_' . esc_attr( $field->id ) . '" id="' . esc_attr( $html_id ) . '" value="' . esc_attr( $value ) . '" ' .  disabled( is_admin(), true, false ) . '></div>';
		}
		return $input;
	}

	/**
	 * @action gform_field_advanced_settings
	 */
	static function advanced_settings( $position, $form_id ) {
		if ( $position == 50 ) {
		?>
			<li class="geo_field_setting field_setting">
				<label for="geo_field">
				Populate with Places Field
				<?php gform_tooltip( 'geo_field' ); ?>
				</label>
				<input type="text" id="geo_field" onkeyup="SetFieldProperty('geoField', this.value);">
			</li>
		<?php
		}
	}

	/**
	 * @filter gform_tooltips
	 */
	static function add_tooltips( $tooltips ) {
		$tooltips['geo_field'] = '<h6>Places Field</h6>Enter a field that would be returned from a Google Places result to be populated upon selection of a result.';
		return $tooltips;
	}

	/**
	 * Fill in defaults for a new field
	 * @action gform_editor_js_set_default_values
	 */
	static function field_edit_defaults() {
	?>
		case 'places-api':
			field.label = 'Location';
		break;
	<?php
	}

	/**
	 * Additional JS to be included after gravity forms JS
	 * @action gform_editor_js
	 */
	static function editor_js() {
	?>
		<script type="text/javascript">
			//defining settings for the new custom field
			fieldSettings['places-api'] = '.conditional_logic_field_setting, .error_message_setting, .label_setting, .label_placement_setting, .rules_setting, .admin_label_setting, .size_setting, .visibility_setting, .duplicate_setting, .placeholder_setting, .description_setting, .css_class_setting';
			fieldSettings['text'] += ', .geo_field_setting';
			fieldSettings['hidden'] += ', .geo_field_setting';

			//binding to the load field settings event to initialize the checkbox
			jQuery(document).bind('gform_load_field_settings', function(event, field, form){
				jQuery("#geo_field").val(field['geoField']);
			});
		</script>
	<?php
	}

	/**
	 * Enqueue scripts needed for the geo complete field
	 * @param  array $form form properties
	 * @param  bool $ajax ajax form or not
	 * @action gform_enqueue_scripts
	 */
	static function enqueue_scripts( $form, $ajax ) {
		foreach ( $form['fields'] as $field ) {
			if ( $field->type === 'places-api' ) {
				wp_enqueue_script( 'google-places', 'https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places', [ 'jquery' ] );
				wp_enqueue_script( 'jquery-geocomplete', PLUGINS_URL . '/assets/jquery.geocomplete.min.js', [ 'jquery', 'google-places' ] );
				wp_enqueue_script( 'gforms-google-places', PLUGINS_URL . '/assets/gforms-google-places.js', [ 'jquery', 'jquery-geocomplete' ] );
				break;
			}
		}
	}

	/**
	 * Modify the field markup
	 * @filter gform_field_content
	 */
	static function modify_fields( $content, $field, $value, $lead_id, $form_id ) {
		if ( !empty( $field->geoField ) ) {
			$content = str_replace( 'type=', 'data-geo="' . esc_attr( $field->geoField ) . '" type=', $content );
		}
		return $content;
	}

}
