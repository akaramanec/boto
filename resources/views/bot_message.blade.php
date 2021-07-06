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
                <td style="width: 30%; border: 1px solid black; padding-left: 10px">{{ $message->briefly }}</td>
                <td style="border: 1px solid black; padding-left: 10px">{{ $message->message }}</td>
            </tr>
        @endforeach
        </tbody>
        <tr>
            <td></td>
        </tr>
    </table>
</div>


