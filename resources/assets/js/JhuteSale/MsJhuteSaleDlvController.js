let MsJhuteSaleDlvModel = require('./MsJhuteSaleDlvModel');
require('./../datagrid-filter.js');
class MsJhuteSaleDlvController {
	constructor(MsJhuteSaleDlvModel)
	{
		this.MsJhuteSaleDlvModel = MsJhuteSaleDlvModel;
		this.formId='jhutesaledlvFrm';
		this.dataTable='#jhutesaledlvTbl';
		this.route=msApp.baseUrl()+"/jhutesaledlv"
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
			this.MsJhuteSaleDlvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteSaleDlvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutesaledlvFrm [id="store_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteSaleDlvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteSaleDlvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutesaledlvTbl').datagrid('reload');
		msApp.resetForm('jhutesaledlvFrm');
		$('#jhutesaledlvFrm [id="store_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let JhuteSaleDlv = this.MsJhuteSaleDlvModel.get(index,row);
		JhuteSaleDlv.then(function (response) {
			$('#jhutesaledlvFrm [id="store_id"]').combobox('setValue', response.data.fromData.store_id);
		}).catch(function (error) {
			console.log(error);
		})
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openWindow() {
		$('#jhutesaledlvwindow').window('open');
	}

	searchJhuteSaleDlv() {
		let params = {};
		params.do_no = $('#jhutesaledlvsearchFrm [name=do_no]').val();
		params.company_id = $('#jhutesaledlvsearchFrm [name=company_id]').val();
		let data = axios.get(this.route + '/getjhutesaledlvorder', { params }).then(function (response) {
			$('#jhutesaledlvsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showJhuteGrid(data) {
		$('#jhutesaledlvsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#jhutesaledlvFrm [name=jhute_sale_dlv_order_id]').val(row.id);
				$('#jhutesaledlvFrm [name=do_no]').val(row.do_no);
				$('#jhutesaledlvFrm [name=company_id]').val(row.company_id);
				$('#jhutesaledlvFrm [name=company_name]').val(row.company_name);
				$('#jhutesaledlvFrm [name=location_name]').val(row.location_name);
				$('#jhutesaledlvFrm [name=buyer_name]').val(row.buyer_name);
				$('#jhutesaledlvsearchTbl').datagrid('loadData', []);
				$('#jhutesaledlvwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	billpdf()
	{
		var id = $('#jhutesaledlvFrm [name=id]').val();
		if(id==""){
			alert("Select a Delivery First");
			return;
		}
		window.open(this.route+"/getbillpdf?id="+id);
	}

	challanpdf()
	{
		var id = $('#jhutesaledlvFrm [name=id]').val();
		if(id==""){
			alert("Select a Delivery First");
			return;
		}
		window.open(this.route+"/getchallanpdf?id="+id);
	}

}
window.MsJhuteSaleDlv=new MsJhuteSaleDlvController(new MsJhuteSaleDlvModel());
MsJhuteSaleDlv.showGrid();
MsJhuteSaleDlv.showJhuteGrid([]);
$('#jhutesaledlvtabs').tabs({
    onSelect:function(title,index){
		let jhute_sale_dlv_id = $('#jhutesaledlvFrm [name=id]').val();
  		var data={};
		data.jhute_sale_dlv_id = jhute_sale_dlv_id;
  		if(index==1){
			if(jhute_sale_dlv_id ===''){
				$('#jhutesaledlvtabs').tabs('select',0);
				msApp.showError('Select Jhute Sale Reference First',0);
				return;
		 	}
			msApp.resetForm('jhutesaledlvitemFrm');
			$('#jhutesaledlvitemFrm  [name=jhute_sale_dlv_id]').val(jhute_sale_dlv_id);
			MsJhuteSaleDlvItem.showGrid(jhute_sale_dlv_id);
   		}
    }
});