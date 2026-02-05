<?php
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/** @var LengthAwarePaginator $paginator */
?>

@if ($paginator->hasPages())
<nav class="pagination my-large">

    {{-- First Page --}}
    <a href="{{ $paginator->url(1) }}"
       class="pagination-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" style="width:18px">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
        </svg>
    </a>

    {{-- Previous Page --}}
    <a href="{{ $paginator->previousPageUrl() }}"
       class="pagination-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" style="width:18px">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </a>

    {{-- Page Numbers --}}
    @foreach ($paginator->getUrlRange(
        max(1, $paginator->currentPage() - 2),
        min($paginator->lastPage(), $paginator->currentPage() + 2)
    ) as $page => $url)

        @if ($page === $paginator->currentPage())
            <span class="pagination-item active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
        @endif

    @endforeach

    {{-- Next Page --}}
    <a href="{{ $paginator->nextPageUrl() }}"
       class="pagination-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" style="width:18px">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </a>

    {{-- Last Page --}}
    <a href="{{ $paginator->url($paginator->lastPage()) }}"
       class="pagination-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" style="width:18px">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
        </svg>
    </a>

</nav>
@endif
