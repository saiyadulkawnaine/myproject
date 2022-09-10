let MsInvFinishFabRcvPurModel = require('./MsInvFinishFabRcvPurModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabRcvPurController {
	constructor(MsInvFinishFabRcvPurModel)
	{
		this.MsInvFinishFabRcvPurModel = MsInvFinishFabRcvPurModel;
		this.formId='invfinishfabrcvpurFrm';
		this.dataTable='#invfinishfabrcvpurTbl';
		this.route=msApp.baseUrl()+"/invfinishfabrcvpur"
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
			this.MsInvFinishFabRcvPurModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvPurModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvPurModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvPurModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabrcvpurTbl').datagrid('reload');
		msApp.resetForm('invfinishfabrcvpurFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabRcvPurModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invfinishfabrcvpuritemFrm');
			if(response.data.fromData.receive_basis_id==2 || response.data.fromData.receive_basis_id==3){
				//$('#invfinishfabrcvpuritemFrm  [name=composition]').removeAttr("disabled");
				$('#invfinishfabrcvpuritemFrm  [name=composition]').attr("disabled","disabled");
				//$('#invfinishfabrcvpuritemTbimport').removeAttr("disabled");
				$('#invfinishfabrcvpuritemTbimport').attr("onClick","MsInvFinishFabRcvPurItem.openfinishfabWindow()");
				$('#invfinishfabrcvpuritemFrm  [name=rate]').removeAttr("disabled");
				$('#invfinishfabrcvpuritemFrm  [name=rate]').removeAttr("readonly");
				$('#invfinishfabrcvpuritemFrm  [name=currency_code]').val("BDT");
				$('#invfinishfabrcvpuritemFrm  [name=exch_rate]').val(1);

			}else{
				$('#invfinishfabrcvpuritemFrm  [name=composition]').attr("disabled","disabled");
				$('#invfinishfabrcvpuritemTbimport').attr("onClick","MsInvFinishFabRcvPurItem.import()");
				$('#invfinishfabrcvpuritemFrm  [name=rate]').attr("readonly",'readonly');
				$('#invfinishfabrcvpuritemFrm  [name=currency_code]').val("");
				$('#invfinishfabrcvpuritemFrm  [name=exch_rate]').val('');
			}
			if(response.data.fromData.receive_against_id==9){
				$('#invfinishfabrcvpuritemFrm  [name=color_id]').attr("readonly",'readonly');
			}else{
				$('#invfinishfabrcvpuritemFrm  [name=color_id]').removeAttr("readonly");;
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


	poWindow(){
		$('#invfinishfabrcvpurpoWindow').window('open');
	}

	getPo()
	{

		let params={};
		let po_no=$('#invfinishfabrcvpurpoFrm  [name=po_no]').val();
		let company_id=$('#invfinishfabrcvpurpoFrm  [name=company_id]').val();
		let supplier_id=$('#invfinishfabrcvpurpoFrm  [name=supplier_id]').val();
		params.po_no=po_no;
		params.company_id=company_id;
		params.supplier_id=supplier_id;
		let data= axios.get(this.route+'/getpo',{params})
		.then(function (response) {
			$('#invfinishfabrcvpurpoTbl').datagrid('loadData', response.data).datagrid('enableFilter');
			
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridPo(data){
		let self=this;
		var dg=$('#invfinishfabrcvpurpoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#invfinishfabrcvpurFrm  [name=po_fabric_id]').val(row.id);
				$('#invfinishfabrcvpurFrm  [name=po_no]').val(row.po_no);
				$('#invfinishfabrcvpurFrm  [name=company_id]').val(row.company_id);
				$('#invfinishfabrcvpurFrm  [name=company_name]').val(row.company_name);
				$('#invfinishfabrcvpurFrm  [name=supplier_id]').val(row.supplier_id);
				$('#invfinishfabrcvpurFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invfinishfabrcvpurpoWindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showPdf()
	{
		var id= $('#invfinishfabrcvpurFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invfinishfabrcvpurFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/getchallan?id="+id);
	}

}
window.MsInvFinishFabRcvPur=new MsInvFinishFabRcvPurController(new MsInvFinishFabRcvPurModel());
MsInvFinishFabRcvPur.showGrid();
MsInvFinishFabRcvPur.showGridPo([]);

$('#invfinishfabrcvpurtabs').tabs({
    onSelect:function(title,index){
        let inv_finish_fab_rcv_id = $('#invfinishfabrcvpurFrm [name=inv_finish_fab_rcv_id]').val();
        let inv_finish_fab_rcv_fabric_id = $('#invfinishfabrcvpurfabricFrm [name=id]').val();
        if(index==1){
			if(inv_finish_fab_rcv_id===''){
				$('#invfinishfabrcvpurtabs').tabs('select',0);
				msApp.showError('Select Finish Fab Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabrcvpurfabricFrm');
			MsInvFinishFabRcvPurFabric.get(inv_finish_fab_rcv_id);
        }

        if(index==2){
			if(inv_finish_fab_rcv_fabric_id===''){
				$('#invfinishfabrcvpurtabs').tabs('select',1);
				msApp.showError('Select Finish Fab Receive Item Entry First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabrcvpuritemFrm');
			MsInvFinishFabRcvPurItem.get(inv_finish_fab_rcv_fabric_id);
        }
    }
});

