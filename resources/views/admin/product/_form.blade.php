@php
  $title_classes = isset($product) ? 'form-control' : 'form-control makeSlug';
@endphp

@if (config('system_settings.can_use_own_catalog_only') &&
        auth()->user()->isFromPlatform())
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
        {!! trans('messages.vendor_can_use_own_catalog_only_notice') !!}
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ isset($product) ? trans('app.update_product') : trans('app.add_product') }}</h3>
          <div class="box-tools pull-right">
            @if (!isset($product))
              {{-- <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.upload') }}" class="ajax-modal-btn btn btn-default btn-flat">{{ trans('app.bulk_import') }}</a> --}}
            @endif
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-9 nopadding-right">
              <div class="form-group">
                {!! Form::label('name', trans('app.form.name') . '*', ['class' => 'with-help']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_name') }}"></i>
                {!! Form::text('name', null, ['class' => $title_classes, 'placeholder' => trans('app.placeholder.title'), 'required']) !!}
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="col-md-3 nopadding-left">
              <div class="form-group">
                {!! Form::label('active', trans('app.form.status') . '*', ['class' => 'with-help']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_active') }}"></i>
                {!! Form::select('active', ['1' => trans('app.active'), '0' => trans('app.inactive')], !isset($product) ? 1 : null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
                <div class="help-block with-errors"></div>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {!! Form::label('gtin', trans('app.form.item_number'), ['class' => 'with-help']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.item_number') }}"></i>
                {!! Form::text('gtin', null, ['class' => 'form-control', 'placeholder' => trans('app.form.item_number')]) !!}
              </div>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('description', trans('app.form.description') . '*', ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_description') }}"></i>
            {!! Form::textarea('description', null, ['class' => 'form-control summernote', 'rows' => '4', 'placeholder' => trans('app.placeholder.description'), 'required']) !!}
            <div class="help-block with-errors">{!! $errors->first('description', ':message') !!}</div>
          </div>

          <fieldset>
            <legend>
              {{ trans('app.form.images') }}
              <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_images') }}"></i>
            </legend>
            <div class="form-group">
              <div class="file-loading">
                <input id="dropzone-input" name="images[]" type="file" accept="image/*" multiple>
              </div>
              <span class="small"><i class="fa fa-info-circle"></i> {{ trans('help.multi_img_upload_instruction', ['size' => getAllowedMaxImgSize(), 'number' => getMaxNumberOfImgsForInventory(), 'dimension' => '800 x 800']) }}</span>
            </div>
          </fieldset>

          <p class="help-block">* {{ trans('app.form.required_fields') }}</p>

          <div class="box-tools pull-right">
            {!! Form::submit(isset($product) ? trans('app.form.update') : trans('app.form.save'), ['class' => 'btn btn-flat btn-lg btn-primary']) !!}
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 nopadding-left">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('app.organization') }}</h3>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <div class="form-group">
            {!! Form::label('category_list[]', trans('app.form.categories') . '*') !!}
            {!! Form::select('category_list[]', $categories, null, ['class' => 'form-control select2-normal', 'multiple' => 'multiple', 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>

          <fieldset>
            <legend>{{ trans('app.catalog_rules') }}</legend>

            <div class="form-group">
              <div class="input-group">
                {{ Form::hidden('requires_shipping', 0) }}
                {!! Form::checkbox('requires_shipping', null, !isset($product) ? 1 : null, ['id' => 'requires_shipping', 'class' => 'icheckbox_line']) !!}
                {!! Form::label('requires_shipping', trans('app.form.requires_shipping')) !!}
                <span class="input-group-addon" id="basic-addon1">
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.requires_shipping') }}"></i>
                </span>
              </div>
            </div>

            @if (auth()->user()->isFromplatform())
              <div class="row">
                <div class="col-md-6 nopadding-right">
                  <div class="form-group">
                    {!! Form::label('min_price', trans('app.form.catalog_min_price'), ['class' => 'with-help']) !!}
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.catalog_min_price') }}"></i>
                    <div class="input-group">
                      <span class="input-group-addon">{{ get_currency_symbol() }}</span>
                      {!! Form::number('min_price', null, ['class' => 'form-control', 'step' => 'any', 'min' => '0', 'placeholder' => trans('app.placeholder.catalog_min_price')]) !!}
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
                <div class="col-md-6 nopadding-left">
                  <div class="form-group">
                    {!! Form::label('max_price', trans('app.form.catalog_max_price'), ['class' => 'with-help']) !!}
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.catalog_max_price') }}"></i>
                    <div class="input-group">
                      <span class="input-group-addon">{{ get_currency_symbol() }}</span>
                      {!! Form::number('max_price', null, ['class' => 'form-control', 'step' => 'any', 'min' => '0', 'placeholder' => trans('app.placeholder.catalog_max_price')]) !!}
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            @endif
          </fieldset>

          <fieldset>
            <legend>
              {{ trans('app.featured_image') }}
              <i class="fa fa-question-circle small" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_featured_image') }}"></i>
            </legend>
            @if (isset($product) && $product->featureImage)
              <img src="{{ get_storage_file_url($product->featureImage->path, 'small') }}" alt="{{ trans('app.featured_image') }}">
              <label>
                <span style="margin-left: 10px;">
                  {!! Form::checkbox('delete_image[feature]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
                </span>
              </label>
            @endif

            <div class="row">
              <div class="col-md-9 nopadding-right">
                <input id="uploadFile" placeholder="{{ trans('app.featured_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
              </div>
              <div class="col-md-3 nopadding-left">
                <div class="fileUpload btn btn-primary btn-block btn-flat">
                  <span>{{ trans('app.form.upload') }} </span>
                  <input type="file" name="images[feature]" id="uploadBtn" class="upload" />
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset style="display: none">
            <legend>{{ trans('app.seo') }}</legend>
            <div class="form-group">
              {!! Form::label('slug', trans('app.form.slug') . '*', ['class' => 'with-help']) !!}
              <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.product_slug') }}"></i>
              {!! Form::text('slug', null, ['class' => 'form-control slug', 'placeholder' => trans('app.placeholder.slug'), isset($product) ? 'disabled' : 'required']) !!}
              <div class="help-block with-errors"></div>
            </div>

            {{-- <div class="form-group">
              {!! Form::label('tag_list[]', trans('app.form.tags'), ['class' => 'with-help']) !!}
              {!! Form::select('tag_list[]', $tags, null, ['class' => 'form-control select2-tag', 'multiple' => 'multiple']) !!}
            </div> --}}
          </fieldset>
        </div>
      </div>
    </div>
  </div>
@endif
