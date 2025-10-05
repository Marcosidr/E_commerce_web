# 📊 Relatório do Banco de Dados - URBANSTREET E-commerce

**Data do Relatório:** 5 de outubro de 2025  
**Sistema:** E-commerce URBANSTREET  
**Banco de Dados:** urbanstreet_db  
**Versão:** 1.0  

---

## 📈 Resumo Executivo

O banco de dados URBANSTREET foi estruturado para suportar um e-commerce completo de streetwear urbano, com **9 tabelas principais** e **1 view de relatórios**, totalizando **216 linhas de código SQL**.

### 🎯 Principais Funcionalidades Suportadas:
- ✅ Gestão de produtos e categorias
- ✅ Sistema completo de usuários e autenticação
- ✅ Carrinho de compras persistente
- ✅ Sistema de pedidos e pagamentos
- ✅ Newsletter e contatos
- ✅ Múltiplos endereços por usuário

---

## 🗂️ Estrutura das Tabelas

### 1. 📦 **CATEGORIES** - Categorias de Produtos
**Propósito:** Organização hierárquica dos produtos

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `name` | VARCHAR(100) | NOT NULL | Nome da categoria |
| `slug` | VARCHAR(100) | UNIQUE | URL amigável |
| `description` | TEXT | - | Descrição da categoria |
| `image` | VARCHAR(255) | - | Imagem representativa |
| `sort_order` | INT | - | Ordem de exibição |
| `active` | BOOLEAN | - | Status ativo/inativo |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Registros Iniciais:** 5 categorias (Tênis, Camisetas, Moletons, Calças, Acessórios)

---

### 2. 👕 **PRODUCTS** - Produtos da Loja
**Propósito:** Catálogo completo de produtos streetwear

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `name` | VARCHAR(200) | NOT NULL | Nome do produto |
| `slug` | VARCHAR(200) | UNIQUE | URL amigável |
| `description` | TEXT | - | Descrição completa |
| `short_description` | VARCHAR(500) | - | Descrição resumida |
| `price` | DECIMAL(10,2) | NOT NULL | Preço atual |
| `compare_price` | DECIMAL(10,2) | - | Preço de comparação |
| `cost_price` | DECIMAL(10,2) | - | Preço de custo |
| `sku` | VARCHAR(100) | - | Código do produto |
| `stock_quantity` | INT | - | Quantidade em estoque |
| `category_id` | INT | FK | Referência à categoria |
| `brand` | VARCHAR(100) | - | Marca do produto |
| `image` | VARCHAR(255) | - | Imagem principal |
| `images` | JSON | - | Galeria de imagens |
| `size_guide` | VARCHAR(255) | - | Guia de tamanhos |
| `care_instructions` | TEXT | - | Cuidados do produto |
| `featured` | BOOLEAN | - | Produto em destaque |
| `active` | BOOLEAN | - | Status ativo/inativo |
| `meta_title` | VARCHAR(200) | - | SEO - Título |
| `meta_description` | VARCHAR(300) | - | SEO - Descrição |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Relacionamentos:** FK com `categories(id)`  
**Registros Iniciais:** 4 produtos em destaque

---

### 3. 👤 **USERS** - Usuários do Sistema
**Propósito:** Gestão de clientes e administradores

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `name` | VARCHAR(100) | NOT NULL | Nome completo |
| `email` | VARCHAR(150) | UNIQUE | E-mail único |
| `email_verified_at` | TIMESTAMP | - | Verificação de e-mail |
| `password` | VARCHAR(255) | NOT NULL | Senha criptografada |
| `phone` | VARCHAR(20) | - | Telefone |
| `birth_date` | DATE | - | Data de nascimento |
| `gender` | ENUM | - | Gênero (M/F/Other) |
| `role` | ENUM | - | Perfil (customer/admin) |
| `active` | BOOLEAN | - | Status ativo/inativo |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Usuário Padrão:** Administrador URBANSTREET

---

