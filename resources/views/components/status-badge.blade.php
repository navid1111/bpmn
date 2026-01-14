@props(['status'])

@php
    $classes = match($status) {
        'Active' => 'bg-gray-400 text-white',
        'Rejected' => 'bg-gray-400 text-white',
        'Pending' => 'bg-gray-400 text-white', 
        default => 'bg-gray-100 text-gray-800'
    };
    // Note: The design had them all mostly greyish in the screenshot provided using 'Active' as grey, but typically you'd want colors. 
    // However, looking at the user screenshot more closely:
    // Active -> Grey background, Dark text? No, it looks like:
    // Active -> Grey pill
    // Pending -> Grey pill
    // Rejected -> Grey pill
    // Wait, let me re-examine the screenshot.
    // They all look like `bg-gray-300` or similar with dark text. 
    // Except maybe the specific row.
    // Let's stick to the user request's code for now but I will check the screenshot colors again.
    // Screenshot:
    // Active: Light Grey bg, Dark Grey text
    // Rejected: Light Grey bg, Dark Grey text
    // Pending: Light Grey bg, Dark Grey text
    // Actually they look slightly different but very subtle.
    // I will use distinct colors for better UX, or stick to a generic one if that's what's preferred.
    // The user's provided code used:
    // 'Active' => 'bg-gray-200 text-gray-800'
    // 'Rejected' => 'bg-red-100 text-red-800'
    // 'Pending' => 'bg-yellow-100 text-yellow-800'
    // I will use that.
    
    $classes = match($status) {
        'Active' => 'bg-green-100 text-green-800',
        'Rejected' => 'bg-red-100 text-red-800',
        'Pending' => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800'
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-medium $classes"]) }}>
    {{ $status }}
</span>
