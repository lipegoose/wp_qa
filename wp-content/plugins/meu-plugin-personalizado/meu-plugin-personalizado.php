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

// Função para exibir a página de configurações para gerenciar múltiplas frases
function meu_plugin_personalizado_opcoes() {
    // Verifique a permissão do usuário
    if (!current_user_can('manage_options')) {
        wp_die('Você não tem permissão suficiente para acessar esta página.');
    }

    // Lógica para adicionar novas frases
    /*if (isset($_POST['meu_plugin_nova_frase']) && check_admin_referer('meu_plugin_nova_frase_adicionar', 'meu_plugin_nova_frase_verificar')) {
        // Recupera as frases existentes e garante que seja um array
        $frases = get_option('meu_plugin_personalizado_texto', array());
        if (!is_array($frases))
            $frases = []; // Se não for um array, inicializa como um array vazio
        $frases[] = sanitize_text_field($_POST['meu_plugin_nova_frase']);
        update_option('meu_plugin_personalizado_texto', $frases);
    }*/

    // if (isset($_POST['meu_plugin_nova_frase'], $_POST['meu_plugin_novo_texto']) && check_admin_referer('meu_plugin_acao_adicionar', 'meu_plugin_verificar')) {
    if (isset($_POST['meu_plugin_nova_frase'], $_POST['meu_plugin_novo_texto']) && check_admin_referer('meu_plugin_nova_frase_adicionar', 'meu_plugin_nova_frase_verificar')) {
        $frases = get_option('meu_plugin_personalizado_texto', array());
        if (!is_array($frases))
            $frases = [];

        // Adiciona a nova frase e o novo texto como um array associativo
        $frases[] = [
            'frase' => sanitize_text_field($_POST['meu_plugin_nova_frase']),
            'texto' => sanitize_textarea_field($_POST['meu_plugin_novo_texto'])
        ];
        /*echo "akki: ";
        print_r($frases);
        echo "!!!";*/
        update_option('meu_plugin_personalizado_texto', $frases);
    }

    // Lógica para excluir uma frase
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && check_admin_referer('delete_frase')) {
        $frases = get_option('meu_plugin_personalizado_texto', array());
        unset($frases[$_GET['frase_id']]);
        $frases = array_values($frases); // Reindexa o array
        update_option('meu_plugin_personalizado_texto', $frases);
        echo '<div class="updated"><p>Frase excluída.</p></div>';
    }

    // Recupere as frases salvas
    $frases = get_option('meu_plugin_personalizado_texto', array());
    // Lógica para exibir as frases existentes com opções de edição e exclusão
    ?>
    <div class="wrap">
        <h2>Meu Plugin Personalizado</h2>
        <form method="post">
            <?php wp_nonce_field('meu_plugin_nova_frase_adicionar', 'meu_plugin_nova_frase_verificar'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Adicionar Nova Frase (Título):</th>
                    <td><input type="text" name="meu_plugin_nova_frase" value="" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Adicionar Novo Texto:</th>
                    <td><textarea name="meu_plugin_novo_texto"></textarea></td>
                </tr>
            </table>
            <?php submit_button('Adicionar Frase'); ?>
        </form>
        <?php
        // print_r($frases);
        if(is_array($frases) && count($frases)>0){
        ?>
        <h3>Frases Salvas</h3>
        <ul>
            <?php
            foreach ($frases as $id => $dados) {
                $delete_url = wp_nonce_url(add_query_arg(['action' => 'delete', 'frase_id' => $id]), 'delete_frase');
                echo '<li>' . esc_html($dados['frase']) . ' <a href="' . esc_url($delete_url) . '">Excluir</a></li>';
            }            
            /*foreach ($frases as $id => $frase) {
                $delete_url = wp_nonce_url(add_query_arg(['action' => 'delete', 'frase_id' => $id]), 'delete_frase');
                echo '<li>' . esc_html($frase) . ' <a href="' . esc_url($delete_url) . '">Excluir</a></li>';
            }*/
            ?>
        </ul>
        <?php
        } // close if(count($frases)>0)
        else{
        ?>
        <h3>Nenhuma frase salva ainda</h3>
        <?php
        } // close else if(count($frases)>0)
        ?>
    </div>
    <?php
}

// Registrar a configuração
add_action('admin_init', 'meu_plugin_personalizado_registrar_configuracao');

function meu_plugin_personalizado_registrar_configuracao() {
    register_setting('meu-plugin-personalizado-opcoes', 'meu_plugin_personalizado_texto');
}

