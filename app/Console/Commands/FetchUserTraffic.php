<?php

namespace App\Console\Commands;

use App\Models\Router;
use App\Models\TrafficStat;
use App\Services\MikrotikService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchUserTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-user-traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store per-user traffic data from all registered Mikrotik routers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Starting Mikrotik user traffic fetch command.');
        $this->info('Starting Mikrotik user traffic fetch command.');

        $routers = Router::all();

        if ($routers->isEmpty()) {
            Log::warning('No routers found in the database.');
            $this->warn('No routers found in the database.');
            return;
        }

        foreach ($routers as $router) {
            $this->info("Fetching user traffic for router: {$router->name}");
            Log::info("Fetching user traffic for router: {$router->name}");

            if (empty($router->user) || empty($router->pass)) {
                Log::warning("Router {$router->name} has incomplete credentials, skipping.");
                $this->warn("Router {$router->name} has incomplete credentials, skipping.");
                continue;
            }

            $activeUsers = MikrotikService::active($router->ip, $router->user, $router->pass, $router->name);

            if (isset($activeUsers['error'])) {
                Log::error("Failed to fetch active users for router {$router->name}: " . $activeUsers['error']);
                $this->error("Failed to fetch active users for router {$router->name}: " . $activeUsers['error']);
                continue;
            }

            if ($activeUsers->isEmpty()) {
                $this->info("No active users on router: {$router->name}");
                Log::info("No active users on router: {$router->name}");
                continue;
            }

            foreach ($activeUsers as $user) {
                try {
                    TrafficStat::create([
                        'router_id' => $router->id,
                        'user' => $user['user'],
                        'rx_rate' => $user['rx_rate'],
                        'tx_rate' => $user['tx_rate'],
                        'stat_timestamp' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to store user traffic data for user {$user['user']} on router {$router->name}: " . $e->getMessage());
                    $this->error("Failed to store user traffic data for user {$user['user']} on router {$router->name}: " . $e->getMessage());
                }
            }
            $this->info("Successfully stored user traffic data for router: {$router->name}");
            Log::info("Successfully stored user traffic data for router: {$router->name}");
        }

        $this->info('Mikrotik user traffic fetch command finished.');
        Log::info('Mikrotik user traffic fetch command finished.');
    }
}