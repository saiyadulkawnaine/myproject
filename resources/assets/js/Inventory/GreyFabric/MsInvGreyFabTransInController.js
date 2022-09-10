let MsInvGreyFabTransInModel = require('./MsInvGreyFabTransInModel');
require('./../../datagrid-filter.js');
class MsInvGreyFabTransInController {
	constructor(MsInvGreyFabTransInModel)
	{
		this.MsInvGreyFabTransInModel = MsInvGreyFabTransInModel;
		this.formId='invgreyfabtransinFrm';
		this.dataTable='#invgreyfabtransinTbl';
		this.route=msApp.baseUrl()+"/invgreyfabtransin"
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
			this.MsInvGreyFabTransInModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransInModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabTransInModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabTransInModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgreyfabtransinTbl').datagrid('reload');
		msApp.resetForm('invgreyfabtransinFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGreyFabTransInModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgreyfabtransinitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransIn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	challanWindow(){
                $('#invgreyfabtransinchallansearchwindow').window('open');
	}

	serachChallan(){
		let issue_no=$('#invgreyfabtransinchallansearchFrm [name=issue_no]').val();
		let from_company_id=$('#invgreyfabtransinFrm [name=from_company_id]').val();
		let params={};
		params.issue_no=issue_no;
		params.from_company_id=from_company_id;
		let d=axios.get(this.route+'/getchallan',{params})
		.then(function(response){
			$('#invgreyfabtransinchallansearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	challanSearchGrid(data){
		let self=this;
		$('#invgreyfabtransinchallansearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgreyfabtransinFrm [name=inv_isu_id]').val(row.id);
				$('#invgreyfabtransinFrm [name=challan_no]').val(row.issue_no);
				$('#invgreyfabtransinFrm [name=from_company_id]').val(row.company_id);
				$('#invgreyfabtransinFrm [name=company_id]').val(row.to_company_id);
				/*$('#invgreyfabtransinitemsearchFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
				$('#invgreyfabtransinitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invgreyfabtransinitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invgreyfabtransinitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				$('#invgreyfabtransinchallansearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	showPdf()
	{
		var id= $('#invgreyfabtransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invgreyfabtransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvGreyFabTransIn=new MsInvGreyFabTransInController(new MsInvGreyFabTransInModel());
MsInvGreyFabTransIn.showGrid();
MsInvGreyFabTransIn.challanSearchGrid([]);

$('#invgreyfabtransintabs').tabs({
    onSelect:function(title,index){
        let inv_grey_fab_rcv_id = $('#invgreyfabtransinFrm [name=inv_grey_fab_rcv_id]').val();
         let inv_grey_fab_rcv_item_id = $('#invgreyfabtransinitemFrm [name=id]').val();
        if(index==1){
			if(inv_grey_fab_rcv_id===''){
				$('#invgreyfabtransintabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invgreyfabtransinitemFrm')
			$('#invgreyfabtransinitemFrm  [name=inv_grey_fab_rcv_id]').val(inv_grey_fab_rcv_id);
			MsInvGreyFabTransInItem.get(inv_grey_fab_rcv_id);
        }

       /* if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invgreyfabtransintabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invgreyfabtransinitemdtlFrm')
			$('#invgreyfabtransinitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvGreyFabTransInItemDtl.get(inv_dye_chem_rcv_item_id);
        }*/
    }
});

