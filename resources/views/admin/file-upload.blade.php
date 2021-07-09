<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Imports
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('file_import') }}" method="post" enctype="multipart/form-data">
                <h3 class="mb-5">Upload Products to shop store</h3>
                @csrf
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="custom-file">
                    <label class="custom-file-label" for="chooseFile">Select file</label>
                    <br>
                    <br>
                    <input type="file" name="file" class="custom-file-input" id="chooseFile">
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                    Upload Files
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
