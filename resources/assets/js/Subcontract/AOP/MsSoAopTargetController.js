
//require('./jquery.easyui.min.js');
let MsSoAopTargetModel = require('./MsSoAopTargetModel');
require('./../../datagrid-filter.js');

class MsSoAopTargetController {
	constructor(MsSoAopTargetModel)
	{
		this.MsSoAopTargetModel = MsSoAopTargetModel;
		this.formId='soaoptargetFrm';
		this.dataTable='#soaoptargetTbl';
		this.route=msApp.baseUrl()+"/soaoptarget"
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
			this.MsSoAopTargetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopTargetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaoptargetFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopTargetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopTargetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaoptargetTbl').datagrid('reload');
		msApp.resetForm('soaoptargetFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let soaoptarget=this.MsSoAopTargetModel.get(index,row);
		soaoptarget.then(function(response){
			$('#soaoptargetFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#soaoptargetFrm [id="teammember_id"]').combobox('setValue', response.data.fromData.teammember_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopTarget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate()
	{
		let self=this;
		let qty=$('#soaoptargetFrm [name=qty]').val();
		let rate=$('#soaoptargetFrm [name=rate]').val();
		let amount=qty*rate;
		$('#soaoptargetFrm [name=amount]').val(amount);
	}

	getTeammember(){
		let buyer_id=$('#soaoptargetFrm  [name=buyer_id]').val();
		const instance = axios.create();
		let data= instance.get(this.route+"/getteammember?buyer_id="+buyer_id);
		data.then(function (response) {
			   $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
					$('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoAopTarget=new MsSoAopTargetController(new MsSoAopTargetModel());
MsSoAopTarget.showGrid();
