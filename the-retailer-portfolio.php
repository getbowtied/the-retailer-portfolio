<?php

/**
 * Plugin Name:       		The Retailer Portfolio Addon
 * Plugin URI:        		https://theretailer.wp-theme.design/
 * Description:       		Extends the functionality of your WordPress site by adding a 'Portfolio' custom post type allowing you to organize and showcase you your work or products.
 * Version:           		1.1
 * Author:            		GetBowtied
 * Author URI:				https://getbowtied.com
 * Text Domain:				the-retailer-portfolio
 * Domain Path:				/languages/
 * Requires at least: 		5.0
 * Tested up to: 			5.2.1
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

			$this->gbt_tr_customizer_options();
			$this->gbt_register_post_type();
			$this->gbt_register_shortcode();
			$this->gbt_register_scripts();
			$this->gbt_register_styles();
			$this->gbt_add_block();

			add_action( 'init', function() {
				add_post_type_support( 'portfolio', 'excerpt' );
			});

			include_once( 'includes/helpers/helpers.php' );

			add_image_size('portfolio-details', 1180, 2000, true);
			add_image_size('portfolio_4_col', 220, 165, true); //4X3
			add_image_size('portfolio_3_col', 300, 225, true); //4X3
			add_image_size('portfolio_2_col', 460, 345, true); //4X3

			// Templates
			add_filter( 'theme_page_templates', array( $this, 'gbt_tr_add_page_template_to_dropdown' ) );
			add_filter( 'page_template', array( $this, 'gbt_mt_portfolio_page_template' ), 99 );
			add_filter( 'single_template', array( $this, 'gbt_mt_portfolio_template' ), 99 );
			add_filter( 'taxonomy_template', array( $this, 'gbt_mt_portfolio_taxonomy_template' ), 99 );

			// Shortcode
			add_action( 'plugins_loaded', function() {
				if ( defined(  'WPB_VC_VERSION' ) ) {
					include_once( 'includes/shortcodes/wb/recent-work-filtered.php' );
					if(function_exists('vc_set_default_editor_post_types')) {
						vc_set_default_editor_post_types( array('post','page','product','portfolio') );
					}
				}
			});
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
		 * Page templates ( for WP 4.7+ )
		 */
		public function gbt_tr_add_page_template_to_dropdown( $posts_templates ) {
			$posts_templates = array_merge( $posts_templates, array( 'page-portfolio.php' => 'Portfolio' ) );
			return $posts_templates;
		}

		/**
		 * Registers customizer options.
		 *
		 * @return void
		 */
		protected function gbt_tr_customizer_options() {
			add_action( 'customize_register', array( $this, 'gbt_tr_portfolio_customizer' ) );
		}

		/**
		 * Creates customizer options.
		 *
		 * @return void
		 */
		public function gbt_tr_portfolio_customizer( $wp_customize ) {

			// Section
			$wp_customize->add_section( 'portfolio', array(
		 		'title'       => esc_attr__( 'Portfolio', 'the-retailer-portfolio' ),
		 		'priority'    => 20,
		 	) );

		 	// Fields
			$wp_customize->add_setting( 'tr_portfolio_items_per_row', array(
				'type'		 			=> 'option',
				'capability' 			=> 'manage_options',
				'default'     			=> 3,
			) );

			$wp_customize->add_control( 
				new WP_Customize_Control(
					$wp_customize,
					'tr_portfolio_items_per_row',
					array( 
						'type'			=> 'number',
						'label'       	=> esc_attr__( 'Number of Portfolio Items per row', 'the-retailer-portfolio' ),
						'section'     	=> 'portfolio',
						'priority'    	=> 10,
						'input_attrs'	=> array(
					        'min'  => 2,
					        'max'  => 4,
					        'step' => 1
					    ),
					)
				)
			);

			$wp_customize->add_setting( 'tr_portfolio_items_order_by', array(
				'type'		 			=> 'option',
				'capability' 			=> 'manage_options',
				'default'     			=> 'date',
			) );

			$wp_customize->add_control( 
				new WP_Customize_Control(
					$wp_customize,
					'tr_portfolio_items_order_by',
					array( 
						'type'			=> 'select',
						'label'       	=> esc_attr__( 'Order By', 'the-retailer-portfolio' ),
						'section'     	=> 'portfolio',
						'priority'    	=> 10,
						'choices'    	=> array(
					    	'date' 	=> 'Date',
					    	'title' => 'Title',
					    )
					)
				)
			);

			$wp_customize->add_setting( 'tr_portfolio_items_order', array(
				'type'		 			=> 'option',
				'capability' 			=> 'manage_options',
				'default'     			=> 'DESC',
			) );

			$wp_customize->add_control( 
				new WP_Customize_Control(
					$wp_customize,
					'tr_portfolio_items_order',
					array( 
						'type'			=> 'select',
						'label'       	=> esc_attr__( 'Order By', 'the-retailer-portfolio' ),
						'section'     	=> 'portfolio',
						'priority'    	=> 10,
						'choices'    	=> array(
					    	'DESC'     => 'DESC',
			        		'ASC'    => 'ASC',
					    )
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
		 * Registers portfolio shortcode
		 *
		 * @return void
		*/
		public static function gbt_register_shortcode() {
			include_once( 'includes/shortcodes/wp/recent-work-filtered.php' );
		}

		/**
		 * Loads Gutenberg blocks
		 *
		 * @return void
		*/
		public static function gbt_add_block() {
			add_action( 'plugins_loaded', function() {
				$registry = new WP_Block_Type_Registry;
				if( !$registry->is_registered( 'getbowtied/tr-portfolio' ) ) {
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
					'gbt-tr-portfolio-styles', 
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
					'gbt-tr-mixitup-scripts',
					plugins_url( 'includes/assets/js/vendor/mixitup.min.js', __FILE__ ), 
					array('jquery'),
					false,
					true
				);

				wp_enqueue_script(
					'gbt-tr-portfolio-scripts',
					plugins_url( 'includes/assets/js/portfolio.js', __FILE__ ), 
					array('jquery'),
					false,
					true
				);

			}, 300 );
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

			if( is_tax( 'portfolio_filter' ) ) {
				$template = plugin_dir_path(__FILE__) . 'includes/templates/taxonomy-portfolio_filter.php';
			}

			return $template;
		}

		/**
		 * Loads portfolio page template
		 *
		 * @return void
		*/
		public static function gbt_mt_portfolio_page_template( $template ) {

			if( is_page_template( 'page-portfolio.php' ) ) {			
				$template = plugin_dir_path(__FILE__) . 'includes/templates/page-portfolio.php';
			}

			return $template;
		}
	}

endif;

$theme = wp_get_theme();
$parent_theme = $theme->parent();
if( $theme->template == 'theretailer' && ( $theme->version >= '2.12' || ( !empty($parent_theme) && $parent_theme->version >= '2.12' ) ) ) {
	$theretailer_portfolio = new TheRetailerPortfolio;
}