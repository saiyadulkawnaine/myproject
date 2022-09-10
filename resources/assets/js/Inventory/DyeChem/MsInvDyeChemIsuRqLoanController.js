let MsInvDyeChemIsuRqLoanModel = require('./MsInvDyeChemIsuRqLoanModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRqLoanController {
	constructor(MsInvDyeChemIsuRqLoanModel)
	{
		this.MsInvDyeChemIsuRqLoanModel = MsInvDyeChemIsuRqLoanModel;
		this.formId='invdyechemisurqloanFrm';
		this.dataTable='#invdyechemisurqloanTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqloan"
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
			this.MsInvDyeChemIsuRqLoanModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqLoanModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invdyechemisurqloanFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqLoanModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqLoanModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurqloanTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurqloanFrm');
		$('#invdyechemisurqloanFrm [id="supplier_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqLoanModel.get(index,row);
		data.then(function(response){
			$('#invdyechemisurqloanFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invdyechemisurqitemloanFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisurqitemloanFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisurqitemloanFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqLoan.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurqloanFrm  [name=id]').val();
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
			$('#invdyechemisurqloanTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsInvDyeChemIsuRqLoan=new MsInvDyeChemIsuRqLoanController(new MsInvDyeChemIsuRqLoanModel());
MsInvDyeChemIsuRqLoan.showGrid();
$('#invdyechemisurqloantabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_rq_id = $('#invdyechemisurqloanFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqloantabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurqitemloanFrm');
			$('#invdyechemisurqitemloanFrm  [name=inv_dye_chem_isu_rq_id]').val(inv_dye_chem_isu_rq_id);
			MsInvDyeChemIsuRqItemLoan.get(inv_dye_chem_isu_rq_id);
        }
    }
});

