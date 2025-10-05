# 🚨 INSTRUÇÕES DE ACESSO - URBANSTREET

## 📂 Estrutura Atual

O projeto agora usa **apenas** o `index.php` da pasta `public/` como ponto de entrada.

## 🌐 URL de Acesso

```
http://localhost/E-comerce/public/
```

## 📁 Estrutura de Arquivos

```
E-comerce/
├── 📁 public/           # ← PONTO DE ENTRADA PRINCIPAL
│   ├── index.php       # ← Arquivo principal da aplicação
│   ├── .htaccess       # ← Configurações Apache
│   ├── css/
│   ├── js/
│   └── images/
├── 📁 app/             # Aplicação MVC
├── 📁 config/          # Configurações
├── 📁 database/        # Scripts SQL
└── 📁 vendor/          # Dependências Composer
```

## ⚙️ Configuração do Apache (Opcional)

Para URLs mais limpas, configure o DocumentRoot para apontar para a pasta `public/`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/E-comerce/public"
    ServerName urbanstreet.local
    
    <Directory "C:/xampp/htdocs/E-comerce/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Com essa configuração, você acessaria apenas: `http://urbanstreet.local`

## 🔧 Benefícios da Estrutura Atual

✅ **Segurança:** Arquivos sensíveis (config, vendor) ficam fora do DocumentRoot  
✅ **Organização:** Separação clara entre público e privado  
✅ **Performance:** Assets servidos diretamente pelo Apache  
✅ **Padrão MVC:** Estrutura profissional e escalável  

## 🚀 Como Testar

1. **Acesse:** `http://localhost/E-comerce/public/`
2. **Navegue:** Explore catálogo, categorias, produtos
3. **Teste:** Newsletter, busca, carrinho

## 📝 Notas Importantes

- ⚠️ **NÃO há mais** `index.php` na raiz
- ✅ **Use sempre** `/public/` na URL
- 🔄 **Rotas ajustadas** para funcionar com `/public/`
- 🎨 **Assets corrigidos** (CSS/JS/imagens)