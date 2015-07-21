<?php
/*
Plugin Name: Phittle Wordpress Paywall plugin
Plugin URI: http://www.phittle.com
Description: Allows content creators to earn revenue for their posts. Works with PayPal, Credit Cards, Bitcoin, Apple pay and more
Author: LioniX Evolve
Author URI: http://www.phittle.com
Version: 0.1
License: GPL v2 or later
*/

$prefix = 'phittle_';

$fields = array(
    array( // Radio group
        'label' => 'Access Level', // <label>
        'desc' => 'This is a global description.', // description
        'id' => $prefix . 'article_access_level', // field id and name
        'type' => 'radio', // type of field
        'options' => array( // array of options
            'one' => array( // array key needs to be the same as the option value
                'label' => 'Free', // text displayed as the option
                'value' => 'free' // value stored for the option
            ),
            'two' => array(
                'label' => 'Registered',
                'value' => 'registered'
            ),
            'three' => array(
                'label' => 'Paid',
                'value' => 'paid'
            )
        )
    ),
    array( // Select box
        'label' => 'Type', // <label>
        'desc' => 'Select the type of article this is.', // description
        'id' => $prefix . 'article_type', // field id and name
        'type' => 'select1', // type of field
        'options' => array( // array of options
            'one' => array( // array key needs to be the same as the option value
                'label' => 'Article', // text displayed as the option
                'value' => 'article' // value stored for the option
            ),
            'two' => array(
                'label' => 'File',
                'value' => 'file'
            ),
            'three' => array(
                'label' => 'Media',
                'value' => 'media'
            )
        )
    ),
    array( // Select box
        'label' => 'Genre', // <label>
        'desc' => 'What genre does this article fall into?', // description
        'id' => $prefix . 'article_genre', // field id and name
        'type' => 'select2', // type of field
        'options' => array( // array of options
            'one' => array( // array key needs to be the same as the option value
                'label' => 'Sports', // text displayed as the option
                'value' => 'sports' // value stored for the option
            ),
            'two' => array(
                'label' => 'Medicine',
                'value' => 'medicine'
            ),
            'three' => array(
                'label' => 'Technology',
                'value' => 'technology'
            ),
            'four' => array(
                'label' => 'Religious',
                'value' => 'religious'
            ),
            'five' => array(
                'label' => 'Society',
                'value' => 'society'
            ),
            'six' => array(
                'label' => 'Nature',
                'value' => 'nature'
            )

        )
    ),
    array( // Text Input
        'label' => 'Credits', // <label>
        'desc' => 'The amount of credits you want users to pay in order to access this post.', // description
        'id' => $prefix . 'article_credit_amount', // field id and name
        'type' => 'number' // type of field
    ),
    array( // Text Input
        'label' => 'Shares', // <label>
        'desc' => 'The amount of times you want users to share this post with other users.', // description
        'id' => $prefix . 'article_shares', // field id and name
        'type' => 'number' // type of field
    ),
    array( // Select box
        'label' => 'Status', // <label>
        'desc' => 'Whether this article is enabled or disabled.', // description
        'id' => $prefix . 'article_status', // field id and name
        'type' => 'select1', // type of field
        'options' => array( // array of options
            'one' => array( // array key needs to be the same as the option value
                'label' => 'Enabled', // text displayed as the option
                'value' => 'enabled' // value stored for the option
            ),
            'two' => array(
                'label' => 'Disabled',
                'value' => 'disabled'
            )
        )
    ),
    array( // Text Input
        'label' => 'Phittle ID', // <label>
        'desc' => 'Your unique Phittle Post ID (PPI) for this post.', // description
        'id' => $prefix . 'article_id', // field id and name
        'type' => 'text2' // type of field
    )
);

/**
 * Instantiate the class with all variables to create a meta box
 * var $id string meta box id
 * var $title string title
 * var $fields array fields
 * var $page string|array post type to add meta box to
 * var $js bool including javascript or not
 */
$sample_box = new custom_add_meta_box('sample_box', 'Phittle Article Settings', $fields, 'post', true);


// metaboxes directory constant
define('CUSTOM_PHITTLE_DIR', plugin_dir_url(__FILE__) . 'phittle');

/**
 * receives data about a form field and spits out the proper html
 *
 * @param    array $field array with various bits of information about the field
 * @param    string|int|bool|array $meta the saved data for this field
 * @param    array $repeatable if is this for a repeatable field, contains parant id and the current integar
 *
 * @return    string                                    html for the field
 */
