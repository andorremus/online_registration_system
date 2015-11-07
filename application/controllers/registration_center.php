<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once("../a2/application/libraries/Attendee.php");
include_once("../a2/application/libraries/Speaker.php");
include_once("../a2/application/libraries/Seminar.php");
include_once("../a2/application/libraries/Seminar_Organiser.php");
include_once("../a2/application/libraries/Centre_Manager.php");
session_start();
class registration_center extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('registration_center_model');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->helpers('xml_encode');
    }

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('registration_center_view');
        $this->load->view('templates/footer');

    }

    function validate_attendee_registration_form()
    {

        $f_name = $this->input->get_post('attendee_f_name', 'first name', true);
        $l_name = $this->input->get_post('attendee_l_name', 'last name', true);
        $email = $this->input->get_post('attendee_email', 'email', true);
        $institution = $this->input->get_post('attendee_institution', 'institution', true);
        $seminarId = $this->input->get_post('seminars', 'seminar Id', true);

        $attendeeToAdd = new Attendee($f_name, $l_name, $email, $institution);


        $response = $this->registration_center_model->add_attendee($attendeeToAdd, $seminarId);
        if (IS_AJAX) {
            //echo $response;
            if ($response == 'ALREADY_IN_SEMINAR')
            {
                echo "AR";
            } else if ($response == 'NO_PLACES_AVAILABLE')
            {
                echo "NPA";
            } else if ($response == 'REGISTERED_SUCCESSFULLY')
            {
                echo "REG";
            } else
            {
                echo "FAIL";
            }

        } else {
            echo "NOT AJAX";
        }
    }

    function retrieve_seminars()
    {
        $seminarsArray = $this->registration_center_model->retrieve_seminars();
        echo json_encode($seminarsArray);

    }

    function create_new_seminar()
    {
        $title = $this->input->get_post('title', 'title', true);
        $startTime = $this->input->get_post('startTime', 'Starting Time and Date', true);
        $endTIme = $this->input->get_post('endTime', 'Ending Time and Date', true);
        $description = $this->input->get_post('description', 'description', true);
        $roomId = $this->input->get_post('room', 'description', true);
        $placesAvailable = $this->input->get_post('capacity', 'Places available', true);

        $firstName = $this->input->get_post('firstName', 'first name', true);
        $lastName = $this->input->get_post('lastName', 'last name', true);
        $email = $this->input->get_post('email', 'email', true);
        $address = $this->input->get_post('address', 'address', true);
        $institution = $this->input->get_post('institution', 'institution', true);

        $seminar = new Seminar($title, $startTime, $endTIme, $description, $placesAvailable, $roomId);
        $speaker = new Speaker($firstName, $lastName, $email, $address, $institution);

        $roomAvailability = $this->registration_center_model->check_room_availability($seminar);

        if (!$roomAvailability) {
            $this->registration_center_model->add_seminar($seminar, $speaker);
            echo "SUCCESS";
        } else {
            echo "BUSY";
        }


    }

    function retrieve_room_details()
    {
        $roomDetailsArray = $this->registration_center_model->get_room_details();
        echo json_encode($roomDetailsArray);
    }

    function retrieve_ticket_details($email, $seminarId)
    {
        $ticketDetailsArray = $this->registration_center_model->get_ticket_details($email, $seminarId);
        echo json_encode($ticketDetailsArray);
    }

    function withdraw_attendee($email,$seminarId)
    {
        $response = $this->registration_center_model->withdraw_attendee($email, $seminarId);
        echo $response;


    }

    function validate_seminar_organiser_login()
    {
        $username = $this->input->get_post('username', 'username', true);
        $password = $this->input->get_post('password', 'password', true);

        $seminarOrganiser = new Seminar_Organiser($username,$password);

        $response = $this->registration_center_model->validate_seminar_organiser_login($seminarOrganiser);

        if($response)
        {
            echo "TRUE";
        }
        else
        {
            echo "FALSE";
        }
    }

    function retrieve_seminar_overview($username)
    {
        $seminars = $this->registration_center_model->retrieve_organiser_seminars($username);
        echo json_encode($seminars);

    }

    function retrieve_seminars_stats($username)
    {
        $stats = $this->registration_center_model->retrieve_seminars_stats($username);
        echo json_encode($stats);

    }
    function seminar_organiser_logout()
    {
        echo "TRUE";
    }
    function centre_manager_logout()
    {
        echo "TRUE";
    }

    function validate_centre_manager_login()
    {
        $username = $this->input->get_post('username', 'username', true);
        $password = $this->input->get_post('password', 'password', true);

        $centreManager = new Centre_Manager($username,$password);

        $response = $this->registration_center_model->validate_centre_manager_login($centreManager);

        if($response)
        {
            echo "TRUE";
        }
        else
        {
            echo "FALSE";
        }
    }

    function retrieve_past_seminars()
    {
        $pastSeminars = $this->registration_center_model->retrieve_past_seminars();
        echo json_encode($pastSeminars);
    }

    function retrieve_upcoming_seminars()
    {
        $upcomingSeminars = $this->registration_center_model->retrieve_upcoming_seminars();
        echo json_encode($upcomingSeminars);
    }
    function retrieve_expected_attendance()
    {
        $expectedAttendance = $this->registration_center_model->retrieve_expected_attendance();
        echo json_encode($expectedAttendance);
    }

    function retrieve_room_popularity()
    {
        $roomPopularity = $this->registration_center_model->retrieve_room_popularity();
        echo json_encode($roomPopularity);
    }




}