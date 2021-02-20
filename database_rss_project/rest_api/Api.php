<?php

//Api.php

class API
{
	private $connect = '';

	function __construct()
	{
		$this->database_connection();
	}

	function database_connection()
	{
		$this->connect = new PDO("mysql:host=localhost;dbname=database_project", "admin", "");

	}


	public function check_age(){
		$query = "DELETE FROM feed WHERE published_date < NOW() - INTERVAL 30 DAY;";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			return true;
		}else return false;	
	}




	function add_all_feed(){

    $query = "DELETE FROM feed WHERE published_date < NOW() - INTERVAL 30 DAY;";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			return true;
		}else return false;	
   
$servername = "localhost";
$username = "admin";
$password = "";
$dbname = "database_project";

// Create connection
$conn = new mysqli($servername, $username, $password , $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$getchanneldata = "SELECT * FROM channels WHERE last_modified_date <= NOW() - INTERVAL 10 MINUTE";

if($result = mysqli_query($conn, $getchanneldata)){
	if(mysqli_num_rows($result) > 0){

		while($row = mysqli_fetch_array($result)){

			$link = $row['channels_url'];

			$provider_id = $row['id'];

			$xml = simplexml_load_file($link);
			
			$checkentry = false;

			foreach ($xml->channel ->item as $itm) {
				//$title = $itm->title;
				//$link = $itm->link;
				//$pubdate = $itm->pubDate;
				
				$title = $itm -> title[0];
				$link = htmlspecialchars_decode($itm -> link[0]);
				$desc = $itm -> description[0];
				$pub_date= explode("-",$itm -> pubDate);
				$publishDate = date('Y-m-d',strtotime(trim($pub_date[0])));
				$log_date = date('Y-m-d');
				$log_date = date('Y-m-d',strtotime(trim($log_date)));
				$guide = $itm -> guid[0];
				$author = $itm -> author[0];

				//escaping 
				$title = $conn -> real_escape_string($title);
				$link =$conn -> real_escape_string($link);
				$desc = $conn -> real_escape_string($desc);
				$guide = $conn -> real_escape_string($link);

 				    
            try{
                  $sql = "INSERT INTO feed (channels_id,feed_title,provider_link,published_date,description,detected_date)
					VALUES ('$provider_id','$title','$link','$publishDate','$desc' ,now())";
            $conn->query($sql);
            $checkentry = true;

                
            }catch(Exception $e){
                
                 //$conn->query("Select * from feed where feed_title= '$title'");
                $sql = "UPDATE feed set description = '$desc' , detected_date = now() ,  published_date=
                		'$publishDate' , provider_link = '$link' WHERE feed_title = '$title' "; 
            
                $conn->query($sql);
                
                
				 if ($conn->query($sql) === TRUE) {
				} else {
		    	echo "Error: " . $sql . "<br>" . $conn->error;
				 }
            } 			   
                  
                
					

				
					
			}
			if($checkentry){
			    $currentdate = date('Y-m-d H:i:s');
			    $sql = $conn->query("UPDATE channels set last_modified_date = '$currentdate' WHERE id = '$provider_id' ");    
			}
		}
	}
}
	
}


	function fetch_all_feed(){

		$query = "SELECT * FROM  feed  JOIN  channels on feed.channels_id= channels.id where channels.current_status = 'ENABLED' ";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			while($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function fetch_single_feed($id){

			$query = "SELECT * FROM feed WHERE id='".$id."'";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			while($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function fetch_all()
	{
		$query = "SELECT * FROM  channels ORDER BY id";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			while($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function insert()
	{
		if(isset($_POST["channels_name"]))
		{
			$form_data = array(
				':channels_name'		=>	$_POST["channels_name"],
				':channels_url'		=>	$_POST["channels_url"],
				':title'		=>	$_POST["title"]
			);
			$query = "
			INSERT INTO channels 
			(channels_name, channels_url,title) VALUES 
			(:channels_name, :channels_url, :title)
			";
			$statement = $this->connect->prepare($query);
			if($statement->execute($form_data))
			{
				$data[] = array(
					'success'	=>	'1'
				);
			}
			else
			{
				$data[] = array(
					'success'	=>	'0'
				);
			}
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
		}
		return $data;
	}

	function fetch_single($id)
	{
		$query = "SELECT * FROM channels WHERE id='".$id."'";
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			foreach($statement->fetchAll() as $row)
			{
				$data['channels_name'] = $row['channels_name'];
				$data['channels_url'] = $row['channels_url'];
				$data['title'] = $row['title'];
			}
			return $data;
		}
	}

	function update()
	{
		if(isset($_POST["channels_name"]))
		{
			$form_data = array(
				':channels_name'	=>	$_POST['channels_name'],
				':channels_url'	=>	$_POST['channels_url'],
				':title'	=>	$_POST['title'],
				':id'			=>	$_POST['id']
			);
			$query = "
			UPDATE channels 
			SET channels_name = :channels_name, channels_url = :channels_url, title = :title
			WHERE id = :id
			";
			$statement = $this->connect->prepare($query);
			if($statement->execute($form_data))
			{
				$data[] = array(
					'success'	=>	'1'
				);
			}
			else
			{
				$data[] = array(
					'success'	=>	'0'
				);
			}
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
		}
		return $data;
	}
	function delete($id)
	{
		$query = "DELETE FROM channels WHERE id = '".$id."'";
		
		$statement = $this->connect->prepare($query);

		if($statement->execute())
		{
			$data[] = array(
				'success'	=>	'1'
			);
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
		}

		deleteChannels($id);
		return $data;
	}

	function view($id)
	{
		$query = "SELECT FROM feed WHERE id = '".$id."'";
		
		$statement = $this->connect->prepare($query);

		if($statement->execute())
		{
			$data[] = array(
				'success'	=>	'1'
			);
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
		}

		return $data;
	}


	function deleteChannels($id){

		$query = "DELETE  channels,feed FROM channels left join feed on channels.id= feed.channels_id WHERE channels.id = '".$id."'";
	
		$statement = $this->connect->prepare($query);
		
		if($statement->execute())
		{
			$data[] = array(
				'success'	=>	'1'
			);
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
		}

		return $data;
	}

	function changeChannelStatus($id,$status){
	    $query = "update channels set current_status='$status' where id='$id'";
	    	$statement = $this->connect->prepare($query);
		if($statement->execute())
			$data[] = array(
				'success'	=>	'1'
			);
		else 
		$data[] = array(
				'success'	=>	'0'
			);
			return $data;
	    
	}
}

?>