let MsProdGmtRcvInputQtyModel = require('./MsProdGmtRcvInputQtyModel');

class MsProdGmtRcvInputQtyController {
	constructor(MsProdGmtRcvInputQtyModel)
	{
		this.MsProdGmtRcvInputQtyModel = MsProdGmtRcvInputQtyModel;
		this.formId='prodgmtrcvinputqtyFrm';	             
		this.dataTable='#prodgmtrcvinputqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtrcvinputqty"
	}

	submit()
	{
		let prod_gmt_rcv_input_id=$('#prodgmtrcvinputFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_gmt_rcv_input_id=prod_gmt_rcv_input_id;
		if(formObj.id){
			this.MsProdGmtRcvInputQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtRcvInputQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	create(prod_gmt_rcv_input_id)
	{
		//let prod_gmt_rcv_input_id=$('#prodgmtrcvinputFrm [name=id]').val();
        let data= axios.get(this.route+"/create"+"?prod_gmt_rcv_input_id="+prod_gmt_rcv_input_id)
		.then(function (response) {
			$('#prodgmtrcvinputmatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtRcvInputQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtRcvInputQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtrcvinputqtyTbl').datagrid('reload');
		msApp.resetForm('prodgmtrcvinputqtyFrm');
		$('#prodgmtrcvinputqtyFrm [name=prod_gmt_rcv_input_id]').val($('#prodgmtrcvinputFrm [name=id]').val());
		MsProdGmtRcvInputQty.create(d.prod_gmt_rcv_input_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtRcvInputQtyModel.get(index,row);

	}

	showGrid(prod_gmt_rcv_input_id){
		let self=this;
		let data = {};
		data.prod_gmt_rcv_input_id=prod_gmt_rcv_input_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtRcvInputQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtRcvInputQty=new MsProdGmtRcvInputQtyController(new MsProdGmtRcvInputQtyModel());