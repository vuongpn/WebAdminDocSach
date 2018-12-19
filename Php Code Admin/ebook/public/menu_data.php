<?php
	include_once('includes/connect_database.php');
?>

<div id="content" class="container col-md-12">
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store data from database
		$data = array();
		
		// get all data from menu table and category table
		$sql_query = "SELECT nid, news_heading, news_date, news_status, category_name, news_image, news_description 
				FROM tbl_news m, tbl_news_category c
				WHERE m.nid = ? AND m.cat_id = c.cid";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['nid'], 
					$data['news_heading'], 
					$data['news_date'], 
					$data['news_status'], 
					$data['category_name'],
					$data['news_image'],
					$data['news_description']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>

<div class="col-md-9 col-md-offset-2">
	<h1>Menu Detail</h1>
	<form method="post">
		<table table class='table table-bordered'>
			<tr class="row">
				<th class="detail" width="15%">ID</th>
				<td class="detail"><?php echo $data['nid']; ?></td>
			</tr>
			<tr class="row">
				<th class="detail">Title</th>
				<td class="detail"><?php echo $data['news_heading']; ?></td>
			</tr>
				<tr class="row">
				<th class="detail">Sub Title</th>
				<td class="detail"><?php echo $data['news_date']; ?></td>
			</tr>
			<tr class="row">
				<th class="detail">Book Category</th>
				<td class="detail"><?php echo $data['category_name']; ?></td>
			</tr>
			<tr class="row">
				<th class="detail">Content</th>
				<td class="detail"><?php echo $data['news_description']; ?></td>
			</tr>
		</table>
		
	</form>
	<div id="option_menu">
			<a href="edit-menu.php?id=<?php echo $ID; ?>"><button class="btn btn-primary">Edit</button></a>
			<a href="delete-menu.php?id=<?php echo $ID; ?>"><button class="btn btn-danger">Delete</button></a>
	</div>
	
	</div>
				
	<div class="separator"> </div>
</div>
			
<?php include_once('includes/close_database.php'); ?>