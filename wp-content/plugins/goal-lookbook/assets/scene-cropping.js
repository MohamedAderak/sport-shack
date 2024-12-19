
/* global variables */

var zoneCurrent = 0;
var selectionCurrent = null;
var valueOfZoneEdited = null;

// Last item is used to save the current zone and 
// allow to replace it if user cancel the editing
var lastEditedItem = null;


// width/height
var sectionWidth = 500;
var sectionHeight = 500;
/* functions called by cropping events */

function editThisZone(aInFixedZoneElement) {
	$fixedZoneElement = jQuery(aInFixedZoneElement).parent();
	
	var x1 = $fixedZoneElement.find('.x1').val();
	x1 = parseInt(x1);
	var y1 = $fixedZoneElement.find('.y1').val();
	y1 = parseInt(y1);

	

	var width = $fixedZoneElement.css('width');	
	width = width.substring(0,width.indexOf('px'));
	var x2 = x1 + parseInt(width);
	var height = $fixedZoneElement.css('height');	
	height = height.substring(0,height.indexOf('px'));
	var y2 = y1 + parseInt(height);

	valueOfZoneEdited = $fixedZoneElement.find('a').attr('rel');
	
	selectionCurrent = new Array();
	selectionCurrent['x1'] = x1;
	selectionCurrent['y1'] = y1;
	selectionCurrent['width'] = width;
	selectionCurrent['height'] = height;
	
	// Save the last zone
	lastEditedItem = $fixedZoneElement;
	
	jQuery('#product_autocomplete_input').val( $fixedZoneElement.find('p').text() );
	showAutocompleteBox(x1, y1+parseInt(height));
	
	jQuery('#large_scene_image').imgAreaSelect({ x1: x1, y1: y1, x2: x2, y2: y2 });
}

/* function called by cropping process (buttons clicks) */

function deleteProduct(index_zone){
	jQuery('#visual_zone_' + index_zone).fadeOut('fast', function(){
		jQuery(this).remove();
	});
	jQuery('#ajax_choose_product').hide();
	return false;
}

function showZone(){
	jQuery('#large_scene_image').imgAreaSelect({show:true});
}

function hideAutocompleteBox(){
	jQuery('#ajax_choose_product')
		.fadeOut('fast')
		.find('#product_autocomplete_input').val('');
}

function onSelectEnd(img, selection) {
	selectionCurrent = selection;
	showAutocompleteBox(selection.x1, selection.y1+selection.height);
}

function undoEdit(){
	hideAutocompleteBox();
	jQuery('#large_scene_image').imgAreaSelect({hide:true});
	jQuery(document).unbind('keydown');
}

/*
** Pointer function do handle event by key released
*/
function handlePressedKey(keyNumber, fct)
{
	// KeyDown isn't handled correctly in editing mode
	jQuery(document).keyup(function(event) 
	{	
	  if (event.keyCode == keyNumber)
		 fct();
	});
}

function showAutocompleteBox(x1, y1) 
{	
	jQuery('#ajax_choose_product:hidden')
	.slideDown('fast');
	jQuery('#product_autocomplete_input').focus();
	handlePressedKey('27', undoEdit);
	jQuery('#ajax_choose_product').css({
		'left': x1 + 'px',
		'top': y1 + 'px',
		'position': 'absolute'
	});
}


function afterTextInserted (event, data) {	
	if (data == null)
		return false;
	// If the element exist, then the user confirm the editing
	// The variable need to be reinitialized to null for the next
	if (lastEditedItem != null)
		lastEditedItem.remove();
	lastEditedItem = null;
	
	sectionWidth = jQuery('#large_scene_image').outerWidth();
	sectionHeight = jQuery('#large_scene_image').outerHeight();

	var spus = renderPercent(selectionCurrent.x1, sectionWidth);
	
	var idProduct = data['slug'];
	var nameProduct = data['label'];
	var postType = data['post_type'];
	// var x1 = selectionCurrent.x1;
	// var y1 = selectionCurrent.y1;
	var x1 = renderPercent(selectionCurrent.x1, sectionWidth);
	var y1 = renderPercent(selectionCurrent.y1, sectionHeight);
	var width = selectionCurrent.width;
	var height = selectionCurrent.height;

	addProduct(zoneCurrent, x1, y1, width, height, idProduct, nameProduct, postType);
	zoneCurrent++;
}

