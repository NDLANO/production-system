<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_ndla_image') ) :


class acf_field_ndla_image extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct( $settings )
	{
		// vars
		$this->name = 'ndla_image';
		$this->label = __('NDLA Image', 'acf-field-ndla-image');
		$this->category = __('Content','acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			//'preview_size' => 'thumbnail'
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = $settings;

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Media types",'acf-ndla'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][media_types]',
			'value'		=>	$field['media_types'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'image' => __('Image'),
				'all' => __('All types'),
			)
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
        global $bc_accounts;

        wp_enqueue_media();

        $fieldHasImg = false;
        $fieldHasVideo = false;
        $mediaUrl = "";
        $mediaId = 0;
        $mediaType = "";
        $supportedMediaTypes = $field['media_types'];


        // See if there's a media id already saved as post meta
        if ($field['value'] != '') {
            $value = $field['value'];
            if (substr($value, 0, 6) === 'video-') {
                $mediaType = 'video';
                $mediaId = substr($value, 6);
                $fieldHasVideo = true;
                $fieldHasImg = false;
                $accountId = $bc_accounts->get_account_id();
                $shortcode = sprintf("[bc_video video_id=\"%s\" account_id=\"%s\" player_id=\"default\"]", $mediaId, $accountId);
                $mediaUrl = do_shortcode($shortcode);

            } else {
                $mediaType = 'image';
                $mediaId = $value;
                $fieldHasImg = true;
                $fieldHasVideo = false;

                $api      = new NDLA\ImageAPIGateway();
                $response = json_decode($api->getDetails( $mediaId, true ), true);
                $mediaUrl = $response['imageUrl'];
            }

        }

        $hasValue = $fieldHasImg || $fieldHasVideo;

        ?>

        <div id="acf-field-ndla_image" class="<?= $fieldHasImg ? 'active' : '' ?> clearfix">
            <input class="acf-ndla_image-value" type="hidden" name="<?php echo $field['name'] ?>" value="<?= $mediaId ?>"/>
            <!-- Your image container, which can be manipulated with js -->
            <div class="ndla-image-container acf-image-uploader has-image">
                <img src="<?= $fieldHasImg ? $mediaUrl : '' ?>" class="ndla-image" alt="" style="max-width:320px;" />
                <div class="ndla-video <?= $fieldHasVideo ? '' : 'hidden' ?>">
                    <?= $fieldHasImg ? '' : $mediaUrl ?>
                </div>
            </div>
            <div class="no-image <?= $hasValue ? 'hidden' : '' ?>">
                <div><?php _e('no image selected', 'acf-ndla-image'); ?>
                    <a name="<?php _e('NDLA Image', 'acf-ndla-image') ?>" href="#" class="add-ndla-image button"><?php _e('Choose image', 'acf-ndla-image') ?></a>
                    <?php if ($supportedMediaTypes === 'all'): ?>
                        <a href="#" id="brightcove-add-media" class="button brightcove-add-media"><?php _e('Choose video', 'acf-ndla-image') ?></a>
                    <?php endif ?>
                </div>
            </div>
            <div class="delete-ndla-image <?= $hasValue ? '' : 'hidden' ?>">
                <a class="delete-ndla-image button" href="#"><?php _e("Remove") ?></a>
                <a class="edit-ndla-image button disabled" href="#"><?php _e("Edit") ?></a>
            </div>

            <!-- Your add & remove image links -->
            <p class="hide-if-no-js">
            </p>

        </div>
		<?php
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
		
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script( 'acf-input-ndla_image', "{$url}assets/js/ndla_image_acf4.js" );
		wp_enqueue_script('acf-input-ndla_image');

		// register & include CSS
		wp_register_style( 'acf-input-ndla_image', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-ndla_image');
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  load_value()
	*
		*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
		return $field;
	}

}


// initialize
new acf_field_ndla_image( $this->settings );

/*
 * AJAX endpoints
 */
add_action( 'wp_ajax_acf-ndla-image-video-shortcode', 'acf_ndla_video_shortcode');

function acf_ndla_video_shortcode()
{
    $accountId = $_GET['accountId'];
    $mediaId = $_GET['mediaId'];

    $shortcode = sprintf("[bc_video video_id=\"%s\" account_id=\"%s\" player_id=\"default\"]", $mediaId, $accountId);
    $mediaUrl = do_shortcode($shortcode);

    echo $mediaUrl;
    wp_die();
}


// class_exists check
endif;

?>