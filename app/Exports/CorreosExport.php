<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CorreosExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public $sucursal = '';

    public function __construct($sucursal){
      $this->sucursal = $sucursal;
    }

    public function collection()
    {
      $correos = DB::table('calificaciones')->select('correo','fecha')
      ->whereRaw("sucursal = '$this->sucursal' AND correo != '' AND CHAR_LENGTH(correo) > 5")
      ->orderBy('id','DESC')->limit(5000)->get();

      return $correos;
    }

    public function headings(): array
    {
        return [ 'CORREO ELECTRÓNICO', 'FECHA'];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 1);
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'SONDEALO');
        $sheet->getStyle('A1')->getFont()->setSize(20);

         $estilos = array(
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff' ],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '0658c9'],
            ],
        );
        $sheet->getStyle('A1')->applyFromArray($estilos);
        $sheet->getStyle('A2:B2')->applyFromArray($estilos);
    }
}
