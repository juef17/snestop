function selectSelectableElement (selectableContainer, elementsToSelect)
{
	$(".ui-selected", selectableContainer).not(elementsToSelect).removeClass("ui-selected").addClass("ui-unselecting");
	$(elementsToSelect).not(".ui-selected").addClass("ui-selecting");
	selectableContainer.data("ui-selectable")._mouseStop(null);
}
