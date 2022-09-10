let MsCashIncentiveAdvModel = require('./MsCashIncentiveAdvModel');
require('./../../datagrid-filter.js');
class MsCashIncentiveAdvController {
	constructor(MsCashIncentiveAdvModel)
	{
		this.MsCashIncentiveAdvModel = MsCashIncentiveAdvModel;
		this.formId='cashincentiveadvFrm';
		this.dataTable='#cashincentiveadvTbl';
		this.route=msApp.baseUrl()+"/cashincentiveadv"
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
			this.MsCashIncentiveAdvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveAdvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveAdvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveAdvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiveadvTbl').datagrid('reload');
		msApp.resetForm('cashincentiveadvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsCashIncentiveAdvModel.get(index,row);
		
	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveAdv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getAdvLetter(){
		var id= $('#cashincentiveadvFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getadvanceletter?id="+id);
	}


}
window.MsCashIncentiveAdv=new MsCashIncentiveAdvController(new MsCashIncentiveAdvModel());
MsCashIncentiveAdv.showGrid();

 $('#advancetabs').tabs({
	onSelect:function(title,index){
	let cash_incentive_adv_id = $('#cashincentiveadvFrm  [name=id]').val();
	let advance_per = $('#cashincentiveadvFrm  [name=advance_per]').val();
	var data={};
    data.cash_incentive_adv_id=cash_incentive_adv_id;
    data.advance_per=advance_per;

	if(index==1){
		if(cash_incentive_adv_id===''){
			 $('#advancetabs').tabs('select',0);
			 msApp.showError('Select a Advance Reference First',0);
			 return;
		 }
		$('#cashincentiveadvclaimFrm  [name=cash_incentive_adv_id]').val(cash_incentive_adv_id);
		//alert(advance_per)
		$('#cashincentiveadvclaimFrm  [name=advance_per]').val(advance_per);
		MsCashIncentiveAdvClaim.showGrid(cash_incentive_adv_id);
	 }
	 
	}
});
