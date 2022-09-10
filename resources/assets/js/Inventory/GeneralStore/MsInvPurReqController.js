let MsInvPurReqModel = require('./MsInvPurReqModel');
require('./../../datagrid-filter.js');
class MsInvPurReqController {
	constructor(MsInvPurReqModel)
	{
		this.MsInvPurReqModel = MsInvPurReqModel;
		this.formId='invpurreqFrm';
		this.dataTable='#invpurreqTbl';
		this.route=msApp.baseUrl()+"/invpurreq"
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
			this.MsInvPurReqModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvPurReqModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#invpurreqFrm  [name=pay_mode]').val(1);
		$('#invpurreqFrm [id="demand_by_id"]').combobox('setValue', '');
		$('#invpurreqFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvPurReqModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvPurReqModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invpurreqTbl').datagrid('reload');
		msApp.resetForm('invpurreqFrm');
	}

	edit(index,row)
	{
		
		row.route=this.route;
		row.formId=this.formId;
		prMaster=this.MsInvPurReqModel.get(index,row);
		prMaster.then(function(response){
			$('#invpurreqFrm [id="demand_by_id"]').combobox('setValue', response.data.fromData.demand_by_id);
			$('#invpurreqFrm [id="price_verified_by_id"]').combobox('setValue', response.data.fromData.price_verified_by_id);
		}).catch(function(error){
			console.log(error);
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
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvPurReq.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	//////////////////////////////////
//	formatprintpdf(value,row)
//	{
//		return '<a href="javascript:void(0)"  onClick="MsInvPurReq.printpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print</span></a>';
//	}

	pdf()
	{
		var id = $('#invpurreqFrm [name=id]').val();
		if(id==""){
			alert("Select a Purchase Requisition No");
			return;
		}
		window.open(this.route+"/getprpdf?id="+id);
	}
	////////////////////////////////////
	approvedmsg(value,row,index)
	{
		if (row.final_approved_by){
		    return 'color:red;';
	    }
	}

	searchInvPurReq()
	{
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to = $('#date_to').val();
		params.company_id = $("#company_id").val();
		let data= axios.get(this.route+"/getallinvpurreq",{params});
		data.then(function (response) {
			$('#invpurreqTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsInvPurReq=new MsInvPurReqController(new MsInvPurReqModel());
MsInvPurReq.showGrid();

$('#invreqtabs').tabs({
    onSelect:function(title,index){
        let inv_pur_req_id = $('#invpurreqFrm [name=id]').val();
        
        var data={};
		data.inv_pur_req_id=inv_pur_req_id;
        if(index==1){
			if(inv_pur_req_id===''){
				$('#invreqtabs').tabs('select',0);
				msApp.showError('Select Requisition Entry First',0);
				return;
		    }
		    msApp.resetForm('invpurreqitemFrm');
			$('#invpurreqitemFrm  [name=inv_pur_req_id]').val(inv_pur_req_id);
			MsInvPurReqItem.showGrid(inv_pur_req_id);
        }
        if(index==2){
			if(inv_pur_req_id===''){
				$('#invreqtabs').tabs('select',0);
				msApp.showError('Select Requisition Entry First',0);
				return;
		    }
		    msApp.resetForm('invpurreqassetbreakdownFrm');
			$('#invpurreqassetbreakdownFrm  [name=inv_pur_req_id]').val(inv_pur_req_id);
			MsInvPurReqAssetBreakdown.showGrid(inv_pur_req_id);
        }
        if(index==3){
			if(inv_pur_req_id===''){
				$('#invreqtabs').tabs('select',0);
				msApp.showError('Select Requisition Entry First',0);
				return;
		    }
		    msApp.resetForm('invpurreqpaidFrm');
			$('#invpurreqpaidFrm  [name=inv_pur_req_id]').val(inv_pur_req_id);
			MsInvPurReqPaid.showGrid(inv_pur_req_id);
        }
    }
});

