@if(session('success'))
    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
     <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-4 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
@endif