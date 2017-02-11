<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define ("AD_ENABLED", 1, true);


define ("AD_SERVER", "10.4.1.82", true);


define ("AD_BASEDN", "ou=People,dc=buu,dc=ac,dc=th", true);


define ("AD_FILTER", "(cn=XUID)", true);

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->model("/ilclassroom/Login_Model", 'login');
	}

	public function index()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    // …
			$jsonData = json_decode(file_get_contents('php://input'));
						
			$object = array();
			$userData = $this->check_with_ad($jsonData->username, $jsonData->password);

			if (is_array($userData)) {
				$object["data"] = array();
				$object["data"]["status"] = "S";
				$object["data"]["user"] = $userData;

                $this->login->username = $jsonData->username;
                $userDetail = $this->login->getStudentDetail()->result();

                $stringHashing = "";

                if (empty($userDetail)) {
                    // Add new Student
                    $this->login->student_id = $jsonData->username;
                    $this->login->student_name = $userData["data"]["name"];
                    $this->login->student_surname = $userData["data"]["surname"];
                    $this->login->student_faculty = $userData["data"]["department"];
                    $this->login->addStudent();
                    $stringHashing = hash('sha256', $jsonData->username.date("Y-m-d h:i:s"));
                    $this->login->token_id = $stringHashing;
                    $this->login->device_push_token_id = "";
                    $this->login->addToken();

                } else {
                    $stringHashing = hash('sha256', $jsonData->username.date("Y-m-d h:i:s"));
                    $this->login->token_id = $stringHashing;
                    $this->login->student_id = $jsonData->username;
                    $this->login->updateToken();
                }

                $object["data"]["tokenId"] = $stringHashing;

			} else {
				$object["data"] = array();
				$object["data"]["status"] = "F";
				$object["data"]["message"] = "username or password invalid !!";
			}

			echo json_encode($object);
		}
	}

	private function check_with_ad ($user, $key)
	{

    $retval = -1;

    $vlan_no = 1;

    $network_id = 0;

    $ad = @ldap_connect("ldap://" . AD_SERVER);

    if ($ad && $user != "" && $key != "") {

        ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);

        ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);

        $retval = 0;

        if (@ldap_bind($ad,"$user@buu.ac.th","$key")) {

            $filter = preg_replace("/XUID/", "$user", AD_FILTER);

            $result = ldap_search($ad, AD_BASEDN, $filter);

            $entries = ldap_get_entries($ad, $result);

            $user = array();
            $user["data"]["name"] = $entries[0]["givenname"][0];
            $user["data"]["surname"] = $entries[0]["sn"][0];
            $user["data"]["emailAddress"] = $entries[0]["mail"][0];
            $user["data"]["department"] = $entries[0]["company"][0];
			$user["data"]["employeeId"] = $entries[0]["employeeid"][0];
			$user["data"]["studentId"] = $entries[0]["cn"][0];

            $retval = 1;

        }

        ldap_unbind($ad);

    }

    return $user;

	}


    public function getUser() {
        echo "getUser";
        // $this->load->model("/ilclassroom/Login_Model", 'login');
        // print_r($this->login->getUserWhere()->result());
    }

}
