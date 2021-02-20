<?php
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
	//print_r($xml);
?> 


