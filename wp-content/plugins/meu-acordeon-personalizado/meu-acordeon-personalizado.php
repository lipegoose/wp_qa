<?php
/*
Plugin Name: Meu Acordeon Personalizado
Description: Um plugin simples de Acordeon personalizado.
Version: 1.0
Author: Mr.Goose
*/
# Author-URI: http://mrgoose.com.br
# Plugin-URI: http://mrgoose.com.br

// Definindo e inicializando $allowed_html globalmente
$GLOBALS['allowed_html'] = array(
    'iframe' => array(
        'width' => true,
        'height' => true,
        'src' => true,
        'frameborder' => true,
        'allow' => true,
        'allowfullscreen' => true,
        'title' => true,
    ),
    'img' => array(
        'width' => true,
        'height' => true,
        'src' => true,
        'class' => true,
        'alt' => true,
        'title' => true,
    ),
    // Adicione outras tags e atributos conforme necessário
);

// Hook para adicionar a página de configurações
add_action('admin_menu', 'meu_acordeon_personalizado_menu');

function meu_acordeon_personalizado_menu() {
    add_options_page(
        'Configurações do Meu Acordeon Personalizado', // Título da página
        'Meu Acordeon Personalizado', // Título do menu
        'manage_options', // Capacidade necessária para ver a opção
        'meu-acordeon-personalizado', // Slug do menu
        'meu_acordeon_personalizado_opcoes' // Função para exibir a página de opções
    );
}

