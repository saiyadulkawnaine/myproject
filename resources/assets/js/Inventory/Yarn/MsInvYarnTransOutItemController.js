let MsInvYarnTransOutItemModel = require('./MsInvYarnTransOutItemModel');
require('./../../datagrid-filter.js');
class MsInvYarnTransOutItemController {
	constructor(MsInvYarnTransOutItemModel)
	{
		this.MsInvYarnTransOutItemModel = MsInvYarnTransOutItemModel;
		this.formId='invyarntransoutitemFrm';
		this.dataTable='#invyarntransoutitemTbl';
		this.route=msApp.baseUrl()+"/invyarntransoutitem"
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
			this.MsInvYarnTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let inv_isu_id=$('#invyarntransoutFrm [name=id]').val();
		$('#invyarntransoutitemFrm [name=inv_isu_id]').val(inv_isu_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnTransOutItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnTransOutItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvYarnTransOutItem.get(d.inv_isu_id);
		msApp.resetForm('invyarntransoutitemFrm');
		$('#invyarntransoutitemFrm [name=inv_isu_id]').val(d.inv_isu_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnTransOutItemModel.get(index,row);
		data.then(function (response) {
			//$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d = axios.get(this.route,{params})
		.then(function(response){
			$('#invyarntransoutitemTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnTransOutItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openInvYarnWindow()
	{
		$('#invyarntransoutitemWindow').window('open');
	}

	getYarnItemParams(){
		let params={}
		params.color_id=$('#invyarntransoutitemsearchFrm [name=color_id]').val();
		params.brand=$('#invyarntransoutitemsearchFrm [name=brand]').val();
		//params.supplier_id=$('#invyarntransoutitemsearchFrm [name=supplier_id]').val();
		return params;
	}

	searchYarnItem(){
		let params=this.getYarnItemParams();
		let d = axios.get(this.route+"/getyarnitem",{params})
		.then(function(response){
			$('#invyarntransoutitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}


	showYarnItemGrid(data){
		let self=this;
		var ryt = $('#invyarntransoutitemsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.getRate(row.id);
				$('#invyarntransoutitemFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#invyarntransoutitemFrm  [name=yarn_count]').val(row.yarn_count);
				$('#invyarntransoutitemFrm  [name=yarn_des]').val(row.yarn_des);
				$('#invyarntransoutitemFrm  [name=yarn_type]').val(row.yarn_type);
				$('#invyarntransoutitemFrm  [name=yarn_color_name]').val(row.yarn_color_name);
				$('#invyarntransoutitemFrm  [name=lot]').val(row.lot);
				$('#invyarntransoutitemFrm  [name=brand]').val(row.brand);
				$('#invyarntransoutitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invyarntransoutitemWindow').window('close');
			},
		});
		ryt.datagrid('enableFilter').datagrid('loadData', data);
	}

	getRate(inv_yarn_item_id){
		let self=this;
		let params={};
		params.inv_yarn_item_id=inv_yarn_item_id
		let d = axios.get(this.route+"/getrate",{params})
		.then(function(response){
				$('#invyarntransoutitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}


	calculate_qty_form()
	{
		
		let qty=$('#invyarntransoutitemFrm input[name=qty]').val();
		let rate=$('#invyarntransoutitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invyarntransoutitemFrm input[name=amount]').val(amount);
	}

}
window.MsInvYarnTransOutItem=new MsInvYarnTransOutItemController(new MsInvYarnTransOutItemModel());
MsInvYarnTransOutItem.showGrid([]);
MsInvYarnTransOutItem.showYarnItemGrid([]);

