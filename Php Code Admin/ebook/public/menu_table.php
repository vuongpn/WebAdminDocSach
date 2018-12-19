<?php
	include_once('includes/connect_database.php');
	include_once('functions.php'); 
?>

<div id="content" class="container col-md-12">
	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])){	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		}else{
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if(empty($keyword)){
			$sql_query = "SELECT nid, news_heading, news_image, category_name, news_status, news_date, news_description 
					FROM tbl_news m, tbl_news_category c
					WHERE m.cat_id = c.cid  
					ORDER BY m.nid DESC";
		}else{
			$sql_query = "SELECT nid, news_heading, news_image, category_name, news_status, news_date, news_description  
					FROM tbl_news m, tbl_news_category c
					WHERE m.cat_id = c.cid AND news_heading LIKE ? 
					ORDER BY m.nid DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if(!empty($keyword)){
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['nid'], 
					$data['news_heading'], 
					$data['news_image'], 
					$data['category_name'],
					$data['news_status'], 
					$data['news_date'],
					$data['news_description']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page){
			$from 	= ($page * $offset) - $offset;
		}else{
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if(empty($keyword)){
			$sql_query = "SELECT nid, news_heading, news_image, category_name, news_status, news_date, news_description  
					FROM tbl_news m, tbl_news_category c
					WHERE m.cat_id = c.cid  
					ORDER BY m.nid DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT nid, news_heading, news_image, category_name, news_status, news_date, news_description  
					FROM tbl_news m, tbl_news_category c
					WHERE m.cat_id = c.cid AND news_heading LIKE ? 
					ORDER BY m.nid DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if(empty($keyword)){
				$stmt_paging ->bind_param('ss', $from, $offset);
			}else{
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result($data['nid'], 
					$data['news_heading'], 
					$data['news_image'], 
					$data['category_name'],
					$data['news_status'], 
					$data['news_date'],
					$data['news_description']
					);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	<h1>Content Not Available
		<a href="add-menu.php">
			<button class="btn btn-danger">Add New</button>
		</a>
	</h1>
	<hr />
	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>

	<div class="col-md-12">
		<h1>
			Content List
			<a href="add-menu.php">
				<button class="btn btn-danger">Add New</button>
			</a>
		</h1>
		<hr/>
	</div>
	<!-- search form -->
	<form class="list_header" method="get">
		<div class="col-md-12">
			<p class="pholder">Search by Name : </p>
		</div>

		<div class="col-md-3">
			<input type="text" class="form-control" name="keyword" />
		</div>

		<br>
		&nbsp;&nbsp;&nbsp;
		<input type="submit" class="btn btn-primary" name="btnSearch" value="Search" />
	</form>
	<!-- end of search form -->
	
	<br/>
	<div class="col-md-12">
		<div class="table-responsive">
			<table class='table table-hover table-condensed table-bordered'>
				<tr>
					<th>Title</th>
					<th>Sub Title</th>
					<th>Books Category</th>
					<th>Preview</th>
					<th>Action</th>
				</tr>
					<?php 
						while ($stmt_paging->fetch()){ ?>
							<tr>
								<td><?php echo $data['news_heading'];?></td>
								<td><?php echo $data['news_date'];?></td>
								<td><?php echo $data['category_name'];?></td>
								<td width="30%"><?php echo substr($data['news_description'], 0, 150), '.....' ;?></td>
								<td width="15%">
									<a href="menu-detail.php?id=<?php echo $data['nid'];?>">
										View
									</a>&nbsp;

									<a href="edit-menu.php?id=<?php echo $data['nid'];?>">
										Edit
									</a>&nbsp;

									<a href="delete-menu.php?id=<?php echo $data['nid'];?>">
										Delete
									</a>
								</td>
							</tr>
							<?php 
							} 
						}
					?>
			</table>
		</div>
	</div>

	
	<div class="col-md-12">
	<h4>
	<?php 
		// for pagination purpose
		$function->doPages($offset, 'story.php', '', $total_records, $keyword);?>
	</h4>
	</div>
	<div class="separator"> </div>
</div> 

<?php 
	$stmt->close();
	include_once('includes/close_database.php'); ?>
					
				