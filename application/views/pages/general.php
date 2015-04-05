
<?php 
function makeDL($data){
    if(!is_array($data) ){
        echo '<h5>'.$data.'</h5>';
        return;
    }
    $dl = '<dl class="dl-horizontal">';
    foreach($data as $k=>$v):
        
        if(is_array($v)): 

            if(!is_numeric($k)) echo '<h5>'.$k.'</h5>';
            echo makeDL($v);
        else:
            if (substr($v, 0, 4) == 'http'):
                $v = '<a href="' . $v . '" target="_blank">' . $v . '</a>';
            endif;

            if ($k == 'id'):
                $v = '<a href="' .current_url().'/'. $v . '">' . $v . '</a>';
            endif;
            if($k == 'geojson' && $v[0] =='{'):
                $dl .= '<dt>'.$k.'</dt>';
                $dl .= '<dd>'.$v.'</dd>';
            else:
                $dl .= '<dt>'.$k.'</dt>';
                $dl .= '<dd>'.$v.'</dd>';
            endif;
        endif;
        
      
    endforeach;

    $dl .= '</dl><hr>';
    echo $dl;
    
}

echo '<h2>Results - <a href="'.current_url().'?format=map&'.$_SERVER['QUERY_STRING'].'" target="_blank">View Map</a><br></h2>';
foreach($this->data->results as $r):
     makeDL($r);
endforeach;

echo "<h2>Meta</h2>";

foreach($this->data->meta as $k => $r):
    if(is_array($r)): 

        if(!is_numeric($k)) echo '<h4>'.$k.'</h4>';
         makeDL($r);
    else:
        echo '<h4>'.$r.'</h4>';
    endif;
    
endforeach;
?>