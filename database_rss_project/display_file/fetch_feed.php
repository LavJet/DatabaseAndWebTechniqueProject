<?php

//fetch.php

$api_url = "http:/database_rss_project/rest_api/test_api.php?action=fetch_all_feed";


$client = curl_init($api_url);

curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($client);

$result = json_decode($response);

$output = '';

if(count($result) > 0)
{
	foreach($result as $row)
	{
	    


$output.='
<div class="col-md-4" >
    <div class="panel panel-default">
          <!--Card content-->
          <div class="panel-body">
'.$row->feed_title.'
            <!--Title-->
<h6 class="card-subtitle mb-2 text-muted">'.$row->published_date.'</h6>
            <!--Text-->
            
            <a href="'.$row->provider_link.'" class="btn btn-primary waves-effect waves-light" target="_blank" >Read More</a>

          </div>

        </div>
    <!--Panel-->
    
    <!--/.Panel-->

  </div>
';
	}
}
else
{

$output.='
<div class="card bg-secondary text-white">
<div class="card-body">No Data Found</div>
</div>
';
}

echo $output;

?>