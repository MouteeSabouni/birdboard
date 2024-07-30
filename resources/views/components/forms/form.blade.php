@csrf

<div>
    <label for="title">Title</label>

    <div class="control">
        <input type="text" name="title"
            class="rounded-xl bg-white/10 border border-white/10 px-2 py-2 w-96"
            value="{{ $project->title }}"
            placeholder="Insert your title...">
    </div>
</div>

<div>
    <label for="description">Description</label>

    <div class="control">
            <textarea name="description"
                    type="text"
                    class="rounded-xl bg-white/10 border border-white/10 px-2 py-2 w-96"
                    placeholder="Write your description..."
            >{{ $project->description }}</textarea>
    </div>
</div>

<div class="w-96">
    <div class="flex justify-end space-x-2">
        <x-forms.button><a href="javascript:history.back()" class="text-white no-underline">Cancel</a></x-forms.button>
        <x-forms.button type="submit" class="text-white no-underline">{{ $buttonText }}</x-forms.button>
    </div>
</div>

@if($errors->any())
    <div class="mt-2">
        @foreach($errors->all() as $error)
            <li class="text-red-700">{{ $error }}</li>
        @endforeach
    </div>
@endif
