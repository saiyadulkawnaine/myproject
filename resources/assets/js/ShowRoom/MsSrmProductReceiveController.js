require('./../datagrid-filter.js');
let MsSrmProductReceiveModel = require('./MsSrmProductReceiveModel');
class MsSrmProductReceiveController {
	constructor(MsSrmProductReceiveModel)
	{
		this.MsSrmProductReceiveModel = MsSrmProductReceiveModel;
		this.formId='srmproductreceiveFrm';
		this.dataTable='#srmproductreceiveTbl';
		this.route=msApp.baseUrl()+"/srmproductreceive"
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
			this.MsSrmProductReceiveModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSrmProductReceiveModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = yyyy + '-' + mm + '-' + dd;
	// 	//alert(d.getDate())
	   $('#srmproductreceiveFrm [name=receive_date]').val(today);
		
		$('#receivedtalicosi').html('');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSrmProductReceiveModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSrmProductReceiveModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#srmproductreceiveTbl').datagrid('reload');
		msApp.resetForm(this.formId);
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = yyyy + '-' + mm + '-' + dd;
		$('#srmproductreceiveFrm [name=receive_date]').val(today);
		msApp.resetForm('srmproductreceiveFrm');
		MsSrmProductReceiveDtl.resetForm();
		//MsSrmProductReceiveDtl.create(srm_product_receive_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSrmProductReceiveModel.get(index,row);
	}

	showGrid(){

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSrmProductReceive.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openInvoiceOrderWindow(){
		$('#openexpinvoicewindow').window('open');
	}
	
    getParams(){
        let params={};
        params.invoice_no = $('#expinvoicesearchFrm  [name=invoice_no]').val();
        params.date_from = $('#expinvoicesearchFrm  [name=date_from]').val();
        params.date_to = $('#expinvoicesearchFrm  [name=date_to]').val();
        params.lc_sc_no = $('#expinvoicesearchFrm  [name=lc_sc_no]').val();
        return params;
	}
	
	searchInvoiceOrderGrid(){
        let params=this.getParams();
        let d=axios.get(this.route+"/getexpinvoice",{params})
        .then(function(response){
            $('#expinvoicesearchTbl').datagrid('loadData',response.data);
        }).catch(function(error){
            console.log(error);
        })
		return d;	
			
	}
	
    showExpInvoiceGrid(){
        let self=this;
        var ex=$('#expinvoicesearchTbl').datagrid({
            method:'get',
            border:false,
            singleSelect:true,
            fit:true,
            onClickRow: function(index,row){
            $('#srmproductreceiveFrm [name=exp_invoice_id]').val(row.id);
            $('#srmproductreceiveFrm [name=invoice_no]').val(row.invoice_no);
            $('#openexpinvoicewindow').window('close');
            $('#expinvoicesearchTbl').datagrid('loadData', []);
        }
    });
    ex.datagrid('enableFilter')/* .datagrid('loadData', data) */;
    }
	
}
window.MsSrmProductReceive=new MsSrmProductReceiveController(new MsSrmProductReceiveModel());
MsSrmProductReceive.showGrid();
MsSrmProductReceive.showExpInvoiceGrid([]);