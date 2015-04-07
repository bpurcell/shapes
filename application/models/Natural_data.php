<?php
class Natural_data extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
    }
    function get($table = 'countries', $id = false , $params = false)
    {
        foreach($this->data->meta['params']['options'] as $k => $p)
             $$k = (!isset($params[$k])) ? false : $params[$k] ;
        
        
        if($simplify == false) $simplify = .1;
        if($limit == false) $limit = 10;
        if($offset == false) $offset = 0;
        
        if($fields == 'all'):
            $this->db->select('*, ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        elseif($fields != false):

            $fields = explode('-',$fields);
            foreach($fields as $field):
                if (!$this->db->field_exists($field, $table))
                {
                    $this->data->results = ['error' => 'Error.  That is not a real field name','available_fields' => $this->db->list_fields($table)];
                    return;
                }
            endforeach;
            $this->db->select(implode($fields,',').', ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        else:
            $name = ($this->db->field_exists('name', $table))? 'name,' : '';
            
            $this->db->select('id, '.$name.' ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        endif;
        
        
        if($id)
            $this->db->where('id', $id[0]);
        
        $this->db->limit($limit, $offset);
        
        $this->data->results = $this->db->get($table)->result_array();
        
        $this->data->meta['count'] = $this->db->count_all($table);
    }

    function search($table = 'countries', $query = false , $params = false)
    {
        foreach($this->data->meta['params']['options'] as $k => $p)
             $$k = (!isset($params[$k])) ? false : $params[$k] ;
        
        
        if($simplify === false) $simplify = .1;
        if($limit == false) $limit = 10;
        if($offset == false) $offset = 0;
        
        if($fields == 'all'):
            $this->db->select('*, ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        elseif($fields != false):

            $fields = explode('-',$fields);
            foreach($fields as $field):
                if (!$this->db->field_exists($field, $table))
                {
                    $this->data->results = ['error' => 'Error.  That is not a real field name','available_fields' => $this->db->list_fields($table)];
                    return;
                }
            endforeach;
            
            $this->db->select(implode($fields,',').', ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        else:
            $this->db->select('id, name, ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        endif;
        
        
        if($query):
            $query = explode('-',$query);
            if (!$this->db->field_exists($query[0], $table))
            {
                $this->data->results = ['error' => 'Error.  That is not a real field name','available_fields' => $this->db->list_fields($table)];
                return;
            }
            $this->db->like('LOWER('.$query[0].')', strtolower($query[1]));
        endif;
        
        $this->db->limit($limit, $offset);
        
        $this->data->results = $this->db->get($table)->result_array();
        $this->data->meta['count'] = $this->db->count_all($table);
    }

    function within($table = 'countries', $query = false , $params = false)
    {
        var_dump($query);
        foreach($this->data->meta['params']['options'] as $k => $p)
             $$k = (!isset($params[$k])) ? false : $params[$k] ;
        
        
        if($simplify === false) $simplify = .1;
        if($limit == false) $limit = 10;
        if($offset == false) $offset = 0;
        
        if($fields == 'all'):
            $this->db->select('*, ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        elseif($fields != false):

            $fields = explode('-',$fields);
            foreach($fields as $field):
                if (!$this->db->field_exists($field, $table))
                {
                    $this->data->results = ['error' => 'Error.  That is not a real field name','available_fields' => $this->db->list_fields($table)];
                    return;
                }
            endforeach;
            
            $this->db->select(implode($fields,',').', ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        else:
            $this->db->select('id, name, ST_AsGeoJSON(ST_Simplify(geom, '.$simplify.')) as geojson', false);
        endif;
        
        
        if($query):
            $query = explode('-',$query);
            if (!$this->db->field_exists($query[0], $table))
            {
                $this->data->results = ['error' => 'Error.  That is not a real field name','available_fields' => $this->db->list_fields($table)];
                return;
            }
            $this->db->like('(ST_Contains('.$query[0].')', strtolower($query[1]));
        endif;
        
        $this->db->limit($limit, $offset);
        
        $this->data->results = $this->db->get($table)->result_array();
        $this->data->meta['count'] = $this->db->count_all($table);
    }
    
}
?>
