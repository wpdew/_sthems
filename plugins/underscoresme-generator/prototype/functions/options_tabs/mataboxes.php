<?php

function my_stylesheet1(){
wp_enqueue_style("style-admin",get_bloginfo('stylesheet_directory')."/functions/options_tabs/style.css");
}
add_action('admin_head', 'my_stylesheet1');

function my_js(){
wp_enqueue_script("style-admin",get_bloginfo('stylesheet_directory')."/functions/options_tabs/zozo.tabs.js", array('jquery'), $ver, 'in_footer');
}
add_action('admin_enqueue_scripts', 'my_js');
/*-----------------------------------------------------------------------------------*/
# Register The Meta Boxes
/*-----------------------------------------------------------------------------------*/
add_action("admin_init", "tie_posts_options_init");
function tie_posts_options_init(){
	add_meta_box("tie_post_options", 'superland', "tie_post_options_module", "post", "normal", "high");
	//add_meta_box("tie_post_options", 'superland', "tie_post_options_module", "page", "normal", "high");
    //add_meta_box("tie_post_options", 'superland', "tie_post_options_module", "product", "normal", "high");
	//add_meta_box("tie_post_general_options", 'superland', "tie_post_general_options_module", "post", "side", "default");
}


/*-----------------------------------------------------------------------------------*/
# Post & page Main Meta Boxes
/*-----------------------------------------------------------------------------------*/
function tie_post_options_module(){
	global $post, $wp_roles ;
	$get_meta = get_post_custom($post->ID);
	
	$checked = 'checked="checked"';
	
	//Sidebar Position -------------------- \\
	if( !empty($get_meta["tie_sidebar_pos"][0]) )
		$tie_sidebar_pos = $get_meta["tie_sidebar_pos"][0];

	//Get Categories 
	$categories_obj = get_categories();
	$categories = array();
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
	
	//Custom Sliders
	$original_post = $post;

	$sliders = array();
	$custom_slider = new WP_Query( array( 'post_type' => 'tie_slider', 'posts_per_page' => -1, 'no_found_rows' => 1  ) );
	while ( $custom_slider->have_posts() ) {
		$custom_slider->the_post();
		$sliders[get_the_ID()] = get_the_title();
	}

	$post = $original_post;
	wp_reset_query();

	
	
	//User Roles
	$roles = $wp_roles->get_names(); ?>
		
	
	
	<input type="hidden" name="tie_hidden_flag" value="true" />
	
	
	
	
	<?php //Post Head Options ----------------------------------------------------------------- */ ?>
	
 <div id="size-demo-02" data-role='z-tabs' data-options='{"multiline": false, "theme": "silver","spaced": true, "size": "mini", "orientation": "vertical", "animation": {"effects": "none"}}'>
 <ul>
    <li><a><?php _e( 'Post Head Options', 'rsnews' ) ?></a></li>
	<li><a><?php _e( 'Post settings', 'rsnews' ) ?></a></li>
	<li><a><?php _e( 'Ads Options', 'tie' ) ?></a></li>                           
	<li><a>Release Date</a></li>
    
 </ul>
 <div>
 <div>
 <?php			
		tie_post_meta_box(				
			array(	"name"		=> __( 'Display<br/>', 'rsnews' ),
					"id"		=> "tie_post_head",
					"type"		=> "select",
					"options"	=> array(
						''				=> __( 'Default', 'rsnews' ),
						'video'			=> __( 'Video', 'rsnews' ),
                        'thumbtop'=> __( 'Thumb top', 'rsnews' )
					)));
                    
        tie_post_meta_box(				
			array(	"name"	=> __( 'Embed Code<br/>', 'rsnews' ),
					"id"	=> "tie_embed_code",
					"type"	=> "textarea"));
							

		tie_post_meta_box(				
			array(	"name"	=> __( 'Video URL <br/><small>supports : YouTube, Vimeo, Viddler, Qik, Hulu, FunnyOrDie, DailyMotion, WordPress.tv and blip.tv</small><br />', 'rsnews' ),
					"id"	=> "tie_video_url",
					"type"	=> "text"));
        tie_post_meta_box(				
			array(	"name"	=> __( 'Self Hosted Video<br/>', 'rsnews' ),
					"id"	=> "tie_video_self",
					"type"	=> "text"));
                                
		
                    
		?>
 </div>
 
 
 <div>
 <?php
        //views				
		tie_post_meta_box(				
			array(	"name"	=> __( 'Views<br/>', 'rsnews' ),
					"id"	=> "views",
					"type"	=> "text"));
                    
                    
        //tie_views 
        tie_post_meta_box(				
			array(	"name"	=> __( 'Tie Views<br/>', 'rsnews' ),
					"id"	=> "tie_views",
					"type"	=> "text"));           
        ?>
 </div>
 
 <div>
 <?php	
    		tie_post_meta_box(				
    			array(	"name"	=> __( 'Hide Above Banner<br/>', 'tie' ),
    					"id"	=> "tie_hide_above",
    					"type"	=> "checkbox"));
    
    		tie_post_meta_box(				
    			array(	"name"	=> __( 'Custom Above Banner<br/>', 'tie' ),
    					"id"	=> "tie_banner_above",
    					"type"	=> "textarea"));
    
    		tie_post_meta_box(				
    			array(	"name"	=> __( 'Hide Below Banner<br/>', 'tie' ),
    					"id"	=> "tie_hide_below",
    					"type"	=> "checkbox"));
    
    		tie_post_meta_box(				
    			array(	"name"	=> __( 'Custom Below Banner<br/>', 'tie' ),
    					"id"	=> "tie_banner_below",
    					"type"	=> "textarea"));
    		?>
 </div>
 
 <div>Release Date</div> 
       
 </div>
 </div>  
    
    
    
    
    
    
	
		
        	
	
  <?php
}




