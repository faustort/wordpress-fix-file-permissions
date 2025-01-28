<?php
// Nome do script
$scriptName = basename(__FILE__);

// Aumentar o tempo de execução para evitar timeouts
set_time_limit(300); // 5 minutos

// Habilitar buffer de saída para permitir envio progressivo
@ini_set('output_buffering', 'Off');
@ini_set('zlib.output_compression', false);
@ini_set('implicit_flush', true);
while (ob_get_level()) ob_end_clean();
ob_implicit_flush(true);

// Definir cabeçalhos para evitar cache
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Processar a solicitação POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'setPermissions') {
            // Definir a localização inicial
            $initialDir = __DIR__;
            $selectedHeaders = isset($_POST['headers']) ? $_POST['headers'] : [];

            // Iniciar o processo e enviar resposta parcial
            echo "<div>";
            flush();
            setPermissionsAndUpdateHtaccess($initialDir, $selectedHeaders);
            echo "Processo concluído.</div>";
            flush();
            exit;
        } elseif ($_POST['action'] === 'removeScript') {
            removeSelf();
            exit;
        }
    }

    // Se nenhuma ação for reconhecida
    echo "Ação inválida.";
    exit;
}

// Função principal para alterar permissões e atualizar .htaccess
function setPermissionsAndUpdateHtaccess($dir, $selectedHeaders)
{
    echo "Iniciando processo de ajuste de permissões e atualização de .htaccess em <strong>$dir</strong>.<br>";
    flush();

    // Aplicar permissões na raiz
    applyPermissions($dir);

    // Encontrar instalações do WordPress em subpastas e na raiz
    $wordpressDirs = findWordpressDirectories($dir);

    foreach ($wordpressDirs as $wpDir) {
        echo "Processando instalação do WordPress em <strong>$wpDir</strong>.<br>";
        flush();

        // Aplicar permissões nas pastas do WordPress
        applyPermissions($wpDir);

        // Atualizar ou criar .htaccess nas pastas do WordPress
        updateHtaccess($wpDir, $selectedHeaders, $wpDir === $dir);

        // Opcional: Criar .htaccess para bloquear PHP em wp-content/uploads
        $uploadsDir = $wpDir . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads';
        if (is_dir($uploadsDir)) {
            createUploadsHtaccess($uploadsDir);
        }
    }

    // Remover .htaccess de diretórios que não são WordPress roots ou suas subpastas
    removeOtherHtaccess($dir, $wordpressDirs);
}

// Função para aplicar permissões por diretório
function applyPermissions($dir)
{
    // Aplicar permissões ao próprio diretório
    if (!@chmod($dir, 0755)) {
        echo "⚠️ Erro ao aplicar permissão <code>0755</code> ao diretório: <strong>$dir</strong><br>";
        flush();
    } else {
        echo "✅ Permissão <code>0755</code> aplicada ao diretório: <strong>$dir</strong><br>";
        flush();
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
            applyPermissions($fullPath);
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
                echo "⚠️ Erro ao aplicar permissão <code>" . decoct($perm) . "</code> ao arquivo: <strong>$fullPath</strong><br>";
                flush();
            } else {
                // Mensagem por arquivo foi removida para reduzir o uso de memória
                // Apenas confirmar que o arquivo foi processado
                // echo "✅ Permissão <code>" . decoct($perm) . "</code> aplicada ao arquivo: <strong>$fullPath</strong><br>";
                // flush();
            }
        }
    }

    // Após processar todos os arquivos, informar que o diretório foi processado
    echo "🔄 Permissões ajustadas para todos os arquivos em: <strong>$dir</strong>.<br><br>";
    flush();
}

