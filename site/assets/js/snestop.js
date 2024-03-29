function selectSelectableElement (selectableContainer, elementsToSelect)
{
	$(".ui-selected", selectableContainer).not(elementsToSelect).removeClass("ui-selected").addClass("ui-unselecting");
	$(elementsToSelect).not(".ui-selected").addClass("ui-selecting");
	selectableContainer.data("ui-selectable")._mouseStop(null);
	selectableContainer.parent().scrollTo($(elementsToSelect), 100);
}

function waitModalVisible(options) {
	if(options.fade == undefined)
		options.fade = true;
	
	if(options.visible)
		$('.waitmodal').fadeIn(options.fade ? 1000 : 0);
	else
		$('.waitmodal').fadeOut(options.fade ? 1000 : 0);
}

function validateSession(data) {
	if(data.indexOf('<!-- session timeout -->') > -1) {
		showMessageDialog('Session timed out!', 'Please refresh the page and log back in!');
		return false;
	} else {
		return true;
	}
}
