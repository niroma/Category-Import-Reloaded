<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.niroma.net/
 * @since      1.0.0
 *
 * @package    Category_Import_Reloaded
 * @subpackage Category_Import_Reloaded/admin/partials
 */
	$taxonomiesList = array('category', 'post_tag');
	$args = array(
		'public'   => true,
		'_builtin' => false
	); 
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies( $args, $output, $operator ); 
	if ( $taxonomies ) {
		foreach ( $taxonomies  as $taxonomy ) {
			$taxonomiesList[] = $taxonomy;
		}
	}
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	
	<form name="bulk_categories" action="" method="post">
	
	<table class="form-table">
						
		<tr valign="top">
			<th scope="row">
				<label for="<?php echo $this->plugin_name; ?>-taxonomy">
					<span><?php esc_attr_e('Taxonomy', $this->plugin_name); ?></span>
				</label>
			</th>
			<td>								
				<select id="<?php echo $this->plugin_name; ?>-taxonomy" name="<?php echo $this->plugin_name; ?>-taxonomy">
					<?php foreach ($taxonomiesList as $taxonomy) { echo '<option value="'.$taxonomy.'">'.$taxonomy.'</option>'; } ?>
				</select>
			</td>
		</tr>	
		<tr valign="top">
			<th scope="row">
				<label for="<?php echo $this->plugin_name; ?>-delimiter">
					<span><?php esc_attr_e('Slug Delimiter', $this->plugin_name); ?> (<?php esc_attr_e('Optional', $this->plugin_name); ?>)</span>
				</label>
			</th>
			<td>								
				<input type="text" id="<?php echo $this->plugin_name; ?>-delimiter" name="<?php echo $this->plugin_name; ?>-delimiter" maxlength="2" size="2" class="regular-text" />
				<p  id="<?php echo $this->plugin_name; ?>-delimiter-description" class="description">Define a delimiter here to split the category name and slug. (default: $).</p>	
				<p class="example">Example : Level A / Level B$level-b1 / Level C$level-c1</p>	
			</td>
		</tr>	
		<tr valign="top">
			<th scope="row">
				<label for="<?php echo $this->plugin_name; ?>-bulkCategoryList">
					<span><?php esc_attr_e('Taxonomies List', $this->plugin_name); ?></span>
				</label>
			</th>
			<td>
				<textarea class="large-text" id="<?php echo $this->plugin_name; ?>-bulkCategoryList" name="<?php echo $this->plugin_name; ?>-bulkCategoryList" rows="20"></textarea>
			</td>
		</tr>	
			
		<tr valign="top">
			<th scope="row">
			</th>
			<td>
				<p class="description">Enter the categories you want to add.</p>
				<p class="description">If you want to make a hierarchy, put a slash between the category and the sub-category in one line.</p>
				<p class="example">Example : Level A/Level B/Level C</p>
			</td>
		</tr>	
			
		<tr valign="top">
			<th scope="row">
			</th>
			<td>
				<input type="hidden" id="<?php echo $this->plugin_name; ?>-redirect_url" name="<?php echo $this->plugin_name; ?>-redirect_url" value="<?php echo admin_url('edit.php?page=category-import-reloaded'); ?>"/><input class="button button-primary" type="submit" id="<?php echo $this->plugin_name; ?>-submit" name="<?php echo $this->plugin_name; ?>-submit" value="<?php esc_attr_e('Add taxonomies', $this->plugin_name); ?>"/>
			</td>
		</tr>
	
	</table>
    </form>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
