<?php include 'head.php';?>

<script>
$(document).ready(function() {
	$('head').append('<script src="<?php echo asset_url() . "tinymce/tinymce.min.js";?>">');
	$('head').append('<script src="<?php echo asset_url() . "js/review.js";?>">');
	
	initEditor();

	var story = <?php if(isset($review)) echo $review['story']; else echo 0;?>;
	var animation = <?php if(isset($review)) echo $review['animation']; else echo 0;?>;
	var sound = <?php if(isset($review)) echo $review['sound']; else echo 0;?>;
	var characters = <?php if(isset($review)) echo $review['characters']; else echo 0;?>;
	var enjoyment = <?php if(isset($review)) echo $review['enjoyment']; else echo 0;?>;
	var overall = <?php if(isset($review)) echo $review['overall']; else echo 0;?>;
	
	fill_scores(story, animation, sound, characters, enjoyment, overall);
});

</script>

<?php include 'navigation.php';?>

<?php 
if (!isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/login_page"));
}
?>

<div id="wrap">
	<div class="container-fluid scrollable content">
		<div id="review_div">
			<p id="review_title"><?php echo "<a id='anime_title' href='" . site_url("animeContent/anime/{$slug}") . "' >" . $anime_name . "</a>";?> <span style="color: #23272D;">Review</span></p>
			<form id="submit_review_form" action="<?php echo site_url("reviews/submit_review/{$anime_id}")?>" method="post">
			<input type="hidden" name="anime_id" value="<?php echo $anime_id;?>">
			<div id="wrap_review_body">
				<div id="review_text_div">
					<textarea name="user_review" id="review_text_editor"><?php if(isset($review)) echo stripslashes($review['review_text']);?></textarea>
					<div id="loading_div"><span class="helper"></span><img src="<?php echo asset_url() . "imgs/loading_icon.gif";?>" id="loading_icon"></div>
					<input type="hidden" name="raw_text" id="raw_text">
					<p id="textarea_characters"></p>
				</div>
			</div>
			<div id="wrap_review_scores">
				<p class="score_title">Story</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="s1" name="story" value="1" />
					    <label for="s1">1</label>
					</li>
					<li>
					    <input type="radio" id="s2" name="story" value="2" />
					    <label for="s2">2</label>
					</li>
					<li>
					    <input type="radio" id="s3" name="story" value="3" />
					    <label for="s3">3</label>
					</li>
					<li>
					    <input type="radio" id="s4" name="story"  value="4" />
					    <label for="s4">4</label>
					</li>
					<li>
					    <input type="radio" id="s5" name="story" value="5" />
					    <label for="s5">5</label>
					</li>
					<li>
					    <input type="radio" id="s6" name="story" value="6" />
					    <label for="s6">6</label>
					</li>
					<li>
					    <input type="radio" id="s7" name="story" value="7" />
					    <label for="s7">7</label>
					</li>
					<li>
					    <input type="radio" id="s8" name="story" value="8" />
					    <label for="s8">8</label>
					</li>
					<li>
					    <input type="radio" id="s9" name="story" value="9" />
					    <label for="s9">9</label>
					</li>
					<li>
					    <input type="radio" id="s10" name="story" value="10" />
					    <label for="s10">10</label>
					</li>
				</ul>
				
				<p class="score_title">Animation</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="a1" name="animation" value="1" />
					    <label for="a1">1</label>
					</li>
					<li>
					    <input type="radio" id="a2" name="animation" value="2" />
					    <label for="a2">2</label>
					</li>
					<li>
					    <input type="radio" id="a3" name="animation" value="3" />
					    <label for="a3">3</label>
					</li>
					<li>
					    <input type="radio" id="a4" name="animation"  value="4" />
					    <label for="a4">4</label>
					</li>
					<li>
					    <input type="radio" id="a5" name="animation" value="5" />
					    <label for="a5">5</label>
					</li>
					<li>
					    <input type="radio" id="a6" name="animation" value="6" />
					    <label for="a6">6</label>
					</li>
					<li>
					    <input type="radio" id="a7" name="animation" value="7" />
					    <label for="a7">7</label>
					</li>
					<li>
					    <input type="radio" id="a8" name="animation" value="8" />
					    <label for="a8">8</label>
					</li>
					<li>
					    <input type="radio" id="a9" name="animation" value="9" />
					    <label for="a9">9</label>
					</li>
					<li>
					    <input type="radio" id="a10" name="animation" value="10" />
					    <label for="a10">10</label>
					</li>
				</ul>
				
				<p class="score_title">Sound</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="so1" name="sound" value="1" />
					    <label for="so1">1</label>
					</li>
					<li>
					    <input type="radio" id="so2" name="sound" value="2" />
					    <label for="so2">2</label>
					</li>
					<li>
					    <input type="radio" id="so3" name="sound" value="3" />
					    <label for="so3">3</label>
					</li>
					<li>
					    <input type="radio" id="so4" name="sound"  value="4" />
					    <label for="so4">4</label>
					</li>
					<li>
					    <input type="radio" id="so5" name="sound" value="5" />
					    <label for="so5">5</label>
					</li>
					<li>
					    <input type="radio" id="so6" name="sound" value="6" />
					    <label for="so6">6</label>
					</li>
					<li>
					    <input type="radio" id="so7" name="sound" value="7" />
					    <label for="so7">7</label>
					</li>
					<li>
					    <input type="radio" id="so8" name="sound" value="8" />
					    <label for="so8">8</label>
					</li>
					<li>
					    <input type="radio" id="so9" name="sound" value="9" />
					    <label for="so9">9</label>
					</li>
					<li>
					    <input type="radio" id="so10" name="sound" value="10" />
					    <label for="so10">10</label>
					</li>
				</ul>
				
				<p class="score_title">Characters</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="c1" name="characters" value="1" />
					    <label for="c1">1</label>
					</li>
					<li>
					    <input type="radio" id="c2" name="characters" value="2" />
					    <label for="c2">2</label>
					</li>
					<li>
					    <input type="radio" id="c3" name="characters" value="3" />
					    <label for="c3">3</label>
					</li>
					<li>
					    <input type="radio" id="c4" name="characters"  value="4" />
					    <label for="c4">4</label>
					</li>
					<li>
					    <input type="radio" id="c5" name="characters" value="5" />
					    <label for="c5">5</label>
					</li>
					<li>
					    <input type="radio" id="c6" name="characters" value="6" />
					    <label for="c6">6</label>
					</li>
					<li>
					    <input type="radio" id="c7" name="characters" value="7" />
					    <label for="c7">7</label>
					</li>
					<li>
					    <input type="radio" id="c8" name="characters" value="8" />
					    <label for="c8">8</label>
					</li>
					<li>
					    <input type="radio" id="c9" name="characters" value="9" />
					    <label for="c9">9</label>
					</li>
					<li>
					    <input type="radio" id="c10" name="characters" value="10" />
					    <label for="c10">10</label>
					</li>
				</ul>
				
				<p class="score_title">Enjoyment</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="e1" name="enjoyment" value="1" />
					    <label for="e1">1</label>
					</li>
					<li>
					    <input type="radio" id="e2" name="enjoyment" value="2" />
					    <label for="e2">2</label>
					</li>
					<li>
					    <input type="radio" id="e3" name="enjoyment" value="3" />
					    <label for="e3">3</label>
					</li>
					<li>
					    <input type="radio" id="e4" name="enjoyment" value="4" />
					    <label for="e4">4</label>
					</li>
					<li>
					    <input type="radio" id="e5" name="enjoyment" value="5" />
					    <label for="e5">5</label>
					</li>
					<li>
					    <input type="radio" id="e6" name="enjoyment" value="6" />
					    <label for="e6">6</label>
					</li>
					<li>
					    <input type="radio" id="e7" name="enjoyment" value="7" />
					    <label for="e7">7</label>
					</li>
					<li>
					    <input type="radio" id="e8" name="enjoyment" value="8" />
					    <label for="e8">8</label>
					</li>
					<li>
					    <input type="radio" id="e9" name="enjoyment" value="9" />
					    <label for="e9">9</label>
					</li>
					<li>
					    <input type="radio" id="e10" name="enjoyment" value="10" />
					    <label for="e10">10</label>
					</li>
				</ul>
				
				<p class="score_title">Overall</p>
				<ul class="score_div">
					<li>
					    <input type="radio" id="o1" name="overall" value="1" />
					    <label for="o1">1</label>
					</li>
					<li>
					    <input type="radio" id="o2" name="overall" value="2" />
					    <label for="o2">2</label>
					</li>
					<li>
					    <input type="radio" id="o3" name="overall" value="3" />
					    <label for="o3">3</label>
					</li>
					<li>
					    <input type="radio" id="o4" name="overall"  value="4" />
					    <label for="o4">4</label>
					</li>
					<li>
					    <input type="radio" id="o5" name="overall" value="5" />
					    <label for="o5">5</label>
					</li>
					<li>
					    <input type="radio" id="o6" name="overall" value="6" />
					    <label for="o6">6</label>
					</li>
					<li>
					    <input type="radio" id="o7" name="overall" value="7" />
					    <label for="o7">7</label>
					</li>
					<li>
					    <input type="radio" id="o8" name="overall" value="8" />
					    <label for="o8">8</label>
					</li>
					<li>
					    <input type="radio" id="o9" name="overall" value="9" />
					    <label for="o9">9</label>
					</li>
					<li>
					    <input type="radio" id="o10" name="overall" value="10" />
					    <label for="o10">10</label>
					</li>
				</ul>			
			</div>		
			<div id="#submit_review_button_div">
				<button id="submit_review_button" type="submit" class="btn btn-primary button-blue"><?php if(isset($review)) echo "Update"; else echo "Submit";?></button>
			</div>
		</form>
			<hr>
		</div>
	</div>
</div>

<?php include 'footer.php';?>