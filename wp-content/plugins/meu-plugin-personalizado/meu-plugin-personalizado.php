<?php
/*
Plugin Name: Meu Plugin Personalizado
Plugin URI: http://localhost
Description: Um plugin personalizado simples para adicionar funcionalidades específicas...
Version: 1.0
Author: Mr.Goose
Author URI: http://localhost
*/

function meu_plugin_personalizado_adicionar_texto($content) {
    return $content . '<p>Texto adicionado pelo Meu Plugin Personalizado.</p>';
}
add_filter('the_content', 'meu_plugin_personalizado_adicionar_texto');
