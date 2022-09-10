let MsWstudyLineSetupMinAdjModel = require('./MsWstudyLineSetupMinAdjModel');

class MsWstudyLineSetupMinAdjController {
	constructor(MsWstudyLineSetupMinAdjModel)
	{
		this.MsWstudyLineSetupMinAdjModel = MsWstudyLineSetupMinAdjModel;
		this.formId='wstudylinesetupminadjFrm';
		this.dataTable='#wstudylinesetupminadjTbl';
		this.route=msApp.baseUrl()+"/wstudylinesetupminadj"
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
			this.MsWstudyLineSetupMinAdjModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWstudyLineSetupMinAdjModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWstudyLineSetupMinAdjModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWstudyLineSetupMinAdjModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let wstudy_line_setup_dtl_id=$('#wstudylinesetupdtlFrm [name=id]').val();
		MsWstudyLineSetupMinAdj.get(wstudy_line_setup_dtl_id);
		msApp.resetForm('wstudylinesetupminadjFrm');
		
		$('#wstudylinesetupminadjFrm [name=wstudy_line_setup_dtl_id]').val(wstudy_line_setup_dtl_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsWstudyLineSetupMinAdjModel.get(index,row);

	}
	get(wstudy_line_setup_dtl_id){
		let params={};
		params.wstudy_line_setup_dtl_id=wstudy_line_setup_dtl_id;
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#wstudylinesetupminadjTbl').datagrid('loadData', response.data);
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
			showFooter:true,
			//queryParams:data,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var no_of_minute=0;
				for(var i=0; i<data.rows.length; i++){
					no_of_minute+=data.rows[i]['no_of_minute'].replace(/,/g,'')*1;
				}			
				$(this).datagrid('reloadFooter', [
					{ no_of_minute: no_of_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	// calculateMinute(){
 //        let self=this;
 //        let lunch_end=$('#wstudylinesetupdtlFrm [name=lunch_end_at]').val();
 //        let sewing_end=$('#wstudylinesetupdtlFrm [name=sewing_end_at]').val();
	// 	let lunch_end_str=lunch_end.split(":");
	// 	let sewing_end_str=sewing_end.split(":");
 //        let minute_adj_reason_id=$('#wstudylinesetupminadjFrm [name=minute_adj_reason_id]').val();
 //        let no_of_resource=$('#wstudylinesetupminadjFrm [name=no_of_resource]').val();
	// 	let minutecal=((sewing_end_str[0]-lunch_end_str[0])*60*no_of_resource*1)+sewing_end_str[1]*1+lunch_end_str[1]*1;
	// 	//alert(no_of_resource);
 //        if (minute_adj_reason_id==1) {
	// 		$('#wstudylinesetupminadjFrm [name=no_of_minute]').val(minutecal);
 //        }
        
 //    }

 	calculateMinute(){
        let self=this;
        let minute_adj_reason_id=$('#wstudylinesetupminadjFrm [name=minute_adj_reason_id]').val();
        let no_of_hour=$('#wstudylinesetupminadjFrm [name=no_of_hour]').val();
        let no_of_resource=$('#wstudylinesetupminadjFrm [name=no_of_resource]').val();
		let minutecal=no_of_hour*no_of_resource*60;
        if (minute_adj_reason_id==1 || minute_adj_reason_id==2 || minute_adj_reason_id==7) {
			$('#wstudylinesetupminadjFrm [name=no_of_minute]').val(minutecal);
        }
    }
    
}
window.MsWstudyLineSetupMinAdj=new MsWstudyLineSetupMinAdjController(new MsWstudyLineSetupMinAdjModel());
MsWstudyLineSetupMinAdj.showGrid([]);