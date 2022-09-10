//require('./jquery.easyui.min.js');
let MsProfitcenterModel = require('./MsProfitcenterModel');
require('./datagrid-filter.js');

class MsProfitcenterController {
	constructor(MsProfitcenterModel)
	{
		this.MsProfitcenterModel = MsProfitcenterModel;
		this.formId='profitcenterFrm';
		this.dataTable='#profitcenterTbl';
		this.route=msApp.baseUrl()+"/profitcenter"
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

		// let companyId= new Array();
		// $('#companyBox2 option').map(function(i, el) {
		// 	companyId.push($(el).val());
		// });
		// $('#company_id').val( companyId.join());
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProfitcenterModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProfitcenterModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProfitcenterModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProfitcenterModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#profitcenterTbl').datagrid('reload');
		msApp.resetForm('subsectionFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProfitcenterModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsProfitcenter.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsProfitcenter=new MsProfitcenterController(new MsProfitcenterModel());
MsProfitcenter.showGrid();
$('#utilprofitcentertabs').tabs({
    onSelect:function(title,index){
        let profitcenter_id = $('#profitcenterFrm [name=id]').val();
        
        var data={};
		    data.profitcenter_id=profitcenter_id;
        if(index==1){
			if(profitcenter_id===''){
				$('#utilprofitcentertabs').tabs('select',0);
				msApp.showError('Select ProfitCenter First',0);
				return;
			}
			$('#companyprofitcenterFrm  [name=profitcenter_id]').val(profitcenter_id);
			MsCompanyProfitcenter.create()
		}
    }
});