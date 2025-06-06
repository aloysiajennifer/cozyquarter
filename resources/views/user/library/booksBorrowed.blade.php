@extends('layout')

@section('content')

<div class="pt-12 px-10">
    <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Books Borrowed</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($borrowings as $brw)
             <div class="relative flex bg-[var(--accent-blue)] rounded-xl shadow p-4">
                <div class="aspect-[2/3] w-32 h-48 object-cover rounded mr-4 flex-shrink-0">
                    <img src="{{ asset($brw->book->cover_book) }}" alt={{ $brw->book->title_book }} class="rounded-lg object-cover w-full h-full mx-auto mb-1">
                </div>
                
                <div class="flex flex-col justify-between text-sm lg:text-md text-white w-full">
                    <div>
                    <p class="mb-1 max-w-full" title="{{ $brw->book->title_book }}">
                       <span class="font-semibold">Title: </span>
                       {{ $brw->book->title_book }}
                    </p>
                    <p class="mb-1 max-w-full" title="{{ $brw->book->author_book }}">
                       <span class="font-semibold">Author: </span>
                       {{ $brw->book->author_book }}
                    </p>
                    <p class="mb-1 max-w-full" title="{{ $brw->borrowing_date }}">
                       <span class="font-semibold">Borrowed Date: </span>
                       {{ \Carbon\Carbon::parse($brw->borrowing_date)->format('d M Y') }}
                    </p>
                    <p class="mb-1 max-w-full" title="{{ $brw->return_due }}">
                       <span class="font-semibold">Return Before: </span>
                       {{ \Carbon\Carbon::parse($brw->return_due)->format('d M Y') }}
                    </p>
                    </div>

                    <p class="mb-1 max-w-full" title="{{ ($brw->fine && $brw->fine->fine_total) ? 'Rp' . number_format($brw->fine->fine_total, 0, ',', '.') : '-' }}">
                        <span class="font-semibold">Fine: </span>
                            @if ($brw->fine && $brw->fine->fine_total)
                                Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }}
                            @else
                                -
                            @endif

                    </p>
                    
                    
                    {{-- STATUS --}}
                        <div class="flex gap-2 items-end self-end mb-1">
                            @if (!$brw->status_returned)
                                <span class="text-sm font-bold bg-[#FDB813] bg-opacity-80 text-white px-2 py-1 rounded">UNRETURNED</span>

                                 @php
                                    $returnDue = \Carbon\Carbon::parse($brw->return_due)->startOfDay();
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                @endphp

                                @if ($today->gt($returnDue))
                                    <span class="text-sm font-bold bg-[#FF4E50] bg-opacity-80 text-white px-2 py-1 rounded">UNPAID</span>
                                @endif
                                                    
                            @elseif (isset($brw->fine) && !$brw->fine->status_fine)
                                <span class="text-sm font-bold bg-[#FF4E50] bg-opacity-80 text-white px-2 py-1 rounded">UNPAID</span>
                            @endif
                        </div>
                </div>
            </div>
            @endforeach
        


            <div class="relative flex bg-[var(--accent-blue)] rounded-xl shadow p-4">
                <div class="aspect-[2/3] w-32 h-48 object-cover rounded mr-4 flex-shrink-0">
                    <img src="{{ asset('images/covers/harry potter 7.jpg') }}" alt="Harry Potter 7" class="rounded-lg object-cover w-full h-full mx-auto mb-1">
                </div>

                {{-- <div class="w-px bg-gray-300"></div> --}}
                
                <div class="flex flex-col justify-between text-sm lg:text-md text-white w-full">
                    <div>
                    <p class="mb-1 max-w-full" title="Harry Potter 7">
                       <span class="font-semibold">Title: </span>
                       Harry Potter 7
                    </p>
                    <p class="mb-1 max-w-full" title="author">
                       <span class="font-semibold">Author: </span>
                       By J.K. Rowling
                    </p>
                    <p class="mb-1 max-w-full" title="borrowed date">
                       <span class="font-semibold">Borrowed Date: </span>
                       2025-06-06
                    </p>
                    <p class="mb-1 max-w-full" title="return before">
                       <span class="font-semibold">Return Before: </span>
                       2025-06-10
                    </p>
                    </div>
                    <p class="mb-1 max-w-full" title="fine">
                       <span class="font-semibold">Fine: </span>
                       Rp1.000
                    </p>
                    {{-- STATUS --}}
                        <div class="flex flex-col items-end self-end">
                            <span class="font-bold text-[#87F1E0] px-2 mb-1">UNRETURNED</span>
                            <span class="font-bold text-[#87F1E0] px-2">UNPAID</span>
                        </div>
                </div>
            </div>

           



    </div>
</div>

@endsection