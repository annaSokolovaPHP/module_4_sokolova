<?php
// config
//$link_limit = 3; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <ul class="pagination pagination_articles">
        <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a class="w3-left w3-btn" href=
            "{{ ($paginator->currentPage() == 1) ? $paginator->url (1) : $paginator->url($paginator->currentPage()-1) }}">
                ❮ Previous</a>
        </li>
        <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url(1) }}">First</a>
        </li>


        <li class="{{$paginator->currentPage() == 1 ? 'active' : ''}}">
            <a href="{{ $paginator->url(1) }}">{{ 1 }}</a>
        </li>
        @if($paginator->currentPage() != 1)
            @if($paginator->currentPage() != 2)
                <li>
                    <a  id = "addLinkBefore">...</a>
                </li>

            @endif


            <li class="active">
                <a href="{{ $paginator->url($paginator->currentPage()) }}">{{ $paginator->currentPage() }}</a>
            </li>
        @endif
        @if($paginator->currentPage() != $paginator->lastPage())
            @if($paginator->currentPage() != $paginator->lastPage() -1)
                <li>
                    <a id = "addLinkAfter">...</a>
                </li>
            @endif
            <li>
                <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            </li>
        @endif

        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}">Last</a>
        </li>
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a class="w3-right w3-btn" href=
            "{{ ($paginator->currentPage() != $paginator->lastPage()) ? $paginator->url($paginator->currentPage()+1) : $paginator->url($paginator->lastPage())}}">
                Next ❯</a>
        </li>
    </ul>
@endif

<script>
    $( document ).ready(function() {
        var countBefore = 2;
        var countAfter = 2;
        var currentPage = {{$paginator->currentPage()}};
        var lastPage = {{$paginator->lastPage()}};
        var pathname = window.location.pathname;
        $( "#addLinkBefore" ).click(function() {
            var i = 1;
            while(i < 5 ){
                $(this).parent().prev().after( "<li><a href = '"+window.location.pathname+"?page="+countBefore+"'>"+countBefore+"</a></li>");
                countBefore = countBefore + 1;
                if(countBefore == currentPage){
                    $( "#addLinkBefore" ).hide();
                    break;
                }
                i++;
            }
        });

        $( "#addLinkAfter" ).click(function() {
            var i = 1;
            while(i < 5){
                $(this).parent().prev().after( "<li><a href = '"+window.location.pathname+"?page="+countAfter+"'>"+countAfter+"</a></li>");
                countAfter = countAfter + 1;
                if(countAfter == lastPage){
                    $( "#addLinkAfter" ).hide();
                    break;
                }
                i++;
            }
        });
    });


</script>