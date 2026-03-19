<x-app-layout :role="$role">
    {{-- Messages --}}
    <x-success-message />
    <x-error-message />

    <main>
        <div>
            <div class="container">
                <div class="flex justify-between items-center">
                    <h1 class="car-details-page-title">Total Users</h1>
                    @if ($users->total() > 0)
                        <div class="pagination-summary">
                            <p>
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                                results
                            </p>
                        </div>
                    @endif
                </div>
                <div class="card p-medium">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Total Cars</th>
                                    <th>Cars Viewed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <x-user-image :user="$user" class="my-cars-img-thumbnail" />
                                            </a>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->cars_count }}</td>
                                        <td>{{ $user->viewed_cars_count }}</td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-large">
                                            No viewed car viewd by user yet. </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $users->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
