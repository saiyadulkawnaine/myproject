//require('./jquery.easyui.min.js');
let MsDyingChargeModel = require('./MsDyingChargeModel');
require('./datagrid-filter.js');

class MsDyingChargeController {
	constructor(MsDyingChargeModel)
	{
		this.MsDyingChargeModel = MsDyingChargeModel;
		this.formId='dyingchargeFrm';
		this.dataTable='#dyingchargeTbl';
		this.route=msApp.baseUrl()+"/dyingcharge"
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
			this.MsDyingChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsDyingChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsDyingChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsDyingChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#dyingchargeTbl').datagrid('reload');
		$('#dyingchargeFrm  [name=id]').val(d.id);
		msApp.resetForm('buyerdyingchargeFrm');
		msApp.resetForm('dyingchargesupplierFrm');
		$('#buyerdyingchargeFrm  [name=dying_charge_id]').val(d.id);
		$('#dyingchargesupplierFrm  [name=dying_charge_id]').val(d.id);
		//msApp.resetForm('dyingchargeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsDyingChargeModel.get(index,row);
		msApp.resetForm('buyerdyingchargeFrm');
		msApp.resetForm('dyingchargesupplierFrm');
		$('#buyerdyingchargeFrm  [name=dying_charge_id]').val(row.id);
		$('#dyingchargesupplierFrm  [name=dying_charge_id]').val(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsDyingCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openFabricationWindow(){
		$('#dyingChargeFabricationWindow').window('open');
		MsDyingCharge.fabricSearchGrid([])
	}
	searchFabric(){
		let construction_name=$('#dyingchargefabricsearchFrm  [name=construction_name]').val();
		let composition_name=$('#dyingchargefabricsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getfabric?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#dyingvhargefabricsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	fabricSearchGrid(data)
	{
		var dg = $('#dyingvhargefabricsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){

				$('#dyingchargeFrm  [name=autoyarn_id]').val(row.id);
				$('#dyingchargeFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#dyingChargeFabricationWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
}
window.MsDyingCharge=new MsDyingChargeController(new MsDyingChargeModel());
MsDyingCharge.showGrid();
