
let MsImpLcPoModel = require('./MsImpLcPoModel');
class MsImpLcPoController {
	constructor(MsImpLcPoModel)
	{
		this.MsImpLcPoModel = MsImpLcPoModel;
		this.formId='implcpoFrm';
		this.dataTable='#implcpoTbl';
		this.route=msApp.baseUrl()+"/implcpo"
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
			this.MsImpLcPoModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpLcPoModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpLcPoModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpLcPoModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#implcpoTbl').datagrid('reload');
		msApp.resetForm('implcpoFrm');
		$('#implcpoFrm  [name=imp_lc_id]').val($('#implcFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsImpLcPoModel.get(index,row);	

	}

	showGrid(imp_lc_id)
	{
		let self=this;
        var data={};
		data.imp_lc_id=imp_lc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
            queryParams:data,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tAmount=0;
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						po_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpLcPo.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	importPo()
	{
			let po_no=$('#implcpoFrm  [name=po_no]').val();
			//let itemcategory_id=$('#implcpoFrm  [name=itemcategory_id]').val();
			let implcid=$('#implcFrm  [name=id]').val();

			let data= axios.get(this.route+"/importpo"+"?po_no="+po_no+"&implcid="+implcid)
			.then(function (response) {
			$('#implcposearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
			});
	}
	posearchGrid(data)
	{
		
		$('#implcposearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		let formObj={};
		formObj.imp_lc_id=$('#implcFrm  [name=id]').val();
		let i=1;
		$.each($('#implcposearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['purchase_order_id['+i+']']=val.id
			formObj['amount['+i+']']=val.amount
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
		if(menu_id==11){
			window.open(msApp.baseUrl()+"/pogeneralservice/report?id="+id+"&menu_id="+menu_id);
		}
	}

	formatpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpLcPo.pdf('+'\''+row.menu_id+'\''+','+'\''+row.purchase_order_id+'\''+')">'+row.po_no+'</a>';
	}

}
window.MsImpLcPo = new MsImpLcPoController(new MsImpLcPoModel());
$('#implcpoTbl').datagrid();

MsImpLcPo.posearchGrid([])
