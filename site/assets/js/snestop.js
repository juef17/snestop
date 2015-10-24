function selectSelectableElement (selectableContainer, elementsToSelect)
{
	$(".ui-selected", selectableContainer).not(elementsToSelect).removeClass("ui-selected").addClass("ui-unselecting");
	$(elementsToSelect).not(".ui-selected").addClass("ui-selecting");
	selectableContainer.data("ui-selectable")._mouseStop(null);
}

function waitModalVisible(options) {
	if(options.fade == undefined)
		options.fade = true;
	
	if(options.visible)
		$('.waitmodal').fadeIn(options.fade ? 1000 : 0);
	else
		$('.waitmodal').fadeOut(options.fade ? 1000 : 0);
}
