//require('./../../jquery.easyui.min.js');
let MsExpProRlzModel = require('./MsExpProRlzModel');
require('./../../datagrid-filter.js');
class MsExpProRlzController {
	constructor(MsExpProRlzModel)
	{
		this.MsExpProRlzModel = MsExpProRlzModel;
		this.formId='expprorlzFrm';
		this.dataTable='#expprorlzTbl';
		this.route=msApp.baseUrl()+"/expprorlz"
	}

	submit()
	{	
        let bank_ref_amount=$('#expprorlzFrm [name=bank_ref_amount]').val();
        let total_doc_value=$('#total_doc_value').val();
        if(bank_ref_amount*1 != total_doc_value*1){
        	alert('Submission amount '+bank_ref_amount+' not equel to realization amount '+total_doc_value);
        	return;
        }

		let formObj=this.getdata();

		//console.log(msApp.qs.stringify(formObj));
		//alert(msApp.qs.stringify(formObj))
		//return;
		if(formObj.id){
			this.MsExpProRlzModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpProRlzModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	getdata(){
		let formObj=msApp.get('expprorlzFrm');
		let i=1;
		$.each($('#frdeTbl').datagrid('getRows'), function (idx, val) {
			$('#frdeTbl').datagrid('endEdit', idx);
				formObj['commercial_head_id['+i+']']=val.commercial_head_id;
				formObj['doc_value['+i+']']=val.doc_value;
				formObj['exch_rate['+i+']']=val.exch_rate;
				formObj['dom_value['+i+']']=val.dom_value;
				
			i++;
		});
        let j=1;
		$.each($('#framTbl').datagrid('getRows'), function (idx, val) {
			$('#framTbl').datagrid('endEdit', idx);
				formObj['a_commercial_head_id['+j+']']=val.commercial_head_id;
				formObj['a_doc_value['+j+']']=val.doc_value;
				formObj['a_exch_rate['+j+']']=val.exch_rate;
				formObj['a_dom_value['+j+']']=val.dom_value;
				formObj['a_ac_loan_id['+j+']']=val.ac_loan_id;
				formObj['a_ac_loan_no['+j+']']=val.ac_loan_no;
				formObj['a_acc_term_loan_payment_id['+j+']']=val.acc_term_loan_payment_id;
				
			j++;
		});
		return formObj;
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpProRlzModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpProRlzModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expprorlzTbl').datagrid('reload');
		msApp.resetForm('expprorlzFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let data=this.MsExpProRlzModel.get(index,row);	
		data.then(function (response) {
			MsExpProRlzDeduct.showGrid(response.data);
			MsExpProRlzAmount.showGrid(response.data);
			//$('#frdeTbl').datagrid('loadData',response.data);
			//$('#framTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
		console.log(error);
		});

	}

	// showGrid(){
	// 	$('#expprorlzTbl').datagrid({
	// 		method:'get',
	// 		width:'100%',
	// 		height:'100%',
	// 		fit:true,
	// 		//showFooter:true,
	// 		singleSelect:true,
	// 		idField:'id',
	// 		url:this.route,
	// 		columns:[[
	// 			{
	// 				field:'id',
	// 				title:'ID',
	// 				width:80,
	// 				halign:'center',
	// 				align:'left',
	// 			},
	// 			{
	// 				field:'realization_date',
	// 				title:'Realization Date',
	// 				width:100,
	// 				halign:'center',
	// 				align:'left',
	// 			},
	// 			{
	// 				field:'bank_ref_bill_no',
	// 				title:'Bank Bill No',
	// 				width:160,
	// 				halign:'center',
	// 				align:'left',
	// 			},
	// 			{
	// 				field:'lc_sc_no',
	// 				title:'LC/SC NO',
	// 				width:200,
	// 				halign:'center',
	// 				align:'left',
	// 			},
	// 			{
	// 				field:'bank_ref_date',
	// 				title:'Bank Ref Bill Date',
	// 				width:80,
	// 				halign:'center',
	// 				align:'left',
	// 			},
	// 			{
	// 				field:'currency_id',
	// 				title:'Currency',
	// 				width:80,
	// 				halign:'center',
	// 				align:'left',
	// 			}
	// 			,
	// 			{
	// 				field:'courier_recpt_no',
	// 				title:'Currier recv No',
	// 				width:100,
	// 				halign:'center',
	// 				align:'left',
	// 			}
	// 			,
	// 			{
	// 				field:'remarks',
	// 				title:'Remarks',
	// 				width:200,
	// 				halign:'center',
	// 				align:'left',
	// 			}
	// 		]]
	// 	}).datagrid('enableFilter');
	// }

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpProRlz.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDocSubmissionWindow(){
		$('#expdocsubwindow').window('open');
	}
	showDocSubmissionGrid(){
		let data = {};
		data.bank_ref_bill_no = $('#expdocsubsearchFrm [name="bank_ref_bill_no"]').val();
		data.date_from = $('#expdocsubsearchFrm [name="date_from"]').val();
		data.date_to = $('#expdocsubsearchFrm [name="date_to"]').val();
		let self = this;
		$('#expdocsubsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/importdocsubmission/",
			onClickRow: function(index,row){
				    $('#expprorlzFrm [name=id]').val(row.id);
					$('#expprorlzFrm [name=exp_doc_submission_id]').val(row.exp_doc_submission_id);
					$('#expprorlzFrm [name=bank_ref_bill_no]').val(row.bank_ref_bill_no);
					$('#expprorlzFrm [name=bank_ref_date]').val(row.bank_ref_date);
					$('#expprorlzFrm [name=bank_ref_amount]').val(row.bank_ref_amount);
					$('#expprorlzFrm [name=negotiated_amount]').val(row.negotiated_amount);
					$('#expprorlzFrm [name=currency_id]').val(row.currency_id);
					$('#expprorlzFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expprorlzFrm [name=buyer_id]').val(row.buyer_id);
					$('#expprorlzFrm [name=beneficiary_id]').val(row.beneficiary_id); 
					$('#expprorlzFrm [name=realization_date]').val(row.realization_date);
					$('#expprorlzFrm [name=remarks]').val(row.remarks);
					$('#expdocsubwindow').window('close');
					//MsExpProRlzDeduct.showGrid([]);
			        //MsExpProRlzAmount.showGrid([]);
					if(row.id){
						self.edit(index,row);
					}else{
						MsExpProRlz.loadSubPage(row.exp_doc_submission_id);
					}
					
			}
			}).datagrid('enableFilter');
	}
	

	loadSubPage(exp_doc_submission_id){
		var d= axios.get(msApp.baseUrl()+"/expprorlz/gethead?exp_doc_submission_id="+exp_doc_submission_id)
		.then(function (response) {
			MsExpProRlzDeduct.showGrid(response.data);
			MsExpProRlzAmount.showGrid(response.data);
		})
		.catch(function (error) {
		console.log(error);
		});
	}
	
}
window.MsExpProRlz=new MsExpProRlzController(new MsExpProRlzModel());
//MsExpProRlz.showGrid();


// $('#comExpRlzTabs').tabs({
// 	onSelect:function(title,index){
// 	 let exp_pro_rlz_id = $('#expprorlz  [name=id]').val();

// 	 var data={};
// 	  data.exp_pro_rlz_id=exp_pro_rlz_id;

// 	 if(index==1){
// 		 if(exp_pro_rlz_id===''){
// 			 $('#comExpRlzTabs').tabs('select',0);
// 			 msApp.showError('Select an ExpPI First',0);
// 			 return;
// 		  }
// 		 $('#expprorlzdecuctFrm  [name=exp_pro_rlz_id]').val(exp_pro_rlz_id)
// 		 MsExpProRlz.showGrid();
// 	 }
// 	 if(index==2){
// 		 if(exp_pro_rlz_id===''){
// 			 $('#comExpRlzTabs').tabs('select',0);
// 			 msApp.showError('Select an ExpPI First',0);
// 			 return;
// 		  }
// 		 $('#expprorlzamountFrm  [name=exp_pro_rlz_id]').val(exp_pro_rlz_id)
// 		 MsExpProrlzamount.showGrid(exp_pro_rlz_id);
// 	 }
// }
// });