 <div class="clearfix">
    <div class="hint-text">Showing <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b> out of <b>{{ $paginator->total() }}</b> entries</div>
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            {{-- <li class="page-item disabled" aria-disabled="true"><a href="#"><i class="fa fa-angle-double-left"></i></a></li> --}}
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="fa fa-angle-double-left"></i></a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><a href="#" class="page-link">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
        @else
            {{-- <li class="page-item disabled" aria-disabled="true"><a href="#"><i class="fa fa-angle-double-right"></i></a></li> --}}
        @endif
        {{-- <li class="page-item"><a href="#" class="page-link"><i class="fa fa-angle-double-right"></i></a></li> --}}
    </ul>
</div>
