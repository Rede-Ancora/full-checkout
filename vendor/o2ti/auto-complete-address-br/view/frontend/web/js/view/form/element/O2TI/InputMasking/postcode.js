/**
 * Copyright © O2TI. All rights reserved.
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

define([
	"uiRegistry",
	"Magento_Ui/js/form/element/abstract",
	"jquery",
	"mage/url",
	"mask"
], function (
	registry,
	abstract,
	$,
	url,
	mask
) {
	"use strict";
	return abstract.extend({
		initialize() {
			this._super();
			if(this.maskEnable){
				let typeMask = this.mask;
				let useClearIfNotMatch = this.maskClearIfNotMatch;
				$("#" + this.uid).mask(typeMask,  { clearIfNotMatch: useClearIfNotMatch });
			}
			return this;
		},
		onChange() {
			if(this.value()) {
				if (this.value().replace(/[^\d]/g, "").length === 8) {
					this.getAddressByPostcode();
				}
			}
		},
		onUpdate() {
			if(this.value()) {
				if (this.value().replace(/[^\d]/g, "").length === 8) {
					this.getAddressByPostcode();
				}
			}
		},
		getAddressByPostcode(){
			var element = this;
			var cep = this.value().replace(/[^\d]/g, "");
			var formKey = $.cookie("form_key");
			var getaddress = url.build("autocompleteaddressbr/postcode/address/zipcode/" + cep + "/form_key/" + formKey + "/");
			$.ajax({
				url: getaddress,
				dataType: "json",
				timeout: 4000,
			}).done(function (data) {
				if (data.success) {
					Object.keys(data.street).forEach(function(i) {
						if (registry.get(element.parentName + "." + "street."+i)) {
							registry.get(element.parentName + "." + "street."+i).value(data.street[i]);
						}
					});
					if (registry.get(element.parentName + "." + "city")) {
						registry.get(element.parentName + "." + "city").value(data.city);
					}
					
					if (registry.get(element.parentName + "." + "country_id")) {
						registry.get(element.parentName + "." + "country_id").value(data.country_id);
					}

					if (registry.get(element.parentName + "." + "region_id")) {
						registry.get(element.parentName + "." + "region_id").value(data.region_id);
					}
				}
			});
		}
	});
});