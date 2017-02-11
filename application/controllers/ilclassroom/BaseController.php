<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {

	public $currentTerm;
	public $currentYear;

	public function __construct() {
		parent::__construct();
		$this->currentTerm = 2;
		$this->currentYear = "2559";
	}

}

?>