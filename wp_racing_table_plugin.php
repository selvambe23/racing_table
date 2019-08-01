<?php
/*
Plugin Name: WordPress Get Race Data
Description: Creates an interfaces to get and manage race datas from other websites.
Version:     1.0.0
Author:      Selvam Murugan
Author URI:  https://github.com/selvambe23
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class wp_simple_location{
	
	public function __construct(){

		add_action('init', array($this,'register_race_data_post_type'));
		add_action('add_meta_boxes', array($this,'add_table_meta_boxes')); //add meta boxes
		add_action('save_post_wp_race_tables', array($this,'save_race_tables')); //save location
		add_action('admin_enqueue_scripts', array($this,'enqueue_admin_scripts_and_styles')); //admin scripts and styles
		add_action('wp_enqueue_scripts', array($this,'enqueue_public_scripts_and_styles')); //public scripts and styles
		
		register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
		register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
		
	}
	
	//register the location content type
	public function register_race_data_post_type(){
		 //Labels for post type
		 $labels = array(
            'name'               => 'Race Tables',
            'singular_name'      => 'Race Details',
            'menu_name'          => 'Race Details',
            'name_admin_bar'     => 'Race Detail',
            'add_new'            => 'Add New', 
            'add_new_item'       => 'Add New Race Table',
            'new_item'           => 'New Race Table', 
            'edit_item'          => 'Edit Race',
            'view_item'          => 'View Race',
            'all_items'          => 'All Race Tables',
            'search_items'       => 'Search Races',
            'parent_item_colon'  => 'Parent Race:', 
            'not_found'          => 'No Races found.', 
            'not_found_in_trash' => 'No Races found in Trash.',
        );
        //arguments for post type
        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'publicly_queryable'=> true,
            'show_ui'           => true,
            'show_in_nav'       => true,
            'query_var'         => true,
            'hierarchical'      => false,
            'supports'          => array('title'),
            'has_archive'       => true,
            'menu_position'     => 20,
            'show_in_admin_bar' => true,
            'menu_icon'         => 'dashicons-location-alt',
            'rewrite'			=> array('slug' => 'race_tables', 'with_front' => 'true')
        );
        //register post type
        register_post_type('wp_race_tables', $args);
	}

	//adding meta boxes for the location content type*/
	public function add_table_meta_boxes(){
		add_meta_box(
			'wp_table_meta_box', //id
			'Race Information', //name
			array($this,'race_meta_box_display'), //display function
			'wp_race_tables', //post type
			'normal', //location
			'default' //priority
        );
        add_meta_box(
			'wp_fetch_form_meta_box', //id
			'Fetch Race Information From Url', //name
			array($this,'race_fetch_form_meta_box_display'), //display function
			'wp_race_tables', //post type
			'normal', //location
			'high' //priority
		);
    }
    
    public function race_fetch_form_meta_box_display($post){
        $fetch_url = $post->filter == 'edit' ? get_post_meta($post->ID,'fetch_url',true): '';
    ?>
	    <div class="field-container">
			<div class="field">
				<label for="fetch_url">Enter Valid Url</label>
				<input type="url" name="fetch_url" id="fetch_url" value="<?php echo $fetch_url; ?>"/>
                <span id="UrlError" class="hide">Enter Valid Url</span>
            </div>
            <div class="field">
				<input type="button" name="start_fetch" id="start_fetch" value="Fetch Data's"/>
            </div>
        </div>
    <?php }
	
	//display function used for our custom location meta box*/
	public function race_meta_box_display($post){
		//set nonce field
        wp_nonce_field('wp_race_nonce', 'wp_race_nonce_field');

        if($post->filter == 'edit'){ ?>
        <div class="field-container" id="raceStatusTable">
            <?php $totalRows = get_post_meta($post->ID,'totalRows',true);
            for($i=0; $i < $totalRows; $i++){
                $race_id = get_post_meta($post->ID,'race_id_'.$i,true);
                $race_name = get_post_meta($post->ID,'race_name_'.$i,true);
                $race_nr = get_post_meta($post->ID,'race_nr_'.$i,true);
                $race_box = get_post_meta($post->ID,'race_box_'.$i,true);
                $race_abstand = get_post_meta($post->ID,'race_abstand_'.$i,true);
                $race_gewinn = get_post_meta($post->ID,'race_gewinn_'.$i,true);
                $race_besitzer = get_post_meta($post->ID,'race_besitzer_'.$i,true);
                $race_trainer = get_post_meta($post->ID,'race_trainer_'.$i,true);
                $race_reiter = get_post_meta($post->ID,'race_reiter_'.$i,true);
                $race_gew = get_post_meta($post->ID,'race_gew_'.$i,true);
                $race_quote = get_post_meta($post->ID,'race_quote_'.$i,true);
            ?>
                <div class="field">
                    <label for="race_id_<?php echo $i;?>">Pl Id</label>
                    <input type="number" name="race_id_<?php echo $i;?>" id="race_id_<?php echo $i;?>" value="<?php echo $race_id;?>"/>
                </div>
                <div class="field">
                    <label for="race_name_<?php echo $i;?>">Name</label>
                    <input type="text" name="race_name_<?php echo $i;?>" id="race_name_<?php echo $i;?>" value="<?php echo $race_name;?>"/>
                </div>
                <div class="field">
                    <label for="race_nr_<?php echo $i;?>">Nr</label>
                    <input type="text" name="race_nr_<?php echo $i;?>" id="race_nr_<?php echo $i;?>" value="<?php echo $race_nr;?>"/>
                </div>
                <div class="field">
                    <label for="race_box_<?php echo $i;?>">Box</label>
                    <input type="text" name="race_box_<?php echo $i;?>" id="race_box_<?php echo $i;?>" value="<?php echo $race_box;?>"/>
                </div>
                <div class="field">
                    <label for="race_abstand_<?php echo $i;?>">Abstand</label>
                    <input type="text" name="race_abstand_<?php echo $i;?>" id="race_abstand_<?php echo $i;?>" value="<?php echo $race_abstand;?>"/>
                </div>
                <div class="field">
                    <label for="race_gewinn_<?php echo $i;?>">Gewinn</label>
                    <input type="text" name="race_gewinn_<?php echo $i;?>" id="race_gewinn_<?php echo $i;?>" value="<?php echo $race_gewinn;?>"/>
                </div>
                <div class="field">
                    <label for="race_besitzer_<?php echo $i;?>">Besitzer</label>
                    <input type="text" name="race_besitzer_<?php echo $i;?>" id="race_besitzer_<?php echo $i;?>" value="<?php echo $race_besitzer;?>"/>
                </div>
                <div class="field">
                    <label for="race_trainer_<?php echo $i;?>">Trainer</label>
                    <input type="text" name="race_trainer_<?php echo $i;?>" id="race_trainer_<?php echo $i;?>" value="<?php echo $race_trainer;?>"/>
                </div>
                <div class="field">
                    <label for="race_reiter_<?php echo $i;?>">Reiter</label>
                    <input type="text" name="race_reiter_<?php echo $i;?>" id="race_reiter_<?php echo $i;?>" value="<?php echo $race_reiter;?>"/>
                </div>
                <div class="field">
                    <label for="race_gew_<?php echo $i;?>">Gew</label>
                    <input type="text" name="race_gew_<?php echo $i;?>" id="race_gew_<?php echo $i;?>" value="<?php echo $race_gew;?>"/>
                </div>
                <div class="field">
                    <label for="race_quote_<?php echo $i;?>">Quote</label>
                    <input type="text" name="race_quote_<?php echo $i;?>" id="race_quote_<?php echo $i;?>" value="<?php echo $race_quote;?>"/>
                </div>
            <?php } ?>
            <input type="hidden" name="totalRows" value="<?php echo $totalRows; ?>"/>
        </div>
        <?php } else { ?>
            <div class="field-container" id="raceStatusTable">
            </div>
       <?php }
	}
	
	//triggered when adding or editing a location
	public function save_race_tables($post_id){

		//check for nonce
		if(!isset($_POST['wp_race_nonce_field'])){
			return $post_id;
		}	
		//verify nonce
		if(!wp_verify_nonce($_POST['wp_race_nonce_field'], 'wp_race_nonce')){
			return $post_id;
		}
		//check for autosave
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $post_id;
        }

        for($i=0; $i < $_POST['totalRows']; $i++){
            //Reading all table Row values
            $race_id = isset($_POST['race_id_'.$i]) ? sanitize_text_field($_POST['race_id_'.$i]) : '';
            $race_name = isset($_POST['race_name_'.$i]) ? sanitize_text_field($_POST['race_name_'.$i]) : '';
            $race_nr = isset($_POST['race_nr_'.$i]) ? sanitize_text_field($_POST['race_nr_'.$i]) : '';
            $race_box = isset($_POST['race_box_'.$i]) ? sanitize_text_field($_POST['race_box_'.$i]) : '';
            $race_abstand = isset($_POST['race_abstand_'.$i]) ? sanitize_text_field($_POST['race_abstand_'.$i]) : '';
            $race_gewinn = isset($_POST['race_gewinn_'.$i]) ? sanitize_text_field($_POST['race_gewinn_'.$i]) : '';
            $race_besitzer = isset($_POST['race_besitzer_'.$i]) ? sanitize_text_field($_POST['race_besitzer_'.$i]) : '';
            $race_trainer = isset($_POST['race_trainer_'.$i]) ? sanitize_text_field($_POST['race_trainer_'.$i]) : '';
            $race_reiter = isset($_POST['race_reiter_'.$i]) ? sanitize_text_field($_POST['race_reiter_'.$i]) : '';
            $race_gew = isset($_POST['race_gew_'.$i]) ? sanitize_text_field($_POST['race_gew_'.$i]) : '';
            $race_quote = isset($_POST['race_quote_'.$i]) ? sanitize_text_field($_POST['race_quote_'.$i]) : '';
            
            //update all the table column fields
            update_post_meta($post_id, 'race_id_'.$i, $race_id);
            update_post_meta($post_id, 'race_name_'.$i, $race_name);
            update_post_meta($post_id, 'race_nr_'.$i, $race_nr);
            update_post_meta($post_id, 'race_box_'.$i, $race_box);
            update_post_meta($post_id, 'race_abstand_'.$i, $race_abstand);
            update_post_meta($post_id, 'race_gewinn_'.$i, $race_gewinn);
            update_post_meta($post_id, 'race_besitzer_'.$i, $race_besitzer);
            update_post_meta($post_id, 'race_trainer_'.$i, $race_trainer);
            update_post_meta($post_id, 'race_reiter_'.$i, $race_reiter);
            update_post_meta($post_id, 'race_gew_'.$i, $race_gew);
            update_post_meta($post_id, 'race_quote_'.$i, $race_quote);
        }
        update_post_meta($post_id, 'totalRows', $_POST['totalRows']);
        update_post_meta($post_id, 'fetch_url', $_POST['fetch_url']);
        do_action('wp_race_tables_admin_save',$post_id);
	}
	
	//enqueus scripts and stles on the back end
	public function enqueue_admin_scripts_and_styles(){
		wp_enqueue_style('wp_race_tables_admin_styles', plugin_dir_url(__FILE__) . '/css/wp_racing_admin_styles.css');
		wp_enqueue_script('wp_race_tables_admin_scripts', plugin_dir_url(__FILE__) . '/js/wp_racing_admin.js');
    }
    
    //triggered on activation of the plugin (called only once)
	public function plugin_activate(){
		//flush permalinks
		flush_rewrite_rules();
	}
	
	//trigered on deactivation of the plugin (called only once)
	public function plugin_deactivate(){
		//flush permalinks
		flush_rewrite_rules();
	}
	
}
$wp_simple_locations = new wp_simple_location;

?>