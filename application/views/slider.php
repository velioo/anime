<div id="slider" class="carousel slide" data-ride="carousel" data-interval="false">		
	  <div class="carousel-inner">
	  		<?php $counter = 0;?>
	  		<?php foreach ($latest_anime as $anime) { ?>
  				<?php if($counter % 7 == 0) { ?>
  					<?php if($counter == 0) {?>
  						<div class="item active">
  					<?php } else {?>
  						</div></div>
  						<div class="item">
  					<?php }?>
  					<div class="row">
				<?php }?>			
  				<div class="slide_div"><a href="#x" class="thumbnail"><img src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']?>" onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"  alt="Image" class="img-responsive slide_img"></a>
                	<p class="text-over-img"><?php  $temp = $anime['titles']; $titles = convert_titles_to_hash($temp); echo $titles[$anime['canonical_title']];?></p>
                </div>		
			    <?php $counter++;?>
	  		<?php }?>
			<?php echo "</div>";?>
			</div>
	   </div>	 
	  <a class="left carousel-control" href="#slider" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true" onclick="checkParaLen()"></span></a>
	  <a class="right carousel-control" href="#slider" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true" onclick="checkParaLen()" ></span></a>
</div>