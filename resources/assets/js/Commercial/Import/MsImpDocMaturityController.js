let MsImpDocMaturityModel = require('./MsImpDocMaturityModel');
require('./../../datagrid-filter.js');
class MsImpDocMaturityController {
	constructor(MsImpDocMaturityModel)
	{
		this.MsImpDocMaturityModel = MsImpDocMaturityModel;
		this.formId='impdocmaturityFrm';
		this.dataTable='#impdocmaturityTbl';
		this.route=msApp.baseUrl()+"/impdocmaturity"
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
			this.MsImpDocMaturityModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpDocMaturityModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpDocMaturityModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpDocMaturityModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
	  $('#impdocmaturityTbl').datagrid('reload');
		msApp.resetForm('impdocmaturityFrm');
		$('#impdocmaturityFrm  [name=id]').val(d.id);
		$('#impdocmaturitydtlFrm  [name=imp_doc_maturity_id]').val(d.id);
	}

	edit(index,row)
	{/*  */
		row.route=this.route;
		row.formId=this.formId;
		this.MsImpDocMaturityModel.get(index,row);
		$('#impdocmaturitydtlFrm  [name=imp_doc_maturity_id]').val(row.id);
		MsImpDocMaturityDtl.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsImpDocMaturity.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	mletter()
   {
		var id= $('#impdocmaturityFrm  [name=id]').val();
		if(id==""){
		alert("Select a Document");
		return;
		}
		window.open(this.route+"/mlatter?id="+id);
    }
	
}
window.MsImpDocMaturity = new MsImpDocMaturityController(new MsImpDocMaturityModel());
MsImpDocMaturity.showGrid();

// $('#impdocmaturitytabs').tabs({
// 	onSelect:function(title,index){
// 	 let imp_doc_maturity_id = $('#impdocmaturityFrm  [name=id]').val();

// 	 var data={};
// 	  data.imp_doc_maturity_id=imp_doc_maturity_id;

// 		if(index==1){
// 			if(imp_doc_maturity_id===''){
// 				$('#impdocmaturitytabs').tabs('select',0);
// 				msApp.showError('Select A Date First',0);
// 				return;
// 			}
// 			$('#impdocmaturitydtlFrm  [name=imp_doc_maturity_id]').val(imp_doc_maturity_id);
// 			MsImpDocMaturityDtl.showGrid(imp_doc_maturity_id);
// 		}
		
// 	}

// });