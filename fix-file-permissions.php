<?php
session_start();

// Nome do script
$scriptName = basename(__FILE__);

// Função para gerenciar traduções
function getTranslations($lang) {
    $translations = [
        'en' => [
            'select_language' => 'Select Language:',
            'create_log' => 'Create Log File',
            'start_button' => 'Start Permission Adjustment',
            'remove_button' => 'Remove This Script',
            'remove_confirm' => 'Are you sure you want to remove this script?',
            'starting_process' => 'Starting process...',
            'removing_script' => 'Removing script...',
            'error_connecting' => '⚠️ Error connecting to the server. Status: ',
            'process_completed' => 'Process completed.',
            'script_removed' => '🗑️ Script successfully removed.',
            'script_remove_error' => '⚠️ Error removing the script.',
            'script_already_removed' => '🔄 The script has already been removed.',
            'invalid_action' => 'Invalid action.',
            'log_created' => 'Log file created: ',
            'log_failed' => '⚠️ Failed to create log file.',
            'htaccess_security_added' => '🔒 Security directives added to <code>.htaccess</code> in',
            'htaccess_security_exists' => '<code>.htaccess</code> in already contains security directives.',
            'htaccess_created' => '<code>.htaccess</code> created with security directives in',
            'htaccess_error_created' => '⚠️ Error creating <code>.htaccess</code> in',
            'php_block_added' => '🔒 PHP blocking directives added to <code>.htaccess</code> in',
            'php_block_exists' => '<code>.htaccess</code> in already contains PHP blocking directives.',
            'php_block_created' => '<code>.htaccess</code> created with PHP blocking directives in',
            'php_block_error_created' => '⚠️ Error creating <code>.htaccess</code> in',
            'htaccess_removed' => '🗑️ <code>.htaccess</code> file removed from:',
            'htaccess_remove_error' => '⚠️ Error removing <code>.htaccess</code> from:',
            'permissions_applied_dir' => '✅ Permission <code>0755</code> applied to directory:',
            'permissions_error_dir' => '⚠️ Error applying permission <code>0755</code> to directory:',
            'permissions_adjusted' => '🔄 Permissions adjusted for all files in:',
            'permissions_error_file' => '⚠️ Error applying permission <code>{perm}</code> to file:',
            'permissions_applied_file' => '✅ Permission <code>{perm}</code> applied to file:',
        ],
        'pt' => [
            'select_language' => 'Selecionar Idioma:',
            'create_log' => 'Criar Arquivo de Log',
            'start_button' => 'Iniciar Ajuste de Permissões',
            'remove_button' => 'Remover Este Script',
            'remove_confirm' => 'Tem certeza de que deseja remover este script?',
            'starting_process' => 'Iniciando processo...',
            'removing_script' => 'Removendo script...',
            'error_connecting' => '⚠️ Erro ao conectar com o servidor. Status: ',
            'process_completed' => 'Processo concluído.',
            'script_removed' => '🗑️ Script removido com sucesso.',
            'script_remove_error' => '⚠️ Erro ao remover o script.',
            'script_already_removed' => '🔄 O script já foi removido.',
            'invalid_action' => 'Ação inválida.',
            'log_created' => 'Arquivo de log criado: ',
            'log_failed' => '⚠️ Falha ao criar o arquivo de log.',
            'htaccess_security_added' => '🔒 Diretivas de segurança adicionadas ao <code>.htaccess</code> em',
            'htaccess_security_exists' => '<code>.htaccess</code> em já contém diretivas de segurança.',
            'htaccess_created' => '<code>.htaccess</code> criado com diretivas de segurança em',
            'htaccess_error_created' => '⚠️ Erro ao criar <code>.htaccess</code> em',
            'php_block_added' => '🔒 Diretivas para bloquear PHP adicionadas ao <code>.htaccess</code> em',
            'php_block_exists' => '<code>.htaccess</code> em já contém diretivas para bloquear PHP.',
            'php_block_created' => '<code>.htaccess</code> criado com diretivas para bloquear PHP em',
            'php_block_error_created' => '⚠️ Erro ao criar <code>.htaccess</code> em',
            'htaccess_removed' => '🗑️ Arquivo <code>.htaccess</code> removido de:',
            'htaccess_remove_error' => '⚠️ Erro ao remover <code>.htaccess</code> de:',
            'permissions_applied_dir' => '✅ Permissão <code>0755</code> aplicada ao diretório:',
            'permissions_error_dir' => '⚠️ Erro ao aplicar permissão <code>0755</code> ao diretório:',
            'permissions_adjusted' => '🔄 Permissões ajustadas para todos os arquivos em:',
            'permissions_error_file' => '⚠️ Erro ao aplicar permissão <code>{perm}</code> ao arquivo:',
            'permissions_applied_file' => '✅ Permissão <code>{perm}</code> aplicada ao arquivo:',
        ],
        'es' => [
            'select_language' => 'Seleccionar Idioma:',
            'create_log' => 'Crear Archivo de Log',
            'start_button' => 'Iniciar Ajuste de Permisos',
            'remove_button' => 'Eliminar Este Script',
            'remove_confirm' => '¿Estás seguro de que deseas eliminar este script?',
            'starting_process' => 'Iniciando proceso...',
            'removing_script' => 'Eliminando script...',
            'error_connecting' => '⚠️ Error al conectar con el servidor. Estado: ',
            'process_completed' => 'Proceso completado.',
            'script_removed' => '🗑️ Script eliminado con éxito.',
            'script_remove_error' => '⚠️ Error al eliminar el script.',
            'script_already_removed' => '🔄 El script ya ha sido eliminado.',
            'invalid_action' => 'Acción inválida.',
            'log_created' => 'Archivo de log creado: ',
            'log_failed' => '⚠️ Falta al crear el archivo de log.',
            'htaccess_security_added' => '🔒 Directivas de seguridad añadidas a <code>.htaccess</code> en',
            'htaccess_security_exists' => '<code>.htaccess</code> en ya contiene directivas de seguridad.',
            'htaccess_created' => '<code>.htaccess</code> creado con directivas de seguridad en',
            'htaccess_error_created' => '⚠️ Error al crear <code>.htaccess</code> en',
            'php_block_added' => '🔒 Directivas para bloquear PHP añadidas a <code>.htaccess</code> en',
            'php_block_exists' => '<code>.htaccess</code> en ya contiene directivas para bloquear PHP.',
            'php_block_created' => '<code>.htaccess</code> creado con directivas para bloquear PHP en',
            'php_block_error_created' => '⚠️ Error al crear <code>.htaccess</code> en',
            'htaccess_removed' => '🗑️ Archivo <code>.htaccess</code> eliminado de:',
            'htaccess_remove_error' => '⚠️ Error al eliminar <code>.htaccess</code> de:',
            'permissions_applied_dir' => '✅ Permiso <code>0755</code> aplicado al directorio:',
            'permissions_error_dir' => '⚠️ Error al aplicar permiso <code>0755</code> al directorio:',
            'permissions_adjusted' => '🔄 Permisos ajustados para todos los archivos en:',
            'permissions_error_file' => '⚠️ Error al aplicar permiso <code>{perm}</code> al archivo:',
            'permissions_applied_file' => '✅ Permiso <code>{perm}</code> aplicado al archivo:',
        ]
    ];

    return isset($translations[$lang]) ? $translations[$lang] : $translations['en'];
}

