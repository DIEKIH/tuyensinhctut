@extends('users.layouts.app')

@section('content')
    @switch($loaimanhinh)
        @case(1)
            @include('users.layouts.hienthi1')
        @break

        @case(2)
            @include('users.layouts.hienthi2')
        @break

        @default
    @endswitch

    {{-- {!! $menu->content !!} --}}
@endsection