/*-----------------------------------------------------------------------------------*/
# Get The Post Options
/*-----------------------------------------------------------------------------------*/
function tie_post_meta_box ( $value ){
	global $post;
	$data = false;
	$id = $value['id'];
	$get_meta = get_post_custom($post->ID);
	if( isset( $get_meta[$id][0] ) ) $data = $get_meta[$id][0]; 
	tie_options_build ( $value, $id, $data  );
}


/*-----------------------------------------------------------------------------------*/
# Save Post Options
/*-----------------------------------------------------------------------------------*/
add_action('save_post', 'tie_save_post');
function tie_save_post( $post_id ){
	global $post;
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;
		
    if (isset($_POST['tie_hidden_flag'])) {
	
		$custom_meta_fields = array(
			'tie_hide_meta',
			'tie_hide_author',
			'tie_hide_share',
			'tie_hide_related',
			'tie_hide_check_also',
			'tie_sidebar_pos',
			'tie_sidebar_post',
			'tie_post_head',
			'tie_post_head_cover',
			'tie_post_slider',
			'tie_googlemap_url',
			'tie_video_url',
			'tie_video_self',
			'tie_embed_code',
			'tie_audio_m4a',
			'tie_audio_mp3',
			'tie_audio_oga',
			'tie_audio_soundcloud',
			'tie_audio_soundcloud_play',
			'tie_audio_soundcloud_visual',
			'tie_hide_above',
			'tie_banner_above',
			'tie_hide_below',
			'tie_banner_below',
			'tie_posts_num',
			'post_color',
			'post_background_full',
			'tie_blog_cats',
			'post_background',
			'tie_authors',
            'views'
		);
			
		foreach( $custom_meta_fields as $custom_meta_field ){
			if( isset( $_POST[$custom_meta_field] ) && !empty( $_POST[ $custom_meta_field] ) ){
				$custom_meta_field_data = $_POST[$custom_meta_field];
				if( is_array( $custom_meta_field_data ) ){
					$custom_meta_field_data		= array_filter( $custom_meta_field_data );
					if( !empty( $custom_meta_field_data ) ){
						update_post_meta( $post_id, $custom_meta_field, $custom_meta_field_data );
					}else{
						delete_post_meta( $post_id, $custom_meta_field );
					}
				}else{
					if( !empty( $custom_meta_field_data ) ){
						update_post_meta( $post_id, $custom_meta_field, htmlspecialchars(stripslashes( $custom_meta_field_data )) );
					}else{
						delete_post_meta( $post_id, $custom_meta_field );
					}
				}
			}else{
				delete_post_meta( $post_id, $custom_meta_field );
			}
		}

	}
}




