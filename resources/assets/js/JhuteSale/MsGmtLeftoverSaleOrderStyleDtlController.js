let MsGmtLeftoverSaleOrderStyleDtlModel = require('./MsGmtLeftoverSaleOrderStyleDtlModel');
class MsGmtLeftoverSaleOrderStyleDtlController {
	constructor(MsGmtLeftoverSaleOrderStyleDtlModel)
	{
		this.MsGmtLeftoverSaleOrderStyleDtlModel = MsGmtLeftoverSaleOrderStyleDtlModel;
		this.formId='gmtleftoversaleorderstyledtlFrm';
		this.dataTable='#gmtleftoversaleorderstyledtlTbl';
		this.route=msApp.baseUrl()+"/gmtleftoversaleorderstyledtl"
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
			this.MsGmtLeftoverSaleOrderStyleDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtLeftoverSaleOrderStyleDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#gmtleftoversalecosi').html('');
		$('#gmtleftoversaleorderstyledtlFrm [name=jhute_sale_dlv_order_id]').val($('#gmtleftoversaleorderFrm [name=id]').val());
		$('#gmtleftoversaleorderstyledtlFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtLeftoverSaleOrderStyleDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtLeftoverSaleOrderStyleDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtleftoversaleorderstyledtlTbl').datagrid('reload');
		msApp.resetForm('gmtleftoversaleorderstyledtlFrm');
		MsGmtLeftoverSaleOrderQty.resetForm();
		$('#gmtleftoversaleorderstyledtlFrm [name=jhute_sale_dlv_order_id]').val($('#gmtleftoversaleorderFrm [name=id]').val());
		$('#gmtleftoversaleorderstyledtlFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let item = this.MsGmtLeftoverSaleOrderStyleDtlModel.get(index,row);
		item.then(function (response) {
			$('#gmtleftoversaleorderstyledtlFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);
		}).catch(function (error) {
			console.log(error);
		})
	}

	showGrid(jhute_sale_dlv_order_id)
	{
		let self=this;
		var data={};
		 data.jhute_sale_dlv_order_id=jhute_sale_dlv_order_id;
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
				self.edit(index,row);
			},
			onLoadSuccess:function(data){
				var tQty = 0 ;
				var tAmount = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
					}
				]);

			}
		}).datagrid('enableFilter');
	}


	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsGmtLeftoverSaleOrderStyleDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openLeftOverStyleWindow() {
		$('#LeftOverStyleWindow').window('open');
	}

	getGmtLeftOverStyleParams(){
		let params={};
		params.buyer_id = $('#LeftOverStyleSearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#LeftOverStyleSearchFrm  [name=style_ref]').val();
		params.style_description = $('#LeftOverStyleSearchFrm  [name=style_description]').val();
		return params;
	}

	searchLeftOverStyleGrid(){
		let params=this.getGmtLeftOverStyleParams();
		let d= axios.get(this.route+'/gmtleftoverstyle',{params})
		.then(function(response){
			$('#leftoverstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showLeftOverStyleGrid(data){
		let self=this;
		$('#leftoverstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#gmtleftoversaleorderstyledtlFrm [name=style_ref]').val(row.style_ref);
				$('#gmtleftoversaleorderstyledtlFrm [name=company_name]').val(row.company_name);
				$('#gmtleftoversaleorderstyledtlFrm [name=style_id]').val(row.id);
				$('#gmtleftoversaleorderstyledtlFrm [name=uom_id]').val(row.uom_id);
				$('#LeftOverStyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsGmtLeftoverSaleOrderStyleDtl = new MsGmtLeftoverSaleOrderStyleDtlController(new MsGmtLeftoverSaleOrderStyleDtlModel());
MsGmtLeftoverSaleOrderStyleDtl.showLeftOverStyleGrid([]);