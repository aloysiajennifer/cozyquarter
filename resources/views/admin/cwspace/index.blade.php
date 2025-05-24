<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CO-WORKING SPACE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

</head>

<body>
    <div>
        <p>CO-WORKING SPACE LIST</p>

        {{-- Success Modal --}}
        @if (session('success'))
        <div id="sessionSuccessModal" tabindex="-1" aria-hidden="true"
            class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30">
            <div class="relative p-4 w-full max-w-md">
                <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                    <button type="button"
                        class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5"
                        onclick="document.getElementById('sessionSuccessModal').classList.add('hidden')">
                        ✕
                    </button>
                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                        <svg aria-hidden="true" class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ session('success') }}
                    </p>
                    <button onclick="document.getElementById('sessionSuccessModal').classList.add('hidden')"
                        class="py-2 px-3 text-sm font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">
                        Continue
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Error Modal --}}
        @if (session('error'))
        <div id="sessionErrorModal" tabindex="-1" aria-hidden="true"
            class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30">
            <div class="relative p-4 w-full max-w-md">
                <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                    <button type="button"
                        class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5"
                        onclick="document.getElementById('sessionErrorModal').classList.add('hidden')">
                        ✕
                    </button>
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                        <svg aria-hidden="true" class="w-8 h-8 text-red-500 dark:text-red-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 002 0zm-1 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ session('error') }}
                    </p>
                    <button onclick="document.getElementById('sessionErrorModal').classList.add('hidden')"
                        class="py-2 px-3 text-sm font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
        @endif


        <!-- Button add cwspace -->
        <button data-modal-target="cwspace-create" data-modal-toggle="cwspace-create"
            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 
                focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 
                text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            + ADD CWSPACE
        </button>


        <!-- Table-->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Code
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Capacity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Edit
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Delete
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cwspaces as $cwspace)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">
                            {{ $cwspace -> code_cwspace}}
                        </td>
                        <td class="px-6 py-4">
                            {{ $cwspace -> capacity_cwspace}}
                        </td>
                        <td class="px-6 py-4">
                            {{ $cwspace -> status_cwspace == 0? "Available" : "Closed"}}
                        </td>

                        <!-- Edit -->
                        <td class="px-6 py-4">
                            <button data-modal-target="cwspace-edit-{{ $cwspace->id }}" data-modal-toggle="cwspace-edit-{{ $cwspace->id }}"
                                class="block text-white bg-yellow-300 hover:bg-yellow-500 focus:ring-4 
                                focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 
                                text-center dark:bg-yellow-300 dark:hover:bg-yellow-400 dark:focus:ring-yellow-500" type="button">
                                Edit
                            </button>
                        </td>

                        <!-- Delete -->
                        <td class="px-6 py-4">
                            <button
                                data-modal-target="cwspace-delete-{{ $cwspace->id }}"
                                data-modal-toggle="cwspace-delete-{{ $cwspace->id }}"
                                class="block text-white bg-red-500 hover:bg-red-800 focus:ring-4 
                                    focus:outline-none focus:ring-red-500 font-medium rounded-lg text-sm px-5 py-2.5 
                                    text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800" type="submit">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div id="cwspace-edit-{{$cwspace->id}}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-lg max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-600 dark:text-white">
                                        Edit Co-Working Space
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-hide="cwspace-edit-{{ $cwspace->id }}">
                                        ✕
                                    </button>
                                </div>

                                <div class="p-4">
                                    <form action="{{ route('cwspace.update', $cwspace->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label for="code_cwspace" class="block text-sm font-medium text-gray-900 dark:text-white">Code</label>
                                            <input type="text" name="code_cwspace" required value="{{ $cwspace->code_cwspace }}"
                                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                                        </div>

                                        <div>
                                            <label for="capacity_cwspace" class="block text-sm font-medium text-gray-900 dark:text-white">Capacity</label>
                                            <input type="number" name="capacity_cwspace" min="3" required value="{{ $cwspace->capacity_cwspace }}"
                                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                                        </div>

                                        <div>
                                            <label for="status_cwspace" class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                            <select name="status_cwspace"
                                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                                                <option value="0" {{ $cwspace->status_cwspace == 0 ? 'selected' : '' }}>Available</option>
                                                <option value="1" {{ $cwspace->status_cwspace == 1 ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>

                                        <div class="flex justify-end pt-3 border-t">
                                            <button type="submit"
                                                class="bg-blue-700 text-white rounded-lg px-4 py-2 hover:bg-blue-800">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Delete -->
                    <div id="cwspace-delete-{{ $cwspace->id }}" tabindex="-1" class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-md max-h-full ">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent 
                                        hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex 
                                        justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="cwspace-delete-{{ $cwspace->id }}">
                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this data?
                                    </h3>

                                    <!-- Delete Form -->
                                    <form action="{{ route('cwspace.delete', $cwspace->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none 
                                                focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex 
                                                items-center px-5 py-2.5 text-center">
                                            Yes, I'm sure
                                        </button>
                                    </form>

                                    <button type="button"
                                        data-modal-hide="cwspace-delete-{{ $cwspace->id }}"
                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white 
                                            rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 
                                            focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 
                                            dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        No, cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>


    <!-- Modal Add -->
    <div id="cwspace-create" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-600 dark:text-white">
                        Add Co-Working Space
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="cwspace-create">
                        ✕
                    </button>
                </div>

                <div class="p-4">
                    <form action="{{ route('cwspace.insert') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="code_cwspace"
                                class="block text-sm font-medium text-gray-900 dark:text-white">Code</label>
                            <input type="text" name="code_cwspace" required
                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                        </div>

                        <div>
                            <label for="capacity_cwspace"
                                class="block text-sm font-medium text-gray-900 dark:text-white">Capacity</label>
                            <input type="number" name="capacity_cwspace" min="3" required
                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                        </div>

                        <div>
                            <label for="status_cwspace"
                                class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select name="status_cwspace"
                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                                <option value="0">Available</option>
                                <option value="1">Closed</option>
                            </select>
                        </div>

                        <div class="flex justify-end pt-3 border-t">
                            <button type="submit"
                                class="bg-blue-700 text-white rounded-lg px-4 py-2 hover:bg-blue-800">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





</body>

</html>