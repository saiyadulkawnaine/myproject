//require('./jquery.easyui.min.js');
let MsMyaccountModel = require('./MsMyaccountModel');
require('./datagrid-filter.js');

class MsMyaccountController {
	constructor(MsMyaccountModel)
	{
		this.MsMyaccountModel = MsMyaccountModel;
		this.formId='myaccountFrm';
		this.route=msApp.baseUrl()+"/myaccount"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
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

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsMyaccountModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			alert("Problem Found. Please Contact With Administrator .")
		}
	}
	
	response(d)
	{
	}
}
window.MsMyaccount=new MsMyaccountController(new MsMyaccountModel());
