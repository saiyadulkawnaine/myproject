<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilsubsectiontabs">
    <div title="Basic" style="padding:2px" class="easyui-layout"  data-options="fit:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="subsectionTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'name'" width="100">Name</th>
            <th data-options="field:'code'" width="100">Code</th>
            <th data-options="field:'floor'" width="100">Floor</th>
            <th data-options="field:'employee_id'" width="100">Chief Name</th>
            <th data-options="field:'is_treat_sewing_line'" width="100">Is Treat Sewing Line</th>
             <th data-options="field:'is_poly_layout'" width="100">Is Poly Layout</th>
            <th data-options="field:'projected_line_id'" width="100">Projected Line</th>
            <th data-options="field:'qty'" width="100">Line Capacity Qty</th>
            <th data-options="field:'uom_id'" width="100">Uom</th>
            <th data-options="field:'amount'" width="100">Line Capacity Value</th>
            <th data-options="field:'gmt_complexity_id'" width="100">Product Skill</th>
            <th data-options="field:'no_of_operator'" width="100">Operator</th>
            <th data-options="field:'no_of_helper'" width="100">Helper</th>
            <th data-options="field:'status_id'" width="100">Status</th>
            <th data-options="field:'address'" width="100">Address</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Subsection',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
            <form id="subsectionFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row">
            <div class="col-sm-5 req-text">Name </div>
            <div class="col-sm-7">
            <input type="text" name="name" id="name" />
            <input type="hidden" name="id" id="id" />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Code  </div>
            <div class="col-sm-7">
            <input type="text" name="code" id="code" />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5 req-text">Floor Name  </div>
            <div class="col-sm-7">
            {!! Form::select('floor_id', $floors,'',array('id'=>'floor_id')) !!}
            </div>
            </div>   
            <div class="row middle">
            <div class="col-sm-5">Chief Name </div>
            <div class="col-sm-7">
            {!! Form::select('employee_id', $employee,'',array('id'=>'employee_id','style'=>'width:100%;border:1px')) !!}
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Address </div>
            <div class="col-sm-7">
                <textarea name="address" id="address" ></textarea>
                {{-- <input type="text" name="address" id="address"  /> --}}
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Is Treat Sewing Line </div>
            <div class="col-sm-7">
            {!! Form::select('is_treat_sewing_line', $yesno,'',array('id'=>'is_treat_sewing_line')) !!}
            </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Is Poly Layout</div>
                <div class="col-sm-7">
                    {!! Form::select('is_poly_layout_id', $yesno,'',array('id'=>'is_poly_layout_id')) !!}
                </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Projected Line </div>
            <div class="col-sm-7">{!! Form::select('projected_line_id', $yesno,'',array('id'=>'projected_line_id')) !!}</div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Resource Capacity Qty</div>
            <div class="col-sm-7"><input type="text" name="qty" id="qty" value="" class="number integer"  /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Uom</div>
            <div class="col-sm-7">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width:100%;border:1px')) !!}</div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Resource Capacity Value</div>
            <div class="col-sm-7"><input type="text" name="amount" id="amount" value="" class="number integer" /></div>
            </div>   
            <div class="row middle">
            <div class="col-sm-5">Product Skill  </div>
            <div class="col-sm-7">{!! Form::select('gmt_complexity_id', $gmtcomplexity,'',array('id'=>'gmt_complexity_id')) !!}</div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">No of Operator</div>
            <div class="col-sm-7"><input type="text" name="no_of_operator" id="no_of_operator" value="" class="number integer"  /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">No of Helper</div>
            <div class="col-sm-7"><input type="text" name="no_of_helper" id="no_of_helper" value="" class="number integer"  /></div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Plan Type</div>
                <div class="col-sm-7">
                    {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                </div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Sequence  </div>
            <div class="col-sm-7"><input type="text" name="sort_id" id="sort_id" class="number integer" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-5">Status  </div>
            <div class="col-sm-7">{!! Form::select('status_id', $status,'',array('id'=>'status_id')) !!}</div>
            </div>
            </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubsection.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subsectionFrm')" >Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubsection.remove()" >Delete</a>
            </div>
            </form>
            </div>
        </div>
    </div>
        <div title="Company" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'New Company',footer:'#utilcompanysubsectionft'" style="width:450px; padding:2px">
        <form id="companysubsectionFrm">
        <input type="hidden" name="subsection_id" id="subsection_id" value="" />
        </form>
        <table id="companysubsectionTbl">

        </table>
        <div id="utilcompanysubsectionft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompanySubsection.submit()">Save</a>
        </div>
        </div>
        <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
        <table id="companysubsectionsavedTbl">
        </table>
        </div>
        </div>
    </div>       
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllSubsectionController.js"></script>
<script>
(function(){
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

        $('#subsectionFrm [id="employee_id"]').combobox();
        $('#subsectionFrm [id="uom_id"]').combobox();
    })(jQuery);
</script>
        