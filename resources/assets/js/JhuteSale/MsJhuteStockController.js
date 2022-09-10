let MsJhuteStockModel = require('./MsJhuteStockModel');
require('./../datagrid-filter.js');
class MsJhuteStockController {
	constructor(MsJhuteStockModel)
	{
		this.MsJhuteStockModel = MsJhuteStockModel;
		this.formId='jhutestockFrm';
		this.dataTable='#jhutestockTbl';
		this.route=msApp.baseUrl()+"/jhutestock"
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
			this.MsJhuteStockModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteStockModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutestockFrm [id="buyer_id"]').combobox('setValue', '');
		$('#jhutestockFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#jhutestockFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteStockModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteStockModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutestockTbl').datagrid('reload');
		msApp.resetForm('jhutestockFrm');
		$('#jhutestockFrm [id="buyer_id"]').combobox('setValue', '');
		$('#jhutestockFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#jhutestockFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let dlvorder = this.MsJhuteStockModel.get(index,row);
		dlvorder.then(function (response) {
		}).catch(function (error) {
			console.log(error);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsJhuteStock.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf()
	{
		var id = $('#jhutestockFrm [name=id]').val();
		if(id==""){
			alert("Select an Order First");
			return;
		}
		window.open(this.route+"/getdlvorderpdf?id="+id);
	}

}
window.MsJhuteStock=new MsJhuteStockController(new MsJhuteStockModel());
MsJhuteStock.showGrid();
$('#jhutestocktabs').tabs({
    onSelect:function(title,index){
		let jhute_stock_id = $('#jhutestockFrm [name=id]').val();
  		var data={};
		data.jhute_stock_id = jhute_stock_id;
  		if(index==1){
			if(jhute_stock_id ===''){
				$('#jhutestocktabs').tabs('select',0);
				msApp.showError('Select Jhute Stock Reference First',0);
				return;
		 	}
			msApp.resetForm('jhutestockitemFrm');
			$('#jhutestockitemFrm  [name=jhute_stock_id]').val(jhute_stock_id);
			MsJhuteStockItem.showGrid(jhute_stock_id);
   		}
    }
});