/* =======================================================
   4U DROP — Bulletproof Hostinger Direct Relay Engine
   Target Path: https://4u.ia.br/app/drop/
   Clean Download Handling & Manual PIN Switching (v21.0)
   ======================================================= */

// Force clear stale mobile Service Workers and Caches on boot
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(registrations => {
    for (let registration of registrations) {
      registration.unregister();
    }
  });
}
if ('caches' in window) {
  caches.keys().then(keys => {
    keys.forEach(key => caches.delete(key));
  });
}

// App State
const state = {
  deviceId: 'dev_' + Math.random().toString(36).substring(2, 9),
  roomId: '',
  sentFiles: [],
  receivedFiles: [],
  clipboardItems: [],
  knownFileIds: new Set(),
  knownTextIds: new Set(),
  isHost: true,
  hasAdaptedMobile: false,
  pollTimer: null,
  deferredInstallPrompt: null
};

// Initialize App
document.addEventListener('DOMContentLoaded', () => {
  if (window.lucide) lucide.createIcons();

  // Check URL parameters for room joining
  const urlParams = new URLSearchParams(window.location.search);
  const targetRoom = urlParams.get('room');

  if (targetRoom) {
    state.isHost = false;
    state.roomId = targetRoom.trim().replace('-', '');
    adaptMobileUI();
  } else {
    state.isHost = true;
    state.roomId = generateRoomId();
  }

  // Render Room ID / PIN badge & QR Code
  renderPinCode();
  renderQrCode();

  // Setup Drag & Drop Handlers
  setupDragAndDrop();

  // Setup enter key listener for manual PIN input
  const manualInput = document.getElementById('manualPinInput');
  if (manualInput) {
    manualInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') connectToManualPin();
    });
  }

  // Start polling sync engine via Hostinger Direct API
  updateStatus("Conectado", "success");
  startSyncEngine();
});

// Adapt UI when opened on mobile device with room parameter (Runs only ONCE)
function adaptMobileUI() {
  if (state.hasAdaptedMobile) return;
  state.hasAdaptedMobile = true;

  const roleLabel = document.getElementById('deviceRoleLabel');
  if (roleLabel) roleLabel.textContent = "📱 Celular ⇄ PC";

  const dropTitle = document.querySelector('.drop-title');
  if (dropTitle) dropTitle.textContent = "Toque para enviar arquivos";

  const dropSub = document.querySelector('.drop-subtitle');
  if (dropSub) dropSub.textContent = "Selecione fotos, vídeos ou documentos do seu smartphone";

  const pairingPanel = document.getElementById('pairingPanel');
  if (pairingPanel && !state.isHost) {
    pairingPanel.innerHTML = `
      <div style="padding: 0.85rem 0.25rem; text-align: center; width: 100%;">
        <div style="width: 48px; height: 48px; background: rgba(16,185,129,0.12); border: 2px solid var(--accent-emerald); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; color: var(--accent-emerald); font-size: 1.3rem;">
          <i data-lucide="smartphone"></i>
        </div>
        <h3 style="font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.2rem;">📱 Celular Conectado</h3>
        <p style="font-size: 0.775rem; color: var(--text-secondary);">Envie fotos ou toque em Baixar no arquivo recebido.</p>
        <div class="pin-code-badge" style="margin-top: 0.5rem; font-size: 1rem; padding: 0.35rem 0.75rem;">SALA: ${state.roomId}</div>
        
        <div style="margin-top: 0.75rem; width: 100%;">
          <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.3rem;">Trocar de Sala Manualmente:</p>
          <div style="display: flex; gap: 0.4rem;">
            <input type="text" id="manualPinInputMobile" placeholder="Ex: 570602" class="clipboard-input" style="padding: 0.4rem; font-size: 0.8rem; text-align: center;">
            <button class="btn-select-file" style="padding: 0.4rem 0.75rem; font-size: 0.775rem;" onclick="connectToManualPinMobile()">Entrar</button>
          </div>
        </div>
      </div>`;
    if (window.lucide) lucide.createIcons();
  }
}

// Generate 6-digit random Room ID
function generateRoomId() {
  return Math.floor(100000 + Math.random() * 900000).toString();
}

