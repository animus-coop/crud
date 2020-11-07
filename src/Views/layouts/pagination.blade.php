@if ($paginator->hasPages())
    <nav class="d-inline-block">
        <ul class="pagination mb-0">

            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                </li>
            @endif



            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif



                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><a class="page-link" href="#">1 <span
                                            class="sr-only">{{ $page }}</span></a></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{$url}}">{{$page}}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach



            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                </li>
            @else

                <li class="page-item disabled">
                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                </li>
            @endif
        </ul>
    </nav>
@endif