// Obter idioma selecionado via GET ou POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lang = isset($_POST['language']) ? $_POST['language'] : 'en';
} else {
    $lang = 'en';
}

$messages = getTranslations($lang);

// Processar solicitações AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'getTranslations') {
            $requestedLang = isset($_POST['lang']) ? $_POST['lang'] : 'en';
            $translatedMessages = getTranslations($requestedLang);
            echo json_encode(['success' => true, 'messages' => $translatedMessages]);
            exit;
        }

        if ($action === 'setPermissions') {
            // Definir a localização inicial
            $initialDir = __DIR__;
            $selectedHeaders = isset($_POST['headers']) ? $_POST['headers'] : [];
            $createLog = isset($_POST['createLog']) ? true : false;

            // Iniciar o processo e coletar feedback
            ob_start();
            setPermissionsAndUpdateHtaccess($initialDir, $selectedHeaders, $createLog, $messages);
            $feedback = ob_get_clean();

            // Se criou o log, armazenar o link na sessão
            if ($createLog && isset($logLink)) {
                $_SESSION['log_link'] = $logLink;
            }

            echo json_encode(['success' => true, 'feedback' => $feedback]);
            exit;
        }

        if ($action === 'removeScript') {
            // Remover o script e retornar o resultado
            ob_start();
            removeSelf($messages);
            $feedback = ob_get_clean();
            echo json_encode(['success' => true, 'feedback' => $feedback]);
            exit;
        }

        // Ação inválida
        echo json_encode(['success' => false, 'message' => $messages['invalid_action']]);
        exit;
    }

    // Ação inválida
    echo json_encode(['success' => false, 'message' => $messages['invalid_action']]);
    exit;
}

