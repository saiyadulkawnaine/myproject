@foreach ($sizes as $size)
<div class="row middle" >
    <div class="col-sm-4">{{ $size->name }} </div>
    <div class="col-sm-8"><input type="text" name="size[{{ $size->stylesize }}]" id="size" class="number integer" value="{{ $size->msure_value }}"/></div>
    </div>
@endforeach
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>