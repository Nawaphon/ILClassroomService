<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require("BaseController.php");

class Drawer extends BaseController {

	public function __construct() {
		parent::__construct();
       $this->load->model("/ilclassroom/Drawer_Model", 'drawer');
	}

	public function getListNewActivity() {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$jsonData = json_decode(file_get_contents('php://input'));
			$userToken = $this->drawer->getStudentId($jsonData->tokenId)->result();
			if (empty($userToken)) {
				$object = array();
				$object["status"] = "T";
				$object["message"] = "Session timeout please login again.";
				echo json_encode($object);
			} else {
				$this->drawer->student_id = $userToken["0"]->student_id;
				$activityResult = $this->drawer->getNewsActivity()->result();
				$object = array();
				$object["items"] = array();
				foreach ($activityResult as $row) {
					$record = array();
					$record["testing"] = array();
					$record["testing"]["topic"] = $row->topic_detail;
					$record["testing"]["status"] = array();
					$record["testing"]["status"]["id"] = $row->status_id;
					$record["exam"] = array();
					$record["exam"]["id"] = $row->exam_id;
					$record["course"] = array();
					$record["course"]["id"] = $row->course_id;
					$record["course"]["name"] = $row->course_name;
					$record["teacher"] = array();
					$record["teacher"]["name"] = $row->teacher_name;
					$record["teacher"]["surname"] = $row->teacher_surname;
					array_push($object["items"], $record);
				}
				echo json_encode($object);
			}
		}
	}

	public function getListOldActivity() {

	}

	public function getListMyCourse() {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$jsonData = json_decode(file_get_contents('php://input'));
			$userToken = $this->drawer->getStudentId($jsonData->tokenId)->result();
			$this->drawer->student_id = $userToken["0"]->student_id;
			if (empty($userToken)) {
				$object = array();
				$object["status"] = "T";
				$object["message"] = "Session timeout please login again.";
				echo json_encode($object);
			} else {
				$this->drawer->student_id = $userToken["0"]->student_id;
				$courseResult = $this->drawer->getListMyCourse()->result();
				$object = array();
				$object["item"] = array();
				foreach ($courseResult as $row) {
					$record = array();
					$record["course"] = array();
					$record["course"]["name"] = $row->course_name;
					$record["course"]["id"] = $row->course_id;
					$record["course"]["term"] = $row->term_id;
					$record["course"]["year"] = $row->year;
					$record["teacher"] = array();
					$record["teacher"]["name"] = $row->teacher_name;
					$record["teacher"]["surname"] = $row->teacher_surname;
					$record["group"] = array();
					$record["group"]["id"] = $row->group_id;
					$record["group"]["number"] = $row->group_number;
					array_push($object["item"], $record);
				}
				echo json_encode($object);
			}
		}
	}

	public function addCourse() {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$jsonData = json_decode(file_get_contents('php://input'));
			$userToken = $this->drawer->getStudentId($jsonData->tokenId)->result();
			$this->drawer->student_id = $userToken["0"]->student_id;
			$object = array();
			if (empty($userToken)) {
				$object["status"] = "T";
				$object["message"] = "Session timeout please login again.";
				echo json_encode($object);
			} else {
				$this->drawer->group_id = $jsonData->groupId;
				$result = $this->drawer->getCourseLearning()->result();
				if (empty($result)) {
					$this->drawer->addCourse();
					$object["status"] = "S";
					echo json_encode($object);
				} else {
					$object["status"] = "F";
					$object["message"] = "Sorry, cannot enrol in same course.";
					echo json_encode($object);
				}
			}
		}
	}

	public function searchCourse() {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$jsonData = json_decode(file_get_contents('php://input'));
			$this->drawer->course_id = $jsonData->courseId;
			$result = $this->drawer->searchCourse()->result();
			$object = array();
			foreach ($result as $row) {
				$record = array();
				$record["course"] = array();
				$record["course"]["id"] = $row->course_id;
				$record["course"]["name"] = $row->course_name;
				$record["course"]["term"] = $row->term_id;
				$record["course"]["year"] = $row->year;
				$record["teacher"] = array();
				$record["teacher"]["name"] = $row->teacher_name;
				$record["teacher"]["surname"] = $row->teacher_surname;
				$record["group"] = array();
				$record["group"]["id"] = $row->group_id;
				$record["group"]["number"] = $row->group_number;
				array_push($object, $record);
			}
			echo json_encode($object);
		}
	}

}