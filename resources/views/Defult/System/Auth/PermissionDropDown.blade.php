<div class="col-sm-5">
{!! Form::select('lstBox1', $permission_arr,'',array('id'=>'lstBox1','multiple'=>'multiple','style'=>'height:150px','class'=>'LeftRight')) !!}
</div>
<div class="col-sm-2" style="text-align:center">
<br />
 <input type='button' id='btnAllRight' value='>>' class="btn btn-default" style="width:40px" onclick="msApp.moveAllToRight(event,'lstBox1','lstBox2')" />
<br />
<input type='button' id='btnRight' value='>' class="btn btn-default" style="width:40px" onclick="msApp.moveToRight(event,'lstBox1','lstBox2')" />
<br />
<input type='button' id='btnLeft' value='<' class="btn btn-default" style="width:40px" onclick="msApp.moveToLeft(event,'lstBox1','lstBox2')"/>
<br />
<input type='button' id='btnAllLeft' value='<<' class="btn btn-default" style="width:40px" onclick="msApp.moveAllToLeft(event,'lstBox1','lstBox2')" />
</div>
<div class="col-sm-5">
{!! Form::select('lstBox1', $permited,'',array('id'=>'lstBox2','multiple'=>'multiple','style'=>'height:150px','class'=>'LeftRight')) !!}
</div>