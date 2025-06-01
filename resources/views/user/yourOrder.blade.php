{{-- @extends('layout')

@section('content')
    <h1 class="text-3xl text-center font-bold mt-5">Your Order</h1>
    <div id="order-list" class="p-5 max-w-3xl mx-auto space-y-4">
        <!-- List will be inserted here -->
    </div>

    <script>
        // Contoh: array beverages bisa dikirim dari backend agar punya info name, price, dsb
        const beverages = @json($beverages);

        const order = JSON.parse(localStorage.getItem('order')) || {};

        const orderList = document.getElementById('order-list');

        let totalPrice = 0;

        for (const [bevId, qty] of Object.entries(order)) {
            if (qty > 0) {
                const bev = beverages.find(b => b.id == bevId);
                if (!bev) continue;

                const subtotal = bev.price * qty;
                totalPrice += subtotal;

                const div = document.createElement('div');
                div.classList.add('flex', 'justify-between', 'bg-gray-100', 'p-4', 'rounded');

                div.innerHTML = `
                    <div>
                        <h3 class="font-semibold">${bev.name}</h3>
                        <p>Quantity: ${qty}</p>
                        <p>Price: Rp${bev.price.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="text-right font-semibold">
                        Rp${subtotal.toLocaleString('id-ID')}
                    </div>
                `;

                orderList.appendChild(div);
            }
        }

        // Total price
        const totalDiv = document.createElement('div');
        totalDiv.classList.add('flex', 'justify-between', 'bg-green-200', 'p-4', 'rounded', 'font-bold');
        totalDiv.innerHTML = `
            <span>Total Price</span>
            <span>Rp${totalPrice.toLocaleString('id-ID')}</span>
        `;
        orderList.appendChild(totalDiv);
    </script>
@endsection --}}
