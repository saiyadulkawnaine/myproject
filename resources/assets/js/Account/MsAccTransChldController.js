
let MsAccTransChldModel = require('./MsAccTransChldModel');

class MsAccTransChldController {
	constructor(MsAccTransChldModel)
	{
		this.MsAccTransChldModel = MsAccTransChldModel;
		this.formId='transchldFrm';
		this.dataTable='#transchldTbl';
		this.route=msApp.baseUrl()+"/acctranschld"
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
		let formObj=this.getdata();
		if(formObj.id){
			this.MsAccTransChldModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
		   this.MsAccTransChldModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', 0);
		$('#transchldFrm [id="party_id"]').combobox('setValue', '');
		$('#transchldFrm [id="location_id"]').combobox('setValue', '');
		$('#transchldFrm [id="division_id"]').combobox('setValue', '');
		$('#transchldFrm [id="department_id"]').combobox('setValue', '');
		$('#transchldFrm [id="section_id"]').combobox('setValue', '');
		$('#transchldFrm [id="employee_id"]').combobox('setValue', '');
	}

	

	remove()
	{
		//let formObj=msApp.get(this.formId);
		let id=$('#transprntFrm [name="id"]').val();
		this.MsAccTransChldModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccTransChldModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#transprntFrm  [name=trans_no]').val('');
		$('#transprntFrm  [name=id]').val('');
		$('#transprntFrm  [name=narration]').val('');
		$('#transchldTbl').datagrid('loadData', []);
		msApp.resetForm('transchldFrm');
		$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', '');
        var t = $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus();
        //t.focus();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccTransChldModel.get(index,row);
	}

	getChart(data)
	{
		let amount_debit=$('#transchldFrm  [name=amount_debit]').val();
		let amount_credit=$('#transchldFrm  [name=amount_credit]').val();

		//MsTransChld.resetForm ();
		$('#transchldFrm  [name=amount_debit]').val(amount_debit);
		$('#transchldFrm  [name=amount_credit]').val(amount_credit);
		let chart=msApp.getJson("/accchartctrlhead/getjsonbycode",data);
		
		chart.then(function (response) {
			/*if(response.data.ctrlhead_type_id==2){
				alert('You have seleted Report Head,Please Select Chart of Account');
				$('#acc_chart_ctrl_head_id').combotree('setValue', '');
				$('#code').val('');
				$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus();
				return;
			}*/
			$('#code').val(response.data.code);
			$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', response.data.id);
			$('#transchldFrm [id="party_id"]').combobox('loadData',response.data.party);
			$('#transchldFrm [id="location_id"]').combobox('loadData',response.data.location);
			$('#transchldFrm [id="division_id"]').combobox('loadData',response.data.division);
			$('#transchldFrm [id="department_id"]').combobox('loadData',response.data.department);
			$('#transchldFrm [id="section_id"]').combobox('loadData',response.data.section);
			$('#transchldFrm [id="employee_id"]').combobox('loadData',response.data.employee);
		})
		.catch(function (error) {
			$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', '');
			console.log(error);
		});

		return chart;
	}

	codeChange()
	{
		let data={};
		let code=$('#transchldFrm  [name=code]').val();
		data.code=code
		this.getChart(data);
	}

	localedit(index,row)
	{
		//let code=$('#transchldFrm  [name=code]').val();
		this.resetForm ()
		let data={};
		data.code=row.code;
		let chart=this.getChart(data);
		chart.then(function (response) {
			for(var key in row){
				$('#transchldFrm [name='+key+']').val(row[key]);
				$('#transchldFrm [name=edit_index]').val(index);
				$('#transchldFrm [id="party_id"]').combobox('setValue',row.party_id);
				$('#transchldFrm [id="location_id"]').combobox('setValue',row.location_id);
				$('#transchldFrm [id="division_id"]').combobox('setValue',row.division_id);
				$('#transchldFrm [id="department_id"]').combobox('setValue',row.department_id);
				$('#transchldFrm [id="section_id"]').combobox('setValue',row.section_id);
				$('#transchldFrm [id="employee_id"]').combobox('setValue',row.employee_id);
			}
		})
		.catch(function (error) {
			$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', '');
			console.log(error);
		});
	}

	showGrid(data)
	{
		
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//data:data,
			idField:'id',
			showFooter:true,
			onClickRow: function(index,row){
				self.localedit(index,row);
			},
			onLoadSuccess: function(data){
				var tamount_debit=0;
				var tamount_credit=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['amount_debit']){
						tamount_debit+=data.rows[i]['amount_debit']*1;
					}
					if(data.rows[i]['amount_credit'])
					{
						tamount_credit+=data.rows[i]['amount_credit']*1;
					}
				}
				let balance=tamount_debit-tamount_credit;
				
				/*$(this).datagrid('reloadFooter', [
				{ amount_debit: tamount_debit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount_credit: tamount_credit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),exch_rate: balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);*/

				let fdata={};
				fdata.total_debit=tamount_debit;
				fdata.total_credit=tamount_credit;
				fdata.balance=balance;

				MsTransChld.reloadFooter(fdata)
				MsTransChld.mergeCellsFotter()
					
			}
		}).datagrid('loadData', data);
		//.datagrid('reloadFooter', [{code:'Total',amount_debit:'123'}])
		//dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsTransChld.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	formatcancel(value,row,index)
	{
		return '<a href="javascript:void(0)"  onClick="MsTransChld.cancel('+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Remove</span></a>';
	}

	cancel(id,e){
		let tr=e.target.closest('tr');
		//let index = $('#transchldTbl').datagrid('getRowIndex', id);//important don't delete
		let index=tr.rowIndex;
		$.messager.confirm('Delete', 'Are you confirm this?', function(r){
			if (r){
				$('#transchldTbl').datagrid('deleteRow', index);
				let data=MsTransChld.getbalance();
		        MsTransChld.resetForm ();
				MsTransChld.reloadFooter(data)
				MsTransChld.mergeCellsFotter()
				if(data.balance>0){
					$('#transchldFrm [name=amount_credit]').val(data.balance*1);
				}
				if(data.balance<0){
					$('#transchldFrm [name=amount_debit]').val(data.balance*-1);
				}
			}
		});
	}

	add(){
		let formObj=msApp.get(this.formId);
		if(formObj.acc_chart_ctrl_head_id=='' || formObj.acc_chart_ctrl_head_id==0)
		{
			alert('Select Account Head');
			return;
		}

		if((formObj.amount_debit=='' && formObj.amount_credit=='') || (formObj.amount_debit==0 && formObj.amount_credit==0))
		{
			alert('Insert Debit or Credit');
			return;
		}

		formObj.acc_chart_ctrl_head_name=$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('getText');
		formObj.party_name=$('#transchldFrm [id="party_id"]').combobox('getText');
		formObj.location_name=$('#transchldFrm [id="location_id"]').combobox('getText');
		formObj.division_name=$('#transchldFrm [id="division_id"]').combobox('getText');
		formObj.department_name=$('#transchldFrm [id="department_id"]').combobox('getText');
		formObj.section_name=$('#transchldFrm [id="section_id"]').combobox('getText');
		formObj.employee_name=$('#transchldFrm [id="employee_id"]').combobox('getText');
        formObj.profitcenter_name=$("#profitcenter_id option:selected").text();
        if(formObj.edit_index===''){
           /*let index=0;
           if(formObj.amount_debit)
           {
           	 index= MsTransChld.getLastDebitIndex();
           }
           if(formObj.amount_credit)
           {
           	  index=MsTransChld.getLastCreditIndex()
           }
        	
            if (index) 
            {
				$(this.dataTable).datagrid('insertRow',{
				index: index,	// index start with 0
				row: formObj
				});
            }
            else
            {
               $(this.dataTable).datagrid('appendRow',formObj);
            }*/
            $(this.dataTable).datagrid('appendRow',formObj);
        	MsTransChld.resetForm ();
        }else{
			$(this.dataTable).datagrid('updateRow',{
			index: formObj.edit_index,
			row: formObj
			});
			MsTransChld.resetForm ();

			$('#'+this.formId+' [name=edit_index]').val('');
        }
        $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', '');
        $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus();
		let data=this.getbalance();
		if(data.balance>0){
			
			$('#'+this.formId+' [name=amount_credit]').val(data.balance*1);
		}
		if(data.balance<0){
			$('#'+this.formId+' [name=amount_debit]').val(data.balance*-1);
		}
		$('#'+this.formId+' [name=chld_narration]').val(formObj.chld_narration);
		$('#'+this.formId+' [name=loan_ref_no]').val(formObj.loan_ref_no);
		$('#'+this.formId+' [name=other_ref_no]').val(formObj.other_ref_no);
		$('#'+this.formId+' [name=import_lc_ref_no]').val(formObj.import_lc_ref_no);
		$('#'+this.formId+' [name=export_lc_ref_no]').val(formObj.export_lc_ref_no);
		$('#'+this.formId+' [name=bill_no]').val(formObj.bill_no);
		MsTransChld.reloadFooter(data)
		MsTransChld.mergeCellsFotter()
	}
	getdata(){
		let formObj=msApp.get('transprntFrm');
		let i=1;
		let total_debit=0;
		let total_credit=0;
		$.each($('#transchldTbl').datagrid('getRows'), function (idx, val) {
				formObj['acc_chart_ctrl_head_id['+i+']']=val.acc_chart_ctrl_head_id;
				formObj['code['+i+']']=val.code;
				formObj['party_id['+i+']']=val.party_id;
				formObj['employee_id['+i+']']=val.employee_id;
				formObj['bill_no['+i+']']=val.bill_no;
				formObj['amount_debit['+i+']']=val.amount_debit;
				formObj['amount_credit['+i+']']=val.amount_credit;
				formObj['exch_rate['+i+']']=val.exch_rate;
				formObj['amount_foreign_debit['+i+']']=val.amount_foreign_debit;
				formObj['amount_foreign_credit['+i+']']=val.amount_foreign_credit;
				formObj['profitcenter_id['+i+']']=val.profitcenter_id;
				formObj['location_id['+i+']']=val.location_id;
				formObj['division_id['+i+']']=val.division_id;
				formObj['department_id['+i+']']=val.department_id;
				formObj['section_id['+i+']']=val.section_id;
				formObj['loan_ref_no['+i+']']=val.loan_ref_no;
				formObj['other_ref_no['+i+']']=val.other_ref_no;
				formObj['chld_narration['+i+']']=val.chld_narration;
				formObj['import_lc_ref_no['+i+']']=val.import_lc_ref_no;
				formObj['export_lc_ref_no['+i+']']=val.export_lc_ref_no;
				if(val.amount_debit){
					total_debit+=val.amount_debit*1;
				}
				if (val.amount_credit) {
					total_credit+=val.amount_credit*1;
				}
			i++;
		});
		formObj.total_debit=(total_debit*1).toFixed(2);
		formObj.total_credit=(total_credit*1).toFixed(2);
		return formObj;
	}
	getbalance(){
		let data={};
		
		let total_debit=0;
		let total_credit=0;
		$.each($('#transchldTbl').datagrid('getRows'), function (idx, val) {
				
				if(val.amount_debit){
					total_debit+=val.amount_debit*1;
				}
				if (val.amount_credit) {
					total_credit+=val.amount_credit*1;
				}
		});
		//alert(total_debit);
		//alert(total_credit);
		let balance=parseFloat((total_debit-total_credit).toFixed(2));
		//alert(balance)
		data.total_debit=total_debit;
		data.total_credit=total_credit;
		data.balance=balance;
		return data;
	}

	changeDebit()
	{

	   let amount_debit=$('#'+this.formId+' [name=amount_debit]').val();
	   let exch_rate=$('#'+this.formId+' [name=exch_rate]').val();	
	   if(exch_rate==''){
	   	       exch_rate=0;
	   }
	   let amount_foreign_debit=amount_debit/exch_rate;
	   amount_foreign_debit = amount_foreign_debit.toFixed(4);
	   if(exch_rate){
	   	    $('#'+this.formId+' [name=amount_foreign_debit]').val(amount_foreign_debit);
	   }
       $('#'+this.formId+' [name=amount_credit]').val('')
       $('#'+this.formId+' [name=amount_foreign_credit]').val('')
	}

	changeCredit()
	{
	   let amount_credit=$('#'+this.formId+' [name=amount_credit]').val();
	   let exch_rate=$('#'+this.formId+' [name=exch_rate]').val();
	   if(exch_rate==''){
	   	       exch_rate=0;

	   }	
	   let amount_foreign_credit=amount_credit/exch_rate;
	   amount_foreign_credit = amount_foreign_credit.toFixed(4);
	   if(exch_rate){
	   	    $('#'+this.formId+' [name=amount_foreign_credit]').val(amount_foreign_credit);
	   }
       $('#'+this.formId+' [name=amount_debit]').val('')
       $('#'+this.formId+' [name=amount_foreign_debit]').val('')
	}

	changeExchRate()
	{
		let amount_debit=$('#'+this.formId+' [name=amount_debit]').val();
		let amount_credit=$('#'+this.formId+' [name=amount_credit]').val();
		let exch_rate=$('#'+this.formId+' [name=exch_rate]').val();	
		if(exch_rate==''){
	   	    exch_rate=0;
	    }

		if(amount_debit)
		{
			let amount_foreign_debit=amount_debit/exch_rate;
			 amount_foreign_debit = amount_foreign_debit.toFixed(4);
			if(exch_rate)
			{
				$('#'+this.formId+' [name=amount_foreign_debit]').val(amount_foreign_debit);
			}
			$('#'+this.formId+' [name=amount_credit]').val('')
			$('#'+this.formId+' [name=amount_foreign_credit]').val('');	
		}

		if(amount_credit)
		{
			let amount_foreign_credit=amount_credit/exch_rate;
			 amount_foreign_credit = amount_foreign_credit.toFixed(4);
			if(exch_rate){
				$('#'+this.formId+' [name=amount_foreign_credit]').val(amount_foreign_credit);
			}
			$('#'+this.formId+' [name=amount_debit]').val('')
			$('#'+this.formId+' [name=amount_foreign_debit]').val('')
		}
	}

	mergeCellsFotter()
	{
		
		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'acc_chart_ctrl_head_name',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'bill_no',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'amount_credit',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'amount_foreign_debit',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'profitcenter_name',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'division_name',
		colspan: 2,
		type: 'footer'
		});

		$('#transchldTbl').datagrid('mergeCells', {
		index: 0,
		field: 'chld_narration',
		colspan: 2,
		type: 'footer'
		});

		





	}

	reloadFooter(data)
	{
		$(this.dataTable).datagrid('reloadFooter', [
		{ acc_chart_ctrl_head_name:'Sum Debit',bill_no: data.total_debit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount_credit:'Sum Credit',amount_foreign_debit: data.total_credit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),profitcenter_name:'Balance',division_name: data.balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
		]);
	}
	setfucusonchld()
	{
		$('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus()
		//$('#accpnanel').layout('collapse','north');
		msApp.colExp('accpnanel','north');
	}



	getLastCreditIndex(){
		let index=0;
		let i=1;
		$.each($('#transchldTbl').datagrid('getRows'), function (idx, val) {
			if(val.amount_credit)
			{
				 index=idx;
			}
			i++;	
		});
		index++;
		return index?index:i;
	}

	getLastDebitIndex(){
		let index=0;
		let i=1;
		$.each($('#transchldTbl').datagrid('getRows'), function (idx, val) {
			if(val.amount_debit)
			{

				 index=idx;
			}
			i++;	
		});
		index++;
		return index?index:i;
	}

	
	
}
window.MsTransChld=new MsAccTransChldController(new MsAccTransChldModel());
MsTransChld.showGrid([]);
MsTransChld.mergeCellsFotter();

