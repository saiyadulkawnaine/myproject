//require('./jquery.easyui.min.js');
let MsSubsectionModel = require('./MsSubsectionModel');
require('./datagrid-filter.js');

class MsSubsectionController {
	constructor(MsSubsectionModel)
	{
		this.MsSubsectionModel = MsSubsectionModel;
		this.formId='subsectionFrm';
		this.dataTable='#subsectionTbl';
		this.route=msApp.baseUrl()+"/subsection"
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
			this.MsSubsectionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubsectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subsectionFrm [id="employee_id"]').combobox('setValue', '');
		$('#subsectionFrm [id="uom_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubsectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubsectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#subsectionTbl').datagrid('reload');
		msApp.resetForm('subsectionFrm');
		$('#subsectionFrm [id="employee_id"]').combobox('setValue', '');
		$('#subsectionFrm [id="uom_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		subsection = this.MsSubsectionModel.get(index,row);
		subsection.then(function(response){
			$('#subsectionFrm [id="employee_id"]').combobox('setValue', response.data.fromData.employee_id);
			$('#subsectionFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSubsection.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSubsection=new MsSubsectionController(new MsSubsectionModel());
MsSubsection.showGrid();
$('#utilsubsectiontabs').tabs({
    onSelect:function(title,index){
        let subsection_id = $('#subsectionFrm [name=id]').val();
        
        var data={};
		    data.subsection_id=subsection_id;
        if(index==1){
			if(subsection_id===''){
				$('#utilsubsectiontabs').tabs('select',0);
				msApp.showError('Select Subsection First',0);
				return;
			}
			$('#companysubsectionFrm  [name=subsection_id]').val(subsection_id);
			MsCompanySubsection.create()
		}
    }
});