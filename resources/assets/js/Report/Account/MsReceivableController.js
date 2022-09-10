let MsReceivableModel = require('./MsReceivableModel');
require('./../../datagrid-filter.js');
class MsReceivableController {
	constructor(MsReceivableModel)
	{
		this.MsReceivableModel = MsReceivableModel;
		this.formId='receivableFrm';
		this.dataTable='#receivableTbl';
		this.route=msApp.baseUrl()+"/receivable";
	}

	get(){
		let params={};
		params.date_to = $('#receivableFrm  [name=date_to]').val();
		params.coa_id = $('#receivableFrm  [name=coa_id]').val();
		params.buyer_id = $('#receivableFrm [name=buyer_id]').val();
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			$('#receivablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}
	getl(){
		let params={};
		params.date_to = $('#receivableFrm  [name=date_to]').val();
		params.coa_id = $('#receivableFrm  [name=coa_id]').val();
		params.buyer_id = $('#receivableFrm [name=buyer_id]').val();
		let d= axios.get(this.route+'/htmll',{params})
		.then(function (response) {
			$('#receivablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}
	getd(){
		let params={};
		params.date_to = $('#receivableFrm  [name=date_to]').val();
		params.coa_id = $('#receivableFrm  [name=coa_id]').val();
		params.buyer_id = $('#receivableFrm [name=buyer_id]').val();
		let d= axios.get(this.route+'/htmld',{params})
		.then(function (response) {
			$('#receivablecontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	buyersearch(){

		let data={};
		let name=$('#receivablesearchFrm  [name=name]').val();
		let code=$('#receivablesearchFrm  [name=code]').val();
		let vendor_code=$('#receivablesearchFrm  [name=vendor_code]').val();
		data.name=name;
        data.code=code;
        data.vendor_code=vendor_code;
        this.getBuyer(data);

	}

	openbuyerWindow()
	{
		$('#receivableFrm  [name=buyer_name]').val();
		
		$('#receivablesearchWindow').window('open');
		MsReceivable.buyersearch()
		
    }
    getBuyer(data){
		let trans=msApp.getJson("buyer/getBuyer",data);
		trans.then(function (response) {
			$('#receivablesearchTbl').datagrid('loadData', response.data);

            //MsTransPrnt.showGrid(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
	}

	showGridBuyer(data)
	{
		let self=this;
		$('#receivablesearchTbl').datagrid({
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
		let checked=$('#receivablesearchTbl').datagrid('getSelections');
		if(checked.length >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#receivableFrm  [name=buyer_id]').val(id);
		$('#receivableFrm  [name=buyer_name]').val(name);
		$('#receivablesearchWindow').window('close');
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/receivable/getcode");
		data.then(function (response) {
			$('#receivablecodefromTbl').datagrid('loadData', response.data);
			$('#receivablecodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#receivablecodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 //$('#receivableFrm  [name=code_from]').val(row.code);
			 //$('#glbuycodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	closecodefromWindow(type){

		let id=[];
		let name=[];
		let checked=$('#receivablecodefromTbl').datagrid('getSelections');
		if(checked.length >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#receivableFrm  [name=coa_id]').val(id);
		$('#receivableFrm  [name=coa_name]').val(name);
		$('#receivablecodefromwindow').window('close');
	}

	getRequestLetter(){

		let id=[];
		let name=[];
		let checked=$('#receivablesearchTbl').datagrid('getSelections');
		if(checked.length !==1 ){
			alert("Select A Single Buyer First");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#receivableFrm  [name=buyer_id]').val(id);
		$('#receivableFrm  [name=buyer_name]').val(name);
		$('#receivablesearchWindow').window('close');

		let date_to = $('#receivableFrm  [name=date_to]').val();
		//let coa_id = $('#receivableFrm  [name=coa_id]').val();
		let buyer_id = $('#receivableFrm  [name=buyer_id]').val();

		if(buyer_id==''){
			alert('Select A Single Buyer First');
			return;
		}

		window.open(msApp.baseUrl()+"/receivable/pdf?buyer_id="+buyer_id/* +"&coa_id="+coa_id */+"&date_to="+date_to);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsReceivable=new MsReceivableController(new MsReceivableModel());
MsReceivable.showGridBuyer([]);
MsReceivable.showcodefromGrid([]);
