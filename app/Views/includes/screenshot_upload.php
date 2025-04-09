<div style="display: none;" id="dialog-upload">
	<?= form_open_multipart(base_url() . 'screenshot_request_dashboard/uploadScreenshot') ?>
		<input type="hidden" id="id" name="id" />
		<input type="hidden" id="type" name="type" />
		<input type="file" id="file" name="userfile" />
		<div id="progress"></div>
		<div class="errors"></div>
	</form>
</div>

<script src="<?=asset_url()?>js/jquery.fileupload.js"></script>

<script>
	function showUploadScreenshotDialog(id, type) {
		$('#dialog-upload #id').val(id);
		$('#dialog-upload #type').val(type);
		$('#dialog-upload #progress').progressbar({ value: 0 });
		$('#dialog-upload #file').fileupload({
			dataType: 'json',
			progressall: function (e, data) {
				$('#dialog-upload .errors').text('');
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#dialog-upload #progress').progressbar('option', { value: progress < 100 ? progress : false });
			},
			done: function (e, data) {
				if(data.result.success) {
					location.reload();
				} else {
					$('#dialog-upload .errors').text(data.result.message);
				}
			}
		});
		
		$('#dialog-upload').dialog({
			width: 400,
			modal: true,
			resizable: false,
			title: 'Upload screenshot',
			show: { effect: 'puff', duration: 200 },
			hide: { effect: 'puff', duration: 200 }
		});
	}
</script>
