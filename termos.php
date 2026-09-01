<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$assetVersion = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Termos de Uso — 4U Drop</title>
  <meta name="description" content="Termos de Uso e Condições do Serviço 4U Drop.">
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
    .legal-container ul { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem; padding-left: 1.2rem; }
    .legal-container li { margin-bottom: 0.4rem; }
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
      
      <h1>Termos de Uso e Serviço</h1>
      <p>Última atualização: <?php echo date('d/m/Y'); ?></p>

      <h2>1. Aceitação dos Termos</h2>
      <p>Ao utilizar o aplicativo ou serviço <strong>4U Drop</strong>, você concorda expressamente com os presentes Termos de Uso. Caso não concorde com qualquer disposição aqui contida, por favor, interrompa o uso do aplicativo.</p>

      <h2>2. Descrição do Serviço</h2>
      <p>O 4U Drop é uma utilidade web para transferência direta e temporária de arquivos e textos entre dispositivos (computador e celular) pareados por um código de sala aleatório de 6 dígitos.</p>

      <h2>3. Uso Aceitável e Conduta do Usuário</h2>
      <p>O usuário se compromete a utilizar a plataforma exclusivamente para fins lícitos. É estritamente proibido utilizar o 4U Drop para:</p>
      <ul>
        <li>Transmitir vírus, malwares, softwares maliciosos ou códigos nocivos;</li>
        <li>Enviar conteúdos ilegais, difamatórios, piratas ou violadores de direitos autorais;</li>
        <li>Tentar violar os sistemas de segurança, sobrecarregar os servidores ou interceptar dados alheios.</li>
      </ul>

      <h2>4. Limitação de Responsabilidade</h2>
      <p>O serviço é fornecido "como está" e "conforme disponível". O 4U Drop não se responsabiliza por perdas acidentais de dados durante a transmissão ou pela exclusão automática programada de arquivos expirados.</p>

      <h2>5. Alterações dos Termos</h2>
      <p>Reservamo-nos o direito de atualizar estes termos a qualquer momento para refletir melhorias no serviço ou exigências legais dos distribuidores (Google Chrome Web Store / Google Play Store).</p>
    </div>
  </main>

  <footer class="app-footer">
    <p>4U Drop — Transferência Direta & Privada PC ⇄ Celular • <a href="privacidade.php" style="color: var(--text-muted);">Privacidade</a> | <a href="termos.php" style="color: var(--text-muted);">Termos</a> | <a href="suporte.php" style="color: var(--text-muted);">Suporte</a></p>
  </footer>
  <script>lucide.createIcons();</script>
</body>
</html>
