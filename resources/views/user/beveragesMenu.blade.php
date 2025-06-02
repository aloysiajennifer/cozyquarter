@extends('layout')

@section('content')
    <h1 class="text-3xl text-[var(--text-primary)] font-bold text-center mt-5 p-3">Beverages Menu</h1>
    <div class="grid grid-cols-3 gap-4 p-5">
        @foreach ($beverages as $b)
            <div
                class="beverage-card w-full max-w-sm hover:text-[var(--primary)] text-[var(--background-light)] bg-[var(--accent-blue)] rounded-lg hover:bg-[var(--highlight)] shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <img class="p-3 rounded-t-lg" src="{{ $b->image }}" alt="product image" />
                <div class="px-5 pb-5">
                    <h5 class="text-2xl text-center font-semibold tracking-tight dark:text-white">{{ $b->name }}</h5>
                    <h5 class="text-l text-center tracking-tight dark:text-white">
                        Rp{{ number_format($b->price, 0, ',', '.') }}</h5>
                    <div class="flex items-center justify-center space-x-2 mt-3">
                        <button
                            class="decreaseBtn px-3 py-1 rounded bg-[var(--accent-green)] hover:bg-[var(--accent-blue)] text-lg font-bold">-</button>
                        <span class="quantity px-4 py-1 text-lg font-medium">0</span>
                        <button
                            class="increaseBtn px-3 py-1 rounded bg-[var(--accent-green)] hover:bg-[var(--accent-blue)] text-lg font-bold">+</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="m-3 p-3">
        <a href="{{route('yourOrder')}}">
            <div
                class="flex items-center text-black hover:text-white hover:bg-[var(--primary)] justify-between bg-[var(--secondary)] rounded p-6 hover:shadow-lg transition-shadow duration-300">
                <h5 class="text-2xl font-semibold tracking-tight dark:text-white">YOUR ORDER</h5>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <script>
        const decreaseBtn = document.querySelectorAll("#decreaseBtn");
        const increaseBtn = document.querySelectorAll("#increaseBtn");
        const quantity = document.querySelectorAll("#quantity");

        document.querySelectorAll(".beverage-card").array.forEach(card => {
            const beverageId = card.dataset.beverageId;
            const decreaseBtn = card.querySelector(".decreaseBtn");
            const increaseBtn = card.querySelector(".increaseBtn");
            const quantitySpan = card.querySelector(".quantity");

            let order = JSON.parse(localStorage.getItem("order")) || {};
            quantitySpan.textContent = order[beverageId] || 0;

            increaseBtn.addEventListener("click", () => {
                let qty = order[beverageId] || 0;
                qty++;
                order[beverageId] = qty;
                localStorage.setItem("order", JSON.stringify(order));
                quantitySpan.textContent = qty;
            });

            decreaseBtn.addEventListener("click", () => {
                let qty = order[beverageId] || 0;
                qty--;
                if (qty < 0) qty = 0;
                order[beverageId] = qty;
                localStorage.setItem("order", JSON.stringify(order));
                quantitySpan.textContent = qty;
            });
        });
    </script>
@endsection
