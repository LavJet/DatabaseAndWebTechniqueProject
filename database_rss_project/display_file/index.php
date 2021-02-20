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
			 <div class="row">
	  			
				<div class="col-md-4">
					<img src="images\toi.png" alt="Times of India" width="310" height="150">
				</div>

				<div class="col-md-4">
	  				<img src="images\jyt.png" alt="The New York Times" width="310" height="150">
	  			</div>

				<div class="col-md-4">
					<img src="images\CBS-News-logo.jpg" alt="CBS News" width="310" height="150">
				</div>
			</div>
		</div>

		
		<br>

		<div class="container">
			 <div class="row">
	  			<div class="col-md-4">
	  				<img src="images\aerzteblatt_2.png" alt="Aerzteblatt News" width="310" height="150">
	  			</div>
				<div class="col-md-4">
					<img src="images\CTV-Logo.jpeg" alt="CVT News" width="310" height="150">
				</div>
				<div class="col-md-4">
					<img src="images\unnamed.png" alt="Trulli" width="310" height="150">
				</div>
			</div>
		</div>


		<div class="container">
		
			<hr>
			<h4>Click This Button To fetch the News content from the Channels</h4>
				<input type="submit" value="Refresh All Channels Data" class="btn btn-info"  id= "submit"> </input>
		
			
		   		<hr>
		    
		    <!--</form>-->
			<div align="right" style="margin-bottom:5px;">
			    
			    <h4 align="center"><b>Channels Details</b></h4>
				<button type="button" name="add_button" id="add_button" class="btn btn-success btn-xs">Add</button>
			</div>

			<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>URL-Link</th>
							<th>Last update</th>
							<th>News Title</th>
							<th>Error Count</th>
							<th>Edit</th>
							<th>Delete</th>
							<th>Refresh</th>
							<th>Display</th>
                            <th>View News</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>


	</body>
</html>

<div id="apicrudModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="api_crud_form">
				<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Create New Channel</h4>
		      	</div>
		      	<div class="modal-body">
		      		<div class="form-group">
			        	<label>Channels Name</label>
			        	<input type="text" name="channels_name" id="channels_name" class="form-control" />
			        </div>
			        <div class="form-group">
			        	<label>Channels URL</label>
			        	<input type="text" name="channels_url" id="channels_url" class="form-control" />
			        </div>
			     
			        <div class="form-group">
			        	<label>News Title</label>
			        	<input type="text" name="title" id="title" class="form-control" />
			        </div>
			    </div>
			    <div class="modal-footer">
			    	<input type="hidden" name="hidden_id" id="hidden_id" />
			    	<input type="hidden" name="action" id="action" value="insert" />
			    	<input type="submit" name="button_action" id="button_action" class="btn btn-info" value="Insert" />
			    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      		</div>
			</form>
		</div>
  	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $.LoadingOverlay("show");
      		var action = 'submit';
      	$.ajax({
				url:"../rest_api/test_api.php/",
				method:"GET",
				data:{action:action},
				success:function(data)
				{
				        $.LoadingOverlay("hide");	
					alert("Feeds Added Successfully.");
				}
			});
    });


	fetch_data();

	function fetch_data()
	{
		$.ajax({
			url:"fetch.php",
			success:function(data)
			{
				$('tbody').html(data);
			}
		})
	}

	function fetch_all_feed(){

		$.ajax({
			url:"fetch_feed.php",
			success:function(data)
			{
				$('tbody').html(data);
			}
		})
	}

	$('#add_button').click(function(){
		$('#action').val('insert');
		$('#button_action').val('Insert');
		$('.modal-title').text('Add Data');
		$('#apicrudModal').modal('show');
	});

	$('#api_crud_form').on('submit', function(event){
		event.preventDefault();
		if($('#channels_name').val() == '')
		{
			alert("Enter Channel Name");
		}
		else if($('#channels_url').val() == '')
		{
			alert("Enter URL");
		}
		else if($('#title').val() == '')
		{
			alert("Enter News Title");
		}
		else
		{
			var form_data = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					fetch_data();
					$('#api_crud_form')[0].reset();
					$('#apicrudModal').modal('hide');
					if(data == 'insert')
					{
						alert("Data Inserted using PHP API");
					}
					if(data == 'update')
					{
						alert("Data Updated using PHP API");
					}
				}
			});
		}
	});

	$(document).on('click', '.edit', function(){
		var id = $(this).attr('id');
		var action = 'fetch_single';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data)
			{
				$('#hidden_id').val(id);
				$('#channels_name').val(data.channels_name);
				$('#channels_url').val(data.channels_url);
				$('#title').val(data.title);
				$('#action').val('update');
				$('#button_action').val('Update');
				$('.modal-title').text('Edit News Channels');
				$('#apicrudModal').modal('show');
			}
		})
	});


	$(document).on('change',':checkbox',function(){
	    var id = $(this).attr("id");
	    var action = 'channel_status';

	    var status="";
	    if (this.checked) {
        console.log("checked now");
        // the checkbox is now checked
        status="ENABLED";
    } else {
        console.log("unchecked now");
        // the checkbox is now no longer checked
    status = "DISABLED";
        
    }
    	$.ajax({
				url:"../rest_api/test_api.php/",
				method:"GET",
				data:{id:id,status:status, action:action},
				success:function(data)
				{
					
					//alert("Channel Status Changed.");
				}
			});
	    
	});

	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		var action = 'delete';
		if(confirm("Are you sure you want to remove this data using PHP API?"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data)
				{
					fetch_data();
					alert("Data Deleted using PHP API");
				}
			});
		}
	});

	$(document).on('click', '.view', function(){
		var id = $(this).attr("id");
		var action = 'view';
		{
			$.ajax({
				url:"feed.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data)
				{
					//fetch_single_feed(id);
				}
			});
		}
	});	

});
</script>