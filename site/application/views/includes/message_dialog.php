<div style="display: none;" id="dialog-message"></div>

<script>
	function showMessageDialog(title, message) {
		$('#dialog-message').text(message);
		$('#dialog-message').dialog(
			{
				title: title,
				modal: true,
				resizable: false,
				show: { effect: 'puff', duration: 200 },
				hide: { effect: 'puff', duration: 200 },
				buttons: {
					Ok: function() {
						$(this).dialog('close');
					}
				}
			}
		);
	}
</script>
