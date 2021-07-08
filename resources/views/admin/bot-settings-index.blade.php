@extends('admin.layouts.bot-settings')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($message = Session::has('status'))
                <div class="alert alert-info">
                    <span>{{ $message }}</span>
                </div>
                <br>
                <br>
            @endif
            <div class="container">
                <form action=" {{ route('bot_settings.store') }}" method="post">
                    @csrf
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Url callback for TelegramBot</label>
                        <br>
                        <br>
                        <div class="input-group">
                            <a href="#"
                               onclick="document.getElementById('url_callback_bot').value = '{{ url('') }}'">
                                Copy URL
                            </a>
                        </div>
                        <br>
                        <input type="url" class="form-control" id="url_callback_bot" name="url_callback_bot"
                               value="{{ $url_callback_bot ?? '' }}" style="width: 40%">
                    </div>

                    <button class="btn btn-primary" type="submit">
                        Save
                    </button>
                </form>
                <br>
                <form action="{{ route('bot_settings.set_webhook') }}" id="set_webhook" method="post">
                    @csrf
                    <input type="hidden" name="url" value="{{ $url_callback_bot ?? '' }}">
                    <input type="submit" class="btn btn-primary" value="Send Webhook URL">
                </form>
                <br>
                <form action="{{ route('bot_settings.get_webhook_info') }}" id="get_webhook_info" method="post">
                    @csrf
                    <input type="submit" class="btn btn-primary" value="Get Webhook info">
                </form>
            </div>
        </div>
    </div>
@endsection


