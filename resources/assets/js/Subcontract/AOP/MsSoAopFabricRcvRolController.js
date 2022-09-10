let MsSoAopFabricRcvRolModel = require('./MsSoAopFabricRcvRolModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvRolController {
	constructor(MsSoAopFabricRcvRolModel)
	{
		this.MsSoAopFabricRcvRolModel = MsSoAopFabricRcvRolModel;
		this.formId='soaopfabricrcvrolFrm';
		this.dataTable='#soaopfabricrcvrolTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcvrol"
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
		let so_aop_fabric_rcv_item_id = $('#soaopfabricrcvitemFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.so_aop_fabric_rcv_item_id=so_aop_fabric_rcv_item_id;
		if(formObj.id){
			this.MsSoAopFabricRcvRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvRolModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvRolModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoAopFabricRcvRol.get(d.so_aop_fabric_rcv_item_id)
		msApp.resetForm('soaopfabricrcvrolFrm')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvRolModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_fabric_rcv_item_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_rcv_item_id="+so_aop_fabric_rcv_item_id);
		data.then(function (response) {
			$('#soaopfabricrcvrolTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcvRol.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSoAopFabricRcvRol=new MsSoAopFabricRcvRolController(new MsSoAopFabricRcvRolModel());
MsSoAopFabricRcvRol.showGrid([]);