<tr>
	<td colspan="3">
		<div class="postbox " id="woocommerce-product-data">
			<h3 class="hndle">
				<span>
					<div class="main_choise" style="padding:0px; margin-right:0px;">
						<input type="radio" id="multiple_product_type_yes" class="switcher" name="is_multiple_product_type" value="yes" <?php echo 'no' != $post['is_multiple_product_type'] ? 'checked="checked"': '' ?>/>
						<label for="multiple_product_type_yes"><?php _e('Product Type', 'pmxi_plugin' )?></label>
					</div>
					<div class="switcher-target-multiple_product_type_yes"  style="float:left;">
						<div class="input">
							<select name="multiple_product_type" id="product-type" style="vertical-align:middle; height:23px; width:220px;">
								<optgroup label="Product Type">
									<option value="simple" <?php echo 'simple' == $post['multiple_product_type'] ? 'selected="selected"': '' ?>><?php _e('Simple product', 'woocommerce');?></option>
									<option value="grouped" <?php echo 'grouped' == $post['multiple_product_type'] ? 'selected="selected"': '' ?>><?php _e('Grouped product','woocommerce');?></option>
									<option value="external" <?php echo 'external' == $post['multiple_product_type'] ? 'selected="selected"': '' ?>><?php _e('External/Affiliate product','woocommerce');?></option>
									<option value="variable" <?php echo 'variable' == $post['multiple_product_type'] ? 'selected="selected"': '' ?>><?php _e('Variable product','woocommerce');?></option>
								</optgroup>
							</select>
						</div>
					</div>
					<div class="main_choise" style="padding:0px; margin-left:40px;">
						<input type="radio" id="multiple_product_type_no" class="switcher" name="is_multiple_product_type" value="no" <?php echo 'no' == $post['is_multiple_product_type'] ? 'checked="checked"': '' ?> disabled="disabled"/>
						<label for="multiple_product_type_no"><?php _e('Set Product Type With XPath', 'pmxi_plugin' )?></label>
					</div>
					<div class="switcher-target-multiple_product_type_no"  style="float:left;">
						<div class="input">
							<input type="text" class="smaller-text" name="single_product_type" style="width:300px;" value="<?php echo esc_attr($post['single_product_type']) ?>"/>
							<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'simple\', \'grouped\', \'external\', \'variable\').', 'pmxi_plugin') ?>">?</a>
						</div>
					</div>
					<div style="float:right;">
						<label class="show_if_simple" for="_virtual" style="border-right:none;"><?php _e('Virtual','woocommerce');?>: <input type="checkbox" id="_virtual" name="_virtual" <?php echo ($post['_virtual']) ? 'checked="checked"' : ''; ?>></label>
						<label class="show_if_simple" for="_downloadable"><?php _e('Downloadable','woocommerce');?>: <input type="checkbox" id="_downloadable" name="_downloadable" <?php echo ($post['_downloadable']) ? 'checked="checked"' : ''; ?>></label>
					</div>
				</span>
			</h3>
			<div class="clear"></div>
			<div class="inside">
				<div class="panel-wrap product_data">

					<div class="wc-tabs-back"></div>

					<ul style="" class="product_data_tabs wc-tabs">

						<li class="general_options hide_if_grouped active"><a href="javascript:void(0);" rel="general_product_data"><?php _e('General','woocommerce');?></a></li>

						<li class="inventory_tab show_if_simple show_if_variable show_if_grouped inventory_options" style="display: block;"><a href="javascript:void(0);" rel="inventory_product_data"><?php _e('Inventory', 'woocommerce');?></a></li>

						<li class="shipping_tab hide_if_virtual shipping_options hide_if_grouped hide_if_external"><a href="javascript:void(0);" rel="shipping_product_data"><?php _e('Shipping', 'woocommerce');?></a></li>

						<li class="linked_product_tab linked_product_options"><a href="javascript:void(0);" rel="linked_product_data"><?php _e('Linked Products', 'woocommerce');?></a></li>

						<li class="attributes_tab attribute_options"><a href="javascript:void(0);" rel="woocommerce_attributes"><?php _e('Attributes','woocommerce');?></a></li>

						<li class="advanced_tab advanced_options"><a href="javascript:void(0);" rel="advanced_product_data"><?php _e('Advanced','woocommerce');?></a></li>

						<li class="variations_tab show_if_variable variation_options"><a title="Variations for variable products are defined here." href="javascript:void(0);" rel="variable_product_options"><?php _e('Variations','woocommerce');?></a></li>

						<li class="options_tab advanced_options"><a title="Variations for variable products are defined here." href="javascript:void(0);" rel="add_on_options"><?php _e('Add-On Options', 'pmxi_plugin');?></a></li>

						<?php do_action('pmwi_tab_header'); ?>

					</ul>

					<div class="panel woocommerce_options_panel" id="general_product_data">

						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>

						<div class="options_group hide_if_grouped">
							<p class="form-field">
								<label><?php _e("SKU"); ?></label>
								<input type="text" class="short" name="single_product_sku" style="" value="<?php echo esc_attr($post['single_product_sku']) ?>"/>
								<a href="#help" class="help" title="<?php _e('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce') ?>">?</a>
							</p>
						</div>
						<div class="options_group show_if_external">
							<p class="form-field">
								<label><?php _e("Product URL"); ?></label>
								<input type="text" class="short" name="single_product_url" value="<?php echo esc_attr($post['single_product_url']) ?>"/>
								<a href="#help" class="help" title="<?php _e('The external/affiliate link URL to the product.', 'pmxi_plugin') ?>">?</a>
							</p>
							<p class="form-field">
								<label><?php _e("Button text"); ?></label>
								<input type="text" class="short" name="single_product_button_text" value="<?php echo esc_attr($post['single_product_button_text']) ?>"/>
								<a href="#help" class="help" title="<?php _e('This text will be shown on the button linking to the external product.', 'pmxi_plugin') ?>">?</a>
							</p>
						</div>
						<div class="options_group pricing show_if_simple show_if_external show_if_variable">
							<p class="form-field">
								<label><?php _e("Regular Price (".get_woocommerce_currency_symbol().")"); ?></label>
								<input type="text" class="short" name="single_product_regular_price" value="<?php echo esc_attr($post['single_product_regular_price']) ?>"/>
							</p>
							<p class="form-field">
								<label><?php _e("Sale Price (".get_woocommerce_currency_symbol().")"); ?></label>
								<input type="text" class="short" name="single_product_sale_price" value="<?php echo esc_attr($post['single_product_sale_price']) ?>"/>&nbsp;<a id="regular_price_shedule" href="javascript:void(0);" <?php if ($post['is_regular_price_shedule']):?>style="display:none;"<?php endif; ?>><?php _e('schedule');?></a>
								<input type="hidden" name="is_regular_price_shedule" value="<?php echo esc_attr($post['is_regular_price_shedule']) ?>"/>
							</p>
							<p class="form-field" <?php if ( ! $post['is_regular_price_shedule']):?>style="display:none;"<?php endif; ?> id="sale_price_range">
								<span style="vertical-align:middle">
									<label><?php _e("Sale Price Dates"); ?></label>
									<input type="text" class="datepicker" name="single_sale_price_dates_from" value="<?php echo esc_attr($post['single_sale_price_dates_from']) ?>" style="float:none;"/>
									<?php _e('and', 'pmxi_plugin') ?>
									<input type="text" class="datepicker" name="single_sale_price_dates_to" value="<?php echo esc_attr($post['single_sale_price_dates_to']) ?>" style="float:none;"/>
									&nbsp;<a id="cancel_regular_price_shedule" href="javascript:void(0);"><?php _e('cancel');?></a>
								</span>
							</p>
							<?php if ( class_exists('woocommerce_wholesale_pricing') ):?>
							<p class="form-field">
								<label><?php _e("Wholesale Price (".get_woocommerce_currency_symbol().")"); ?></label>
								<input type="text" class="short" name="single_product_whosale_price" value="<?php echo esc_attr($post['single_product_whosale_price']) ?>"/>								
							</p>
							<?php endif; ?>
						</div>
						<div class="options_group show_if_virtual">
							<div class="input" style="padding-left:0px;">
								<div class="input fleft">
									<input type="radio" id="is_product_virtual_yes" class="switcher" name="is_product_virtual" value="yes" <?php echo 'yes' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_virtual_yes"><?php _e("Virtual"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="is_product_virtual_no" class="switcher" name="is_product_virtual" value="no" <?php echo 'no' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_virtual_no"><?php _e("Not Virtual"); ?></label>
								</div>
								<div class="input fleft" style="position:relative;width:220px;">
									<input type="radio" id="is_product_virtual_xpath" class="switcher" name="is_product_virtual" value="xpath" <?php echo 'xpath' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_virtual_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
									<div class="switcher-target-is_product_virtual_xpath set_with_xpath">
										<div class="input">
											&nbsp;<input type="text" class="smaller-text" name="single_product_virtual" style="width:300px;" value="<?php echo esc_attr($post['single_product_virtual']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group show_if_downloadable">
							<div class="input" style="padding-left:0px;">
								<div class="input fleft">
									<input type="radio" id="is_product_downloadable_yes" class="switcher" name="is_product_downloadable" value="yes" <?php echo 'yes' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_downloadable_yes"><?php _e("Downloadable"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="is_product_downloadable_no" class="switcher" name="is_product_downloadable" value="no" <?php echo 'no' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_downloadable_no"><?php _e("Not Downloadable"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="is_product_downloadable_xpath" class="switcher" name="is_product_downloadable" value="xpath" <?php echo 'xpath' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_downloadable_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
									<div class="switcher-target-is_product_downloadable_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_downloadable" style="width:300px;" value="<?php echo esc_attr($post['single_product_downloadable']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="clear"></div>
							<p class="form-field">
								<label><?php _e("File paths"); ?></label>
								<input type="text" class="short" name="single_product_files" value="<?php echo esc_attr($post['single_product_files']) ?>" style="margin-right:5px;"/>
								<input type="text" class="small" name="product_files_delim" value="<?php echo esc_attr($post['product_files_delim']) ?>" style="width:5%; text-align:center;"/>
								<a href="#help" class="help" title="<?php _e('File paths/URLs, comma separated. The delimiter is used when an XML element contains multiple URLs/paths - i.e. <code>http://files.com/1.doc, http://files.com/2.doc</code>.', 'pmxi_plugin') ?>">?</a>
							</p>
							<p class="form-field">
								<label><?php _e("Download Limit"); ?></label>
								<input type="text" class="short" placeholder="Unimited" name="single_product_download_limit" value="<?php echo esc_attr($post['single_product_download_limit']) ?>"/>&nbsp;
								<?php _e( 'Leave blank for unlimited re-downloads.', 'woocommerce' ) ?>
							</p>
							<p class="form-field">
								<label><?php _e("Download Expiry"); ?></label>
								<input type="text" class="short" placeholder="Never" name="single_product_download_expiry" value="<?php echo esc_attr($post['single_product_download_expiry']) ?>"/>&nbsp;
								<?php _e( 'Enter the number of days before a download link expires, or leave blank.', 'woocommerce' ) ?>
							</p>
						</div>
						<div class="options_group show_if_simple show_if_external show_if_variable">
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_tax_status_yes" class="switcher" name="is_multiple_product_tax_status" value="yes" <?php echo 'no' != $post['is_multiple_product_tax_status'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_tax_status_yes"><?php _e("Tax Status"); ?></label>
								</div>
								<div class="switcher-target-multiple_product_tax_status_yes"  style="padding-left:17px;">
									<div class="input">
										<select class="select short" name="multiple_product_tax_status">
											<option value="taxable" <?php echo 'taxable' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('Taxable', 'woocommerce');?></option>
											<option value="shipping" <?php echo 'shipping' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('Shipping only', 'woocommerce');?></option>
											<option value="none" <?php echo 'none' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('None', 'woocommerce');?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_tax_status_no" class="switcher" name="is_multiple_product_tax_status" value="no" <?php echo 'no' == $post['is_multiple_product_tax_status'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_tax_status_no"><?php _e('Set product tax status with XPath', 'pmxi_plugin' )?></label>
								</div>
								<div class="switcher-target-multiple_product_tax_status_no"  style="padding-left:17px;">
									<div class="input">
										<input type="text" class="smaller-text" name="single_product_tax_status" style="width:300px;" value="<?php echo esc_attr($post['single_product_tax_status']) ?>"/>
										<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'taxable\', \'shipping\', \'none\').', 'pmxi_plugin') ?>">?</a>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group show_if_simple show_if_external show_if_variable">
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_tax_class_yes" class="switcher" name="is_multiple_product_tax_class" value="yes" <?php echo 'no' != $post['is_multiple_product_tax_class'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_tax_class_yes"><?php _e("Tax Class"); ?></label>
								</div>
								<div class="switcher-target-multiple_product_tax_class_yes"  style="padding-left:17px;">
									<div class="input">
										<select class="select short" name="multiple_product_tax_class">
											<option value="" <?php echo '' == $post['multiple_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Standard', 'woocommerce');?></option>
											<option value="reduced-rate" <?php echo 'reduced-rate' == $post['multiple_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Reduced Rate', 'woocommerce');?></option>
											<option value="zero-rate" <?php echo 'zero-rate' == $post['multiple_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Zero Rate', 'woocommerce');?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_tax_class_no" class="switcher" name="is_multiple_product_tax_class" value="no" <?php echo 'no' == $post['is_multiple_product_tax_class'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_tax_class_no"><?php _e('Set product tax class with XPath', 'pmxi_plugin' )?></label>
								</div>
								<div class="switcher-target-multiple_product_tax_class_no"  style="padding-left:17px;">
									<div class="input">
										<input type="text" class="smaller-text" name="single_product_tax_class" style="width:300px;" value="<?php echo esc_attr($post['single_product_tax_class']) ?>"/>
										<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'reduced-rate\', \'zero-rate\').', 'pmxi_plugin') ?>">?</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- INVENTORY -->

					<div class="panel woocommerce_options_panel" id="inventory_product_data" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group show_if_simple show_if_variable">
							<p class="form-field">Manage stock?</p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="is_product_manage_stock_yes" class="switcher" name="is_product_manage_stock" value="yes" <?php echo 'yes' == $post['is_product_manage_stock'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_manage_stock_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="is_product_manage_stock_no" class="switcher" name="is_product_manage_stock" value="no" <?php echo 'no' == $post['is_product_manage_stock'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_manage_stock_no"><?php _e("No"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="is_product_manage_stock_xpath" class="switcher" name="is_product_manage_stock" value="xpath" <?php echo 'xpath' == $post['is_product_manage_stock'] ? 'checked="checked"': '' ?>/>
									<label for="is_product_manage_stock_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
									<div class="switcher-target-is_product_manage_stock_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_manage_stock" style="width:300px;" value="<?php echo esc_attr($post['single_product_manage_stock']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group stock_fields show_if_simple show_if_variable" style="padding-bottom:0px;">
							<p class="form-field">
								<label><?php _e("Stock Qty"); ?></label>
								<input type="text" class="short" name="single_product_stock_qty" value="<?php echo esc_attr($post['single_product_stock_qty']) ?>"/>
								<a href="#help" class="help" title="<?php _e('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce'); ?>">?</a>
							</p>
						</div>
						<div class="options_group">
							<p class="form-field"><?php _e('Stock status','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_stock_status_in_stock" class="switcher" name="product_stock_status" value="instock" <?php echo 'instock' == $post['product_stock_status'] ? 'checked="checked"': '' ?>/>
									<label for="product_stock_status_in_stock"><?php _e("In stock"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_stock_status_out_of_stock" class="switcher" name="product_stock_status" value="outofstock" <?php echo 'outofstock' == $post['product_stock_status'] ? 'checked="checked"': '' ?>/>
									<label for="product_stock_status_out_of_stock"><?php _e("Out of stock"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_stock_status_xpath" class="switcher" name="product_stock_status" value="xpath" <?php echo 'xpath' == $post['product_stock_status'] ? 'checked="checked"': '' ?>/>
									<label for="product_stock_status_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_stock_status_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_stock_status" style="width:300px;" value="<?php echo esc_attr($post['single_product_stock_status']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'instock\', \'outofstock\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group show_if_simple show_if_variable">
							<p class="form-field"><?php _e('Allow Backorders?','pmxi_plugin');?><a href="#help" class="help" title="<?php _e('If managing stock, this controls whether or not backorders are allowed for this product and variations. If enabled, stock quantity can go below 0.', 'woocommerce'); ?>">?</a></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_allow_backorders_no" class="switcher" name="product_allow_backorders" value="no" <?php echo 'no' == $post['product_allow_backorders'] ? 'checked="checked"': '' ?>/>
									<label for="product_allow_backorders_no"><?php _e("Do not allow"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_allow_backorders_notify" class="switcher" name="product_allow_backorders" value="notify" <?php echo 'notify' == $post['product_allow_backorders'] ? 'checked="checked"': '' ?>/>
									<label for="product_allow_backorders_notify"><?php _e("Allow, but notify customer"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_allow_backorders_yes" class="switcher" name="product_allow_backorders" value="yes" <?php echo 'yes' == $post['product_allow_backorders'] ? 'checked="checked"': '' ?>/>
									<label for="product_allow_backorders_yes"><?php _e("Allow"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_allow_backorders_xpath" class="switcher" name="product_allow_backorders" value="xpath" <?php echo 'xpath' == $post['product_allow_backorders'] ? 'checked="checked"': '' ?>/>
									<label for="product_allow_backorders_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_allow_backorders_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_allow_backorders" style="width:300px;" value="<?php echo esc_attr($post['single_product_allow_backorders']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'no\', \'notify\', \'yes\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group show_if_simple show_if_variable">
							<p class="form-field"><?php _e('Sold Individually?','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_sold_individually_yes" class="switcher" name="product_sold_individually" value="yes" <?php echo 'yes' == $post['product_sold_individually'] ? 'checked="checked"': '' ?>/>
									<label for="product_sold_individually_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_sold_individually_no" class="switcher" name="product_sold_individually" value="no" <?php echo 'no' == $post['product_sold_individually'] ? 'checked="checked"': '' ?>/>
									<label for="product_sold_individually_no"><?php _e("No"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_sold_individually_xpath" class="switcher" name="product_sold_individually" value="xpath" <?php echo 'xpath' == $post['product_sold_individually'] ? 'checked="checked"': '' ?>/>
									<label for="product_sold_individually_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_sold_individually_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_sold_individually" style="width:300px;" value="<?php echo esc_attr($post['single_product_sold_individually']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- SHIPPING -->

					<div class="panel woocommerce_options_panel" id="shipping_product_data" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group">
							<p class="form-field">
								<label><?php _e("Weight (kg)"); ?></label>
								<input type="text" class="short" placeholder="0.00" name="single_product_weight" style="" value="<?php echo esc_attr($post['single_product_weight']) ?>"/>
							</p>
							<p class="form-field">
								<label><?php _e("Dimensions (cm)"); ?></label>
								<input type="text" class="short" placeholder="Length" name="single_product_length" style="margin-right:5px;" value="<?php echo esc_attr($post['single_product_length']) ?>"/>
								<input type="text" class="short" placeholder="Width" name="single_product_width" style="margin-right:5px;" value="<?php echo esc_attr($post['single_product_width']) ?>"/>
								<input type="text" class="short" placeholder="Height" name="single_product_height" style="" value="<?php echo esc_attr($post['single_product_height']) ?>"/>
							</p>
						</div> <!-- End options group -->

						<div class="options_group">
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_shipping_class_yes" class="switcher" name="is_multiple_product_shipping_class" value="yes" <?php echo 'no' != $post['is_multiple_product_shipping_class'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_shipping_class_yes"><?php _e("Shipping Class"); ?></label>
								</div>
								<div class="switcher-target-multiple_product_shipping_class_yes"  style="padding-left:17px;">
									<div class="input">
										<?php
											$classes = get_the_terms( 0, 'product_shipping_class' );
											if ( $classes && ! is_wp_error( $classes ) ) $current_shipping_class = current($classes)->term_id; else $current_shipping_class = '';

											$args = array(
												'taxonomy' 			=> 'product_shipping_class',
												'hide_empty'		=> 0,
												'show_option_none' 	=> __( 'No shipping class', 'woocommerce' ),
												'name' 				=> 'multiple_product_shipping_class',
												'id'				=> 'multiple_product_shipping_class',
												'selected'			=> $current_shipping_class,
												'class'				=> 'select short'
											);

											wp_dropdown_categories( $args );
										?>
									</div>
								</div>
							</div>
							<div class="input">
								<div class="main_choise">
									<input type="radio" id="multiple_product_shipping_class_no" class="switcher" name="is_multiple_product_shipping_class" value="no" <?php echo 'no' == $post['is_multiple_product_shipping_class'] ? 'checked="checked"': '' ?>/>
									<label for="multiple_product_shipping_class_no"><?php _e('Set product shipping class with XPath', 'pmxi_plugin' )?></label>
								</div>
								<div class="switcher-target-multiple_product_shipping_class_no"  style="padding-left:17px;">
									<div class="input">
										<input type="text" class="smaller-text" name="single_product_shipping_class" style="width:300px;" value="<?php echo esc_attr($post['single_product_shipping_class']) ?>"/>
										<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'taxable\', \'shipping\', \'none\').', 'pmxi_plugin') ?>">?</a>
									</div>
								</div>
							</div>
						</div>	<!-- End options group -->
					</div> <!-- End Product Panel -->

					<!-- LINKED PRODUCT -->

					<div class="panel woocommerce_options_panel" id="linked_product_data" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group">
							<p class="form-field">
								<label><?php _e("Up-Sells"); ?></label>
								<input type="text" class="" placeholder="Products SKU, comma separated" name="single_product_up_sells" style="" value="<?php echo esc_attr($post['single_product_up_sells']) ?>"/>
								<a href="#help" class="help" title="<?php _e('Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce') ?>">?</a>
							</p>
							<p class="form-field">
								<label><?php _e("Cross-Sells"); ?></label>
								<input type="text" class="" placeholder="Products SKU, comma separated" name="single_product_cross_sells" value="<?php echo esc_attr($post['single_product_cross_sells']) ?>"/>
								<a href="#help" class="help" title="<?php _e('Cross-sells are products which you promote in the cart, based on the current product.', 'woocommerce') ?>">?</a>
							</p>
						</div> <!-- End options group -->
						<div class="options_group grouping show_if_simple show_if_external">
							<?php
							$post_parents = array();
							$post_parents[''] = __( 'Choose a grouped product&hellip;', 'woocommerce' );

							$posts_in = array_unique( (array) get_objects_in_term( get_term_by( 'slug', 'grouped', 'product_type' )->term_id, 'product_type' ) );
							if ( sizeof( $posts_in ) > 0 ) {
								$args = array(
									'post_type'		=> 'product',
									'post_status' 	=> 'any',
									'numberposts' 	=> -1,
									'orderby' 		=> 'title',
									'order' 		=> 'asc',
									'post_parent' 	=> 0,
									'include' 		=> $posts_in,
								);
								$grouped_products = get_posts( $args );

								if ( $grouped_products ) {
									foreach ( $grouped_products as $product ) {

										if ( $product->ID == $post->ID )
											continue;

										$post_parents[ $product->ID ] = $product->post_title;
									}
								}
							}
							?>
							<p class="form-field">
								<label><?php _e("Grouping", "woocommerce"); ?></label>
								<select name="grouping_product">
									<?php
									foreach ($post_parents as $parent_id => $parent_title) {
										?>
										<option value="<?php echo $parent_id; ?>" <?php if ($parent_id == $post['grouping_product']):?>selected="selected"<?php endif;?>><?php echo $parent_title;?></option>
										<?php
									}
									?>
								</select>
								<a href="#help" class="help" title="<?php _e('Set this option to make this product part of a grouped product.', 'woocommerce'); ?>">?</a>
							</p>
						</div>
					</div><!-- End Product Panel -->

					<!-- ATTRIBUTES -->

					<div class="panel woocommerce_options_panel" id="woocommerce_attributes" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="input">
							<table class="form-table custom-params" id="attributes_table" style="max-width:95%;">
								<thead>
									<tr>
										<td><?php _e('Name', 'pmxi_plugin') ?></td>
										<td><?php _e('Values', 'pmxi_plugin') ?></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($post['attribute_name'][0])):?>
										<?php foreach ($post['attribute_name'] as $i => $name): if ("" == $name) continue; ?>
											<tr class="form-field">
												<td><input type="text" name="attribute_name[]"  value="<?php echo esc_attr($name) ?>" style="width:100%;"/></td>
												<td>
													<textarea name="attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."><?php echo str_replace("&amp;","&", htmlentities(htmlentities($post['attribute_value'][$i]))); ?></textarea>
													<br>
													<span class='in_variations'>													
														<input type="checkbox" name="in_variations[]" id="in_variations_<?php echo $i; ?>" <?php echo ($post['in_variations'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
														<label for="in_variations_<?php echo $i; ?>"><?php _e('In Variations','pmxi_plugin');?></label>															
													</span>

													<span class='is_visible'>
														<input type="checkbox" name="is_visible[]" id="is_visible_<?php echo $i; ?>" <?php echo ($post['is_visible'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
														<label for="is_visible_<?php echo $i; ?>"><?php _e('Is Visible','pmxi_plugin');?></label>																									
													</span>

													<span class='is_taxonomy'>
														<input type="checkbox" name="is_taxonomy[]" id="is_taxonomy_<?php echo $i; ?>" <?php echo ($post['is_taxonomy'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
														<label for="is_taxonomy_<?php echo $i; ?>"><?php _e('Taxonomy','pmxi_plugin');?></label>													
													</span>

													<span class='is_create_taxonomy'>
														<input type="checkbox" name="create_taxonomy_in_not_exists[]" id="create_taxonomy_in_not_exists_<?php echo $i; ?>" <?php echo ($post['create_taxonomy_in_not_exists'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
														<label for="create_taxonomy_in_not_exists_<?php echo $i; ?>"><?php _e('Auto-Create Terms','pmxi_plugin');?></label>													
													</span>												
												</td>
												<td class="action remove"><a href="#remove"></a></td>
											</tr>
										<?php endforeach ?>
									<?php else: ?>
									<tr class="form-field">
										<td><input type="text" name="attribute_name[]" value="" style="width:100%;"/></td>
										<td>
											<textarea name="attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."></textarea>
											<br>
											<span class='in_variations'>
												<input type="checkbox" name="in_variations[]" id="in_variations_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for="in_variations_0"><?php _e('In Variations','pmxi_plugin');?></label>											
											</span>
											<span class='is_visible'>
												<input type="checkbox" name="is_visible[]" id="is_visible_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for="is_visible_0"><?php _e('Is Visible','pmxi_plugin');?></label>
											</span>
											<span class='is_taxonomy'>
												<input type="checkbox" name="is_taxonomy[]" id="is_taxonomy_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for="is_taxonomy_0"><?php _e('Taxonomy','pmxi_plugin');?></label>
											</span>
											<span class='is_create_taxonomy'>
												<input type="checkbox" name="create_taxonomy_in_not_exists[]" id="create_taxonomy_in_not_exists_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for="create_taxonomy_in_not_exists_0"><?php _e('Auto-Create Terms','pmxi_plugin');?></label>
											</span>
										<td class="action remove"><a href="#remove"></a></td>
									</tr>
									<?php endif;?>
									<tr class="form-field template">
										<td><input type="text" name="attribute_name[]" value="" style="width:100%;"/></td>
										<td>
											<textarea name="attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."></textarea>
											<br>
											<span class='in_variations'>
												<input type="checkbox" name="in_variations[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for=""><?php _e('In Variations','pmxi_plugin');?></label>																	
											</span>
											<span class='is_visible'>
												<input type="checkbox" name="is_visible[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for=""><?php _e('Is Visible','pmxi_plugin');?></label>																	
											</span>
											<span class='is_taxonomy'>
												<input type="checkbox" name="is_taxonomy[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for=""><?php _e('Taxonomy','pmxi_plugin');?></label>																	
											</span>
											<span class='is_create_taxonomy'>
												<input type="checkbox" name="create_taxonomy_in_not_exists[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
												<label for=""><?php _e('Auto-Create Terms','pmxi_plugin');?></label>																	
											</span>										
										<td class="action remove"><a href="#remove"></a></td>
									</tr>
									<tr>
										<td colspan="3"><a href="#add" title="<?php _e('add', 'pmxi_plugin')?>" class="action add-new-custom"><?php _e('Add more', 'pmxi_plugin') ?></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="options_group show_if_variable">
							<div class="input" style="padding-left:5px; marign-top:10px;">
								<input type="hidden" name="link_all_variations" value="0" />
								<input type="checkbox" id="link_all_variations" name="link_all_variations" value="1" <?php echo $post['link_all_variations'] ? 'checked="checked"' : '' ?> style="width:25px; position:relative; top:-1px;"/>
								<label for="link_all_variations"><?php _e('Link all variations', 'pmxi_plugin') ?></label>
								<a href="#help" class="help" title="<?php _e('This option will create all possible variations for the presented attributes. Works just like the Link All Variations option inside WooCommerce.', 'pmxi_plugin') ?>" style="position:relative; top:-3px;">?</a>
							</div>
						</div>
					</div><!-- End Product Panel -->

					<!-- ADVANCED -->

					<div class="panel woocommerce_options_panel" id="advanced_product_data" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group hide_if_external" style="padding-bottom:0px;">
							<p class="form-field">
								<label><?php _e("Purchase Note"); ?></label>
								<input type="text" class="short" placeholder="" name="single_product_purchase_note" style="" value="<?php echo esc_attr($post['single_product_purchase_note']) ?>"/>
							</p>
						</div>
						<div class="options_group" style="padding-bottom:0px;">
							<p class="form-field">
								<label><?php _e("Menu order"); ?></label>
								<input type="text" class="short" placeholder="" name="single_product_menu_order" value="<?php echo esc_attr($post['single_product_menu_order']) ?>"/>
							</p>
						</div>
						<div class="options_group reviews">
							<p class="form-field"><?php _e('Enable reviews','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_enable_reviews_yes" class="switcher" name="is_product_enable_reviews" value="yes" <?php echo 'yes' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
									<label for="product_enable_reviews_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_enable_reviews_no" class="switcher" name="is_product_enable_reviews" value="no" <?php echo 'no' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
									<label for="product_enable_reviews_no"><?php _e("No"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_enable_reviews_xpath" class="switcher" name="is_product_enable_reviews" value="xpath" <?php echo 'xpath' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
									<label for="product_enable_reviews_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_enable_reviews_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_enable_reviews" style="width:300px;" value="<?php echo esc_attr($post['single_product_enable_reviews']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- End options group -->
						<div class="options_group">
							<p class="form-field"><?php _e('Featured','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_featured_yes" class="switcher" name="is_product_featured" value="yes" <?php echo 'yes' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
									<label for="product_featured_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_featured_no" class="switcher" name="is_product_featured" value="no" <?php echo 'no' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
									<label for="product_featured_no"><?php _e("No"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_featured_xpath" class="switcher" name="is_product_featured" value="xpath" <?php echo 'xpath' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
									<label for="product_featured_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_featured_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_featured" style="width:300px;" value="<?php echo esc_attr($post['single_product_featured']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- End options group -->
						<div class="options_group">
							<p class="form-field"><?php _e('Catalog visibility','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_visibility_visible" class="switcher" name="is_product_visibility" value="visible" <?php echo 'visible' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
									<label for="product_visibility_visible"><?php _e("Catalog/search"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_visibility_catalog" class="switcher" name="is_product_visibility" value="catalog" <?php echo 'catalog' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
									<label for="product_visibility_catalog"><?php _e("Catalog"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_visibility_search" class="switcher" name="is_product_visibility" value="search" <?php echo 'search' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
									<label for="product_visibility_search"><?php _e("Search"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_visibility_hidden" class="switcher" name="is_product_visibility" value="hidden" <?php echo 'hidden' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
									<label for="product_visibility_hidden"><?php _e("Hidden"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_visibility_xpath" class="switcher" name="is_product_visibility" value="xpath" <?php echo 'xpath' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
									<label for="product_visibility_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_visibility_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_visibility" style="width:300px;" value="<?php echo esc_attr($post['single_product_visibility']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'visible\', \'catalog\', \'search\', \'hidden\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- End options group -->
					</div><!-- End Product Panel -->

					<!-- VARIATIONS -->

					<div class="panel woocommerce_options_panel" id="variable_product_options" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group" style="padding-bottom:0px;">
							<div class="input" style="padding-bottom:10px;">																								
								<input type="radio" id="auto_matching_parent" class="switcher" name="matching_parent" value="auto" <?php echo 'auto' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="auto_matching_parent" style="width:95%"><?php _e('All my variable products have SKUs or some other unique identifier. Each variation is linked to its parent with its parent\'s SKU or other unique identifier.', 'pmxi_plugin' )?></label><br>
								<div class="switcher-target-auto_matching_parent"  style="padding-left:17px;">									
									<p class="form-field">
										<label style="width:185px;"><?php _e("SKU element for parent", "pmxi_plugin"); ?></label> 
										<input type="text" class="short" placeholder="" name="single_product_id" value="<?php echo esc_attr($post['single_product_id']) ?>"/>
										<a href="#help" class="help" title="<?php _e('SKU column in the below example.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
									</p>
									<p class="form-field">
										<label style="width:185px;"><?php _e("Parent SKU element for variation", "pmxi_plugin"); ?></label>
										<input type="text" class="short" placeholder="" name="single_product_parent_id" value="<?php echo esc_attr($post['single_product_parent_id']) ?>"/>
										<a href="#help" class="help" title="<?php _e('Parent SKU column in the below example.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
									</p>
									<p><strong><?php _e("Example Data For Use With This Option","pmxi_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2013/12/data-example-1.csv" tatger="_blank"><?php _e("download","pmxi_plugin");?></a></p>
									<img src="<?php echo PMWI_FREE_ROOT_URL; ?>/static/img/data-example-1.png"/>
									<p class="highlight">
										<strong><?php _e("Important: Your Unique Key must be unique for each variation.","pmxi_plugin");?></strong> <a href="#help" class="help" title="<?php _e('If each variation doesn’t have a unique ID/SKU, it is recommended that you construct your Unique Key using your variation attribute values. You can set your Unique Key at the top of the Record Matching section below.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
									</p>
									<?php if ( ! $id ): ?>
									<p style="text-align:center;"><a href="javascript:void(0);" class="auto_generate_unique_key"><?php _e("Auto-Append Variation Attributes To Unique Key", "pmxi_plugin");?></a></p>																			
									<?php endif; ?>
								</div>
								<div class="clear" style="margin-top:5px;"></div>
								<input type="radio" id="auto_matching_parent_first_is_parent_id" class="switcher" name="matching_parent" value="first_is_parent_id" <?php echo 'first_is_parent_id' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="auto_matching_parent_first_is_parent_id" style="width:95%"><?php _e('All products with variations are grouped with a unique identifier that is the same for each variation and unique for each product.', 'pmxi_plugin' )?></label><br>
								<div class="switcher-target-auto_matching_parent_first_is_parent_id"  style="padding-left:17px;">									
									<p class="form-field">
										<label style="width:95px;"><?php _e("Unique Identifier", "pmxi_plugin"); ?></label> 
										<input type="text" class="short" placeholder="" name="single_product_id_first_is_parent_id" value="<?php echo esc_attr($post['single_product_id_first_is_parent_id']) ?>"/>
										<a href="#help" class="help" title="<?php _e('Group ID column in the below example.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
									</p>										
									<p><strong><?php _e("Example Data For Use With This Option","pmxi_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2013/12/data-example-2.csv" tatger="_blank"><?php _e("download","pmxi_plugin");?></a></p>
									<img src="<?php echo PMWI_FREE_ROOT_URL; ?>/static/img/data-example-2.png"/>
									<p class="highlight"><strong><?php _e("Important: Your Unique Key must be unique for each variation.","pmxi_plugin");?></strong> <a href="#help" class="help" title="<?php _e('If each variation doesn’t have a unique ID/SKU, it is recommended that you construct your Unique Key using your variation attribute values. You can set your Unique Key at the top of the Record Matching section below.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a></p>
									<?php if ( ! $id ): ?>
									<p style="text-align:center;"><a href="javascript:void(0);" class="auto_generate_unique_key"><?php _e("Auto-Append Variation Attributes To Unique Key", "pmxi_plugin");?></a></p>																			
									<?php endif; ?>
								</div>
								<div class="clear" style="margin-top:5px;"></div>
								<input type="radio" id="auto_matching_parent_first_is_parent_title" class="switcher" name="matching_parent" value="first_is_parent_title" <?php echo 'first_is_parent_title' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="auto_matching_parent_first_is_parent_title"><?php _e('All variations for a particular product have the same title as the parent product.', 'pmxi_plugin' )?></label><br>
								<div class="switcher-target-auto_matching_parent_first_is_parent_title"  style="padding-left:17px;">									
									<p class="form-field">
										<label style="width:75px;"><?php _e("Product Title", "pmxi_plugin"); ?></label> 
										<input type="text" class="short" placeholder="" name="single_product_id_first_is_parent_title" value="<?php echo ($post['single_product_id_first_is_parent_title']) ? esc_attr($post['single_product_id_first_is_parent_title']) : ((!empty(PMXI_Plugin::$session->data['pmxi_import']['template']['title'])) ? esc_attr(PMXI_Plugin::$session->data['pmxi_import']['template']['title']) : ''); ?>"/>
									</p>
									<p><strong><?php _e("Example Data For Use With This Option","pmxi_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2013/12/data-example-3.csv" tatger="_blank"><?php _e("download","pmxi_plugin");?></a></p>
									<img src="<?php echo PMWI_FREE_ROOT_URL; ?>/static/img/data-example-3.png"/>
									<p class="highlight"><strong><?php _e("Important: Your Unique Key must be unique for each variation.","pmxi_plugin");?></strong> <a href="#help" class="help" title="<?php _e('If each variation doesn’t have a unique ID/SKU, it is recommended that you construct your Unique Key using your variation attribute values. You can set your Unique Key at the top of the Record Matching section below.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a></p>
									<?php if ( ! $id ): ?>
									<p style="text-align:center;"><a href="javascript:void(0);" class="auto_generate_unique_key"><?php _e("Auto-Append Variation Attributes To Unique Key", "pmxi_plugin");?></a></p>																			
									<?php endif; ?>
								</div>	
								<div class="clear" style="margin-top:5px;"></div>
								<input type="radio" id="auto_matching_parent_first_is_variation" class="switcher" name="matching_parent" value="first_is_variation" <?php echo 'first_is_variation' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="auto_matching_parent_first_is_variation"><?php _e('All variations for a particular product have the same title. There are no parent products.', 'pmxi_plugin' )?></label><br>
								<div class="switcher-target-auto_matching_parent_first_is_variation"  style="padding-left:17px;">									
									<p class="form-field">
										<label style="width:75px;"><?php _e("Product Title"); ?></label> 
										<input type="text" class="short" placeholder="" name="single_product_id_first_is_variation" value="<?php echo ($post['single_product_id_first_is_variation']) ? esc_attr($post['single_product_id_first_is_variation']) : ((!empty(PMXI_Plugin::$session->data['pmxi_import']['template']['title'])) ? esc_attr(PMXI_Plugin::$session->data['pmxi_import']['template']['title']) : ''); ?>"/>
									</p>
									<p><strong><?php _e("Example Data For Use With This Option","pmxi_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2013/12/data-example-4.csv" tatger="_blank"><?php _e("download","pmxi_plugin");?></a></p>
									<img src="<?php echo PMWI_FREE_ROOT_URL; ?>/static/img/data-example-4.png"/>
									<p class="highlight"><strong><?php _e("Important: Your Unique Key must be unique for each variation.","pmxi_plugin");?></strong> <a href="#help" class="help" title="<?php _e('If each variation doesn’t have a unique ID/SKU, it is recommended that you construct your Unique Key using your variation attribute values. You can set your Unique Key at the top of the Record Matching section below.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a></p>
									<?php if ( ! $id ): ?>
									<p style="text-align:center;"><a href="javascript:void(0);" class="auto_generate_unique_key"><?php _e("Auto-Append Variation Attributes To Unique Key", "pmxi_plugin");?></a></p>																			
									<?php endif; ?>
								</div>																
								<div class="clear" style="margin-top:5px;"></div>

								<input type="radio" id="xml_matching_parent" class="switcher" name="matching_parent" value="xml" <?php echo 'xml' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="xml_matching_parent"><?php _e('I\'m importing XML and my variations are child XML elements', 'pmxi_plugin' )?> </label>
								<a href="#help" class="help" title="<?php _e('This allows you to set variations that are stored as children XML elements.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
								<div class="switcher-target-xml_matching_parent" style="padding-left:17px; position:relative;">
									<div class="input">
										<p class="form-field"><a href="http://youtu.be/7xL4RGT-JRc?t=1m40s" target="_blank"><?php _e("Video Example", "pmxi_plugin");?></a></p>
										<p class="form-field">
											<label style="width:150px;"><?php _e("Variations XPath", "pmxi_plugin"); ?></label>
											<input type="text" class="short" placeholder="" id="variations_xpath" name="variations_xpath" value="<?php echo esc_attr($post['variations_xpath']) ?>" style="width:370px !important;"/> <a href="javascript:void(0);" id="toggle_xml_tree">Open XML Tree</a>
											<div id="variations_console"></div>
										</p>
										<p class="form-field">
											<label style="border-right:none;" for="_variable_virtual"><?php _e('Virtual', 'woocommerce');?> </label>
											<input type="checkbox" name="_variable_virtual" id="_variable_virtual" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['_variable_virtual']) ? 'checked="checked"' : ''; ?>>
											<label for="_variable_downloadable" class="show_if_simple"><?php _e('Downloadable','woocommerce');?></label>
											<input type="checkbox" name="_variable_downloadable" id="_variable_downloadable" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['_variable_downloadable']) ? 'checked="checked"' : ''; ?>>
										</p>
										<div style="margin-right:2%;">

											<div class="options_group">
												<p class="form-field">
													<label style="width:150px;"><?php _e('SKU','woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_sku']) ?>" style="" name="variable_sku" class="short">
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('Stock Qty', 'woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_stock']) ?>" style="" name="variable_stock" class="short">
													<a href="#help" class="help" title="<?php _e('Enter a quantity to enable stock management at variation level, oe leave blank to use parent product\'s options', 'pmxi_plugin') ?>" style="position:relative; top:0px;">?</a>
													<span class="use_parent">
														<input type="hidden" name="variable_stock_use_parent" value="0"/>
														<input type="checkbox" name="variable_stock_use_parent" id="variable_stock_use_parent" style="position:relative; top:6px; margin-left:5px;" <?php echo ($post['variable_stock_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_stock_use_parent" style="top:2px;">XPath Is From Parent</label>
														<a href="#help" class="help" title="<?php _e('Enable this checkbox to determine XPath from parent element.', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
													</span>
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('Image','woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_image']) ?>" style="" name="variable_image" class="short">
													<span class="use_parent">
														<input type="hidden" name="variable_image_use_parent" value="0"/>
														<input type="checkbox" name="variable_image_use_parent" id="variable_image_use_parent" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['variable_image_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_image_use_parent" style="top:0px;">XPath Is From Parent</label>
													</span>
												</p>
											</div>
											<div class="options_group">
												<p class="form-field">
													<label style="width:150px;"><?php _e('Regular Price','woocommerce');?> (<?php echo get_woocommerce_currency_symbol(); ?>)</label>
													<input type="text" value="<?php echo esc_attr($post['variable_regular_price']) ?>" style="" name="variable_regular_price" class="short">
													<span class="use_parent">
														<input type="hidden" name="variable_regular_price_use_parent" value="0"/>
														<input type="checkbox" name="variable_regular_price_use_parent" id="variable_regular_price_use_parent" style="position:relative; top:6px; margin-left:5px;" <?php echo ($post['variable_regular_price_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_regular_price_use_parent" style="top:0px;">XPath Is From Parent</label>
													</span>
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('Sale Price','woocommerce');?> (<?php echo get_woocommerce_currency_symbol(); ?>)</label>&nbsp;
													<a id="variable_sale_price_shedule" href="javascript:void(0);" style="<?php if ($post['is_variable_sale_price_shedule']):?>display:none;<?php endif; ?>position:relative; top:-10px;"><?php _e('schedule');?></a>
													<input type="text" value="<?php echo esc_attr($post['variable_sale_price']) ?>" style="" name="variable_sale_price" class="short">
													<input type="hidden" name="is_variable_sale_price_shedule" value="<?php echo esc_attr($post['is_variable_sale_price_shedule']) ?>"/>
													<span class="use_parent">
														<input type="hidden" name="variable_sale_price_use_parent" value="0"/>
														<input type="checkbox" name="variable_sale_price_use_parent" id="variable_sale_price_use_parent" style="position:relative; top:3px; margin-left:5px;" <?php echo ($post['variable_sale_price_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_sale_price_use_parent">XPath Is From Parent</label>
													</span>
												</p>
												<?php if ( class_exists('woocommerce_wholesale_pricing') ):?>
												<p class="form-field">
													<label style="width:150px;"><?php _e("Wholesale Price (".get_woocommerce_currency_symbol().")"); ?></label>
													<input type="text" class="short" name="variable_whosale_price" value="<?php echo esc_attr($post['variable_whosale_price']) ?>"/>								
													<span class="use_parent">
														<input type="hidden" name="variable_whosale_price_use_parent" value="0"/>
														<input type="checkbox" name="variable_whosale_price_use_parent" id="variable_whosale_price_use_parent" style="position:relative; top:3px; margin-left:5px;" <?php echo ($post['variable_whosale_price_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_whosale_price_use_parent">XPath Is From Parent</label>
													</span>
												</p>
												<?php endif; ?>
											</div>
											<div class="options_group" <?php if ( ! $post['is_variable_sale_price_shedule']):?>style="display:none;"<?php endif; ?> id="variable_sale_price_range">
												<p class="form-field">
													<span style="vertical-align:middle">
														<label style="width:150px;"><?php _e("Variable Sale Price Dates", "woocommerce"); ?></label>
														<span class="use_parent">
															<input type="hidden" name="variable_sale_dates_use_parent" value="0"/>
															<input type="checkbox" name="variable_sale_dates_use_parent" id="variable_sale_dates_use_parent" style="position:relative; top:4px; margin-left:5px;" <?php echo ($post['variable_sale_dates_use_parent']) ? 'checked="checked"' : ''; ?>>
															<label for="variable_sale_dates_use_parent">XPath Is From Parent</label>
														</span>
														<br>
														<input type="text" class="datepicker" name="variable_sale_price_dates_from" value="<?php echo esc_attr($post['variable_sale_price_dates_from']) ?>" style="float:none;"/>
														<?php _e('and', 'pmxi_plugin') ?>
														<input type="text" class="datepicker" name="variable_sale_price_dates_to" value="<?php echo esc_attr($post['variable_sale_price_dates_to']) ?>" style="float:none;"/>
														&nbsp;<a id="cancel_variable_regular_price_shedule" href="javascript:void(0);"><?php _e('cancel');?></a>
													</span>
													
												</p>
											</div>
											<div class="options_group"  <?php echo ( ! $post['_variable_virtual']) ? 'style="display:none;"' : ''; ?> id="variable_virtual">
												<div class="input" style="padding-left:0px;">
													<div class="input fleft">
														<input type="radio" id="is_variable_product_virtual_yes" class="switcher" name="is_variable_product_virtual" value="yes" <?php echo 'yes' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_virtual_yes"><?php _e("Virtual"); ?></label>
													</div>
													<div class="input fleft">
														<input type="radio" id="is_variable_product_virtual_no" class="switcher" name="is_variable_product_virtual" value="no" <?php echo 'no' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_virtual_no"><?php _e("Not Virtual"); ?></label>
													</div>
													<div class="input fleft" style="position:relative;width:220px;">
														<input type="radio" id="is_variable_product_virtual_xpath" class="switcher" name="is_variable_product_virtual" value="xpath" <?php echo 'xpath' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_virtual_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
														<div class="switcher-target-is_variable_product_virtual_xpath set_with_xpath" style="width:300px;">
															<div class="input" style="width:365px;">
																&nbsp;<input type="text" class="smaller-text" name="single_variable_product_virtual" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_virtual']) ?>"/>
																<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
																<span class="use_parent" style="float:right;">
																	<input type="hidden" name="single_variable_product_virtual_use_parent" value="0"/>
																	<input type="checkbox" name="single_variable_product_virtual_use_parent" id="single_variable_product_virtual_use_parent" style="position:relative; top:4px; margin-left:5px;" <?php echo ($post['single_variable_product_virtual_use_parent']) ? 'checked="checked"' : ''; ?>>
																	<label for="single_variable_product_virtual_use_parent" style="top:3px;">XPath Is From Parent</label>
																</span>
															</div>															
														</div>
													</div>
												</div>
											</div>
											<div class="options_group" <?php echo ($post['_variable_virtual']) ? 'style="display:none;"' : ''; ?> id="variable_dimensions">
												<p class="form-field">
													<label style="width:150px;"><?php _e('Weight','woocommerce');?></label>
													<input type="text" placeholder="0.00" value="<?php echo esc_attr($post['variable_weight']) ?>" style="" name="variable_weight" class="short">
													<span class="use_parent">
														<input type="hidden" name="variable_weight_use_parent" value="0"/>
														<input type="checkbox" name="variable_weight_use_parent" id="variable_weight_use_parent" style="position:relative; top:6px; margin-left:5px;" <?php echo ($post['variable_weight_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_weight_use_parent">XPath Is From Parent</label>
													</span>
												</p>
												<p class="form-field">
													<label for"product_length"=""><?php _e('Dimensions (L×W×H)','woocommerce');?></label>
													<span class="use_parent">
														<input type="hidden" name="variable_dimensions_use_parent" value="0"/>
														<input type="checkbox" name="variable_dimensions_use_parent" id="variable_dimensions_use_parent" style="position:relative; top:3px; margin-left:5px;" <?php echo ($post['variable_dimensions_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_dimensions_use_parent">XPath Is From Parent</label>
													</span>
													<br>
													<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_length']) ?>" name="variable_length" class="short" style="margin-right:5px;">
													<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_width']) ?>" name="variable_width" class="short" style="margin-right:5px;">
													<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_height']) ?>" style="" name="variable_height" class="short">
													
												</p>
											</div>
											<div class="options_group">
												<p class="form-field">
													<div class="input">
														<div class="main_choise">
															<input type="radio" id="multiple_variable_product_shipping_class_yes" class="switcher" name="is_multiple_variable_product_shipping_class" value="yes" <?php echo 'no' != $post['is_multiple_variable_product_shipping_class'] ? 'checked="checked"': '' ?>/>
															<label for="multiple_variable_product_shipping_class_yes" style="width:150px;"><?php _e("Shipping Class"); ?></label>
														</div>
														<div class="switcher-target-multiple_variable_product_shipping_class_yes"  style="padding-left:17px;">
															<div class="input">
																<?php
																	$classes = get_the_terms( 0, 'product_shipping_class' );
																	if ( $classes && ! is_wp_error( $classes ) ) $current_shipping_class = current($classes)->term_id; else $current_shipping_class = '';

																	$args = array(
																		'taxonomy' 			=> 'product_shipping_class',
																		'hide_empty'		=> 0,
																		'show_option_none' 	=> __( 'No shipping class', 'woocommerce' ),
																		'name' 				=> 'multiple_variable_product_shipping_class',
																		'id'				=> 'multiple_variable_product_shipping_class',
																		'selected'			=> $current_shipping_class,
																		'class'				=> 'select short'
																	);

																	wp_dropdown_categories( $args );
																?>
															</div>															
														</div>
													</div>
													<div class="input">
														<div class="main_choise">
															<input type="radio" id="multiple_variable_product_shipping_class_no" class="switcher" name="is_multiple_variable_product_shipping_class" value="no" <?php echo 'no' == $post['is_multiple_variable_product_shipping_class'] ? 'checked="checked"': '' ?>/>
															<label for="multiple_variable_product_shipping_class_no"><?php _e('Set product shipping class with XPath', 'pmxi_plugin' )?></label>
														</div>
														<div class="switcher-target-multiple_variable_product_shipping_class_no"  style="padding-left:17px;">
															<div class="input">
																<input type="text" class="smaller-text" name="single_variable_product_shipping_class" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_shipping_class']) ?>"/>
																<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'taxable\', \'shipping\', \'none\').', 'pmxi_plugin') ?>">?</a>
																<span class="use_parent">
																	<input type="hidden" name="single_variable_product_shipping_class_use_parent" value="0"/>
																	<input type="checkbox" name="single_variable_product_shipping_class_use_parent" id="single_variable_product_shipping_class_use_parent" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['single_variable_product_shipping_class_use_parent']) ? 'checked="checked"' : ''; ?>>
																	<label for="single_variable_product_shipping_class_use_parent" style="top:0px;">XPath Is From Parent</label>
																</span>
															</div>															
														</div>
													</div>
												</p>
												<p class="form-field">
													<div class="input">
														<div class="main_choise">
															<input type="radio" id="multiple_variable_product_tax_class_yes" class="switcher" name="is_multiple_variable_product_tax_class" value="yes" <?php echo 'no' != $post['is_multiple_variable_product_tax_class'] ? 'checked="checked"': '' ?>/>
															<label for="multiple_variable_product_tax_class_yes" style="width:150px;"><?php _e("Tax Class", "woocommerce"); ?></label>
														</div>
														<div class="switcher-target-multiple_variable_product_tax_class_yes"  style="padding-left:17px;">
															<div class="input">
																<select class="select short" name="multiple_variable_product_tax_class">
																	<option value="parent" <?php echo 'parent' == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Same as parent', 'woocommerce');?></option>
																	<option value="" <?php echo '' == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Standard', 'woocommerce');?></option>
																	<option value="reduced-rate" <?php echo 'reduced-rate' == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Reduced Rate', 'woocommerce');?></option>
																	<option value="zero-rate" <?php echo 'zero-rate' == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Zero Rate', 'woocommerce');?></option>
																</select>
															</div>
														</div>
													</div>
													<div class="input">
														<div class="main_choise">
															<input type="radio" id="multiple_variable_product_tax_class_no" class="switcher" name="is_multiple_variable_product_tax_class" value="no" <?php echo 'no' == $post['is_multiple_variable_product_tax_class'] ? 'checked="checked"': '' ?>/>
															<label for="multiple_variable_product_tax_class_no"><?php _e('Set product tax class with XPath', 'pmxi_plugin' )?></label>
														</div>
														<div class="switcher-target-multiple_variable_product_tax_class_no"  style="padding-left:17px;">
															<div class="input">
																<input type="text" class="smaller-text" name="single_variable_product_tax_class" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_tax_class']) ?>"/>
																<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'reduced-rate\', \'zero-rate\').', 'pmxi_plugin') ?>">?</a>
																<span class="use_parent">
																	<input type="hidden" name="single_variable_product_tax_class_use_parent" value="0"/>
																	<input type="checkbox" name="single_variable_product_tax_class_use_parent" id="single_variable_product_tax_class_use_parent" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['single_variable_product_tax_class_use_parent']) ? 'checked="checked"' : ''; ?>>
																	<label for="single_variable_product_tax_class_use_parent" style="top:0px;">XPath Is From Parent</label>
																</span>
															</div>															
														</div>
													</div>
												</p>
											</div>
											<div class="options_group" <?php echo ( ! $post['_variable_downloadable']) ? 'style="display:none;"' : ''; ?> id="variable_downloadable">
												<p class="form-field">
													<div class="input fleft">
														<input type="radio" id="is_variable_product_downloadable_yes" class="switcher" name="is_variable_product_downloadable" value="yes" <?php echo 'yes' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_downloadable_yes"><?php _e("Downloadable"); ?></label>
													</div>
													<div class="input fleft">
														<input type="radio" id="is_variable_product_downloadable_no" class="switcher" name="is_variable_product_downloadable" value="no" <?php echo 'no' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_downloadable_no"><?php _e("Not Downloadable"); ?></label>
													</div>
													<div class="input fleft" style="position:relative; width:220px;">
														<input type="radio" id="is_variable_product_downloadable_xpath" class="switcher" name="is_variable_product_downloadable" value="xpath" <?php echo 'xpath' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
														<label for="is_variable_product_downloadable_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
														<div class="switcher-target-is_variable_product_downloadable_xpath set_with_xpath" style="width:300px;">
															<div class="input">
																<input type="text" class="smaller-text" name="single_variable_product_downloadable" style="width:345px;" value="<?php echo esc_attr($post['single_variable_product_downloadable']) ?>"/>
																<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
																<span class="use_parent">
																	<input type="hidden" name="single_variable_product_downloadable_use_parent" value="0"/>
																	<input type="checkbox" name="single_variable_product_downloadable_use_parent" id="single_variable_product_downloadable_use_parent" style="position:relative; top:4px; margin-left:5px;" <?php echo ($post['single_variable_product_downloadable_use_parent']) ? 'checked="checked"' : ''; ?>>
																	<label for="single_variable_product_downloadable_use_parent" style="top:2px;">XPath Is From Parent</label>
																</span>
															</div>															
														</div>
													</div>
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('File paths','woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_file_paths']) ?>" name="variable_file_paths" class="short" style="width:60% !important;">
													<input type="text" class="small" name="variable_product_files_delim" value="<?php echo esc_attr($post['variable_product_files_delim']) ?>" style="width:5% !important;text-align:center; margin-left:5px;"/>
													<a href="#help" class="help" title="<?php _e('File paths/URLs, comma separated. The delimiter option uses when xml element contains few paths/URLs (http://files.com/1.doc, http://files.com/2.doc).', 'pmxi_plugin') ?>">?</a>
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('Download Limit','woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_download_limit']) ?>" style="" name="variable_download_limit" class="short">
													<span class="use_parent">
														<input type="hidden" name="variable_download_limit_use_parent" value="0"/>
														<input type="checkbox" name="variable_download_limit_use_parent" id="variable_download_limit_use_parent" style="position:relative; top:4px; margin-left:5px;" <?php echo ($post['variable_download_limit_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_download_limit_use_parent">XPath Is From Parent</label>
													</span>
												</p>
												<p class="form-field">
													<label style="width:150px;"><?php _e('Download Expiry','woocommerce');?></label>
													<input type="text" value="<?php echo esc_attr($post['variable_download_expiry']) ?>" style="" name="variable_download_expiry" class="short">
													<span class="use_parent">
														<input type="hidden" name="variable_download_expiry_use_parent" value="0"/>
														<input type="checkbox" name="variable_download_expiry_use_parent" id="variable_download_expiry_use_parent" style="position:relative; top:4px; margin-left:5px;" <?php echo ($post['variable_download_expiry_use_parent']) ? 'checked="checked"' : ''; ?>>
														<label for="variable_download_expiry_use_parent">XPath Is From Parent</label>
													</span>
												</p>
											</div>

											<div class="options_group">

												<p class="form-field"><?php _e('Variation Enabled','pmxi_plugin');?></p>
												<div class="input" style="margin-top:-10px;">
													<div class="input fleft">
														<input type="radio" id="variable_product_enabled_yes" class="switcher" name="is_variable_product_enabled" value="yes" <?php echo 'yes' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
														<label for="variable_product_enabled_yes"><?php _e("Yes"); ?></label>
													</div>
													<div class="input fleft">
														<input type="radio" id="variable_product_enabled_no" class="switcher" name="is_variable_product_enabled" value="no" <?php echo 'no' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
														<label for="variable_product_enabled_no"><?php _e("No"); ?></label>
													</div>
													<div class="input fleft" style="position:relative; width:220px;">
														<input type="radio" id="variable_product_enabled_xpath" class="switcher" name="is_variable_product_enabled" value="xpath" <?php echo 'xpath' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
														<label for="variable_product_enabled_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
														<div class="switcher-target-variable_product_enabled_xpath set_with_xpath">
															<div class="input">
																<input type="text" class="smaller-text" name="single_variable_product_enabled" style="width:300px; " value="<?php echo esc_attr($post['single_variable_product_enabled']) ?>"/>
																<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
															</div>
														</div>
													</div>
												</div>												

											</div>

											<div class="options_group variation_attributes">
												<p class="form-field">
													<label style="width:150px; padding-left:0px;"><?php _e('Variation Attributes','pmxi_plugin');?></label>
												</p>
												<div class="input">
													<table class="form-table custom-params" style="max-width:95%;">
														<thead>
															<tr>
																<td><?php _e('Name', 'pmxi_plugin') ?></td>
																<td><?php _e('Values', 'pmxi_plugin') ?></td>
																<td></td>
															</tr>
														</thead>
														<tbody>
															<?php if (!empty($post['variable_attribute_name'][0])):?>
																<?php foreach ($post['variable_attribute_name'] as $i => $name): if ("" == $name) continue; ?>
																	<tr class="form-field">
																		<td><input type="text" name="variable_attribute_name[]"  value="<?php echo esc_attr($name) ?>" style="width:95% !important;"/></td>
																		<td><textarea name="variable_attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."><?php echo str_replace("&amp;","&", htmlentities(htmlentities($post['variable_attribute_value'][$i]))); ?></textarea>
																		<br>
																		<span class='in_variations' style="margin-left:0px;">
																			<input type="checkbox" name="variable_in_variations[]" id="variable_in_variations_<?php echo $i; ?>" <?php echo ($post['variable_in_variations'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																			<label for="variable_in_variations_<?php echo $i; ?>"><?php _e('In Variations','pmxi_plugin');?></label>															
																		</span>

																		<span class='is_visible'>
																			<input type="checkbox" name="variable_is_visible[]" id="variable_is_visible_<?php echo $i; ?>" <?php echo ($post['variable_is_visible'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																			<label for="variable_is_visible_<?php echo $i; ?>"><?php _e('Is Visible','pmxi_plugin');?></label>																		
																		</span>

																		<span class='is_taxonomy'>
																			<input type="checkbox" name="variable_is_taxonomy[]" id="variable_is_taxonomy_<?php echo $i; ?>" <?php echo ($post['variable_is_taxonomy'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																			<label for="variable_is_taxonomy_<?php echo $i; ?>"><?php _e('Taxonomy','pmxi_plugin');?></label>																	
																		</span>

																		<span class='is_create_taxonomy'>
																			<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" id="variable_create_taxonomy_in_not_exists_<?php echo $i;?>" <?php echo ($post['variable_create_taxonomy_in_not_exists'][$i]) ? 'checked="checked"' : ''; ?> style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																			<label for="variable_create_taxonomy_in_not_exists_<?php echo $i; ?>"><?php _e('Auto-Create Terms','pmxi_plugin');?></label>
																		</span>
																		
																		<!--a href="#help" class="help" title="<?php _e('Enable &#39;is taxonomy&#39; and an Attribute on the Products -> Attributes page of WooCommerce will be added or an existing Attribute of the same name will be used. Check this box to auto-create attribute values, if they haven&#39;t already been set up.', 'pmxi_plugin') ?>" style="position:relative; top:-5px; left:24px;">?</a-->

																		</td>
																		<td class="action remove"><a href="#remove"></a></td>
																	</tr>
																<?php endforeach ?>
															<?php else: ?>
															<tr class="form-field">
																<td><input type="text" name="variable_attribute_name[]" value="" style="width:95% !important;"/></td>
																<td><textarea name="variable_attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."></textarea>
																<br>
																<span class='in_variations' style="margin-left:0px;">
																	<input type="checkbox" name="variable_in_variations[]" id="variable_in_variations_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for="variable_in_variations_0"><?php _e('In Variations','pmxi_plugin');?></label>																	
																</span>
																<span class='is_visible'>
																	<input type="checkbox" name="variable_is_visible[]" id="variable_is_visible_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for="variable_is_visible_0"><?php _e('Is Visible','pmxi_plugin');?></label>																								
																</span>
																<span class='is_taxonomy'>
																	<input type="checkbox" name="variable_is_taxonomy[]" id="variable_is_taxonomy_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for="variable_is_taxonomy_0"><?php _e('Taxonomy','pmxi_plugin');?></label>																	
																</span>
																<span class='is_create_taxonomy'>
																	<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" id="variable_create_taxonomy_in_not_exists_0" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for="variable_create_taxonomy_in_not_exists_0"><?php _e('Auto-Create Terms','pmxi_plugin');?></label>
																</span>																
																<td class="action remove"><a href="#remove"></a></td>
															</tr>
															<?php endif;?>
															<tr class="form-field template">
																<td><input type="text" name="variable_attribute_name[]" value="" style="width:95% !important;"/></td>
																<td><textarea name="variable_attribute_value[]" placeholder="Enter some text, or some attributes by pipe (|) separating values."></textarea>
																<br>
																<span class='in_variations' style="margin-left:0px;">
																	<input type="checkbox" name="variable_in_variations[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for=""><?php _e('In Variations','pmxi_plugin');?></label>																	
																</span>
																<span class='is_visible'>
																	<input type="checkbox" name="variable_is_visible[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for=""><?php _e('Is Visible','pmxi_plugin');?></label>	
																</span>
																<span class='is_taxonomy'>
																	<input type="checkbox" name="variable_is_taxonomy[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for=""><?php _e('Taxonomy','pmxi_plugin');?></label>
																</span>
																<span class='is_create_taxonomy'>
																	<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" checked="checked" style="width: auto; position: relative; top: 1px; left: 0px;" value="1"/>
																	<label for=""><?php _e('Auto-Create Terms','pmxi_plugin');?></label>
																</span>	
																<td class="action remove"><a href="#remove"></a></td>
															</tr>
															<tr>
																<td colspan="3"><a href="#add" title="<?php _e('add', 'pmxi_plugin')?>" class="action add-new-custom"><?php _e('Add more', 'pmxi_plugin') ?></a></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>										
										<div id="variations_tag" class="options_group show_if_variable">
											<a href="javascript:void(0)" id="close_xml_tree"></a>
											<div class="variations_tree">													
												<div id="variations_xml">
													<div class="variations_tag">
														<input type="hidden" name="variations_tagno" value="<?php echo $tagno ?>" />
														<div class="title">
															<?php printf(__('No matching elements found for XPath expression specified', 'pmxi_plugin'), $tagno, $variation_list_count); ?>
														</div>
														<div class="clear"></div>
														<div class="xml resetable"></div>
													</div>
												</div>
											</div>
										</div>										
									</div>
								</div>
								
								<div class="clear" style="margin-top:5px;"></div>

								<input type="radio" id="manual_matching_parent" class="switcher" name="matching_parent" value="manual" <?php echo 'manual' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
								<label for="manual_matching_parent"><?php _e('I\'m an advanced user and will match my variations with my parent products manually. (Advanced)', 'pmxi_plugin' )?></label>
								<a href="#help" class="help" title="<?php _e('This allows you to match products as you would in the Manual Record Matching section of WP All Import.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
								<div class="switcher-target-manual_matching_parent" style="padding-left:17px;">
									<div class="input">
										<input type="radio" id="duplicate_indicator_title_parent" class="switcher" name="parent_indicator" value="title" <?php echo 'title' == $post['parent_indicator'] ? 'checked="checked"': '' ?>/>
										<label for="duplicate_indicator_title_parent"><?php _e('title', 'pmxi_plugin' )?>&nbsp;</label>
										<input type="radio" id="duplicate_indicator_content_parent" class="switcher" name="parent_indicator" value="content" <?php echo 'content' == $post['parent_indicator'] ? 'checked="checked"': '' ?>/>
										<label for="duplicate_indicator_content_parent"><?php _e('content', 'pmxi_plugin' )?>&nbsp;</label>
										<input type="radio" id="duplicate_indicator_custom_field_parent" class="switcher" name="parent_indicator" value="custom field" <?php echo 'custom field' == $post['parent_indicator'] ? 'checked="checked"': '' ?>/>
										<label for="duplicate_indicator_custom_field_parent"><?php _e('custom field', 'pmxi_plugin' )?></label><br>
										<span class="switcher-target-duplicate_indicator_custom_field_parent" style="vertical-align:middle" style="padding-left:17px;">
											<?php _e('Name', 'pmxi_plugin') ?>
											<input type="text" name="custom_parent_indicator_name" value="<?php echo esc_attr($post['custom_parent_indicator_name']) ?>" style="float:none; margin:1px;" /><br>
											<?php _e('Value', 'pmxi_plugin') ?>
											<input type="text" name="custom_parent_indicator_value" value="<?php echo esc_attr($post['custom_parent_indicator_value']) ?>" style="float:none; margin:1px; margin-left:3px;" />
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="options_group">
							<p class="form-field"><?php _e('Variable Enabled','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="product_enabled_yes" class="switcher" name="is_product_enabled" value="yes" <?php echo 'yes' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
									<label for="product_enabled_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="product_enabled_no" class="switcher" name="is_product_enabled" value="no" <?php echo 'no' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
									<label for="product_enabled_no"><?php _e("No"); ?></label>
								</div>
								<div class="input fleft" style="position:relative; width:220px;">
									<input type="radio" id="product_enabled_xpath" class="switcher" name="is_product_enabled" value="xpath" <?php echo 'xpath' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
									<label for="product_enabled_xpath"><?php _e('Set with XPath', 'pmxi_plugin' )?></label><br>
									<div class="switcher-target-product_enabled_xpath set_with_xpath">
										<div class="input">
											<input type="text" class="smaller-text" name="single_product_enabled" style="width:300px;" value="<?php echo esc_attr($post['single_product_enabled']) ?>"/>
											<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
										</div>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<p class="form-field"><?php _e('Automaticaly set default selections','pmxi_plugin');?></p>
							<div class="input" style="margin-top:-10px;">
								<div class="input fleft">
									<input type="radio" id="set_default_yes" name="is_default_attributes" value="1" <?php echo $post['is_default_attributes'] ? 'checked="checked"': '' ?>/>
									<label for="set_default_yes"><?php _e("Yes"); ?></label>
								</div>
								<div class="input fleft">
									<input type="radio" id="set_default_no" name="is_default_attributes" value="0" <?php echo ! $post['is_default_attributes'] ? 'checked="checked"': '' ?>/>
									<label for="set_default_no"><?php _e("No"); ?></label>
								</div>													
							</div>
						</div>
					</div><!-- End Product Panel -->

					<?php do_action('pmwi_tab_content'); ?>

					<!-- OPTIONS -->

					<div class="panel woocommerce_options_panel" id="add_on_options" style="display:none;">
						<p class="upgrade_template" style='display:none; font-size: 1.3em; font-weight: bold;'>
							<a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=wooco&utm_campaign=free+plugin+wooco" target="_blank" class="upgrade_link">Upgrade to the pro version of the WooCommerce Add-On to import to grouped, affiliate/external, and variable products.</a>
						</p>
						<div class="options_group">
							<p class="form-field" style="font-size:16px; font-weight:bold;"><?php _e('Re-import options','pmxi_plugin');?></p>
							<div class="input" style="padding-left:20px;">
								<input type="hidden" name="missing_records_stock_status" value="0" />
								<input type="checkbox" id="missing_records_stock_status" name="missing_records_stock_status" value="1" <?php echo $post['missing_records_stock_status'] ? 'checked="checked"' : '' ?> />
								<label for="missing_records_stock_status"><?php _e('Set out of stock status for missing records', 'pmxi_plugin') ?></label>
								<a href="#help" class="help" title="<?php _e('Option to set the stock status to out of stock instead of deleting the product entirely. This option doesn\'t work when \'Delete missing records\' option is enabled.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
							</div>
							<div class="input" style="padding-left:20px;">
								<input type="hidden" name="disable_auto_sku_generation" value="0" />
								<input type="checkbox" id="disable_auto_sku_generation" name="disable_auto_sku_generation" value="1" <?php echo $post['disable_auto_sku_generation'] ? 'checked="checked"' : '' ?> />
								<label for="disable_auto_sku_generation"><?php _e('Disable auto SKU generation', 'pmxi_plugin') ?></label>
								<a href="#help" class="help" title="<?php _e('Plugin will NOT automaticaly generate the SKU for each product based on md5 algorithm, if SKU option is empty.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
							</div>
							<div class="input" style="padding-left:20px;">
								<input type="hidden" name="disable_sku_matching" value="0" />
								<input type="checkbox" id="disable_sku_matching" name="disable_sku_matching" value="1" <?php echo $post['disable_sku_matching'] ? 'checked="checked"' : '' ?> />
								<label for="disable_sku_matching"><?php _e('Disable SKU matching', 'pmxi_plugin') ?></label>
								<a href="#help" class="help" title="<?php _e('Plugin will NOT search matches for SKU. This option will speed up import process.', 'pmxi_plugin') ?>" style="position:relative; top:-2px;">?</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="clear"></div>

	</td>
</tr>