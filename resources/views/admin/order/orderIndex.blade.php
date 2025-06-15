@extends('admin.layoutAdmin')

@section('title', 'CRUD - Home Order')

@section('content')

    <div class="pt-16">
        <div class="w-full p-4 min-h-screen">

            <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Order List</h1>

            <form action="{{ route('order.index') }}" method="GET" class="max-w-md mx-auto mb-4">
                <label for="search" class="mb-2 text-sm font-medium text-primary sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" name="search" id="search"
                        class="block w-full p-4 pl-10 text-sm text-primary border border-gray-300 rounded-lg bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                        placeholder="Search by title..." value="{{ request('search') }}">
                    <button type="submit"
                        class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 absolute right-2.5 bottom-2.5">
                        Search
                    </button>
                </div>
            </form>


            <div class="relative overflow-x-auto overflow-y-auto max-h-[80vh]">
                <table class="w-full text-sm text-left text-gray-500 bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-primary">No</th>
                            <th scope="col" class="px-6 py-3 text-primary">Room</th>
                            <th scope="col" class="px-6 py-3 text-primary">User Name</th>
                            <th scope="col" class="px-6 py-3 text-primary">Order Details</th>
                            <th scope="col" class="px-6 py-3 text-primary">Total Price</th>
                            <th scope="col" class="px-6 py-3 text-primary">Status Order</th>
                            <th scope="col" class="px-6 py-3 text-primary">Confirm</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="bg-white border-b hover:bg-gray-100">
                                <td class="px-6 py-4 text-primary">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-[var(--primary)] whitespace-nowrap">
                                    @php
                                        $schedules =
                                            $order->reservation->status_reservation == 0
                                                ? $order->reservation->schedule->first()
                                                : null;
                                    @endphp
                                    {{ $schedules->cwspace->code_cwspace }}
                                </td>
                                <td class="px-6 py-4 text-[var(--primary)]">{{ $order->reservation->user->name }}</td>
                                <td class="px-6 py-4 text-[var(--primary)]">
                                    @foreach ($order->orderdetails as $detail)
                                        • {{ $detail->quantity }} {{ $detail->beverage->name }} x
                                        {{ $detail->quantity }} =
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }} <br>
                                    @endforeach
                                </td>
                                <td
                                    class="px-6 py-4 max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap text-[var(--primary)]">
                                    Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="{{ $order->status_order ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                        {{ $order->status_order ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                </td>
                                <td class="px-6 py-4">
                                    @if (!$order->status_order)
                                        <form action="{{ route('order.confirm', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="bg-green-600 text-white hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                                Confirm Payment
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled
                                            class="bg-gray-400 text-white font-medium rounded-lg text-sm px-5 py-2.5 cursor-not-allowed">
                                            Confirmed
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        <script>
            // Prevent showing old alerts when pressing back
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && performance.navigation.type === 2)) {
                    window.location.reload();
                }
            });
        </script>

    </div>

@endsection