<?php

namespace App\Console\Commands;

use App\Models\Car;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeDeletedCars extends Command
{
    protected $signature = 'cars:purge';

    protected $description = 'Permanently delete cars soft-deleted for more than 2 minutes';

    public function handle()
    {
        $cars = Car::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(10))
            ->with(['images', 'features'])
            ->get();

        foreach ($cars as $car) {

            // Delete images (storage + DB)
            foreach ($car->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }

            // Delete hasOne feature record
            $car->features()->delete();

            // Permanently delete car
            $car->forceDelete();
        }

        $this->info('Old deleted cars purged successfully.');

        return Command::SUCCESS;
    }
}
