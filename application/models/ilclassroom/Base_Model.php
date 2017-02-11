<?php

class Base_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('ilclassroom', TRUE);
		$this->dbname = $this->db->database;
	}

	public function getStudentId($tokenId) {
		$sql = "SELECT student_id FROM exs_student_device WHERE token_id = ?";
		return $this->db->query($sql, array($tokenId));
	}

}