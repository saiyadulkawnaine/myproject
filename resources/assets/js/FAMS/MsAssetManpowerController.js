let MsAssetManpowerModel = require('./MsAssetManpowerModel');
class MsAssetManpowerController {
	constructor(MsAssetManpowerModel)
	{
		this.MsAssetManpowerModel = MsAssetManpowerModel;
		this.formId='assetmanpowerFrm';
		this.dataTable='#assetmanpowerTbl';
		this.route=msApp.baseUrl()+"/assetmanpower"
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
			this.MsAssetManpowerModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetManpowerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);

	}
	remove()
	{
		msApp.get(this.formId);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetManpowerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assetmanpowerTbl').datagrid('reload');
		msApp.resetForm('assetmanpowerFrm');
		$('#assetmanpowerFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetManpowerModel.get(index,row);
	}

	showGrid(asset_acquisition_id)
	{
		let self=this;
		var data={};
		data.asset_acquisition_id=asset_acquisition_id;
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
	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAssetManpower.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openManpowerEmployee(){
		$('#openmanpoweremployeewindow').window('open');
	}

	getParams(){
		let params = {}
		params.designation_id=$('#assetempsearchFrm [name=designation_id]').val();
		params.department_id=$('#assetempsearchFrm [name=department_id]').val();
		params.company_id=$('#assetempsearchFrm [name=company_id]').val();
		return params;
	}
	searchEmployeeGrid(){
		let params=this.getParams();
		let emp= axios.get(this.route+'/getemployee',{params})
		.then(function(response){
			$('#assetempsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}

	employeeGrid(data){
		let self = this;
		$('#assetempsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#assetmanpowerFrm [name=employee_h_r_id]').val(row.id);
				$('#assetmanpowerFrm [name=name]').val(row.name);
				$('#assetempsearchTbl').datagrid('loadData',[]);
				$('#openmanpoweremployeewindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	openManpowerMachineWindow(){
		MsAssetManpower.showManpowerMachineGrid([]);
		$('#assetmachineWindow').window('open');
	}
	showManpowerMachineGrid(data){
		let self = this;
		$('#assetmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#assetmanpowerFrm [name=asset_quantity_cost_id]').val(row.id);
					$('#assetmanpowerFrm [name=custom_no]').val(row.custom_no);
					$('#assetmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchAssetMachine()
	{
		let params={};
		params.acquisitionid=$('#assetacquisitionFrm  [name=id]').val();
		params.dia_width=$('#assetempsearchFrm  [name=dia_width]').val();
		params.no_of_feeder=$('#assetempsearchFrm  [name=no_of_feeder]').val();
		
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#assetmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

}
window.MsAssetManpower=new MsAssetManpowerController(new MsAssetManpowerModel());
MsAssetManpower.employeeGrid([]);