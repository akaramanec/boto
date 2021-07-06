@extends('bot-message-edit')

@section('sub-content')
    @if($messages != null)

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="width: 100%;">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" >#</th>
                            <th scope="col" style="text-align: left; padding-left: 10px">Briefly</th>
                            <th scope="col" style="text-align: left; padding-left: 10px">Message</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr style="border: 1px solid black">
                                <th scope="row" style="width: 10%; border: 1px solid black"> {{ $message->id }}</th>
                                <td style="width: 30%; border: 1px solid black; padding-left: 10px">
                                    <a href="{{ route('bot_messages.edit', $message->id) }}" title="Click to edit">
                                        {{ $message->briefly }}
                                    </a>
                                </td>
                                <td style="border: 1px solid black; padding-left: 10px">
                                    <a href="{{ route('bot_messages.edit', $message->id) }}" title="Click to edit">
                                        {{ $message->message }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