### 4. 📍 **ADDRESSES** - Endereços dos Usuários
**Propósito:** Múltiplos endereços por usuário (cobrança/entrega)

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `user_id` | INT | FK | Referência ao usuário |
| `type` | ENUM | - | billing/shipping/both |
| `name` | VARCHAR(100) | - | Nome do endereço |
| `street` | VARCHAR(200) | NOT NULL | Logradouro |
| `number` | VARCHAR(20) | NOT NULL | Número |
| `complement` | VARCHAR(100) | - | Complemento |
| `neighborhood` | VARCHAR(100) | NOT NULL | Bairro |
| `city` | VARCHAR(100) | NOT NULL | Cidade |
| `state` | VARCHAR(2) | NOT NULL | Estado (UF) |
| `zipcode` | VARCHAR(10) | NOT NULL | CEP |
| `country` | VARCHAR(2) | - | País (padrão: BR) |
| `is_default` | BOOLEAN | - | Endereço padrão |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Relacionamentos:** FK com `users(id)` ON DELETE CASCADE

---

### 5. 🛒 **CART** - Carrinho de Compras
**Propósito:** Carrinho persistente para usuários logados e sessões

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `user_id` | INT | FK | Usuário logado |
| `session_id` | VARCHAR(255) | - | Sessão anônima |
| `product_id` | INT | FK | Produto no carrinho |
| `quantity` | INT | - | Quantidade |
| `size` | VARCHAR(10) | - | Tamanho selecionado |
| `color` | VARCHAR(50) | - | Cor selecionada |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Relacionamentos:** FK com `users(id)` e `products(id)` ON DELETE CASCADE

---

### 6. 📋 **ORDERS** - Pedidos
**Propósito:** Controle completo de pedidos e status

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `user_id` | INT | FK | Cliente do pedido |
| `order_number` | VARCHAR(50) | UNIQUE | Número do pedido |
| `status` | ENUM | - | Status do pedido |
| `subtotal` | DECIMAL(10,2) | NOT NULL | Subtotal |
| `tax_amount` | DECIMAL(10,2) | - | Valor dos impostos |
| `shipping_amount` | DECIMAL(10,2) | - | Valor do frete |
| `discount_amount` | DECIMAL(10,2) | - | Desconto aplicado |
| `total_amount` | DECIMAL(10,2) | NOT NULL | Valor total |
| `currency` | VARCHAR(3) | - | Moeda (BRL) |
| `payment_method` | VARCHAR(50) | - | Método de pagamento |
| `payment_status` | ENUM | - | Status do pagamento |
| `shipping_method` | VARCHAR(50) | - | Método de envio |
| `tracking_code` | VARCHAR(100) | - | Código de rastreamento |
| `notes` | TEXT | - | Observações |
| `shipped_at` | TIMESTAMP | - | Data de envio |
| `delivered_at` | TIMESTAMP | - | Data de entrega |
| `created_at` | TIMESTAMP | - | Data de criação |
| `updated_at` | TIMESTAMP | - | Data de atualização |

**Status Possíveis:** pending, processing, shipped, delivered, cancelled, refunded  
**Relacionamentos:** FK com `users(id)` ON DELETE CASCADE

---

### 7. 📦 **ORDER_ITEMS** - Itens dos Pedidos
**Propósito:** Detalhamento dos produtos em cada pedido

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `order_id` | INT | FK | Pedido relacionado |
| `product_id` | INT | FK | Produto comprado |
| `product_name` | VARCHAR(200) | NOT NULL | Nome no momento da compra |
| `product_sku` | VARCHAR(100) | - | SKU no momento da compra |
| `quantity` | INT | NOT NULL | Quantidade comprada |
| `price` | DECIMAL(10,2) | NOT NULL | Preço unitário |
| `total` | DECIMAL(10,2) | NOT NULL | Total do item |
| `size` | VARCHAR(10) | - | Tamanho comprado |
| `color` | VARCHAR(50) | - | Cor comprada |
| `created_at` | TIMESTAMP | - | Data de criação |

**Relacionamentos:** FK com `orders(id)` e `products(id)` ON DELETE CASCADE

---

