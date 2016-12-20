(function($) {
	$('select#Form_EditForm_VideoSource').entwine({
		toggleState: function(){ 
			$('#Form_EditForm_YoutubeCode_Holder').hide();
			$('#Form_EditForm_VimeoCode_Holder').hide();
			$('#Form_EditForm_VideoVersions').hide();
			if($(this).val() == 'YouTube')  {
				$('#Form_EditForm_YoutubeCode_Holder').show();
			}
			else if ($(this).val() == 'Vimeo') {
				$('#Form_EditForm_VimeoCode_Holder').show();
			}				 
			else if ($(this).val() == 'Self-Hosted') {
				$('#Form_EditForm_VideoVersions').show();
			}				 
		},
		onchange: function(){
			this.toggleState();
		},
		onmatch: function(){
			this.toggleState();
		}
	});
	$('select#Form_ItemEditForm_VideoSource').entwine({
		toggleState: function(){ 
			$('#Form_ItemEditForm_YoutubeCode_Holder').hide();
			$('#Form_ItemEditForm_VimeoCode_Holder').hide();
			$('#Form_ItemEditForm_VideoVersions').hide();
			if($(this).val() == 'YouTube')  {
				$('#Form_ItemEditForm_YoutubeCode_Holder').show();
			}
			else if ($(this).val() == 'Vimeo') {
				$('#Form_ItemEditForm_VimeoCode_Holder').show();
			}				 
			else if ($(this).val() == 'Self-Hosted') {
				$('#Form_ItemEditForm_VideoVersions').show();
			}				 
		},
		onchange: function(){
			this.toggleState();
		},
		onmatch: function(){
			this.toggleState();
		}
	});
})(jQuery);