function custom_meta_box_field($field, $meta = null, $repeatable = null)
{
    if (!($field || is_array($field)))
        return;

    // get field data
    $type = isset($field['type']) ? $field['type'] : null;
    $label = isset($field['label']) ? $field['label'] : null;
    $desc = isset($field['desc']) ? '<span class="description">' . $field['desc'] . '</span>' : null;
    $place = isset($field['place']) ? $field['place'] : null;
    $size = isset($field['size']) ? $field['size'] : null;
    $post_type = isset($field['post_type']) ? $field['post_type'] : null;
    $options = isset($field['options']) ? $field['options'] : null;
    $settings = isset($field['settings']) ? $field['settings'] : null;
    $repeatable_fields = isset($field['repeatable_fields']) ? $field['repeatable_fields'] : null;

    // the id and name for each field
    $id = $name = isset($field['id']) ? $field['id'] : null;
    if ($repeatable) {
        $name = $repeatable[0] . '[' . $repeatable[1] . '][' . $id . ']';
        $id = $repeatable[0] . '_' . $repeatable[1] . '_' . $id;
    }
    switch ($type) {
        // basic
        case 'text1':
        case 'tel':
        case 'email':
        default:
            echo '<input type="' . $type . '" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($meta) . '" class="regular-text" size="30" />
					<br />' . $desc;
            break;
        case 'text2':
            echo '<input type="' . $type . '" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($meta) . '" class="regular-text" size="30" readonly />
					<br />' . $desc;
            break;
        case 'url':
            echo '<input type="' . $type . '" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_url($meta) . '" class="regular-text" size="30" />
					<br />' . $desc;
            break;
        case 'number':
            echo '<input min="0" type="' . $type . '" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . intval($meta) . '" class="regular-text" size="30" />
					<br />' . $desc;
            break;
        // textarea
        case 'textarea':
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" cols="60" rows="4">' . esc_textarea($meta) . '</textarea>
					<br />' . $desc;
            break;
        // editor
        case 'editor':
            echo wp_editor($meta, $id, $settings) . '<br />' . $desc;
            break;
        // checkbox
        case 'checkbox':
            echo '<input type="checkbox" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" ' . checked($meta, true, false) . ' value="1" />
					<label for="' . esc_attr($id) . '">' . $desc . '</label>';
            break;
        // select, chosen
        case 'select1':
        case 'chosen':
            echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '"', $type == 'chosen' ? ' class="chosen"' : '', isset($multiple) && $multiple == true ? ' multiple="multiple"' : '', '>'; // Select One
            foreach ($options as $option)
                echo '<option' . selected($meta, $option['value'], false) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
            echo '</select><br />' . $desc;
            break;
        // select, chosen
        case 'select2':
            echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '"', $type == 'chosen' ? ' class="chosen"' : '', isset($multiple) && $multiple == true ? ' multiple="multiple"' : '', '>
					<option value="">Select One</option>'; // Select One
            foreach ($options as $option)
                echo '<option' . selected($meta, $option['value'], false) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
            echo '</select><br />' . $desc;
            break;
        // radio
        case 'radio':
            echo '<ul class="meta_box_items">';
            foreach ($options as $option)
                echo '<li><input type="radio" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked($meta, $option['value'], false) . ' />
						<label for="' . esc_attr($id) . '-' . $option['value'] . '">' . $option['label'] . '</label></li>';
            echo '</ul>' . $desc;
            break;
        // checkbox_group
        case 'checkbox_group':
            echo '<ul class="meta_box_items">';
            foreach ($options as $option)
                echo '<li><input type="checkbox" value="' . $option['value'] . '" name="' . esc_attr($name) . '[]" id="' . esc_attr($id) . '-' . $option['value'] . '"', is_array($meta) && in_array($option['value'], $meta) ? ' checked="checked"' : '', ' />
						<label for="' . esc_attr($id) . '-' . $option['value'] . '">' . $option['label'] . '</label></li>';
            echo '</ul>' . $desc;
            break;
        // color
        case 'color':
            $meta = $meta ? $meta : '#';
            echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . $meta . '" size="10" />
				<br />' . $desc;
            echo '<div id="colorpicker-' . esc_attr($id) . '"></div>
				<script type="text/javascript">
				jQuery(function(jQuery) {
					jQuery("#colorpicker-' . esc_attr($id) . '").hide();
					jQuery("#colorpicker-' . esc_attr($id) . '").farbtastic("#' . esc_attr($id) . '");
					jQuery("#' . esc_attr($id) . '").bind("blur", function() { jQuery("#colorpicker-' . esc_attr($id) . '").slideToggle(); } );
					jQuery("#' . esc_attr($id) . '").bind("focus", function() { jQuery("#colorpicker-' . esc_attr($id) . '").slideToggle(); } );
				});
				</script>';
            break;
        // post_select, post_chosen
        case 'post_select':
        case 'post_list':
        case 'post_chosen':
            echo '<select data-placeholder="Select One" name="' . esc_attr($name) . '[]" id="' . esc_attr($id) . '"', $type == 'post_chosen' ? ' class="chosen"' : '', isset($multiple) && $multiple == true ? ' multiple="multiple"' : '', '>
					<option value=""></option>'; // Select One
            $posts = get_posts(array('post_type' => $post_type, 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC'));
            foreach ($posts as $item)
                echo '<option value="' . $item->ID . '"' . selected(is_array($meta) && in_array($item->ID, $meta), true, false) . '>' . $item->post_title . '</option>';
            $post_type_object = get_post_type_object($post_type);
            echo '</select> &nbsp;<span class="description"><a href="' . admin_url('edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label) . '</a></span><br />' . $desc;
            break;
        // post_checkboxes
        case 'post_checkboxes':
            $posts = get_posts(array('post_type' => $post_type, 'posts_per_page' => -1));
            echo '<ul class="meta_box_items">';
            foreach ($posts as $item)
                echo '<li><input type="checkbox" value="' . $item->ID . '" name="' . esc_attr($name) . '[]" id="' . esc_attr($id) . '-' . $item->ID . '"', is_array($meta) && in_array($item->ID, $meta) ? ' checked="checked"' : '', ' />
						<label for="' . esc_attr($id) . '-' . $item->ID . '">' . $item->post_title . '</label></li>';
            $post_type_object = get_post_type_object($post_type);
            echo '</ul> ' . $desc, ' &nbsp;<span class="description"><a href="' . admin_url('edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label) . '</a></span>';
            break;
        // post_drop_sort
        case 'post_drop_sort':
            //areas
            $post_type_object = get_post_type_object($post_type);
            echo '<p>' . $desc . ' &nbsp;<span class="description"><a href="' . admin_url('edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label) . '</a></span></p><div class="post_drop_sort_areas">';
            foreach ($areas as $area) {
                echo '<ul id="area-' . $area['id'] . '" class="sort_list">
						<li class="post_drop_sort_area_name">' . $area['label'] . '</li>';
                if (is_array($meta)) {
                    $items = explode(',', $meta[$area['id']]);
                    foreach ($items as $item) {
                        $output = $display == 'thumbnail' ? get_the_post_thumbnail($item, array(204, 30)) : get_the_title($item);
                        echo '<li id="' . $item . '">' . $output . '</li>';
                    }
                }
                echo '</ul>
					<input type="hidden" name="' . esc_attr($name) . '[' . $area['id'] . ']"
					class="store-area-' . $area['id'] . '" 
					value="', $meta ? $meta[$area['id']] : '', '" />';
            }
            echo '</div>';
            // source
            $exclude = null;
            if (!empty($meta)) {
                $exclude = implode(',', $meta); // because each ID is in a unique key
                $exclude = explode(',', $exclude); // put all the ID's back into a single array
            }
            $posts = get_posts(array('post_type' => $post_type, 'posts_per_page' => -1, 'post__not_in' => $exclude));
            echo '<ul class="post_drop_sort_source sort_list">
					<li class="post_drop_sort_area_name">Available ' . $label . '</li>';
            foreach ($posts as $item) {
                $output = $display == 'thumbnail' ? get_the_post_thumbnail($item->ID, array(204, 30)) : get_the_title($item->ID);
                echo '<li id="' . $item->ID . '">' . $output . '</li>';
            }
            echo '</ul>';
            break;
        // tax_select
        case 'tax_select':
            echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '">
					<option value="">Select One</option>'; // Select One
            $terms = get_terms($id, 'get=all');
            $post_terms = wp_get_object_terms(get_the_ID(), $id);
            $taxonomy = get_taxonomy($id);
            $selected = $post_terms ? $taxonomy->hierarchical ? $post_terms[0]->term_id : $post_terms[0]->slug : null;
            foreach ($terms as $term) {
                $term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
                echo '<option value="' . $term_value . '"' . selected($selected, $term_value, false) . '>' . $term->name . '</option>';
            }
            echo '</select> &nbsp;<span class="description"><a href="' . get_bloginfo('url') . '/wp-admin/edit-tags.php?taxonomy=' . $id . '">Manage ' . $taxonomy->label . '</a></span>
				<br />' . $desc;
            break;
        // tax_checkboxes
        case 'tax_checkboxes':
            $terms = get_terms($id, 'get=all');
            $post_terms = wp_get_object_terms(get_the_ID(), $id);
            $taxonomy = get_taxonomy($id);
            $checked = $post_terms ? $taxonomy->hierarchical ? $post_terms[0]->term_id : $post_terms[0]->slug : null;
            foreach ($terms as $term) {
                $term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
                echo '<input type="checkbox" value="' . $term_value . '" name="' . $id . '[]" id="term-' . $term_value . '"' . checked($checked, $term_value, false) . ' /> <label for="term-' . $term_value . '">' . $term->name . '</label><br />';
            }
            echo '<span class="description">' . $field['desc'] . ' <a href="' . get_bloginfo('url') . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $page . '">Manage ' . $taxonomy->label . '</a></span>';
            break;
        // date
        case 'date':
            echo '<input type="text" class="datepicker" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . $meta . '" size="30" />
					<br />' . $desc;
            break;
        // slider
        case 'slider':
            $value = $meta != '' ? intval($meta) : '1';
            echo '<div id="' . esc_attr($id) . '-slider"></div>
					<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . $value . '" size="5" />
					<br />' . $desc;
            break;
        // image
        case 'image':
            $image = CUSTOM_PHITTLE_DIR . '/images/image.png';
            echo '<div class="meta_box_image"><span class="meta_box_default_image" style="display:none">' . $image . '</span>';
            if ($meta) {
                $image = wp_get_attachment_image_src(intval($meta), 'medium');
                $image = $image[0];
            }
            echo '<input name="' . esc_attr($name) . '" type="hidden" class="meta_box_upload_image" value="' . intval($meta) . '" />
						<img src="' . esc_attr($image) . '" class="meta_box_preview_image" alt="" />
							<a href="#" class="meta_box_upload_image_button button" rel="' . get_the_ID() . '">Choose Image</a>
							<small>&nbsp;<a href="#" class="meta_box_clear_image_button">Remove Image</a></small></div>
							<br clear="all" />' . $desc;
            break;
        // file
        case 'file':
            $iconClass = 'meta_box_file';
            if ($meta) $iconClass .= ' checked';
            echo '<div class="meta_box_file_stuff"><input name="' . esc_attr($name) . '" type="hidden" class="meta_box_upload_file" value="' . esc_url($meta) . '" />
						<span class="' . $iconClass . '"></span>
						<span class="meta_box_filename">' . esc_url($meta) . '</span>
							<a href="#" class="meta_box_upload_image_button button" rel="' . get_the_ID() . '">Choose File</a>
							<small>&nbsp;<a href="#" class="meta_box_clear_file_button">Remove File</a></small></div>
							<br clear="all" />' . $desc;
            break;
        // repeatable
        case 'repeatable':
            echo '<table id="' . esc_attr($id) . '-repeatable" class="meta_box_repeatable" cellspacing="0">
				<thead>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="#"></a></th>
					</tr>
				</thead>
				<tbody>';
            $i = 0;
            // create an empty array
            if ($meta == '' || $meta == array()) {
                $keys = wp_list_pluck($repeatable_fields, 'id');
                $meta = array(array_fill_keys($keys, null));
            }
            $meta = array_values($meta);
            foreach ($meta as $row) {
                echo '<tr>
						<td><span class="sort hndle"></span></td><td>';
                foreach ($repeatable_fields as $repeatable_field) {
                    if (!array_key_exists($repeatable_field['id'], $meta[$i]))
                        $meta[$i][$repeatable_field['id']] = null;
                    echo '<label>' . $repeatable_field['label'] . '</label><p>';
                    echo custom_meta_box_field($repeatable_field, $meta[$i][$repeatable_field['id']], array($id, $i));
                    echo '</p>';
                } // end each field
                echo '</td><td><a class="meta_box_repeatable_remove" href="#"></a></td></tr>';
                $i++;
            } // end each row
            echo '</tbody>';
            echo '
				<tfoot>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="#"></a></th>
					</tr>
				</tfoot>';
            echo '</table>
				' . $desc;
            break;
    } //end switch

}

/**
 * Finds any item in any level of an array
 *
 * @param    string $needle field type to look for
 * @param    array $haystack an array to search the type in
 *
 * @return    bool                whether or not the type is in the provided array
 */
function meta_box_find_field_type($needle, $haystack)
{
    foreach ($haystack as $h)
        if (isset($h['type']) && $h['type'] == 'repeatable')
            return meta_box_find_field_type($needle, $h['repeatable_fields']);
        elseif ((isset($h['type']) && $h['type'] == $needle) || (isset($h['repeatable_type']) && $h['repeatable_type'] == $needle))
            return true;
    return false;
}

/**
 * Find repeatable
 *
 * This function does almost the same exact thing that the above function
 * does, except we're exclusively looking for the repeatable field. The
 * reason is that we need a way to look for other fields nested within a
 * repeatable, but also need a way to stop at repeatable being true.
 * Hopefully I'll find a better way to do this later.
 *
 * @param    string $needle field type to look for
 * @param    array $haystack an array to search the type in
 *
 * @return    bool                whether or not the type is in the provided array
 */
function meta_box_find_repeatable($needle = 'repeatable', $haystack)
{
    foreach ($haystack as $h)
        if (isset($h['type']) && $h['type'] == $needle)
            return true;
    return false;
}

/**
 * sanitize boolean inputs
 */
function meta_box_santitize_boolean($string)
{
    if (!isset($string) || $string != 1 || $string != true)
        return false;
    else
        return true;
}

/**
 * outputs properly sanitized data
 *
 * @param    string $string the string to run through a validation function
 * @param    string $function the validation function
 *
 * @return                        a validated string
 */
function meta_box_sanitize($string, $function = 'sanitize_text_field')
{
    switch ($function) {
        case 'intval':
            return intval($string);
        case 'absint':
            return absint($string);
        case 'wp_kses_post':
            return wp_kses_post($string);
        case 'wp_kses_data':
            return wp_kses_data($string);
        case 'esc_url_raw':
            return esc_url_raw($string);
        case 'is_email':
            return is_email($string);
        case 'sanitize_title':
            return sanitize_title($string);
        case 'santitize_boolean':
            return santitize_boolean($string);
        case 'sanitize_text_field':
        default:
            return sanitize_text_field($string);
    }
}

/**
 * Map a multideminsional array
 *
 * @param    string $func the function to map
 * @param    array $meta a multidimensional array
 * @param    array $sanitizer a matching multidimensional array of sanitizers
 *
 * @return    array                new array, fully mapped with the provided arrays
 */
function meta_box_array_map_r($func, $meta, $sanitizer)
{

    $newMeta = array();
    $meta = array_values($meta);

    foreach ($meta as $key => $array) {
        if ($array == '')
            continue;
        /**
         * some values are stored as array, we only want multidimensional ones
         */
        if (!is_array($array)) {
            return array_map($func, $meta, (array)$sanitizer);
            break;
        }
        /**
         * the sanitizer will have all of the fields, but the item may only
         * have valeus for a few, remove the ones we don't have from the santizer
         */
        $keys = array_keys($array);
        $newSanitizer = $sanitizer;
        if (is_array($sanitizer)) {
            foreach ($newSanitizer as $sanitizerKey => $value)
                if (!in_array($sanitizerKey, $keys))
                    unset($newSanitizer[$sanitizerKey]);
        }
        /**
         * run the function as deep as the array goes
         */
        foreach ($array as $arrayKey => $arrayValue)
            if (is_array($arrayValue))
                $array[$arrayKey] = meta_box_array_map_r($func, $arrayValue, $newSanitizer[$arrayKey]);

        $array = array_map($func, $array, $newSanitizer);
        $newMeta[$key] = array_combine($keys, array_values($array));
    }
    return $newMeta;
}

/**
 * takes in a few peices of data and creates a custom meta box
 *
 * @param    string $id meta box id
 * @param    string $title title
 * @param    array $fields array of each field the box should include
 * @param    string|array $page post type to add meta box to
 */
class Custom_Add_Meta_Box
{

    var $id;
    var $title;
    var $fields;
    var $page;

    public function __construct($id, $title, $fields, $page)
    {
        $this->id = $id;
        $this->title = $title;
        $this->fields = $fields;
        $this->page = $page;

        if (!is_array($this->page))
            $this->page = array($this->page);

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('admin_head', array($this, 'admin_head'));
        add_action('add_meta_boxes', array($this, 'add_box'));
// 		add_action( 'save_post',  array( $this, 'save_box' ));
// 		add_action( 'publish_post', 'email_friends' );
        //dai
        add_action('save_post', array($this, 'save_article'));

    }

    /**
     * enqueue necessary scripts and styles
     */
    function admin_enqueue_scripts()
    {
        global $pagenow;
        if (in_array($pagenow, array('post-new.php', 'post.php')) && in_array(get_post_type(), $this->page)) {
            // js
            $deps = array('jquery');
            if (meta_box_find_field_type('date', $this->fields))
                $deps[] = 'jquery-ui-datepicker';
            if (meta_box_find_field_type('slider', $this->fields))
                $deps[] = 'jquery-ui-slider';
            if (meta_box_find_field_type('color', $this->fields))
                $deps[] = 'farbtastic';
            if (in_array(true, array(
                meta_box_find_field_type('chosen', $this->fields),
                meta_box_find_field_type('post_chosen', $this->fields)
            ))) {
                wp_register_script('chosen', CUSTOM_PHITTLE_DIR . '/js/chosen.js', array('jquery'));
                $deps[] = 'chosen';
                wp_enqueue_style('chosen', CUSTOM_PHITTLE_DIR . '/css/chosen.css');
            }
            if (in_array(true, array(
                meta_box_find_field_type('date', $this->fields),
                meta_box_find_field_type('slider', $this->fields),
                meta_box_find_field_type('color', $this->fields),
                meta_box_find_field_type('chosen', $this->fields),
                meta_box_find_field_type('post_chosen', $this->fields),
                meta_box_find_repeatable('repeatable', $this->fields),
                meta_box_find_field_type('image', $this->fields),
                meta_box_find_field_type('file', $this->fields)
            )))
                wp_enqueue_script('meta_box', CUSTOM_PHITTLE_DIR . '/js/scripts.js', $deps);

            // css
            $deps = array();
            wp_register_style('jqueryui', CUSTOM_PHITTLE_DIR . '/css/jqueryui.css');
            if (meta_box_find_field_type('date', $this->fields) || meta_box_find_field_type('slider', $this->fields))
                $deps[] = 'jqueryui';
            if (meta_box_find_field_type('color', $this->fields))
                $deps[] = 'farbtastic';
            wp_enqueue_style('meta_box', CUSTOM_PHITTLE_DIR . '/css/meta_box.css', $deps);
        }
    }

    /**
     * adds scripts to the head for special fields with extra js requirements
     */
    function admin_head()
    {
        if (in_array(get_post_type(), $this->page) && (meta_box_find_field_type('date', $this->fields) || meta_box_find_field_type('slider', $this->fields))) {

            echo '<script type="text/javascript">
						jQuery(function( $) {';

            foreach ($this->fields as $field) {
                switch ($field['type']) {
                    // date
                    case 'date' :
                        echo '$("#' . $field['id'] . '").datepicker({
								dateFormat: \'yy-mm-dd\'
							});';
                        break;
                    // slider
                    case 'slider' :
                        $value = get_post_meta(get_the_ID(), $field['id'], true);
                        if ($value == '')
                            $value = $field['min'];
                        echo '
							$( "#' . $field['id'] . '-slider" ).slider({
								value: ' . $value . ',
								min: ' . $field['min'] . ',
								max: ' . $field['max'] . ',
								step: ' . $field['step'] . ',
								slide: function( event, ui ) {
									$( "#' . $field['id'] . '" ).val( ui.value );
								}
							});';
                        break;
                }
            }

            echo '});
				</script>';

        }
    }

    /**
     * adds the meta box for every post type in $page
     */
    function add_box()
    {
        foreach ($this->page as $page) {
            add_meta_box($this->id, $this->title, array($this, 'meta_box_callback'), $page, 'normal', 'high');
        }
    }

    /**
     * outputs the meta box
     */
    function meta_box_callback()
    {
        // Use nonce for verification
        wp_nonce_field('custom_meta_box_nonce_action', 'custom_meta_box_nonce_field');

        // Begin the field table and loop
        echo '<table class="form-table meta_box">';
        foreach ($this->fields as $field) {
            if ($field['type'] == 'section') {
                echo '<tr>
						<td colspan="2">
							<h2>' . $field['label'] . '</h2>
						</td>
					</tr>';
            } else {
                echo '<tr>
						<th style="width:20%"><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
						<td>';

                $meta = get_post_meta(get_the_ID(), $field['id'], true);
                echo custom_meta_box_field($field, $meta);

                echo '<td>
					</tr>';
            }
        } // end foreach
        echo '</table>'; // end table
    }


    #Función save_article
    function save_article($post_id)
    {
        //--------------- Harold CODE--------------------------------------------------------------------------------------------------------------
        $post_type = get_post_type();
        foreach (get_the_category() as $category) {
//             error_log($category->cat_name);
        }
        // verify nonce
        if (!isset($_POST['custom_meta_box_nonce_field']))
            return $post_id;
        if (!(in_array($post_type, $this->page) || wp_verify_nonce($_POST['custom_meta_box_nonce_field'], 'custom_meta_box_nonce_action')))
            return $post_id;
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // check permissions
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        // loop through fields and save the data
        foreach ($this->fields as $field) {
            if ($field['type'] == 'section') {
                $sanitizer = null;
                continue;
            }
            if (in_array($field['type'], array('tax_select', 'tax_checkboxes'))) {
                // save taxonomies
                if (isset($_POST[$field['id']])) {
                    $term = $_POST[$field['id']];
                    wp_set_object_terms($post_id, $term, $field['id']);
                }
            } else {
                // save the rest
                $new = false;
                $old = get_post_meta($post_id, $field['id'], true);
                if (isset($_POST[$field['id']]))
                    $new = $_POST[$field['id']];
                if (isset($new) && '' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                } elseif (isset($new) && $new != $old) {
                    $sanitizer = isset($field['sanitizer']) ? $field['sanitizer'] : 'sanitize_text_field';
                    if (is_array($new))
                        $new = meta_box_array_map_r('meta_box_sanitize', $new, $sanitizer);
                    else
                        $new = meta_box_sanitize($new, $sanitizer);
                    update_post_meta($post_id, $field['id'], $new);
                }
            }
        } // end foreach
        //--------------- END Harold CODE --------------------------------------------------------------------------------------------------------------

        #Article Data Definition
        $article_id = get_post_meta($post_id, 'phittle_article_id', true);
        $options = get_option( 'phittle_settings' );
        $publisherId  = $options['publisherId'];
        $publisherSystemId = $post_id;
        $publisherSystemName = "Wordpress";
        $title = get_the_title($post_id);
        $url = get_permalink($post_id);
        $accessLevel = get_post_meta($post_id, 'phittle_article_access_level', true);
        $creditsAmount = get_post_meta($post_id, 'phittle_article_credit_amount', true);
        $shares = get_post_meta($post_id, 'phittle_article_shares', true);
        $type = get_post_meta($post_id, 'phittle_article_type', true);
        $genre = get_post_meta($post_id, 'phittle_article_genre', true);
        $status = get_post_meta($post_id, 'phittle_article_status', true);
        $article_img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $img = $article_img[0];
        $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        $excerpt = substr($content, 0, 315) . "...";
        $categories_list = get_the_category_list(__(', '));
        #END - DEFINICIÓN DE DATOS DE ARTÍCULO
        $options = get_option( 'phittle_settings' );
        $phittleDomain = $options['apiEndpoint'];
        $api_url = $phittleDomain;

        if (get_post_status($post_id) == "publish") {

            #GET TOKEN
            $json_url_source = $api_url."/api/v1/getToken";//Api url
            //Parametros del post
            $postfields = array(
                'username' => $options['publisherLogin'],
                'password' => $options['publisherSecret']
            );
            //Conevertirmos el arreglo a json
            $json_postfields = json_encode($postfields);
            //Definición de los headers
            $headers_token = array(
                'Content-type: application/json',
                'Accept: application/json',
            );
            //Definición de las opciones de curl
            $curl_options = array(
                CURLOPT_URL => $json_url_source,//url del api
                CURLOPT_HTTPHEADER => $headers_token,//headers
                CURLOPT_RETURNTRANSFER => true,//Devolver resultado de la transferencia
                CURLOPT_CUSTOMREQUEST => "POST",//Method POST
                CURLOPT_POSTFIELDS => $json_postfields//Parametros que se le envian al post
            );
            //Inicializamos curl
            $ch = curl_init();
            //Le pasamos las opciones
            curl_setopt_array($ch, $curl_options);
            //Ejecutamos curl
            $response = curl_exec($ch);
            //Desencodeamos la respuesta que viene en json
            $api_result = json_decode($response, true);
            //Obtenemos el token
            $token = $api_result['token'];
            //Cerramos curl
            curl_close($ch);
            #END - GET TOKEN DEL API

            #Si el articulo en wordpress no tiene phittle id (article_id)
            if ($article_id == "") {
                #CREAR ARTÍCULO EN PHITTLE
                $url_article = $api_url."/api/v1/articles";//Api url
                //Parametros del post
                $article_postfields = array("article" => array(
                    'publisherId' => $publisherId,
                    'publisherSystemId' => $publisherSystemId,
                    'publisherSystemName' => $publisherSystemName,
                    'title' => $title,
                    'url' => $url,
                    'accessLevel' => $accessLevel,
                    'credits' => $creditsAmount,
                    'shares' => $shares,
                    'type' => $type,
                    'genre' => $genre,
                    'status' => $status,
                    'img' => $img,
                    'excerpt' => $excerpt
                ));
                //Conevertirmos el arreglo a json
                $article_postfields_json = json_encode($article_postfields);
                //Definición de los headers
                $headers_article = array(
                    'Content-type: application/json',
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json',
                );
                //Definición de las opciones de curl
                $curl_article_options = array(
                    CURLOPT_URL => $url_article,//url del api
                    CURLOPT_HTTPHEADER => $headers_article,//headers
                    CURLOPT_RETURNTRANSFER => true,//Devolver resultado de la transferencia
                    CURLOPT_CUSTOMREQUEST => "POST",//Method POST
                    CURLOPT_POSTFIELDS => $article_postfields_json,//Parametros que se le envian al post
                    CURLOPT_HEADER => true,//Incluir el header en el output.
                    CURLINFO_HEADER_OUT => true
                );
                //Inicializamos curl
                $ch_article = curl_init();
                //Le pasamos las opciones
                curl_setopt_array($ch_article, $curl_article_options);
                //Ejecutamos curl
                $article_response = curl_exec($ch_article);
                //Obtenemos el código http de respuesta de la respuesta del curl
                $http_code = curl_getinfo($ch_article, CURLINFO_HTTP_CODE);
                //201 Created
                if ($http_code == "201") {
                    //Si hay location url en la respuesta
                    if (preg_match('#Location: (.*)#', $article_response, $r)) {
                        $location_url = trim($r[1]);
                        //Obtenemos el id de la url
                        $article_id_phittle = substr(strrchr(rtrim($location_url, '/'), '/'), 1);
                        //Actualizamos el campo phittle_article_id en el wordpress con el valor del phittle_id
                        update_post_meta($post_id, 'phittle_article_id', $article_id_phittle);
                    }
                }
                //Cerramos curl
                curl_close($ch_article);
                #END - CREAR ARTÍCULO EN PHITTLE
            }#END - Si el articulo en wordpress no tiene phittle id (article_id)
            else {
                #UPDATE ARTÍCULO EN PHITTLE
                $url_article = $api_url."/api/v1/articles/" . $article_id;//Api url
                //Parametros del post
                $article_postfields = array("article" => array(
                    'publisherId' => $publisherId,
                    'publisherSystemId' => $publisherSystemId,
                    'publisherSystemName' => $publisherSystemName,
                    'title' => $title,
                    'url' => $url,
                    'accessLevel' => $accessLevel,
                    'credits' => $creditsAmount,
                    'shares' => $shares,
                    'type' => $type,
                    'genre' => $genre,
                    'status' => $status,
                    'img' => $img,
                    'excerpt' => $excerpt
                ));
                //Conevertirmos el arreglo a json
                $article_postfields_json = json_encode($article_postfields);
                //Definición de los headers
                $headers_article = array(
                    'Content-type: application/json',
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json',
                );
                //Definición de las opciones de curl
                $curl_article_options = array(
                    CURLOPT_URL => $url_article,//url del api
                    CURLOPT_HTTPHEADER => $headers_article,//headers
                    CURLOPT_RETURNTRANSFER => true,//Devolver resultado de la transferencia
                    CURLOPT_CUSTOMREQUEST => "PUT",//Method POST
                    CURLOPT_POSTFIELDS => $article_postfields_json,//Parametros que se le envian al post
                    CURLOPT_HEADER => true,//Incluir el header en el output.
                    CURLINFO_HEADER_OUT => true
                );
                //Inicializamos curl
                $ch_article = curl_init();
                //Le pasamos las opciones
                curl_setopt_array($ch_article, $curl_article_options);
                //Ejecutamos curl
                $article_response = curl_exec($ch_article);
                //Obtenemos el código http de respuesta de la respuesta del curl
                $http_code = curl_getinfo($ch_article, CURLINFO_HTTP_CODE);
                //204 seccefully
                if ($http_code == "204") {
//                     error_log("####UPDATE EXITOSO");
                } else {
//                     error_log("####RESPUESTA DEL API: " . $article_response);
                }
                //Cerramos curl
                curl_close($ch_article);
                #END - UPDATE ARTÍCULO EN PHITTLE
            }

        }#END - Si el post status es publish
    }#END - Función save_article

}


function phittle_dev_options_page()
{
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2><?php _e('Phittle Main Settings'); ?></h2>

        <p><?php _e('Phittle is a plugin solution meant to let you earn money by locking your content to average site visitors.', 'phittle'); ?></p>

        <form method="POST" action="options.php">
            <?php
            settings_fields('phittle_dev_settings');
            ?>
            <table class="form-table">
                <tbody>
                <?php phittle_dev_do_options(); ?>
                </tbody>
            </table>
            <input type="submit" class="button-primary" value="<?php _e('Save Options', 'phittle'); ?>"/>
            <input type="hidden" name="phittle-dev-submit" value="Y"/>
        </form>
    </div>
<?php
}

function phittle_dev_menu()
{
    add_menu_page(__('Phittle Main Settings'), __('Phittle', 'phittle'), 'edit_themes', 'phittle_main', 'phittle_dev_options_page', '', 7);
}

//add_action('admin_menu', 'phittle_dev_menu');

function phittle_dev_init()
{
    register_setting('phittle_dev_settings', 'phittle_dev', 'phittle_dev_validate');
}

add_action('admin_init', 'phittle_dev_init');

function phittle_dev_do_options()
{
    $options = get_option('phittle_dev');
    ob_start();
    ?>
    <tr valign="top">
        <th scope="row"><?php _e('Not-logged in users text', 'phittle'); ?></th>
        <td>
            <?php
            if ($options['sharing-text']) {
                $options['sharing-text'] = wp_kses($options['sharing-text']);
            } else {
                $options['sharing-text'] = __('Phittle for more...', 'phittle');
            }
            ?>
            <input type="text" id="sharing-text" name="phittle_dev[sharing-text]" width="30"
                   value="<?php echo $options['sharing-text']; ?>"/><br/>
            <label class="description"
                   for="phittle_dev[sharing-text]"><?php _e('Allows you to change the text that displays for average visitors who are not logged into the website.', 'phittle'); ?></label>
        </td>
    </tr>
    <th scope="row"><?php _e('', 'phittle'); ?></th>
    <td>
        <?php
        foreach (phittle_dev_services() as $service) {
            $label = $service['label'];
            $value = $service['value'];
            echo '<label><input type="checkbox" name="phittle_dev[' . $value . '] value="1" ';
            switch ($value) {
                case 'email' :
                    if (isset($options['email'])) {
                        checked('on', $options['email']);
                    }
                    break;
            }
            echo '/> ' . esc_attr($label) . '</label><br />';
        }
        ?>
    </td>
<?php
}

function phittle_dev_services()
{
    $services = array(
        'email' => array(
            'value' => 'email',
            'label' => __('', 'phittle')
        ),
    );
    return $services;
}

function phittle_dev_content_filter($content)
{
    global $post;
    $options = get_option('phittle_dev');

    if (!$options['sharing-text']) {
        $options['sharing-text'] = __('THIS IS THE TEXT', 'phittle');
    }
    $article_id = get_post_meta(get_the_ID(), 'phittle_article_id', true);
    $new_content = "";
    if (is_main_query() && is_single() && !empty($article_id)) {
        //Means that we are in the single post page && this is the main run of the content
        // and not some 3rd party plugin calling the content
        $articleContent = get_the_content('');
        $articleContent = substr($articleContent, 0, 50);
        $articleContent = strip_tags($articleContent, '<br><br/>');
        $new_content = '<div id="phittle_paywall_full" data-postid="' . $post->ID . '" data-phittleid="' . $article_id . '" data-phittleexcerpt="'. $articleContent .'">';
        $new_content .= phittle_display_data();
        $new_content .= '</div>';
    } elseif (is_main_query() && !is_single() && !empty($article_id)) {
        $new_content = '<div id="phittle_paywall_excerpt_' . $post->ID . '" data-postid="' . $post->ID . '" data-phittleid="' . $article_id . '">';
        $new_content .= phittle_display_data();
        $new_content .= '</div>';
    }

    return $content . $new_content;
}

add_filter('the_content', 'phittle_dev_content_filter');
add_filter('the_excerpt', 'phittle_dev_content_filter');

function phittle_display_data()
{
    $access_level = get_post_meta(get_the_ID(), 'phittle_article_access_level', true);
    $credits = get_post_meta(get_the_ID(), 'phittle_article_credit_amount', true);
    $shares = get_post_meta(get_the_ID(), 'phittle_article_shares', true);
    $article_id = get_post_meta(get_the_ID(), 'phittle_article_id', true);
    $categories_list = get_the_category_list(__(', '));
    $article_postid=get_the_ID();
    $article_url=post_permalink( $article_postid );

    $options = get_option( 'phittle_settings' );
    $phittleDomain = $options['apiEndpoint'];

    wp_enqueue_script('phittle', $phittleDomain . '/bundles/lionixphittle/js/phittleapi.js');
    wp_enqueue_style('smoothnessdialog', 'https://code.jquery.com/ui/1.11.4/themes/black-tie/jquery-ui.css');
//     wp_enqueue_style('smoothnessdialog', CUSTOM_PHITTLE_DIR . '/css/jquery-ui.css');
    wp_enqueue_script('jquerydialog', 'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js');
//     wp_enqueue_script('jquerydialog', CUSTOM_PHITTLE_DIR . '/js/jquery-ui.js');

    $phittleid = "";
    if (($article_id)) {
        $phittleid = '<input  type="hidden" value="' . $article_id . '" id="phittleid' . $article_id . '" name="phittleid' . $article_id . '" data-phittleaccesslevel="'.$access_level.'" data-phittlearticleurl="'.$article_url.'" readonly>';
    }
    return $phittleid;
}

function phittle_dev_head_scripts()
{
    $options = get_option('phittle_dev');
}

add_action('wp_head', 'phittle_dev_head_scripts');

function phittle_dev_scripts()
{
    $options = get_option('phittle_dev');

    if (!is_admin()) {
        if (isset($options['google'])) {
            wp_enqueue_script('googleplus', 'https://apis.google.com/js/plusone.js');
        }
// 		wp_enqueue_style( 'phittle-dev', plugins_url( 'css/style.css', __FILE__ ) );
    }
}

add_action('wp_print_scripts', 'phittle_dev_scripts');

function phittle_dev_validate($input)
{
    $input['sharing-text'] = wp_filter_nohtml_kses($input['sharing-text']);
    foreach (phittle_dev_services() as $service) {
        $value = $service['value'];
        switch ($value) {
            case 'email' :
                if (!array_key_exists($input['email'], $value))
                    $input['email'] = $input['email'];
                break;
        }
    }

    return $input;
}
add_action( 'admin_menu', 'phittle_add_admin_menu' );
add_action( 'admin_init', 'phittle_settings_init' );


function phittle_add_admin_menu(  ) {

    add_menu_page( 'phittle', 'phittle', 'manage_options', 'phittle', 'phittle_options_page' );

}


function phittle_settings_init(  ) {

    register_setting( 'pluginPage', 'phittle_settings' );

    add_settings_section(
        'phittle_pluginPage_section',
        __( 'Use the configuration provided when you signed up for Phittle', 'wordpress' ),
        'phittle_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'apiEndpoint',
        __( 'API Endpoing URL', 'wordpress' ),
        'phittle_text_field_0_render',
        'pluginPage',
        'phittle_pluginPage_section'
    );

    add_settings_field(
        'publisherId',
        __( 'PublisherID', 'wordpress' ),
        'phittle_text_field_1_render',
        'pluginPage',
        'phittle_pluginPage_section'
    );

    add_settings_field(
        'publisherLogin',
        __( 'Publisher Login', 'wordpress' ),
        'phittle_text_field_2_render',
        'pluginPage',
        'phittle_pluginPage_section'
    );

    add_settings_field(
        'publisherSecret',
        __( 'Publisher Secret', 'wordpress' ),
        'phittle_text_field_3_render',
        'pluginPage',
        'phittle_pluginPage_section'
    );


}

function phittle_text_field_0_render(  ) {

    $options = get_option( 'phittle_settings' );
    printf(
        '<input type="text" id="apiEndpoint" name="phittle_settings[apiEndpoint]" value="%s" />',
        isset( $options['apiEndpoint'] ) ? esc_attr( $options['apiEndpoint']) : ''
    );

}


function phittle_text_field_1_render(  ) {

    $options = get_option( 'phittle_settings' );
    printf(
        '<input type="text" id="publisherId" name="phittle_settings[publisherId]" value="%s" />',
        isset( $options['publisherId'] ) ? esc_attr( $options['publisherId']) : ''
    );
}

function phittle_text_field_2_render(  ) {

    $options = get_option( 'phittle_settings' );
    printf(
        '<input type="text" id="publisherLogin" name="phittle_settings[publisherLogin]" value="%s" />',
        isset( $options['publisherLogin'] ) ? esc_attr( $options['publisherLogin']) : ''
    );

}

function phittle_text_field_3_render(  ) {

    $options = get_option( 'phittle_settings' );
    printf(
        '<input type="text" id="publisherSecret" name="phittle_settings[publisherSecret]" value="%s" />',
        isset( $options['publisherSecret'] ) ? esc_attr( $options['publisherSecret']) : ''
    );

}


function phittle_settings_section_callback(  ) {

    echo __( 'Parameters', 'wordpress' );

}


function phittle_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2>Phittle Publisher Wordpress Plugin Configuration</h2>

        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>

    </form>
<?php

}

?>