### 8. 📧 **NEWSLETTER** - Assinantes da Newsletter
**Propósito:** Gestão de e-mail marketing

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `email` | VARCHAR(150) | UNIQUE | E-mail do assinante |
| `name` | VARCHAR(100) | - | Nome do assinante |
| `status` | ENUM | - | active/unsubscribed |
| `subscribed_at` | TIMESTAMP | - | Data de inscrição |
| `unsubscribed_at` | TIMESTAMP | - | Data de cancelamento |

---

### 9. 📞 **CONTACTS** - Contatos/Suporte
**Propósito:** Sistema de contato e suporte ao cliente

| Campo | Tipo | Chave | Descrição |
|-------|------|-------|-----------|
| `id` | INT | PK, AI | Identificador único |
| `name` | VARCHAR(100) | NOT NULL | Nome do contato |
| `email` | VARCHAR(150) | NOT NULL | E-mail para resposta |
| `phone` | VARCHAR(20) | - | Telefone |
| `subject` | VARCHAR(200) | - | Assunto da mensagem |
| `message` | TEXT | NOT NULL | Mensagem completa |
| `status` | ENUM | - | new/read/replied |
| `created_at` | TIMESTAMP | - | Data de criação |

---

## 📊 Views e Relatórios

### **PRODUCT_STATS** - Estatísticas de Produtos
**Propósito:** View para relatórios de vendas e performance

**Campos Disponíveis:**
- `id`, `name`, `price` - Dados básicos do produto
- `category_name` - Nome da categoria
- `stock_quantity` - Estoque atual
- `featured`, `active` - Status do produto
- `total_sold` - Total de unidades vendidas
- `revenue` - Receita gerada pelo produto

---

## 🔍 Índices de Performance

### Índices Criados:
- `idx_products_category` - Busca por categoria
- `idx_products_featured` - Produtos em destaque
- `idx_products_active` - Produtos ativos
- `idx_products_slug` - URLs amigáveis
- `idx_categories_slug` - Categorias por slug
- `idx_orders_user` - Pedidos por usuário
- `idx_orders_status` - Pedidos por status
- `idx_cart_user` - Carrinho por usuário
- `idx_cart_session` - Carrinho por sessão
- `idx_newsletter_email` - Newsletter por e-mail

---

## 🔗 Relacionamentos

```
users (1) ──→ (N) addresses
users (1) ──→ (N) cart
users (1) ──→ (N) orders

categories (1) ──→ (N) products

orders (1) ──→ (N) order_items
products (1) ──→ (N) order_items
products (1) ──→ (N) cart
```

---

## 📋 Dados Iniciais

### Categorias (5 registros):
1. Tênis - Tênis exclusivos para streetwear urbano
2. Camisetas - Camisetas com estilo urbano autêntico  
3. Moletons - Moletons confortáveis para o dia a dia
4. Calças - Calças modernas com atitude urbana
5. Acessórios - Acessórios para completar seu look

### Produtos em Destaque (4 registros):
1. **Air Force Urban Black** - R$ 599,90 (NIKE)
2. **Dunk Low Street Edition** - R$ 749,90 (NIKE)
3. **Oversized Tee Black** - R$ 149,90 (URBAN STYLE)
4. **Hoodie Oversized Gray** - R$ 299,90 (URBAN STYLE)

### Usuário Administrador:
- **E-mail:** admin@urbanstreet.com
- **Senha:** admin123 (criptografada)

---

## 🚀 Recomendações Técnicas

### ✅ Implementado:
- Chaves estrangeiras com integridade referencial
- Timestamps automáticos
- Índices para performance
- View para relatórios
- Dados de exemplo para testes

### 🔄 Melhorias Futuras:
- Backup automático diário
- Logs de auditoria
- Cache de queries frequentes
- Particionamento de tabelas grandes
- Monitoramento de performance

---

## 📞 Contato da Equipe

**Desenvolvedor:** URBANSTREET Development Team  
**Data:** 5 de outubro de 2025  
**Versão do Banco:** 1.0  

---

*Este relatório foi gerado automaticamente a partir da estrutura SQL do projeto URBANSTREET E-commerce.*