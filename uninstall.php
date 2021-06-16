<?php

?/**
*Este arquivo vai ser carregado clicar em desinstalar o plugin
* @package Wordpress Cookie Mensagem
* @subpackage uninstall
* @author César Macaia
* @since 1.0 */

// Se a desinstalação não for chamada pelo wordpress, abortar!
if(!defined( 'WP_UNISTALL_PLUGIN' )) exit ();

delete_option ('wpcm_settings');