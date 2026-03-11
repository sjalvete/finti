@php
    $colors = ['gray', 'orange', 'yellow', 'green'];
    $color = $colors[$step];

    $dotMap = [
        'gray'   => 'bg-gray-500 dark:bg-gray-400',
        'orange' => 'bg-orange-500 dark:bg-orange-400',
        'yellow' => 'bg-yellow-500 dark:bg-yellow-400',
        'green'  => 'bg-green-500 dark:bg-green-400',
    ];

    $lineMap = [
        'gray'   => 'bg-gray-400 dark:bg-gray-500',
        'orange' => 'bg-orange-400 dark:bg-orange-500',
        'yellow' => 'bg-yellow-400 dark:bg-yellow-500',
        'green'  => 'bg-green-400 dark:bg-green-500',
    ];

    $activeDot  = $dotMap[$color];
    $inactiveDot = 'bg-gray-300 dark:bg-gray-600';

    $activeLine  = $lineMap[$color];
    $inactiveLine = 'bg-gray-200 dark:bg-gray-700';
@endphp

<div class="inline-flex shrink-0 items-center w-[30px] h-4" title="Progress: {{ $step }}/3">
    <div class="w-1.5 h-1.5 rounded-full shrink-0 {{ $step >= 1 ? $activeDot : $inactiveDot }}"></div>

    <div class="flex-1 h-[1px] {{ $step >= 1 ? $activeLine : $inactiveLine }}"></div>

    <div class="w-1.5 h-1.5 rounded-full shrink-0 {{ $step >= 2 ? $activeDot : $inactiveDot }}"></div>

    <div class="flex-1 h-[1px] {{ $step >= 2 ? $activeLine : $inactiveLine }}"></div>

    <div class="w-1.5 h-1.5 rounded-full shrink-0 {{ $step >= 3 ? $activeDot : $inactiveDot }}"></div>
</div>
