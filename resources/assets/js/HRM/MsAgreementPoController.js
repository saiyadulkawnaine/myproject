let MsAgreementPoModel = require('./MsAgreementPoModel');
class MsAgreementPoController {
	constructor(MsAgreementPoModel)
	{
		this.MsAgreementPoModel = MsAgreementPoModel;
		this.formId='agreementpoFrm';
		this.dataTable='#agreementpoTbl';
		this.route=msApp.baseUrl()+"/agreementpo"
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
		let formObj=this.getSelections();
        if(formObj.id){
            this.MsAgreementPoModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsAgreementPoModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
    }
    
    submitAndClose()
	{
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsAgreementPoModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAgreementPoModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			$('#importpoWindow').window('close');
			$('#posearchTbl').datagrid('loadData', []);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#agreementpoFrm [name=agreement_id]').val($('#agreementFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsAgreementPoModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAgreementPoModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsAgreementPo.get($('#agreementFrm  [name=id]').val())
		$('#agreementpoTbl').datagrid('reload');
		$('#agreementpoFrm [name=agreement_id]').val($('#agreementFrm [name=id]').val());
		msApp.resetForm('agreementpoFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAgreementPoModel.get(index,row);
	}

	// showGrid(agreement_id)
	// {
	// 	let self=this;
	// 	let data={}
	// 	data.agreement_id=agreement_id
	// 	$(this.dataTable).datagrid({
	// 		method:'get',
	// 		border:false,
	// 		singleSelect:true,
	// 		fit:true,
	// 		fitColumns:true,
	// 		queryParams:data,
	// 		url:this.route,
	// 		onClickRow: function(index,row){
	// 			self.edit(index,row);
	// 		}
	// 	}).datagrid('enableFilter');
	// }

    get(agreement_id){
		let data= axios.get(this.route+"?agreement_id="+agreement_id)
		.then(function (response) {
			$('#agreementpoTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		var df = $('#agreementpoTbl');
		df.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
	 			self.edit(index,row);
	 		}
		});
		df.datagrid('enableFilter').datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAgreementPo.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsAgreementPo.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
    
    openPurchaseOrderWindow()
	{
		$('#posearchTbl').datagrid('loadData', []);
		let supplier_id=$('#agreementFrm  [name=supplier_id]').val();
        $('#posearchFrm  [name=supplier_id]').val(supplier_id);    
		$('#importpoWindow').window('open');
    }
    searchPo(){
        let agreement_id=$('#agreementFrm  [name=id]').val();
		let menu_id=$('#agreementpoFrm  [name=menu_id]').val();
		let company_id=$('#posearchFrm  [name=company_id]').val();
		let supplier_id=$('#posearchFrm  [name=supplier_id]').val();
		let po_no=$('#posearchFrm  [name=po_no]').val();
		let data= axios.get(this.route+"/importpo"+"?agreement_id="+agreement_id+"&menu_id="+menu_id+"&company_id="+company_id+"&supplier_id="+supplier_id+"&po_no="+po_no)
		.then(function (response) {
			$('#posearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }
    
    poSearchGrid(data)
	{
		var dg = $('#posearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

    }
    getSelections()
	{
		let formObj={};
		formObj.agreement_id=$('#agreementFrm  [name=id]').val();
		formObj.menu_id=$('#agreementpoFrm  [name=menu_id]').val();
		let i=1;
		$.each($('#posearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['purchase_order_id['+i+']']=val.purchase_order_id
			i++;
		});
		return formObj;
	}
	
	pdf(menu_id,purchase_order_id){

		let id=purchase_order_id;

		if(menu_id==1){
			window.open(msApp.baseUrl()+"/pofabric/getpospdf?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==2){
			window.open(msApp.baseUrl()+"/potrim/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==3){
			window.open(msApp.baseUrl()+"/poyarn/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==4){
			window.open(msApp.baseUrl()+"/poknitservice/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==5){
			window.open(msApp.baseUrl()+"/poaopservice/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==6){
			window.open(msApp.baseUrl()+"/podyeingservice/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==7){
			window.open(msApp.baseUrl()+"/podyechem/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==8){
			window.open(msApp.baseUrl()+"/pogeneral/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==9){
			window.open(msApp.baseUrl()+"/poyarndyeing/report?id="+id+"&menu_id="+menu_id);
		}
		if(menu_id==10){
			window.open(msApp.baseUrl()+"/poembservice/report?id="+id+"&menu_id="+menu_id);
		}
	}

	formatpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAgreementPo.pdf('+'\''+row.menu_id+'\''+','+'\''+row.purchase_order_id+'\''+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

}
window.MsAgreementPo = new MsAgreementPoController(new MsAgreementPoModel());
MsAgreementPo.showGrid([]);
MsAgreementPo.poSearchGrid([]);