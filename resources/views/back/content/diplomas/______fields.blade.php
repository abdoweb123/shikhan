
    <div class="form-group row">
      <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
      <div class=" col-lg-4 col-md-9 col-sm-12">
        @foreach($languages as $language)
          {{ $language->title }} : <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"
          value="{{ old('title' , isset($data) ? $data->titleTranslation($language->locale) ?? '' : null ) }}"
          name="title[{{$language->locale}}]" placeholder="">
        @endforeach
        <!-- <span class="form-text text-muted">Please enter your full name</span> -->
        @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
      </div>
    </div>


    <div class="form-group row">
      <label class="col-form-label col-lg-3 col-sm-12">{{ __('project.contents') }} *</label>
      <div class="col-lg-4 col-md-9 col-sm-12">
        <select class="form-control kt-select2 {{ $errors->has('content_id') ? ' is-invalid' : '' }}" id="kt_select2_3" required name="content_id">
          @foreach ( $contentsTree as $content )
            <option {{ old('content_id' , isset($data) ? $data->content_id : null ) == $content->id ? 'selected' : '' }} value="{{ $content->id }}">
              {{str_repeat('....', $content->depth)}}
              {{ !$content->content_info->isEmpty() ? $content->content_info->first()->title : __('words.not_translated') }}
            </option>
          @endforeach
        </select>
        <!-- <span class="form-text text-muted">Please enter your full name</span> -->
        @if ($errors->has('content_id'))<span class="invalid-feedback">{{ $errors->first('gender_id') }}</span>@endif
      </div>
    </div>




    <div class="form-group row">
      <label class="col-form-label col-lg-3 col-sm-12">{{ __('project.lesson_type') }} *</label>
      <div class="col-lg-4 col-md-9 col-sm-12">
        <select class="form-control kt-select2 {{ $errors->has('lesson_type_id') ? ' is-invalid' : '' }}" id="kt_select2_3" required name="lesson_type_id">
          @foreach ( $lessonTypes as $lessonType )
            <option {{ old('lesson_type_id' , isset($data) ? $data->lesson_type_id : null ) == $lessonType->id ? 'selected' : '' }} value="{{ $lessonType->id }}">{{ $lessonType->titleTranslation() }}</option>
          @endforeach
        </select>
        <!-- <span class="form-text text-muted">Please enter your full name</span> -->
        @if ($errors->has('lesson_type_id'))<span class="invalid-feedback">{{ $errors->first('lesson_type_id') }}</span>@endif
      </div>
    </div>




      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12">{{ __('project.skills') }}</label>
        <div class=" col-lg-9">
          @foreach ($skills as $skill)
              <label class="kt-checkbox">
                <input type="checkbox"  value="{{ $skill->id }}"
                {{
                    in_array( $skill->id , old('skills' , isset($data) ? !empty($data->skills) ? $data->skills->pluck('id')->toArray() : [] : []) )
                    ? 'checked' : ''
                }}
                name="skills[]">{{ $skill->translation() }}
                <span></span>
              </label>
              <br>
          @endforeach
          @if ($errors->has('skills'))
              <span class="invalid-feedback">{{ $errors->first('skills') }}</span>
          @endif
        </div>
      </div>


      <x-admin.is-free dataValue="{{ isset($data) ? $data->is_free : null }}"/>

      <x-admin.is-active dataValue="{{ isset($data) ? $data->is_active : null }}"/>


      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>
        <div class="col-lg-9 col-md-9 col-sm-12">
          <input type="file" name="image" id="image" class="dropify img_edit"
          data-default-file="{{ isset($data) ? $data->imagePath() : '' }}" />
          <input type="checkbox" value="1" id="image_remove" name="image_remove" class="image_check_remove">
        </div>
      </div>

      {{--
      <td><x-admin.files :files="$item->pdfTranslation()" type="pdf"/></td>
      <td><x-admin.files :files="$item->soundTranslation()" type="sound"/></td>
      <td><x-admin.files :files="$item->vedioTranslation()" type="Video"/></td>
        --}}
