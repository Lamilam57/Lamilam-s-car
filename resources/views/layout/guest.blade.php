@props(['title' => '', 'bodyClass' => null, 'footerLinks' => ''])

<x-base-layout :$title :$bodyClass> 
    <main {{ $attributes }}>
        <div class="container-small page-login">
            <div class="flex" style="gap: 5rem">
                <div class="auth-page-form">
                    <div class="text-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('img/logoipsum-265.svg') }}" alt="Logo">
                        </a>
                    </div>

                    {{ $slot }}
                </div>

                <div class="auth-page-image">
                    <img
                        src="{{ asset('img/car-png-39071.png') }}"
                        class="img-responsive"
                        alt="Car"
                    >
                </div>
            </div>
        </div>
    </main>
</x-base-layout>

