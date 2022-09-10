let MsImpDocMaturityDtlModel = require('./MsImpDocMaturityDtlModel');
class MsImpDocMaturityDtlController {
	constructor(MsImpDocMaturityDtlModel)
	{
		this.MsImpDocMaturityDtlModel = MsImpDocMaturityDtlModel;
		this.formId='impdocmaturitydtlFrm';
		this.dataTable='#impdocmaturitydtlTbl';
		this.route=msApp.baseUrl()+"/impdocmaturitydtl"
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
			this.MsImpDocMaturityDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpDocMaturityDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpDocMaturityDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpDocMaturityDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
	  $('#impdocmaturitydtlTbl').datagrid('reload');
		msApp.resetForm('impdocmaturitydtlFrm');
      $('#impdocmaturitydtlFrm  [name=imp_doc_maturity_id]').val($('#impdocmaturityFrm  [name=id]').val());
     // MsImpDocMaturityDtl.create(d.imp_doc_accept_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsImpDocMaturityDtlModel.get(index,row);
	}

	showGrid(id)
	{
		let self=this;
        var data={};
		data.imp_doc_maturity_id=id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
            queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpDocMaturityDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
    
	
	openMatureImpDocAcceptWindow(){
		$('#impdocacceptWindow').window('open');
	}

	getParams(){
		let params = {};
		params.invoice_no=$('#impdocacceptsearchFrm  [name=invoice_no]').val();
      	params.invoice_date=$('#impdocacceptsearchFrm  [name=invoice_date]').val();
      	params.bank_ref=$('#impdocacceptsearchFrm  [name=bank_ref]').val();
      	params.lc_no=$('#impdocacceptsearchFrm  [name=lc_no]').val();
      	return params;
	}

	searchDocAcceptImpLc(){
		let params=MsImpDocMaturityDtl.getParams();
		let d=axios.get(this.route+"/getimpmaturedoc",{params})
		.then(function(response){
			$('#impdocacceptsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		return d;
	}

	showDocAcceptImpLc(data){ 
      let self=this;
		$('#impdocacceptsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#impdocmaturitydtlFrm [name=imp_doc_accept_id]').val(row.id);
				$('#impdocmaturitydtlFrm [name=invoice_no]').val(row.invoice_no);
				$('#impdocacceptsearchTbl').datagrid('loadData',[]);
				$('#impdocacceptWindow').window('close')
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsImpDocMaturityDtl = new MsImpDocMaturityDtlController(new MsImpDocMaturityDtlModel());
MsImpDocMaturityDtl.showGrid();
MsImpDocMaturityDtl.showDocAcceptImpLc([]);