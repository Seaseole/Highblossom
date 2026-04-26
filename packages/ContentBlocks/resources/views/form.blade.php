@php
    $fields = $fields ?? [];
    $submitText = $submit_text ?? 'Submit';
    $actionUrl = $action_url ?? null;
    $formId = 'cb-form-' . uniqid();
@endphp

<form class="cb-form" @if($actionUrl) action="{{ $actionUrl }}" method="POST" @endif>
    @csrf
    @if($actionUrl)
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif

    @foreach($fields as $field)
        @php
            $fieldName = $field['name'] ?? '';
            $fieldLabel = $field['label'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            $fieldRequired = $field['required'] ?? false;
            $fieldOptions = $field['options'] ?? [];
        @endphp

        <div class="cb-form__field">
            <label for="{{ $formId }}-{{ $fieldName }}" class="cb-form__label">
                {{ $fieldLabel }}
                @if($fieldRequired) <span class="cb-form__required">*</span> @endif
            </label>

            @if($fieldType === 'textarea')
                <textarea
                    id="{{ $formId }}-{{ $fieldName }}"
                    name="{{ $fieldName }}"
                    @if($fieldRequired) required @endif
                    class="cb-form__input cb-form__input--textarea"
                    rows="4"
                ></textarea>
            @elseif($fieldType === 'select')
                <select
                    id="{{ $formId }}-{{ $fieldName }}"
                    name="{{ $fieldName }}"
                    @if($fieldRequired) required @endif
                    class="cb-form__input cb-form__input--select"
                >
                    <option value="">Select an option</option>
                    @foreach($fieldOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            @elseif($fieldType === 'checkbox')
                <input
                    type="checkbox"
                    id="{{ $formId }}-{{ $fieldName }}"
                    name="{{ $fieldName }}"
                    value="1"
                    @if($fieldRequired) required @endif
                    class="cb-form__input cb-form__input--checkbox"
                >
            @elseif($fieldType === 'radio')
                <div class="cb-form__radio-group">
                    @foreach($fieldOptions as $index => $option)
                        <label class="cb-form__radio-label">
                            <input
                                type="radio"
                                id="{{ $formId }}-{{ $fieldName }}-{{ $index }}"
                                name="{{ $fieldName }}"
                                value="{{ $option }}"
                                @if($fieldRequired && $index === 0) required @endif
                                class="cb-form__input cb-form__input--radio"
                            >
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <input
                    type="{{ $fieldType }}"
                    id="{{ $formId }}-{{ $fieldName }}"
                    name="{{ $fieldName }}"
                    @if($fieldRequired) required @endif
                    class="cb-form__input"
                >
            @endif
        </div>
    @endforeach

    <button type="submit" class="cb-form__submit">{{ $submitText }}</button>
</form>
