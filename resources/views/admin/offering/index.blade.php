@extends('admin.layouts.master')

@section('page-style')
    @include('plugins.ionic')
@endsection

@section('content')
    <div class="">
        <div class="box">
            <div class="box-tools pull-right p-2">
                @if (!Auth::user()->isAdmin() || !Auth::user()->isMerchant())
                    <a href="javascript:void(0)" data-link="{{ route('admin.offering.create') }}"
                        class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.form.create_offering') }}</a>
                @endif
            </div>
            <div style="padding-top: 50px;padding-left:10px;padding-right:10px;">
                <div style="overflow: auto">
                    <table class="table table-hover" id="offering-table" style="width: 1500px;">
                        <thead>
                            <tr>
                                <th class="massActionWrapper">
                                    <button type="button" class="btn btn-xs btn-default checkbox-toggle">
                                        <i class="fa fa-square-o" data-toggle="tooltip" data-placement="top"
                                            title="{{ trans('app.select_all') }}"></i>
                                    </button>
                                </th>
                                <th>{{ trans('app.form.product_name') }}</th>
                                <th>{{ trans('app.form.small_quantity') }}</th>
                                <th>{{ trans('app.form.medium_quantity') }}</th>
                                <th>{{ trans('app.form.large_quantity') }}</th>
                                @if (Auth::user()->isAdmin() || Auth::user()->isMerchant())
                                    <th>{{ trans('app.form.created_at') }}</th>
                                    <th>{{ trans('app.form.created_by') }}</th>
                                    <th>{{ trans('app.form.company_name') }}</th>
                                    <th>{{ trans('app.form.email') }}</th>
                                    <th>{{ trans('app.form.phone') }}</th>
                                    <th>{{ trans('app.form.updated_at') }}</th>
                                    <th>{{ trans('app.form.updated_by') }}</th>
                                @endif
                                <th>{{ trans('app.form.status') }}</th>
                                @if (Auth::user()->isAdmin() || Auth::user()->isMerchant())
                                    <th>{{ trans('app.form.option') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="massSelectArea">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
