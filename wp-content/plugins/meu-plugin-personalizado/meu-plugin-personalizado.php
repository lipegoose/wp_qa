<?php
/*
Plugin Name: Meu Plugin Personalizado
Plugin URI: http://localhost
Description: Um plugin personalizado simples para adicionar funcionalidades específicas...
Version: 1.0
Author: Mr.Goose
Author URI: http://localhost
*/

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

    if (isset($_GET['edit'])) {
        $edit_index = intval($_GET['edit']);
        $frases = get_option('meu_plugin_personalizado_texto', array());
        if (isset($frases[$edit_index])) {
            // Mostrar formulário de edição com $frases[$edit_index]['frase'] e $frases[$edit_index]['texto']
            ?>
            <div class="wrap">
                <h2>Meu Plugin Personalizado (Editar)</h2>
                <form method="post">
                    <input type="hidden" name="edit_index" value="<?php echo $edit_index; ?>">
                    <?php wp_nonce_field('meu_plugin_editar', 'meu_plugin_editar_verificar'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Editar Frase (Título):</th>
                            <td><input type="text" name="meu_plugin_editar_frase" value="<?php echo $frases[$edit_index]['frase']; ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Editar Texto:</th>
                            <td>
                                <?php
                                $content = $frases[$edit_index]['texto'];
                                $editor_id = 'meu_plugin_editar_texto';
                                $settings = array( 
                                    'textarea_name' => 'meu_plugin_editar_texto', // Importante: precisa ser configurado para o nome do campo
                                    'media_buttons' => true, // Habilita o botão de adicionar mídia
                                    'textarea_rows' => 10,
                                );
                                wp_editor( $content, $editor_id, $settings );
                                ?>
                            </td>
                            <!-- <td><textarea name="meu_plugin_novo_texto"></textarea></td> -->
                        </tr>
                    </table>
                    <?php submit_button('Editar Frase'); ?>
                </form>
            </div>
            <?php
        }

        if (isset($_POST['meu_plugin_editar_frase'], $_POST['meu_plugin_editar_texto'], $_POST['edit_index']) && check_admin_referer('meu_plugin_editar', 'meu_plugin_editar_verificar')) {
            $edit_index = intval($_POST['edit_index']);
            $frases = get_option('meu_plugin_personalizado_texto', array());

            if (isset($frases[$edit_index])) {
                $frases[$edit_index] = [
                    'frase' => sanitize_text_field($_POST['meu_plugin_editar_frase']),
                    'texto' => wp_kses_post($_POST['meu_plugin_editar_texto'])
                ];
                update_option('meu_plugin_personalizado_texto', $frases);
                // Redirecionar de volta para a página de configurações para evitar ressubmissões do formulário
                // Após processar a ação do formulário, como salvar as alterações
                // Definir mensagem de feedback na sessão
                $_SESSION['meu_plugin_feedback'] = 'Frase editada com sucesso.';
                echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-plugin-personalizado')) . '";</script>';
                exit;
            }
        }
    } else {
        // A lógica existente de adicionar e listar frases/textos

        // Lógica para adicionar novas frases
        if (isset($_POST['meu_plugin_nova_frase'], $_POST['meu_plugin_novo_texto']) && check_admin_referer('meu_plugin_nova_frase_adicionar', 'meu_plugin_nova_frase_verificar')) {
            $frases = get_option('meu_plugin_personalizado_texto', array());
            if (!is_array($frases))
                $frases = [];

            // Adiciona a nova frase e o novo texto como um array associativo
            $frases[] = [
                'frase' => sanitize_text_field($_POST['meu_plugin_nova_frase']),
                'texto' => wp_kses_post($_POST['meu_plugin_novo_texto']) // Alterado para wp_kses_post
                // 'texto' => sanitize_textarea_field($_POST['meu_plugin_novo_texto'])
            ];
            update_option('meu_plugin_personalizado_texto', $frases);
            $_SESSION['meu_plugin_feedback'] = 'Frase criada com sucesso.';
            echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-plugin-personalizado')) . '";</script>';
            exit;
        }

        // Lógica para excluir uma frase
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && check_admin_referer('delete_frase')) {
            $frases = get_option('meu_plugin_personalizado_texto', array());
            unset($frases[$_GET['frase_id']]);
            $frases = array_values($frases); // Reindexa o array
            update_option('meu_plugin_personalizado_texto', $frases);
            $_SESSION['meu_plugin_feedback'] = 'Frase excluída com sucesso.';
            echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-plugin-personalizado')) . '";</script>';
            exit;
        }

        // Recupere as frases salvas
        $frases = get_option('meu_plugin_personalizado_texto', array());
        // Lógica para exibir as frases existentes com opções de edição e exclusão
        ?>
        <div class="wrap">
            <h2>Meu Plugin Personalizado</h2>
            <?php
            if (isset($_SESSION['meu_plugin_feedback'])) {
                echo '<div class="updated"><p>' . $_SESSION['meu_plugin_feedback'] . '</p></div>';
                // Limpar a mensagem de feedback para não ser reexibida
                unset($_SESSION['meu_plugin_feedback']);
            }
            ?>
            <form method="post">
                <?php wp_nonce_field('meu_plugin_nova_frase_adicionar', 'meu_plugin_nova_frase_verificar'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Adicionar Nova Frase (Título):</th>
                        <td><input type="text" name="meu_plugin_nova_frase" value="" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Adicionar Novo Texto:</th>
                        <td>
                            <?php
                            $content = '';
                            $editor_id = 'meu_plugin_novo_texto';
                            $settings = array( 
                                'textarea_name' => 'meu_plugin_novo_texto', // Importante: precisa ser configurado para o nome do campo
                                'media_buttons' => true, // Habilita o botão de adicionar mídia
                                'textarea_rows' => 10,
                            );
                            wp_editor( $content, $editor_id, $settings );
                            ?>
                        </td>
                        <!-- <td><textarea name="meu_plugin_novo_texto"></textarea></td> -->
                    </tr>
                </table>
                <?php submit_button('Adicionar Frase'); ?>
            </form>
            <?php
            if(is_array($frases) && count($frases)>0){
            ?>
            <h3>Frases Salvas</h3>
            <ul>
                <?php
                foreach ($frases as $id => $dados) {
                    $delete_url = wp_nonce_url(add_query_arg(['action' => 'delete', 'frase_id' => $id]), 'delete_frase');
                    echo '<li>' . esc_html($dados['frase']) . ' - <a href="' . esc_url(admin_url('admin.php?page=meu-plugin-personalizado&edit=' . $id)) . '">Editar</a> - ' . ' <a href="' . esc_url($delete_url) . '">Excluir</a></li>';

                }            
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
}

// Registrar a configuração
add_action('admin_init', 'meu_plugin_personalizado_registrar_configuracao');

function meu_plugin_personalizado_registrar_configuracao() {
    register_setting('meu-plugin-personalizado-opcoes', 'meu_plugin_personalizado_texto');
}

// Adiciona a meta box
add_action('add_meta_boxes', 'meu_plugin_adicionar_meta_box');
function meu_plugin_adicionar_meta_box() {
    // Adiciona a meta box em postagens
    /*add_meta_box('meu-plugin-meta-box', 'Selecionar Frase Personalizada', 'meu_plugin_mostrar_meta_box', 'post', 'side', 'high');*/

    // Adiciona a mesma meta box em páginas
    add_meta_box('meu-plugin-meta-box', 'Frases Personalizadas', 'meu_plugin_mostrar_meta_box', 'page', 'side', 'high');
}

// Mostra a meta box
function meu_plugin_mostrar_meta_box($post) {
    echo '<p>Use o shortcode [meu_plugin_frase] em seu conteúdo para exibir as Frases Personalizadas.</p>';
}

function meu_plugin_shortcode_frase($atts) {
    global $post;
    $content = '';
    $frases = get_option('meu_plugin_personalizado_texto', array());
    foreach ($frases as $id => $dados) {
        $frase = isset($dados['frase']) ? $dados['frase'] : '';
        $texto = isset($dados['texto']) ? $dados['texto'] : '';

        // Ajuste a formatação conforme necessário
        $content .= '<h3>' . esc_html($frase) . '</h3>';
        $content .= do_shortcode(stripslashes(wp_kses_post($texto)));
    }
    return $content;
}
add_shortcode('meu_plugin_frase', 'meu_plugin_shortcode_frase');

add_action('wp_loaded', 'meu_plugin_iniciar_sessao');
function meu_plugin_iniciar_sessao() {
    if (!session_id()) {
        session_start();
    }
}
