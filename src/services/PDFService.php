<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{
    public static function generateBulletinHtml(array $data): string
    {
        ob_start();
        extract($data);
        include __DIR__ . '/../templates/bulletin_template.php';
        return ob_get_clean();
    }

    public static function createPdf(string $html): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        $file = sys_get_temp_dir() . '/bulletin_' . uniqid() . '.pdf';
        file_put_contents($file, $dompdf->output());
        return $file;
    }
}
