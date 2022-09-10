let MsInvFinishFabTransInModel = require('./MsInvFinishFabTransInModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabTransInController {
	constructor(MsInvFinishFabTransInModel)
	{
		this.MsInvFinishFabTransInModel = MsInvFinishFabTransInModel;
		this.formId='invfinishfabtransinFrm';
		this.dataTable='#invfinishfabtransinTbl';
		this.route=msApp.baseUrl()+"/invfinishfabtransin"
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
			this.MsInvFinishFabTransInModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransInModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabTransInModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabTransInModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabtransinTbl').datagrid('reload');
		msApp.resetForm('invfinishfabtransinFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabTransInModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invfinishfabtransinitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransIn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	challanWindow(){
                $('#invfinishfabtransinchallansearchwindow').window('open');
	}

	serachChallan(){
		let issue_no=$('#invfinishfabtransinchallansearchFrm [name=issue_no]').val();
		let from_company_id=$('#invfinishfabtransinFrm [name=from_company_id]').val();
		let params={};
		params.issue_no=issue_no;
		params.from_company_id=from_company_id;
		let d=axios.get(this.route+'/getchallan',{params})
		.then(function(response){
			$('#invfinishfabtransinchallansearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	challanSearchGrid(data){
		let self=this;
		$('#invfinishfabtransinchallansearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invfinishfabtransinFrm [name=inv_isu_id]').val(row.id);
				$('#invfinishfabtransinFrm [name=challan_no]').val(row.issue_no);
				$('#invfinishfabtransinFrm [name=from_company_id]').val(row.company_id);
				$('#invfinishfabtransinFrm [name=company_id]').val(row.to_company_id);
				/*$('#invfinishfabtransinitemsearchFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
				$('#invfinishfabtransinitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invfinishfabtransinitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invfinishfabtransinitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				$('#invfinishfabtransinchallansearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	showPdf()
	{
		var id= $('#invfinishfabtransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invfinishfabtransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvFinishFabTransIn=new MsInvFinishFabTransInController(new MsInvFinishFabTransInModel());
MsInvFinishFabTransIn.showGrid();
MsInvFinishFabTransIn.challanSearchGrid([]);

$('#invfinishfabtransintabs').tabs({
    onSelect:function(title,index){
        let inv_finish_fab_rcv_id = $('#invfinishfabtransinFrm [name=inv_finish_fab_rcv_id]').val();
         let inv_finish_fab_rcv_item_id = $('#invfinishfabtransinitemFrm [name=id]').val();
        if(index==1){
			if(inv_finish_fab_rcv_id===''){
				$('#invfinishfabtransintabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabtransinitemFrm')
			$('#invfinishfabtransinitemFrm  [name=inv_finish_fab_rcv_id]').val(inv_finish_fab_rcv_id);
			MsInvFinishFabTransInItem.get(inv_finish_fab_rcv_id);
        }

       /* if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invfinishfabtransintabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabtransinitemdtlFrm')
			$('#invfinishfabtransinitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvFinishFabTransInItemDtl.get(inv_dye_chem_rcv_item_id);
        }*/
    }
});

