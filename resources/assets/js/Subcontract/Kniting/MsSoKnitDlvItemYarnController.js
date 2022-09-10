let MsSoKnitDlvItemYarnModel = require('./MsSoKnitDlvItemYarnModel');
require('./../../datagrid-filter.js');
class MsSoKnitDlvItemYarnController {
	constructor(MsSoKnitDlvItemYarnModel)
	{
		this.MsSoKnitDlvItemYarnModel = MsSoKnitDlvItemYarnModel;
		this.formId='soknitdlvitemyarnFrm';
		this.dataTable='#soknitdlvitemyarnTbl';
		this.route=msApp.baseUrl()+"/soknitdlvitemyarn"
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
		let so_knit_dlv_item_id = $('#soknitdlvitemFrm  [name=id]').val();
		formObj.so_knit_dlv_item_id=so_knit_dlv_item_id;
		if(formObj.id){
			this.MsSoKnitDlvItemYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitDlvItemYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitDlvItemYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitDlvItemYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoKnitDlvItemYarn.get(d.so_knit_dlv_item_id)
		msApp.resetForm('soknitdlvitemyarnFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitDlvItemYarnModel.get(index,row);
		workReceive.then(function(response){
			//$('#soknitdlvitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_knit_dlv_item_id)
	{
		let data= axios.get(this.route+"?so_knit_dlv_item_id="+so_knit_dlv_item_id);
		data.then(function (response) {
			$('#soknitdlvitemyarnTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvItemYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	
	itemGrid(data){
		let self = this;
		$('#soknitdlvitemyarnsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknitdlvitemyarnFrm [name=so_knit_yarn_rcv_item_id]').val(row.id);
				$('#soknitdlvitemyarnFrm [name=yarn_count]').val(row.count);
				$('#soknitdlvitemyarnFrm [name=item_desc]').val(row.composition);
				$('#soknitdlvitemyarnFrm [name=lot]').val(row.lot);
				$('#soknitdlvitemyarnFrm [name=supplier]').val(row.supplier_name);
				$('#soknitdlvitemyarnWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		msApp.resetForm('soknitdlvitemyarnFrm');
		let so_knit_dlv_item_id=$('#soknitdlvitemFrm  [name=id]').val();
		let data= axios.get(this.route+"/getitem?so_knit_dlv_item_id="+so_knit_dlv_item_id);
		data.then(function (response) {
			$('#soknitdlvitemyarnsearchTbl').datagrid('loadData', response.data);
			$('#soknitdlvitemyarnWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
}
window.MsSoKnitDlvItemYarn=new MsSoKnitDlvItemYarnController(new MsSoKnitDlvItemYarnModel());
MsSoKnitDlvItemYarn.showGrid([]);
MsSoKnitDlvItemYarn.itemGrid([]);
 
