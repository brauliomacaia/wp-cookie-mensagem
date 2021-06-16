<?php

/** 
* @package Wordpress Cookie Mensagem
* @subpackage Class Admin
* @author César Macaia
*/

// Abortar se acessar diretamente
if ( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'WPCM_Admin' )){
 
class WPCM_Admin {
	
	/**
	 * contructor 
	 * 
	 */
	
	private function __construct (){
		
	}

    /**
	 * register_plugin_settings
	 * 
	 */
	 
	 public static function register_settings (){
	 		
        register_setting('wpcm_settings_group', 'wpcm_settings', array( 'wpcm_Admin', 'sanitize_options') );
        
    }

     /**
	  * sanitize_options
	  */

	 public static function sanitize_options( $opcoes ){

		$allowed = apply_filters( 'wpcm_message_allowed_tags',  array(
				    'a' => array(
				        'href' 	=> array(),
				        'title' => array(),
				        'class' => array(),
				        'id' 	=> array(),
					),
				    'br' => array(),
				    'em' => array(),
				    'i' => array(),
				    'strong' => array(),
				    'b' => array()
		) );

	 	$opcoes['message'] 		= wp_kses( $opcoes['message'], $allowed );
	 	$opcoes['more-info-label'] = sanitize_text_field( $opcoes['more-info-label'] );
	 	$opcoes['ok-label'] 		= sanitize_text_field( $opcoes['ok-label'] );
	 	$opcoes['more-info-url'] 	= esc_url_raw( $opcoes['more-info-url'] );

	 	$opcoes['font-size'] 		= absint( $opcoes['font-size'] );
	 	$opcoes['text-align'] 		= sanitize_text_field( $opcoes['text-align'] );

	 	( isset( $opcoes['display-shadow'] ) ) ? $opcoes['display-shadow'] = absint( $opcoes['display-shadow'] ) : $opcoes['display-shadow'] = 0;

	 	$opcoes['background-color']	= sanitize_text_field( $opcoes['background-color'] );
	 	$opcoes['text-color']			= sanitize_text_field( $opcoes['text-color'] );
	 	$opcoes['border-color']		= sanitize_text_field( $opcoes['border-color'] );
	 	$opcoes['ok-background-color'] = sanitize_text_field( $opcoes['ok-background-color'] );
	 	$opcoes['button-border-color'] = sanitize_text_field( $opcoes['button-border-color'] );

	 	return $opcoes;

	}
	 
	 /**
	 * set_settings_link_page
	 * 
	 */
	 
	 public static function set_settings_link (){
	 		
	 	add_options_page('Simple Cookie Bar', 'Simple Cookie Bar', 'manage_options', 'wpcm-options', array( 'wpcm_Admin' , 'settings_page'));
		
	 }
	 
	/**
	 * settings_page
	 * 
	 */
	
	public static function settings_page() {
		
		$opcoes = wpcm::get_options();
		require_once( WPCM_PLUGIN_DIR . 'includes/settings-form.php' );
	
	}
	
	/**
	 * enqueue_scripts
	 * 
	 */
	 
	 public static function enqueue_scripts (){
	 	
			wp_enqueue_script('jquery');

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			
			wp_enqueue_script( 'wpcm-admin-script', WPCM_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'wp-color-picker'), WPCM_PLUGIN_VERSION );
			wp_enqueue_style('wpcm-admin-style', WPCM_PLUGIN_URL . 'assets/css/admin.css', array(), WPCM_PLUGIN_VERSION);
			
	 }

	 /**
	  * add_action_links
	  * 
	  */


	 public static function add_action_links( $links ) {
 
	    $url = get_admin_url(null, 'options-general.php?page=wpcm-options');
	 
	    $links[] = '<a href="'. $url .'">Settings</a>';
	    return $links;
	}

	/**
	 * save_default_options
	 * Essa função será carregada na ativação do plugin
	 * ajuda na compatibilidade com o polylang and wpml
	 *
	 */

	public static function save_default_options() {

		$opcoes = get_option( 'wpcm_settings' );
		
		if( empty($opcoes) ) {
			$opcoes = wpcm::get_options();
			update_option( 'wpcm_settings', $opcoes );
		}

		return;

	}
	
}// class
}// if