function renderPinCode() {
  const pinEl = document.getElementById('pinCode');
  if (pinEl) {
    const formatted = state.roomId.length === 6 ? `${state.roomId.substring(0,3)}-${state.roomId.substring(3)}` : state.roomId;
    pinEl.textContent = formatted;
  }
}

// Render Dynamic QR Code
function renderQrCode() {
  const qrContainer = document.getElementById('qrcode');
  if (!qrContainer) return;

  qrContainer.innerHTML = '';
  
  let baseUrl = window.location.href.split('?')[0].split('#')[0];
  if (baseUrl.startsWith('file://')) {
    baseUrl = "https://4u.ia.br/app/drop/index.php";
  }

  const joinUrl = `${baseUrl}?room=${state.roomId}`;

  try {
    new QRCode(qrContainer, {
      text: joinUrl,
      width: 170,
      height: 170,
      colorDark: "#07090e",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
  } catch (e) {
    qrContainer.innerHTML = `<div style="padding:1rem; font-size:0.8rem; color:#333;">Sala: ${state.roomId}</div>`;
  }
}

// Connect to manual PIN
function connectToManualPin() {
  const input = document.getElementById('manualPinInput');
  if (!input || !input.value.trim()) return;

  const pin = input.value.trim().replace('-', '');
  if (pin.length < 3) return;

  state.roomId = pin;
  state.sentFiles = [];
  state.receivedFiles = [];
  state.clipboardItems = [];
  state.knownFileIds.clear();
  state.knownTextIds.clear();

  renderPinCode();
  renderQrCode();

  updateStatus(`Sala ${pin}`, "success");
  startSyncEngine();
}

function connectToManualPinMobile() {
  const input = document.getElementById('manualPinInputMobile');
  if (!input || !input.value.trim()) return;

  const pin = input.value.trim().replace('-', '');
  if (pin.length < 3) return;

  state.roomId = pin;
  state.sentFiles = [];
  state.receivedFiles = [];
  state.clipboardItems = [];
  state.knownFileIds.clear();
  state.knownTextIds.clear();

  renderPinCode();
  renderReceivedList();
  renderSentList();

  updateStatus(`Sala ${pin}`, "success");
  startSyncEngine();
}

// Sync Engine (Polls Hostinger Direct API every 2s)
function startSyncEngine() {
  if (state.pollTimer) clearInterval(state.pollTimer);

  fetchRoomData();
  state.pollTimer = setInterval(fetchRoomData, 2000);
}

function fetchRoomData() {
  const apiUrl = `api.php?action=list&room=${state.roomId}&t=${Date.now()}`;

  fetch(apiUrl)
    .then(res => res.json())
    .then(data => {
      if (data && data.success) {
        updateStatus("Conectado", "success");
        processIncomingFiles(data.files || []);
        processIncomingTexts(data.texts || []);
      }
    })
    .catch(err => {
      console.warn("Sync fetch notice:", err);
    });
}

// Process Files from Server
function processIncomingFiles(files) {
  files.forEach(file => {
    if (!state.knownFileIds.has(file.id)) {
      state.knownFileIds.add(file.id);

      if (file.sender === state.deviceId) {
        if (!state.sentFiles.some(f => f.id === file.id)) {
          state.sentFiles.unshift(file);
        }
      } else {
        // Was received from other device!
        state.receivedFiles.unshift(file);
        renderReceivedList();
        switchTab('received');
      }
    }
  });

  renderSentList();
  renderReceivedList();
}

// Process Texts from Server
function processIncomingTexts(texts) {
  texts.forEach(item => {
    if (!state.knownTextIds.has(item.id)) {
      state.knownTextIds.add(item.id);
      state.clipboardItems.unshift(item);
      renderClipboardHistory();
    }
  });
}

// Setup Drag & Drop
function setupDragAndDrop() {
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');

  if (!dropZone || !fileInput) return;

  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eName => {
    dropZone.addEventListener(eName, preventDefaults, false);
    document.body.addEventListener(eName, preventDefaults, false);
  });

  ['dragenter', 'dragover'].forEach(eName => {
    dropZone.addEventListener(eName, () => dropZone.classList.add('drag-over'), false);
  });

  ['dragleave', 'drop'].forEach(eName => {
    dropZone.addEventListener(eName, () => dropZone.classList.remove('drag-over'), false);
  });

  dropZone.addEventListener('drop', (e) => {
    handleFiles(e.dataTransfer.files);
  });

  fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
  });
}

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

