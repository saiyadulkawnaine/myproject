let MsPayableModel = require('./MsPayableModel');
require('./../../datagrid-filter.js');
class MsPayableController {
	constructor(MsPayableModel)
	{
		this.MsPayableModel = MsPayableModel;
		this.formId='payableFrm';
		this.dataTable='#payableTbl';
		this.route=msApp.baseUrl()+"/payable";
	}

	get(){
		let params={};
		params.date_to = $('#payableFrm  [name=date_to]').val();
		params.coa_id = $('#payableFrm  [name=coa_id]').val();
		params.supplier_id = $('#payableFrm [name=supplier_id]').val();
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			$('#payablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	getl(){
		let params={};
		params.date_to = $('#payableFrm  [name=date_to]').val();
		params.coa_id = $('#payableFrm  [name=coa_id]').val();
		params.supplier_id = $('#payableFrm [name=supplier_id]').val();
		let d= axios.get(this.route+'/htmll',{params})
		.then(function (response) {
			$('#payablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}
	getd(){
		let params={};
		params.date_to = $('#payableFrm  [name=date_to]').val();
		params.coa_id = $('#payableFrm  [name=coa_id]').val();
		params.supplier_id = $('#payableFrm [name=supplier_id]').val();
		let d= axios.get(this.route+'/htmld',{params})
		.then(function (response) {
			$('#payablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	buyersearch(){

		let data={};
		let name=$('#payablesearchFrm  [name=name]').val();
		let code=$('#payablesearchFrm  [name=code]').val();
		let vendor_code=$('#payablesearchFrm  [name=vendor_code]').val();
		data.name=name;
        data.code=code;
        data.vendor_code=vendor_code;
        this.getBuyer(data);

	}

	openbuyerWindow()
	{
		$('#payableFrm  [name=buyer_name]').val();
		
		$('#payablesearchWindow').window('open');
		MsPayable.buyersearch()
		
    }
    getBuyer(data){
		let trans=msApp.getJson("supplier/getSupplier",data);
		trans.then(function (response) {
			$('#payablesearchTbl').datagrid('loadData', response.data);

            //MsTransPrnt.showGrid(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
	}

	showGridBuyer(data)
	{
		let self=this;
		$('#payablesearchTbl').datagrid({
			//method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			//url:this.route,
			//checkbox:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	closeBuyerWindow(type){

		let id=[];
		let name=[];
		let checked=$('#payablesearchTbl').datagrid('getSelections');
		if(checked.length >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#payableFrm  [name=supplier_id]').val(id);
		$('#payableFrm  [name=supplier_name]').val(name);
		$('#payablesearchWindow').window('close');
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/payable/getcode");
		data.then(function (response) {
			$('#payablecodefromTbl').datagrid('loadData', response.data);
			$('#payablecodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#payablecodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 //$('#payableFrm  [name=code_from]').val(row.code);
			 //$('#glbuycodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	closecodefromWindow(type){

		let id=[];
		let name=[];
		let checked=$('#payablecodefromTbl').datagrid('getSelections');
		if(checked.length >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#payableFrm  [name=coa_id]').val(id);
		$('#payableFrm  [name=coa_name]').val(name);
		$('#payablecodefromwindow').window('close');
	}

	getDPdf(){
		let date_to = $('#payableFrm  [name=date_to]').val();
		let coa_id = $('#payableFrm  [name=coa_id]').val();
		let supplier_id = $('#payableFrm [name=supplier_id]').val();
		window.open(msApp.baseUrl()+"/payable/getdpdf?coa_id="+coa_id+"&supplier_id="+supplier_id+"&date_to="+date_to);
	}
}
window.MsPayable=new MsPayableController(new MsPayableModel());
MsPayable.showGridBuyer([]);
MsPayable.showcodefromGrid([]);
