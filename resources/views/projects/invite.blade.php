<div class = 'bg-white rounded-lg shadow p-4 flex flex-col'>
    <h3 class="font-normal text-xl py-3 -ml-6 mb-2 border-l-4 border-cyan pl-5">
    Invite a user
    </h3>

    <form method="POST" action="{{ $project->path() . '/invite' }}">
        @csrf

        <div class="flex flex-col">
            <div class="mb-3">
                <input type="email" name="email" class="p-2 border border-grey rounded-lg w-full mr-2">
            </div>

            <button class='bg-cyan text-white rounded-lg py-2 px-3 font-semibold text-sm shadow w-1/4'>Invite</button>

            @include('components.forms.errors', ['bag' => 'invitations'])
        </div>
    </form>
</div>
