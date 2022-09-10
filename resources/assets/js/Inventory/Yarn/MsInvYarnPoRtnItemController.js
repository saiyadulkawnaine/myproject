let MsInvYarnPoRtnItemModel = require('./MsInvYarnPoRtnItemModel');

class MsInvYarnPoRtnItemController {
	constructor(MsInvYarnPoRtnItemModel)
	{
		this.MsInvYarnPoRtnItemModel = MsInvYarnPoRtnItemModel;
		this.formId='invyarnportnitemFrm';
		this.dataTable='#invyarnportnitemTbl';
		this.route=msApp.baseUrl()+"/invyarnportnitem"
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
			this.MsInvYarnPoRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnPoRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let inv_isu_id = $('#invyarnportnFrm [name=id]').val();
		msApp.resetForm(this.formId);
		$('#invyarnportnitemFrm  [name=inv_isu_id]').val(inv_isu_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnPoRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnPoRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvYarnPoRtnItem.get(d.inv_isu_id);
		msApp.resetForm('invyarnportnitemFrm');
		$('#invyarnportnitemFrm [name=inv_isu_id]').val(d.inv_isu_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvYarnPoRtnItemModel.get(index,row);
		$('#invyarnportnmrritemTbl').datagrid('loadData',[]);
	}
	get(inv_isu_id)
	{
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invyarnportnitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvYarnPoRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    showGridMrr(data){
		let self=this;
		$('#invyarnportnmrritemTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				MsInvYarnPoRtnItem.resetForm();
				$('#invyarnportnitemFrm  [name=po_no]').val(row.po_no)
				$('#invyarnportnitemFrm  [name=pi_no]').val(row.pi_no)
				$('#invyarnportnitemFrm  [name=yarn_supplier]').val(row.supplier_name)
				$('#invyarnportnitemFrm  [name=challan_no]').val(row.challan_no)
				$('#invyarnportnitemFrm  [name=yarn_desc]').val(row.yarn_desc)
				$('#invyarnportnitemFrm  [name=store_id]').val(row.store_id)
				$('#invyarnportnitemFrm  [name=rate]').val(row.rate)
				$('#invyarnportnitemFrm  [name=lot]').val(row.lot)
				$('#invyarnportnitemFrm  [name=receive_qty]').val(row.qty)
				$('#invyarnportnitemFrm  [name=store_rate]').val(row.store_rate)
				$('#invyarnportnitemFrm  [name=inv_yarn_rcv_item_id]').val(row.id)
				$('#invyarnportnitemFrm  [name=inv_yarn_rcv_id]').val(row.inv_yarn_rcv_id)
				$('#invyarnportnitemFrm  [name=inv_yarn_item_id]').val(row.inv_yarn_item_id)
				$('#invyarnportnitemFrm  [name=inv_rcv_id]').val(row.inv_rcv_id)
				
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getMrrItem()
	{
		$('#invyarnportnmrritemTbl').datagrid('loadData',[]);
		let inv_rcv_id=$('#invyarnportnitemFrm  [name=inv_rcv_id]').val();
		let inv_isu_id=$('#invyarnportnitemFrm  [name=inv_isu_id]').val();
		let params={};
		params.inv_rcv_id=inv_rcv_id;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getmrritem',{params})
		.then(function(response){
			$('#invyarnportnmrritemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty()
	{
		let qty=$('#invyarnportnitemFrm input[name=qty]').val();
		let rate=$('#invyarnportnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invyarnportnitemFrm input[name=amount]').val(amount);
	}
}
window.MsInvYarnPoRtnItem=new MsInvYarnPoRtnItemController(new MsInvYarnPoRtnItemModel());
MsInvYarnPoRtnItem.showGrid([]);
MsInvYarnPoRtnItem.showGridMrr([]);