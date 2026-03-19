@props(['title' => '', 'bodyClass' => null, 'footerLinks' => '', 'role'=>"Auth::user()->role"])

{{-- @switch($role)
{{ dd($role) }}
    @case($role === 'admin')
        <x-base-layout :$title :$bodyClass> 
            <x-layouts.admin-header />
            {{ $slot }}
        </x-base-layout>
        @break

    @case($role === 'user')
        <x-base-layout :$title :$bodyClass> 
            <x-layouts.user-header />
            {{ $slot }}
        </x-base-layout>
        @break
    @default
        <x-base-layout :$title :$bodyClass> 
            <x-layouts.user-header />
            {{ $slot }}
        </x-base-layout>
@endswitch --}}

@switch($role)

    @case('admin')
        <x-base-layout :$title :$bodyClass>
            <x-layouts.admin-header />
            {{ $slot }}
        </x-base-layout>
        @break

    @case('user')
        <x-base-layout :$title :$bodyClass>
            <x-layouts.user-header />
            {{ $slot }}
        </x-base-layout>
        @break

    @default
        <x-base-layout :$title :$bodyClass>
            <x-layouts.user-header />
            {{ $slot }}
        </x-base-layout>

@endswitch
