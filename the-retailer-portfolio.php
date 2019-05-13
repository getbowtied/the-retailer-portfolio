<?php

/**
 * Plugin Name:       		The Retailer Portfolio Addon
 * Plugin URI:        		https://theretailer.wp-theme.design/
 * Description:       		Extends the functionality of your WordPress site by adding a 'Portfolio' custom post type allowing you to organize and showcase you your work or products.
 * Version:           		1.0
 * Author:            		GetBowtied
 * Author URI:				https://getbowtied.com
 * Text Domain:				the-retailer-portfolio
 * Domain Path:				/languages/
 * Requires at least: 		5.0
 * Tested up to: 			5.1.1
 *
 * @package  The Retailer Portfolio
 * @author   GetBowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/the-retailer-portfolio/master/core/updater/assets/plugin.json',
	__FILE__,
	'the-retailer-portfolio'
);

if ( ! class_exists( 'TheRetailerPortfolio' ) ) :

	/**
	 * TheRetailerPortfolio class.
	*/
	class TheRetailerPortfolio {

		/**
		 * The single instance of the class.
		 *
		 * @var TheRetailerPortfolio
		*/
		protected static $_instance = null;

		/**
		 * TheRetailerPortfolio constructor.
		 *
		*/
		public function __construct() {

			// $this->gbt_mt_customizer_options();
			// $this->gbt_register_post_type();
			// $this->gbt_add_metabox();
			// $this->gbt_register_shortcode();
			// $this->gbt_register_scripts();
			// $this->gbt_register_admin_scripts();
			// $this->gbt_register_styles();
			// $this->gbt_add_block();

			// add_filter( 'single_template', array( $this, 'gbt_mt_portfolio_template' ), 99 );
			// add_filter( 'taxonomy_template', array( $this, 'gbt_mt_portfolio_taxonomy_template' ), 99 );

			// if ( defined(  'WPB_VC_VERSION' ) ) {
			// 	add_action( 'init', function() {
			// 		include_once( 'includes/shortcodes/wb/portfolio.php' );
			// 		if(function_exists('vc_set_default_editor_post_types')) {
			// 			vc_set_default_editor_post_types( array('post','page','product','portfolio') );
			// 		}
			// 	} );
			// }
		}

		/**
		 * Ensures only one instance of TheRetailerPortfolio is loaded or can be loaded.
		 *
		 * @return TheRetailerPortfolio
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Registers customizer options.
		 *
		 * @return void
		 */
		protected function gbt_mt_customizer_options() {
			add_action( 'customize_register', array( $this, 'gbt_mt_portfolio_customizer' ) );
		}

		/**
		 * Creates customizer options.
		 *
		 * @return void
		 */
		public function gbt_mt_portfolio_customizer( $wp_customize ) {

			// Section
			$wp_customize->add_section( 'portfolio', array(
		 		'title'       => esc_attr__( 'Portfolio', 'the-retailer-portfolio' ),
		 		'priority'    => 20,
		 	) );

		 	// Fields
			$wp_customize->add_setting( 'mt_portfolio_slug', array(
				'type'		 			=> 'option',
				'capability' 			=> 'manage_options',
				'default'     			=> 'portfolio-item',
			) );

			$wp_customize->add_control( 
				new WP_Customize_Control(
					$wp_customize,
					'mt_portfolio_slug',
					array( 
						'type'			=> 'text',
						'label'       	=> esc_attr__( 'Portfolio Posts Slug', 'the-retailer-portfolio' ),
						'description' 	=> __('<span class="dashicons dashicons-editor-help"></span>Default slug is "portfolio-item". Enter a custom one to overwrite it. <br/><b>You need to regenerate your permalinks if you modify this!</b>', 'the-retailer-portfolio'),
						'section'     	=> 'portfolio',
						'priority'    	=> 20,
					)
				)
			);
		}

		/**
		 * Registers portfolio post type and taxonomy
		 *
		 * @return void
		*/
		public static function gbt_register_post_type() {

			include_once( 'includes/portfolio/post-type.php' );
			include_once( 'includes/portfolio/taxonomy.php' );
		}

		/**
		 * Adds portfolio metabox
		 *
		 * @return void
		*/
		public static function gbt_add_metabox() {
			
			include_once( 'includes/portfolio/metabox.php' );
		}

		/**
		 * Registers portfolio shortcode
		 *
		 * @return void
		*/
		public static function gbt_register_shortcode() {
			include_once( 'includes/shortcodes/wp/portfolio.php' );
		}

		/**
		 * Loads Gutenberg blocks
		 *
		 * @return void
		*/
		public static function gbt_add_block() {
			add_action( 'plugins_loaded', function() {
				$registry = new WP_Block_Type_Registry;
				if( !$registry->is_registered( 'getbowtied/mt-portfolio' ) ) {
					include_once( 'includes/blocks/index.php' );
				}
			});
		}

		/**
		 * Enqueues portfolio styles
		 *
		 * @return void
		*/
		public static function gbt_register_styles() {
			add_action( 'wp_enqueue_scripts', function() {
				wp_enqueue_style(
					'gbt-mt-portfolio-styles', 
					plugins_url( 'includes/assets/css/portfolio.css', __FILE__ ), 
					NULL
				);
			} );
		}

		/**
		 * Enqueues portfolio scripts
		 *
		 * @return void
		*/
		public static function gbt_register_scripts() {
			add_action( 'wp_enqueue_scripts', function() {
				wp_enqueue_script(
					'gbt-mt-portfolio-scripts',
					plugins_url( 'includes/assets/js/portfolio.js', __FILE__ ), 
					array('jquery'),
					false,
					true
				);
			}, 300 );
		}

		/**
		 * Enqueues portfolio admin scripts
		 *
		 * @return void
		*/
		public static function gbt_register_admin_scripts() {
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', function() {
					global $post_type;
					wp_enqueue_script(
						'gbt-mt-portfolio-admin-scripts',
						plugins_url( 'includes/assets/js/wp-admin-portfolio.js', __FILE__ ), 
						array('wp-color-picker'), 
						false
					);
				} );
			}			
		}

		/**
		 * Loads portfolio template
		 *
		 * @return void
		*/
		public static function gbt_mt_portfolio_template( $template ) {
			global $post;
			
			if ( $post->post_type == 'portfolio' ) {
				$template = plugin_dir_path(__FILE__) . 'includes/templates/single-portfolio.php';		        
		    }

		    return $template;
		}

		/**
		 * Loads portfolio taxonomy template
		 *
		 * @return void
		*/
		public static function gbt_mt_portfolio_taxonomy_template( $template ) {

			if( is_tax( 'portfolio_categories' ) ) {
				$template = plugin_dir_path(__FILE__) . 'includes/templates/taxonomy-portfolio_categories.php';
			}

			return $template;
		}
	}

endif;

$theme = wp_get_theme();
$parent_theme = $theme->parent();
if( $theme->template == 'theretailer' && ( $theme->version >= '2.11.1' || ( !empty($parent_theme) && $parent_theme->version >= '2.11.1' ) ) ) {
	$theretailer_portfolio = new TheRetailerPortfolio;
}