<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class WelcomeHooks extends CI_Controller {
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    public function replacePlaceholderCode() {
        // load the instance
        $this->CI = & get_instance();

        // get the actual output
        $contents = $this->CI->output->get_output();

        // replace the tokens
        $this->CI->load->helper('date');
        $contents = str_replace("[DATETIME]", standard_date(), $contents);

        // set the output
        echo $contents;
        return;
    }

}

?>