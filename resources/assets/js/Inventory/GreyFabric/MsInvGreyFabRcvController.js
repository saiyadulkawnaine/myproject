let MsInvGreyFabRcvModel = require('./MsInvGreyFabRcvModel');
require('./../../datagrid-filter.js');
class MsInvGreyFabRcvController {
	constructor(MsInvGreyFabRcvModel)
	{
		this.MsInvGreyFabRcvModel = MsInvGreyFabRcvModel;
		this.formId='invgreyfabrcvFrm';
		this.dataTable='#invgreyfabrcvTbl';
		this.route=msApp.baseUrl()+"/invgreyfabrcv"
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
			this.MsInvGreyFabRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgreyfabrcvTbl').datagrid('reload');
		msApp.resetForm('invgreyfabrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGreyFabRcvModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgreyfabrcvitemFrm');
			if(response.data.fromData.receive_basis_id==2 || response.data.fromData.receive_basis_id==3){
				//$('#invgreyfabrcvitemFrm  [name=composition]').removeAttr("disabled");
				$('#invgreyfabrcvitemFrm  [name=composition]').attr("disabled","disabled");
				//$('#invgreyfabrcvitemTbimport').removeAttr("disabled");
				$('#invgreyfabrcvitemTbimport').attr("onClick","MsInvGreyFabRcvItem.opengreyfabWindow()");
				$('#invgreyfabrcvitemFrm  [name=rate]').removeAttr("disabled");
				$('#invgreyfabrcvitemFrm  [name=rate]').removeAttr("readonly");
				$('#invgreyfabrcvitemFrm  [name=currency_code]').val("BDT");
				$('#invgreyfabrcvitemFrm  [name=exch_rate]').val(1);

			}else{
				$('#invgreyfabrcvitemFrm  [name=composition]').attr("disabled","disabled");
				$('#invgreyfabrcvitemTbimport').attr("onClick","MsInvGreyFabRcvItem.import()");
				$('#invgreyfabrcvitemFrm  [name=rate]').attr("readonly",'readonly');
				$('#invgreyfabrcvitemFrm  [name=currency_code]').val("");
				$('#invgreyfabrcvitemFrm  [name=exch_rate]').val('');
			}
			if(response.data.fromData.receive_against_id==9){
				$('#invgreyfabrcvitemFrm  [name=color_id]').attr("readonly",'readonly');
			}else{
				$('#invgreyfabrcvitemFrm  [name=color_id]').removeAttr("readonly");;
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
		return '<a href="javascript:void(0)"  onClick="MsRcvGreyFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getChallan()
	{

		let params={};
		let data= axios.get(this.route+'/getchallan')
		.then(function (response) {
			$('#invgreyfabrcvchallanTbl').datagrid('loadData', response.data).datagrid('enableFilter');
			$('#invgreyfabrcvchallanWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridChallan(data){
		let self=this;
		var dg=$('#invgreyfabrcvchallanTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#invgreyfabrcvFrm  [name=prod_knit_dlv_id]').val(row.id);
				$('#invgreyfabrcvFrm  [name=challan_no]').val(row.dlv_no);
				$('#invgreyfabrcvFrm  [name=company_id]').val(row.company_id);
				$('#invgreyfabrcvFrm  [name=company_name]').val(row.company_name);
				$('#invgreyfabrcvFrm  [name=receive_date]').val(row.dlv_date);
				$('#invgreyfabrcvchallanWindow').window('close');
				//$('#prodknitqcrollsimilarselectedTbl').datagrid('deleteRow', index);

			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showPdf()
	{
		var id= $('#invgreyfabrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invgreyfabrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvGreyFabRcv=new MsInvGreyFabRcvController(new MsInvGreyFabRcvModel());
MsInvGreyFabRcv.showGrid();
MsInvGreyFabRcv.showGridChallan([]);

$('#invgreyfabrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_greyfab_rcv_id = $('#invgreyfabrcvFrm [name=inv_greyfab_rcv_id]').val();
        let inv_grey_fab_rcv_item_id = $('#invgreyfabrcvitemFrm [name=id]').val();
        if(index==1){
			if(inv_greyfab_rcv_id===''){
				$('#invgreyfabrcvtabs').tabs('select',0);
				msApp.showError('Select Grey Fab Receive Entry First',0);
				return;
		    }
			$('#invgreyfabrcvitemFrm  [name=inv_greyfab_rcv_id]').val(inv_greyfab_rcv_id);
			MsInvGreyFabRcvItem.get(inv_greyfab_rcv_id);
        }

        if(index==2){
			if(inv_grey_fab_rcv_item_id===''){
				$('#invgreyfabrcvtabs').tabs('select',1);
				msApp.showError('Select Grey Fab Receive Item Entry First',0);
				return;
		    }
			$('#invgreyfabrcvitemsplitFrm  [name=inv_grey_fab_rcv_item_id]').val(inv_grey_fab_rcv_item_id);
			MsInvGreyFabRcvItemSplit.get(inv_grey_fab_rcv_item_id);
        }
    }
});

