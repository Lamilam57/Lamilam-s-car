<x-app-layout :role="$role">
    <main class="container py-4">
        <h1 class="mb-4">User Subscriptions</h1>
<div class="dashboard-stats">
    <div class="stat-card">
                <h6>Overall Subscription</h6>
                <h3>{{ $overallSubscriptionCount ?? 0 }}</h3>
            </div>
            <div class="stat-card">
                <h6>Overall Subscribed Users</h6>
                <h3>{{ $overallSubscribedUsers ?? 0 }}</h3>
            </div>
            <div class="stat-card">
                <h6>Total Active Subscriptions</h6>
                <h3>{{ $totalActiveSubs ?? 0 }}</h3>
            </div>
            <div class="stat-card">
                <h6>Current Active Subscribers</h6>
                <h3>{{ $currentActiveSubscribers ?? 0 }}</h3>
            </div>
            <div class="stat-card">
                <h6>Pending Subscriptions</h6>
                <h3>{{ $pendingSubscriptions ?? 0 }}</h3>
            </div>
            <div class="stat-card">
                <h6>Cancelled Subscriptions</h6>
                <h3>{{ $cancelledSubscriptions ?? 0 }}</h3>
            </div>
            
</div>
        <x-success-message />
        <x-error-message />

        <div class="card">
            <div class="card-body table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Reference</th>
                            <th>Amount (₦)</th>
                            <th>Start</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $sub)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $sub->user) }}">
                                        <x-user-image :user="$sub->user" class="my-cars-img-thumbnail" />
                                    </a>{{ $sub->user->name }}
                                </td>
                                <td>{{ $sub->user->email }}</td>
                                <td>{{ $sub->reference }}</td>
                                <td>{{ number_format($sub->amount) }}</td>
                                <td>{{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}</td>
<td>{{ \Carbon\Carbon::parse($sub->expires_at)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge @if($sub->status=='active') bg-success @else bg-warning @endif">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.subscriptions.update', $sub) }}" method="POST">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm mb-1">
                                            <option value="active" @selected($sub->status=='active')>Active</option>
                                            <option value="inactive" @selected($sub->status=='inactive')>Inactive</option>
                                            <option value="cancelled" @selected($sub->status=='cancelled')>Cancelled</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary w-100">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $subscriptions->onEachSide(1)->links() }}
    </main>
</x-app-layout>