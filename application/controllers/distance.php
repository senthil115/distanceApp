<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class distance extends CI_Controller
{
	   public function __construct()
     {
    	  parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
				$this->load->helper('security');
        $this->load->library('form_validation');
        //load the distance model
        //$this->load->model('distance_model');
     }

     public function index()
     {
        show_404();
     }

		 public function getDistance()
		 {
			 		echo 'getDistance';
		 }

     public function home()
     {
	        //echo 'home';
	        $data['title'] = 'home';
	        $this->load->helper(array('form', 'url'));

	    		$this->load->library('form_validation');

					$this->load->view('templates/header', $data);
					$this->load->view('templates/navbar');

					$this->form_validation->set_rules('source', 'Source', 'trim|required|max_length[100]|xss_clean');
					$this->form_validation->set_rules('destination', 'Destination', 'trim|required|max_length[100]|xss_clean');

	    		if ($this->form_validation->run() == FALSE)
	    		{
	    		    $this->load->view('distance/home', $data);
	    		}
	    		else
	    		{
	    		    $this->load->model('distance_model');
							$model_data = $this->distance_model->getDistance();

							if( 1 == $model_data['status'] )
							{
									$data['status']   		= 1;
									$data['distance'] 		= $model_data['distance'];
									$data['duration'] 		= $model_data['duration'];
									$data['source']   		= $model_data['source'];
									$data['destination']  = $model_data['destination'];
							}

							else if( 0 == $model_data['status'] )
							{
									$data['status']   		= 0;
									$data['source']   		= $model_data['source'];
									$data['destination']  = $model_data['destination'];
							}

							else {
									$data['status']   		= 2;
							}

							$this->load->view('distance/home', $data);

	    		}

					$this->load->view('templates/footer');

     }


}
