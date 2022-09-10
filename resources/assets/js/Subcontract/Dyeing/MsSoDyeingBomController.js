let MsSoDyeingBomModel = require('./MsSoDyeingBomModel');
require('./../../datagrid-filter.js');
class MsSoDyeingBomController {
	constructor(MsSoDyeingBomModel)
	{
		this.MsSoDyeingBomModel = MsSoDyeingBomModel;
		this.formId='sodyeingbomFrm';
		this.dataTable='#sodyeingbomTbl';
		this.route=msApp.baseUrl()+"/sodyeingbom"
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
			this.MsSoDyeingBomModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingBomModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingbomFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingBomModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingBomModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingbomTbl').datagrid('reload');
		msApp.resetForm('sodyeingbomFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingBomModel.get(index,row);
		workReceive.then(function(response){
			//$('#sodyeingbomFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingBom.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	soWindow(){
		$('#sodyeingbomsoWindow').window('open');
	}
	sodyeingbomsoGrid(data){
		let self = this;
		$('#sodyeingbomsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingbomFrm [name=so_dyeing_id]').val(row.id);
				$('#sodyeingbomFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingbomFrm [name=company_id]').val(row.company_id);
				$('#sodyeingbomFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingbomFrm [name=order_val]').val(row.order_val);
				$('#sodyeingbomFrm [name=currency_id]').val(row.currency_id);
				$('#sodyeingbomsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#sodyeingbomsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#sodyeingbomFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#sodyeingbomsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	pdf()
	{
		var id= $('#sodyeingbomFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getpdf?id="+id);
	}
}
window.MsSoDyeingBom=new MsSoDyeingBomController(new MsSoDyeingBomModel());
MsSoDyeingBom.showGrid();
MsSoDyeingBom.sodyeingbomsoGrid([]);
 $('#sodyeingbomtabs').tabs({
	onSelect:function(title,index){
	 let so_dyeing_bom_id = $('#sodyeingbomFrm  [name=id]').val();
	 let currency_id = $('#sodyeingbomFrm  [name=currency_id]').val();
	 let order_val = $('#sodyeingbomFrm  [name=order_val]').val();
	 let so_dyeing_bom_fabric_id = $('#sodyeingbomfabricFrm  [name=id]').val();
	 let fabric_wgt = $('#sodyeingbomfabricFrm  [name=fabric_wgt]').val();
	 let liqure_wgt = $('#sodyeingbomfabricFrm  [name=liqure_wgt]').val();
	 
	 if(index==1){
		 if(so_dyeing_bom_id===''){
			 $('#sodyeingbomtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('sodyeingbomfabricFrm');
		 $('#sodyeingbomfabricFrm  [name=so_dyeing_bom_id]').val(so_dyeing_bom_id);
		 MsSoDyeingBomFabric.get(so_dyeing_bom_id);
	 }

	 if(index==2){
		 if(so_dyeing_bom_fabric_id===''){
			 $('#sodyeingbomtabs').tabs('select',1);
			 msApp.showError('Select a Fabric First',0);
			 return;
		  }
		  msApp.resetForm('sodyeingbomfabricitemFrm');
		 $('#sodyeingbomfabricitemFrm  [name=so_dyeing_bom_fabric_id]').val(so_dyeing_bom_fabric_id);
		 $('#sodyeingbomfabricitemFrm  [name=fabric_wgt]').val(fabric_wgt);
		 $('#sodyeingbomfabricitemFrm  [name=liqure_wgt]').val(liqure_wgt);
		 $('#sodyeingbomfabricitemFrm  [name=currency_id]').val(currency_id);
		 //MsSoDyeingBomItem.showGrid([]);
		 MsSoDyeingBomFabricItem.get(so_dyeing_bom_fabric_id);
	 }

	 if(index==3){
		 if(so_dyeing_bom_id===''){
			 $('#sodyeingbomtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('sodyeingbomoverheadFrm');
		 $('#sodyeingbomoverheadFrm  [name=so_dyeing_bom_id]').val(so_dyeing_bom_id);
		 $('#sodyeingbomoverheadFrm  [name=order_val]').val(order_val);
		 MsSoDyeingBomOverhead.get(so_dyeing_bom_id);
	 }
}
}); 
