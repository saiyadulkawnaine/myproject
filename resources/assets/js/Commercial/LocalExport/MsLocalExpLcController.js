let MsLocalExpLcModel = require('./MsLocalExpLcModel');
require('./../../datagrid-filter.js');
class MsLocalExpLcController {
	constructor(MsLocalExpLcModel)
	{
		this.MsLocalExpLcModel = MsLocalExpLcModel;
		this.formId='localexplcFrm';
		this.dataTable='#localexplcTbl';
		this.route=msApp.baseUrl()+"/localexplc"
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
			this.MsLocalExpLcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpLcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#localexplcFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpLcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpLcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexplcTbl').datagrid('reload');
		msApp.resetForm('localexplcFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let expLC=this.MsLocalExpLcModel.get(index,row);	
		expLC.then(function(response){
			$('#localexplcFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		});

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
		return '<a href="javascript:void(0)"  onClick="MsLocalExpLc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsLocalExpLc=new MsLocalExpLcController(new MsLocalExpLcModel());
MsLocalExpLc.showGrid();

 $('#comlocalexplctabs').tabs({
	onSelect:function(title,index){
	 let local_exp_lc_id = $('#localexplcFrm  [name=id]').val();

	 var data={};
	  data.local_exp_lc_id=local_exp_lc_id;

	 if(index==1){
		 if(local_exp_lc_id===''){
			 $('#comlocalexplctabs').tabs('select',0);
			 msApp.showError('Select an Export LC First',0);
			 return;
		  }
		 $('#localexplctagpiFrm  [name=exp_lc_sc_id]').val(local_exp_lc_id);
		MsLocalExpLcTagPi.showGrid(local_exp_lc_id);
	 }
}
}); 
