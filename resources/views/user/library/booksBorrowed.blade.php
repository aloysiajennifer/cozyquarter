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
                
                <div class="flex flex-col justify-between text-xs md:text-sm lg:text-md text-white w-full">
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

                    <p class="mb-1 max-w-full" title="{{ $brw->fine_realtime ? 'Rp' . number_format($brw->fine_realtime, 0, ',', '.') : (($brw->fine && $brw->fine->fine_total) ? 'Rp' . number_format($brw->fine->fine_total, 0, ',', '.') : '-') }}">
                        <span class="font-semibold">Fine: </span>
                            @if ($brw->fine_realtime)
                                Rp{{ number_format($brw->fine_realtime, 0, ',', '.') }}
                            @elseif ($brw->fine && $brw->fine->fine_total)
                                Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }}
                            @else
                                -
                            @endif
                    </p>
                    
                    
                    {{-- STATUS --}}
                        <div class="flex flex-wrap gap-2 items-start justify-end mt-2 mb-1">
                            @if (!$brw->status_returned)
                                <span class="text-xs md:text-sm font-bold bg-[#FDB813] bg-opacity-80 text-white px-2 py-1 rounded">UNRETURNED</span>

                                 @php
                                    $returnDue = \Carbon\Carbon::parse($brw->return_due)->startOfDay();
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                @endphp

                                @if ($today->gt($returnDue))
                                    <span class="text-xs md:text-sm font-bold bg-[#FF4E50] bg-opacity-80 text-white px-2 py-1 rounded">UNPAID</span>
                                @endif
                                                    
                            @elseif (isset($brw->fine) && !$brw->fine->status_fine)
                                <span class="text-sm font-bold bg-[#FF4E50] bg-opacity-80 text-white px-2 py-1 rounded">UNPAID</span>
                            @endif
                        </div>
                </div>
            </div>
            @endforeach


    </div>
</div>

@endsection