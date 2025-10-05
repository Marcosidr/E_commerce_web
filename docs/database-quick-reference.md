# 📊 Referência Rápida - Tabelas URBANSTREET

## 🗂️ Resumo das Tabelas

| # | Tabela | Registros | Propósito | Relacionamentos |
|---|--------|-----------|-----------|----------------|
| 1 | `categories` | 5 | Categorias de produtos | → products |
| 2 | `products` | 4 | Catálogo de produtos | categories ← → cart, order_items |
| 3 | `users` | 1 | Usuários e admins | → addresses, cart, orders |
| 4 | `addresses` | 0 | Endereços dos usuários | users ← |
| 5 | `cart` | 0 | Carrinho de compras | users, products ← |
| 6 | `orders` | 0 | Pedidos realizados | users ← → order_items |
| 7 | `order_items` | 0 | Itens dos pedidos | orders, products ← |
| 8 | `newsletter` | 0 | Assinantes newsletter | - |
| 9 | `contacts` | 0 | Contatos/suporte | - |

## 🔑 Campos Principais por Tabela

### CATEGORIES
- `id`, `name`, `slug`, `description`, `active`

### PRODUCTS  
- `id`, `name`, `slug`, `price`, `category_id`, `brand`, `featured`, `active`

### USERS
- `id`, `name`, `email`, `password`, `role` (customer/admin), `active`

### ADDRESSES
- `id`, `user_id`, `type`, `street`, `city`, `state`, `zipcode`

### CART
- `id`, `user_id`, `session_id`, `product_id`, `quantity`, `size`

### ORDERS
- `id`, `user_id`, `order_number`, `status`, `total_amount`, `payment_status`

### ORDER_ITEMS
- `id`, `order_id`, `product_id`, `quantity`, `price`, `total`

### NEWSLETTER
- `id`, `email`, `name`, `status` (active/unsubscribed)

### CONTACTS
- `id`, `name`, `email`, `subject`, `message`, `status` (new/read/replied)

## 📈 Status dos Dados

### ✅ Populadas:
- **categories** (5 categorias)
- **products** (4 produtos em destaque)  
- **users** (1 administrador)

### 🔄 Vazias (prontas para uso):
- addresses, cart, orders, order_items, newsletter, contacts

## 🎯 Funcionalidades Suportadas

| Módulo | Status | Tabelas Envolvidas |
|--------|--------|--------------------|
| **Catálogo** | ✅ | categories, products |
| **Usuários** | ✅ | users, addresses |
| **Carrinho** | ✅ | cart, products |
| **Pedidos** | ✅ | orders, order_items |
| **Newsletter** | ✅ | newsletter |
| **Contato** | ✅ | contacts |

## 🔍 Consultas Úteis

```sql
-- Produtos por categoria
SELECT p.name, c.name as category 
FROM products p JOIN categories c ON p.category_id = c.id;

-- Produtos em destaque
SELECT name, price FROM products WHERE featured = 1;

-- Total de produtos por categoria  
SELECT c.name, COUNT(p.id) as total 
FROM categories c LEFT JOIN products p ON c.id = p.category_id 
GROUP BY c.id;

-- Usuários ativos
SELECT name, email, role FROM users WHERE active = 1;
```

---
**Gerado em:** 5 de outubro de 2025  
**Versão:** 1.0