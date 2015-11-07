
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class showdetails extends CI_Controller {

    public function index()
    {
        $this->load->model('showdetails_model');
        $this->load->helpers('xml_encode');
        $dataBef['record'] = $this->showdetails_model->show_details();
        /*for($i = 0; $i < count($dataBef['record']) ;$i++)
        {
            $data = xml_encode($dataBef['record']);
        }*/
        $data = xml_encode($dataBef);
        //echo $data;
        var_dump($data);
        //echo $data['details'][0]['firstname'];
        //echo implode("," ,$data['details'][0]);
        $this->load->view('header');
        $this->load->view('showdetails_view',$data);
        $this->load->view('footer');

    }
}
?>