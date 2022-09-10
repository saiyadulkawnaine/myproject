//require('./jquery.easyui.min.js');
let MsAopChargeModel = require('./MsAopChargeModel');
require('./datagrid-filter.js');

class MsAopChargeController {
	constructor(MsAopChargeModel)
	{
		this.MsAopChargeModel = MsAopChargeModel;
		this.formId='aopchargeFrm';
		this.dataTable='#aopchargeTbl';
		this.route=msApp.baseUrl()+"/aopcharge"
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
			this.MsAopChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAopChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAopChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAopChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#aopchargeTbl').datagrid('reload');
		$('#aopchargeFrm  [name=id]').val(d.id);
		msApp.resetForm('aopbuyerchargeFrm');
		msApp.resetForm('aopsupplierchargeFrm');
	  $('#aopbuyerchargeFrm  [name=aop_charge_id]').val(d.id);
	  $('#aopsupplierchargeFrm  [name=aop_charge_id]').val(d.id);
		//msApp.resetForm('aopchargeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAopChargeModel.get(index,row);
		msApp.resetForm('aopbuyerchargeFrm');
		msApp.resetForm('aopsupplierchargeFrm');
		$('#aopbuyerchargeFrm  [name=aop_charge_id]').val(row.id);
	  $('#aopsupplierchargeFrm  [name=aop_charge_id]').val(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsAopCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openFabricationWindow(){
		$('#aopChargeFabricationWindow').window('open');
		MsAopCharge.fabricSearchGrid([])
	}
	searchFabric(){
		let construction_name=$('#aopchargefabricsearchFrm  [name=construction_name]').val();
		let composition_name=$('#aopchargefabricsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getfabric?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#aopchargefabricsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	fabricSearchGrid(data)
	{
		var dg = $('#aopchargefabricsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){

				$('#aopchargeFrm  [name=autoyarn_id]').val(row.id);
				$('#aopchargeFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#aopChargeFabricationWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
}
window.MsAopCharge=new MsAopChargeController(new MsAopChargeModel());
MsAopCharge.showGrid();
