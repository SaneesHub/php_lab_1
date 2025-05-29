<?php
namespace App\Controller;

use App\Model\AnimalModel;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController
{
    private $animalModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
    }

    public function generate($format)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        $animals = $this->animalModel->getAll();
        $sectionNames = [
            'mammals' => 'Млекопитающие',
            'reptiles' => 'Рептилии',
            'birds' => 'Птицы'
        ];

        switch ($format) {
            case 'pdf':
                $this->generatePDF($animals, $sectionNames);
                break;
            case 'excel':
                $this->generateExcel($animals, $sectionNames);
                break;
            case 'csv':
                $this->generateCSV($animals, $sectionNames);
                break;
            default:
                header("Location: /profile");
                exit;
        }
    }

    private function generatePDF($animals, $sectionNames)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Зоопарк');
        $pdf->SetTitle('Отчет по животным');
        $pdf->SetFont('dejavusans', '', 12); 

        $pdf->AddPage();

        $html = '<h1>Отчет по животным</h1>
                <table border="1">
                    <tr>
                        <th>ID</th><th>Животное</th><th>Секция</th><th>Клетка</th><th>Условия</th>
                    </tr>';

        foreach ($animals as $animal) {
            $html .= '<tr>
                <td>'.$animal['id'].'</td>
                <td>'.$animal['animal'].'</td>
                <td>'.($sectionNames[$animal['section']] ?? 'Неизвестно').'</td>
                <td>'.$animal['cage'].'</td>
                <td>'.$animal['condition_zoo'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('animals_report.pdf', 'D');
    }

    private function generateExcel($animals, $sectionNames)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Животное');
        $sheet->setCellValue('C1', 'Секция');
        $sheet->setCellValue('D1', 'Клетка');
        $sheet->setCellValue('E1', 'Условия содержания');

        $row = 2;
        foreach ($animals as $animal) {
            $sheet->setCellValue('A'.$row, $animal['id']);
            $sheet->setCellValue('B'.$row, $animal['animal']);
            $sheet->setCellValue('C'.$row, $sectionNames[$animal['section']] ?? 'Неизвестно');
            $sheet->setCellValue('D'.$row, $animal['cage']);
            $sheet->setCellValue('E'.$row, $animal['condition_zoo']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="animals_report.xlsx"');
        $writer->save('php://output');
        exit;
    }

    private function generateCSV($animals, $sectionNames)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="animals_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Животное', 'Секция', 'Клетка', 'Условия содержания'], ';');

        foreach ($animals as $animal) {
            fputcsv($output, [
                $animal['id'],
                $animal['animal'],
                $sectionNames[$animal['section']] ?? 'Неизвестно',
                $animal['cage'],
                $animal['condition_zoo']
            ], ';');
        }

        fclose($output);
        exit;
    }
}