require('./../datagrid-filter.js');
let MsTargetTransferModel = require('./MsTargetTransferModel');
class MsTargetTransferController {
	constructor(MsTargetTransferModel)
	{
		this.MsTargetTransferModel = MsTargetTransferModel;
		this.formId='targettransferFrm';
		this.dataTable='#targettransferTbl';
		this.route=msApp.baseUrl()+"/targettransfer"
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
		let formData=$("#"+this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsTargetTransferModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsTargetTransferModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#targettransferFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTargetTransferModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTargetTransferModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#targettransferTbl').datagrid('reload');
		$('#targettransferFrm  [name=id]').val(d.id);
		$('#targettransferFrm  [name=entry_id]').val(d.entry_id);
		msApp.resetForm('targettransferFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let targettransfer=this.MsTargetTransferModel.get(index,row);
		targettransfer.then(function(response){
			$('#targettransferFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			MsTargetTransfer.getInfo($('#targettransferFrm  [name=process_id] option:selected').val())

		}).catch(function(error){
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
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsTargetTransfer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	opentargetorderWindow(){
		$('#opentargetorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#opentargetorderFrm [name=style_ref]').val();
		params.job_no=$('#opentargetorderFrm [name=job_no]').val();
		params.sale_order_no=$('#opentargetorderFrm [name=sale_order_no]').val();
		
		return params;
	}
	searchTargetOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/gettargettransfer',{params})
		.then(function(response){
			$('#opentargetorderTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showTargetOrderGrid(data){
		let self=this;
		$('#opentargetorderTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#targettransferFrm [name=sales_order_id]').val(row.sales_order_id);
				$('#targettransferFrm [name=sale_order_no]').val(row.sale_order_no);		
				//$('#targettransferFrm [name=produced_company_id]').val(row.produced_company_id);
				//$('#targettransferFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#targettransferFrm [name=ship_date]').val(row.ship_date);
				$('#targettransferFrm [name=style_gmt_name]').val(row.item_description);
				$('#targettransferFrm [name=style_gmt_id]').val(row.style_gmt_id);
				$('#opentargetorderTbl').datagrid('loadData', []);			
				$('#opentargetorderwindow').window('close');
				MsTargetTransfer.getInfo($('#targettransferFrm  [name=process_id] option:selected').val())

			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	getInfo(process_id)
	{
		let params={};
		params.process_id=process_id;
		params.sales_order_id=$('#targettransferFrm [name=sales_order_id]').val();
		params.style_gmt_id=$('#targettransferFrm [name=style_gmt_id]').val();
		if(params.process_id==''){
			alert('Please Select Process');
			return;
		}
		if(params.sales_order_id==''){
			alert('Please Select Sales Order');
			return;
		}
		if(params.style_gmt_id==''){
			alert('Please Select Gmt. Item');
			return;
		}
		$('#targettransferFrmInfo').html('');
		let d= axios.get(this.route+'/getinfo',{params})
		.then(function(response){
			//$('#opentargetorderTbl').datagrid('loadData', response.data);
			$('#targettransferFrmInfo').html(response.data);
		}).catch(function (error) {
			console.log(error);
		});

	}
}


window.MsTargetTransfer=new MsTargetTransferController(new MsTargetTransferModel());
MsTargetTransfer.showGrid();
MsTargetTransfer.showTargetOrderGrid([]);
