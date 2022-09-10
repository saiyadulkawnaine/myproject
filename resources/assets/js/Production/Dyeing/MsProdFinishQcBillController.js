require('./../../datagrid-filter.js');
let MsProdFinishQcBillModel = require('./MsProdFinishQcBillModel');
class MsProdFinishQcBillController {
	constructor(MsProdFinishQcBillModel)
	{
		this.MsProdFinishQcBillModel = MsProdFinishQcBillModel;
		this.formId='prodfinishqcbillFrm';
		this.dataTable='#prodfinishqcbillTbl';
		this.route=msApp.baseUrl()+"/prodfinishqcbill"
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
			this.MsProdFinishQcBillModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishQcBillModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodfinishqcbillFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishQcBillModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishQcBillModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishqcbillTbl').datagrid('reload');
		MsProdFinishQcBill.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let qcbill=this.MsProdFinishQcBillModel.get(index,row);
		qcbill.then(function (response) {	
			$('#prodfinishqcbillFrm [id="buyer_id"]').combobox('setValue',response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(error);
		})

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
		return '<a href="javascript:void(0)"  onClick="MsProdFinishQcBill.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
	pdf()
	{
		var id= $('#prodfinishqcbillFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsProdFinishQcBill=new MsProdFinishQcBillController(new MsProdFinishQcBillModel());
MsProdFinishQcBill.showGrid();
 $('#prodfinishqcbilltabs').tabs({
	onSelect:function(title,index){
		let prod_finish_dlv_id= $('#prodfinishqcbillFrm  [name=id]').val();
		let data = {};
		data.prod_finish_dlv_id=prod_finish_dlv_id;
		if(index==1){
			if(prod_finish_dlv_id===''){
				$('#prodfinishqcbilltabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			msApp.resetForm('prodfinishqcbillitemFrm');
		  $('#prodfinishqcbillitemFrm  [name=prod_finish_dlv_id]').val(prod_finish_dlv_id);
		MsProdFinishQcBillItem.showGrid(prod_finish_dlv_id);
		}
	}
});