// Upload Files to Hostinger API
function handleFiles(files) {
  if (!files || files.length === 0) return;

  const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

  Array.from(files).forEach(file => {
    const formData = new FormData();
    formData.append('action', 'upload');
    formData.append('room', state.roomId);
    formData.append('sender', state.deviceId);
    formData.append('timestamp', nowTime);
    formData.append('file', file);

    updateStatus("Enviando...", "warning");

    fetch('api.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data && data.success) {
        updateStatus("Conectado", "success");
        state.knownFileIds.add(data.file.id);
        state.sentFiles.unshift(data.file);
        renderSentList();
        switchTab('sent');
      } else {
        alert("Erro ao enviar arquivo: " + (data.error || 'Tente novamente'));
      }
    })
    .catch(err => {
      console.error("Upload error:", err);
      alert("Erro ao conectar com a Hostinger.");
    });
  });
}

// Send Clipboard Text
function sendClipboardText() {
  const input = document.getElementById('clipboardText');
  if (!input || !input.value.trim()) return;

  const textVal = input.value.trim();
  const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

  const formData = new FormData();
  formData.append('action', 'send_text');
  formData.append('room', state.roomId);
  formData.append('sender', state.deviceId);
  formData.append('timestamp', nowTime);
  formData.append('text', textVal);

  fetch('api.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data && data.success) {
      state.knownTextIds.add(data.entry.id);
      state.clipboardItems.unshift(data.entry);
      renderClipboardHistory();
      input.value = '';
    }
  });
}

// Clear Room Data
function clearCurrentRoom() {
  if (confirm("Tem certeza que deseja apagar todos os arquivos e historico desta sala no servidor?")) {
    const formData = new FormData();
    formData.append('action', 'clear_room');
    formData.append('room', state.roomId);

    fetch('api.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data && data.success) {
        state.sentFiles = [];
        state.receivedFiles = [];
        state.clipboardItems = [];
        state.knownFileIds.clear();
        state.knownTextIds.clear();
        renderSentList();
        renderReceivedList();
        renderClipboardHistory();
        alert("🔒 Sala e arquivos apagados com sucesso!");
      }
    });
  }
}

// Update UI Status Badge
function updateStatus(text, type) {
  const textEl = document.getElementById('statusText');
  const dotEl = document.getElementById('statusDot');

  if (textEl) textEl.textContent = text;
  if (dotEl) {
    if (type === 'success') {
      dotEl.style.backgroundColor = 'var(--accent-emerald)';
      dotEl.style.boxShadow = '0 0 12px var(--accent-emerald)';
    } else if (type === 'danger') {
      dotEl.style.backgroundColor = '#ef4444';
      dotEl.style.boxShadow = '0 0 12px #ef4444';
    } else {
      dotEl.style.backgroundColor = 'var(--accent-amber)';
      dotEl.style.boxShadow = '0 0 12px var(--accent-amber)';
    }
  }
}

