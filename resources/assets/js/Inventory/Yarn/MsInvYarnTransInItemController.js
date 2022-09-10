let MsInvYarnTransInItemModel = require('./MsInvYarnTransInItemModel');
require('./../../datagrid-filter.js');
class MsInvYarnTransInItemController {
	constructor(MsInvYarnTransInItemModel)
	{
		this.MsInvYarnTransInItemModel = MsInvYarnTransInItemModel;
		this.formId='invyarntransinitemFrm';
		this.dataTable='#invyarntransinitemTbl';
		this.route=msApp.baseUrl()+"/invyarntransinitem"
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
		let inv_rcv_id=$('#invyarntransinFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarntransinFrm [name=inv_yarn_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvYarnTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let inv_yarn_rcv_id=$('#invyarntransinFrm [name=inv_yarn_rcv_id]').val();
		$('#invyarntransinitemFrm [name=inv_yarn_rcv_id]').val(inv_yarn_rcv_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnTransInItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnTransInItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvYarnTransInItem.get(d.inv_yarn_rcv_id);
		msApp.resetForm('invyarntransinitemFrm');
		$('#invyarntransinitemFrm [name=inv_yarn_rcv_id]').val(d.inv_yarn_rcv_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnTransInItemModel.get(index,row);
		data.then(function (response) {
			//$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(inv_yarn_rcv_id){
		let params={};
		params.inv_yarn_rcv_id=inv_yarn_rcv_id;
		let d = axios.get(this.route,{params})
		.then(function(response){
			$('#invyarntransinitemTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnTransInItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openInvYarnWindow()
	{
		$('#invyarntransinitemWindow').window('open');
	}

	getYarnItemParams(){
		let params={}
		params.lot=$('#invyarntransinitemsearchFrm [name=lot]').val();
		params.brand=$('#invyarntransinitemsearchFrm [name=brand]').val();
		params.from_company_id=$('#invyarntransinFrm [name=from_company_id]').val();
		params.inv_rcv_id=$('#invyarntransinFrm [name=id]').val();
		//params.supplier_id=$('#invyarntransoutitemsearchFrm [name=supplier_id]').val();
		return params;
	}

	searchYarnItem(){
		let params=this.getYarnItemParams();
		let d = axios.get(this.route+"/getyarnitem",{params})
		.then(function(response){
			$('#invyarntransinitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}


	showYarnItemGrid(data){
		let self=this;
		var ryt = $('#invyarntransinitemsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				//self.getRate(row.id);
				$('#invyarntransinitemFrm  [name=inv_yarn_isu_item_id]').val(row.inv_yarn_isu_item_id);
				$('#invyarntransinitemFrm  [name=transfer_no]').val(row.transfer_no);
				$('#invyarntransinitemFrm  [name=inv_yarn_item_id]').val(row.inv_yarn_item_id);
				$('#invyarntransinitemFrm  [name=yarn_count]').val(row.yarn_count);
				$('#invyarntransinitemFrm  [name=yarn_des]').val(row.yarn_des);
				$('#invyarntransinitemFrm  [name=yarn_type]').val(row.yarn_type);
				$('#invyarntransinitemFrm  [name=yarn_color_name]').val(row.yarn_color_name);
				$('#invyarntransinitemFrm  [name=lot]').val(row.lot);
				$('#invyarntransinitemFrm  [name=brand]').val(row.brand);
				$('#invyarntransinitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invyarntransinitemFrm  [name=qty]').val(row.qty);
				$('#invyarntransinitemFrm  [name=rate]').val(row.rate);
				$('#invyarntransinitemFrm  [name=amount]').val(row.amount);
				$('#invyarntransinitemWindow').window('close');
			},
		});
		ryt.datagrid('enableFilter').datagrid('loadData', data);
	}

	/*getRate(inv_yarn_item_id){
		let self=this;
		let params={};
		params.inv_yarn_item_id=inv_yarn_item_id
		let d = axios.get(this.route+"/getrate",{params})
		.then(function(response){
				$('#invyarntransinitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}*/


	calculate_qty_form()
	{
		
		let qty=$('#invyarntransinitemFrm input[name=qty]').val();
		let rate=$('#invyarntransinitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invyarntransinitemFrm input[name=amount]').val(amount);
	}

}
window.MsInvYarnTransInItem=new MsInvYarnTransInItemController(new MsInvYarnTransInItemModel());
MsInvYarnTransInItem.showGrid([]);
MsInvYarnTransInItem.showYarnItemGrid([]);

