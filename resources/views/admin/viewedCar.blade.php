<x-app-layout :role="$role">
    {{-- Messages --}}
    <x-success-message />
    <x-error-message />

    <main>
        <div>
            <div class="container">
                <div class="flex justify-between items-center">
                    <h1 class="car-details-page-title">Viewed Cars</h1>
                    @if ($cars->total() > 0)
                        <div class="pagination-summary">
                            <p>
                                Showing {{ $cars->firstItem() }} to {{ $cars->lastItem() }} of {{ $cars->total() }}
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
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Published</th>
                                    <th>Total Clicks</th>
                                    <th>Unique Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cars as $car)
                                    <tr>
                                        <td>
                                            <a href="{{ route('car.show', $car) }}">
                                                <x-car-image :car="$car" class="my-cars-img-thumbnail" />
                                            </a>
                                        </td>
                                        <td>{{ $car->year }} - {{ $car->maker->name }} {{ $car->model->name }}</td>
                                        <td>{{ $car->getCreatedDate() }}</td>
                                        <td>{{ $car->published_at ? 'Yes' : 'No' }}</td>
                                        <td>{{ $car->total_clicks ?? 0 }}</td>
                                        <td><a href="{{ route('admin.carViewers', $car) }}"  class="car-item-badge" style="color: black">{{ $car->unique_clicks ?? 0 }}</a></td>
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

                    {{ $cars->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
