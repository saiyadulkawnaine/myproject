require('./../datagrid-filter.js');
let MsPoDyeChemModel = require('./MsPoDyeChemModel');
class MsPoDyeChemController {
	constructor(MsPoDyeChemModel)
	{
		this.MsPoDyeChemModel = MsPoDyeChemModel;
		this.formId='podyechemFrm';
		this.dataTable='#podyechemTbl';
		this.route=msApp.baseUrl()+"/podyechem"
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
			this.MsPoDyeChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoDyeChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#podyechemFrm [id="supplier_id"]').combobox('setValue','');
		$('#podyechemFrm [id="indentor_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoDyeChemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoDyeChemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#podyechemTbl').datagrid('reload');
		msApp.resetForm('podyechemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let ptrim=this.MsPoDyeChemModel.get(index,row);
		ptrim.then(function (response) {
			MsPoDyeChemItem.get(row.id);	
			$('#podyechemFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#podyechemFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
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
				$('#podyechemtopsheetTbl').datagrid('appendRow',row);
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
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#podyechemFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	pdf2(){
		var id= $('#podyechemFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/reportshort?id="+id);
	}

	openTopSheetWindow()
	{
		$('#podyechemtopsheetWindow').window('open');
	}

	showGridTopSheet(data)
	{
		var dg = $('#podyechemtopsheetTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChem.topsheetporemove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Remove</span></a>';
	}

	topsheetporemove(event,id)
	{
		let tr=event.target.closest('tr');
		let index=tr.rowIndex;
		$('#podyechemtopsheetTbl').datagrid('deleteRow', index);
	}

	printTopSheet()
	{

		let id=[];
		let checked=$('#podyechemtopsheetTbl').datagrid('getRows');

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
		//$('#poyarnordersearchWindow').window('close');

	}

	searchPoDyeChem()
	{
		let params = {};
		params.po_no = $('#po_no').val();
		params.supplier_search_id = $('#supplier_search_id').val();
		params.company_search_id = $('#company_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/getsearchpodyechem", { params });
		data.then(function (response)
		{
			$('#podyechemTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
}
window.MsPoDyeChem=new MsPoDyeChemController(new MsPoDyeChemModel());
MsPoDyeChem.showGrid();
MsPoDyeChem.showGridTopSheet([]);

$('#podyechemAccordion').accordion({
	onSelect:function(title,index){
		let po_dye_chem_id = $('#podyechemFrm  [name=id]').val();
		if(index==1){
			if(po_dye_chem_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#podyechemAccordion').accordion('unselect',1);
				$('#podyechemAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_dye_chem_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#podyechemAccordion').accordion('unselect',1);
				$('#podyechemAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_dye_chem_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(7)
			MsPurchaseTermsCondition.get();
		}
	}
})