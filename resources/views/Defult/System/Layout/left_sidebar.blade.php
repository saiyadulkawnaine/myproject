<Style>
#one:hover
{
	animation-name: colorchange;
	animation-duration: 3s;
}

@keyframes colorchange {
	0% 
	{
		Border-radius:5px;
	}
	50% 
	{
		Border-radius:30px;
		Transform:skew(10deg)
	}
	100% 
	{
		Border-radius:5px;
	}
}
</style>
<div data-options="region:'west',split:false,title:'Navigation',hideCollapsedContent:false"  style="width:250px;padding:10px;background:url( <?php echo url('/');?>/images/pattern.svg);">
	<?php
    echo $menus;
	?>
</div>