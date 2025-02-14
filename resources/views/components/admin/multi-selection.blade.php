<div class="form-group">
    <label>{{$title}}</label>
    <select name="{{$name}}[][id]" class="select2" multiple="multiple" style="width: 100%;" {{$required == 'true' ? 'required':''}}>
{{--        @forelse ($childs as $child) <!-- $course->trackLessons -->--}}
{{--            @foreach ($items as $item) <!-- $lessons -->--}}
{{--                <option value="{{$item->id}}" {{$edit ? ($child->pivot->courseable_id == $item->id ? 'selected':'') :''}}>{{mb_substr($item->title_general?? $item->title, 0, 30)}}</option>--}}
{{--            @endforeach--}}
{{--        @empty--}}
{{--            @foreach ($items as $item)--}}
{{--                <option value="{{$item->id}}">{{$item->title_general ?? $item->title}}</option>--}}
{{--            @endforeach--}}
{{--        @endforelse--}}

        @php
            $taken = [];

        $ids = [];

        foreach ( $childs as $item) {
            $ids[] = $item['id'];
        }

        @endphp
        @isset ($childs )   <!-- $course->trackTests -->
            @foreach ($items as $item) <!-- $tests -->
{{--                @if(!in_array($item->id, $taken))--}}
                    <option value="{{$item->id}}" {{$edit ? (in_array($item->id, $ids) ? 'selected':'') :''}}>{{mb_substr($item->title_general?? $item->title, 0, 30)}}</option>
{{--                    @php--}}
{{--                        $taken[] = $item->id;--}}
{{--                    @endphp--}}

{{--                @endif--}}

                @endforeach
        @else
            @foreach ($items as $item)
                <option value="{{$item->id}}">{{$item->title_general ?? $item->title}}</option>
            @endforeach
        @endisset

{{--        <?php $uniqueIds = []; ?>--}}
{{--        @forelse ($childs as $child)--}}
{{--            @foreach ($items as $item)--}}
{{--                @if (!in_array($item->id, $uniqueIds))--}}
{{--                    <?php $uniqueIds[] = $item->id; ?>--}}
{{--                    <option value="{{$item->id}}" {{$edit ? ($child->pivot->courseable_id == $item->id ? 'selected':'') :''}}>--}}
{{--                        {{mb_substr($item->title_general ?? $item->title, 0, 30)}}--}}
{{--                    </option>--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @empty--}}
{{--            @foreach ($items as $item)--}}
{{--                @if (!in_array($item->id, $uniqueIds))--}}
{{--                    <?php $uniqueIds[] = $item->id; ?>--}}
{{--                    <option value="{{$item->id}}">{{$item->title_general ?? $item->title}}</option>--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @endforelse--}}
    </select>
  </div>

  <div id="additionalFieldsContainer_{{$name}}" class="raw">
    @if ($edit)
    @foreach ($childs as $child)
      <div class="form-group">
        <label>{{$arabicLabel .' : ' .  $child->name . $child->title_general}}</label>
{{--          <label>{{$arabicLabel .' : ' . $child->title_general }}</label>--}}
        <input type="number" min="0" value="{{$child->pivot->sort}}" class="form-control" name="{{$name}}[{{$loop->index}}][sort]" required/>
      </div>
    @endforeach



    @endif
  </div>
@push('js_pagelevel')
  <script>
    $(document).ready(function() {
        var name = '{{$name}}';
        var arabicLabel = '{{$arabicLabel}}';
        $('select[name="'+name+'[][id]"]').on('change', function() {
            // Clear the additionalFields div
            $('#additionalFieldsContainer_'+name).html('');

            // Loop through the selected options and generate an input field for each one
            $(this).find('option:selected').each(function(index) {
                var optionValue = $(this).val();
                var optionText = $(this).text();

            // Create a div with the class "form-group"
            var formGroupDiv = $('<div>').addClass('form-group');

            // Create a label for the input field
            var label = $('<label>')
                .attr('for', 'inputField_' + optionValue)
                .text(arabicLabel +' : ' + optionText );
                // input for sort number
                var inputField = $('<input>')
                .attr('type', 'number')
                .attr('min', '0')
                .attr('required','required')
                .attr('class', 'form-control')
                .attr('name', name + '['+index+'][sort]');

                // input hidden for id of item
            var hiddenInput = $('<input>')
                .attr('type', 'number')
                .attr('name', name + '['+index+'][id]').val(optionValue);

            // Append the label and input field to the form-group div
            formGroupDiv.append(label).append(inputField);

            // Append the form-group div to the additionalFieldsContainer div
            $('#additionalFieldsContainer_'+name).append(formGroupDiv);

        });
        });
    });
  </script>
@endpush
<style>
  .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: #333;
}
</style>
