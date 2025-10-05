# 📸 Estrutura de Imagens - URBANSTREET

## 📁 Organização das Pastas

### `/public/images/`
Pasta principal para todas as imagens do projeto.

### 📂 **Subpastas:**

#### `/products/`
- **Finalidade**: Imagens dos produtos da loja
- **Formato recomendado**: JPG, PNG, WebP
- **Resolução**: 800x800px (quadrado) para melhor exibição
- **Nomenclatura**: `produto_id_001.jpg`, `produto_id_002.jpg`

#### `/categories/`
- **Finalidade**: Imagens representativas das categorias
- **Formato recomendado**: JPG, PNG
- **Resolução**: 400x300px (landscape)
- **Nomenclatura**: `categoria_camisetas.jpg`, `categoria_calcas.jpg`

#### `/banners/`
- **Finalidade**: Banners promocionais e da homepage
- **Formato recomendado**: JPG, PNG, WebP
- **Resolução**: 1920x400px para banners principais
- **Nomenclatura**: `banner_home.jpg`, `banner_promocao.jpg`

#### `/icons/`
- **Finalidade**: Logos, favicons e ícones da interface  
- **Formato recomendado**: PNG, SVG
- **Resolução**: Variável conforme uso
- **Nomenclatura**: `logo.png`, `favicon.ico`, `icon_carrinho.svg`

## 🔗 **Como usar no código:**

```php
// URL das imagens nos templates
$productImage = BASE_URL . '/images/products/produto_1.jpg';
$categoryImage = BASE_URL . '/images/categories/categoria_streetwear.jpg';
$bannerImage = BASE_URL . '/images/banners/banner_promocao.jpg';
```

## 📋 **Dicas:**

1. **Otimização**: Comprima as imagens antes de fazer upload
2. **SEO**: Use nomes descritivos nos arquivos
3. **Responsivo**: Considere diferentes tamanhos para mobile
4. **Fallback**: Usar `no-image.svg` quando imagem não existir

## 🚀 **Pronto para uso!**
Basta colocar suas imagens nas pastas correspondentes e referenciar no código PHP.