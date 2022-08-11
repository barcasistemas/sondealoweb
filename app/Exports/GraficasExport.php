<?php
namespace App\Exports;

use App\Http\Controllers\PreguntaController;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class GraficasExport implements WithTitle, WithCharts
{
    public $sucursal;
    public $desde;
    public $hasta;

    public function __construct($sucursal, $desde, $hasta)
    {
        $this->sucursal = $sucursal;
        $this->desde    = $desde;
        $this->hasta    = $hasta;
    }

    public function title(): string
    {
        return 'Gráficas';
    }

    public function charts()
    {
        $posiciones = [
            0  => ['top' => 'A1', 'bottom' => 'J15'],
            1  => ['top' => 'L1', 'bottom' => 'U15'],
            2  => ['top' => 'A17', 'bottom' => 'J31'],
            3  => ['top' => 'L17', 'bottom' => 'U31'],
            4  => ['top' => 'A33', 'bottom' => 'J47'],
            5  => ['top' => 'L33', 'bottom' => 'U47'],
            6  => ['top' => 'A49', 'bottom' => 'J63'],
            7  => ['top' => 'L49', 'bottom' => 'U63'],
            8  => ['top' => 'A65', 'bottom' => 'J79'],
            9  => ['top' => 'L65', 'bottom' => 'U79'],
            10 => ['top' => 'A81', 'bottom' => 'J95'],
            11 => ['top' => 'L81', 'bottom' => 'U95'],
            12 => ['top' => 'A97', 'bottom' => 'J111'],
        ];

        $preguntas = PreguntaController::getInfoCharts($this->sucursal, $this->desde, $this->hasta);

        $cont   = 0;
        $charts = [];

        foreach ($preguntas as $pregunta) {
            $labels     = [];
            $categories = [];
            $values     = [];

            $lb_arr = $pregunta->labels;
            $vl_arr = $pregunta->valores;

            for ($i = 0; $i < count($lb_arr); $i++) {
                $labels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '', null, 1, [$lb_arr[$i]]);
                $values[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '', null, 1, [$vl_arr[$i]]);
            }
            $categories[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '', null, 1, ['']);

            $chart = new Chart(
                'chart',
                new Title($pregunta->pregunta),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_STANDARD, range(0, count($values) - 1), $labels, $categories, $values),
                ])
            );

            $chart->setTopLeftPosition($posiciones[$cont]['top']);
            $chart->setBottomRightPosition($posiciones[$cont]['bottom']);

            $charts[] = $chart;
            $cont++;
        }
        return $charts;
    }
}