// Função principal para alterar permissões e atualizar .htaccess
function setPermissionsAndUpdateHtaccess($dir, $selectedHeaders, $createLog, $messages)
{
    global $scriptName;

    // Gerar um nome único para o arquivo de log se necessário
    if ($createLog) {
        $uniqueHash = bin2hex(random_bytes(16)); // 32 caracteres hexadecimais
        $logFile = $dir . DIRECTORY_SEPARATOR . "fix-file-permissions-$uniqueHash.log";
        $logLink = "fix-file-permissions-$uniqueHash.log";
        $logHandle = fopen($logFile, 'a');
        if ($logHandle) {
            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] " . $messages['starting_process'] . " $dir.\n");
        } else {
            echo $messages['log_failed'] . "\n";
            flush();
            $createLog = false; // Desativar log se não puder ser criado
        }
    }

    echo $messages['starting_process'] . " <strong>$dir</strong>.<br>";
    flush();
    if ($createLog && isset($logHandle)) {
        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] " . $messages['starting_process'] . " $dir.\n");
    }

    // Aplicar permissões na raiz
    applyPermissions($dir, $createLog, $messages, $logHandle ?? null);

    // Encontrar instalações do WordPress em subpastas e na raiz
    $wordpressDirs = findWordpressDirectories($dir);

    foreach ($wordpressDirs as $wpDir) {
        echo $messages['processing_wp'] . " <strong>$wpDir</strong>.<br>";
        flush();
        if ($createLog && isset($logHandle)) {
            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] " . $messages['processing_wp'] . " $wpDir.\n");
        }

        // Aplicar permissões nas pastas do WordPress
        applyPermissions($wpDir, $createLog, $messages, $logHandle ?? null);

        // Atualizar ou criar .htaccess nas pastas do WordPress
        updateHtaccess($wpDir, $selectedHeaders, $wpDir === $dir, $createLog, $messages, $logHandle ?? null);

        // Opcional: Criar .htaccess para bloquear PHP em wp-content/uploads
        $uploadsDir = $wpDir . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads';
        if (is_dir($uploadsDir)) {
            createUploadsHtaccess($uploadsDir, $createLog, $messages, $logHandle ?? null);
        }
    }

    // Remover .htaccess de diretórios que não são WordPress roots ou suas subpastas
    removeOtherHtaccess($dir, $wordpressDirs, $createLog, $messages, $logHandle ?? null);

    if ($createLog && isset($logHandle)) {
        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] " . $messages['process_completed'] . ".\n");
        fclose($logHandle);
        // Armazenar o link do log na sessão para exibição após o redirecionamento
        $_SESSION['log_link'] = $logLink;
    }

    echo $messages['process_completed'] . ".<br>";
    flush();
}

