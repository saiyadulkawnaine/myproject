let MsProdGmtRcvInputOrderModel = require('./MsProdGmtRcvInputOrderModel');

class MsProdGmtRcvInputOrderController {
	constructor(MsProdGmtRcvInputOrderModel)
	{
		this.MsProdGmtRcvInputOrderModel = MsProdGmtRcvInputOrderModel;
		this.formId='prodgmtrcvinputorderFrm';
		this.dataTable='#prodgmtrcvinputorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtrcvinputorder"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtRcvInputOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtRcvInputOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	create(prod_gmt_rcv_input_id)
	{
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
		/* $('#rcvinputgmtcosi').html('');
		let prod_gmt_rcv_input_id = $('#prodgmtrcvinputFrm  [name=id]').val();
		$('#prodgmtrcvinputorderFrm  [name=prod_gmt_rcv_input_id]').val(prod_gmt_rcv_input_id); */
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtRcvInputOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtRcvInputOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtrcvinputorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtrcvinputorderFrm');
		$('#prodgmtrcvinputorderFrm [name=prod_gmt_rcv_input_id]').val($('#prodgmtrcvinputFrm [name=id]').val());
		MsProdGmtRcvInputOrder.create(d.prod_gmt_rcv_input_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtRcvInputOrderModel.get(index,row);

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
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtRcvInputOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtRcvInputOrder=new MsProdGmtRcvInputOrderController(new MsProdGmtRcvInputOrderModel());
//MsProdGmtRcvInputOrder.showRcvInputOrderGrid([]);