<?php namespace App\Activity\UserActions;
//
use Dplus\Dpluso\UserActions\ActionsPanel;
// PhpSpreadsheet Library
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style as SpreadsheetStyles;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// Dplus Spreadsheets
use Dplus\Spreadsheets;
// Controllers
use Mvc\Controllers\Controller;

class Report extends Controller {
	const COLUMNS = [
		'userid'      => ['label' => 'Rep', 'justify' => 'left'],
		'custid'      => ['label' => 'Cust ID', 'justify' => 'left'],
		'custname'    => ['label' => 'Customer Name', 'justify' => 'left'],
		'address'     => ['label' => 'City, State', 'justify' => 'left']
	];

	const STYLES_HEADER = [
		'font' => [
			'bold' => true,
			'size' => 14
		],
		'borders' => [
			'bottom' => [
				'borderStyle' => SpreadsheetStyles\Border::BORDER_THICK,
			],
		],
	];

	const STYLES_ACTION = [
		'font' => [
			'bold' => true,
			'size' => 12
		],
		'borders' => [
			'top' => [
				'borderStyle' => SpreadsheetStyles\Border::BORDER_THIN,
			],
		],
		'fill' => [
			'fillType' => SpreadsheetStyles\Fill::FILL_SOLID,
			'startColor' => [
				'rgb' => 'E6E6EA',
			],
			'endColor' => [
				'rgb' => 'E6E6EA',
			],
		],
	];

	public static function index($data = null) {
		self::sanitizeParametersShort($data, ['download|text']);

		if ($data->download) {
			return self::download($data);
		}
		return self::list($data);
	}

	private static function list($data) {
		self::initInputFilters();
		$actionpanel = new ActionsPanel(session_id(), self::pw('page')->fullURL, self::pw('input'));
		$salespersonjson = json_decode(file_get_contents(self::pw('config')->companyfiles."json/salespersontbl.json"), true);
		$actionpanel->setSalespeople($salespersonjson['data']);
		return self::pw('config')->twig->render('activity/user-actions/report/call/display.twig', ['actionpanel' => $actionpanel]);
	}

	private static function writeSpreadsheet(array $actions) {
		$spreadsheet = new PhpSpreadsheet();
		$sheet       = $spreadsheet->getActiveSheet();
		$colCount    = count(self::COLUMNS);
		Spreadsheets\Writer::setColumnsAutowidth($sheet, $colCount);

		$row = 1;
		$i   = 0;

		foreach (self::COLUMNS as $col => $colData) {
			$cell = $sheet->getCellByColumnAndRow($i + 1, $row);
			$cell->getStyle()->applyFromArray(static::STYLES_HEADER);
			$cell->getStyle()->getAlignment()->setHorizontal(Spreadsheets\Writer::getAlignmentCode($colData['justify']));
			$cell->setValue($colData['label']);
			$i++;
		}

		$row++;

		foreach ($actions as $action) {
			$i = 0;

			$data = [
				'userid' => $action->createdby,
				'custid' => $action->customerlink,
				'custname' => $action->customerName(),
				'address'  => $action->customerCityState()
			];

			foreach (self::COLUMNS as $col => $colData) {
				$cell = $sheet->getCellByColumnAndRow($i + 1, $row);
				$cell->getStyle()->applyFromArray(static::STYLES_ACTION);
				$cell->getStyle()->getAlignment()->setHorizontal(Spreadsheets\Writer::getAlignmentCode($colData['justify']));
				$cell->setValueExplicit($data[$col], DataType::TYPE_STRING);
				$i++;
			}
			$row++;

			$cell = $sheet->getCellByColumnAndRow(1, $row);
			$cell->setValueExplicit($action->textbody, DataType::TYPE_STRING);
			$colFirst = Coordinate::stringFromColumnIndex(1);
			$colLast  = Coordinate::stringFromColumnIndex(sizeof($data));
			$sheet->mergeCells("$colFirst$row:$colLast$row");
			$row++;
		}
		return $spreadsheet;
	}

	private static function initInputFilters() {
		$salespersonjson = json_decode(file_get_contents(self::pw('config')->companyfiles."json/salespersontbl.json"), true);
		$input = self::pw('input');
		$values = $input->get;

		if ($values->offsetExists('assignedto') === false) {
			$values->filter = 'filter';
			$values->assignedto = array_keys($salespersonjson['data']);
		}

		if ($values->offsetExists('datecreated') === false) {
			$values->filter = 'filter';
			$dates = [
				date('m/d/Y', strtotime('-1 year')),
				date('m/d/Y'),
			];
			$values->datecreated = $dates;
		}
	}

	private static function download($data) {
		self::initInputFilters();
		set_time_limit(240);
		$actionpanel = new ActionsPanel(session_id(), self::pw('page')->fullURL, self::pw('input'));
		$spreadsheet = self::writeSpreadsheet($actionpanel->getActionsAll());
		$writer = new Spreadsheets\Writers\Xlsx();
		$writer->filename = 'call-report';
		$writer->write($spreadsheet);
		$file = $writer->getFilepath();

		$mime = mime_content_type($file);
		header('Content-Description: File Transfer');
		header("Content-Type: $mime; charset=utf-8");
		header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Length: ' . filesize($file)); //Remove
		readfile($file);
		exit;
	}
}
