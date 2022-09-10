let MsInvFinishFabRcvModel = require('./MsInvFinishFabRcvModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabRcvController {
	constructor(MsInvFinishFabRcvModel)
	{
		this.MsInvFinishFabRcvModel = MsInvFinishFabRcvModel;
		this.formId='invfinishfabrcvFrm';
		this.dataTable='#invfinishfabrcvTbl';
		this.route=msApp.baseUrl()+"/invfinishfabrcv"
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
			this.MsInvFinishFabRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabrcvTbl').datagrid('reload');
		msApp.resetForm('invfinishfabrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabRcvModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invfinishfabrcvitemFrm');
			if(response.data.fromData.receive_basis_id==2 || response.data.fromData.receive_basis_id==3){
				//$('#invfinishfabrcvitemFrm  [name=composition]').removeAttr("disabled");
				$('#invfinishfabrcvitemFrm  [name=composition]').attr("disabled","disabled");
				//$('#invfinishfabrcvitemTbimport').removeAttr("disabled");
				$('#invfinishfabrcvitemTbimport').attr("onClick","MsInvFinishFabRcvItem.openfinishfabWindow()");
				$('#invfinishfabrcvitemFrm  [name=rate]').removeAttr("disabled");
				$('#invfinishfabrcvitemFrm  [name=rate]').removeAttr("readonly");
				$('#invfinishfabrcvitemFrm  [name=currency_code]').val("BDT");
				$('#invfinishfabrcvitemFrm  [name=exch_rate]').val(1);

			}else{
				$('#invfinishfabrcvitemFrm  [name=composition]').attr("disabled","disabled");
				$('#invfinishfabrcvitemTbimport').attr("onClick","MsInvFinishFabRcvItem.import()");
				$('#invfinishfabrcvitemFrm  [name=rate]').attr("readonly",'readonly');
				$('#invfinishfabrcvitemFrm  [name=currency_code]').val("");
				$('#invfinishfabrcvitemFrm  [name=exch_rate]').val('');
			}
			if(response.data.fromData.receive_against_id==9){
				$('#invfinishfabrcvitemFrm  [name=color_id]').attr("readonly",'readonly');
			}else{
				$('#invfinishfabrcvitemFrm  [name=color_id]').removeAttr("readonly");;
			}
		}).catch(function(error){
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
		return '<a href="javascript:void(0)"  onClick="MsRcvFinishFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getChallan()
	{

		let params={};
		let data= axios.get(this.route+'/getchallan')
		.then(function (response) {
			$('#invfinishfabrcvchallanTbl').datagrid('loadData', response.data).datagrid('enableFilter');
			$('#invfinishfabrcvchallanWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridChallan(data){
		let self=this;
		var dg=$('#invfinishfabrcvchallanTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#invfinishfabrcvFrm  [name=prod_finish_dlv_id]').val(row.id);
				$('#invfinishfabrcvFrm  [name=challan_no]').val(row.dlv_no);
				$('#invfinishfabrcvFrm  [name=company_id]').val(row.rcv_company_id);
				$('#invfinishfabrcvFrm  [name=company_name]').val(row.rcv_company_name);
				$('#invfinishfabrcvFrm  [name=receive_date]').val(row.dlv_date);
				$('#invfinishfabrcvchallanWindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showPdf()
	{
		var id= $('#invfinishfabrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invfinishfabrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvFinishFabRcv=new MsInvFinishFabRcvController(new MsInvFinishFabRcvModel());
MsInvFinishFabRcv.showGrid();
MsInvFinishFabRcv.showGridChallan([]);

$('#invfinishfabrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_finish_fab_rcv_id = $('#invfinishfabrcvFrm [name=inv_finish_fab_rcv_id]').val();
        let inv_finish_fab_rcv_item_id = $('#invfinishfabrcvitemFrm [name=id]').val();
        if(index==1){
			if(inv_finish_fab_rcv_id===''){
				$('#invfinishfabrcvtabs').tabs('select',0);
				msApp.showError('Select Finish Fab Receive Entry First',0);
				return;
		    }
			$('#invfinishfabrcvitemFrm  [name=inv_finish_fab_rcv_id]').val(inv_finish_fab_rcv_id);
			MsInvFinishFabRcvItem.get(inv_finish_fab_rcv_id);
        }

        if(index==2){
			if(inv_finish_fab_rcv_item_id===''){
				$('#invfinishfabrcvtabs').tabs('select',1);
				msApp.showError('Select Finish Fab Receive Item Entry First',0);
				return;
		    }
			$('#invfinishfabrcvitemsplitFrm  [name=inv_finish_fab_rcv_item_id]').val(inv_finish_fab_rcv_item_id);
			MsInvFinishFabRcvItemSplit.get(inv_finish_fab_rcv_item_id);
        }
    }
});

