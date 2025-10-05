# 🏷️ URBANSTREET - E-commerce Streetwear

> **Estilo Urbano Autêntico** - Sistema de e-commerce desenvolvido em PHP com arquitetura MVC para loja de streetwear.

## ✨ Sobre o Projeto

URBANSTREET é uma loja online especializada em moda streetwear, oferecendo tênis, camisetas, moletons, calças e acessórios para quem vive a cultura urbana. O sistema foi desenvolvido do zero usando PHP puro com padrão MVC.

## 🚀 Funcionalidades

### ✅ **Core System**
- Arquitetura MVC completa e organizada
- Sistema de roteamento personalizado com URLs amigáveis
- Autoloader PSR-4 com Composer
- Conexão com banco MySQL usando PDO e Singleton
- Sistema de templates com layouts reutilizáveis

### ✅ **Frontend**
- Design responsivo inspirado em lojas de streetwear modernas
- Interface desenvolvida com Bootstrap 5
- Animações CSS3 e JavaScript interativo
- Otimização para mobile-first
- Lazy loading de imagens

### ✅ **Funcionalidades de E-commerce**
- Catálogo de produtos com paginação
- Sistema de categorias (Tênis, Camisetas, Moletons, etc.)
- Busca de produtos em tempo real
- Páginas detalhadas de produtos
- Produtos relacionados e em destaque
- Carrinho de compras (localStorage)
- Newsletter com integração AJAX

### ✅ **Administração**
- Painel administrativo (em desenvolvimento)
- Gerenciamento de produtos e categorias
- Sistema de usuários com roles
- Relatórios de vendas

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8.0+, PDO, MySQL
- **Frontend:** HTML5, CSS3, JavaScript ES6+, Bootstrap 5
- **Banco de Dados:** MySQL 8.0+
- **Ferramentas:** Composer, XAMPP
- **Arquitetura:** MVC (Model-View-Controller)
- **Versionamento:** Git & GitHub

## 📋 Pré-requisitos

- **XAMPP** (Apache + MySQL + PHP 8.0+)
- **Composer** (para gerenciamento de dependências)
- **Git** (para versionamento)
- **Navegador moderno** (Chrome, Firefox, Safari, Edge)

## 🔧 Instalação

### 1. **Clone o Repositório**
```bash
git clone https://github.com/Marcosidr/E_commerce_web.git
cd E_commerce_web
```

### 2. **Instale as Dependências**
```bash
composer install
```

### 3. **Configure o Banco de Dados**
- Abra o **phpMyAdmin** (http://localhost/phpmyadmin)
- Execute o script `database/urbanstreet.sql`
- Ajuste as configurações em `config/database.php` se necessário

### 4. **Configure o Servidor**
- Certifique-se que o projeto está em `C:\xampp\htdocs\E-comerce`
- Inicie **Apache** e **MySQL** no XAMPP Control Panel
- Configure a constante `BASE_URL` em `index.php` se necessário

### 5. **Acesse o Sistema**
```
http://localhost/E-comerce
```

## 📁 Estrutura do Projeto

```
E-comerce/
├── 📁 app/                     # Aplicação principal
│   ├── 📁 controllers/         # Controllers MVC
│   │   ├── HomeController.php  # Página inicial e institucionais  
│   │   └── ProductController.php # Catálogo e produtos
│   ├── 📁 core/               # Classes base do framework
│   │   ├── Controller.php     # Controller base
│   │   ├── Model.php         # Model base
│   │   ├── Router.php        # Sistema de roteamento
│   │   └── Database.php      # Conexão com banco
│   ├── 📁 models/            # Models de dados
│   │   ├── Product.php       # Modelo de produtos
│   │   └── Category.php      # Modelo de categorias
│   └── 📁 views/             # Templates e layouts
│       ├── 📁 layouts/       # Layouts reutilizáveis
│       ├── 📁 home/          # Views da home
│       ├── 📁 products/      # Views de produtos
│       └── 📁 errors/        # Páginas de erro
├── 📁 config/                 # Configurações
│   ├── database.php          # Config do banco
│   └── routes.php            # Definição das rotas
├── 📁 database/              # Scripts SQL
│   └── urbanstreet.sql       # Estrutura do banco
├── 📁 public/                # Assets públicos
│   ├── 📁 css/              # Estilos
│   ├── 📁 js/               # JavaScript
│   └── 📁 images/           # Imagens
├── 📁 vendor/                # Dependências Composer
├── .gitignore               # Arquivos ignorados pelo Git
├── composer.json            # Configurações Composer
└── index.php               # Ponto de entrada
```

## 🎯 Como Usar

### **Criando um Controller**
```php
<?php
namespace App\Controllers;

use App\Core\Controller;

class MeuController extends Controller
{
    public function index()
    {
        $this->loadView('minha/index', [
            'title' => 'Minha Página',
            'data' => $dados
        ]);
    }
}
```

### **Criando um Model**
```php
<?php
namespace App\Models;

use App\Core\Model;

class MeuModel extends Model
{
    protected $table = 'minha_tabela';
    
    public function buscarPorSlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }
}
```

### **Adicionando Rotas**
```php
// config/routes.php
$router->add('/^minha-rota\/(?P<slug>[a-z0-9-]+)$/', 'Meu', 'index');
```

## 🔐 Credenciais de Acesso

### **Administrador Padrão**
- **Email:** admin@urbanstreet.com
- **Senha:** admin123
- **Tipo:** Administrador

## 🌟 Principais Páginas

- **🏠 Home:** Produtos em destaque, categorias, newsletter
- **🛍️ Catálogo:** Lista completa de produtos com paginação
- **📂 Categorias:** Produtos filtrados por categoria
- **📄 Produto:** Detalhes, imagens, produtos relacionados
- **🔍 Busca:** Pesquisa de produtos por termo
- **ℹ️ Sobre:** Informações da empresa
- **📞 Contato:** Formulário de contato

## 📱 Responsividade

- ✅ **Mobile First Design**
- ✅ **Breakpoints Bootstrap 5**
- ✅ **Touch-friendly Interface**
- ✅ **Fast Loading**
- ✅ **Cross-browser Compatible**

## 🚧 Roadmap

### **Próximas Implementações**
- [ ] Sistema de autenticação completo
- [ ] Carrinho de compras funcional
- [ ] Sistema de pedidos
- [ ] Integração com meios de pagamento
- [ ] Painel administrativo completo
- [ ] Sistema de avaliações
- [ ] Wishlist de produtos
- [ ] Cupons de desconto
- [ ] API REST
- [ ] PWA (Progressive Web App)
- [ ] Testes automatizados

## 🤝 Contribuição

1. Faça um **fork** do projeto
2. Crie uma branch para sua feature: `git checkout -b feature/MinhaFeature`
3. Commit suas mudanças: `git commit -m 'Add: Nova funcionalidade'`
4. Push para a branch: `git push origin feature/MinhaFeature`
5. Abra um **Pull Request**

## 📄 Licença

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Marcos Inácio**
- 📧 Email: marcosincio556@gmail.com
- 🐙 GitHub: [@Marcosidr](https://github.com/Marcosidr)
- 💼 LinkedIn: [Marcos Inácio](https://linkedin.com/in/marcosidr)

---

<div align="center">

**🏷️ URBANSTREET** - *Estilo Urbano Autêntico*

⭐ **Se este projeto te ajudou, deixe uma estrela!** ⭐

</div>