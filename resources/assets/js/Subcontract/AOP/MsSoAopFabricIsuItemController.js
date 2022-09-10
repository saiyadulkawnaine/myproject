let MsSoAopFabricIsuItemModel = require('./MsSoAopFabricIsuItemModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricIsuItemController {
	constructor(MsSoAopFabricIsuItemModel)
	{
		this.MsSoAopFabricIsuItemModel = MsSoAopFabricIsuItemModel;
		this.formId='soaopfabricisuitemFrm';
		this.dataTable='#soaopfabricisuitemTbl';
		this.route=msApp.baseUrl()+"/soaopfabricisuitem"
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
		let so_aop_fabric_isu_id=$('#soaopfabricisuFrm  [name=id]').val();
		let formObj=MsSoAopFabricIsuItem.getSelections();
		formObj.so_aop_fabric_isu_id=so_aop_fabric_isu_id;
		if(formObj.id){
			this.MsSoAopFabricIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricisuitemWindow').window('close');
		MsSoAopFabricIsuItem.get(d.so_aop_fabric_isu_id)
		msApp.resetForm('soaopfabricisuitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricIsuItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_fabric_isu_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_isu_id="+so_aop_fabric_isu_id);
		data.then(function (response) {
			$('#soaopfabricisuitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}
				$('#soaopfabricisuitemTbl').datagrid('reloadFooter', [
				{ 
					rcv_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#soaopfabricisuitemWindow').window('open');
	}*/
	itemGrid(data){
		let self = this;
		$('#soaopfabricisuitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			onLoadSuccess: function(data){
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}
				$('#soaopfabricisuitemsearchTbl').datagrid('reloadFooter', [
				{ 
					rcv_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		let so_aop_fabric_isu_id=$('#soaopfabricisuFrm  [name=id]').val();
		let data= axios.get(this.route+"/getitem?so_aop_fabric_isu_id="+so_aop_fabric_isu_id);
		data.then(function (response) {
			$('#soaopfabricisuitemsearchTbl').datagrid('loadData', response.data);
			$('#soaopfabricisuitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}
	getSelections(){
		let formObj={};
		let checked=$('#soaopfabricisuitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		let i=1;
		$.each(checked, function (idx, val) {
			formObj['so_aop_fabric_rcv_rol_id['+i+']']=val.id;
			i++;
		});
		$('#soaopfabricisuitemsearchTbl').datagrid('clearSelections');
		MsSoAopFabricIsuItem.itemGrid([]);
		$('#soaopfabricisuitemWindow').window('close');
		return formObj;
	}

	
}
window.MsSoAopFabricIsuItem=new MsSoAopFabricIsuItemController(new MsSoAopFabricIsuItemModel());
MsSoAopFabricIsuItem.showGrid([]);
MsSoAopFabricIsuItem.itemGrid([]);