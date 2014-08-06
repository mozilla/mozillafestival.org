<?php

	$custom_types = get_post_types(array('_builtin' => false), 'objects'); 
	$isWizard = $this->isWizard;
	$baseUrl  = $this->baseUrl;
	
?>

<input type="hidden" id="selected_post_type" value="<?php echo (!empty($post['custom_type'])) ? $post['custom_type'] : '';?>">
<input type="hidden" id="selected_type" value="<?php echo (!empty($post['type'])) ? $post['type'] : '';?>">
<h2>
	<?php if ($isWizard): ?>
		<?php _e('Import XML/CSV - Step 4: Options', 'pmxi_plugin') ?>
	<?php else: ?>
		<?php _e('Edit Import Options', 'pmxi_plugin') ?>
	<?php endif ?>
</h2>
<h3><?php _e('Click the appropriate tab to choose the type of posts to create.', 'pmxi_plugin');?></h3>

<?php do_action('pmxi_options_header', $isWizard, $post); ?>

<div class="ajax-console">
	<?php if ($this->errors->get_error_codes()): ?>
		<?php $this->error() ?>
	<?php endif ?>
</div>

<table class="layout">
	<tr>
		<td class="left" style="width:100%;">
			<?php $templates = new PMXI_Template_List() ?>
			<form class="load_options options <?php echo ! $isWizard ? 'edit' : '' ?>" method="post">
				<div class="load-template">
					<span><?php _e('Load existing template:','pmxi_plugin');?> </span>
					<select name="load_template">
						<option value=""><?php _e('Load Template...', 'pmxi_plugin') ?></option>
						<?php foreach ($templates->getBy()->convertRecords() as $t): ?>
							<option value="<?php echo $t->id ?>"><?php echo $t->name ?></option>
						<?php endforeach ?>
						<option value="-1"><?php _e('Reset...', 'pmxi_plugin') ?></option>
					</select>
				</div>
			</form>
			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" rel="posts" href="javascript:void(0);"><?php _e('Posts','pmxi_plugin');?></a>
				<a class="nav-tab" rel="pages" href="javascript:void(0);"><?php _e('Pages','pmxi_plugin');?></a>
				<?php $custom_types = apply_filters( 'pmxi_custom_types', $custom_types );?>
				<?php if (count($custom_types)): ?>
					<?php foreach ($custom_types as $key => $ct):?>
					<a class="nav-tab" rel="<?php echo $key; ?>" href="javascript:void(0);"><?php echo $ct->labels->name ?></a>					
					<?php endforeach ?>
				<?php endif ?>				
				<?php do_action('pmxi_custom_menu_item'); ?>			
			</h2>
			<div id="pmxi_tabs">			
				<div class="left">			   

				    <!-- Post Options -->

				    <div id="posts" class="pmxi_tab"> <!-- Basic -->
					    <form class="options <?php echo ! $isWizard ? 'edit' : '' ?>" method="post">
					    	<input type="hidden" name="type" value="post"/>
					    	<input type="hidden" name="custom_type" value=""/>
							<div class="post-type-options">
								<table class="form-table" style="max-width:none;">
									<?php
										$post_type = 'post';
										$entry = 'post';

										include( 'options/_main_options_template.php' );
										do_action('pmxi_extend_options_main', $entry);
										include( 'options/_taxonomies_template.php' );
										do_action('pmxi_extend_options_taxonomies', $entry);
										include( 'options/_categories_template.php' );
										do_action('pmxi_extend_options_categories', $entry);
										include( 'options/_custom_fields_template.php' );																		
										do_action('pmxi_extend_options_custom_fields', $entry);
										include( 'options/_featured_template.php' );
										do_action('pmxi_extend_options_featured', $entry);
										include( 'options/_author_template.php' );		
										do_action('pmxi_extend_options_author', $entry);								
										include( 'options/_reimport_template.php' );									
										include( 'options/_settings_template.php' );
									?>
								</table>
							</div>

							<?php include( 'options/_buttons_template.php' ); ?>

						</form>
					</div>

					<!-- Page Options -->

					<div id="pages" class="pmxi_tab">
						<form class="options <?php echo ! $isWizard ? 'edit' : '' ?>" method="post">
							<input type="hidden" name="type" value="page"/>
							<input type="hidden" name="custom_type" value=""/>						
							<div class="post-type-options">
								<table class="form-table" style="max-width:none;">

									<?php 
										$post_type = 'post';
										$entry = 'page';
										include( 'options/_main_options_template.php' ); 
									?>

									<tr>
										<td align="center" width="33%">
											<label><?php _e('Page Template', 'pmxi_plugin') ?></label> <br>
											<select name="page_template" id="page_template">
												<option value='default'><?php _e('Default', 'pmxi_plugin') ?></option>
												<?php page_template_dropdown($post['page_template']); ?>
											</select>
										</td>
										<td align="center" width="33%">
											<label><?php _e('Parent Page', 'pmxi_plugin') ?></label> <br>
											<?php wp_dropdown_pages(array('post_type' => 'page', 'selected' => $post['parent'], 'name' => 'parent', 'show_option_none' => __('(no parent)', 'pmxi_plugin'), 'sort_column'=> 'menu_order, post_title',)) ?>
										</td>
										<td align="center" width="33%">
											<label><?php _e('Order', 'pmxi_plugin') ?></label> <br>
											<input type="text" class="" name="order" value="<?php echo esc_attr($post['order']) ?>" />
										</td>
									</tr>
									<?php		
										do_action('pmxi_extend_options_main', $entry);
										include( 'options/_custom_fields_template.php' );
										do_action('pmxi_extend_options_custom_fields', $entry);
										include( 'options/_taxonomies_template.php' );
										do_action('pmxi_extend_options_taxonomies', $entry);
										include( 'options/_featured_template.php' );
										do_action('pmxi_extend_options_featured', $entry);
										include( 'options/_author_template.php' );
										do_action('pmxi_extend_options_author', $entry);
										include( 'options/_reimport_template.php' );									
										include( 'options/_settings_template.php' );
									?>
								</table>
							</div>

							<?php include( 'options/_buttons_template.php' ); ?>

						</form>
					</div>

					<!-- Custom Post Types -->

					<?php 				
					if (count($custom_types)): ?>
						<?php foreach ($custom_types as $key => $ct):?>
							<div id="<?php echo $key;?>" class="pmxi_tab">
								<form class="options <?php echo ! $isWizard ? 'edit' : '' ?>" method="post">
									<input type="hidden" name="custom_type" value="<?php echo $key; ?>"/>
									<input type="hidden" name="type" value="post"/>
									<div class="post-type-options">
										<table class="form-table" style="max-width:none;">
											<?php
												$post_type = $entry = $key;
												include( 'options/_main_options_template.php' );
												do_action('pmxi_extend_options_main', $entry);
												include( 'options/_taxonomies_template.php' );
												do_action('pmxi_extend_options_taxonomies', $entry);
												include( 'options/_categories_template.php' );
												do_action('pmxi_extend_options_categories', $entry);
												include( 'options/_custom_fields_template.php' );
												do_action('pmxi_extend_options_custom_fields', $entry);																								
												include( 'options/_featured_template.php' );
												do_action('pmxi_extend_options_featured', $entry);
												include( 'options/_author_template.php' );
												do_action('pmxi_extend_options_author', $entry);
												include( 'options/_reimport_template.php' );											
												include( 'options/_settings_template.php' );
											?>
										</table>
									</div>

									<?php include( 'options/_buttons_template.php' ); ?>

								</form>
							</div>
						<?php endforeach ?>
					<?php endif ?>
					
					<?php do_action('pmxi_custom_options_tab', $isWizard, $post);?>
					
				</div>
				<?php if ($isWizard or $this->isTemplateEdit): ?>
				<div class="right options">
					<?php $this->tag() ?>
				</div>
				<?php endif ?>
			</div>
		</td>	
	</tr>
