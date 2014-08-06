<?php

if ( ! function_exists('reverse_taxonomies_html') ) {

	function reverse_taxonomies_html($post_taxonomies, $item_id, &$i){
		$childs = array();
		foreach ($post_taxonomies as $j => $cat) if ($cat->parent_id == $item_id) { $childs[] = $cat; }

		if (!empty($childs)){
			?>
			<ol>
			<?php
			foreach ($childs as $child_cat){
				$i++;
				?>
	            <li id="item_<?php echo $i; ?>">
	            	<div class="drag-element">
	            		<input type="checkbox" class="assign_post" <?php if ($child_cat->assign): ?>checked="checked"<?php endif; ?> title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/>		            		
	            		<input class="widefat" type="text" value="<?php echo esc_attr($child_cat->xpath); ?>"/>
	            	</div>
	            	<a href="javascript:void(0);" class="icon-item remove-ico"></a>
	            	<?php echo reverse_taxonomies_html($post_taxonomies, $child_cat->item_id, $i); ?>
	            </li>
				<?php
			}
			?>
			</ol>
			<?php
		}
	}
}

?>