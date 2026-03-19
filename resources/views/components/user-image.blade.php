@props(['user' => null, 'car', 'class' => '', 'id' => ''])
{{-- @if ($user->image)
                        <img src="{{ asset('storage/profile/' . $user->image) }}" width="150"
                            class="rounded-circle border"> --}}
@if ($user->image)
    <img
        src="{{ asset('storage/profile/' . $user->image) }}"
        alt=""
        width="150"
        class="{{ $class }}"
        id="{{ $id }}"
    >
@else
    <img
        src="{{ asset('img/avatar.png') }}"
        alt="avatar pictures"
        class="{{ $class }}"
        id="{{ $id }}"
    >
@endif