// Função para atualizar ou criar .htaccess
function updateHtaccess($dir, $selectedHeaders, $isRoot)
{
    global $scriptName;
    $htaccessFile = $dir . DIRECTORY_SEPARATOR . '.htaccess';

    // Diretivas de segurança
    $securityDirectives = "\n# Proteção adicional\n<FilesMatch \"^(wp-config\\.php|\\.htaccess)$\">\n    Require all denied\n</FilesMatch>\n";

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
    $securityDirectives .= "# Bloquear acesso a XML-RPC\n<Files xmlrpc.php>\n    Require all denied\n</Files>\n# Bloquear listagem de diretórios\nOptions -Indexes\n";

    // Verificar se o .htaccess existe
    if (file_exists($htaccessFile)) {
        if (is_writable($htaccessFile)) {
            $htaccessContent = file_get_contents($htaccessFile);
            // Adicionar diretivas se ainda não existirem
            if (strpos($htaccessContent, '# Proteção adicional') === false) {
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
                    echo "🔒 Diretivas de segurança adicionadas ao <code>.htaccess</code> em <strong>$dir</strong>.<br>";
                    flush();
                } else {
                    echo "⚠️ Erro ao atualizar o <code>.htaccess</code> em <strong>$dir</strong>.<br>";
                    flush();
                }
            } else {
                echo "<code>.htaccess</code> em <strong>$dir</strong> já contém diretivas de segurança.<br>";
                flush();
            }
        } else {
            echo "⚠️ O arquivo <code>.htaccess</code> em <strong>$dir</strong> não possui permissões de escrita. Verifique as permissões.<br>";
            flush();
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
            echo "<code>.htaccess</code> criado com diretivas de segurança em <strong>$dir</strong>.<br>";
            flush();
        } else {
            echo "⚠️ Erro ao criar o arquivo <code>.htaccess</code> em <strong>$dir</strong>.<br>";
            flush();
        }
    }
}

// Função para criar .htaccess em wp-content/uploads para bloquear PHP
function createUploadsHtaccess($uploadsDir)
{
    $htaccessFile = $uploadsDir . DIRECTORY_SEPARATOR . '.htaccess';

    // Diretivas para bloquear a execução de PHP
    $blockPhp = "\n# Bloquear execução de scripts PHP nesta pasta\n<FilesMatch \"\\.php$\">\n    Require all denied\n</FilesMatch>\n";

    if (file_exists($htaccessFile)) {
        if (is_writable($htaccessFile)) {
            $htaccessContent = file_get_contents($htaccessFile);
            if (strpos($htaccessContent, '# Bloquear execução de scripts PHP nesta pasta') === false) {
                $htaccessContent .= $blockPhp;
                if (file_put_contents($htaccessFile, $htaccessContent)) {
                    echo "🔒 Diretivas para bloquear PHP adicionadas ao <code>.htaccess</code> em <strong>$uploadsDir</strong>.<br>";
                    flush();
                } else {
                    echo "⚠️ Erro ao atualizar o <code>.htaccess</code> em <strong>$uploadsDir</strong>.<br>";
                    flush();
                }
            } else {
                echo "<code>.htaccess</code> em <strong>$uploadsDir</strong> já contém diretivas para bloquear PHP.<br>";
                flush();
            }
        } else {
            echo "⚠️ O arquivo <code>.htaccess</code> em <strong>$uploadsDir</strong> não possui permissões de escrita. Verifique as permissões.<br>";
            flush();
        }
    } else {
        // Criar um novo .htaccess
        $defaultHtaccess = "# Início do .htaccess\n" . $blockPhp;
        if (file_put_contents($htaccessFile, $defaultHtaccess)) {
            echo "<code>.htaccess</code> criado com diretivas para bloquear PHP em <strong>$uploadsDir</strong>.<br>";
            flush();
        } else {
            echo "⚠️ Erro ao criar o arquivo <code>.htaccess</code> em <strong>$uploadsDir</strong>.<br>";
            flush();
        }
    }
}

