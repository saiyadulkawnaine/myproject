let MsInvCasReqModel = require('./MsInvCasReqModel');
require('./../../datagrid-filter.js');
class MsInvCasReqController {
	constructor(MsInvCasReqModel)
	{
		this.MsInvCasReqModel = MsInvCasReqModel;
		this.formId='invcasreqFrm';
		this.dataTable='#invcasreqTbl';
		this.route=msApp.baseUrl()+"/invcasreq"
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
			this.MsInvCasReqModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvCasReqModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invcasreqFrm [id="demand_by_id"]').combobox('setValue', '');
		$('#invcasreqFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvCasReqModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvCasReqModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invcasreqTbl').datagrid('reload');
		msApp.resetForm('invcasreqFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		crMst=this.MsInvCasReqModel.get(index,row);
		crMst.then(function(response){
			$('#invcasreqFrm [id="demand_by_id"]').combobox('setValue', response.data.fromData.demand_by_id);
			$('#invcasreqFrm [id="price_verified_by_id"]').combobox('setValue', response.data.fromData.price_verified_by_id);
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
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvCasReq.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf()
	{
		var id = $('#invcasreqFrm [name=id]').val();
		if(id==""){
			alert("Select a Cash Requisition First");
			return;
		}
		window.open(this.route+"/getcrpdf?id="+id);
	}
}
window.MsInvCasReq=new MsInvCasReqController(new MsInvCasReqModel());
MsInvCasReq.showGrid();
$('#cashtabs').tabs({
    onSelect:function(title,index){
        let inv_pur_req_id = $('#invcasreqFrm [name=id]').val();
        
        var data={};
		    data.inv_pur_req_id=inv_pur_req_id;
        if(index==1){
				if(inv_pur_req_id===''){
					$('#cashtabs').tabs('select',0);
					msApp.showError('Select A Requisition First',0);
					return;
			    }
				$('#invcasreqitemFrm  [name=inv_pur_req_id]').val(inv_pur_req_id);
				MsInvCasReqItem.showGrid(inv_pur_req_id);
            }
        if(index==2){
				if(inv_pur_req_id===''){
					$('#cashtabs').tabs('select',0);
					msApp.showError('Select A Requisition First',0);
					return;
			    }
				$('#invcasreqpaidFrm  [name=inv_pur_req_id]').val(inv_pur_req_id);
				MsInvCasReqPaid.showGrid(inv_pur_req_id);
            }
    }
});