</table>

<div id="record_matching_pointer" style="display:none;">	

	<h3><?php _e("Record Matching", "pmxi_plugin");?></h3>

	<p>
		<b><?php _e("Record Matching is how WP All Import matches records in your file with posts that already exist WordPress.","pmxi_plugin");?></b>
	</p>

	<p>
		<?php _e("Record Matching is most commonly used to tell WP All Import how to match up records in your file with posts WP All Import has already created on your site, so that if your file is updated with new data, WP All Import can update your posts accordingly.","pmxi_plugin");?>
	</p>

	<hr />

	<p><?php _e("AUTOMATIC RECORD MATCHING","pmxi_plugin");?></p>
	
	<p>
		<?php _e("Automatic Record Matching allows WP All Import to update records that were imported or updated during the last run of this same import.","pmxi_plugin");?>
	</p>

	<p>
		<?php _e("Your unique key must be UNIQUE for each record in your feed. Make sure you get it right - you can't change it later. You'll have to re-create your import.","pmxi_plugin");?>
	</p>

	<hr />

	<p><?php _e("MANUAL RECORD MATCHING", "pmxi_plugin");?></p>
	
	<p>
		<?php _e("Manual record matching allows WP All Import to update any records, even records that were not imported with WP All Import, or are part of a different import.","pmxi_plugin");?>
	</p>

</div>