require('./../../datagrid-filter.js');
let MsProdGmtSewingLineModel = require('./MsProdGmtSewingLineModel');
class MsProdGmtSewingLineController {
	constructor(MsProdGmtSewingLineModel)
	{
		this.MsProdGmtSewingLineModel = MsProdGmtSewingLineModel;
		this.formId='prodgmtsewinglineFrm';
		this.dataTable='#prodgmtsewinglineTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewingline"
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
		let formData=$("#"+this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtSewingLineModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtSewingLineModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtsewinglineFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtSewingLineModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtSewingLineModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtsewinglineTbl').datagrid('reload');
		$('#prodgmtsewinglineFrm  [name=id]').val(d.id);
		$('#prodgmtsewinglineFrm  [name=challan_no]').val(d.challan_no);
		msApp.resetForm('prodgmtsewinglineFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let sewingline=this.MsProdGmtSewingLineModel.get(index,row);
		sewingline.then(function(response){
			$('#prodgmtsewinglineFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(error);
		});
		
	}

	showGrid(){

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtSewingLine.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id = $('#prodgmtsewinglineFrm [name=id]').val();
		if(id==''){
			alert("Select A Challan First");
			return;
		}
		window.open(this.route+"/sewinglinepdf?id="+id);
	}
	pdfshort(){
		var id = $('#prodgmtsewinglineFrm [name=id]').val();
		if(id==''){
			alert("Select A Challan First");
			return;
		}
		window.open(this.route+"/sewinglineshortpdf?id="+id);
	}
}
window.MsProdGmtSewingLine=new MsProdGmtSewingLineController(new MsProdGmtSewingLineModel());
MsProdGmtSewingLine.showGrid();

 $('#prodgmtsewinglinetabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_sewing_line_id = $('#prodgmtsewinglineFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_sewing_line_id=prod_gmt_sewing_line_id;

	 if(index==1){
		 if(prod_gmt_sewing_line_id===''){
			 $('#prodgmtsewinglinetabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtsewinglineorderFrm');
		  $('#prodgmtsewinglineorderFrm  [name=prod_gmt_sewing_line_id]').val(prod_gmt_sewing_line_id);
		  MsProdGmtSewingLineOrder.showGrid(prod_gmt_sewing_line_id);
	  }
   }
}); 
