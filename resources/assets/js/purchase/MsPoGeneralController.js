require('./../datagrid-filter.js');
let MsPoGeneralModel = require('./MsPoGeneralModel');
class MsPoGeneralController {
	constructor(MsPoGeneralModel)
	{
		this.MsPoGeneralModel = MsPoGeneralModel;
		this.formId='pogeneralFrm';
		this.dataTable='#pogeneralTbl';
		this.route=msApp.baseUrl()+"/pogeneral"
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
			this.MsPoGeneralModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoGeneralModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pogeneralFrm [id="supplier_id"]').combobox('setValue','');
		$('#pogeneralFrm [id="indentor_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoGeneralModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoGeneralModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#pogeneralTbl').datagrid('reload');
		msApp.resetForm('pogeneralFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let ptrim=this.MsPoGeneralModel.get(index,row);
		ptrim.then(function (response) {
			MsPoGeneralItem.get(row.id);	
			$('#pogeneralFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#pogeneralFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
				$('#pogeneraltopsheetTbl').datagrid('appendRow',row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneral.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#pogeneralFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	pdf2(){
		var id= $('#pogeneralFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/reportshort?id="+id);
	}

	openTopSheetWindow()
	{
		$('#pogeneraltopsheetWindow').window('open');
	}

	showGridTopSheet(data)
	{
		var dg = $('#pogeneraltopsheetTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				/*var no_of_supplier=0;
				var no_of_po=0;
				var qty=0;
				var amount_taka=0;
				var po_usd=0;
				var po_taka=0;
				for(var i=0; i<data.rows.length; i++){
					no_of_po+=data.rows[i]['no_of_po'].replace(/,/g,'')*1;
					no_of_supplier+=data.rows[i]['no_of_supplier'].replace(/,/g,'')*1;
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_taka+=data.rows[i]['amount_taka'].replace(/,/g,'')*1;
					po_usd+=data.rows[i]['po_usd'].replace(/,/g,'')*1;
					po_taka+=data.rows[i]['po_taka'].replace(/,/g,'')*1;
				}			
				$('#purchaseorderreportcategorywiseTbl').datagrid('reloadFooter', [
					{ 
						no_of_po: no_of_po.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						no_of_supplier: no_of_supplier.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_taka: amount_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_usd: po_usd.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_taka: po_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);*/
			}
		});
		dg.datagrid('loadData', data);
	}

	topsheetporemovebtn(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneral.topsheetporemove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Remove</span></a>';
	}

	topsheetporemove(event,id)
	{
		let tr=event.target.closest('tr');
		let index=tr.rowIndex;
		$('#pogeneraltopsheetTbl').datagrid('deleteRow', index);
	}

	printTopSheet()
	{

		let id=[];
		let checked=$('#pogeneraltopsheetTbl').datagrid('getRows');

		if(checked.lenght > 100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			id.push(val.id);
		});
		id=id.join(',');
		if(id==""){
			alert("Select a Order");
			return;
		}
		//$('#podyechemtopsheetTbl').datagrid('clearSelections');
		//MsPoDyeChem.showGridTopSheet([]);
		window.open(this.route+"/reporttopsheet?id="+id);
	}

	searchPoGeneral()
	{
		let params = {};
		params.po_no = $("#po_no").val();
		params.supplier_search_id = $("#supplier_search_id").val();
		params.company_search_id = $("#company_search_id").val();
		params.from_date = $("#from_date").val();
		params.to_date = $("#to_date").val();
		let data = axios.get(this.route + "/getsearchgeneral", { params });
		data.then(function (response)
		{
			$('#pogeneralTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}

}
window.MsPoGeneral=new MsPoGeneralController(new MsPoGeneralModel());
MsPoGeneral.showGrid();
MsPoGeneral.showGridTopSheet([]);

$('#pogeneralAccordion').accordion({
	onSelect:function(title,index){
		let po_general_id = $('#pogeneralFrm  [name=id]').val();
		let menu_id = 8 ;
		if(index==1){
			if(po_general_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pogeneralAccordion').accordion('unselect',1);
				$('#pogeneralAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_general_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pogeneralAccordion').accordion('unselect',1);
				$('#pogeneralAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_general_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(menu_id)
			MsPurchaseTermsCondition.get();
		}
	}
})