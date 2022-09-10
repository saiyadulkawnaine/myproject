let MsPlaningBoardModel = require('./MsPlaningBoardModel');
require('./../datagrid-filter.js');
class MsPlaningBoardController {
	constructor(MsPlaningBoardModel)
	{
		this.MsPlaningBoardModel = MsPlaningBoardModel;
		this.formId='planingboardFrm';
		this.dataTable='#planingboardTbl';
		this.route=msApp.baseUrl()+"/planingboard"
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
            this.MsPlaningBoardModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsPlaningBoardModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsPlaningBoardModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlaningBoardModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#planingboardTbl').datagrid('reload');
		msApp.resetForm('planingboardFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlaningBoardModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsPlaningBoard.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPlaningBoard = new MsPlaningBoardController(new MsPlaningBoardModel());
//MsPlaningBoard.showGrid();
