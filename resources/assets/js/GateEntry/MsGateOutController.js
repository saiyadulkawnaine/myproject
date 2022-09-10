require('./../datagrid-filter.js');
let MsGateOutModel= require('./MsGateOutModel');
class MsGateOutController {
	constructor(MsGateOutModel)
	{
		this.MsGateOutModel= MsGateOutModel;
		this.formId='gateoutFrm';
		this.dataTable='#gateoutTbl';
		this.route=msApp.baseUrl()+"/gateout"
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

		let formObj=this.getData();
		if(formObj.id){
			this.MsGateOutModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGateOutModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		
	}

	getData(){
		let formObj=msApp.get('gateoutFrm');
		let i=1;
		$.each($('#gateoutitemTbl').datagrid('getRows'),function(idx,val){
			formObj['item_id['+i+']']=val.item_id;
			formObj['item_description['+i+']']=val.item_description;
			formObj['uom_code['+i+']']=val.uom_code;
			formObj['qty['+i+']']=val.qty;
			formObj['returned_qty['+i+']']=val.returned_qty;
			i++;
		});
		return formObj;
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#gateoutitemTbl').datagrid('loadData',[]);
		$( "#barcode_no_id" ).focus();
	}

	remove()
	{
		
		let formObj=msApp.get(this.formId);
		this.MsGateOutModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGateOutModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsGateOut.get();
		$('#gateoutitemTbl').datagrid('loadData',[]);
		msApp.resetForm('gateoutFrm');
		//MsGateOut.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data =this.MsGateOutModel.get(index,row);
		data.then(function (response) {
			$('#gateoutitemTbl').datagrid('loadData', response.data.chlddata);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#gateoutTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)" onClick="MsGateOut.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
	getPurchaseNo(){
		let menu_id=$('#menu_id').val();
		let barcode_no_id=$('#barcode_no_id').val();

		let params={};
		params.barcode_no_id = barcode_no_id;
		params.menu_id = menu_id;

		let d= axios.get(this.route+'/getmenuitem',{params})
		.then(function (response) {
			$('#gateoutitemTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	purchaseOnchage(menu_id){
		if(menu_id){
			$( "#barcode_no_id" ).focus();
			$('#gateoutFrm [name=barcode_no_id]').val([]);

			let d= axios.get(this.route+"?menu_id="+menu_id)
			.then(function (response) {
				$('#gateoutTbl').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		}
	}

	showOutItemGrid(data){
		let self=this;
		$('#gateoutitemTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
				$('#gateoutitemTbl').datagrid('selectRow',index);
			},
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	searchList(){
		let params={};
		params.menu_id=$('#gatesearchFrm [name=barcode_menu_id]').val();
		params.from_date=$('#gatesearchFrm [name=from_date]').val();
		params.to_date=$('#gatesearchFrm [name=to_date]').val();
		if(!params.menu_id){
			alert('Select A Menu First ');
			return;
		}
		let e= axios.get(this.route+"/getoutentry",{params});
		e.then(function (response) {
			$('#gateoutTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
}
window.MsGateOut=new MsGateOutController(new MsGateOutModel());
MsGateOut.showGrid([]);
MsGateOut.showOutItemGrid([]);
MsGateOut.get();