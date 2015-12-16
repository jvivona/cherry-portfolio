<?php
/**
 * Class for option page rendering.
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

if ( ! class_exists( 'Cherry_Option_Page' ) ) {



	class Cherry_Option_Page {

		/**
		 * Default settings.
		 *
		 * @var array
		 */
		public static $default_settings = array();

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Cherry Interface builder constructor.
		 *
		 * @since 4.0.0
		 * @param array $args
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'admin_head', array( $this, 'admin_head' ) );

			cherry_page_builder()->add_parent_menu_item( array(
					'page_title'	=> sprintf( __( 'Cherry Plugins', 'cherry-portfolio' ), wp_get_theme()->get( 'Name' ) ),
					'menu_title'	=> __( 'Plugins', 'cherry-portfolio' ),
					'capability'	=> 'edit_theme_options',
					'menu_slug'		=> 'plugins',
					'function'		=> array( __CLASS__, 'portfolio_options_page_build' ),
					'icon_url'		=> CHERRY_PORTFOLIO_URI . 'admin/assets/images/svg/cherry-icon.png',
					'position'		=> 63,
				)
			);

			cherry_page_builder()->add_child_menu_item( array(
					'parent_slug'	=> 'plugins',
					'page_title'	=> __( 'Portfolio options', 'cherry-portfolio' ),
					'menu_title'	=> __( 'Portfolio options', 'cherry-portfolio' ),
					'capability'	=> 'edit_theme_options',
					'menu_slug'		=> 'portfolio_options',
					'function'		=> array( __CLASS__, 'portfolio_options_page_build' ),
				)
			);

			self::$default_settings = cherry_portfolio_options_class()->portfolio_options;
		}

		/**
		 *
		 * @since 4.0.0
		 */
		public static function portfolio_options_page_build() {

			$html = '<form id="cherry-portfolio-options-form" method="post">';
				$html .= '<div class="cherry-portfolio-options-page-wrapper">';

				foreach ( self::$default_settings as $option_key => $option_setting ) {

					$id = $option_key;
					$field_settings = $option_setting;

					$option_field = new Cherry_Option_Field();
					$html .= $option_field->render_option_field( $id, $field_settings );

				}

				$html .= '</div>';
			$html .= '</form>';

			echo $html;
		}

		/**
		 * Enqueue admin scripts function.
		 *
		 * @return void
		 */
		public function enqueue_scripts( $hook_suffix ) {
			$screen = get_current_screen();

		}

		/**
		 * Enqueue admin styles function.
		 *
		 * @return void
		 */
		public function enqueue_styles( $hook_suffix ) {
			$screen = get_current_screen();

			if ( is_admin() && 'plugins_page_portfolio_options' == $hook_suffix ) {
				wp_enqueue_style( 'options-page-style', trailingslashit( CHERRY_PORTFOLIO_URI ) . 'admin/assets/css/options-page-style.css', array(), CHERRY_PORTFOLIO_VERSION, 'all' );
			}
		}

		/**
		 * Delete sub menu item
		 *
		 * @since 4.0.0
		 */
		function admin_head(){
			global $submenu;

			unset( $submenu['plugins'][0] );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

	/**
	 * Define main function to get plugin instance
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function cherry_option_page_class() {
		return Cherry_Option_Page::get_instance();
	}

	Cherry_Option_Page::get_instance();
}
