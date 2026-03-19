@props(['user', 'isInWatchList' => false])

<div class="car-item card">
    <a href="{{ route('admin.users.show', $user) }}">
        <x-user-image :user="$user" class="car-item-img rounded-t" />
    </a>
    <div class="p-medium">
        <h2 class="car-item-title">{{ $user->name ?? 'none' }}</h2>
        <p class="car-item-email">{{ $user->email ?? 'none' }}</p>
        <div class="flex items-center justify-between">
            <small class="m-0 text-muted">{{ $user->state_name ?? 'N/A' }} State, {{ $user->city_name ?? 'N/A' }}</small>
        </div>
        <hr />
        <p>
            <span>Cars Owned: </span>
            <span class="car-item-badge">{{ $user->cars()->count() }}</span>
        </p>
        <p class="m-0">
            <span>Cars Viewed: </span>
            <span class="car-item-badge">{{ $user->carViews()->count() }}</span>
        </p>
    </div>
</div>