// Função para encontrar diretórios do WordPress
function findWordpressDirectories($dir)
{
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
function removeOtherHtaccess($dir, $wordpressDirs)
{
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
                        echo "🗑️ Arquivo <code>.htaccess</code> removido de: <strong>$fullPath</strong>.<br>";
                        flush();
                    } else {
                        echo "⚠️ Erro ao remover <code>.htaccess</code> de: <strong>$fullPath</strong>.<br>";
                        flush();
                    }
                }
            }
        }
    }
}

// Função para remover o próprio script
function removeSelf()
{
    $scriptPath = __FILE__;

    if (file_exists($scriptPath)) {
        if (@unlink($scriptPath)) {
            echo "🗑️ Script removido com sucesso.<br>";
            flush();
        } else {
            echo "⚠️ Erro ao remover o script.<br>";
            flush();
        }
    } else {
        echo "🔄 O script já foi removido.<br>";
        flush();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segurança do WordPress</title>
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

        .headers {
            text-align: left;
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
        }

        .headers label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333333;
        }

        .headers input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Segurança do WordPress</h1>
        <div class="headers">
            <label><input type="checkbox" name="headers[]" value="Strict-Transport-Security"> Strict-Transport-Security</label>
            <label><input type="checkbox" name="headers[]" value="Content-Security-Policy"> Content-Security-Policy (upgrade-insecure-requests)</label>
            <label><input type="checkbox" name="headers[]" value="X-Frame-Options"> X-Frame-Options</label>
            <label><input type="checkbox" name="headers[]" value="X-Content-Type-Options"> X-Content-Type-Options</label>
            <label><input type="checkbox" name="headers[]" value="Referrer-Policy"> Referrer-Policy</label>
            <label><input type="checkbox" name="headers[]" value="Permissions-Policy"> Permissions-Policy</label>
        </div>
        <button id="startButton" class="btn btn-permissions">Iniciar Alteração de Permissões</button>
        <button id="removeButton" class="btn btn-remove">Remover Este Script</button>
        <div id="feedback"></div>
    </div>

    <script>
        document.getElementById('startButton').addEventListener('click', function () {
            this.disabled = true;
            document.getElementById('feedback').innerHTML = "Iniciando processo...<br>";

            // Coletar os headers selecionados
            const checkboxes = document.querySelectorAll('input[name="headers[]"]:checked');
            let headers = [];
            checkboxes.forEach((checkbox) => {
                headers.push(checkbox.value);
            });

            // Enviar solicitação AJAX para o PHP
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 3) { // Recebendo dados
                    // Append os dados recebidos até agora
                    document.getElementById('feedback').innerHTML += xhr.responseText;
                }
                if (xhr.readyState === 4) { // Finalizado
                    if (xhr.status === 200) {
                        document.getElementById('feedback').innerHTML += "";
                    } else {
                        document.getElementById('feedback').innerHTML += "⚠️ Erro ao conectar com o servidor. Status: " + xhr.status;
                    }
                    document.getElementById('startButton').disabled = false;
                }
            };

            const params = 'action=setPermissions&' + headers.map(header => 'headers[]=' + encodeURIComponent(header)).join('&');
            xhr.send(params);
        });

        document.getElementById('removeButton').addEventListener('click', function () {
            if (!confirm("Tem certeza de que deseja remover este script?")) {
                return;
            }

            this.disabled = true;
            document.getElementById('feedback').innerHTML = "Removendo script...<br>";

            // Enviar solicitação AJAX para o PHP
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 3) { // Recebendo dados
                    // Append os dados recebidos até agora
                    document.getElementById('feedback').innerHTML += xhr.responseText;
                }
                if (xhr.readyState === 4) { // Finalizado
                    if (xhr.status === 200) {
                        document.getElementById('feedback').innerHTML += "";
                    } else {
                        document.getElementById('feedback').innerHTML += "⚠️ Erro ao conectar com o servidor. Status: " + xhr.status;
                    }
                }
            };

            xhr.send('action=removeScript');
        });
    </script>
</body>

</html>
