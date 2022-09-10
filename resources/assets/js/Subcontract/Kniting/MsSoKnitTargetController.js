
//require('./jquery.easyui.min.js');
let MsSoKnitTargetModel = require('./MsSoKnitTargetModel');
require('./../../datagrid-filter.js');

class MsSoKnitTargetController {
	constructor(MsSoKnitTargetModel)
	{
		this.MsSoKnitTargetModel = MsSoKnitTargetModel;
		this.formId='soknittargetFrm';
		this.dataTable='#soknittargetTbl';
		this.route=msApp.baseUrl()+"/soknittarget"
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
			this.MsSoKnitTargetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoKnitTargetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soknittargetFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitTargetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitTargetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soknittargetTbl').datagrid('reload');
		msApp.resetForm('soknittargetFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let soknittarget=this.MsSoKnitTargetModel.get(index,row);
		soknittarget.then(function(response){
			$('#soknittargetFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoKnitTarget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
calculate()
{
      let self=this;
      let qty=$('#soknittargetFrm [name=qty]').val();
      let rate=$('#soknittargetFrm [name=rate]').val();
      let amount=qty*rate;
     $('#soknittargetFrm [name=amount]').val(amount);
}
}
window.MsSoKnitTarget=new MsSoKnitTargetController(new MsSoKnitTargetModel());
MsSoKnitTarget.showGrid();
