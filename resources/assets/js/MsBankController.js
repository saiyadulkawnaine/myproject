//require('./jquery.easyui.min.js');
let MsBankModel = require('./MsBankModel');
require('./datagrid-filter.js');
class MsBankController {
	constructor(MsBankModel)
	{
		this.MsBankModel = MsBankModel;
		this.formId='bankFrm';
		this.dataTable='#bankTbl';
		this.route=msApp.baseUrl()+"/bank";
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
			this.MsBankModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBankModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsBankModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBankModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#bankTbl').datagrid('reload');
		//MsBank.showGrid();
		msApp.resetForm('bankFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBankModel.get(index,row);
	}

	showGrid()
	{
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBank.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBank=new MsBankController(new MsBankModel());

MsBank.showGrid();
$('#utilbanktabs').tabs({
	onSelect:function(title,index){
		let bank_id = $('#bankFrm  [name=id]').val();
		let bank_branch_id = $('#bankbranchFrm  [name=id]').val();

		var data={};
		data.bank_id=bank_id;
		data.bank_branch_id=bank_branch_id;

		if(index==1){
			if(bank_id===''){
				$('#utilbanktabs').tabs('select',0);
				msApp.showError('Select A Bank First',0);
				return;
			}
			$('#bankbranchFrm  [name=bank_id]').val(bank_id)
			MsBankBranch.showGrid(bank_id);
		}
		if(index==2){
			if(bank_branch_id===''){
				$('#utilbanktabs').tabs('select',0);
				msApp.showError('Select A Bank Branch First',0);
				return;
			}
			$('#bankaccountFrm  [name=bank_branch_id]').val(bank_branch_id)
			MsBankAccount.get(bank_branch_id);
		}
    }
 });

