<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Imports
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <br>
            @if ($message = Session::get('status'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div><br>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br>
            @endif
            <h2 class="mb-5" style="font-size: 14pt; font-weight: bold;">From file</h2>
            <form action="{{ route('file_import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="custom-file">
                    <label class="custom-file-label" for="chooseFile">Select file</label>
                    <br>
                    <br>
                    <input type="file" name="file" class="custom-file-input" id="chooseFile">
                </div>

                <button type="submit"
                        name="submit"
                        class="btn btn-primary btn-block mt-4"
                        style=" background-color: #4CAF50; /* Green */
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        border-radius: 4px;">
                    Upload from file
                </button>
            </form>
            <br>
            <h2 class="mb-5" style="font-size: 14pt; font-weight: bold;">From Google Sheet</h2>
            <form action="{{ route('google_sheet_import') }}" method="get" enctype="multipart/form-data">
                @csrf
                <button type="submit"
                        name="submit"
                        class="btn btn-primary btn-block mt-4"
                        style=" background-color: #4CAF50; /* Green */
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        border-radius: 4px;">
                    Upload from GoogleSheet
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
