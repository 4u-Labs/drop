<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$assetVersion = time();

$feedbackMsg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email']) && !empty($_POST['message'])) {
    $senderEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $userMsg = htmlspecialchars($_POST['message']);
    
    $to = "contato@4u.ia.br";
    $subject = "=?UTF-8?B?" . base64_encode("4U Drop — Nova Mensagem de Suporte") . "?=";
    $body = "Nova mensagem enviada pelo 4U Drop Suporte:\n\nDe: " . $senderEmail . "\nData: " . date('d/m/Y H:i') . "\n\nMensagem:\n" . $userMsg;
    
    // Proper Hostinger-compliant headers
    $headers = "From: contato@4u.ia.br\r\n" .
               "Reply-To: " . $senderEmail . "\r\n" .
               "MIME-Version: 1.0\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // 1. Send via Hostinger PHP Mailer
    @mail($to, $subject, $body, $headers);

    // 2. BACKUP GUARANTEE: Save message to server log so NO MESSAGE IS EVER LOST
    $uploadDir = __DIR__ . '/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $logFile = $uploadDir . 'messages_log.json';
    $existing = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $existing[] = [
        'id' => uniqid('msg_', true),
        'from' => $senderEmail,
        'date' => date('Y-m-d H:i:s'),
        'message' => $_POST['message']
    ];
    file_put_contents($logFile, json_encode($existing, JSON_PRETTY_PRINT));

    $feedbackMsg = "Mensagem enviada com sucesso! Nossa equipe responderá em breve.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suporte & Ajuda — 4U Drop</title>
  <meta name="description" content="Central de Suporte e Perguntas Frequentes do 4U Drop.">
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="stylesheet" href="style.css?v=<?php echo $assetVersion; ?>">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    .legal-container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      line-height: 1.7;
    }
    .legal-container h1 { font-family: 'Outfit', sans-serif; font-size: 1.8rem; margin-bottom: 0.5rem; color: var(--accent-cyan); }
    .legal-container h2 { font-family: 'Outfit', sans-serif; font-size: 1.25rem; margin: 1.5rem 0 0.5rem; color: var(--accent-emerald); }
    .legal-container p { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem; }
    .faq-item { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 0.75rem; }
    .faq-q { font-weight: 700; color: #fff; font-size: 0.95rem; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.5rem; }
    .faq-a { color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5; }
    .contact-card { background: rgba(0, 242, 254, 0.03); border: 1px solid var(--border-highlight); border-radius: var(--radius-md); padding: 1.25rem; margin-top: 1.5rem; }
    .back-btn { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--accent-cyan); text-decoration: none; font-weight: 600; font-size: 0.875rem; margin-bottom: 1.5rem; }
  </style>
</head>
<body>
  <header class="app-header">
    <div class="nav-container">
      <a href="index.php" class="brand">
        <div class="brand-icon"><i data-lucide="shield-check"></i></div>
        <div class="brand-text">4U<span>Drop</span></div>
      </a>
    </div>
  </header>

  <main style="flex:1;">
    <div class="glass-panel legal-container">
      <a href="index.php" class="back-btn"><i data-lucide="arrow-left"></i> Voltar ao 4U Drop</a>
      
      <h1>Central de Suporte & Ajuda</h1>
      <p>Tire suas dúvidas ou entre em contato diretamente com nossa equipe de desenvolvimento.</p>

      <h2>Perguntas Frequentes (FAQ)</h2>
      
      <div class="faq-item">
        <div class="faq-q"><i data-lucide="help-circle" style="color: var(--accent-cyan); width: 18px;"></i> Como conectar meu celular ao computador?</div>
        <div class="faq-a">Basta abrir o aplicativo da câmera do seu celular e apontar para o Código QR na tela do computador. A conexão é estabelecida instantaneamente sem precisar criar conta.</div>
      </div>

      <div class="faq-item">
        <div class="faq-q"><i data-lucide="shield-check" style="color: var(--accent-emerald); width: 18px;"></i> Meus arquivos ficam salvos no servidor?</div>
        <div class="faq-a">Não. Utilizamos a política de <strong>Zero Retenção</strong>. Assim que você baixa um arquivo, ele é deletado permanentemente do servidor na mesma hora.</div>
      </div>

      <div class="faq-item">
        <div class="faq-q"><i data-lucide="smartphone" style="color: var(--accent-purple); width: 18px;"></i> Posso instalar o 4U Drop no celular como App?</div>
        <div class="faq-a">Sim! Ao abrir a página no navegador do seu smartphone, toque no botão <strong>"Instalar App"</strong> no topo para adicioná-lo à tela inicial como um PWA nativo.</div>
      </div>

      <h2>Entre em Contato</h2>
      <div class="contact-card">
        <?php if ($feedbackMsg): ?>
          <div style="padding: 0.75rem; background: rgba(16,185,129,0.15); border: 1px solid var(--accent-emerald); color: var(--accent-emerald); border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 1rem;">
            <?php echo $feedbackMsg; ?>
          </div>
        <?php endif; ?>

        <p style="font-size: 0.85rem; margin-bottom: 1rem;">Precisa de suporte técnico ou tem uma sugestão? Envie uma mensagem para <code>contato@4u.ia.br</code> ou preencha o formulário abaixo:</p>

        <form method="POST" action="suporte.php" style="display: flex; flex-direction: column; gap: 0.75rem;">
          <input type="email" name="email" placeholder="Seu e-mail de contato" class="clipboard-input" required>
          <textarea name="message" rows="4" placeholder="Descreva sua dúvida ou mensagem..." class="clipboard-input" style="resize: vertical;" required></textarea>
          <button type="submit" class="btn-select-file" style="align-self: flex-start;">
            <i data-lucide="send"></i> Enviar Mensagem
          </button>
        </form>
      </div>

    </div>
  </main>

  <footer class="app-footer">
    <p>4U Drop — Transferência Direta & Privada PC ⇄ Celular • <a href="privacidade.php" style="color: var(--text-muted);">Privacidade</a> | <a href="termos.php" style="color: var(--text-muted);">Termos</a> | <a href="suporte.php" style="color: var(--text-muted);">Suporte</a></p>
  </footer>
  <script>lucide.createIcons();</script>
</body>
</html>
