<?php

/*
Plugin Name: Wordpress Cookie Mensagem
Description: O plugin apresenta uma simples barra notificando que o website possui cookies
Version: 1.0
Author: César Macaia
Author URI: https://cesarmacaia.com
*/

// Começar sempre com essa condição.
// Se o arquivo for chamado diretamente e não pelo wordpress, abortar!

if (!defined ('ASPATH')) exit;

// Verifica se uma classe foi definida, caso não retorna falso.
// https://www.php.net/manual/pt_BR/function.class-exists.php
if (!class_exists ('WPCM')) {

        final class WPCM {
            
            private static $instancia = null;

            //Variáveis
            public $opcoes_padrao = array();
            public static $opcoes = null;

            /** Instancias: a função retorna a única instancia true da classe principal do plugin
            * @return object instancia
            * @since 1.0
            */

            // Utilização do singleton pattern para limitar o número de instâncias que podem ser criadas para apenas uma
            
            public static function instancia (){

                // o objeto é criado com e apartir da própria classe e somente se a classe não tiver já alguma instância.
                // como restringimos o número de objetos que podem ser cirados da classe para apenas uma, então ficamos com todas as variáveis apontando para o mesmo objeto
                // o legal do singleton pattern é que cria variáveis glovais que podem ser acessadas e alteradas de qualquer local do código
                if(self::$instancia == null){

                    self::$instancia = new WPCM;
                    self::$instancia->constants();
                    self::$instancia->includes();
                    self::$instancia->load_textdomain();
                }

                return self::$instancia;
            }

            private function __construct () {

                $this->opcoes_padrao = array(
                    'more-info-label'     => '',
                    'more-info-url'    	  => '',
                    'ok-label'  	  	  => __('Aceitar', 'wpcm_domain'),
                    'message' 	 		  => __('Atenção: Este website utiliza cookies, os seus dados serão armazenados para facilitar o carregamento do site na próxima vez que acessar.', 'wpcm_domain'),
                    'border' 			  => true,
                    'font-size' 		  => 11,
                    'text-align' 		  => 'right',
                    'display-shadow' 	  => false,
                    'button-border' 	  => '',
                    'button-border-color' => 2
);
add_action( 'admin_init', 			 array( 'WPCM_Admin', 'register_settings' ));
add_action( 'admin_menu', 			 array( 'WPCM_Admin', 'set_settings_link' ));

/*Função chamada utilizando o wp_enqueue_scripts action hook se quiser chamar a função para o front-end do site, 
Para chamar a função no painel administrativo, use o admin_enqueue_scripts action hook. 
Para a tela de login, use o login_enqueue_scripts action hook. 
OBS: Chamar fora esses enqueue fora de um action hook pode gerar problemas ao seu site. */
add_action( 'admin_enqueue_scripts', array( 'WPCM_Admin', 'enqueue_scripts' ));

// ler: https://developer.wordpress.org/reference/functions/wp_enqueue_script/

// filtra o action link de cada plugin na tabela de lista de plugins 
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( 'WPCM_Admin', 'add_action_links') );

// Ler: https://developer.wordpress.org/reference/functions/register_activation_hook/
register_activation_hook( __FILE__, array( 'WPCM_Admin', 'salvar_$opcoes_padrao') );
 }

 private function includes () {
		  	
    require_once( WPCM_PLUGIN_DIR . 'includes/class-cookie.php');
    require_once( WPCM_PLUGIN_DIR . 'includes/class-admin.php');

    WPCM_Main::instancia();
    
 }
 private function constants() {
		  	
    if( !defined('WPCM_PLUGIN_DIR') )  	{ define('WPCM_PLUGIN_DIR', plugin_dir_path( __FILE__ )); }
  if( !defined('WPCM_PLUGIN_URL') )  	{ define('WPCM_PLUGIN_URL', plugin_dir_url( __FILE__ ));  }
  if( !defined('WPCM_PLUGIN_FILE') ) 	{ define('WPCM_PLUGIN_FILE',  __FILE__ );  }
  if( !defined('WPCM_PLUGIN_VERSION') )  { define('WPCM_PLUGIN_VERSION', '1.0');  } 
  
}

public static  function get_options(){
		 	
    if( self::$opcoes == null ) {
    
        $opcoes = get_option( 'WPCM_settings' );
        
        if( empty($opcoes) ){
            $opcoes = WPCM::instancia()->$opcoes_padrao;
        }
    
         self::$opcoes = $opcoes;

         /* Definir novas opções na atualização */	
         foreach( WPCM::instancia()->$opcoes_padrao as $key => $value ) {

             if( !isset( self::$opcoes[$key] ) ) { 
                 self::$opcoes[$key] = WPCM::instancia()->$opcoes_padrao[$key]; 
             }

         }
    }

    return self::$opcoes;
    
 } 


/**
 * load_textdomain
 * 
 */
public function load_textdomain() {
    
    load_plugin_textdomain('WPCM_domain', false,  dirname( plugin_basename( WPCM_PLUGIN_FILE ) ) . '/languages/' );	
 }





}// class


}// se !class_exists


WPCM::instancia();