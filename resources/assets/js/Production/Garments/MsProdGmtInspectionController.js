require('./../../datagrid-filter.js');
let MsProdGmtInspectionModel = require('./MsProdGmtInspectionModel');
class MsProdGmtInspectionController {
	constructor(MsProdGmtInspectionModel)
	{
		this.MsProdGmtInspectionModel = MsProdGmtInspectionModel;
		this.formId='prodgmtinspectionFrm';
		this.dataTable='#prodgmtinspectionTbl';
		this.route=msApp.baseUrl()+"/prodgmtinspection"
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
			this.MsProdGmtInspectionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtInspectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#inspectionordergmtcosi').html('');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtInspectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtInspectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtinspectionTbl').datagrid('reload');
		msApp.resetForm('prodgmtinspectionFrm');
		MsProdGmtInspectionOrder.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtInspectionModel.get(index,row);
		
	}

	showGrid(){

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtInspection.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openInspecSalesOrderWindow(){
		$('#openinspectionwindow').window('open');
	}
	searchInspectionSaleOrderGrid(){
		let data={};
			data.style_ref = $('#inspectionordersearchFrm  [name=style_ref]').val();
			data.job_no = $('#inspectionordersearchFrm  [name=job_no]').val();
			data.sale_order_no = $('#inspectionordersearchFrm  [name=sale_order_no]').val();
			let self=this;
			var ex=$('#inspectionordersearchTbl').datagrid({
				method:'get',
				border:false,
				singleSelect:true,
				fit:true,
				queryParams:data,
				url:msApp.baseUrl()+"/prodgmtinspection/getsalesordercountry",
				onClickRow: function(index,row){
				$('#prodgmtinspectionFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtinspectionFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtinspectionFrm [name=country_id]').val(row.country_id);
				$('#prodgmtinspectionFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtinspectionFrm [name=job_no]').val(row.job_no);
				$('#prodgmtinspectionFrm [name=company_id]').val(row.company_id);
				$('#prodgmtinspectionFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtinspectionFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtinspectionFrm [name=ship_date]').val(row.ship_date);
					//$('#dlvinputsearchTbl').datagrid('loadData', []);
				$('#openinspectionwindow').window('close');
			}
		});
		ex.datagrid('enableFilter')/* .datagrid('loadData', data) */;
	}
	
}
window.MsProdGmtInspection=new MsProdGmtInspectionController(new MsProdGmtInspectionModel());
MsProdGmtInspection.showGrid();
/* 
 $('#prodgmtinspectiontabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_inspection_id = $('#prodgmtinspectionFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_inspection_id=prod_gmt_inspection_id;

	 if(index==1){
		 if(prod_gmt_inspection_id===''){
			 $('#prodgmtinspectiontabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  $('#prodgmtinspectionorderFrm  [name=prod_gmt_inspection_id]').val(prod_gmt_inspection_id);
		  MsProdGmtInspectionOrder.create(prod_gmt_inspection_id);
	  }

   }
});  */