// Função para aplicar permissões por diretório
function applyPermissions($dir, $createLog, $messages, $logHandle = null)
{
    global $scriptName;

    // Aplicar permissões ao próprio diretório
    if (!@chmod($dir, 0755)) {
        echo "⚠️ " . $messages['permissions_error_dir'] . " <strong>$dir</strong>.<br>";
        flush();
        if ($createLog && $logHandle) {
            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['permissions_error_dir'] . " $dir.\n");
        }
    } else {
        echo "✅ " . $messages['permissions_applied_dir'] . " <strong>$dir</strong>.<br>";
        flush();
        if ($createLog && $logHandle) {
            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ✅ " . $messages['permissions_applied_dir'] . " $dir.\n");
        }
    }

    // Iterar sobre os itens no diretório
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === $scriptName) {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($fullPath)) {
            // Aplicar permissões ao subdiretório e recursivamente
            applyPermissions($fullPath, $createLog, $messages, $logHandle);
        } else {
            // Definir permissões para arquivos
            $basename = strtolower(basename($fullPath));
            if ($basename === 'wp-config.php') {
                $perm = 0400; // Permissões mais restritivas para wp-config.php
            } elseif ($basename === '.htaccess') {
                $perm = 0644;
            } else {
                $perm = 0644;
            }

            if (!@chmod($fullPath, $perm)) {
                echo "⚠️ " . str_replace('{perm}', decoct($perm), $messages['permissions_error_file']) . " <strong>$fullPath</strong>.<br>";
                flush();
                if ($createLog && $logHandle) {
                    fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . str_replace('{perm}', decoct($perm), $messages['permissions_error_file']) . " $fullPath.\n");
                }
            } else {
                // Mensagem por arquivo foi removida para reduzir o uso de memória
                // Apenas confirmar que o arquivo foi processado
            }
        }
    }

    // Após processar todos os arquivos, informar que o diretório foi processado
    echo "🔄 " . $messages['permissions_adjusted'] . " <strong>$dir</strong>.<br><br>";
    flush();
    if ($createLog && $logHandle) {
        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] 🔄 " . $messages['permissions_adjusted'] . " $dir.\n\n");
    }
}

