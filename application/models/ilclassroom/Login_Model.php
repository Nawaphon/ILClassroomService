<?php 

require("Base_Model.php");

class Login_Model extends Base_Model {

	public $username;
	public $student_id;
	public $student_name;
	public $student_surname;
	public $student_faculty;
	public $device_push_token_id;
	public $token_id;


	public function __construct() {
		parent::__construct();
	}

	public function getStudentDetail() {
		$sql = "SELECT student_id FROM exs_student WHERE student_id = ?";
		return $this->db->query($sql, array($this->username));
	}

	public function addStudent() {
		$sql = "INSERT INTO exs_student " 
			   . "(student_id, student_name, student_surname, student_faculty) "
			   . "VALUES (?, ?, ?, ?)";
		$this->db->query($sql, array($this->student_id, $this->student_name, $this->student_surname, $this->student_faculty));
	}

	public function addToken() {
		$sql = "INSERT INTO exs_student_device "
			   ."(device_push_token_id, student_id, token_id) "
			   ."VALUES (?, ?, ?)";
		$this->db->query($sql, array($this->device_push_token_id, $this->student_id, $this->token_id));
	}

	public function updateToken() {
		$sql = "UPDATE exs_student_device "
			   ."SET token_id = ? "
			   ."WHERE student_id = ?";
		$this->db->query($sql, array($this->token_id, $this->student_id));
	}

}

?>