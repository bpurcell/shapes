<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
        $this->view = 'pages/general';
        $this->data->meta['title'] = 'Sourcemap GIS';

        $this->data->results = [];

        if($this->data)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data = ['Error'];
            $this->_out_put();
        }
	}
}