// Função para atualizar ou criar .htaccess
function updateHtaccess($dir, $selectedHeaders, $isRoot, $createLog, $messages, $logHandle = null)
{
    global $scriptName;
    $htaccessFile = $dir . DIRECTORY_SEPARATOR . '.htaccess';

    // Diretivas de segurança
    $securityDirectives = "\n# Security Directives Added by Script\n<FilesMatch \"^(wp-config\\.php|\\.htaccess)$\">\n    Require all denied\n</FilesMatch>\n";
    // Permitir acesso ao script
    $securityDirectives .= "<Files \"$scriptName\">\n    Require all granted\n</Files>\n";

    // Adicionar Headers se selecionados
    if (!empty($selectedHeaders)) {
        foreach ($selectedHeaders as $header) {
            switch ($header) {
                case 'Strict-Transport-Security':
                    $securityDirectives .= "Header always set Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\"\n";
                    break;
                case 'Content-Security-Policy':
                    // Adiciona a diretiva sugerida
                    $securityDirectives .= "Header always set Content-Security-Policy \"upgrade-insecure-requests\"\n";
                    break;
                case 'X-Frame-Options':
                    $securityDirectives .= "Header set X-Frame-Options \"SAMEORIGIN\"\n";
                    break;
                case 'X-Content-Type-Options':
                    $securityDirectives .= "Header set X-Content-Type-Options \"nosniff\"\n";
                    break;
                case 'Referrer-Policy':
                    $securityDirectives .= "Header set Referrer-Policy \"no-referrer-when-downgrade\"\n";
                    break;
                case 'Permissions-Policy':
                    $securityDirectives .= "Header set Permissions-Policy \"geolocation=(self), microphone=()\"\n";
                    break;
            }
        }
    }

    // Diretivas comuns
    $securityDirectives .= "# Block access to XML-RPC\n<Files xmlrpc.php>\n    Require all denied\n</Files>\n# Disable directory listing\nOptions -Indexes\n";

    // Verificar se o .htaccess existe
    if (file_exists($htaccessFile)) {
        if (is_writable($htaccessFile)) {
            $htaccessContent = file_get_contents($htaccessFile);
            // Adicionar diretivas se ainda não existirem
            if (strpos($htaccessContent, '# Security Directives Added by Script') === false) {
                // Ajustar RewriteBase e RewriteRule com base na localização do WordPress
                if ($isRoot) {
                    $htaccessContent = preg_replace(
                        '/(RewriteBase\s+)([^ \n]+)/i',
                        '$1/',
                        $htaccessContent
                    );
                    $htaccessContent = preg_replace(
                        '/(RewriteRule\s+\. )(\/[^ \n]+)(\s+\[L\])/i',
                        '$1/index.php$3',
                        $htaccessContent
                    );
                } else {
                    // Obter o caminho relativo da subpasta
                    $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($dir));
                    $relativePath = '/' . trim($relativePath, '/') . '/';
                    $htaccessContent = preg_replace(
                        '/(RewriteBase\s+)([^ \n]+)/i',
                        '$1' . $relativePath,
                        $htaccessContent
                    );
                    $htaccessContent = preg_replace(
                        '/(RewriteRule\s+\. )(\/[^ \n]+)(\s+\[L\])/i',
                        '$1' . $relativePath . 'index.php$3',
                        $htaccessContent
                    );
                }

                // Adicionar as diretivas de segurança
                $htaccessContent .= $securityDirectives;

                if (file_put_contents($htaccessFile, $htaccessContent)) {
                    echo "🔒 " . $messages['htaccess_security_added'] . " <code>.htaccess</code> em <strong>$dir</strong>.<br>";
                    flush();
                    if ($createLog && $logHandle) {
                        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] 🔒 " . $messages['htaccess_security_added'] . " .htaccess em $dir.\n");
                    }
                } else {
                    echo "⚠️ " . $messages['htaccess_error_created'] . " <strong>$dir</strong>.<br>";
                    flush();
                    if ($createLog && $logHandle) {
                        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['htaccess_error_created'] . " $dir.\n");
                    }
                }
            } else {
                echo "<code>.htaccess</code> em <strong>$dir</strong> " . $messages['htaccess_security_exists'] . ".<br>";
                flush();
                if ($createLog && $logHandle) {
                    fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] <code>.htaccess</code> em $dir " . $messages['htaccess_security_exists'] . ".\n");
                }
            }
        } else {
            echo "⚠️ " . $messages['htaccess_error_created'] . " <strong>$dir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['htaccess_error_created'] . " $dir.\n");
            }
        }
    } else {
        // Detectar a localização do WordPress para ajustar as regras de reescrita
        if ($isRoot) {
            // WordPress na raiz
            $rewriteBase = '/';
            $rewriteRule = '/index.php [L]';
        } else {
            // WordPress em subpasta
            $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($dir));
            $relativePath = '/' . trim($relativePath, '/') . '/';
            $rewriteBase = $relativePath;
            $rewriteRule = $relativePath . 'index.php [L]';
        }

        // Criar um novo .htaccess com regras de reescrita apropriadas
        $defaultHtaccess = "# Início do .htaccess\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase $rewriteBase\nRewriteRule ^index\\.php$ - [L]\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule . $rewriteRule\n</IfModule>\n" . $securityDirectives;

        if (file_put_contents($htaccessFile, $defaultHtaccess)) {
            echo "<code>.htaccess</code> " . $messages['htaccess_created'] . " <strong>$dir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] <code>.htaccess</code> " . $messages['htaccess_created'] . " $dir.\n");
            }
        } else {
            echo "⚠️ " . $messages['htaccess_error_created'] . " <strong>$dir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['htaccess_error_created'] . " $dir.\n");
            }
        }
    }
}

