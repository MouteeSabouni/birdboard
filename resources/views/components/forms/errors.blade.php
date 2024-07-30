<?php $errorBag = $bag ?? 'default' ?>

@if($errors->$errorBag->any())
<div class="mt-2">
    @foreach($errors->$errorBag->all() as $error)
        <p class="text-red-700 -mb-3 mt-2">{{ $error }}</p>
    @endforeach
</div>
@endif
