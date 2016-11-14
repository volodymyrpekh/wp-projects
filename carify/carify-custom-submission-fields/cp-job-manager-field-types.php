<?php
/*
  Plugin Name: Carify Custom Submission Fields (CP Job Manager Field Types)
  Plugin URI: 
  Description: This plugin creates additional custom fields for available spaces in submission form
  Version: 1.0.0
  Author: Vlad Pekh
  Author URI: http://www.vpekh.com
 */

add_action( 'add_meta_boxes', 'cp_add_available_places_metabox');
function cp_add_available_places_metabox(){
	add_meta_box('cp_available_places', 'Available Places', 'cp_available_places_location', 'job_listing','normal');
}
function cp_available_places_location(){
	global $post;
	$val=get_post_meta($post->ID, '_job_spaces',true);
	$fields='<!--'.var_export($val,true).'-->';
	$fields='<style>.tab-wrapper fieldset{width:20%;}.tab-wrapper fieldset{min-height:50px !important;}.tab-wrapper{padding:10px 10px 10px;display:block;opacity:1;margin:0;}div[id*="c_tab"]::before, div[id*="c_all"]::before {content:"";}</style>';
	$fields.='<script>$(document).ready(function() {jQuery(".jmfe-date-picker").each(function(){
		jQuery(this).datepicker({
			dateFormat:jmfe_date_field.dateFormat,
			monthNames:jmfe_date_field.monthNames,
			monthNamesShort:jmfe_date_field.monthNamesShort,
			dayNames:jmfe_date_field.dayNames,
			dayNamesShort:jmfe_date_field.dayNamesShort,
			dayNamesMin:jmfe_date_field.dayNamesMin
		})
	})})</script>';
	$fields.='';
	for($y=1;$y<=5;$y++){
		$i=$y-1;
		$fields.='<input type="hidden" name="available_places_noncename" id="available_places_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		$fields.='<div id="description-c_tab'.$y.'" style="margin-bottom: 32px;"><small class="description">Childcare Space '.$y.'</small></div>
			<div class="tab-wrapper" data-group="description-c_tab'.$y.'">
				<fieldset class="fieldset-child_openings selectfields">
					<label for="child_openings" style="display:inline;">Openings</label>
					<div class="field " style="width: 90%;">
						<select name="job_spaces[child_openings][]" id="child_openings">
							<option value="" '.selected($val[child_openings][$i],'',false).' >None</option>
							<option value="1" '.selected($val[child_openings][$i],'1',false).' >1</option>
							<option value="2" '.selected($val[child_openings][$i],'2',false).' >2</option>
							<option value="3" '.selected($val[child_openings][$i],'3',false).' >3</option>
							<option value="4" '.selected($val[child_openings][$i],'4',false).' >4</option>
							<option value="5" '.selected($val[child_openings][$i],'5',false).' >5</option>
							<option value="6" '.selected($val[child_openings][$i],'6',false).' >6</option>
							<option value="7" '.selected($val[child_openings][$i],'7',false).' >7</option>
							<option value="8" '.selected($val[child_openings][$i],'8',false).' >8</option>
							<option value="9" '.selected($val[child_openings][$i],'9',false).' >9</option>
							<option value="10" '.selected($val[child_openings][$i],'10',false).' >10</option>
						</select>
					</div>
				</fieldset>
				<fieldset class="fieldset-child_ages selectfields">
					<label for="child_ages">Age Group</label>
					<div class="field " style="width: 90%;">
						<select name="job_spaces[child_ages][]" id="child_ages">
							<option value="" '.selected($val[child_ages][$i],'',false).' >Any</option>
							<option value="infant" '.selected($val[child_ages][$i],'infant',false).' >Infant</option>
							<option value="toddler" '.selected($val[child_ages][$i],'toddler',false).' >Toddler</option>
							<option value="preschool" '.selected($val[child_ages][$i],'preschool',false).' >Preschool</option>
							<option value="kindergarten" '.selected($val[child_ages][$i],'kindergarten',false).' >Kindergarten</option>
							<option value="schoolage" '.selected($val[child_ages][$i],'schoolage',false).' >School age</option>
						</select>
					</div>
				</fieldset>
				<fieldset class="fieldset-child_startdate calendarfields">
					<label for="child_startdate">Starting</label>
					<div class="field ">
						<input type="text" class="jmfe-date-picker input-text" name="job_spaces[child_startdate][]" id="child_startdate'.$y.'" placeholder="" value="'.$val[child_startdate][$i].'" maxlength="">
					</div>
				</fieldset>
				<fieldset class="fieldset-child_rates textfields">
					<label for="child_rates">Rate per day</label>
					<div class="field ">
						<input type="text" class="input-text" name="job_spaces[child_rates][]" id="child_rates" title="" placeholder="" value="'.$val[child_rates][$i].'" maxlength="">
					</div>
				</fieldset>
				<fieldset class="fieldset-child_notes textfields">
					<label for="child_notes">Additional notes</label>
					<div class="field ">
						<input type="text" class="input-text" name="job_spaces[child_notes][]" id="child_notes" title="" placeholder="" value="'.$val[child_notes][$i].'" maxlength="">
					</div>
				</fieldset>
			</div>';
	}
	echo $fields;
}
function getBetweenStr($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);    
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
// add_filter( 'job_manager_get_posted_available_spaces_field', 'cp_save_field_type' );
// function cp_save_field_type($key, $field) {
	// echo '<!--';
	// var_export($key);
	// var_export($field);
	// echo '-->';