// Função para criar .htaccess em wp-content/uploads para bloquear PHP
function createUploadsHtaccess($uploadsDir, $createLog, $messages, $logHandle = null)
{
    global $scriptName;
    $htaccessFile = $uploadsDir . DIRECTORY_SEPARATOR . '.htaccess';

    // Diretivas para bloquear a execução de PHP
    $blockPhp = "\n# Block PHP execution in this directory\n<FilesMatch \"\\.php$\">\n    Require all denied\n</FilesMatch>\n";

    if (file_exists($htaccessFile)) {
        if (is_writable($htaccessFile)) {
            $htaccessContent = file_get_contents($htaccessFile);
            if (strpos($htaccessContent, '# Block PHP execution in this directory') === false) {
                $htaccessContent .= $blockPhp;
                if (file_put_contents($htaccessFile, $htaccessContent)) {
                    echo "🔒 " . $messages['php_block_added'] . " <code>.htaccess</code> em <strong>$uploadsDir</strong>.<br>";
                    flush();
                    if ($createLog && $logHandle) {
                        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] 🔒 " . $messages['php_block_added'] . " .htaccess em $uploadsDir.\n");
                    }
                } else {
                    echo "⚠️ " . $messages['php_block_error_created'] . " <strong>$uploadsDir</strong>.<br>";
                    flush();
                    if ($createLog && $logHandle) {
                        fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['php_block_error_created'] . " $uploadsDir.\n");
                    }
                }
            } else {
                echo "<code>.htaccess</code> em <strong>$uploadsDir</strong> " . $messages['php_block_exists'] . ".<br>";
                flush();
                if ($createLog && $logHandle) {
                    fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] <code>.htaccess</code> em $uploadsDir " . $messages['php_block_exists'] . ".\n");
                }
            }
        } else {
            echo "⚠️ " . $messages['php_block_error_created'] . " <strong>$uploadsDir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['php_block_error_created'] . " $uploadsDir.\n");
            }
        }
    } else {
        // Criar um novo .htaccess
        $defaultHtaccess = "# Início do .htaccess\n" . $blockPhp;
        if (file_put_contents($htaccessFile, $defaultHtaccess)) {
            echo "<code>.htaccess</code> " . $messages['php_block_created'] . " <strong>$uploadsDir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] <code>.htaccess</code> " . $messages['php_block_created'] . " $uploadsDir.\n");
            }
        } else {
            echo "⚠️ " . $messages['php_block_error_created'] . " <strong>$uploadsDir</strong>.<br>";
            flush();
            if ($createLog && $logHandle) {
                fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['php_block_error_created'] . " $uploadsDir.\n");
            }
        }
    }
}

// Função para encontrar diretórios do WordPress
function findWordpressDirectories($dir)
{
    global $scriptName;
    $wordpressDirs = [];
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === $scriptName) {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($fullPath)) {
            // Verificar se wp-config.php existe no diretório e se as pastas essenciais existem
            if (
                file_exists($fullPath . DIRECTORY_SEPARATOR . 'wp-config.php') &&
                is_dir($fullPath . DIRECTORY_SEPARATOR . 'wp-content') &&
                is_dir($fullPath . DIRECTORY_SEPARATOR . 'wp-admin') &&
                is_dir($fullPath . DIRECTORY_SEPARATOR . 'wp-includes')
            ) {
                $wordpressDirs[] = $fullPath;
            }
            // Recursivo para subpastas
            $wordpressDirs = array_merge($wordpressDirs, findWordpressDirectories($fullPath));
        }
    }

    // Verificar se o WordPress está na raiz
    if (
        file_exists($dir . DIRECTORY_SEPARATOR . 'wp-config.php') &&
        is_dir($dir . DIRECTORY_SEPARATOR . 'wp-content') &&
        is_dir($dir . DIRECTORY_SEPARATOR . 'wp-admin') &&
        is_dir($dir . DIRECTORY_SEPARATOR . 'wp-includes') &&
        !in_array($dir, $wordpressDirs)
    ) {
        $wordpressDirs[] = $dir;
    }

    return $wordpressDirs;
}