function addProduct(zoneIndex, x1, y1, width, height, idProduct, nameProduct, postType){
	jQuery('#large_scene_image') 
		.imgAreaSelect({hide:true})
		.append('\
			<div class="fixed_zone" id="visual_zone_' + zoneIndex + '" style="color:black;overflow:hidden;position:absolute;left:' + x1 + '%; top:' + y1 + '%; width:' + width + 'px; height :' + height + 'px; background-color:white;border:1px solid black; position:absolute;" title="' + nameProduct + '">\
				<input type="hidden" class="x1" name="zones[' + zoneIndex + '][x1]" value="' + x1 + '"/>\
				<input type="hidden" class="y1" name="zones[' + zoneIndex + '][y1]" value="' + y1 + '"/>\
				<input type="hidden" class="width" name="zones[' + zoneIndex + '][width]" value="' + width + '"/>\
				<input type="hidden" class="height" name="zones[' + zoneIndex + '][height]" value="' + height + '"/>\
				<input type="hidden" name="zones[' + zoneIndex + '][slug]" value="' + idProduct + '"/>\
				<input type="hidden" name="zones[' + zoneIndex + '][post_type]" value="' + postType + '"/>\
				<p style="position:absolute;text-align:center;width:100%;" id="p_zone_' + zoneIndex + '">' + nameProduct + '</p>\
				<a style="margin-left:' + (parseInt(width)/2 - 16) + 'px; margin-top:' + (parseInt(height)/2 - 8) + 'px; position:absolute;" href="#" data-zoneIndex="' + zoneIndex + '" onclick="{deleteProduct(' + zoneIndex + '); return false;}">\
					<img src="' + goal_lookbook_vars.delete_img + '" alt="" />\
				</a>\
			</div>\
		');
	jQuery('.fixed_zone').css('opacity', '0.8');
	//jQuery('#save_scene').fadeIn('slow');
	jQuery('#ajax_choose_product:visible')
		.fadeOut('slow')
		.find('#product_autocomplete_input').val('');
}
/**
* x2: total
* x1: value
*/
function renderPercent(x1, x2) {
	return x1/x2*100;
}

jQuery(document).ready(function($) {
	
	$( "#product_autocomplete_input" ).autocomplete({
      	source: function( request, response ) {
	        $.ajax( {
	          	url: goal_lookbook_vars.ajaxurl,
	          	dataType: "jsonp",
	          	data: {
	            	term: request.term,
	            	type: $('.select_type').val(),
	            	action: 'goal_lookbook_search_product'
	          	},
	          	success: function( data ) {
	            	response( data );
	          	}
	        } );
      	},
      	minLength: 2,
      	select: function( event, ui ) {
        	afterTextInserted(event, ui.item);
      	}
    } );


	$('#large_scene_image').imgAreaSelect({
		borderWidth: 1,
		onSelectEnd: onSelectEnd,
		onSelectStart: showZone,
		onSelectChange: hideAutocompleteBox,
		minHeight:30,
		minWidth:30
	});
	
	/* load existing products zone */
	if ( typeof goalStartingData !== 'undefined' ) {
		for(var i = 0; i < goalStartingData.length; i++)
		{
			addProduct(i, goalStartingData[i][2], goalStartingData[i][3], goalStartingData[i][4], goalStartingData[i][5], goalStartingData[i][1], goalStartingData[i][0], goalStartingData[i][6]);
		}
		zoneCurrent = goalStartingData.length;
	}
});
