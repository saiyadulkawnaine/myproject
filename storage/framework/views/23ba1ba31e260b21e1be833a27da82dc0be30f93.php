<?php
        $i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
?>
<?php if($colorsizes->isNotEmpty()): ?>
<p>New Gmt Color Iron</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Tot. Qc Qty</th>
        <th width="80px"  class="text-center">Bal. Qc Qty</th>
        <th width="80px"  class="text-center">QC Pass Qty</th>
        <th width="40px"  class="text-center">Alter Qty</th>
        <th width="40px"  class="text-center">Spot Qty</th>
        <th width="40px"  class="text-center">Reject Qty</th>
        <th width="40px"  class="text-center">Replace Qty</th>
    </tr>
    <tbody>
        
        <?php $__currentLoopData = $colorsizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorsize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <tr align="center">
        <td width="200px">
            <?php echo e($colorsize->item_description); ?>

            <input type="hidden" name="sales_order_gmt_color_size_id[<?php echo e($i); ?>]" id="sales_order_gmt_color_size_id<?php echo e($i); ?>" value="<?php echo e($colorsize->sales_order_gmt_color_size_id); ?>"/>
        </td>
        <td width="200px"><?php echo e($colorsize->color_name); ?></td>
        <td width="80px"><?php echo e($colorsize->size_name); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->plan_cut_qty); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->cumulative_qty); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->balance_qty); ?></td>
        <td width="80px"><input type="text" name="qty[<?php echo e($i); ?>]" id="qty<?php echo e($i); ?>" value="" class="number integer"/></td>
        <td width="40px"><input type="text" name="alter_qty[<?php echo e($i); ?>]" id="alter_qty<?php echo e($i); ?>" value="" class="number integer"/></td>
        <td width="40px"><input type="text" name="spot_qty[<?php echo e($i); ?>]" id="spot_qty<?php echo e($i); ?>" value="" class="number integer"/></td>
        <td width="40px"><input type="text" name="reject_qty[<?php echo e($i); ?>]" id="reject_qty<?php echo e($i); ?>" value="" class="number integer"/></td>
        <td width="40px"><input type="text" name="replace_qty[<?php echo e($i); ?>]" id="replace_qty<?php echo e($i); ?>" value="" class="number integer"/></td>
    </tr>
        <?php
            $i++;
        ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php endif; ?>
<?php if($saved->isNotEmpty()): ?>
<br/>
<p>Saved Gmt Color Iron</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="100px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Tot. Qc Qty</th>
        <th width="80px"  class="text-center">Bal. Qc Qty</th>
        <th width="80px"  class="text-center">QC Pass Qty</th>
        <th width="40px"  class="text-center">Alter Qty</th>
        <th width="40px"  class="text-center">Spot Qty</th>
        <th width="40px"  class="text-center">Reject Qty</th>
        <th width="40px"  class="text-center">Replace Qty</th>
        <th width="80px"  class="text-center"></th>
    </tr>
    <tbody>
        <?php
        //$i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
        ?>
        <?php $__currentLoopData = $saved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorsize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <tr align="center">
        <td width="200px">
            <?php echo e($colorsize->item_description); ?>

            <input type="hidden" name="sales_order_gmt_color_size_id[<?php echo e($i); ?>]" id="sales_order_gmt_color_size_id<?php echo e($i); ?>" value="<?php echo e($colorsize->sales_order_gmt_color_size_id); ?>"/>
        </td>
        <td width="200px"><?php echo e($colorsize->color_name); ?></td>
        <td width="80px"><?php echo e($colorsize->size_name); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->plan_cut_qty); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->cumulative_qty_saved); ?></td>
        <td width="80px" align="right"><?php echo e($colorsize->balance_qty_saved); ?></td>
        <td width="80px"><input type="text" name="qty[<?php echo e($i); ?>]" id="qty<?php echo e($i); ?>" value="<?php echo e($colorsize->qty); ?>" class="number integer"/></td>
        <td width="40px"><input type="text" name="alter_qty[<?php echo e($i); ?>]" id="alter_qty<?php echo e($i); ?>" value="<?php echo e($colorsize->alter_qty); ?>" class="number integer"/></td>
        <td width="40px"><input type="text" name="spot_qty[<?php echo e($i); ?>]" id="spot_qty<?php echo e($i); ?>" value="<?php echo e($colorsize->spot_qty); ?>" class="number integer"/></td>
        <td width="40px"><input type="text" name="reject_qty[<?php echo e($i); ?>]" id="reject_qty<?php echo e($i); ?>" value="<?php echo e($colorsize->reject_qty); ?>" class="number integer"/></td>
        <td width="40px"><input type="text" name="replace_qty[<?php echo e($i); ?>]" id="replace_qty<?php echo e($i); ?>" value="<?php echo e($colorsize->replace_qty); ?>" class="number integer"/></td>
        <td width="80px"><a href="javascript:void(0)" onclick="MsProdGmtIronQty.delete(event,<?php echo e($colorsize->prod_gmt_iron_qty_id); ?>)">Remove</a></td>
    </tr>
        <?php
            $i++;
        ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php endif; ?>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
