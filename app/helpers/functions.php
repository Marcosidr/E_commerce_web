<?php

/**
 * Helper functions para URBANSTREET
 */

if (!function_exists('getProductImageUrl')) {
    /**
     * Gera URL da imagem do produto baseada no ID
     * Procura por imagens nos formatos: jpg, jpeg, png, webp
     * Fallback para imagem padrão se não encontrar
     */
    function getProductImageUrl($productId, $size = 'medium') {
        // Primeiro, tentar recuperar imagens associadas ao produto no banco
        try {
            $productModel = new \App\Models\Product();
            $imgs = $productModel->getImages((int)$productId);
            if (!empty($imgs)) {
                // Retornar a primeira imagem cadastrada (caminho relativo salvo no DB)
                $first = $imgs[0]['image_path'] ?? '';
                if ($first) {
                    return BASE_URL . '/' . ltrim($first, '/');
                }
            }
        } catch (\Throwable $e) {
            // se algo falhar ao instanciar o model, continuar para fallback por arquivo
        }

        // Fallback: procurar por arquivo nomeado pelo ID (compatibilidade antiga)
        $basePath = PUBLIC_PATH . '/images/products/';
        $baseUrl = BASE_URL . '/images/products/';

        // Formatos suportados em ordem de preferência
        $formats = ['webp', 'jpg', 'jpeg', 'png', 'svg'];

        // Tamanhos disponíveis
        $sizePrefix = '';
        switch ($size) {
            case 'thumb':
                $sizePrefix = 'thumb_';
                break;
            case 'large':
                $sizePrefix = 'large_';
                break;
            case 'medium':
            default:
                $sizePrefix = '';
                break;
        }

        // Procura pela imagem em diferentes formatos
        foreach ($formats as $format) {
            $filename = $sizePrefix . $productId . '.' . $format;
            $filePath = $basePath . $filename;

            if (file_exists($filePath)) {
                return $baseUrl . $filename;
            }
        }

        // Fallback para imagem padrão
        return BASE_URL . '/images/no-product.jpg';
    }
}

if (!function_exists('getCategoryImageUrl')) {
    /**
     * Gera URL da imagem da categoria baseada no slug
     */
    function getCategoryImageUrl($categorySlug) {
        $basePath = PUBLIC_PATH . '/images/categories/';
        $baseUrl = BASE_URL . '/images/categories/';
        
        $formats = ['webp', 'jpg', 'jpeg', 'png'];
        
        foreach ($formats as $format) {
            $filename = $categorySlug . '.' . $format;
            $filePath = $basePath . $filename;
            
            if (file_exists($filePath)) {
                return $baseUrl . $filename;
            }
        }
        
        return BASE_URL . '/images/no-category.jpg';
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Formata preço para o padrão brasileiro
     */
    function formatPrice($price) {
        return 'R$ ' . number_format((float)$price, 2, ',', '.');
    }
}

if (!function_exists('sanitizeString')) {
    /**
     * Sanitiza string para exibição
     */
    function sanitizeString($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('truncateText')) {
    /**
     * Trunca texto com reticências
     */
    function truncateText($text, $limit = 100, $suffix = '...') {
        if (strlen($text) <= $limit) {
            return $text;
        }
        return substr($text, 0, $limit) . $suffix;
    }
}

if (!function_exists('getCategoryUrl')) {
    /**
     * Gera URL do catálogo filtrado por categoria
     */
    function getCategoryUrl($categorySlug) {
        $categoryMap = [
            'tenis' => 1,
            'camisetas' => 2,
            'moletons' => 3,
            'calcas' => 4,
            'calca' => 4, // compatibilidade
            'acessorios' => 5
        ];
        
        $categoryId = $categoryMap[$categorySlug] ?? null;
        if ($categoryId) {
            return BASE_URL . "/catalogo?category={$categoryId}";
        }
        
        return BASE_URL . "/catalogo";
    }
}