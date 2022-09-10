let MsProdGmtExFactoryModel = require('./MsProdGmtExFactoryModel');
require('./../../datagrid-filter.js');
class MsProdGmtExFactoryController {
	constructor(MsProdGmtExFactoryModel)
	{
		this.MsProdGmtExFactoryModel = MsProdGmtExFactoryModel;
		this.formId='prodgmtexfactoryFrm';
		this.dataTable='#prodgmtexfactoryTbl';
		this.route=msApp.baseUrl()+"/prodgmtexfactory"
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
			this.MsProdGmtExFactoryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtExFactoryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtexfactoryFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtExFactoryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtExFactoryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtexfactoryTbl').datagrid('reload');
		msApp.resetForm('prodgmtexfactoryFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let exfactory=this.MsProdGmtExFactoryModel.get(index,row);
		exfactory.then(function(response){
			$('#prodgmtexfactoryFrm [id="buyer_id"]').combobox('setValue',response.data.fromData.buyer_id);
		}).catch(function (error) {
			console.log(error);
		});

	}

	showGrid(){

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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtExFactory.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	/* openCartonWindow(){
		$('#opencartonwindow').window('open');
	}
	showCartonGrid(){
		let data = {};
		data.carton_date = $('#cartonsearchFrm [name="carton_date"]').val();
		data.order_source_id = $('#cartonsearchFrm [name="order_source_id"]').val();
		data.prod_source_id = $('#cartonsearchFrm [name="prod_source_id"]').val();
		let self = this;
		$('#cartonsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getcarton/",
			onClickRow: function(index,row){
					$('#prodgmtexfactoryFrm [name=prod_gmt_carton_entry_id]').val(row.id);
					$('#prodgmtexfactoryFrm [name=company_id]').val(row.company_id);
					$('#prodgmtexfactoryFrm [name=buyer_id]').val(row.buyer_id);
					$('#prodgmtexfactoryFrm [name=location_id]').val(row.location_id);
					$('#prodgmtexfactoryFrm [name=supplier_id]').val(row.supplier_id);
					$('#opencartonwindow').window('close');
			}
			}).datagrid('enableFilter');
	} */

	pdf(){
		var id = $('#prodgmtexfactoryFrm [name=id]').val();
		if(id==''){
			alert("Select A Challan First");
			return;
		}
		window.open(this.route+"/exfactorypdf?id="+id);
	}

		openExpInvoiceWindow(){
		$('#openinvoicewindow').window('open');
	}
	
	getParams(){
		let params = {};
		params.invoice_no = $('#expinvoicesearchFrm [name="invoice_no"]').val();
		params.invoice_date = $('#expinvoicesearchFrm [name="invoice_date"]').val();
		return params;
	}

	searchExpInvoice(){
		let params = this.getParams();
		let adv=axios.get(this.route+"/getexpinvoice",{params})
		.then(function(response){
			$('#expinvoicesearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showExpInvoiceGrid(data){
		let self = this;
		$('#expinvoicesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtexfactoryFrm [name=exp_invoice_id]').val(row.id);
				$('#prodgmtexfactoryFrm [name=invoice_no]').val(row.invoice_no);
				$('#openinvoicewindow').window('close');
				$('#expinvoicesearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	
}
window.MsProdGmtExFactory=new MsProdGmtExFactoryController(new MsProdGmtExFactoryModel());
MsProdGmtExFactory.showGrid();
MsProdGmtExFactory.showExpInvoiceGrid([]);

$('#prodgmtexfactorytabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_ex_factory_id = $('#prodgmtexfactoryFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_ex_factory_id=prod_gmt_ex_factory_id;

	 if(index==1){
		 if(prod_gmt_ex_factory_id===''){
			 $('#prodgmtexfactorytabs').tabs('select',0);
			 msApp.showError('Select an Ex Factory entry First',0);
			 return;
		  }
		 //$('#prodgmtexfactoryqtyFrm  [name=prod_gmt_ex_factory_id]').val(prod_gmt_ex_factory_id);
		 //MsProdGmtExFactoryQty.get(prod_gmt_ex_factory_id);
		 //MsProdGmtExFactoryQty.showGrid(prod_gmt_ex_factory_id);
	  }
   }
}); 
