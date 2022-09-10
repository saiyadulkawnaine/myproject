//require('./../../jquery.easyui.min.js');
let MsExpPiModel = require('./MsExpPiModel');
require('./../../datagrid-filter.js');
require('./../../datagrid-cellediting.js');
class MsExpPiController {
	constructor(MsExpPiModel)
	{
		this.MsExpPiModel = MsExpPiModel;
		this.formId='exppiFrm';
		this.dataTable='#exppiTbl';
		this.route=msApp.baseUrl()+"/exppi"
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
			this.MsExpPiModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpPiModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpPiModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpPiModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#exppiTbl').datagrid('reload');
		msApp.resetForm('exppiFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpPiModel.get(index,row);	

	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpPi.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsExpPi=new MsExpPiController(new MsExpPiModel());
MsExpPi.showGrid()

$('#comExpTabs').tabs({
	onSelect:function(title,index){
	 let exp_pi_id = $('#exppiFrm  [name=id]').val();

	 var data={};
	  data.exp_pi_id=exp_pi_id;

	 if(index==1){
		 if(exp_pi_id===''){
			 $('#comExpTabs').tabs('select',0);
			 msApp.showError('Select an ExpPI First',0);
			 return;
		  }
		 $('#exppiorderFrm  [name=exp_pi_id]').val(exp_pi_id)
		 MsExpPiOrder.showGrid(exp_pi_id);
	 }
}
});
