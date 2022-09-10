
//require('./../../jquery.easyui.min.js');
let MsSubInbServiceModel = require('./MsSubInbServiceModel');
//require('./../../datagrid-filter.js');
class MsSubInbServiceController {
	constructor(MsSubInbServiceModel)
	{
		this.MsSubInbServiceModel = MsSubInbServiceModel;
		this.formId='subinbserviceFrm';
		this.dataTable='#subinbserviceTbl';
		this.route=msApp.baseUrl()+"/subinbservice"
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
			this.MsSubInbServiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubInbServiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subinbserviceFrm [name=sub_inb_marketing_id]').val($('#subinbmarketingFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubInbServiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubInbServiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#subinbserviceTbl').datagrid('reload');
		msApp.resetForm('subinbserviceFrm');
		$('#subinbserviceFrm [name=sub_inb_marketing_id]').val($('#subinbmarketingFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSubInbServiceModel.get(index,row);

	}

	showGrid(sub_inb_marketing_id){
		let self=this;
		let data = {};
		data.sub_inb_marketing_id=sub_inb_marketing_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSubInbService.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate()
	{
		let qty = $('#subinbserviceFrm  [name=qty]').val();
		let rate = $('#subinbserviceFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#subinbserviceFrm  [name=amount]').val(amount);
	}
    
  
}
window.MsSubInbService=new MsSubInbServiceController(new MsSubInbServiceModel());
//MsSubInbService.showGrid();

