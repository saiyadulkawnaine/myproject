require('./../datagrid-filter.js');
let MsGateEntryModel= require('./MsGateEntryModel');
class MsGateEntryController {
	constructor(MsGateEntryModel)
	{
		this.MsGateEntryModel= MsGateEntryModel;
		this.formId='gateentryFrm';
		this.dataTable='#gateentryTbl';
		this.route=msApp.baseUrl()+"/gateentry"
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
		//let formObj=msApp.get(this.formId);
		let formObj=this.getData();
		if(formObj.id){
			this.MsGateEntryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGateEntryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		
		
	}

	getData(){
		let formObj=msApp.get('gateentryFrm');
		let i=1;
		$.each($('#gateentryitemTbl').datagrid('getRows'),function(idx,val){
			formObj['item_id['+i+']']=val.item_id;
			formObj['item_description['+i+']']=val.item_description;
			formObj['uom_code['+i+']']=val.uom_code;
			formObj['qty['+i+']']=val.qty;
			formObj['remarks['+i+']']=val.remarks;
			i++;
		});
		return formObj;
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#gateentryitemTbl').datagrid('loadData',[]);
		$( "#barcode_no_id" ).focus();

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGateEntryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGateEntryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsGateEntry.get();
		$('#gateentryitemTbl').datagrid('loadData',[]);
		msApp.resetForm('gateentryFrm');
		//MsGateEntry.resetForm();
		//MsGateEntry.getInvoice(d.id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data =this.MsGateEntryModel.get(index,row);
		data.then(function (response) {
			$('#gateentryitemTbl').datagrid('loadData', response.data.chlddata);
			//let data=MsGateEntry.getsum();
			//MsGateEntry.reloadFooter(data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}
	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#gateentryTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
 
	

	formatDetail(value,row){
		return '<a href="javascript:void(0)" onClick="MsGateEntry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showEntryItemGrid(data){
		let self=this;
		$('#gateentryitemTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			nowrap:false,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#gateentryitemTbl').datagrid('selectRow',index);
            $(this).datagrid('beginEdit', index);
			},
			onBeginEdit:function(rowIndex){
				var editors = $('#gateentryitemTbl').datagrid('getEditors', rowIndex);
				var n1 = $(editors[0].target);

				n1.numberbox({
				onChange:function(){
					let qty = n1.numberbox('getValue');
					$('#gateentryitemTbl').datagrid('endEdit', rowIndex);
				}
				})
			},
            onEndEdit:function(index,row){
				$('#gateentryitemTbl').datagrid('updateRow',{
					index: index,
					row: row
				});
               
            },
            onAfterEdit:function(index,row){
               row.editing = false;
               $(this).datagrid('refreshRow', index);
				let data=MsGateEntry.getsum();
				MsGateEntry.reloadFooter(data);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getPurchaseNo(){
		let menu_id=$('#menu_id').val();
		let barcode_no_id=$('#barcode_no_id').val();

		let params={};
		params.barcode_no_id = barcode_no_id;
		params.menu_id = menu_id;
		let d= axios.get(this.route+'/getpurchaseitem',{params})
		.then(function (response) {
			$('#gateentryitemTbl').datagrid('loadData',response.data);
			let data=MsGateEntry.getsum();
			MsGateEntry.reloadFooter(data);
			$('#gateentryFrm [name=po_no]').val(data.po_no);
			//$('#gateentryFrm [name=barcode_no_id]').val(data.barcode_no_id);
			$('#gateentryFrm [name=supplier_name]').val(data.supplier_name);
			$('#gateentryFrm [name=supplier_contact]').val(data.supplier_contact);
			$('#gateentryFrm [name=requisition_no]').val(data.requisition_no);
			$('#gateentryFrm [name=company_name]').val(data.company_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getsum(){
		let data={};
		let total_qty=0;
		$.each($('#gateentryitemTbl').datagrid('getRows'), function (idx, val) {
			if(val.qty){
				total_qty+=val.qty*1;
			}
			po_no=val.po_no;
			barcode_no_id=val.barcode_no_id;
			supplier_name=val.supplier_name;
			supplier_contact=val.supplier_contact;
			requisition_no=val.requisition_no;
			company_name=val.company_name;
		});

		data.total_qty=total_qty;
		data.po_no=po_no;
		data.barcode_no_id=barcode_no_id;
		data.supplier_name=supplier_name;
		data.supplier_contact=supplier_contact;
		data.requisition_no=requisition_no;
		data.company_name=company_name;
		return data;
	}

	reloadFooter(data)
	{
		$('#gateentryitemTbl').datagrid('reloadFooter', [
		{ 
			item_desc:'Total',
			qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		}
	]);
	}

	purchaseOnchage(menu_id){
		if(menu_id){
			$( "#barcode_no_id" ).focus();
			$('#gateentryFrm [name=barcode_no_id]').val([]);

			let d= axios.get(this.route+"?menu_id="+menu_id)
			.then(function (response) {
				$('#gateentryTbl').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		}
	}
	
}
window.MsGateEntry=new MsGateEntryController(new MsGateEntryModel());
MsGateEntry.showGrid([]);
MsGateEntry.showEntryItemGrid([]);
MsGateEntry.get();