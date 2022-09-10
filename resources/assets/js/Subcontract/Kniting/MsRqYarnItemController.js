let MsRqYarnItemModel = require('./MsRqYarnItemModel');
require('./../../datagrid-filter.js');
class MsRqYarnItemController {
	constructor(MsRqYarnItemModel)
	{
		this.MsRqYarnItemModel = MsRqYarnItemModel;
		this.formId='rqyarnitemFrm';
		this.dataTable='#rqyarnitemTbl';
		this.route=msApp.baseUrl()+"/rqyarnitem"
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
		let rq_yarn_id=$('#rqyarnFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.rq_yarn_id=rq_yarn_id;
		if(formObj.id){
			this.MsRqYarnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRqYarnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let rq_yarn_fabrication_id=$('#rqyarnfabricationFrm [name=id]').val();
		$('#rqyarnitemFrm [name=rq_yarn_fabrication_id]').val(rq_yarn_fabrication_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsRqYarnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRqYarnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('rqyarnitemFrm');
		MsRqYarnItem.get(d.rq_yarn_fabrication_id)
		$('#rqyarnitemFrm [name=rq_yarn_fabrication_id]').val(d.rq_yarn_fabrication_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRqYarnItemModel.get(index,row);
	}
	get(rq_yarn_fabrication_id)
	{
		let params={};
		params.rq_yarn_fabrication_id=rq_yarn_fabrication_id;
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#rqyarnitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsRqYarnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	rqYarnItemWindowOpen(){
		$('#rqYarnItemWindow').window('open');

	}
	searchItem(){
		let lot=$('#rqyarnitemsearchFrm  [name=lot]').val();
		let brand=$('#rqyarnitemsearchFrm  [name=brand]').val();
		let count=$('#rqyarnitemsearchFrm  [name=count]').val();
		let type=$('#rqyarnitemsearchFrm  [name=type]').val();
		let params={};
		params.lot=lot;
		params.brand=brand;
		params.count=count;
		params.type=type;

		let data= axios.get(this.route+"/getitem",{params});
		data.then(function (response) {
			$('#rqyarnitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showrqyarnitemGrid(data){
		$('#rqyarnitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#rqyarnitemFrm [name=inv_yarn_item_id]').val(row.id);
				$('#rqyarnitemFrm [name=composition]').val(row.composition);
				$('#rqyarnitemFrm [name=lot]').val(row.lot);
				$('#rqyarnitemFrm [name=brand]').val(row.brand);
				$('#rqyarnitemFrm [name=count]').val(row.count);
				$('#rqyarnitemFrm [name=yarn_type]').val(row.yarn_type);
				$('#rqyarnitemFrm [name=color_name]').val(row.color_name);
				$('#rqYarnItemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsRqYarnItem=new MsRqYarnItemController(new MsRqYarnItemModel());
MsRqYarnItem.showrqyarnitemGrid([]);
MsRqYarnItem.showGrid([]);