<?php
// Prevent browser caching for index page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
$assetVersion = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>4U Drop — Transferência Direta & Privada PC ⇄ Celular</title>
  <meta name="description" content="Envie e receba arquivos e textos entre o seu computador e celular de forma ultrarrápida e 100% privada com criptografia SHA-256.">
  
  <!-- Favicon & PWA Icons -->
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="apple-touch-icon" href="favicon.svg">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#00f2fe">

  <!-- Dynamic Anti-Cache Versioning -->
  <link rel="stylesheet" href="style.css?v=<?php echo $assetVersion; ?>">
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- QRCode.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

  <!-- Header / Navigation -->
  <header class="app-header">
    <div class="nav-container">
      <a href="#" class="brand">
        <div class="brand-icon">
          <i data-lucide="shield-check"></i>
        </div>
        <div class="brand-text">4U<span>Drop</span></div>
      </a>
      
      <div class="header-actions">
        <!-- PWA Install Button -->
        <button id="pwaInstallBtn" class="btn-select-file" style="display: none; padding: 0.35rem 0.65rem; font-size: 0.75rem; background: rgba(0,242,254,0.15); border: 1px solid var(--accent-cyan); color: var(--accent-cyan);">
          <i data-lucide="download-cloud"></i> Instalar
        </button>

        <div class="status-badge" id="networkStatus">
          <div class="pulse-dot" id="statusDot"></div>
          <span id="statusText">Conectado</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Layout -->
  <main class="main-wrapper">
    
    <!-- Left Main Column: Drag & Drop + Transfers -->
    <div class="left-column">
      
      <!-- Drag and Drop Card -->
      <div class="glass-panel" style="margin-bottom: 1.25rem;">
        <div class="panel-header">
          <h2 class="panel-title">
            <i data-lucide="upload-cloud" style="color: var(--accent-cyan);"></i>
            Enviar Arquivos
          </h2>
          <span style="font-size: 0.8rem; color: var(--text-muted);" id="deviceRoleLabel">PC ⇄ Celular</span>
        </div>

        <div class="drop-zone" id="dropZone">
          <input type="file" id="fileInput" multiple style="display: none;">
          <div class="drop-icon-wrapper">
            <i data-lucide="hard-drive-upload"></i>
          </div>
          <h3 class="drop-title">Arraste seus arquivos aqui</h3>
          <p class="drop-subtitle">ou clique para selecionar fotos, vídeos, documentos, APKs ou áudios</p>
          <button class="btn-select-file" onclick="document.getElementById('fileInput').click()">
            <i data-lucide="plus"></i> Selecionar Arquivos
          </button>
        </div>
      </div>

      <!-- Transfers Section (Tabs: Enviar / Recebidos / Área de Transferência) -->
      <div class="glass-panel">
        <div class="content-tabs" style="align-items: center; justify-content: space-between;">
          <div style="display: flex; gap: 0.3rem; flex: 1;">
            <button class="tab-btn active" id="tabReceivedBtn" onclick="switchTab('received')">
              <i data-lucide="download"></i> Recebidos (<span id="receivedCount">0</span>)
            </button>
            <button class="tab-btn" id="tabSentBtn" onclick="switchTab('sent')">
              <i data-lucide="send"></i> Enviados (<span id="sentCount">0</span>)
            </button>
            <button class="tab-btn" id="tabClipboardBtn" onclick="switchTab('clipboard')">
              <i data-lucide="clipboard-copy"></i> Copiar Texto
            </button>
          </div>
          <button class="btn-icon" onclick="clearCurrentRoom()" title="Limpar Arquivos da Sala" style="margin-left: 0.5rem; color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
            <i data-lucide="trash-2"></i>
          </button>
        </div>

        <!-- Received Files List -->
        <div id="tabReceivedContent">
          <div class="file-list" id="receivedList">
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);" id="emptyReceivedState">
              <i data-lucide="inbox" style="width: 42px; height: 42px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
              <p>Nenhum arquivo recebido ainda.</p>
              <span style="font-size: 0.775rem;">Escaneie o QR Code no celular para enviar!</span>
            </div>
          </div>
        </div>

        <!-- Sent Files List -->
        <div id="tabSentContent" style="display: none;">
          <div class="file-list" id="sentList">
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);" id="emptySentState">
              <i data-lucide="send" style="width: 42px; height: 42px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
              <p>Nenhum arquivo enviado nesta sessão.</p>
            </div>
          </div>
        </div>

        <!-- Live Clipboard Sync Panel -->
        <div id="tabClipboardContent" style="display: none;">
          <div style="margin-bottom: 1rem;">
            <p style="font-size: 0.875rem; color: var(--text-secondary);">
              Copie links, senhas ou textos no PC/Celular para ler instantaneamente no outro dispositivo:
            </p>
            <div class="clipboard-input-wrapper">
              <input type="text" id="clipboardText" class="clipboard-input" placeholder="Cole ou digite um texto para enviar...">
              <button class="btn-select-file" style="padding: 0.75rem 1.2rem;" onclick="sendClipboardText()">
                <i data-lucide="send"></i> Enviar
              </button>
            </div>
          </div>

          <div class="file-list" id="clipboardHistory">
            <!-- Items populated by JS -->
          </div>
        </div>

      </div>
    </div>

    <!-- Right Sidebar Column: Pairing & Network Info -->
    <div class="right-column">
      
      <!-- Pairing Card -->
      <div class="glass-panel pairing-panel" id="pairingPanel">
        <h3 class="panel-title" style="margin-bottom: 0.25rem;">
          <i data-lucide="qr-code" style="color: var(--accent-cyan);"></i>
          Conectar Celular
        </h3>
        <p style="font-size: 0.8rem; color: var(--text-secondary);">
          Aponte a câmera do seu celular para parear instantaneamente:
        </p>

        <!-- Dynamic QR Code Container -->
        <div class="qr-box" id="qrcode"></div>

        <div style="font-size: 0.75rem; color: var(--text-muted);">SALA DE PAREAMENTO:</div>
        <div class="pin-code-badge" id="pinCode">...</div>

        <!-- Manual PIN input for cross-network connecting -->
        <div class="manual-pin-group">
          <p style="font-size: 0.75rem; color: var(--text-muted); text-align: center;">Ou digite o Código da Sala no celular:</p>
          <div class="input-row">
            <input type="text" id="manualPinInput" placeholder="Ex: 572125" class="clipboard-input" style="padding: 0.45rem 0.6rem; font-size: 0.85rem; text-align: center;">
            <button class="btn-select-file" style="padding: 0.45rem 0.8rem; font-size: 0.8rem;" onclick="connectToManualPin()">Entrar na Sala</button>
          </div>
        </div>

        <ul class="instructions-list">
          <li>
            <i data-lucide="check-circle-2" style="color: var(--accent-emerald); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
            <span>Abra a câmera do celular e escaneie o código QR acima.</span>
          </li>
          <li>
            <i data-lucide="shield-check" style="color: var(--accent-cyan); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
            <span>Conexão direta pela Hostinger em tempo real.</span>
          </li>
        </ul>
      </div>

      <!-- Detailed Privacy Protection Info Card -->
      <div class="glass-panel" style="margin-top: 1.25rem;">
        <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent-emerald);">
          <i data-lucide="shield-lock"></i>
          Garantia de Privacidade & Criptografia
        </h4>
        <ul style="font-size: 0.785rem; color: var(--text-secondary); line-height: 1.6; list-style: none; padding: 0;">
          <li style="margin-bottom: 0.5rem; display: flex; gap: 0.4rem;">
            <span style="color: var(--accent-cyan); font-weight: bold;">🔑</span>
            <div><strong>Criptografia SHA-256</strong>: Nomes hash anonimizados (<code>.dat</code>) no servidor. Conteúdo ilegível para terceiros.</div>
          </li>
          <li style="margin-bottom: 0.5rem; display: flex; gap: 0.4rem;">
            <span style="color: var(--accent-emerald); font-weight: bold;">🗑️</span>
            <div><strong>Zero-Storage</strong>: O arquivo é destruído no exato segundo em que o download é concluído.</div>
          </li>
          <li style="margin-bottom: 0.5rem; display: flex; gap: 0.4rem;">
            <span style="color: var(--accent-purple); font-weight: bold;">🔒</span>
            <div><strong>Isolamento de Sala</strong>: Acesso exclusivo via PIN de 6 dígitos. Sem indexação.</div>
          </li>
          <li style="display: flex; gap: 0.4rem;">
            <span style="color: var(--accent-amber); font-weight: bold;">⏱️</span>
            <div><strong>Autolimpeza</strong>: Apaga dados remanescentes não reivindicados a cada 15 min.</div>
          </li>
        </ul>
      </div>

    </div>
  </main>

  <footer class="app-footer">
    <p>4U Drop — Transferência Direta & Privada PC ⇄ Celular • <a href="privacidade.php" style="color: var(--text-muted); text-decoration: underline;">Privacidade</a> | <a href="termos.php" style="color: var(--text-muted); text-decoration: underline;">Termos</a> | <a href="suporte.php" style="color: var(--text-muted); text-decoration: underline;">Suporte & Contato</a></p>
  </footer>

  <!-- Force Dynamic Anti-Cache Script Versioning -->
  <script src="app.js?v=<?php echo $assetVersion; ?>"></script>
</body>
</html>