// Função para remover .htaccess fora das instalações do WordPress e da raiz
function removeOtherHtaccess($dir, $wordpressDirs, $createLog, $messages, $logHandle = null)
{
    global $scriptName;
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === $scriptName) {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($fullPath)) {
            // Remover .htaccess se existir e não for parte do WordPress
            $htaccessFile = $fullPath . DIRECTORY_SEPARATOR . '.htaccess';
            if (file_exists($htaccessFile)) {
                // Verificar se está dentro de uma instalação do WordPress
                $isWordpress = false;
                foreach ($wordpressDirs as $wpDir) {
                    if (strpos(realpath($fullPath), realpath($wpDir)) === 0) {
                        $isWordpress = true;
                        break;
                    }
                }

                if (!$isWordpress) {
                    if (@unlink($htaccessFile)) {
                        echo "🗑️ " . $messages['htaccess_removed'] . " <strong>$fullPath</strong>.<br>";
                        flush();
                        if ($createLog && $logHandle) {
                            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] 🗑️ " . $messages['htaccess_removed'] . " $fullPath.\n");
                        }
                    } else {
                        echo "⚠️ " . $messages['htaccess_remove_error'] . " <strong>$fullPath</strong>.<br>";
                        flush();
                        if ($createLog && $logHandle) {
                            fwrite($logHandle, "[" . date('Y-m-d H:i:s') . "] ⚠️ " . $messages['htaccess_remove_error'] . " $fullPath.\n");
                        }
                    }
                }
            }
        }
    }
}

// Função para remover o próprio script
function removeSelf($messages)
{
    $scriptPath = __FILE__;

    if (file_exists($scriptPath)) {
        if (@unlink($scriptPath)) {
            echo "🗑️ " . $messages['script_removed'] . "<br>";
            flush();
        } else {
            echo "⚠️ " . $messages['script_remove_error'] . "<br>";
            flush();
        }
    } else {
        echo "🔄 " . $messages['script_already_removed'] . "<br>";
        flush();
    }
}

