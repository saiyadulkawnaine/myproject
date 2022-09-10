require('./../datagrid-filter.js');
let MsPoGeneralServiceItemModel = require('./MsPoGeneralServiceItemModel');
class MsPoGeneralServiceItemController {
	constructor(MsPoGeneralServiceItemModel)
	{
		this.MsPoGeneralServiceItemModel = MsPoGeneralServiceItemModel;
		this.formId='pogeneralserviceitemFrm';
		this.dataTable='#pogeneralserviceitemTbl';
		this.route=msApp.baseUrl()+"/pogeneralserviceitem"
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
		let po_general_service_id=$('#pogeneralserviceFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_general_service_id=po_general_service_id;
		if(formObj.id){
			this.MsPoGeneralServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoGeneralServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}


	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pogeneralserviceitemFrm [id="department_id"]').combobox('setValue', '');
		$('#pogeneralserviceitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#pogeneralserviceitemFrm [id="demand_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoGeneralServiceItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoGeneralServiceItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_general_service_id=$('#pogeneralserviceFrm  [name=id]').val()
		MsPoGeneralServiceItem.get(po_general_service_id);
		$('#pogeneralserviceitemFrm [id="department_id"]').combobox('setValue', '');
		$('#pogeneralserviceitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#pogeneralserviceitemFrm [id="demand_by_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let gsitem=this.MsPoGeneralServiceItemModel.get(index,row);
		gsitem.then(function(response){
			$('#pogeneralserviceitemFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
			$('#pogeneralserviceitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#pogeneralserviceitemFrm [id="demand_by_id"]').combobox('setValue', response.data.fromData.demand_by_id);
		}).catch(function(error){
			console.log(error);
		});

	}

	get(po_general_service_id){
		let data= axios.get(this.route+"?po_general_service_id="+po_general_service_id)
		.then(function (response) {
			$('#pogeneralserviceitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		var dg = $('#pogeneralserviceitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		});
		dg.datagrid('loadData', data);
		
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralServiceItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	

	calculate()
	{
		let rate=$('#pogeneralserviceitemFrm  [name=rate]').val();
		let qty=$('#pogeneralserviceitemFrm  [name=qty]').val();
		let balance_qty=$('#pogeneralserviceitemFrm  [name=balance_qty]').val();
		if(qty*1>balance_qty*1){
			alert('More than balance not allowed');
			$('#pogeneralserviceitemFrm  [name=qty]').val('');
			return;
		}
		let amount=msApp.multiply(qty,rate);
		$('#pogeneralserviceitemFrm  [name=amount]').val(amount);
	}

	openAssetWindow(){
		$('#assetWindow').window('open');
	}

	showAssetGrid(data){
		let self = this;
		$('#assetsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#pogeneralserviceitemFrm [name=asset_quantity_cost_id]').val(row.id);
					$('#pogeneralserviceitemFrm [name=asset_desc]').val(row.asset_desc);
					$('#assetWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchAsset()
	{
		let params={};
		params.brand=$('#assetsearchFrm  [name=brand]').val();
		params.machine_no=$('#assetsearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getasset",{params});
		data.then(function (response) {
			$('#assetsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsPoGeneralServiceItem=new MsPoGeneralServiceItemController(new MsPoGeneralServiceItemModel());
MsPoGeneralServiceItem.showAssetGrid([]);
MsPoGeneralServiceItem.showGrid([]);