// UI Renderers
function renderReceivedList() {
  const container = document.getElementById('receivedList');
  const countEl = document.getElementById('receivedCount');

  if (countEl) countEl.textContent = state.receivedFiles.length;
  if (!container) return;

  if (state.receivedFiles.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 2rem 0.5rem; color: var(--text-muted);">
        <i data-lucide="inbox" style="width: 40px; height: 40px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
        <p style="font-size: 0.85rem;">Nenhum arquivo recebido ainda.</p>
        <span style="font-size: 0.75rem;">Escaneie o QR Code no celular para enviar!</span>
      </div>`;
    if (window.lucide) lucide.createIcons();
    return;
  }

  container.innerHTML = state.receivedFiles.map(file => `
    <div class="file-item">
      <div class="file-info">
        <div class="file-icon">
          <i data-lucide="${getFileIcon(file.name)}"></i>
        </div>
        <div class="file-meta">
          <div class="file-name" title="${file.name}">${file.name}</div>
          <div class="file-details">${file.size} • Recebido às ${file.timestamp}</div>
        </div>
      </div>
      <div class="file-actions">
        <a href="${file.url}" target="_blank" download="${file.name}" class="btn-select-file" style="padding: 0.4rem 0.75rem; font-size: 0.775rem; text-decoration: none;" title="Baixar Arquivo Seguramente">
          <i data-lucide="download"></i> Baixar
        </a>
      </div>
    </div>
  `).join('');

  if (window.lucide) lucide.createIcons();
}

function renderSentList() {
  const container = document.getElementById('sentList');
  const countEl = document.getElementById('sentCount');

  if (countEl) countEl.textContent = state.sentFiles.length;
  if (!container) return;

  if (state.sentFiles.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 2rem 0.5rem; color: var(--text-muted);">
        <i data-lucide="send" style="width: 40px; height: 40px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
        <p style="font-size: 0.85rem;">Nenhum arquivo enviado nesta sessão.</p>
      </div>`;
    if (window.lucide) lucide.createIcons();
    return;
  }

  container.innerHTML = state.sentFiles.map(file => `
    <div class="file-item">
      <div class="file-info">
        <div class="file-icon" style="color: var(--accent-purple);">
          <i data-lucide="${getFileIcon(file.name)}"></i>
        </div>
        <div class="file-meta">
          <div class="file-name" title="${file.name}">${file.name}</div>
          <div class="file-details">${file.size} • Enviado às ${file.timestamp}</div>
        </div>
      </div>
      <div class="file-actions">
        <span style="font-size: 0.75rem; color: var(--accent-emerald); font-weight: 600; padding: 0.25rem 0.5rem; background: rgba(16,185,129,0.1); border-radius: var(--radius-sm);">
          Enviado ✓
        </span>
      </div>
    </div>
  `).join('');

  if (window.lucide) lucide.createIcons();
}

function renderClipboardHistory() {
  const container = document.getElementById('clipboardHistory');
  if (!container) return;

  if (state.clipboardItems.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 2rem 0.5rem; color: var(--text-muted);">
        <i data-lucide="clipboard-x" style="width: 36px; height: 36px; opacity: 0.5; margin-bottom: 0.5rem;"></i>
        <p style="font-size: 0.85rem;">Nenhum texto compartilhado ainda.</p>
      </div>`;
    if (window.lucide) lucide.createIcons();
    return;
  }

  container.innerHTML = state.clipboardItems.map(item => `
    <div class="file-item">
      <div class="file-info" style="flex: 1;">
        <div class="file-icon" style="color: var(--accent-cyan);">
          <i data-lucide="file-text"></i>
        </div>
        <div class="file-meta" style="flex: 1;">
          <div class="file-name" style="max-width: 100%; color: #fff;">${escapeHtml(item.text)}</div>
          <div class="file-details">Compartilhado às ${item.timestamp}</div>
        </div>
      </div>
      <div class="file-actions">
        <button class="btn-icon" onclick="copyToClipboard('${escapeHtml(item.text)}')" title="Copiar Texto">
          <i data-lucide="copy"></i>
        </button>
      </div>
    </div>
  `).join('');

  if (window.lucide) lucide.createIcons();
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert("Texto copiado!");
  }).catch(() => {
    prompt("Copie o texto:", text);
  });
}

function switchTab(tabName) {
  const tabs = ['received', 'sent', 'clipboard'];
  tabs.forEach(t => {
    const btn = document.getElementById(`tab${capitalize(t)}Btn`);
    const content = document.getElementById(`tab${capitalize(t)}Content`);
    if (btn && content) {
      if (t === tabName) {
        btn.classList.add('active');
        content.style.display = 'block';
      } else {
        btn.classList.remove('active');
        content.style.display = 'none';
      }
    }
  });
}

function getFileIcon(filename) {
  const ext = filename.split('.').pop().toLowerCase();
  if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) return 'image';
  if (['mp4', 'mkv', 'webm', 'mov'].includes(ext)) return 'video';
  if (['mp3', 'wav', 'ogg'].includes(ext)) return 'music';
  if (ext === 'pdf') return 'file-text';
  if (ext === 'apk') return 'smartphone';
  return 'file';
}

function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
  return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
