<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
class Shape extends MY_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        $this->load->model('natural_data');
        

        $this->data->meta['title'] = 'Shapes Homepage';

        $this->data->meta['params'] = [
            'description' => 'These are the optional query params you can tack on to change the way the API returns and filter your resutls.',
            'options' => [
                'simplify' => 'You can simplify the returned GeoJSON by adding simplify=# .  The number should be between 0 (no simplifcation) and 10. You will need to play around with it to get a value you like. Default simplication is .1',
                'fields' => 'You can request, fields=all, fields=id, or fields=id-name-otherfield-otherfield.',
                'limit' => 'Limit the returned results',
                'offset' => 'Where to start the limit'
                
            ]
        ];
    }
    
    
    function index()
    {
        $this->view = 'pages/general';
        $this->data->meta['title'] = 'Shapes Homepage';

        $this->data->results = [
            'countries' => 'Country Data',
            'states_provinces' => 'States and Provinces Data'
        ];

        if($this->data->results)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data->results = ['error' => 'Error.  There were no results found.'];
            $this->_out_put();
        }
    }
    function search($type, $params=false){
        $this->{'search_'.$type}($params);
    }
    
    function countries($id = false)
    {
        if($id != false && !is_numeric($id)){
                $this->data->results = ['Error.  That was not an id.'];
                $this->_out_put();
                return;
        }
        
        $this->natural_data->get('countries',$id,$this->input->get());

        if($this->data->results)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data->results = ['Error.  There were no results found.'];
            $this->_out_put();
        }
    }
    
    function search_countries($query = false)
    {
        $this->natural_data->search('countries',$query,$this->input->get());

        if($this->data->results)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data->results = ['Error.  There were no results found.'];
            $this->_out_put();
        }
    }
    
    function states_provinces($id = false)
    {
        if($id != false && !is_numeric($id)){
                $this->data->results = ['Error.  That was not an id.'];
                $this->_out_put();
                return;
        }
            
        $this->natural_data->get('states_provinces',$id,$this->input->get());

        if($this->data->results)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data->results = ['Error.  There were no results found.'];
            $this->_out_put();
        }
    }
    
    function search_states_provinces($query = false)
    {
        $this->natural_data->search('states_provinces',$query,$this->input->get());

        if($this->data->results)
        {
            $this->_out_put(); // 200 being the HTTP response code
        }

        else
        {
            $this->data->results = ['Error.  There were no results found.'];
            $this->_out_put();
        }
    }

}