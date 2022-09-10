require('./../datagrid-filter.js');
let MsDayTargetTransferModel = require('./MsDayTargetTransferModel');
class MsDayTargetTransferController {
	constructor(MsDayTargetTransferModel)
	{
		this.MsDayTargetTransferModel = MsDayTargetTransferModel;
		this.formId='daytargettransferFrm';
		this.dataTable='#daytargettransferTbl';
		this.route=msApp.baseUrl()+"/daytargettransfer"
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
			this.MsDayTargetTransferModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsDayTargetTransferModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#daytargettransferFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsDayTargetTransferModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsDayTargetTransferModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#daytargettransferTbl').datagrid('reload');
		$('#daytargettransferFrm  [name=id]').val(d.id);
		$('#daytargettransferFrm  [name=entry_id]').val(d.entry_id);
		msApp.resetForm('daytargettransferFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let daytargettransfer=this.MsDayTargetTransferModel.get(index,row);
		daytargettransfer.then(function(response){
			$('#daytargettransferFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsDayTargetTransfer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	opendaytargetorderWindow(){
		$('#opendaytargetorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#opendaytargetorderFrm [name=style_ref]').val();
		params.job_no=$('#opendaytargetorderFrm [name=job_no]').val();
		params.sale_order_no=$('#opendaytargetorderFrm [name=sale_order_no]').val();
		
		return params;
	}
	searchDayTargetOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdaytargettransfer',{params})
		.then(function(response){
			$('#opendaytargetorderTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showDayTargetOrderGrid(data){
		let self=this;
		$('#opendaytargetorderTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#daytargettransferFrm [name=sales_order_id]').val(row.sales_order_id);
				$('#daytargettransferFrm [name=sale_order_no]').val(row.sale_order_no);		
				$('#daytargettransferFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#daytargettransferFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#daytargettransferFrm [name=ship_date]').val(row.ship_date);
							
				$('#opendaytargetorderwindow').window('close');

			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	sendSms()
	{
		let params={};
		params.target_date=$('#daytargettransferFrm [name=target_date]').val();
		if(params.target_date==''){
			alert('Select a Target Date');
			return;
		}

		let d= axios.get(this.route+'/sendsms',{params})
		.then(function(response){
			alert('SMS Send Successfull');
			//$('#opendaytargetorderTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			alert('SMS Send Faild');
			console.log(error);
		});
	}
}


window.MsDayTargetTransfer=new MsDayTargetTransferController(new MsDayTargetTransferModel());
MsDayTargetTransfer.showGrid();
MsDayTargetTransfer.showDayTargetOrderGrid([]);
