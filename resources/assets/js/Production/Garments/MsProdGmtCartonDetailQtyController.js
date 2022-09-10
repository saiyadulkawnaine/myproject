let MsProdGmtCartonDetailQtyModel = require('./MsProdGmtCartonDetailQtyModel');

class MsProdGmtCartonDetailQtyController {
	constructor(MsProdGmtCartonDetailQtyModel)
	{
		this.MsProdGmtCartonDetailQtyModel = MsProdGmtCartonDetailQtyModel;
		this.formId='prodgmtcartondetailqtyFrm';
		this.dataTable='#prodgmtcartondetailqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtcartondetailqty"
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
			this.MsProdGmtCartonDetailQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCartonDetailQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCartonDetailQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCartonDetailQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtcartondetailqtyTbl').datagrid('reload');
		msApp.resetForm('prodgmtcartondetailqtyFrm');
		$('#prodgmtcartondetailqtyFrm [name=prod_gmt_carton_detail_id]').val($('#prodgmtcartondetailFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtCartonDetailQtyModel.get(index,row);

	}

	showGrid(prod_gmt_carton_detail_id){
		let self=this;
		let data = {};
		data.prod_gmt_carton_detail_id=prod_gmt_carton_detail_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCartonDetailQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

  
}
window.MsProdGmtCartonDetailQty=new MsProdGmtCartonDetailQtyController(new MsProdGmtCartonDetailQtyModel());
