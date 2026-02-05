<x-app-layout>
    <main>
        <section>
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">My Favourite Cars</h2>

                    @if ($cars->total() > 0)
                        <div class="text-gray-600">
                            Showing {{ $cars->firstItem() }} to {{ $cars->lastItem() }} of {{ $cars->total() }}
                        </div>
                    @endif
                </div>
                @if ($cars->isEmpty())
                    <div style="padding: 40px; font-size: 22px; font-weight: 600;"
                        class="text-center py-20 text-gray-500 text-xl">
                        You don't have cars yet.
                        <a href="{{ route('car.create') }}" class="text-blue-600 underline">Add new car</a>
                    </div>
                @else
                    <div class="car-items-listing">
                        @foreach ($cars as $favourite)
                            <x-car-items :car="$favourite->car" :isInWatchList="true" />
                        @endforeach
                    </div>
                @endif
                <div class="mt-6">
                    {{ $cars->onEachSide(1)->links() }}
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
