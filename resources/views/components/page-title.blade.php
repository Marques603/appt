@props(['header', 'page' => '', 'pageUrl' => null])

<div class="mb-6 flex flex-col justify-between gap-y-1 sm:flex-row sm:gap-y-0">
    <h5>{{ $header }}</h5>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Início</a>
        </li>
        @if ($page && $pageUrl)
            <li class="breadcrumb-item">
                <a href="{{ $pageUrl }}">{{ $page }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active">{{ $header }}</li>
    </ol>
</div>