// Função para exibir a página de configurações para gerenciar múltiplas frases
function meu_acordeon_personalizado_opcoes() {
    // Verifique a permissão do usuário
    if (!current_user_can('manage_options')) {
        wp_die('Você não tem permissão suficiente para acessar esta página.');
    }

    // Lista personalizada de tags e atributos permitidos
    global $allowed_html;

    if (isset($_GET['edit'])) {
        $edit_index = intval($_GET['edit']);
        $itens = get_option('meu_acordeon_personalizado_lista', array());
        if (isset($itens[$edit_index])) {
            // Mostrar formulário de edição com $itens[$edit_index]['frase'] e $itens[$edit_index]['texto']
            ?>
            <div class="wrap">
                <h2>Meu Acordeon Personalizado (Editar)</h2>
                <?php echo '<a href="' . esc_url(admin_url('admin.php?page=meu-acordeon-personalizado')) . '" class="button">Voltar para a Lista</a>'; ?>
                <form method="post">
                    <input type="hidden" name="edit_index" value="<?php echo $edit_index; ?>">
                    <?php wp_nonce_field('meu_acordeon_editar', 'meu_acordeon_editar_verificar'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Editar Título:</th>
                            <td><input type="text" name="meu_acordeon_editar_frase" value="<?php echo $itens[$edit_index]['frase']; ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Editar Texto:</th>
                            <td>
                                <?php
                                $content = stripslashes($itens[$edit_index]['texto']);
                                $editor_id = 'meu_acordeon_editar_texto';
                                $settings = array( 
                                    'textarea_name' => 'meu_acordeon_editar_texto', // Importante: precisa ser configurado para o nome do campo
                                    'media_buttons' => true, // Habilita o botão de adicionar mídia
                                    'textarea_rows' => 10,
                                );
                                wp_editor( $content, $editor_id, $settings );
                                ?>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Editar Item'); ?>
                </form>
            </div>
            <?php
        }

        if (isset($_POST['meu_acordeon_editar_frase'], $_POST['meu_acordeon_editar_texto'], $_POST['edit_index']) && check_admin_referer('meu_acordeon_editar', 'meu_acordeon_editar_verificar')) {
            $edit_index = intval($_POST['edit_index']);
            $itens = get_option('meu_acordeon_personalizado_lista', array());

            if (isset($itens[$edit_index])) {

                $texto_limpo = wp_kses($_POST['meu_acordeon_editar_texto'], $allowed_html);

                $itens[$edit_index] = [
                    'frase' => sanitize_text_field($_POST['meu_acordeon_editar_frase']),
                    // 'texto' => wp_kses_post($_POST['meu_acordeon_editar_texto'])
                    'texto' => $texto_limpo,
                ];
                update_option('meu_acordeon_personalizado_lista', $itens);
                // Redirecionar de volta para a página de configurações para evitar ressubmissões do formulário
                // Após processar a ação do formulário, como salvar as alterações
                // Definir mensagem de feedback na sessão
                $_SESSION['meu_acordeon_feedback'] = 'Item do Acordeon editado com sucesso.';
                echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-acordeon-personalizado')) . '";</script>';
                exit;
            }
        }
    } else if (isset($_GET['novo'])) {
        // A lógica existente de adicionar itens

        // Lógica para adicionar novos itens
        if (isset($_POST['meu_acordeon_nova_frase'], $_POST['meu_acordeon_novo_texto']) && check_admin_referer('meu_acordeon_nova_frase_adicionar', 'meu_acordeon_nova_frase_verificar')) {
            $itens = get_option('meu_acordeon_personalizado_lista', array());
            if (!is_array($itens))
                $itens = [];

            $texto_limpo = wp_kses($_POST['meu_acordeon_novo_texto'], $allowed_html);

            // Adiciona a nova frase e o novo texto como um array associativo
            $itens[] = [
                'frase' => sanitize_text_field($_POST['meu_acordeon_nova_frase']),
                // 'texto' => wp_kses_post($_POST['meu_acordeon_novo_texto']) // Alterado para wp_kses_post
                'texto' => $texto_limpo
            ];
            update_option('meu_acordeon_personalizado_lista', $itens);
            $_SESSION['meu_acordeon_feedback'] = 'Novo Item do Acordeon criado com sucesso.';
            echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-acordeon-personalizado')) . '";</script>';
            exit;
        }
        ?>
        <div class="wrap">
            <h2>Meu Acordeon Personalizado (Novo)</h2>
            <?php echo '<a href="' . esc_url(admin_url('admin.php?page=meu-acordeon-personalizado')) . '" class="button">Voltar para a Lista</a>'; ?>
            <form method="post">
                <?php wp_nonce_field('meu_acordeon_nova_frase_adicionar', 'meu_acordeon_nova_frase_verificar'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Adicionar Novo Título:</th>
                        <td><input type="text" name="meu_acordeon_nova_frase" value="" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Adicionar Novo Texto:</th>
                        <td>
                            <?php
                            $content = '';
                            $editor_id = 'meu_acordeon_novo_texto';
                            $settings = array( 
                                'textarea_name' => 'meu_acordeon_novo_texto', // Importante: precisa ser configurado para o nome do campo
                                'media_buttons' => true, // Habilita o botão de adicionar mídia
                                'textarea_rows' => 10,
                            );
                            wp_editor( $content, $editor_id, $settings );
                            ?>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Adicionar Item'); ?>
            </form>
        </div>
        <?php
    } else {
        // A lógica existente de excluir e listar itens

        // Lógica para excluir um item
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && check_admin_referer('delete_frase')) {
            $itens = get_option('meu_acordeon_personalizado_lista', array());
            unset($itens[$_GET['frase_id']]);
            $itens = array_values($itens); // Reindexa o array
            update_option('meu_acordeon_personalizado_lista', $itens);
            $_SESSION['meu_acordeon_feedback'] = 'Item do Acordeon excluído com sucesso.';
            echo '<script>window.location.href="' . esc_js(admin_url('admin.php?page=meu-acordeon-personalizado')) . '";</script>';
            exit;
        }

        // Recupere os itens salvos
        $itens = get_option('meu_acordeon_personalizado_lista', array());
        // Lógica para exibir os itens existentes com opções de edição e exclusão
        ?>
        <div class="wrap">
            <h2>Meu Acordeon Personalizado (Lista)</h2>
            <?php
            if (isset($_SESSION['meu_acordeon_feedback'])) {
                echo '<div class="updated"><p>' . $_SESSION['meu_acordeon_feedback'] . '</p></div>';
                // Limpar a mensagem de feedback para não ser reexibida
                unset($_SESSION['meu_acordeon_feedback']);
            }

            echo '<a href="' . esc_url(admin_url('admin.php?page=meu-acordeon-personalizado&novo')) . '" class="button">Criar novo Item</a>';

            if(is_array($itens) && count($itens)>0){
            ?>
            <h3>Itens Salvos</h3>
            <ul>
                <?php
                foreach ($itens as $id => $dados) {
                    $delete_url = wp_nonce_url(add_query_arg(['action' => 'delete', 'frase_id' => $id]), 'delete_frase');
                    echo '<li>' . '<a href="' . esc_url(admin_url('admin.php?page=meu-acordeon-personalizado&edit=' . $id)) . '">Editar</a> - ' . ' <a href="' . esc_url($delete_url) . '">Excluir</a> - ' . esc_html($dados['frase']) .'</li>';

                }            
                ?>
            </ul>
            <?php
            } // close if(count($itens)>0)
            else{
            ?>
            <h3>Nenhum Acordeon criado ainda</h3>
            <?php
            } // close else if(count($itens)>0)
            ?>
        </div>
        <?php
    }
}

// Registrar a configuração
add_action('admin_init', 'meu_acordeon_personalizado_registrar_configuracao');

function meu_acordeon_personalizado_registrar_configuracao() {
    register_setting('meu-acordeon-personalizado-opcoes', 'meu_acordeon_personalizado_lista');
}

// Adiciona a meta box
add_action('add_meta_boxes', 'meu_acordeon_adicionar_meta_box');
function meu_acordeon_adicionar_meta_box() {
    // Adiciona a meta box em postagens
    /*add_meta_box('meu-acordeon-meta-box', 'Meu Acordeon Personalizado', 'meu_acordeon_mostrar_meta_box', 'post', 'side', 'high');*/

    // Adiciona a mesma meta box em páginas
    add_meta_box('meu-acordeon-meta-box', 'Meu Acordeon Personalizado', 'meu_acordeon_mostrar_meta_box', 'page', 'side', 'high');
}

// Mostra a meta box
function meu_acordeon_mostrar_meta_box($post) {
    echo '<p>Use o shortcode [meu_acordeon_personalizado] em seu conteúdo para exibir o seu Acordeon Personalizado.</p>';
}

function meu_acordeon_personalizado_shortcode($atts) {
    global $post, $allowed_html;
    $content = '';
    $itens = get_option('meu_acordeon_personalizado_lista', array());
    foreach ($itens as $id => $dados) {
        $frase = isset($dados['frase']) ? $dados['frase'] : '';
        $texto = isset($dados['texto']) ? $dados['texto'] : '';

        $texto_limpo = wp_kses($texto, $allowed_html);

        // Ajuste a formatação conforme necessário
        $content .= '<h3>' . esc_html($frase) . '</h3>';
        // $content .= do_shortcode(stripslashes(wp_kses_post(nl2br($texto))));
        $content .= do_shortcode(stripslashes(nl2br($texto_limpo)));
    }
    return $content;
}
add_shortcode('meu_acordeon_personalizado', 'meu_acordeon_personalizado_shortcode');

add_action('wp_loaded', 'meu_acordeon_iniciar_sessao');
function meu_acordeon_iniciar_sessao() {
    if (!session_id()) {
        session_start();
    }
}
