<?php
/**
 * Class for option field rendering.
 *
 * @package   Cherry Portfolio
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2015 Cherry Team
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'Cherry_Option_Field' ) ) {
	class Cherry_Option_Field {

		/**
		 * Option Name prefix.
		 *
		 * @var string
		 */
		public $name_prefix	= 'cherry';

		/**
		 * Default field settings array.
		 *
		 * @var array
		 */
		public $default_field_settings = array();

		/**
		 * Cherry Interface builder constructor.
		 *
		 * @since 4.0.0
		 * @param array $args
		 */
		public function __construct( $name_prefix = '' ) {
			$this->default_field_settings = array(
				'title'					=> '',
				'title'					=> '',
				'description'			=> '',
				'label'					=> '',
				'class'					=> '',
				'type'					=> '',
				'value'					=> '',
				'multiple'				=> false,
				'max_value'				=> 100,
				'min_value'				=> 0,
				'step_value'			=> 0,
				'options'				=> array(),
				'placeholder'			=> '',
				'upload_button_text'	=> __( 'Choose Media', 'cherry' ),
				'remove_button_text'	=> __( 'Remove Media', 'cherry' ),
				'multi_upload'			=> true,
				'display_image'			=> true,
				'display_input'			=> true,
				'library_type'			=> '',
				'toggle'				=> array(
					'true_toggle'		=> __( 'On', 'cherry' ),
					'false_toggle'		=> __( 'Off', 'cherry' ),
				),
			);

			$this->name_prefix = ( '' !== $name_prefix ) ? $this->name_prefix : $this->name_prefix ;
		}

		/**
		 * Generating option field.
		 *
		 * @param  string $id             Option id.
		 * @param  array  $field_settings Field settings array.
		 * @return string
		 */
		public function render_option_field( $id = '', $field_settings = array() ) {

			$field_settings = array_merge( $this->default_field_settings, $field_settings );

			$ui_html = $this->render_ui_element( $id, $field_settings );

			$html = '<div class="option-section">';
				$html .= '<div class="option-info-wrapper">';
					$html .= ( '' !== $field_settings['title'] ) ? '<h4 class="option-description">' . $field_settings['title'] . '</h4>' : '' ;
					$html .= ( '' !== $field_settings['description'] ) ? '<span class="option-description">' . $field_settings['description'] . '</span>' : '';
				$html .= '</div>';
				$html .= '<div class="option-uielement-wrapper">';
					//$html .= $ui_html;
				$html .= '</div>';
				$html .= '<div class="clear"></div>';
			$html .= '</div>';

			echo $html;
		}

		/**
		 * Rendering ui element.
		 *
		 * @param  string $id             Option id.
		 * @param  array  $field_settings Field settings array.
		 * @return string ui element html-formated string.
		 */
		public function render_ui_element( $id = '', $field_settings = array() ) {

			extract( $field_settings );

			$id = $this->name_prefix . '-' . $id;
			$name = $this->name_prefix . '[' . $id . ']';
			$ui_html = '';

			switch ( $type ) {
				case 'text':
					$ui_text = new UI_Text(
						array(
							'id'			=> $id,
							'name'			=> $name,
							'value'			=> $value,
							'class'			=> $class,
						)
					);
					$ui_html = $ui_text->render();
				break;
			}

			return $ui_html;
		}
	}
}
