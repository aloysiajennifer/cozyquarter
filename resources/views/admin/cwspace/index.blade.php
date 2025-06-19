@extends('admin.layoutAdmin')

@section('title', 'Co-working Space Management')

@php
   
    $cwspaceStatusLabels = [
        1 => 'Open',
        0 => 'Closed',
    ];
    $cwspaceStatusBgColors = [
        1 => 'bg-green-600',
        0 => 'bg-red-600',
        'default' => 'bg-gray-500'
    ];
@endphp

@section('content')
<div class="p-4 sm:p-6 mt-14 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6 dark:text-white">Co-working Space Management</h1>

        <div class="mb-6 flex justify-start">
            <button data-modal-target="cwspace-create" data-modal-toggle="cwspace-create"
                class="inline-flex items-center text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md transition-colors" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Space
            </button>
        </div>

        <div class="bg-white relative overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Capacity</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cwspaces as $cwspace)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $cwspace->code_cwspace }}</td>
                        <td class="px-6 py-4">{{ $cwspace->capacity_cwspace }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <span class="w-20 inline-flex items-center justify-center py-1 px-2 text-xs font-semibold text-white rounded-full {{ $cwspaceStatusBgColors[$cwspace->status_cwspace] ?? $cwspaceStatusBgColors['default'] }}">
                                    {{ $cwspaceStatusLabels[$cwspace->status_cwspace] ?? 'Unknown' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-3">
                                <button data-modal-target="cwspace-edit-{{ $cwspace->id }}" data-modal-toggle="cwspace-edit-{{ $cwspace->id }}"
                                    class="bg-[var(--highlight)] hover:bg-yellow-600 text-white font-semibold text-xs py-1.5 px-3 rounded-lg shadow-sm transition-colors" type="button">
                                    Edit
                                </button>
                                <button data-modal-target="cwspace-delete-{{ $cwspace->id }}" data-modal-toggle="cwspace-delete-{{ $cwspace->id }}"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold text-xs py-1.5 px-3 rounded-lg shadow-sm transition-colors" type="button">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No co-working space data found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="cwspace-create" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Add New Co-working Space</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="cwspace-create">✕</button>
            </div>
            <form action="{{ route('cwspace.insert') }}" method="POST" class="p-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="code_cwspace_create" class="block mb-2 text-sm font-medium text-gray-900">Code</label>
                        <input type="text" name="code_cwspace" id="code_cwspace_create" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                    </div>
                    <div>
                        <label for="capacity_cwspace_create" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                        <input type="number" name="capacity_cwspace" id="capacity_cwspace_create" min="1" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                    </div>
                    <div>
                        <label for="status_cwspace_create" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                        <select name="status_cwspace" id="status_cwspace_create" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="1" selected>Open</option>
                            <option value="0">Closed</option>
                        </select>
                    </div>
                    <div class="flex justify-end pt-3 mt-4 border-t">
                        <button type="submit" class="bg-teal-600 text-white rounded-lg px-5 py-2.5 hover:bg-teal-700 font-medium text-sm text-center transition">Add Space</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($cwspaces as $cwspace)
    <!-- Modal edit -->
    <div id="cwspace-edit-{{$cwspace->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Co-working Space</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="cwspace-edit-{{ $cwspace->id }}">✕</button>
                </div>
                <form action="{{ route('cwspace.update', $cwspace->id) }}" method="POST" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="code_cwspace_edit_{{ $cwspace->id }}" class="block mb-2 text-sm font-medium text-gray-900">Code</label>
                            <input type="text" name="code_cwspace" id="code_cwspace_edit_{{ $cwspace->id }}" value="{{ $cwspace->code_cwspace }}" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                        <div>
                            <label for="capacity_cwspace_edit_{{ $cwspace->id }}" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                            <input type="number" name="capacity_cwspace" id="capacity_cwspace_edit_{{ $cwspace->id }}" min="1" value="{{ $cwspace->capacity_cwspace }}" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                        <div>
                            <label for="status_cwspace_edit_{{ $cwspace->id }}" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                            <select name="status_cwspace" id="status_cwspace_edit_{{ $cwspace->id }}" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="1" {{ $cwspace->status_cwspace == 1 ? 'selected' : '' }}>Open</option>
                                <option value="0" {{ $cwspace->status_cwspace == 0 ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="flex justify-end pt-3 mt-4 border-t">
                            <button type="submit" class="bg-teal-600 text-white rounded-lg px-5 py-2.5 hover:bg-teal-700 font-medium text-sm text-center transition">Update Space</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete -->
    <div id="cwspace-delete-{{ $cwspace->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="cwspace-delete-{{ $cwspace->id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" /></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this space?</h3>
                    <form action="{{ route('cwspace.delete', $cwspace->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                    </form>
                    <button type="button" data-modal-hide="cwspace-delete-{{ $cwspace->id }}" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session("success") }}',
    confirmButtonColor: '#14b8a6' // Teal
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: '{!! session("error") !!}',
    confirmButtonColor: '#ef4444' // Red
});
</script>
@endif

@if($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Input Tidak Valid!',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#ef4444' // Red
});
</script>
@endif
@endsection