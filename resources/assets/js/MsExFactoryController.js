//require('./jquery.easyui.min.js');
let MsExFactoryModel = require('./MsExFactoryModel');
require('./datagrid-filter.js');

class MsExFactoryController {
	constructor(MsExFactoryModel)
	{
		this.MsExFactoryModel = MsExFactoryModel;
		this.formId='exfactoryFrm';
		this.dataTable='#exfactoryTbl';
		this.route=msApp.baseUrl()+"/exfactory"
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
			this.MsExFactoryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExFactoryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExFactoryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExFactoryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#exfactoryTbl').datagrid('reload');
		msApp.resetForm('exfactoryFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExFactoryModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsExFactory.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(){
		let qty;
		let rate;
		qty=$('#exfactoryFrm [name=qty]').val();
		rate=$('#exfactoryFrm [name=rate]').val();
		let amount=qty*rate;
		$('#exfactoryFrm [name=amount]').val(amount);
	}

	openSaleOrderSecWindow(){
		$('#salesSelcWindow').window('open');
	}
	showSalesOrderGrid()
	 {
      let data={};
      data.style_ref=$('#salesordersearchFrm [name=style_ref]').val();
      data.job_id=$('#salesordersearchFrm [name=job_id]').val();
      let self=this;
		$('#salesordersearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getSalesJS",
			onClickRow: function(index,row){
					$('#exfactoryFrm [name=sale_order_id]').val(row.id);
					$('#exfactoryFrm [name=sale_order_no]').val(row.sale_order_no);
					$('#salesordersearchFrm [name=style_ref]').val(row.style_ref);
					$('#salesordersearchFrm [name=job_id]').val(row.job_id);
					$('#salesordersearchFrm [name=file_no]').val(row.file_no);
					$('#salesSelcWindow').window('close')
			}
			}).datagrid('enableFilter');
    }
}
window.MsExFactory=new MsExFactoryController(new MsExFactoryModel());
MsExFactory.showGrid();
