//require('./jquery.easyui.min.js');
let MsSoDyeingTargetModel = require('./MsSoDyeingTargetModel');
require('./../../datagrid-filter.js');

class MsSoDyeingTargetController {
	constructor(MsSoDyeingTargetModel)
	{
		this.MsSoDyeingTargetModel = MsSoDyeingTargetModel;
		this.formId='sodyeingtargetFrm';
		this.dataTable='#sodyeingtargetTbl';
		this.route=msApp.baseUrl()+"/sodyeingtarget"
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
			this.MsSoDyeingTargetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingTargetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingtargetFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingTargetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingTargetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingtargetTbl').datagrid('reload');
		msApp.resetForm('sodyeingtargetFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let sodyeingtarget=this.MsSoDyeingTargetModel.get(index,row);
		sodyeingtarget.then(function(response){
			$('#sodyeingtargetFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#sodyeingtargetFrm [id="teammember_id"]').combobox('setValue', response.data.fromData.teammember_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingTarget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate()
	{
		let self=this;
		let qty=$('#sodyeingtargetFrm [name=qty]').val();
		let rate=$('#sodyeingtargetFrm [name=rate]').val();
		let amount=qty*rate;
		$('#sodyeingtargetFrm [name=amount]').val(amount);
	}

	getTeammember(){
		let buyer_id=$('#sodyeingtargetFrm  [name=buyer_id]').val();
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
window.MsSoDyeingTarget=new MsSoDyeingTargetController(new MsSoDyeingTargetModel());
MsSoDyeingTarget.showGrid();
