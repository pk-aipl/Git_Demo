<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index($login_val = '') {
        $this->load->helper('language');
        if ($this->input->post('submitLang')) {
            $button_val = $this->input->post('submitLang');
        } else {
            $button_val = 'english';
        }

        $this->lang->load($button_val, $button_val);
        $arr = array('login_val' => $login_val);
        $this->load->view('common/header');
        $this->load->view('home', $arr);
        $this->load->view('common/footer');
    }

    public function home_page() {
        $this->load->view('common/header');
        $this->load->view('login');
        $this->load->view('common/footer');
    }

    public function contact() {
        $this->load->view('common/header');
        $this->load->view('contact');
        $this->load->view('common/footer');
    }

    public function news() {
        $this->load->view('common/header');
        $this->load->view('news');
        $this->load->view('common/footer');
    }

    public function events() {
        $this->load->view('common/header');
        $this->load->view('events');
        $this->load->view('common/footer');
    }

    public function speakers() {
        $this->load->view('common/header');
        $this->load->view('speakers');
        $this->load->view('common/footer');
    }

    public function insert_data() {
        $this->load->model('db_model');
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message'),
        );
        $values = $this->db_model->saverecords($data);
        if ($values) {
            echo "<script type='text/javascript'>alert(\"Data Inserted\")
					</script>";
            header("location: contact");
            // redirect('welcome/contact');
        }
        $this->load->helper('url');
    }

    public function insert_signup_data() {
        $this->load->model('db_model');

        $match_result = $this->db_model->compare_captcha_query();

        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );

        $this->load->helper(array('form', 'url', 'captcha'));
        $this->load->library('form_validation');
        $result = $this->formVal($data);
        print_r($result);
        if ($result == 'fail' && $match_result != 'match') {
            redirect('welcome/index/fail');
        } else {
            $this->db_model->save_signup_record($data);
        }

        $this->load->helper('url');
        redirect('welcome');
    }

    public function insert_login_data() {
        $this->load->model('db_model');
        $login_data = array(
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
        );
        $this->db_model->compare_login_record($login_data);

        $this->load->helper('url');
    }

    function formVal($data) {

        $this->form_validation->set_rules('username', 'username', 'required|is_unique[signup.username]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('email', 'email', 'required|valid_email', array(
            'required' => 'You have not provided %s.'
        ));
        $this->form_validation->set_rules('password', 'password', 'required|min_length[8]', array(
            'required' => 'You have not provided %s.'
        ));
        $this->form_validation->set_rules('captcha', 'Captcha', 'callback_validate_captcha');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');


        if ($this->form_validation->run() == FALSE)
            return 'fail';
        return 'success';
    }

}

?>
