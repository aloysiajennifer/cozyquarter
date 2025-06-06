@extends('layout')

@section('content')
    <h1 class="text-3xl text-[var(--text-primary)] font-bold text-center mt-5 p-3">Beverages Menu</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 p-5">
        @foreach ($beverages as $b)
            <div
                class="beverage-card w-full max-w-xs mx-auto overflow-hidden hover:text-[var(--primary)] text-[var(--background-light)] bg-[var(--accent-blue)] rounded-lg hover:bg-[var(--highlight)] shadow-sm dark:bg-gray-800 dark:border-gray-700"
                data-beverage-id="{{ $b->id }}" data-stock="{{ $b->stock }}"
                data-beverage-name="{{ $b->name }}" data-beverage-price="{{ $b->price }}"
                data-beverage-image="{{ $b->image }}">
                <img class="p-3 rounded-t-lg object-cover w-full" src="{{ $b->image }}" alt="product image" />
                <div class="px-3 py-4">
                    <h5 class="text-xl text-center font-semibold tracking-tight dark:text-white truncate">{{ $b->name }}</h5>
                    <h5 class="text-md text-center font-semibold tracking-tight dark:text-white mt-1">
                        Rp{{ number_format($b->price, 0, ',', '.') }}</h5>
                    <p class="text-center text-sm mt-2">Stock: <span class="current-stock">{{ $b->stock }}</span></p>
                    <div class="flex items-center justify-center space-x-2 mt-3">
                        <button
                            class="decreaseBtn px-2 py-0.5 rounded bg-[var(--accent-green)] hover:bg-[var(--accent-blue)] text-lg font-bold">-</button>
                        <span class="quantity-input px-3 py-0.5 text-lg font-medium border border-gray-500 rounded">0</span>
                        <button
                            class="increaseBtn px-2 py-0.5 rounded bg-[var(--accent-green)] hover:bg-[var(--accent-blue)] text-lg font-bold">+</button>
                    </div>
                    <div class="flex justify-center mt-4">
                        <button
                            class="add-to-cart-btn w-full px-4 py-2 text-center text-white bg-[var(--primary)] rounded-lg hover:bg-[var(--secondary)] focus:ring-4 focus:outline-none focus:ring-blue-300 text-sm">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="m-3 p-3">
        <a href="{{ route('yourOrder') }}">
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
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll(".beverage-card").forEach(card => {
                const beverageId = card.dataset.beverageId;
                const stock = parseInt(card.dataset.stock);
                const beverageName = card.dataset.beverageName;
                const beveragePrice = parseFloat(card.dataset.beveragePrice);
                const beverageImage = card.dataset.beverageImage;

                const decreaseBtn = card.querySelector(".decreaseBtn");
                const increaseBtn = card.querySelector(".increaseBtn");
                const quantityInput = card.querySelector(".quantity-input");
                const addToCartBtn = card.querySelector(".add-to-cart-btn");

                let currentLocalQuantity = 0;
                quantityInput.textContent = currentLocalQuantity;

                let order = JSON.parse(localStorage.getItem("order")) || {};
                if (order[beverageId]) {
                    quantityInput.textContent = order[beverageId].quantity;
                    currentLocalQuantity = order[beverageId].quantity;
                } else {
                    quantityInput.textContent = 0;
                    currentLocalQuantity = 0;
                }

                increaseBtn.addEventListener("click", () => {
                    if (currentLocalQuantity < stock) {
                        currentLocalQuantity++;
                        quantityInput.textContent = currentLocalQuantity;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok Habis!',
                            text: 'Jumlah pesanan melebihi stok yang tersedia!',
                            confirmButtonColor: 'var(--primary)'
                        });
                    }
                });

                decreaseBtn.addEventListener("click", () => {
                    if (currentLocalQuantity > 0) {
                        currentLocalQuantity--;
                        quantityInput.textContent = currentLocalQuantity;
                    }
                });

                addToCartBtn.addEventListener("click", () => {
                    const quantityToAdd = parseInt(quantityInput.textContent);

                    if (quantityToAdd === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pilih Jumlah!',
                            text: 'Silakan pilih jumlah lebih dari 0 untuk ditambahkan ke keranjang.',
                            confirmButtonColor: 'var(--primary)'
                        });
                        return;
                    }

                    let order = JSON.parse(localStorage.getItem("order")) || {};

                    let existingCartQuantity = order[beverageId] ? order[beverageId].quantity : 0;
                    let newTotalQuantity = existingCartQuantity + quantityToAdd;

                    if (newTotalQuantity <= stock) {
                        order[beverageId] = {
                            id: beverageId,
                            name: beverageName,
                            price: beveragePrice,
                            image: beverageImage,
                            stock: stock,
                            quantity: newTotalQuantity
                        };
                        localStorage.setItem("order", JSON.stringify(order));
                        Swal.fire({
                            icon: 'success',
                            title: 'Menu Berhasil Ditambahkan!',
                            text: `${quantityToAdd} ${beverageName} berhasil ditambahkan ke keranjang! Total di keranjang: ${newTotalQuantity}`,
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });

                        currentLocalQuantity = 0;
                        quantityInput.textContent = currentLocalQuantity;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menambahkan!',
                            text: `Tidak dapat menambahkan ${quantityToAdd} pesanan lagi. Total pesanan untuk ${beverageName} akan melebihi stok (${stock}).`,
                            confirmButtonColor: 'var(--primary)'
                        });
                    }
                });
            });
        });
    </script>
@endsection