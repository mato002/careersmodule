@php
    $countryCodes = [
        '254' => 'Kenya (+254)',
        '255' => 'Tanzania (+255)',
        '256' => 'Uganda (+256)',
        '250' => 'Rwanda (+250)',
        '257' => 'Burundi (+257)',
        '251' => 'Ethiopia (+251)',
        '252' => 'Somalia (+252)',
        '249' => 'Sudan (+249)',
        '211' => 'South Sudan (+211)',
        '260' => 'Zambia (+260)',
        '265' => 'Malawi (+265)',
        '263' => 'Zimbabwe (+263)',
        '27' => 'South Africa (+27)',
        '234' => 'Nigeria (+234)',
        '233' => 'Ghana (+233)',
        '1' => 'USA/Canada (+1)',
        '44' => 'UK (+44)',
        '91' => 'India (+91)',
        '86' => 'China (+86)',
    ];
@endphp

<select name="{{ $name }}" id="{{ $id }}" 
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
    <option value="">Select Code</option>
    @foreach($countryCodes as $code => $label)
        <option value="{{ $code }}" {{ old($name, $selected ?? '') == $code ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
