let MsSoDyeingFabricRcvRolModel = require('./MsSoDyeingFabricRcvRolModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvRolController {
	constructor(MsSoDyeingFabricRcvRolModel)
	{
		this.MsSoDyeingFabricRcvRolModel = MsSoDyeingFabricRcvRolModel;
		this.formId='sodyeingfabricrcvrolFrm';
		this.dataTable='#sodyeingfabricrcvrolTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcvrol"
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
		let so_dyeing_fabric_rcv_item_id = $('#sodyeingfabricrcvitemFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_item_id;
		if(formObj.id){
			this.MsSoDyeingFabricRcvRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvRolModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvRolModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoDyeingFabricRcvRol.get(d.so_dyeing_fabric_rcv_item_id)
		msApp.resetForm('sodyeingfabricrcvrolFrm')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvRolModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_fabric_rcv_item_id)
	{
		let data= axios.get(this.route+"?so_dyeing_fabric_rcv_item_id="+so_dyeing_fabric_rcv_item_id);
		data.then(function (response) {
			$('#sodyeingfabricrcvrolTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcvRol.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSoDyeingFabricRcvRol=new MsSoDyeingFabricRcvRolController(new MsSoDyeingFabricRcvRolModel());
MsSoDyeingFabricRcvRol.showGrid([]);