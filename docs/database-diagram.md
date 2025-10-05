# 🏗️ Diagrama de Relacionamentos - URBANSTREET Database

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   CATEGORIES    │    │    PRODUCTS     │    │      CART       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • id (PK)       │◄──┐│ • id (PK)       │◄──┐│ • id (PK)       │
│ • name          │   ││ • name          │   ││ • user_id (FK)  │
│ • slug          │   ││ • slug          │   ││ • session_id    │
│ • description   │   ││ • price         │   ││ • product_id(FK)│
│ • active        │   ││ • category_id(FK)│   ││ • quantity      │
│ • created_at    │   ││ • brand         │   ││ • size          │
└─────────────────┘   ││ • featured      │   │└─────────────────┘
                      ││ • active        │   │         ▲
                      │└─────────────────┘   │         │
                      │                      │         │
┌─────────────────┐   │                      │         │
│     USERS       │   │                      │         │
├─────────────────┤   │                      │         │
│ • id (PK)       │◄──┼──────────────────────┘         │
│ • name          │   │                                │
│ • email         │   │                                │
│ • password      │   │                                │
│ • role          │   │┌─────────────────┐             │
│ • active        │   ││   ADDRESSES     │             │
│ • created_at    │   ││ ├─────────────────┤             │
└─────────────────┘   ││ • id (PK)       │             │
         │            ││ • user_id (FK)  │◄────────────┘
         │            ││ • type          │
         │            ││ • street        │
         │            ││ • city          │
         │            ││ • state         │
         │            │└─────────────────┘
         │            │
         │            │┌─────────────────┐
         │            ││     ORDERS      │
         │            │├─────────────────┤
         │            ││ • id (PK)       │
         │            ││ • user_id (FK)  │◄─────────────┐
         │            ││ • order_number  │              │
         │            ││ • status        │              │
         │            ││ • total_amount  │              │
         │            ││ • payment_status│              │
         │            │└─────────────────┘              │
         │            │         │                       │
         │            │         │                       │
         │            │         ▼                       │
         │            │┌─────────────────┐              │
         │            ││  ORDER_ITEMS    │              │
         │            │├─────────────────┤              │
         │            ││ • id (PK)       │              │
         │            ││ • order_id (FK) │──────────────┘
         │            ││ • product_id(FK)│──────────────┐
         │            ││ • quantity      │              │
         │            ││ • price         │              │
         └────────────┼┤ • total         │              │
                      │└─────────────────┘              │
                      │                                 │
                      └─────────────────────────────────┘

┌─────────────────┐    ┌─────────────────┐
│   NEWSLETTER    │    │    CONTACTS     │
├─────────────────┤    ├─────────────────┤
│ • id (PK)       │    │ • id (PK)       │
│ • email         │    │ • name          │
│ • name          │    │ • email         │
│ • status        │    │ • subject       │
│ • subscribed_at │    │ • message       │
└─────────────────┘    │ • status        │
                       └─────────────────┘

LEGENDAS:
━━━  Relacionamento 1:N (One-to-Many)
──►  Chave Estrangeira (Foreign Key)
PK   Chave Primária (Primary Key)  
FK   Chave Estrangeira (Foreign Key)
```

## 📊 Cardinalidades

| Relacionamento | Tipo | Descrição |
|----------------|------|-----------|
| users → addresses | 1:N | Um usuário pode ter vários endereços |
| users → cart | 1:N | Um usuário pode ter vários itens no carrinho |
| users → orders | 1:N | Um usuário pode fazer vários pedidos |
| categories → products | 1:N | Uma categoria pode ter vários produtos |
| products → cart | 1:N | Um produto pode estar em vários carrinhos |
| products → order_items | 1:N | Um produto pode estar em vários pedidos |
| orders → order_items | 1:N | Um pedido pode ter vários itens |

## 🔒 Integridade Referencial

### ON DELETE CASCADE:
- `addresses.user_id` → Se usuário for deletado, endereços são removidos
- `cart.user_id` → Se usuário for deletado, carrinho é limpo
- `orders.user_id` → Se usuário for deletado, pedidos são removidos
- `order_items.order_id` → Se pedido for deletado, itens são removidos

### ON DELETE SET NULL:
- `products.category_id` → Se categoria for deletada, produtos ficam sem categoria

---
**Diagrama atualizado em:** 5 de outubro de 2025