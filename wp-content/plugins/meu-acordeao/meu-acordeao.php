<?php
/**
 * Plugin Name: Meu Acordeão
 * Description: Um bloco de acordeão simples para Gutenberg.
 * Version: 1.0
 * Author: Mr.Goose
 */

function meu_acordeao_block() {
    wp_register_script(
        'meu-acordeao-block-script',
        plugins_url('bloco/index.build.js', __FILE__), // Caminho para o arquivo compilado
        array('wp-blocks', 'wp-editor'), // Dependências
        filemtime(plugin_dir_path(__FILE__) . 'bloco/index.build.js') // Versão do arquivo para cache busting
    );

    register_block_type('meu-plugin/meu-acordeao', array(
        'editor_script' => 'meu-acordeao-block-script',
    ));
}
add_action('init', 'meu_acordeao_block');

function meu_acordeao_enqueue_scripts() {
    wp_enqueue_script('meu-acordeao-js', plugins_url('bloco/acordeao.js', __FILE__), array(), false, true);
}
add_action('wp_enqueue_scripts', 'meu_acordeao_enqueue_scripts');

function meu_acordeao_enqueue_styles() {
    wp_enqueue_style('meu-acordeao-css', plugins_url('bloco/acordeao-estilo.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'meu_acordeao_enqueue_styles');

function meu_acordeao_enqueue_editor_assets() {
    wp_enqueue_style(
        'meu-acordeao-editor-style', 
        plugins_url('bloco/editor-estilo-acordeao.css', __FILE__),
        array(), 
        '1.0'
    );
}
add_action('enqueue_block_editor_assets', 'meu_acordeao_enqueue_editor_assets');
