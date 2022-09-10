require('./../../datagrid-filter.js');
let MsProdGmtPrintRcvModel = require('./MsProdGmtPrintRcvModel');
class MsProdGmtPrintRcvController {
	constructor(MsProdGmtPrintRcvModel)
	{
		this.MsProdGmtPrintRcvModel = MsProdGmtPrintRcvModel;
		this.formId='prodgmtprintrcvFrm';
		this.dataTable='#prodgmtprintrcvTbl';
		this.route=msApp.baseUrl()+"/prodgmtprintrcv"
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
		/* let formData=$("#"+this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtPrintRcvModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtPrintRcvModel.save(this.route,'POST',formData ,this.response);
		} */
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtPrintRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtPrintRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtPrintRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtPrintRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtprintrcvTbl').datagrid('reload');
		//$('#prodgmtprintrcvFrm  [name=id]').val(d.id);
		$('#prodgmtprintrcvFrm  [name=receive_no]').val(d.receive_no);
		msApp.resetForm('prodgmtprintrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtPrintRcvModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtPrintRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDlvPrintWindow(){
		$('#opendlvprintwindow').window('open');
	}
	getParams(){
		let params={};
		params.supplier_id = $('#dlvprintsearchFrm  [name=supplier_id]').val();
		params.delivery_date = $('#dlvinputsearchFrm  [name=delivery_date]').val();
		return params;
	}
	searchDlvPrintGrid(){
		let params = this.getParams();
		let d = axios.get(this.route+'/getdlvprint',{params})
		.then(function(response){
			$('#dlvprintsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}
	showDlvPrintGrid(data){
		let self=this;
		$('#dlvprintsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow:function(index,row){
				$('#prodgmtprintrcvFrm  [name=prod_gmt_dlv_print_id]').val(row.id);
				$('#prodgmtprintrcvFrm  [name=challan_no]').val(row.challan_no);
				$('#prodgmtprintrcvFrm  [name=supplier_id]').val(row.supplier_id);
				$('#prodgmtprintrcvFrm  [name=supplier_name]').val(row.supplier_name);
				$('#prodgmtprintrcvFrm  [name=location_id]').val(row.location_id);
				$('#dlvprintsearchTbl').datagrid('loadData', []);
				$('#opendlvprintwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsProdGmtPrintRcv=new MsProdGmtPrintRcvController(new MsProdGmtPrintRcvModel());
MsProdGmtPrintRcv.showGrid();
MsProdGmtPrintRcv.showDlvPrintGrid([]);

 $('#prodgmtscreenprintrcvtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_print_rcv_id = $('#prodgmtprintrcvFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_print_rcv_id=prod_gmt_print_rcv_id;

	 if(index==1){
		 if(prod_gmt_print_rcv_id===''){
			 $('#prodgmtscreenprintrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtprintrcvorderFrm');
		  $('#prodgmtprintrcvorderFrm  [name=prod_gmt_print_rcv_id]').val(prod_gmt_print_rcv_id);
		  MsProdGmtPrintRcvOrder.showGrid(prod_gmt_print_rcv_id);
	  }

   }
});
