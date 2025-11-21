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

    public function exportProducts()
    {
        $format = $this->detectFormatFromUri('produtos');
        $rows = $this->db->query("SELECT id, name, price, stock_quantity, featured, active FROM products ORDER BY id DESC")
                         ->fetchAll(\PDO::FETCH_ASSOC);
        $this->exportRows('produtos', $rows, $format);
    }

    public function exportOrders()
    {
        $format = $this->detectFormatFromUri('pedidos');
        try{
            $rows = $this->db->query("SELECT id, user_id, status, total_amount, created_at FROM orders ORDER BY id DESC")
                             ->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Throwable $e){ $rows = []; }
        $this->exportRows('pedidos', $rows, $format);
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
}
