{{-- @can('view', $offering) --}}
  {{-- <a href="javascript:void(0)" data-link="{{ route('admin.admin.offering.show', $offering->id) }}" class="ajax-modal-btn modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.profile') }}" class="fa fa-user-circle-o"></i></a>&nbsp; --}}
{{-- @endcan --}}

{{-- @can('update', $offering) --}}
@if(Auth::user()->isAdmin() || Auth::user()->isMerchant())
  <a href="javascript:void(0)" data-link="{{ route('admin.admin.offering.edit', $offering->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
@endif
{{-- @endcan --}}

{{-- @can('delete', $offering) --}}
@if(Auth::user()->isAdmin() || Auth::user()->isMerchant())
  {!! Form::open(['route' => ['admin.admin.offering.trash', $offering->id], 'method' => 'delete', 'class' => 'data-form']) !!}
  {!! Form::button('<i class="fa fa-trash-o text-info"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
  {!! Form::close() !!}
@endif
{{-- @endcan --}}
