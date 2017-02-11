<?php 

require("Base_Model.php");

class Drawer_Model extends Base_Model {

	public $student_id;
	public $currentTerm;
	public $currentYear;
	public $course_owner_id;
	public $group_id;
	public $course_id;

	// public function getNewActivity() {
	// 	$sql = "SELECT exs_testing.testing_id, exs_testing.testing_score, exs_testing.student_id, exs_testing.status_id, exs_topic_testing.topic_detail, exs_testing_status.status_name, exs_examination.exam_name, exs_examination.exam_description, exs_course.course_id, exs_course.course_name, exs_teacher.teacher_name, exs_teacher.teacher_surname, exs_group.group_id, exs_group.group_number FROM exs_testing "
	// 		  ."INNER JOIN exs_topic_testing ON exs_testing.topic_id = exs_topic_testing.topic_id "
	// 		  ."INNER JOIN exs_testing_status ON exs_testing.status_id = exs_testing_status.status_id "
	// 		  ."INNER JOIN exs_examination ON exs_topic_testing.exam_id = exs_examination.exam_id "
	// 		  ."INNER JOIN exs_course_owner ON exs_examination.course_owner_id = exs_course_owner.course_owner_id "
	// 		  ."INNER JOIN exs_teacher ON exs_course_owner.teacher_id = exs_teacher.teacher_id "
	// 		  ."INNER JOIN exs_course ON exs_course_owner.course_id = exs_course.course_id "
	// 		  ."WHERE exs_testing.student_id = ? and exs_testing.status_id = ?";

	// 	return $this->db->query($sql, array($this->student_id, 1));
	// }

	public function getNewsActivity() {
		$sql = "SELECT * FROM exs_learning_course "
		      ."INNER JOIN exs_group ON exs_learning_course.group_id = exs_group.group_id "
		      ."INNER JOIN exs_topic_testing ON exs_topic_testing.group_id = exs_group.group_id "
		      ."INNER JOIN exs_course_owner ON exs_course_owner.course_owner_id = exs_group.course_owner_id "
		      ."INNER JOIN exs_course ON exs_course_owner.course_id = exs_course.course_id "
		      ."INNER JOIN exs_teacher ON exs_course_owner.teacher_id = exs_teacher.teacher_id "
		      ."WHERE exs_learning_course.student_id = ? and exs_topic_testing.status_id = ?";
		return $this->db->query($sql, array($this->student_id, 1));
	}

	public function getListMyCourse() {
		$sql = "SELECT * FROM exs_learning_course "
			  ."INNER JOIN exs_group ON exs_learning_course.group_id = exs_group.group_id "
		      ."INNER JOIN exs_course_owner ON exs_group.course_owner_id = exs_course_owner.course_owner_id "
		      ."INNER JOIN exs_course ON exs_course_owner.course_id = exs_course.course_id "
		      ."INNER JOIN exs_teacher ON exs_course_owner.teacher_id = exs_teacher.teacher_id "
		      ."WHERE student_id = ?";
		return $this->db->query($sql, array($this->student_id));
	}

	public function addCourse() {
		$sql = "INSERT INTO exs_learning_course (student_id, group_id) VALUES (?, ?)";
		$this->db->query($sql, array($this->student_id, $this->group_id));
		return;
	}

	public function getCourseLearning() {
		$sql = "SELECT * FROM exs_learning_course WHERE student_id = ? and group_id = ?";
		return $this->db->query($sql, array($this->student_id, $this->group_id));
	}

	public function searchCourse() {
		$sql = "SELECT * FROM exs_course "
			  ."INNER JOIN exs_course_owner ON exs_course.course_id = exs_course_owner.course_id "
			  ."INNER JOIN exs_teacher ON exs_course_owner.teacher_id = exs_teacher.teacher_id "
			  ."INNER JOIN exs_group ON exs_course_owner.course_owner_id = exs_group.course_owner_id "
			  ."WHERE exs_course.course_id LIKE '%". $this->course_id ."%' ORDER BY exs_course.course_id";
		return $this->db->query($sql);
	}

}