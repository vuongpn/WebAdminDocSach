<?php
	include_once('includes/connect_database.php'); 
	include_once('functions.php'); 
	require_once("thumbnail_images.class.php");
?>
<div id="content" class="container col-md-12">
	<?php 
		$sql_query = "SELECT cid, category_name 
			FROM tbl_news_category 
			ORDER BY cid ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['cid'], 
				$category_data['category_name']
				);		
		}
		
			
		//$max_serve = 10;
			
		if(isset($_POST['btnAdd'])){
			$news_heading = $_POST['news_heading'];
			$cid = $_POST['cid'];
			$news_date = $_POST['news_date'];
			$news_description = $_POST['news_description'];
				
			// get image info
			// $news_image = $_FILES['news_image']['name'];
			// $image_error = $_FILES['news_image']['error'];
			// $image_type = $_FILES['news_image']['type'];
			
				
			// create array variable to handle error
			$error = array();
			
			if(empty($news_heading)){
				$error['news_heading'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
				
			if(empty($cid)){
				$error['cid'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}				
				
			if(empty($news_date)){
				$error['news_date'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}			

			if(empty($news_description)){
				$error['news_description'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
			
			// common image file extensions
			//$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			//error_reporting(E_ERROR | E_PARSE);
			//$extension = end(explode(".", $_FILES["news_image"]["name"]));
					
			// if($image_error > 0){
			// 	$error['news_image'] = " <span class='label label-danger'>Image Not Uploaded!!</span>";
			// }else if(!(($image_type == "image/gif") || 
			// 	($image_type == "image/jpeg") || 
			// 	($image_type == "image/jpg") || 
			// 	($image_type == "image/x-png") ||
			// 	($image_type == "image/png") || 
			// 	($image_type == "image/pjpeg")) &&
			// 	!(in_array($extension, $allowedExts))){
			
			// 	$error['news_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
			// }
				
			if( !empty($news_heading) && 
				!empty($cid) && 
				!empty($news_date) && 
				//empty($error['news_image']) && 
				!empty($news_description)) {
				
				// create random image file name
				// $string = '0123456789';
				// $file = preg_replace("/\s+/", "_", $_FILES['news_image']['name']);
				// $function = new functions;
				// $news_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// // upload new image
				// $unggah = 'upload/'.$news_image;
				// $upload = move_uploaded_file($_FILES['news_image']['tmp_name'], $unggah);

				// error_reporting(E_ERROR | E_PARSE);
				// copy($news_image, $unggah);
									 
				// 							$thumbpath= 'upload/thumbs/'.$news_image;
				// 							$obj_img = new thumbnail_images();
				// 							$obj_img->PathImgOld = $unggah;
				// 							$obj_img->PathImgNew =$thumbpath;
				// 							$obj_img->NewWidth = 72;
				// 							$obj_img->NewHeight = 72;
				// 							if (!$obj_img->create_thumbnail_images()) 
				// 								{
				// 								echo "Thumbnail not created... please upload image again";
				// 									exit;
				// 								}	 
		
				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_news (news_heading, cat_id, news_date, news_description)
						VALUES(?, ?, ?, ?)";
						
				//$upload_image = $news_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ssss', 
								$news_heading, 
								$cid, 
								$news_date, 
								//$upload_image,
								$news_description
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
				
				if($result){
					$error['add_menu'] = " <span class='label label-primary'>Success added</span>";
				}else {
					$error['add_menu'] = " <span class='label label-danger'>Failed</span>";
				}
			}
				
			}
	?>
	<div class="col-md-12">
	<h1>Add Content <?php echo isset($error['add_menu']) ? $error['add_menu'] : '';?></h1>
	<hr />
	</div>

	<div class="col-md-12">
	<form method="post" enctype="multipart/form-data">

	<div class="col-md-10">
	    <div class="col-md-3">
		<label>Title :</label><?php echo isset($error['news_heading']) ? $error['news_heading'] : '';?>
		<input type="text" class="form-control" name="news_heading"/>
	    <br/>
	    <label>Sub Title :</label><?php echo isset($error['news_date']) ? $error['news_date'] : '';?>
		<input type="text" name="news_date" class="form-control">
		<br/>

	    <label>Book Category :</label><?php echo isset($error['cid']) ? $error['cid'] : '';?>
		<select name="cid" class="form-control">
			<?php while($stmt_category->fetch()){ ?>
				<option value="<?php echo $category_data['cid']; ?>"><?php echo $category_data['category_name']; ?></option>
			<?php } ?>
		</select>
		
		</div>

		<div class="col-md-9">
		<label>Content :</label><?php echo isset($error['news_description']) ? $error['news_description'] : '';?>
		<textarea name="news_description" id="news_description" class="form-control" rows="16"></textarea>
		<script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">                        
            CKEDITOR.replace( 'news_description' );
        </script>
		</div>
	</div>
	
	<br/>
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Action</div>
				<div class="panel-body">
					<input type="submit" class="btn-primary btn" value="Publish" name="btnAdd" />&nbsp;
					<input type="reset" class="btn-danger btn" value="Clear"/>
				</div>
		</div>
	</div>
	</form>
	</div>	
	<div class="separator"> </div>
</div>
			

<?php 
	$stmt_category->close();
	include_once('includes/close_database.php'); ?>