// Função para processar solicitações de remoção
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'removeScript') {
    ob_start();
    removeSelf($messages);
    $feedback = ob_get_clean();
    echo json_encode(['success' => true, 'feedback' => $feedback]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress Security</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            overflow-y: auto;
            max-height: 90vh;
        }

        h1 {
            color: #333333;
            margin-bottom: 25px;
            font-size: 24px;
        }

        #feedback {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
            color: #555555;
            white-space: pre-wrap;
        }

        .btn {
            padding: 12px 25px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-permissions {
            background-color: #28a745;
            color: #ffffff;
        }

        .btn-permissions:hover {
            background-color: #218838;
        }

        .btn-remove {
            background-color: #dc3545;
            color: #ffffff;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }

        .btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .headers,
        .options {
            text-align: left;
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
        }

        .headers label,
        .options label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333333;
        }

        .headers input[type="checkbox"],
        .options input[type="radio"] {
            margin-right: 10px;
        }

        .language-options {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars('WordPress Security'); ?></h1>
        <div class="language-options">
            <?php echo htmlspecialchars($messages['select_language']); ?>
            <select id="languageSelect">
                <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>English</option>
                <option value="pt" <?php if ($lang == 'pt') echo 'selected'; ?>>Português</option>
                <option value="es" <?php if ($lang == 'es') echo 'selected'; ?>>Español</option>
            </select>
        </div>
        <div class="options">
            <label><input type="checkbox" name="createLog" id="createLog"> <?php echo htmlspecialchars($messages['create_log']); ?></label>
        </div>
        <div class="headers">
            <label><input type="checkbox" name="headers[]" value="Strict-Transport-Security"> Strict-Transport-Security</label>
            <label><input type="checkbox" name="headers[]" value="Content-Security-Policy"> Content-Security-Policy (upgrade-insecure-requests)</label>
            <label><input type="checkbox" name="headers[]" value="X-Frame-Options"> X-Frame-Options</label>
            <label><input type="checkbox" name="headers[]" value="X-Content-Type-Options"> X-Content-Type-Options</label>
            <label><input type="checkbox" name="headers[]" value="Referrer-Policy"> Referrer-Policy</label>
            <label><input type="checkbox" name="headers[]" value="Permissions-Policy"> Permissions-Policy</label>
        </div>
        <button id="startButton" class="btn btn-permissions"><?php echo htmlspecialchars($messages['start_button']); ?></button>
        <button id="removeButton" class="btn btn-remove"><?php echo htmlspecialchars($messages['remove_button']); ?></button>
        <div id="feedback"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const languageSelect = document.getElementById('languageSelect');
            const startButton = document.getElementById('startButton');
            const removeButton = document.getElementById('removeButton');
            const feedback = document.getElementById('feedback');
            const createLogCheckbox = document.getElementById('createLog');

            // Função para atualizar as traduções
            languageSelect.addEventListener('change', function () {
                const selectedLang = this.value;
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=getTranslations&lang=' + encodeURIComponent(selectedLang)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const messages = data.messages;
                        // Atualizar textos da interface
                        document.querySelector('.language-options').innerHTML = `${messages['select_language']} <select id="languageSelect">` +
                            `<option value="en" ${selectedLang === 'en' ? 'selected' : ''}>English</option>` +
                            `<option value="pt" ${selectedLang === 'pt' ? 'selected' : ''}>Português</option>` +
                            `<option value="es" ${selectedLang === 'es' ? 'selected' : ''}>Español</option>` +
                            `</select>`;

                        document.querySelector('.options label').innerHTML = `<input type="checkbox" name="createLog" id="createLog" ${createLogCheckbox.checked ? 'checked' : ''}> ${messages['create_log']}`;
                        const headerLabels = document.querySelectorAll('.headers label');
                        const headers = ['Strict-Transport-Security', 'Content-Security-Policy', 'X-Frame-Options', 'X-Content-Type-Options', 'Referrer-Policy', 'Permissions-Policy'];
                        headers.forEach((header, index) => {
                            headerLabels[index].innerHTML = `<input type="checkbox" name="headers[]" value="${header}"> ${header}`;
                        });
                        startButton.textContent = messages['start_button'];
                        removeButton.textContent = messages['remove_button'];
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching translations:', error);
                });
            });

            // Função para iniciar o ajuste de permissões
            startButton.addEventListener('click', function () {
                this.disabled = true;
                feedback.innerHTML = "<?php echo htmlspecialchars($messages['starting_process']); ?> <br>";

                // Coletar os headers selecionados
                const checkboxes = document.querySelectorAll('input[name="headers[]"]:checked');
                let headers = [];
                checkboxes.forEach((checkbox) => {
                    headers.push(checkbox.value);
                });

                // Verificar se a opção de criar log está selecionada
                const createLog = createLogCheckbox.checked ? 1 : 0;

                // Obter o idioma selecionado
                const language = languageSelect.value;

                // Enviar solicitação AJAX para o PHP
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=setPermissions&language=' + encodeURIComponent(language) +
                          '&createLog=' + encodeURIComponent(createLog) +
                          headers.map(header => '&headers[]=' + encodeURIComponent(header)).join('')
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        feedback.innerHTML += data.feedback;
                        // Se criou o log, adicionar o link
                        <?php if (isset($_SESSION['log_link'])): ?>
                            feedback.innerHTML += "<br><?php echo htmlspecialchars($messages['log_created']); ?> <a href='<?php echo htmlspecialchars($_SESSION['log_link']); ?>' target='_blank'><?php echo htmlspecialchars($_SESSION['log_link']); ?></a><br>";
                            <?php unset($_SESSION['log_link']); ?>
                        <?php endif; ?>
                    } else {
                        feedback.innerHTML += "⚠️ " + data.message;
                    }
                    startButton.disabled = false;
                })
                .catch(error => {
                    feedback.innerHTML += "⚠️ Error: " + error;
                    console.error('Error:', error);
                    startButton.disabled = false;
                });
            });

            // Função para remover o script
            removeButton.addEventListener('click', function () {
                const language = languageSelect.value;
                const removeConfirm = "<?php echo htmlspecialchars($messages['remove_confirm']); ?>";
                if (!confirm(removeConfirm)) {
                    return;
                }

                this.disabled = true;
                feedback.innerHTML = "<?php echo htmlspecialchars($messages['removing_script']); ?> <br>";

                // Enviar solicitação AJAX para o PHP
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=removeScript&language=' + encodeURIComponent(language)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        feedback.innerHTML += data.feedback;
                    } else {
                        feedback.innerHTML += "⚠️ " + data.message;
                    }
                    removeButton.disabled = true; // Script foi removido, desabilitar botão
                })
                .catch(error => {
                    feedback.innerHTML += "⚠️ Error: " + error;
                    console.error('Error:', error);
                    removeButton.disabled = false;
                });
            });
        });
    </script>
</body>

</html>
