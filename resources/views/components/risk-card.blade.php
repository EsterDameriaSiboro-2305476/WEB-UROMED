@props(['risk', 'color'])

<div class="prediction-{{ $color }} rounded-lg p-4 mb-4">
    <div class="flex items-center justify-between mb-2">
        <h4 class="font-medium text-{{ $color }}-800">{{ $risk['name'] }}</h4>
        <span class="text-sm font-bold text-{{ $color }}-800">{{ $risk['percentage'] }}%</span>
    </div>
    <p class="text-sm text-{{ $color }}-700 mb-3">
        {{ $risk['based_on'] }}
    </p>
    <div class="flex items-center">
        <div class="w-full bg-{{ $color }}-200 rounded-full h-2 mr-3">
            <div class="bg-{{ $color }}-600 h-2 rounded-full" style="width: {{ $risk['percentage'] }}%"></div>
        </div>
        <button onclick="viewPredictionDetails('{{ $risk['name'] }}', '{{ $risk['description'] }}')" class="text-xs text-{{ $color }}-800 hover:text-{{ $color }}-900 font-medium">
            Detail
        </button>
    </div>
</div>
