$(function() {
	var pageurl = window.location;
	var tag_link="";
	if(pageurl==url+"/"){
		pageurl+="dashboard";
	}
	msApp.PageLoader.load(pageurl,tag_link,'','menu_content');
	$(window).bind('popstate', function() {
		$.ajax({
			type:'get',
			url:window.location,
			headers: {'X-Requested-With': 'XMLHttpRequest'},
			async :true,
			beforeSend: function () {
				$.blockUI({
					message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
						overlayCSS: {
						backgroundColor: '#1b2024',
						opacity: 0.8,
						zIndex: 999999,
						cursor: 'wait'
					},
					css: {
						border: 0,
						color: '#fff',
						padding: 0,
						zIndex: 9999999,
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data){
				$.unblockUI();
				$('#menu_content').html(data);
			},
			error: function(request, ajaxOptions, thrownError) {
				var d=JSON.parse(request.responseText);
				msApp.showError(d.msg,'');
				//alert(d.msg);
				var pageurl = url+"/";
				var tag_link="";
				if(pageurl==url+"/"){
				pageurl+="dashboard";
				}
				msApp.PageLoader.load(pageurl,tag_link,'','menu_content');
                //$('#menu_content').html('<p>status code: '+request.status+'</p><p>errorThrown: ' + thrownError + '</p><p>responseText:</p><div>'+request.responseText + '</div>');
				$.unblockUI();
		}
		});
	});
});