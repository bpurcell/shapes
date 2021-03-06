<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Rest Controller
 *
 * A fully RESTful server implementation for CodeIgniter using one library, one config file and one controller.
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link			https://github.com/chriskacerguis/codeigniter-restserver
 * @version         3.0.0-pre
 */
abstract class MY_Controller extends CI_Controller
{

    protected $_get_format = 'geojson';   
    protected $http_code = 200;   
    protected $_supported_formats   = array(
        'xml'           => 'application/xml',
        'json'          => 'application/json',
        'geojson'       => 'application/json',
        'map'          => 'text/html',
        'html'          => 'text/html',
        //'jsonp'         => 'application/javascript',
        //'serialized'    => 'application/vnd.php.serialized',
        //'php'           => 'text/plain',
        //'csv'           => 'application/csv'
    );
    

    public function __construct($config = 'rest')
    {
        parent::__construct();
        

        $this->view = 'pages/general';
        
        $this->data = new stdClass;
        $this->data->meta = [
            'title' => 'Sourcemap GIS', 
            'author' => 'Sourcemap, Inc.',
            'url' => base_url(),
            'response' => $this->_supported_formats,
            'endpoints' => [
                    ['endpoint' =>'examples', 
                    'description' => 'Some examples for you to peruse.',
                    'link' => base_url().'examples'],
                    ['endpoint' =>'shapes/', 
                    'description' => 'Get more detailed information about the shape endpoint here',
                    'link' => base_url().'shapes'],
                    ['endpoint' =>'shapes/countries', 
                    'description' => 'Get Countries here.  Refer to params options to filter and limit.',
                    'link' => base_url().'shapes/countries'],
                    ['endpoint' =>'shapes/countries/#id', 
                    'description' => 'Get a country by id. Refer to params options to filter and limit.'],
                    ['endpoint' =>'shapes/search/countries/#query', 
                    'description' => 'Do a search against the countries.   We use Natural Earth Data and have all the fields.  You can define a search by `column-query`. We then search for column LIKE `%query%`'],
                    ['endpoint' =>'shapes/states_provinces', 
                    'description' => 'Get State or Province here.  Refer to params options to filter and limit.',
                    'link' => base_url().'shapes/states_provinces'],
                    ['endpoint' =>'shapes/states_provinces/#id', 
                    'description' => 'Get a State or Province by id. Refer to params options to filter and limit.'],
                    ['endpoint' =>'shapes/search/states_provinces/#query', 
                    'description' => 'Do a search against the State or Provinces.   We use Natural Earth Data and have all the fields.  You can define a search by `column-query`. We then search for column LIKE `%query%`']
                        ] 
        ];
        $this->data->results = new stdClass;

        $this->data->mappable = false;

        // Check to see if this IP is Blacklisted
        if ($this->config->item('rest_ip_blacklist_enabled')) {
            $this->_check_blacklist_auth();
        }
        // Set up our GET variables
        $this->_get_format        = ($this->input->get('format'))?$this->input->get('format'):'html';
        

        $this->_allow = $this->_detect_api_key();
        
    }

    public function _remap($method, $params = [])
    {
        if (method_exists($this, $method)):
            return call_user_func_array([
                $this,
                $method
            ], $params);
        else:
            $this->main($method,$params);
        endif;
    }    
    function _out_put()
    {
        set_status_header($this->http_code);

        $this->load->library('Format');
        
        
        if($this->_get_format == 'html'):
        
            $this->load->view('template');
            
            
        elseif($this->_get_format == 'map'):
        
            $this->data = $this->format->factory((array)$this->data)->{'to_geojson'}();
        
            $this->load->view('map');
            
            
        else:
            
            header('Content-Type: '.$this->_supported_formats[$this->_get_format] . '; charset=' . strtolower($this->config->item('charset')));

        
            $output = $this->format->factory((array)$this->data)->{'to_'.$this->_get_format}();

            echo($output);
        endif;
        
        
    }
    


    /**
     * Detect API Key
     *
     * See if the user has provided an API key
     *
     * @return boolean
     */
    protected function _detect_api_key()
    {
        // Get the api key name variable set in the rest config file
        $api_key_variable = config_item('rest_key_name');

        // Work out the name of the SERVER entry based on config
        $key_name = 'HTTP_'.strtoupper(str_replace('-', '_', $api_key_variable));

        $this->key = NULL;
        $this->level = NULL;
        $this->user_id = NULL;
        $this->ignore_limits = FALSE;

        // Find the key from server or arguments
        if (($key = isset($this->_args[$api_key_variable]) ? $this->_args[$api_key_variable] : $this->input->server($key_name))) {
            if ( ! ($row = $this->db->where(config_item('rest_key_column'), $key)->get(config_item('rest_keys_table'))->row())) {
                return FALSE;
            }

            $this->key = $row->{config_item('rest_key_column')};

            isset($row->user_id) and $this->user_id = $row->user_id;
            isset($row->level) and $this->level = $row->level;
            isset($row->ignore_limits) and $this->ignore_limits = $row->ignore_limits;

            $this->_apiuser =  $row;

            /*
             * If "is private key" is enabled, compare the ip address with the list
             * of valid ip addresses stored in the database.
             */
            if (!empty($row->is_private_key)) {
                var_dump('private key?');
                // Check for a list of valid ip addresses
                if (isset($row->ip_addresses)) {
                    // multiple ip addresses must be separated using a comma, explode and loop
                    $list_ip_addresses = explode(",", $row->ip_addresses);
                    $found_address = FALSE;

                    foreach ($list_ip_addresses as $ip_address) {
                        if ($this->input->ip_address() == trim($ip_address)) {
                            // there is a match, set the the value to TRUE and break out of the loop
                            $found_address = TRUE;
                            break;
                        }
                    }

                    return $found_address;
                } else {
                    // There should be at least one IP address for this private key.
                    return FALSE;
                }
            }

            return $row;
        }

        // No key has been sent
        return FALSE;
    }
    
    
}