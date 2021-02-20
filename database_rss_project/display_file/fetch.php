<?php

//fetch.php

$api_url = "http://dogshopsy.com/rss/database_rss_project/rest_api/test_api.php?action=fetch_all";


$client = curl_init($api_url);

curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($client);

$result = json_decode($response);

$output = '';

if(count($result) > 0)
{
	foreach($result as $row)
	{
		 $checked="";
	    if ($row->current_status=='ENABLED')
	    $checked="checked";
		$output .= '
		<tr>
			<td>'.$row->channels_name.'</td>
			<td><a href="'.$row->channels_url.'">'.$row->channels_url.'</a></td>
			<td>'.$row->last_modified_date.'</td>
			<td>'.$row->title.'</td>
			<td>'.$row->last_count.'</td>

			
			<td><button type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
			<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>



			<td><button type="button" name="refresh" class="btn btn-success btn-xs"  id="'.$row->id.'">Refresh</button><td>
		
	<label class="switch">
  <input type="checkbox"' .$checked.'  id="'.$row->id.'">
  <span class="slider round"></span>
</label>

	<td><button type="button" name="View" class="btn btn-success btn-xs view"  id="'.$row->id.'">View</button><td>

		</tr>
		';
	}
}
else
{
	$output .= '
	<tr>
		<td colspan="4" align="center">No Data Found</td>
	</tr>
	';
}

echo $output;

?>