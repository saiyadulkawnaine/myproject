let MsInvDyeChemIsuRqAopModel = require('./MsInvDyeChemIsuRqAopModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRqAopController {
	constructor(MsInvDyeChemIsuRqAopModel)
	{
		this.MsInvDyeChemIsuRqAopModel = MsInvDyeChemIsuRqAopModel;
		this.formId='invdyechemisurqaopFrm';
		this.dataTable='#invdyechemisurqaopTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqaop"
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
			this.MsInvDyeChemIsuRqAopModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqAopModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invdyechemisurqaopFrm [id="buyer_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqAopModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqAopModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurqaopTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurqaopFrm');
		$('#invdyechemisurqaopFrm [id="buyer_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqAopModel.get(index,row);
		data.then(function(response){
			$('#invdyechemisurqaopFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			msApp.resetForm('invdyechemisurqitemaopFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisurqitemaopFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisurqitemaopFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqAop.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurqaopFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	searchRq(){
		let params={};
		params.from_rq_date=$('#from_rq_date').val();
		params.to_rq_date=$('#to_rq_date').val();
		let data= axios.get(this.route+"/getrq",{params});
		data.then(function (response) {
			$('#invdyechemisurqaopTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	openbatchWindow(){
		$('#invdyechemisurqaopbatchsearchwindow').window('open');
		$('#invdyechemisurqaopbatchsearchTbl').datagrid('loadData',[]);

	}
	batchSearchGrid(data){
		let self=this;
		$('#invdyechemisurqaopbatchsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqaopFrm [name=prod_aop_batch_id]').val(row.id);
				$('#invdyechemisurqaopFrm [name=batch_no]').val(row.batch_no);
				$('#invdyechemisurqaopFrm [name=company_id]').val(row.company_id);
				$('#invdyechemisurqaopFrm [name=fabric_color]').val(row.batch_color_name);
				$('#invdyechemisurqaopFrm [name=design_no]').val(row.design_no);
				$('#invdyechemisurqaopFrm [name=paste_wgt]').val(row.paste_wgt);
				$('#invdyechemisurqaopFrm [name=fabric_wgt]').val(row.fabric_wgt);
				$('#invdyechemisurqaopFrm [name=buyer_id]').val(row.buyer_id);
				$('#invdyechemisurqaopbatchsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachBatch(){
		let batch_date_from=$('#invdyechemisurqaopbatchsearchFrm [name=batch_date_from]').val();
		let batch_date_to=$('#invdyechemisurqaopbatchsearchFrm [name=batch_date_to]').val();
		let params={};
		params.batch_date_from=batch_date_from;
		params.batch_date_to=batch_date_to;
		let d=axios.get(this.route+'/getbatch',{params})
		.then(function(response){
			$('#invdyechemisurqaopbatchsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGridOld()
	{
		let self=this;
		$('#invdyechemisurqaopoldTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route+'/oldrq',
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatoldpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqAop.showPdfOld(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	showPdfOld(e,id)
	{
		//var id= $('#invdyechemisurqaopFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reportold?id="+id);
	}
}
window.MsInvDyeChemIsuRqAop=new MsInvDyeChemIsuRqAopController(new MsInvDyeChemIsuRqAopModel());
MsInvDyeChemIsuRqAop.showGrid();
MsInvDyeChemIsuRqAop.batchSearchGrid([]);

$('#invdyechemisurqaoptabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_rq_id = $('#invdyechemisurqaopFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqaoptabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurqitemaopFrm');
			$('#invdyechemisurqitemaopFrm  [name=inv_dye_chem_isu_rq_id]').val(inv_dye_chem_isu_rq_id);
			MsInvDyeChemIsuRqItemAop.get(inv_dye_chem_isu_rq_id);
			//MsInvDyeChemIsuRqItemAop.getPrintType(inv_dye_chem_isu_rq_id);
        }
        if(index==2){
			/*if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqaoptabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }*/
		    MsInvDyeChemIsuRqAop.showGridOld();
        }
    }
});

