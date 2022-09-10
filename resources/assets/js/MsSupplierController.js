//require('./jquery.easyui.min.js');
let MsSupplierModel = require('./MsSupplierModel');
require('./datagrid-filter.js');

class MsSupplierController {
	constructor(MsSupplierModel)
	{
		this.MsSupplierModel = MsSupplierModel;
		this.formId='supplierFrm';
		this.dataTable='#supplierTbl';
		this.route=msApp.baseUrl()+"/supplier"
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
		/*let companyId= new Array();
		$('#companyBox2 option').map(function(i, el) {
			companyId.push($(el).val());
		});
		$('#company_id').val( companyId.join());*/
		
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsSupplierModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSupplierModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSupplierModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSupplierModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#supplierTbl').datagrid('reload');
		//$('#SupplierFrm  [name=id]').val(d.id);
		msApp.resetForm('supplierFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSupplierModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSupplier.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSupplier=new MsSupplierController(new MsSupplierModel());
MsSupplier.showGrid();

$('#utilsuppliertabs').tabs({
    onSelect:function(title,index){
        let supplier_id = $('#supplierFrm [name=id]').val();
        
        var data={};
		    data.supplier_id=supplier_id;
        if(index==1){
				if(supplier_id===''){
					$('#utilsuppliertabs').tabs('select',0);
					msApp.showError('Select Supplier First',0);
					return;
			    }
				$('#companysupplierFrm  [name=supplier_id]').val(supplier_id);
				MsCompanySupplier.create()
            }
        if(index==2){
				if(supplier_id===''){
					$('#utilsuppliertabs').tabs('select',0);
					msApp.showError('Select Supplier First',0);
					return;
			    }
				$('#suppliernatureFrm  [name=supplier_id]').val(supplier_id);
				MsSupplierNature.create()
            }
    }
});
