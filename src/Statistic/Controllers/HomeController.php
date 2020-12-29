<?php

namespace Statistic\Controllers;

use Twig\Environment;
use Statistic\Models\Calculation;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class HomeController {

    /**
     * @var Environment
     */
    protected $_twig;

    /**
     * @var Csv
     */
    protected $_reader;

    /**
     *
     * @var string
     */
    protected $_errorMessage;

    public function __construct(Environment $twig, Csv $reader) {
        $this->_twig = $twig;
        $this->_reader = $reader;
    }

    public function index() {
        echo $this->_twig->render("home.twig");
    }

    public function calculate() {

        if ($this->validatePost()) {
            try {
                $dataValues = $this->loadFile();

                if (!empty($dataValues)) {
                    $index = new Calculation;
                    $index->setUsers($dataValues);
                    $scoreCount = $index->getCountOfUsersWithinScoreRange($_POST["rangeStart"], $_POST["rangeEnd"]);
                    $conditionCount = $index->getCountOfUsersByCondition($_POST["region"], $_POST["gender"], filter_var($_POST["legal"], FILTER_VALIDATE_BOOLEAN), filter_var($_POST["score"], FILTER_VALIDATE_BOOLEAN));
                }
                
                $variables = [ "scoreCount" => $scoreCount, "conditionCount" => $conditionCount];

            } catch (\Exception $ex) {
                $variables = [ "error" => $ex->getMessage() ];
            }
        }

        echo $this->_twig->render("home.twig", [ "variables" => $variables ]);
    }

    private function validatePost() : bool {

        if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_FILES["csvFile"]) && $_FILES["csvFile"]['error'] == 0)) {
            $file = pathinfo($_FILES["csvFile"]['name']);
            if (in_array($file['extension'], ['csv'])) {
                return true;
            } else {
                $this->_errorMessage = "File must be csv. Please try again.";
                return false;
            }
        }
    }

    private function loadFile() : array {
        $spreadsheet = $this->_reader->load($_FILES['csvFile']['tmp_name']);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        $headers = array_shift($rows);
        $data = [];

        foreach ($rows as $row) {
            $data[] = array_combine($headers, $row);
        }

        return $data;
    }

}
