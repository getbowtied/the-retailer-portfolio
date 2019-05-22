<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_tr_portfolio_editor_assets' );
if ( ! function_exists( 'gbt_18_tr_portfolio_editor_assets' ) ) {
	function gbt_18_tr_portfolio_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_tr_portfolio_script',
			plugins_url( 'block.js', dirname(__FILE__) ),
            array( 'wp-api-request', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
		);

		wp_enqueue_style(
			'gbt_18_tr_portfolio_editor_styles',
			plugins_url( 'assets/css/editor.css', dirname(__FILE__) ),
			array( 'wp-edit-blocks' )
		);
	}
}

//==============================================================================
//	Register Block Type
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
    register_block_type( 'getbowtied/tr-portfolio', array(
        'attributes'      => array(
            'number'                        => array(
                'type'                      => 'integer',
                'default'                   => 12,
            ),
            'categoriesSavedIDs'            => array(
                'type'                      => 'string',
                'default'                   => '',
            ),
            'showFilters'                   => array(
                'type'                      => 'boolean',
                'default'                   => false,
            ),
            'columns'                       => array(
                'type'                      => 'number',
                'default'                   => '3',
            ),
            'align'                         => array(
                'type'                      => 'string',
                'default'                   => 'center',
            ),
            'orderby'                       => array(
                'type'                      => 'string',
                'default'                   => 'date_desc',
            ),
        ),

        'render_callback' => 'gbt_18_tr_render_frontend_portfolio',
    ) );
}