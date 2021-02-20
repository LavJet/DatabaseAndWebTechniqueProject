<?php

//test_api.php

include('Api.php');

$api_object = new API();

if($_GET["action"] == 'fetch_all')
{
	$data = $api_object->fetch_all();
}

if($_GET["action"] == 'fetch_all_feed')
{
	$data = $api_object->fetch_all_feed();
}

if($_GET["action"] == 'insert')
{
	$data = $api_object->insert();
}

if($_GET["action"] == 'fetch_single')
{
	$data = $api_object->fetch_single($_GET["id"]);
}

if($_GET["action"] == 'update')
{
	$data = $api_object->update();
}

if($_GET["action"] == 'delete')
{
	$data = $api_object->delete($_GET["id"]);
}

if($_GET["action"] == 'view')
{
	$data = $api_object->view($_GET["id"]);
}

if($_GET["action"] == 'channel_status'){
    $data = $api_object->changeChannelStatus($_GET["id"],$_GET["status"]);
}
if($_GET["action"] == 'submit'){
    $data = $api_object->add_all_feed();
}

echo json_encode($data);

?>