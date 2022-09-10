<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true,hideCollapsedContent:false" style="height:83px;width:600px">
                <div data-options="footer:'#searchft'">
                    <code>
                        <form id="itemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-2" style="padding-right:0px">Item Category{!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                                <div class="col-sm-2" style="padding-right:0px">Item Class{!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id','style'=>'width: 100%; border-radius:2px')) !!} </div>
                                <div class="col-sm-2" style="padding-right:0px">Item Nature
                                    {!! Form::select('item_nature_id', $itemnature,'',array('id'=>'item_nature_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                <div class="col-sm-2" style="padding-right:0px">Sub Class Name<input type="text" name="sub_class_name" id="sub_class_name" value="" style="width: 100%; border-radius:2px"/>
                                </div>
                                <div class="col-sm-2" >Yarn Count{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                <div id="searchft" class="col-sm-2" style="padding:10px 0px;"><a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccount.getItem()">Search</a>
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
            </div>
            <div data-options="region:'center',border:true" style="padding:2px">
                <table id="itemaccountTbl" style="width:1900px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'itemcategory'" width="100">Item Category</th>
                            <th data-options="field:'itemclass'" width="100">Item Class</th>
                            <th data-options="field:'sub_class_name'" width="100">Sub Class Name</th>
                            <th data-options="field:'sub_class_code'" width="70">Sub Class<br/> Code</th>
                            <th data-options="field:'item_nature_id'" width="120">Item Nature</th>
                            <th data-options="field:'count_name'" width="70">Yarn Count</th>
                            <th data-options="field:'yarn_type'" width="100">Yarn Type</th>
                            <th data-options="field:'composition'" width="100">Composition</th>
                            <th data-options="field:'item_description'" width="120">Item Description</th>
                            <th data-options="field:'specification'" width="100">Specification</th>
                            <th data-options="field:'color'" width="80">Color</th>
                            <th data-options="field:'size'" width="70">Size</th>
                            <th data-options="field:'gmt_position'" width="100">Gmt Position</th>
                            <th data-options="field:'gmt_category'" width="100">Gmt Category </th>
                            <th data-options="field:'reorder_level'" width="100">Reorder Level  </th>
                            <th data-options="field:'uom'" width="50">UOM </th>
                            <th data-options="field:'min_level'" width="100">Min Level   </th>
                            <th data-options="field:'max_level'" width="100">Max Level   </th>
                            <th data-options="field:'custom_code'" width="100">Custom Code   </th>
                            <th data-options="field:'consumption_level_id'" width="80">Consumption Level </th>
                            <th data-options="field:'status_id'" width="80">Status </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Item Account',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding: 0px 12px">
        <code>
            <form id="itemaccountFrm">
                <div class="row">
                    <div class="col-sm-5 req-text">Item Category</div>
                    <div class="col-sm-7">
                        {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id','onchange'=>'MsItemAccount.getCategoryData(this.value)')) !!}
                    <input type="hidden" name="id" id="id" readonly/>
                    <input type="hidden" name="identity" id="identity" readonly/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Item Class </div>
                    <div class="col-sm-7">
                        {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Sub Class Name  </div>
                    <div class="col-sm-7">
                        <input type="text" name="sub_class_name" id="sub_class_name" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Sub Class Code  </div>
                    <div class="col-sm-7">
                        <input type="text" name="sub_class_code" id="sub_class_code" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Item Nature </div>
                    <div class="col-sm-7">
                        {!! Form::select('item_nature_id', $itemnature,'',array('id'=>'item_nature_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Yarn Count  </div>
                    <div class="col-sm-7">
                        {!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Yarn Type </div>
                    <div class="col-sm-7">
                        {!! Form::select('yarntype_id', $yarntype,'',array('id'=>'yarntype_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Composition  </div>
                    <div class="col-sm-7">
                        {!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Item Description  </div>
                    <div class="col-sm-7">
                    	{{-- <textarea name="item_description" id="item_description"></textarea> --}}
                        <input type="text" name="item_description" id="item_description" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Specification  </div>
                    <div class="col-sm-7">
                    	<textarea name="specification" id="specification"></textarea>
                        <!-- <input type="text" name="specification" id="specification" value=""/> -->
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Color </div>
                    <div class="col-sm-7">
                        {!! Form::select('color_id', $color,'',array('id'=>'color_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Size  </div>
                    <div class="col-sm-7">
                        {!! Form::select('size_id', $size,'',array('id'=>'size_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Gmt Position  </div>
                    <div class="col-sm-7">
                        <input type="text" name="gmt_position" id="gmt_position" value=""/>
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-5">Gmt Category  </div>
                    <div class="col-sm-7">
                        {!! Form::select('gmt_category', $gmtcategory,'',array('id'=>'gmt_category')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Reorder Level  </div>
                    <div class="col-sm-7">
                        <input type="text" name="reorder_level" id="reorder_level" value=""/>
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-5">Uom  </div>
                    <div class="col-sm-7">
                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Min Level  </div>
                    <div class="col-sm-7">
                        <input type="text" name="min_level" id="min_level" value=""/>
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-5">Max Level </div>
                    <div class="col-sm-7">
                        <input type="text" name="max_level" id="max_level" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Custom Code </div>
                    <div class="col-sm-7">
                        <input type="text" name="custom_code" id="custom_code" value=""/>
                    </div>
                </div>
                 <div class="row middle">
                    <div class="col-sm-5">Consumption Level  </div>
                    <div class="col-sm-7">
                        {!! Form::select('consumption_level_id', $consumptionlevel,'',array('id'=>'consumption_level_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Status  </div>
                    <div class="col-sm-7">
                        {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccount.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('itemaccountFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccount.remove()" >Delete</a>
                </div>
            </form>
        </code>
    </div>
</div>
