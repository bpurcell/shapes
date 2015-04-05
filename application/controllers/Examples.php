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

class Examples extends MY_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
    }
    
    function index()
    {
        
        $this->view = 'pages/general';
        $this->data->meta['title'] = 'Shapes Homepage';

        $this->data->results = [
            'african_countries' => ['name' => "African Countries",'url' => 'shapes/search/countries/continent-africa?limit=100'],
            'american_states' => ['name' => "American States",'url' => 'shapes/search/states_provinces/sr_adm0_a3-usa?limit=50'],
            'china' => ['name' => "China Simplified with some extra fields",'url' => 'shapes/search/countries/name-china?fields=id-name-formal_en-economy-income_grp-pop_est-subregion-continent&simplify=.5'],
            'caribbean' => ['name' => "Caribbean Unsimplified",'url' => 'shapes/search/countries/subregion-Caribbean?fields=all&limit=500&simplify=.001'],
            'usa' => ['name' => "United States all fields by id",'url' => 'shapes/countries/238?fields=all'],
        ];
        foreach($this->data->results as &$result):
            foreach($this->_supported_formats as $k => $formats):
                if($k == 'html'){
                    $result[$k] = base_url().$result['url'];
                } else {
                    $result[$k] = base_url().$result['url'].'&format='.$k;
                }
            endforeach;
            
            unset($result['url']);
            
        endforeach;
        
        if($this->data->results)
        {
            $this->_out_put();
        }

        else
        {
            $this->data->results = ['error' => 'Error.  There were no results found.'];
            $this->_out_put();
        }
    }
}