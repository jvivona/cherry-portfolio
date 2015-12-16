<?php
/**
 * Plugin Name: Cherry Portfolio
 * Plugin URI:  http://www.cherryframework.com/
 * Description: A portfolio plugin for WordPress.
 * Version:     1.0.5
 * Author:      Cherry Team
 * Author URI:  http://www.cherryframework.com/
 * Text Domain: cherry-portfolio
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 *
 * @package   Cherry Portfolio
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2014 Cherry Team
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If class 'Cherry_Portfolio' not exists.
if ( ! class_exists( 'Cherry_Portfolio' ) ) {

	/**
	 * Sets up and initializes the Cherry Portfolio plugin.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Portfolio {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Set the constants needed by the plugin.
			$this->constants();

			// Load the functions files.
			$this->includes();

			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 2 );

			// Load the admin files.
			add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );

			// Load the extensions files.
			add_action( 'after_setup_theme', array( $this, 'extensions' ), 11 );

			// Load public-facing style sheet.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_filter( 'cherry_compiler_static_css', array( $this, 'add_style_to_compiler' ) );

			// Load public-facing JavaScript.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Defines constants for the plugin.
		 *
		 * @since 1.0.0
		 */
		function constants() {

			/**
			 * Set constant name for the post type name.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_NAME', 'portfolio' );

			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_VERSION', '1.0.5' );

			/**
			 * Set the slug of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_SLUG', basename( dirname( __FILE__ ) ) );

			/**
			 * Set the name for the 'meta_key' value in the 'wp_postmeta' table.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_POSTMETA', '_cherry_portfolio' );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_PORTFOLIO_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}

		/**
		 * Loads files from the '/inc' folder.
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/class-cherry-portfolio-registration.php' );
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/class-cherry-portfolio-page-template.php' );
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/aq-resizer.php' );
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/class-cherry-portfolio-options.php' );
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/class-cherry-portfolio-data.php' );
			require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/includes/classes/class-cherry-portfolio-shortcode.php' );
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 */
		function lang() {
			load_plugin_textdomain( 'cherry-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 */
		function admin() {

			if ( is_admin() ) {
				require_once( CHERRY_PORTFOLIO_DIR . 'admin/includes/class-cherry-page-builder.php' );
				require_once( CHERRY_PORTFOLIO_DIR . 'admin/includes/class-cherry-options-page.php' );
				require_once( CHERRY_PORTFOLIO_DIR . 'admin/includes/class-cherry-option-field.php' );
				require_once( CHERRY_PORTFOLIO_DIR . 'admin/includes/class-cherry-portfolio-admin.php' );
				require_once( CHERRY_PORTFOLIO_DIR . 'admin/includes/class-cherry-update/class-cherry-plugin-update.php' );

				$Cherry_Plugin_Update = new Cherry_Plugin_Update();
				$Cherry_Plugin_Update -> init( array(
						'version'			=> CHERRY_PORTFOLIO_VERSION,
						'slug'				=> CHERRY_PORTFOLIO_SLUG,
						'repository_name'	=> CHERRY_PORTFOLIO_SLUG,
				));
			}
		}

		/**
		 * Return true if CherryFramework active.
		 *
		 * @return boolean
		 */
		public function is_cherry_framework(){

			if ( class_exists( 'Cherry_Framework' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Loads extensions files.
		 *
		 * @since 1.0.4.2
		 */
		public function extensions() {

			if ( function_exists( 'icl_object_id' ) ) {
				require_once( trailingslashit( CHERRY_PORTFOLIO_DIR ) . 'public/extensions/wpml.php' );
			}

		}

		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'cherry-portfolio', plugins_url( 'public/assets/css/style.css', __FILE__ ), array(), CHERRY_PORTFOLIO_VERSION );
		}

		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {}

		/**
		 * Pass style handle to CSS compiler.
		 *
		 * @since 1.0.0
		 *
		 * @param array $handles CSS handles to compile.
		 */
		public function add_style_to_compiler( $handles ) {
			$handles = array_merge(
				array( 'cherry-portfolio' => plugins_url( 'public/assets/css/style.css', __FILE__ ) ),
				$handles
			);

			return $handles;
		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 */
		function activation() {
			Cherry_Portfolio_Registration::register();
			Cherry_Portfolio_Registration::register_taxonomy();

			flush_rewrite_rules();
		}

		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 */
		function deactivation() {
			flush_rewrite_rules();
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

	Cherry_Portfolio::get_instance();

	/**
	 * Define main function to get plugin instance
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function cherry_portfolio_class() {
		return Cherry_Portfolio::get_instance();
	}
}
