<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DashboardReportsController extends Controller
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index()
    {
        // Verifica admin
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $type = $_GET['type'] ?? '';
        $from = $_GET['from'] ?? '';
        $to   = $_GET['to'] ?? '';
        $data = null;
        if ($type) {
            $data = $this->buildReport($type, $from, $to);
        }
        $this->loadPartial('Painel/relatorios/index', [
            'title' => 'Relatórios - Dashboard',
            'type' => $type,
            'from' => $from,
            'to' => $to,
            'report' => $data
        ]);
    }

    public function exportProducts()
    {
        $format = $this->detectFormatFromUri('produtos');
        [$clause,$params] = $this->buildDateWhere($_GET['from'] ?? '', $_GET['to'] ?? '');
        $sql = "SELECT id, nome, preco, estoque, destaque, ativo, criado_em FROM produtos".$clause." ORDER BY id DESC";
        $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k,$v); $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->exportRows('produtos', $rows, $format);
    }

    public function exportOrders()
    {
        $format = $this->detectFormatFromUri('pedidos');
        try{
            [$clause,$params] = $this->buildDateWhere($_GET['from'] ?? '', $_GET['to'] ?? '');
            $sql = "SELECT id, usuario_id, status, total, criado_em FROM pedidos".$clause." ORDER BY id DESC";
            $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k,$v); $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Throwable $e){ $rows = []; }
        $this->exportRows('pedidos', $rows, $format);
    }

    public function exportCustomers()
    {
        $format = $this->detectFormatFromUri('clientes');
        try{
            [$clause,$params] = $this->buildDateWhere($_GET['from'] ?? '', $_GET['to'] ?? '');
            $sql = "SELECT id, nome, email, telefone, criado_em FROM usuarios WHERE ativo = 1" . ($clause? str_replace(' WHERE ',' AND ',$clause): '') . " ORDER BY criado_em DESC";
            $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k,$v); $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Throwable $e){ $rows = []; }
        $this->exportRows('clientes', $rows, $format);
    }

    public function exportDailySales()
    {
        $format = $this->detectFormatFromUri('vendas_diario');
        try{
            [$clause,$params] = $this->buildDateWhere($_GET['from'] ?? '', $_GET['to'] ?? '');
            $sql = "SELECT DATE(criado_em) as dia, COUNT(*) as total_pedidos, SUM(total) as total_vendas FROM pedidos".$clause." GROUP BY DATE(criado_em) ORDER BY dia DESC";
            $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k,$v); $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Throwable $e){ $rows = []; }
        $this->exportRows('vendas_diario', $rows, $format);
    }

    public function exportSales()
    {
        // Exporta pedidos (vendas) em formato detalhado; similar a exportOrders
        return $this->exportOrders();
    }

    private function exportRows(string $baseName, array $rows, string $format): void
    {
        $filename = $baseName . '_' . date('Ymd_His');
        switch ($format) {
            case 'csv':
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=' . $filename . '.csv');
                $out = fopen('php://output', 'w');
                if (!empty($rows)) {
                    fputcsv($out, array_keys($rows[0]));
                    foreach ($rows as $r) { fputcsv($out, $r); }
                }
                fclose($out);
                break;
            case 'xlsx':
                // Entrega CSV compatível com Excel como solução inicial (xlsx real exige biblioteca)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename . '.xls');
                echo $this->arrayToHtmlTable($rows);
                break;
            case 'pdf':
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename=' . $filename . '.pdf');
                // Placeholder: entrega PDF simples via conversão mínima
                // Para PDF real, integrar mPDF/TCPDF via Composer.
                echo "%PDF-1.4\n% Dashboard export placeholder\n"; // conteúdo não-renderizável completo
                break;
            case 'docx':
                header('Content-Type: application/msword');
                header('Content-Disposition: attachment; filename=' . $filename . '.doc');
                echo '<html><body>' . $this->arrayToHtmlTable($rows) . '</body></html>';
                break;
            default:
                header('Content-Type: application/json');
                echo json_encode($rows);
        }
        exit;
    }

    private function arrayToHtmlTable(array $rows): string
    {
        if (empty($rows)) return '<table border="1"><tr><td>Sem dados</td></tr></table>';
        $html = '<table border="1"><thead><tr>';
        foreach (array_keys($rows[0]) as $h) { $html .= '<th>'.htmlspecialchars((string)$h).'</th>'; }
        $html .= '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $html .= '<tr>';
            foreach ($r as $v) { $html .= '<td>'.htmlspecialchars((string)$v).'</td>'; }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }

    private function detectFormatFromUri(string $base): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match('/'.preg_quote($base,'/').'\.(csv|xlsx|pdf|docx)$/i', $uri, $m)) {
            return strtolower($m[1]);
        }
        return 'csv';
    }

    private function buildReport(string $type, string $from = '', string $to = ''): ?array
    {
        [$clause,$params,$where] = $this->buildDateWhere($from, $to, true);

        try {
            switch ($type) {
                case 'produtos':
                    $sql = "SELECT id, nome, preco, estoque, destaque, ativo, criado_em FROM produtos" . $clause . " ORDER BY id DESC LIMIT 1000";
                    $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k, $v); $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    return ['columns'=>array_keys($rows[0] ?? ['id'=>null,'name'=>null]), 'rows'=>$rows];

                case 'pedidos':
                case 'vendas':
                    $sql = "SELECT id, usuario_id, status, total, criado_em FROM pedidos" . $clause . " ORDER BY id DESC LIMIT 1000";
                    $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k, $v); $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    return ['columns'=>array_keys($rows[0] ?? ['id'=>null,'user_id'=>null]), 'rows'=>$rows];

                case 'vendas_diario':
                    // Para período, aplica nos limites de created_at e agrupa por dia
                    $base = "SELECT DATE(criado_em) as dia, COUNT(*) as total_pedidos, SUM(total) as total_vendas FROM pedidos" . $clause . " GROUP BY DATE(criado_em) ORDER BY dia DESC";
                    $stmt = $this->db->prepare($base); foreach ($params as $k=>$v) $stmt->bindValue($k, $v); $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    return ['columns'=>['dia','total_pedidos','total_vendas'], 'rows'=>$rows];

                case 'clientes':
                    $sql = "SELECT id, nome, email, telefone, criado_em FROM usuarios WHERE ativo = 1" . ($where? ' AND ' . implode(' AND ', $where): '') . " ORDER BY id DESC LIMIT 1000";
                    $stmt = $this->db->prepare($sql); foreach ($params as $k=>$v) $stmt->bindValue($k, $v); $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    return ['columns'=>array_keys($rows[0] ?? ['id'=>null,'name'=>null]), 'rows'=>$rows];

                case 'visao_geral':
                case 'tudo':
                    // Resumo com contagens principais
                    $counts = [];
                    $counts['total_produtos'] = (int)($this->db->query('SELECT COUNT(*) FROM produtos')->fetchColumn() ?: 0);
                    try { $counts['total_pedidos'] = (int)($this->db->query('SELECT COUNT(*) FROM pedidos')->fetchColumn() ?: 0); } catch (\Throwable $e) { $counts['total_pedidos'] = 0; }
                    $counts['total_clientes'] = (int)($this->db->query('SELECT COUNT(*) FROM usuarios WHERE ativo = 1')->fetchColumn() ?: 0);
                    $rows = [$counts];
                    return ['columns'=>array_keys($rows[0]), 'rows'=>$rows];
            }
        } catch (\Throwable $e) {
            // Fall-through: sem dados
        }
        return null;
    }

    private function buildDateWhere(string $from = '', string $to = '', bool $returnWhereParts = false): array
    {
        $where = [];
        $params = [];
        if ($from) { $where[] = 'criado_em >= :from'; $params[':from'] = $from . ' 00:00:00'; }
        if ($to)   { $where[] = 'criado_em <= :to';   $params[':to']   = $to   . ' 23:59:59'; }
        $clause = $where ? (' WHERE ' . implode(' AND ', $where)) : '';
        return $returnWhereParts ? [$clause,$params,$where] : [$clause,$params];
    }
}