// }
add_filter( 'field_editor_field_types', 'cp_add_field_type' );
function cp_add_field_type($field_types){	
	$field_types['available_spaces'] = __( 'Available Spaces', 'cp-job-manager-field-types' );	
	//var_export($field_types);	
	return $field_types;
}
add_action('submit_job_form_job_fields_start','cp_submit_job_form_job_fields_start');
add_action('submit_job_form_job_fields_end','cp_submit_job_form_job_fields_end');
function cp_submit_job_form_job_fields_start(){
	ob_start();
}
function cp_submit_job_form_job_fields_end(){
	global $job_manager;
	$page = ob_get_contents();
	ob_end_clean();
	$f=new WP_Job_Manager_Form_Submit_Job();
	$job_id=$f->get_job_id();
	if (!$job_id) {
		$job_id= mysql_real_escape_string($_GET['job_id']);
	}
	$val=get_post_meta($job_id, '_job_spaces',true);
	$fields='<div id="description-c_all" style="margin-bottom: 32px;" class=""><small class="description">Available Spaces</small></div>';
	for($y=1;$y<=5;$y++){
		$i=$y-1;
		$fields.='<div id="description-c_tab'.$y.'" style="margin-bottom: 32px; display: none; cursor: pointer;"><small class="description">Childcare Space '.$y.'</small></div>
			<div class="tab-wrapper" data-group="description-c_tab'.$y.'">
				<fieldset class="fieldset-child_openings selectfields">
					<label for="child_openings" style="display:inline;">Openings</label>
					<div class="field ">
						<select name="job_spaces[child_openings][]" id="child_openings">
							<option value="" '.selected($val[child_openings][$i],'',false).' >None</option>
							<option value="1" '.selected($val[child_openings][$i],'1',false).' >1</option>
							<option value="2" '.selected($val[child_openings][$i],'2',false).' >2</option>
							<option value="3" '.selected($val[child_openings][$i],'3',false).' >3</option>
							<option value="4" '.selected($val[child_openings][$i],'4',false).' >4</option>
							<option value="5" '.selected($val[child_openings][$i],'5',false).' >5</option>
							<option value="6" '.selected($val[child_openings][$i],'6',false).' >6</option>
							<option value="7" '.selected($val[child_openings][$i],'7',false).' >7</option>
							<option value="8" '.selected($val[child_openings][$i],'8',false).' >8</option>
							<option value="9" '.selected($val[child_openings][$i],'9',false).' >9</option>
							<option value="10" '.selected($val[child_openings][$i],'10',false).' >10</option>
						</select>
					</div>
				</fieldset>
				<fieldset class="fieldset-child_ages selectfields">
					<label for="child_ages">Age Group</label>
					<div class="field ">
						<select name="job_spaces[child_ages][]" id="child_ages">
							<option value="" '.selected($val[child_ages][$i],'',false).' >Any</option>
							<option value="infant" '.selected($val[child_ages][$i],'infant',false).' >Infant</option>
							<option value="toddler" '.selected($val[child_ages][$i],'toddler',false).' >Toddler</option>
							<option value="preschool" '.selected($val[child_ages][$i],'preschool',false).' >Preschool</option>
							<option value="kindergarten" '.selected($val[child_ages][$i],'kindergarten',false).' >Kindergarten</option>
							<option value="schoolage" '.selected($val[child_ages][$i],'schoolage',false).' >School age</option>
						</select>
					</div>
				</fieldset>
				<fieldset class="fieldset-child_startdate calendarfields">
					<label for="child_startdate">Starting</label>
					<div class="field ">
						<input type="text" class="jmfe-date-picker input-text" name="job_spaces[child_startdate][]" id="child_startdate'.$y.'" placeholder="" value="'.$val[child_startdate][$i].'" maxlength="">
					</div>
				</fieldset>
				<fieldset class="fieldset-child_rates textfields">
					<label for="child_rates">Rate per day</label>
					<div class="field ">
						<input type="text" class="input-text" name="job_spaces[child_rates][]" id="child_rates" title="" placeholder="" value="'.$val[child_rates][$i].'" maxlength="">
					</div>
				</fieldset>
				<fieldset class="fieldset-child_notes textfields">
					<label for="child_notes">Additional notes</label>
					<div class="field ">
						<input type="text" class="input-text" name="job_spaces[child_notes][]" id="child_notes" title="" placeholder="" value="'.$val[child_notes][$i].'" maxlength="">
					</div>
				</fieldset>
				<span class="clearAll">Reset Fields</span>
			</div>';
	}
	echo str_replace(getBetweenStr($page,'<!--startavailable_spaces-->','<!--endavailable_spaces-->'),$fields,$page);
}
function cp_scripts_method() {
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script( 'cp_avail_script', plugins_url( 'ccsf.js', __FILE__ ),array( 'jquery-ui-datepicker' ) );
	$translation_array = json_decode('{"showButtonPanel":"1","closeText":"Done","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"MM d, yy","firstDay":"0"}', true);
	wp_localize_script( 'cp_avail_script', 'jmfe_date_field', $translation_array );
}
// Localize the script with new data
add_action( 'wp_enqueue_scripts', 'cp_scripts_method' );
add_action('wp_print_styles', 'cp_add_my_stylesheet');
function my_enqueue() {
    global $post_type;
    if( 'job_listing' != $post_type ){
        return;
    }
	wp_enqueue_script('jquery-ui-datepicker');
	// wp_enqueue_script( 'cp_avail_script', plugins_url( 'ccsf.js', __FILE__ ),array( 'jquery-ui-datepicker' ) );
	$translation_array = json_decode('{"showButtonPanel":"1","closeText":"Done","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"MM d, yy","firstDay":"0"}', true);
	wp_localize_script( 'jquery-ui-datepicker', 'jmfe_date_field', $translation_array );
	$myStyleUrl = plugins_url( 'ccsf.css', __FILE__ );
	wp_register_style('myStyleSheets', $myStyleUrl);
	wp_enqueue_style( 'myStyleSheets');
	wp_enqueue_style('jquery-style', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    //wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'myscript.js' );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );
/*
 * Добавляем в очередь файл стилей, если он существует.
 */

function cp_add_my_stylesheet() {
	$myStyleUrl = plugins_url( 'ccsf.css', __FILE__ );
	wp_register_style('myStyleSheets', $myStyleUrl);
	wp_enqueue_style( 'myStyleSheets');
	wp_enqueue_style('jquery-style', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
}
class Available_Places_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'available_places_widget', // Base ID
			__( 'Available places Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Available places Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $post;
		$val=get_post_meta($post->ID, '_job_spaces',true);
		if (!!$val){
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] .'<i class="ion-android-contacts"></i>&nbsp;&nbsp;'. apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo '<table id="spaces"><thead><tr><th>Openings</th><th>Age Group</th><th>Starting</th><th>Rate</th><th>Additional notes</th></th></thead><tbody>';
			for($y=0;$y<=4;$y++){
				if (!!$val[child_openings][$y]){
					if (!$val[child_ages][$y]){
						$val[child_ages][$y]='any';
					}
					$val[child_ages][$y]=ucfirst($val[child_ages][$y]);
					echo "<tr><td data-th=\"Openings\">{$val[child_openings][$y]}</td><td data-th=\"Age Group\">{$val[child_ages][$y]}</td><td data-th=\"Starting\">{$val[child_startdate][$y]}</td><td data-th=\"Rate\">\${$val[child_rates][$y]}/day</td><td data-th=\"Additional notes\">{$val[child_notes][$y]}</td></tr>";
				}
			}
			echo '</tbody></table>';
			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
function register_available_places_widget() {
    register_widget( 'Available_Places_Widget' );
}
add_action( 'widgets_init', 'register_available_places_widget' );

// Save the Metabox Data

function cp_save_available_places_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['available_places_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$val = $_POST['job_spaces'];
	
	// Add values of $events_meta as custom fields
	$key='_job_spaces';
	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	//$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
	if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, $key, $val);
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, $key, $val);
	}
	if(!$val) delete_post_meta($post->ID, $key); // Delete if blank

}

add_action('save_post', 'cp_save_available_places_meta', 1, 2); // save the custom fields
?>