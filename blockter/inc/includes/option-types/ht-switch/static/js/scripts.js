jQuery(document).ready(function ($) {
	var optionTypeClass = 'fw-option-type-ht-switch';

	fwEvents.on('fw:options:init', function(data){
		data.$elements.find('.'+ optionTypeClass +':not(.fw-option-initialized)')
			.addClass('fw-option-initialized')
			.find('input[type="checkbox"]')
			.on('change', function(){
				var $this = $(this),
					checked = $this.prop('checked'),
					value = '';

				if(checked == true){
					value = 'yes';
				}else{
					value = 'no';
				}

				$this.val(value);
			})
	});
});