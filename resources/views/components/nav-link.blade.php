@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary dark:border-indigo-600 text-sm font-medium leading-5 text-main dark:text-gray-100 focus:outline-none focus:border-primary transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-muted dark:text-gray-400 hover:text-main dark:hover:text-gray-300 hover:border-border dark:hover:border-gray-700 focus:outline-none focus:text-main dark:focus:text-gray-300 focus:border-border dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
