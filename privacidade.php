<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$assetVersion = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Política de Privacidade — 4U Drop</title>
  <meta name="description" content="Política de Privacidade do 4U Drop. Transferência direta, segura e com retenção zero de dados.">
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
      
      <h1>Política de Privacidade</h1>
      <p>Última atualização: <?php echo date('d/m/Y'); ?></p>

      <h2>1. Compromisso com a Privacidade</h2>
      <p>O <strong>4U Drop</strong> foi projetado sob o princípio fundamental de <em>Zero Retention</em> (Retenção Zero) e <em>Zero Knowledge Privacy</em>. Não exigimos cadastro, e-mail, senha ou qualquer forma de identificação pessoal dos usuários.</p>

      <h2>2. Coleta de Dados e Informações</h2>
      <p>Nenhum dado pessoal identificável (PII) é coletado, armazenado ou comercializado pelo 4U Drop. As únicas informações processadas são estritamente técnicas e temporárias:</p>
      <ul>
        <li><strong>Arquivos e Conteúdos Temporários</strong>: Os arquivos enviados são renomeados para identificadores hash anônimos (ex: <code>sha256.dat</code>) no servidor. O nome original e conteúdo ficam inacessíveis a terceiros.</li>
        <li><strong>Identificador de Sala (PIN)</strong>: Um código numérico aleatório de 6 dígitos gerado temporariamente para parear o computador com o celular.</li>
      </ul>

      <h2>3. Política de Retenção e Exclusão Imediata (Zero Storage)</h2>
      <p>Todos os arquivos e textos transmitidos através do 4U Drop cumprem a seguinte regra de expiração rígida:</p>
      <ul>
        <li><strong>Exclusão Imediata</strong>: Assim que o download de um arquivo é concluído pelo destinatário, o arquivo físico é destruído permanentemente do servidor na mesma hora.</li>
        <li><strong>Limpeza Automática de Órfãos</strong>: Arquivos que não forem baixados em até 10 minutos são excluídos automaticamente por uma rotina contínua de varredura.</li>
      </ul>

      <h2>4. Criptografia e Segurança</h2>
      <p>Todos os dados trafegam através de conexões seguras criptografadas por HTTPS (TLS/SSL). Os arquivos são armazenados no servidor de forma ofuscada, sem extensão legível e sem indexação por buscadores.</p>

      <h2>5. Cookies e Armazenamento Local</h2>
      <p>Utilizamos apenas o <code>localStorage</code> do navegador do usuário para armazenar dados de sessão temporária (como o ID da sala atual). Nenhum cookie de rastreamento ou analítico de terceiros é utilizado.</p>

      <h2>6. Contato</h2>
      <p>Para dúvidas sobre nossa Política de Privacidade, entre em contato através da nossa <a href="suporte.php" style="color: var(--accent-cyan);">Página de Suporte</a> ou pelo e-mail <code>contato@4u.ia.br</code>.</p>
    </div>
  </main>

  <footer class="app-footer">
    <p>4U Drop — Transferência Direta & Privada PC ⇄ Celular • <a href="privacidade.php" style="color: var(--text-muted);">Privacidade</a> | <a href="termos.php" style="color: var(--text-muted);">Termos</a> | <a href="suporte.php" style="color: var(--text-muted);">Suporte</a></p>
  </footer>
  <script>lucide.createIcons();</script>
</body>
</html>
