//require('./jquery.easyui.min.js');
let MsWashChargeModel = require('./MsWashChargeModel');
require('./datagrid-filter.js');

class MsWashChargeController {
	constructor(MsWashChargeModel)
	{
		this.MsWashChargeModel = MsWashChargeModel;
		this.formId='washchargeFrm';
		this.dataTable='#washchargeTbl';
		this.route=msApp.baseUrl()+"/washcharge"
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
			this.MsWashChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWashChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWashChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWashChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#washchargeTbl').datagrid('reload');
		$('#washchargeFrm  [name=id]').val(d.id);
		msApp.resetForm('supplierwashchargeFrm');
		$('#supplierwashchargeFrm  [name=wash_charge_id]').val(d.id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let i=index;
		let r=row;
		let self=this;
		let data=MsWashCharge.getembType (row.embelishment_id)
		.then(function (response) {
			  MsWashCharge.MsWashChargeModel.get(index,row);
		})
		.catch(function (error) {
			console.log(error);
		});
		msApp.resetForm('supplierwashchargeFrm');
	    $('#supplierwashchargeFrm  [name=wash_charge_id]').val(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsWashCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	embnameChange(embelishment_id){
		MsWashCharge.getembType (embelishment_id)
		$('#washchargeFrm  [name=rate]').val('');
	}
	embtypeChange(embelishment_type_id){
		$('#washchargeFrm  [name=rate]').val('');
	}
	getembType (embelishment_id){
		let data= axios.get(this.route+"/embtype?embelishment_id="+embelishment_id)
		.then(function (response) {
			    $('select[name="embelishment_type_id"]').empty();
				$('select[name="embelishment_type_id"]').append('<option value="">-Select-</option>');
                $.each(response.data.embelishmenttype, function(key, value) {
					$('select[name="embelishment_type_id"]').append('<option value="'+ value.id +'">'+ value.name+'</option>');
                });
				$('#washchargeFrm  [name=production_area_id]').val(response.data.embelishment.production_area_id);
				MsWashCharge.setClass(response.data.embelishment.production_area_id)
		})
		.catch(function (error) {
			console.log(error);
		});
		return data;
	}
	
	setClass(production_area_id)
	{
		if(production_area_id==45 ||  production_area_id==50)
		{
			$("#wash_charge_embelishment_size").addClass("req-text");
		}
		else
		{
			$("#wash_charge_embelishment_size").removeClass("req-text");

		}
	}
}
window.MsWashCharge=new MsWashChargeController(new MsWashChargeModel());
MsWashCharge.showGrid();
