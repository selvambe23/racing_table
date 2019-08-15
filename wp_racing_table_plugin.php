<?php
/*
Plugin Name: WordPress Horse Details
Description: Creates an interfaces to get and manage horce datas.
Version:     1.0.0
*/

class wp_simple_horse{
	
	public function __construct(){

		add_action('init', array($this,'register_horse_data_post_type'));
		add_action('add_meta_boxes', array($this,'add_table_meta_boxes')); //add meta boxes
		add_action('save_post_wp_horse_tables', array($this,'save_horse_tables')); //save location
		add_action('admin_enqueue_scripts', array($this,'enqueue_admin_scripts_and_styles')); //admin scripts and styles
		add_action('wp_enqueue_scripts', array($this,'enqueue_public_scripts_and_styles')); //public scripts and styles
		
		register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
		register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
		
	}
	
	//register the location content type
	public function register_horse_data_post_type(){
		 //Labels for post type
		 $labels = array(
            'name'               => 'Horse Tables',
            'singular_name'      => 'Horse Details',
            'menu_name'          => 'Horse Details',
            'name_admin_bar'     => 'Horse Detail',
            'add_new'            => 'Add New', 
            'add_new_item'       => 'Add New Horse Table',
            'new_item'           => 'New Horse Table', 
            'edit_item'          => 'Edit Horse',
            'view_item'          => 'View Horse',
            'all_items'          => 'All Horse Tables',
            'search_items'       => 'Search Horses',
            'parent_item_colon'  => 'Parent Horse:', 
            'not_found'          => 'No Horses found.', 
            'not_found_in_trash' => 'No Horses found in Trash.',
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
            'rewrite'			=> array('slug' => 'horse_tables', 'with_front' => 'true')
        );
        //register post type
        register_post_type('wp_horse_tables', $args);
	}

	//adding meta boxes for the location content type*/
	public function add_table_meta_boxes(){
		add_meta_box(
			'wp_table_meta_box', //id
			'Horse Information', //name
			array($this,'horse_meta_box_display'), //display function
			'wp_horse_tables', //post type
			'normal', //location
			'default' //priority
        );
        add_meta_box(
			'wp_fetch_form_meta_box', //id
			'Fetch Horse Information From Url', //name
			array($this,'horse_fetch_form_meta_box_display'), //display function
			'wp_horse_tables', //post type
			'normal', //location
			'high' //priority
		);
    }
    