/*function meu_plugin_personalizado_adicionar_texto($content) {
    $texto_personalizado = get_option('meu_plugin_personalizado_texto', 'Texto adicionado pelo Meu Plugin Personalizado!!!');
    return $content . '<p>' . esc_html($texto_personalizado) . '</p>';
}
add_filter('the_content', 'meu_plugin_personalizado_adicionar_texto');*/

// Adiciona a meta box
add_action('add_meta_boxes', 'meu_plugin_adicionar_meta_box');
function meu_plugin_adicionar_meta_box() {
    // Adiciona a meta box em postagens
    /*add_meta_box('meu-plugin-meta-box', 'Selecionar Frase Personalizada', 'meu_plugin_mostrar_meta_box', 'post', 'side', 'high');*/

    // Adiciona a mesma meta box em páginas
    add_meta_box('meu-plugin-meta-box', 'Selecionar Frase Personalizada', 'meu_plugin_mostrar_meta_box', 'page', 'side', 'high');
}

// Mostra a meta box
function meu_plugin_mostrar_meta_box($post) {
    $frases = get_option('meu_plugin_personalizado_texto', array());
    // $selecao = get_post_meta($post->ID, '_meu_plugin_frase_selecionada', true);

    // Segurança
    wp_nonce_field('meu_plugin_salvar_frase', 'meu_plugin_meta_box_nonce');

    /*echo '<select name="meu_plugin_frase_selecionada">';
    echo '<option value="">Selecione uma Frase...</option>';
    foreach ($frases as $frase) {
        echo '<option value="' . esc_attr($frase) . '"' . selected($selecao, $frase, false) . '>' . esc_html($frase) . '</option>';
    }
    echo '</select>';*/

    echo '<p>Use o shortcode [meu_plugin_frase] em seu conteúdo para exibir a frase selecionada.</p>';
}

// Salva a seleção do usuário
add_action('save_post', 'meu_plugin_salvar_meta_box_dados');
function meu_plugin_salvar_meta_box_dados($post_id) {
    if (!isset($_POST['meu_plugin_meta_box_nonce']) || !wp_verify_nonce($_POST['meu_plugin_meta_box_nonce'], 'meu_plugin_salvar_frase')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['meu_plugin_frase_selecionada'])) {
        update_post_meta($post_id, '_meu_plugin_frase_selecionada', sanitize_text_field($_POST['meu_plugin_frase_selecionada']));
    }
}

/*function meu_plugin_personalizado_adicionar_texto($content) {
    global $post;
    $frase_selecionada = get_post_meta($post->ID, '_meu_plugin_frase_selecionada', true);
    if (!empty($frase_selecionada)) {
        $content .= '<p>' . esc_html($frase_selecionada) . '</p>';
    }
    return $content;
}
add_filter('the_content', 'meu_plugin_personalizado_adicionar_texto');*/

function meu_plugin_shortcode_frase($atts) {
    global $post;
    /*$frase_selecionada = get_post_meta($post->ID, '_meu_plugin_frase_selecionada', true);
    if (!empty($frase_selecionada)) {
        return '<p>' . esc_html($frase_selecionada) . '</p>';
    }
    return ''; // Retorna vazio se não houver frase selecionada*/

    // $selecionado = get_post_meta($post->ID, '_meu_plugin_frase_selecionada', true);

    // Supondo que `$selecionado` agora pode incluir tanto a frase quanto o texto.
    /*if (!empty($selecionado) && is_array($selecionado)) {
        $frase = isset($selecionado['frase']) ? $selecionado['frase'] : '';
        $texto = isset($selecionado['texto']) ? $selecionado['texto'] : '';

        // Ajuste a formatação conforme necessário
        $content .= '<h3>' . esc_html($frase) . '</h3>';
        $content .= '<p>' . esc_html($texto) . '</p>';
    }*/
    $content = '';
    $frases = get_option('meu_plugin_personalizado_texto', array());
    foreach ($frases as $id => $dados) {
        $frase = isset($dados['frase']) ? $dados['frase'] : '';
        $texto = isset($dados['texto']) ? $dados['texto'] : '';

        // Ajuste a formatação conforme necessário
        $content .= '<h3>' . esc_html($frase) . '</h3>';
        $content .= '<p>' . esc_html($texto) . '</p>';
    }
    return $content;
}
add_shortcode('meu_plugin_frase', 'meu_plugin_shortcode_frase');
