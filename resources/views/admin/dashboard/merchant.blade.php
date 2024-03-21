@extends('admin.layouts.master')

@section('page-style')
  @include('plugins.ionic')
@endsection



@section('page-script')
  @include('plugins.chart')

  {!! $chart->script() !!}
@endsection
