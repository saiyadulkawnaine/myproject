let MsWstudyLineSetupDtlOrdModel = require('./MsWstudyLineSetupDtlOrdModel');

class MsWstudyLineSetupDtlOrdController {
	constructor(MsWstudyLineSetupDtlOrdModel)
	{
		this.MsWstudyLineSetupDtlOrdModel = MsWstudyLineSetupDtlOrdModel;
		this.formId='wstudylinesetupdtlordFrm';
		this.dataTable='#wstudylinesetupdtlordTbl';
		this.route=msApp.baseUrl()+"/wstudylinesetupdtlord"
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
			this.MsWstudyLineSetupDtlOrdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWstudyLineSetupDtlOrdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWstudyLineSetupDtlOrdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWstudyLineSetupDtlOrdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let wstudy_line_setup_dtl_id=$('#wstudylinesetupdtlFrm [name=id]').val();
		MsWstudyLineSetupDtlOrd.get(wstudy_line_setup_dtl_id);
		msApp.resetForm('wstudylinesetupdtlordFrm');
		$('#wstudylinesetupdtlordFrm [name=wstudy_line_setup_dtl_id]').val(wstudy_line_setup_dtl_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsWstudyLineSetupDtlOrdModel.get(index,row);

	}
	get(wstudy_line_setup_dtl_id){
		let params={};
		params.wstudy_line_setup_dtl_id=wstudy_line_setup_dtl_id;
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#wstudylinesetupdtlordTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		/*var data={};
		data.wstudy_line_setup_id=wstudy_line_setup_id;*/
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//queryParams:data,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}


	//////////////////////////////////
	openLineSetupStyleRefWindow(){
		$('#openlinesetupstylerefwindow').window('open');
	}
	searchLineSetupStyleRefGrid(){
		let data={};
		data.company_id=$('#linesetupstylerefsearchFrm [name=company_id]').val();
		data.buyer_id=$('#linesetupstylerefsearchFrm [name=buyer_id]').val();
		data.job_no=$('#linesetupstylerefsearchFrm [name=job_no]').val();
		data.style_ref=$('#linesetupstylerefsearchFrm [name=style_ref]').val();
		let self = this;
		$('#linesetupstylerefsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/wstudylinesetupdtlord/linesetupstyleref",
			onClickRow: function(index,row){
				$('#wstudylinesetupdtlordFrm [name=style_gmt_id]').val(row.style_gmt_id);
				$('#wstudylinesetupdtlordFrm [name=item_description]').val(row.item_description);
				$('#wstudylinesetupdtlordFrm [name=sales_order_id]').val(row.sales_order_id);
				$('#wstudylinesetupdtlordFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#openlinesetupstylerefwindow').window('close');
				$('#linesetupstylerefsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter');
	}
	

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsWstudyLineSetupDtlOrd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsWstudyLineSetupDtlOrd=new MsWstudyLineSetupDtlOrdController(new MsWstudyLineSetupDtlOrdModel());
MsWstudyLineSetupDtlOrd.showGrid([]);
MsWstudyLineSetupDtlOrd.searchLineSetupStyleRefGrid([]);