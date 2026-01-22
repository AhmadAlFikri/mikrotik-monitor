<?php

namespace App\Console\Commands;

use App\Models\Router;
use App\Models\TrafficStat;
use App\Services\MikrotikService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchMikrotikTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-mikrotik-traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store traffic data from all registered Mikrotik routers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Starting Mikrotik traffic fetch command.');
        $this->info('Starting Mikrotik traffic fetch command.');

        $routers = Router::all();

        if ($routers->isEmpty()) {
            Log::warning('No routers found in the database.');
            $this->warn('No routers found in the database.');

            return;
        }

        foreach ($routers as $router) {
            $this->info("Fetching traffic for router: {$router->name}");
            Log::info("Fetching traffic for router: {$router->name}");

            if (empty($router->user) || empty($router->pass)) {
                Log::warning("Router {$router->name} has incomplete credentials, skipping.");
                $this->warn("Router {$router->name} has incomplete credentials, skipping.");

                continue;
            }

            // Mengambil traffic dari interface 'ether1' sebagai default
            $traffic = MikrotikService::getOverallTraffic($router->ip, $router->user, $router->pass, 'ether1');

            if (isset($traffic['error'])) {
                Log::error("Failed to fetch traffic for router {$router->name}: ".$traffic['error']);
                $this->error("Failed to fetch traffic for router {$router->name}: ".$traffic['error']);

                continue;
            }

            try {
                TrafficStat::create([
                    'router_id' => $router->id,
                    'rx_rate' => $traffic['rx_rate'],
                    'tx_rate' => $traffic['tx_rate'],
                    'stat_date' => Carbon::now()->toDateString(),
                ]);

                $this->info("Successfully stored traffic data for router: {$router->name}");
                Log::info("Successfully stored traffic data for router: {$router->name}");

            } catch (\Exception $e) {
                Log::error("Failed to store traffic data for router {$router->name}: ".$e->getMessage());
                $this->error("Failed to store traffic data for router {$router->name}: ".$e->getMessage());
            }
        }

        $this->info('Mikrotik traffic fetch command finished.');
        Log::info('Mikrotik traffic fetch command finished.');
    }
}
