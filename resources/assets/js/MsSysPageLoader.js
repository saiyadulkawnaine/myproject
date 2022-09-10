export function load(url,tag_link,js_link,div){
	//location.hash = this.id; 
	/*
	if uncomment the above line, html5 nonsupported browers won't change the url but will display the ajax content;
	if commented, html5 nonsupported browers will reload the page to the specified link.
	*/
	// e.preventDefault();
	var pageurl = url;
	/*$('#'+div).panel('refresh', pageurl);
	if(pageurl!=window.location){
		window.history.pushState({path:pageurl},'',pageurl);
	}
	return false;*/
	$.ajax({
		type:'get',
		url:pageurl,
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
			//alert(data)
			$.unblockUI();
			//alert(data);
			
			//document.getElementById(div).innerHTML = data;
			$('#'+div).html(data);
			$.parser.parse('#'+div)
			//$('#'+div).panel('refresh', 'create');
		},
		error: function(request, ajaxOptions, thrownError) {
				var d=JSON.parse(request.responseText);
				msApp.showError(d.message,'');
				//alert(d.msg);
				var pageurl = url+"/";
				var tag_link="";
				if(pageurl==url+"/"){
					pageurl+="dashboard";
				}
				//msApp.PageLoader.load(pageurl,tag_link,'','menu_content');
				
                //$('#menu_content').html('<p>status code: '+request.status+'</p><p>errorThrown: ' + thrownError + '</p><p>responseText:</p><div>'+request.responseText + '</div>');
				$.unblockUI();
		}
	});
	
	//to change the browser URL to the given link location
	if(pageurl!=window.location){
		window.history.pushState({path:pageurl},'',pageurl);
	}
	
	//stop refreshing to the page given in
	return false;
}