<?php
/*
Plugin Name: Meu Plugin Personalizado
Plugin URI: http://localhost
Description: Um plugin personalizado simples para adicionar funcionalidades específicas...
Version: 1.0
Author: Mr.Goose
Author URI: http://localhost
*/

/*function meu_plugin_personalizado_adicionar_texto($content) {
    return $content . '<p>Texto adicionado pelo Meu Plugin Personalizado.</p>';
}
add_filter('the_content', 'meu_plugin_personalizado_adicionar_texto');*/

// Hook para adicionar a página de configurações
add_action('admin_menu', 'meu_plugin_personalizado_menu');

function meu_plugin_personalizado_menu() {
    add_options_page(
        'Configurações do Meu Plugin Personalizado', // Título da página
        'Meu Plugin Personalizado', // Título do menu
        'manage_options', // Capacidade necessária para ver a opção
        'meu-plugin-personalizado', // Slug do menu
        'meu_plugin_personalizado_opcoes' // Função para exibir a página de opções
    );
}

// Função para exibir a página de configurações
function meu_plugin_personalizado_opcoes() {
    if (!current_user_can('manage_options')) {
        wp_die('Você não tem permissão suficiente para acessar esta página.');
    }
    ?>
    <div class="wrap">
        <h2>Meu Plugin Personalizado</h2>
        <form method="post" action="options.php">
            <?php settings_fields('meu-plugin-personalizado-opcoes'); ?>
            <?php do_settings_sections('meu-plugin-personalizado'); ?>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Texto Personalizado:</th>
                <td><input type="text" name="meu_plugin_personalizado_texto" value="<?php echo esc_attr(get_option('meu_plugin_personalizado_texto')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registrar a configuração
add_action('admin_init', 'meu_plugin_personalizado_registrar_configuracao');

function meu_plugin_personalizado_registrar_configuracao() {
    register_setting('meu-plugin-personalizado-opcoes', 'meu_plugin_personalizado_texto');
}

function meu_plugin_personalizado_adicionar_texto($content) {
    $texto_personalizado = get_option('meu_plugin_personalizado_texto', 'Texto adicionado pelo Meu Plugin Personalizado!!!');
    return $content . '<p>' . esc_html($texto_personalizado) . '</p>';
}
add_filter('the_content', 'meu_plugin_personalizado_adicionar_texto');