    public function horse_fetch_form_meta_box_display($post){
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
	public function horse_meta_box_display($post){
		//set nonce field
        wp_nonce_field('wp_race_nonce', 'wp_race_nonce_field');

        if($post->filter == 'edit'){ ?>
        <div class="field-container" id="raceStatusTable">
            <?php $totalRows = get_post_meta($post->ID,'totalRows',true);
            for($i=0; $i < $totalRows; $i++){
                $race_id = get_post_meta($post->ID,'race_id_'.$i,true);
                $horse_name = get_post_meta($post->ID,'horse_name_'.$i,true);
                $race_sire = get_post_meta($post->ID,'race_sire_'.$i,true);
                $race_draw = get_post_meta($post->ID,'race_draw_'.$i,true);
                $race_colors = get_post_meta($post->ID,'race_colors_'.$i,true);
                $race_owner = get_post_meta($post->ID,'race_owner_'.$i,true);
                $race_trainer = get_post_meta($post->ID,'race_trainer_'.$i,true);
                $race_jockey = get_post_meta($post->ID,'race_jockey_'.$i,true);
                $race_weight = get_post_meta($post->ID,'race_weight_'.$i,true);
                $race_earnings = get_post_meta($post->ID,'race_earnings_'.$i,true);
                $race_form = get_post_meta($post->ID,'race_form_'.$i,true);
                $race_rating = get_post_meta($post->ID,'race_rating_'.$i,true);
                $race_blink = get_post_meta($post->ID,'race_blink_'.$i,true);
                $race_coupled = get_post_meta($post->ID,'race_coupled_'.$i,true);
                $race_claim = get_post_meta($post->ID,'race_claim_'.$i,true);
                $race_breeders = get_post_meta($post->ID,'race_breeders_'.$i,true);
            ?>
                <div class="field">
                    <label for="race_id_<?php echo $i;?>">N°</label>
                    <input type="number" name="race_id_<?php echo $i;?>" id="race_id_<?php echo $i;?>" value="<?php echo $race_id;?>"/>
                </div>
                <div class="field">
                    <label for="horse_name_<?php echo $i;?>">Horse</label>
                    <input type="text" name="horse_name_<?php echo $i;?>" id="horse_name_<?php echo $i;?>" value="<?php echo $horse_name;?>"/>
                </div>
                <div class="field">
                    <label for="race_sire_<?php echo $i;?>">Sire/Dam</label>
                    <input type="text" name="race_sire_<?php echo $i;?>" id="race_sire_<?php echo $i;?>" value="<?php echo $race_sire;?>"/>
                </div>
                <div class="field">
                    <label for="race_draw_<?php echo $i;?>">Draw</label>
                    <input type="text" name="race_draw_<?php echo $i;?>" id="race_draw_<?php echo $i;?>" value="<?php echo $race_draw;?>"/>
                </div>
                <div class="field">
                    <label for="race_colors_<?php echo $i;?>">Colors</label>
                    <input type="text" name="race_colors_<?php echo $i;?>" id="race_colors_<?php echo $i;?>" value="<?php echo $race_colors;?>"/>
                </div>
                <div class="field">
                    <label for="race_owner_<?php echo $i;?>">Owner</label>
                    <input type="text" name="race_owner_<?php echo $i;?>" id="race_owner_<?php echo $i;?>" value="<?php echo $race_owner;?>"/>
                </div>
                <div class="field">
                    <label for="race_trainer_<?php echo $i;?>">Trainer</label>
                    <input type="text" name="race_trainer_<?php echo $i;?>" id="race_trainer_<?php echo $i;?>" value="<?php echo $race_trainer;?>"/>
                </div>
                <div class="field">
                    <label for="race_jockey_<?php echo $i;?>">Jockey</label>
                    <input type="text" name="race_jockey_<?php echo $i;?>" id="race_jockey_<?php echo $i;?>" value="<?php echo $race_jockey;?>"/>
                </div>
                <div class="field">
                    <label for="race_weight_<?php echo $i;?>">Weight</label>
                    <input type="text" name="race_weight_<?php echo $i;?>" id="race_weight_<?php echo $i;?>" value="<?php echo $race_weight;?>"/>
                </div>
                <div class="field">
                    <label for="race_earnings_<?php echo $i;?>">Earnings</label>
                    <input type="text" name="race_earnings_<?php echo $i;?>" id="race_earnings_<?php echo $i;?>" value="<?php echo $race_earnings;?>"/>
                </div>
                <div class="field">
                    <label for="race_form_<?php echo $i;?>">Form</label>
                    <input type="text" name="race_form_<?php echo $i;?>" id="race_form_<?php echo $i;?>" value="<?php echo $race_form;?>"/>
                </div>
                <div class="field">
                    <label for="race_rating_<?php echo $i;?>">Rating</label>
                    <input type="text" name="race_rating_<?php echo $i;?>" id="race_rating_<?php echo $i;?>" value="<?php echo $race_rating;?>"/>
                </div>
                <div class="field">
                    <label for="race_blink_<?php echo $i;?>">Blink</label>
                    <input type="text" name="race_blink_<?php echo $i;?>" id="race_blink_<?php echo $i;?>" value="<?php echo $race_blink;?>"/>
                </div>
                <div class="field">
                    <label for="race_coupled_<?php echo $i;?>">Coupled</label>
                    <input type="text" name="race_coupled_<?php echo $i;?>" id="race_coupled_<?php echo $i;?>" value="<?php echo $race_coupled;?>"/>
                </div>
                <div class="field">
                    <label for="race_claim_<?php echo $i;?>">Claim</label>
                    <input type="text" name="race_claim_<?php echo $i;?>" id="race_claim_<?php echo $i;?>" value="<?php echo $race_claim;?>"/>
                </div>
                <div class="field">
                    <label for="race_breeders_<?php echo $i;?>">Breeders</label>
                    <input type="text" name="race_breeders_<?php echo $i;?>" id="race_breeders_<?php echo $i;?>" value="<?php echo $race_breeders;?>"/>
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
	public function save_horse_tables($post_id){

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
            $horse_name = isset($_POST['horse_name_'.$i]) ? sanitize_text_field($_POST['horse_name_'.$i]) : '';
            $race_sire = isset($_POST['race_sire_'.$i]) ? sanitize_text_field($_POST['race_sire_'.$i]) : '';
            $race_draw = isset($_POST['race_draw_'.$i]) ? sanitize_text_field($_POST['race_draw_'.$i]) : '';
            $race_colors = isset($_POST['race_colors_'.$i]) ? sanitize_text_field($_POST['race_colors_'.$i]) : '';
            $race_owner = isset($_POST['race_owner_'.$i]) ? sanitize_text_field($_POST['race_owner_'.$i]) : '';
            $race_trainer = isset($_POST['race_trainer_'.$i]) ? sanitize_text_field($_POST['race_trainer_'.$i]) : '';
            $race_jockey = isset($_POST['race_jockey_'.$i]) ? sanitize_text_field($_POST['race_jockey_'.$i]) : '';
            $race_weight = isset($_POST['race_weight_'.$i]) ? sanitize_text_field($_POST['race_weight_'.$i]) : '';
            $race_earnings = isset($_POST['race_earnings_'.$i]) ? sanitize_text_field($_POST['race_earnings_'.$i]) : '';
            $race_form = isset($_POST['race_form_'.$i]) ? sanitize_text_field($_POST['race_form_'.$i]) : '';
            $race_rating = isset($_POST['race_rating_'.$i]) ? sanitize_text_field($_POST['race_rating_'.$i]) : '';
            $race_blink = isset($_POST['race_blink_'.$i]) ? sanitize_text_field($_POST['race_blink_'.$i]) : '';
            $race_coupled = isset($_POST['race_coupled_'.$i]) ? sanitize_text_field($_POST['race_coupled_'.$i]) : '';
            $race_claim = isset($_POST['race_claim_'.$i]) ? sanitize_text_field($_POST['race_claim_'.$i]) : '';
            $race_breeders = isset($_POST['race_breeders_'.$i]) ? sanitize_text_field($_POST['race_breeders_'.$i]) : '';
            
            //update all the table column fields
            update_post_meta($post_id, 'race_id_'.$i, $race_id);
            update_post_meta($post_id, 'horse_name_'.$i, $horse_name);
            update_post_meta($post_id, 'race_sire_'.$i, $race_sire);
            update_post_meta($post_id, 'race_draw_'.$i, $race_draw);
            update_post_meta($post_id, 'race_colors_'.$i, $race_colors);
            update_post_meta($post_id, 'race_owner_'.$i, $race_owner);
            update_post_meta($post_id, 'race_trainer_'.$i, $race_trainer);
            update_post_meta($post_id, 'race_jockey_'.$i, $race_jockey);
            update_post_meta($post_id, 'race_weight_'.$i, $race_weight);
            update_post_meta($post_id, 'race_earnings_'.$i, $race_earnings);
            update_post_meta($post_id, 'race_form_'.$i, $race_form);
            update_post_meta($post_id, 'race_rating_'.$i, $race_rating);
            update_post_meta($post_id, 'race_blink_'.$i, $race_blink);
            update_post_meta($post_id, 'race_coupled_'.$i, $race_coupled);
            update_post_meta($post_id, 'race_claim_'.$i, $race_claim);
            update_post_meta($post_id, 'race_breeders_'.$i, $race_breeders);
        }
        update_post_meta($post_id, 'totalRows', $_POST['totalRows']);
        update_post_meta($post_id, 'fetch_url', $_POST['fetch_url']);
        do_action('wp_horse_tables_admin_save',$post_id);
	}
	
	//enqueus scripts and stles on the back end
	public function enqueue_admin_scripts_and_styles(){
		wp_enqueue_style('wp_horse_tables_admin_styles', plugin_dir_url(__FILE__) . 'css/wp_racing_admin_styles.css');
		wp_enqueue_script('wp_horse_tables_admin_scripts', plugin_dir_url(__FILE__) . 'js/wp_racing_admin.js');
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
$wp_simple_horses = new wp_simple_horse;

?>