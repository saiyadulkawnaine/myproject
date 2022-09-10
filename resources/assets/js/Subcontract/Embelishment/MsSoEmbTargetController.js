//require('./jquery.easyui.min.js');
let MsSoEmbTargetModel = require('./MsSoEmbTargetModel');
require('./../../datagrid-filter.js');

class MsSoEmbTargetController {
	constructor(MsSoEmbTargetModel)
	{
		this.MsSoEmbTargetModel = MsSoEmbTargetModel;
		this.formId='soembtargetFrm';
		this.dataTable='#soembtargetTbl';
		this.route=msApp.baseUrl()+"/soembtarget"
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
			this.MsSoEmbTargetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbTargetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembtargetFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbTargetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbTargetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembtargetTbl').datagrid('reload');
		msApp.resetForm('soembtargetFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let soembtarget=this.MsSoEmbTargetModel.get(index,row);
		soembtarget.then(function(response){
			$('#soembtargetFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbTarget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calcutate()
	{
		let self=this;
		let qty=$('#soembtargetFrm [name=qty]').val();
		let rate=$('#soembtargetFrm [name=rate]').val();
		let amount=qty*rate;
		$('#soembtargetFrm [name=amount]').val(amount);
	}
}
window.MsSoEmbTarget=new MsSoEmbTargetController(new MsSoEmbTargetModel());
MsSoEmbTarget.showGrid();
