@extends('layout')

@section('content')
    <h1 class="text-3xl text-[var(--text-primary)] font-bold text-center mt-5 p-3">Your Order</h1>

    <div class="container mx-auto p-5">
        <div id="cart-items" class="bg-white shadow-md rounded-lg p-6">

        </div>

        <div id="cart-summary" class="mt-8 bg-white shadow-md rounded-lg p-6">
            <p id="empty-cart-message" class="text-center text-gray-500 text-lg font-medium py-2 hidden">
                Your cart is empty.
            </p>
            <div class="flex justify-between items-center border-t pt-4 mt-4">
                <h3 class="text-xl font-semibold">Total:</h3>
                <span id="grand-total" class="text-2xl font-bold text-[var(--primary)]">Rp0</span>
            </div>

            <div class="flex justify-end mt-6">
                <button id="order-now-btn"
                    class="px-8 py-3 bg-[var(--accent-green)] text-white font-semibold rounded-lg shadow-md hover:bg-[var(--primary)] transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Order Now
                </button>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('beverages.menu') }}"
                class="text-[var(--primary)] hover:text-[var(--highlight)] flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Menu
            </a>
        </div>

        <div class="mt-10 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl text-[var(--text-primary)] font-bold mb-4 text-center">Your Orders Today</h2>

            @if ($todayOrders->isEmpty())
                <p class="text-center text-gray-500 text-lg py-4">No orders placed today.</p>
            @else
                <div class="relative overflow-x-auto overflow-y-auto max-h-[60vh]">
                    <table
                        class="w-full text-sm text-left text-gray-500 bg-white border border-gray-200 rounded-lg shadow-md">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-primary">Order ID</th>
                                <th scope="col" class="px-6 py-3 text-primary">Time</th>
                                <th scope="col" class="px-6 py-3 text-primary">Room</th>
                                <th scope="col" class="px-6 py-3 text-primary">Items</th>
                                <th scope="col" class="px-6 py-3 text-primary">Total Price</th>
                                <th scope="col" class="px-6 py-3 text-primary">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayOrders as $order)
                                <tr class="bg-white border-b hover:bg-gray-100">
                                    <td class="px-6 py-4 text-primary">{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-primary">{{ $order->created_at->format('H:i') }}</td>
                                    <td class="px-6 py-4 text-primary">
                                        @php
                                            $schedule = $order->reservation->schedule->first();
                                            $roomCode = $schedule?->cwspace?->code_cwspace ?? 'N/A';
                                        @endphp
                                        {{ $roomCode }}
                                    </td>
                                    <td class="px-6 py-4 text-primary">
                                        @foreach ($order->orderdetails as $detail)
                                            • {{ $detail->beverage->name }} ({{ $detail->quantity }}x) -
                                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }} <br>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-primary">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="{{ $order->status_order ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                            {{ $order->status_order ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const allBeveragesData = @json($beverages->keyBy('id'));

        document.addEventListener('DOMContentLoaded', () => {
            const cartItemsContainer = document.getElementById('cart-items');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const grandTotalSpan = document.getElementById('grand-total');
            const orderNowBtn = document.getElementById('order-now-btn');

            let order = JSON.parse(localStorage.getItem("order")) || {};

            function renderCart() {
                cartItemsContainer.innerHTML = '';

                let totalAmount = 0;

                if (Object.keys(order).length === 0) {
                    emptyCartMessage.classList.remove('hidden');
                    orderNowBtn.disabled = true;
                    grandTotalSpan.textContent = 'Rp0';
                    return;
                } else {
                    emptyCartMessage.classList.add('hidden');
                    orderNowBtn.disabled = false;
                }

                Object.values(order).forEach(item => {
                    const subtotal = item.price * item.quantity;
                    totalAmount += subtotal;

                    const currentBeverageData = allBeveragesData[item.id];
                    if (!currentBeverageData) {
                        delete order[item.id];
                        localStorage.setItem("order", JSON.stringify(order));
                        renderCart();
                        Swal.fire({
                            icon: 'error',
                            title: 'Item Unavailable!',
                            text: `One or more items in your cart are no longer available and have been removed.`
                        });
                        return;
                    }

                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('flex', 'items-center', 'justify-between', 'py-4', 'border-b',
                        'last:border-b-0');
                    itemDiv.innerHTML = `
                        <div class="flex items-center space-x-4">
                            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md">
                            <div>
                                <h4 class="font-semibold text-lg">${item.name}</h4>
                                <p class="text-gray-600">Rp${(item.price).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button data-id="${item.id}" class="decrease-cart-qty px-2 py-1 rounded bg-gray-200 hover:bg-gray-300">-</button>
                            <span class="font-medium text-lg">${item.quantity}</span>
                            <button data-id="${item.id}" class="increase-cart-qty px-2 py-1 rounded bg-gray-200 hover:bg-gray-300">+</button>
                            <span class="font-semibold text-lg w-28 text-right">Rp${(subtotal).toLocaleString('id-ID')}</span>
                            <button data-id="${item.id}" class="remove-from-cart text-red-500 hover:text-red-700 ml-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemDiv);
                });

                grandTotalSpan.textContent = `Rp${totalAmount.toLocaleString('id-ID')}`;

                document.querySelectorAll('.decrease-cart-qty').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const id = event.target.dataset.id;
                        if (order[id] && order[id].quantity > 1) {
                            order[id].quantity--;
                            localStorage.setItem("order", JSON.stringify(order));
                            renderCart();
                        } else if (order[id] && order[id].quantity === 1) {
                            Swal.fire({
                                title: 'Remove Item?',
                                text: `Are you sure you want to remove ${order[id].name} from your cart?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, remove it!',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    delete order[id];
                                    localStorage.setItem("order", JSON.stringify(order));
                                    renderCart();
                                    Swal.fire(
                                        'Removed!',
                                        'Your item has been removed.',
                                        'success'
                                    );
                                }
                            });
                        }
                    });
                });

                document.querySelectorAll('.increase-cart-qty').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const id = event.target.dataset.id;
                        if (order[id]) {
                            const currentBeverageData = allBeveragesData[id];
                            if (!currentBeverageData) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Item Unavailable!',
                                    text: `This item (${order[id].name}) is no longer available.`
                                });
                                delete order[id];
                                localStorage.setItem("order", JSON.stringify(order));
                                renderCart();
                                return;
                            }
                            const availableStock = currentBeverageData.stock;

                            if (order[id].quantity < availableStock) {
                                order[id].quantity++;
                                localStorage.setItem("order", JSON.stringify(order));
                                renderCart();
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Out of Stock!',
                                    text: `Cannot add more. Max stock is ${availableStock} for ${order[id].name}.`
                                });
                            }
                        }
                    });
                });

                document.querySelectorAll('.remove-from-cart').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const id = event.target.dataset.id || event.target.closest('button').dataset
                            .id;
                        Swal.fire({
                            title: 'Remove Item?',
                            text: `Are you sure you want to remove ${order[id].name} from your cart?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, remove it!',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                delete order[id];
                                localStorage.setItem("order", JSON.stringify(order));
                                renderCart();
                                Swal.fire(
                                    'Removed!',
                                    'Your item has been removed.',
                                    'success'
                                );
                            }
                        });
                    });
                });
            }

            renderCart();

            orderNowBtn.addEventListener('click', async () => {
                if (Object.keys(order).length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cart is Empty',
                        text: 'Your cart is empty. Please add items before ordering.'
                    });
                    return;
                }

                const itemsToVerify = [];
                for (const itemId in order) {
                    const localQuantity = order[itemId].quantity;
                    const serverData = allBeveragesData[itemId];

                    if (!serverData || localQuantity > serverData.stock) {
                        itemsToVerify.push({
                            name: order[itemId].name,
                            requested: localQuantity,
                            available: serverData ? serverData.stock : 0
                        });
                    }
                }

                if (itemsToVerify.length > 0) {
                    let message =
                        "Some items in your cart are out of stock or exceed available quantity:<br>";
                    itemsToVerify.forEach(item => {
                        message +=
                            `<b>${item.name}</b>: Requested ${item.requested}, Available ${item.available}<br>`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock Issue!',
                        html: message + "<br>Please adjust your quantities and try again.",
                        allowOutsideClick: false,
                        showConfirmButton: true
                    });
                    renderCart();
                    return;
                }

                const orderItemsToSend = Object.values(order).map(item => ({
                    id: item.id,
                    quantity: item.quantity
                }));


                const confirmOrder = await Swal.fire({
                    title: 'Place Order?',
                    text: "Are you sure you want to place this order?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, place order!'
                });

                if (!confirmOrder.isConfirmed) {
                    return;
                }

                try {
                    const response = await fetch("{{ route('placeOrder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            order_items: orderItemsToSend
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Placed!',
                            text: data.message
                        });
                        localStorage.removeItem("order");
                        order = {};
                        renderCart();
                    } else {
                        if (response.status === 422 && data.errors) {
                            let errorMessages = '';
                            for (const key in data.errors) {
                                if (data.errors.hasOwnProperty(key)) {
                                    errorMessages += `<p>${data.errors[key].join('<br>')}</p>`;
                                }
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error!',
                                html: errorMessages ||
                                    'An unexpected validation error occurred.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Order Failed!',
                                text: data.message ||
                                    'An error occurred while placing your order. Please try again.'
                            });
                        }
                        renderCart();
                    }
                } catch (error) {
                    console.error("Error placing order:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while placing your order. Please try again.'
                    });
                }
            });
        });
    </script>
@endsection
