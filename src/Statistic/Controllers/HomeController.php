<?php

namespace Statistic\Controllers;

use Twig\Environment;

class HomeController {

    /**
     * @var Environment
     */
    protected $_twig;

    public function __construct(Environment $twig) {
        $this->_twig = $twig;
    }

    public function index() {
        echo $this->_twig->render("home.twig");
    }

}
