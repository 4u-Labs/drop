# 🚀 Guia Passo a Passo: Publicando o 4U Drop na Chrome Web Store & Google Play

Este guia contém as instruções exatas para enviar e aprovar o **4U Drop** nas lojas oficiais do Google.

---

## 1. 🛍️ Como publicar na Chrome Web Store (Extensão do Chrome)

### Requisitos:
1. Conta no [Chrome Web Store Developer Console](https://chrome.google.com/webstore/developer/dashboard) (taxa única vitalícia de $5 do Google).
2. As 3 páginas legais já ativas em seu site:
   - `https://4u.ia.br/app/drop/privacidade.php`
   - `https://4u.ia.br/app/drop/termos.php`
   - `https://4u.ia.br/app/drop/suporte.php`

### Passo a Passo:
1. No seu computador, selecione a pasta `4u-drop` contendo `manifest_ext.json` (renomeado para `manifest.json`), `index.php`, `style.css`, `app.js` e `favicon.svg`.
2. Compacte os arquivos em um arquivo `.zip`.
3. Acesse o **Chrome Developer Console** e clique em **"Adicionar Novo Item"**.
4. Faça o upload do arquivo `.zip`.
5. Preencha as informações do formulário:
   - **Título**: `4U Drop — Transferência Direta PC ⇄ Celular`
   - **Descrição curta**: `Envie e receba arquivos e textos entre seu computador e celular instantaneamente com retenção zero de dados.`
   - **URL da Política de Privacidade**: `https://4u.ia.br/app/drop/privacidade.php`
   - **Capturas de Tela (Screenshots)**: Envie 2 ou 3 capturas da tela do app.
6. Clique em **"Publicar"**. A aprovação do Google costuma levar entre 24h a 48h.

---

## 2. 📱 Como publicar na Google Play Store (App Android)

### Requisitos:
1. Conta no [Google Play Console](https://play.google.com/console) (taxa única vitalícia de $25 do Google).

### Passo a Passo Rápido:
1. Acesse o site gratuito e oficial da Microsoft/Google: [PWABuilder.com](https://www.pwabuilder.com/).
2. Cole a URL do seu app: `https://4u.ia.br/app/drop/index.php`.
3. Clique em **"Package for Store"** -> Selecione **Android**.
4. O PWABuilder gerará automaticamente o arquivo `.aab` (Android App Bundle) pronto e assinado.
5. No Google Play Console, crie um novo aplicativo e faça o upload do arquivo `.aab`.
6. Preencha a URL da Política de Privacidade (`https://4u.ia.br/app/drop/privacidade.php`) e publique!

---

## 🛡️ Lista de Verificação de Compliance do Google (100% Aprovada):
- [x] HTTPS Ativo em `4u.ia.br`
- [x] Política de Privacidade acessível publicamente
- [x] Termos de Uso e Canal de Suporte Funcional
- [x] Criptografia de Dados e Retenção Zero
- [x] Manifest V3 compatível com extensões modernas
