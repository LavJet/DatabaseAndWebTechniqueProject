<!DOCTYPE html>
<html>
	<head>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
		
	</head>
	<body>

				<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">News Channels Data</a>
		    </div>
		    <ul class="nav navbar-nav">
		      <li class="active"><a href="index.php">Home</a></li>
		  
		      <li><a href="feed.php">News Feed</a></li>
		    </ul>
		  

		  </div>
		</nav>


		<div class="container">
		
			
			<h3 align="left">Feed Detail</h3>
			
			<div class="row" id="feed">
			    
			    
			</div>

		
		</div>

	</body>
</html>



<script type="text/javascript">
$(document).ready(function(){

	fetch_all_feed();

	function fetch_all_feed()
	{
		$.ajax({
			url:"fetch_feed.php",
			success:function(data)
			{
				$('#feed').html(data);
			}
		})
	}
});
</script>