/*-----------------------------------------------------------------------------------*/
# Build The options
/*-----------------------------------------------------------------------------------*/
function tie_options_build( $value, $option_name, $data ){


	# get Google Fonts
	/*---------------------------*/
	require ('google-fonts.php');
	$google_font_array = json_decode ($google_api_output,true) ;

	$options_fonts = array();
	$options_fonts[''] = __( 'Default Font', 'rsnews' );
	foreach ($google_font_array as $item) {
		$variants='';
		$variantCount=0;
		foreach ($item['variants'] as $variant) {
			$variantCount++;
			if ($variantCount>1) { $variants .= '|'; }
			$variants .= $variant;
		}
		$variantText = ' (' . $variantCount .' '. __( 'Variants', 'rsnews' ) . ')';
		if ($variantCount <= 1) $variantText = '';
		$options_fonts[ $item['family'] . ':' . $variants ] = $item['family']. $variantText;
	}

 ?>
	<div class="option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label">
		<?php if( !empty($value['name']) ) echo $value['name']; ?></span>

	<?php
	switch ( $value['type'] ) {

		//Text Option
		case 'text': ?>
			<input name="<?php echo $option_name ?>" id="<?php  echo $value['id']; ?>" type="text" value="<?php if( !empty( $data ) ) echo $data; elseif( !empty( $value['default'] ) ) echo $value['default'];  ?>" />
			<?php
				if( $value['id']=="slider_tag" || $value['id']=="featured_posts_tag" || $value['id']=="breaking_tag"){
				$tags = get_tags('orderby=count&order=desc&number=50'); ?>
				<a style="cursor:pointer" title="<?php _e( 'Choose from the most used tags', 'rsnews' )  ?>" onclick="toggleVisibility('<?php echo $value['id']; ?>_tags');"><img src="<?php echo get_template_directory_uri(); ?>/framework/admin/images/expand.png" alt="" /></a>
				<span class="tags-list" id="<?php echo $value['id']; ?>_tags">
					<?php foreach ($tags as $tag){?>
						<a style="cursor:pointer" onclick="if(<?php echo $value['id'] ?>.value != ''){ var sep = ' , '}else{var sep = ''} <?php echo $value['id'] ?>.value=<?php echo $value['id'] ?>.value+sep+(this.rel);" rel="<?php echo $tag->name ?>"><?php echo $tag->name ?></a>
					<?php } ?>
				</span>
			<?php } ?>
		<?php
		break;


		//Array Option
		case 'arrayText':  $currentValue = $data;?>
			<input name="<?php echo $option_name ?>[<?php echo $value['key']; ?>]" id="<?php  echo $value['id']; ?>[<?php echo $value['key']; ?>]" type="text" value="<?php if( !empty( $currentValue[$value['key']] ) ) echo $currentValue[$value['key']] ?>" />
		<?php
		break;


		//Short-Text Option
		case 'short-text': ?>
			<input style="width:50px" name="<?php echo $option_name ?>" id="<?php  echo $value['id']; ?>" type="text" value="<?php if( !empty( $data ) ) echo $data; elseif( !empty( $value['default'] ) ) echo $value['default']; ?>" />
		<?php
		break;


		//Checkbox Option
		case 'checkbox':
			if( $data ){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input class="on-of" type="checkbox" name="<?php echo $option_name ?>" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />
		<?php
		break;


		//Radio Option
		case 'radio':
		?>
			<div class="option-contents">
				<?php
				$i = 0;
				foreach ($value['options'] as $key => $option) { $i++; ?>
				<label style="display:block; margin-bottom:8px;"><input name="<?php echo $option_name ?>" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( ( !empty(  $data ) && $data == $key ) || ( empty( $data ) && $i==1 ) ) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
				<?php } ?>
			</div>
		<?php
		break;


		//Select Menu Option
		case 'select':
		?>
			<select name="<?php echo $option_name ?>" id="<?php echo $value['id']; ?>">
				<?php
				$i = 0;
				foreach ($value['options'] as $key => $option) {  $i++; ?>
				<option value="<?php echo $key ?>" <?php if ( ( !empty(  $data ) && $data == $key ) || ( empty( $data ) && $i==1 ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		<?php
		break;


		//Textarea Option
		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left; width:350px;" name="<?php echo $option_name ?>" id="<?php echo $value['id']; ?>" type="textarea" rows="3" tabindex="4"><?php echo $data;  ?></textarea>
		<?php
		break;


		//Upload Option
		case 'upload':
		?>
				<input id="<?php echo $value['id']; ?>" class="img-path" type="text" size="56" style="direction:ltr; text-align:left" name="<?php echo $option_name ?>" value="<?php echo $data; ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="button" value="<?php _e( 'Upload', 'rsnews' )  ?>" />

				<?php if( isset( $value['extra_text'] ) ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>

				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if( !$data ) echo 'style="display:none;"' ?>>
					<img src="<?php if( $data ) echo $data; else echo get_template_directory_uri().'/framework/admin/images/empty.png'; ?>" alt="" />
					<a class="del-img" title="Delete"></a>
				</div>
				<script type='text/javascript'>
					jQuery('#<?php echo $value['id']; ?>').change(function(){
						jQuery('#<?php echo $value['id']; ?>-preview').show();
						jQuery('#<?php echo $value['id']; ?>-preview img').attr("src", jQuery(this).val());
					});
					tie_set_uploader( '<?php echo $value['id']; ?>' );
				</script>
		<?php
		break;


		//Slider Option
		case 'slider':
		?>
				<div id="<?php echo $value['id']; ?>-slider"></div>
				<input type="text" id="<?php echo $value['id']; ?>" value="<?php if( !empty( $data ) ) echo $data; elseif( !empty( $value['default'] ) ) echo $value['default']; else echo 0; ?>" name="<?php echo $option_name ?>" style="width:50px;" /> <?php echo $value['unit']; ?>
				<script>
				  jQuery(document).ready(function() {
					jQuery("#<?php echo $value['id']; ?>-slider").slider({
						range: "min",
						min: <?php echo $value['min']; ?>,
						max: <?php echo $value['max']; ?>,
						value: <?php if( !empty( $data ) ) echo $data; elseif( !empty( $value['default'] ) ) echo $value['default']; else echo 0; ?>,

						slide: function(event, ui) {
						jQuery('#<?php echo $value['id']; ?>').attr('value', ui.value );
						}
					});
				  });
				</script>
		<?php
		break;


		//Background Option
		case 'background':
			$current_value = $data;
			if( is_serialized( $current_value ) )
				$current_value = unserialize ( $current_value );
		?>
				<input id="<?php echo $value['id']; ?>-img" class="img-path" type="text" size="56" style="direction:ltr; text-align:left" name="<?php echo $option_name ?>[img]" value="<?php if( ! empty( $current_value['img'] )) echo $current_value['img']; ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="button" value="<?php _e( 'Upload', 'rsnews' )  ?>" />

				<div style="margin-top:15px; clear:both">
					<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
					<input style="width:100px;"  name="<?php echo $option_name ?>[color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />

					<select name="<?php echo $option_name ?>[repeat]" id="<?php echo $value['id']; ?>[repeat]" style="width:96px;">
						<option value="" <?php if ( !$current_value['repeat'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="repeat" <?php if ( $current_value['repeat']  == 'repeat' ) { echo ' selected="selected"' ; } ?>><?php _e( 'repeat', 'rsnews' )  ?></option>
						<option value="no-repeat" <?php if ( $current_value['repeat']  == 'no-repeat') { echo ' selected="selected"' ; } ?>><?php _e( 'no-repeat', 'rsnews' )  ?></option>
						<option value="repeat-x" <?php if ( $current_value['repeat'] == 'repeat-x') { echo ' selected="selected"' ; } ?>><?php _e( 'repeat-x', 'rsnews' )  ?></option>
						<option value="repeat-y" <?php if ( $current_value['repeat'] == 'repeat-y') { echo ' selected="selected"' ; } ?>><?php _e( 'repeat-y', 'rsnews' )  ?></option>
					</select>

					<select name="<?php echo $option_name ?>[attachment]" id="<?php echo $value['id']; ?>[attachment]" style="width:96px;">
						<option value="" <?php if ( !$current_value['attachment'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="fixed" <?php if ( $current_value['attachment']  == 'fixed' ) { echo ' selected="selected"' ; } ?>><?php _e( 'Fixed', 'rsnews' )  ?></option>
						<option value="scroll" <?php if ( $current_value['attachment']  == 'scroll') { echo ' selected="selected"' ; } ?>><?php _e( 'Scroll', 'rsnews' )  ?></option>
					</select>

					<select name="<?php echo $option_name ?>[hor]" id="<?php echo $value['id']; ?>[hor]" style="width:96px;">
						<option value="" <?php if ( !$current_value['hor'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="left" <?php if ( $current_value['hor']  == 'left' ) { echo ' selected="selected"' ; } ?>><?php _e( 'Left', 'rsnews' )  ?></option>
						<option value="right" <?php if ( $current_value['hor']  == 'right') { echo ' selected="selected"' ; } ?>><?php _e( 'Right', 'rsnews' )  ?></option>
						<option value="center" <?php if ( $current_value['hor'] == 'center') { echo ' selected="selected"' ; } ?>><?php _e( 'Center', 'rsnews' )  ?></option>
					</select>

					<select name="<?php echo $option_name ?>[ver]" id="<?php echo $value['id']; ?>[ver]" style="width:100px;">
						<option value="" <?php if ( !$current_value['ver'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="top" <?php if ( $current_value['ver']  == 'top' ) { echo ' selected="selected"' ; } ?>><?php _e( 'Top', 'rsnews' )  ?></option>
						<option value="bottom" <?php if ( $current_value['ver']  == 'bottom') { echo ' selected="selected"' ; } ?>><?php _e( 'Bottom', 'rsnews' )  ?></option>
						<option value="center" <?php if ( $current_value['ver'] == 'center') { echo ' selected="selected"' ; } ?>><?php _e( 'Center', 'rsnews' )  ?></option>

					</select>
				</div>
				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if( empty( $current_value['img'] )) echo 'style="display:none;"' ?>>
					<img src="<?php if( ! empty( $current_value['img'] )) echo $current_value['img'] ; else echo get_template_directory_uri().'/framework/admin/images/empty.png'; ?>" alt="" />
					<a class="del-img" title="Delete"></a>
				</div>

				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
					}
				});
				jQuery('#<?php echo $value['id']; ?>-img').change(function(){
					jQuery('#<?php echo $value['id']; ?>-preview').show();
					jQuery('#<?php echo $value['id']; ?>-preview img').attr("src", jQuery(this).val());
				});
				tie_set_uploader( '<?php echo $value['id']; ?>', true );
				</script>
		<?php
		break;


		//Color Option
		case 'color':
		?>
			<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $data; ?>"></div></div>
			<input style="width:80px;"  name="<?php echo $option_name ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php echo $data ; ?>" />

			<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo $data; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>').val('#'+hex);
					}
				});
				</script>
		<?php
		break;


		//Typography Option
		case 'typography':
			$current_value = $data;
		?>
				<div style="clear:both;"></div>
				<div style="clear:both; padding:10px 14px; margin:0 -15px;">
					<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
					<input style="width:80px;"  name="<?php echo $option_name ?>[color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />

					<select name="<?php echo $option_name ?>[size]" id="<?php echo $value['id']; ?>[size]" style="width:55px;">
						<option value="" <?php if (!$current_value['size'] ) { echo ' selected="selected"' ; } ?>></option>
					<?php for( $i=1 ; $i<101 ; $i++){ ?>
						<option value="<?php echo $i ?>" <?php if (( $current_value['size']  == $i ) ) { echo ' selected="selected"' ; } ?>><?php echo $i ?></option>
					<?php } ?>
					</select>

					<select name="<?php echo $option_name ?>[font]" id="<?php echo $value['id']; ?>[font]" style="width:190px;">
					<?php foreach( $options_fonts as $font => $font_name ){
						if( empty($font_name) || $font_name == 'Arabic' ){ ?>
						<optgroup disabled="disabled" label="<?php echo $font_name ?>"></optgroup>
						<?php  }else{ ?>
						<option value="<?php echo $font ?>" <?php if ( $current_value['font']  == $font ) { echo ' selected="selected"' ; } ?>><?php echo $font_name ?></option>
					<?php  }
					} ?>
					</select>

					<select name="<?php echo $option_name ?>[weight]" id="<?php echo $value['id']; ?>[weight]" style="width:96px;">
						<option value="" <?php if ( !$current_value['weight'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['weight']  == 'normal' ) { echo ' selected="selected"' ; } ?>><?php _e( 'Normal', 'rsnews' )  ?></option>
						<option value="bold" <?php if ( $current_value['weight']  == 'bold') { echo ' selected="selected"' ; } ?>><?php _e( 'Bold', 'rsnews' )  ?></option>
						<option value="lighter" <?php if ( $current_value['weight'] == 'lighter') { echo ' selected="selected"' ; } ?>><?php _e( 'Lighter', 'rsnews' )  ?></option>
						<option value="bolder" <?php if ( $current_value['weight'] == 'bolder') { echo ' selected="selected"' ; } ?>><?php _e( 'Bolder', 'rsnews' )  ?></option>
						<option value="100" <?php if ( $current_value['weight'] == '100') { echo ' selected="selected"' ; } ?>>100</option>
						<option value="200" <?php if ( $current_value['weight'] == '200') { echo ' selected="selected"' ; } ?>>200</option>
						<option value="300" <?php if ( $current_value['weight'] == '300') { echo ' selected="selected"' ; } ?>>300</option>
						<option value="400" <?php if ( $current_value['weight'] == '400') { echo ' selected="selected"' ; } ?>>400</option>
						<option value="500" <?php if ( $current_value['weight'] == '500') { echo ' selected="selected"' ; } ?>>500</option>
						<option value="600" <?php if ( $current_value['weight'] == '600') { echo ' selected="selected"' ; } ?>>600</option>
						<option value="700" <?php if ( $current_value['weight'] == '700') { echo ' selected="selected"' ; } ?>>700</option>
						<option value="800" <?php if ( $current_value['weight'] == '800') { echo ' selected="selected"' ; } ?>>800</option>
						<option value="900" <?php if ( $current_value['weight'] == '900') { echo ' selected="selected"' ; } ?>>900</option>
					</select>

					<select name="<?php echo $option_name ?>[style]" id="<?php echo $value['id']; ?>[style]" style="width:100px;">
						<option value="" <?php if ( !$current_value['style'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['style']  == 'normal' ) { echo ' selected="selected"' ; } ?>><?php _e( 'Normal', 'rsnews' )  ?></option>
						<option value="italic" <?php if ( $current_value['style'] == 'italic') { echo ' selected="selected"' ; } ?>><?php _e( 'Italic', 'rsnews' )  ?></option>
						<option value="oblique" <?php if ( $current_value['style']  == 'oblique') { echo ' selected="selected"' ; } ?>><?php _e( 'Oblique', 'rsnews' )  ?></option>
					</select>
				</div>


				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
						<?php if( $value['id'] == 'typography_test' ): ?>
						jQuery('#font-preview').css('color', '#' + hex);
						<?php endif; ?>
					}
				});
				</script>
		<?php
		break;
	}
	?>
	<?php if( isset( $value['extra_text'] ) && $value['type'] != 'upload' ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>
	<?php if( isset( $value['help'] ) ) : ?>
		<a class="mo-help tie-tooltip"  title="<?php echo $value['help'] ?>"></a>
		<?php endif; ?>
	</div>
<?php
}
?>