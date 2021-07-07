<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="form-floating mb-3">
            <form id="update" name="update" class="row g-3 needs-validation" role="form" method="post"
                  @if(request()->routeIs('bot_messages.index'))
                  action="{{ route('bot_messages.store') }}"
                  @elseif(request()->routeIs('bot_messages.edit'))
                  action="{{ route('bot_messages.update', $message->id) }}"
                @endif>
                @csrf
                @if(request()->routeIs('bot_messages.edit'))
                    @method('patch')
                @endif
                <div class="input-group has-validation">
                    <input type="text"
                           style="width: 30%;@if($errors->has('briefly'))border:1px solid red;@endif"
                           class="form-control"
                           placeholder="@if($errors->has('briefly')){{ $errors->first('briefly') }}@else Briefly @endif"
                           id="briefly"
                           name="briefly"
                           @if(request()->routeIs('bot_messages.edit'))
                           value="{{ $message->briefly }}"
                           readonly
                        @endif>

                    <input type="text"
                           class="form-control inline"
                           style="width: 50%;@if($errors->has('message'))border:1px solid red;@endif"
                           placeholder="@if($errors->has('message')){{ $errors->first('message') }}@else Message @endif"
                           id="message"
                           name="message"
                           @if(request()->routeIs('bot_messages.edit'))
                           value="{{ $message->message }}"
                        @endif>

                    <input type="submit"
                           form="update"
                           title="Click to update"
                           class="btn btn-primary"
                           @if(request()->routeIs('bot_messages.index'))
                           value="Add"
                           @elseif(request()->routeIs('bot_messages.edit'))
                           value="Edit"
                           onclick="return confirm('Are you sure you want to update this item?')"
                           @endif>

                    @if(request()->routeIs('bot_messages.edit'))
                        <input type="submit"
                               name="_method"
                               title="Click to delete"
                               value="Delete"
                               onclick="return confirm('Are you sure you want to delete this item?')"
                               class="btn btn-primary">
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
