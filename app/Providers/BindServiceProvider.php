<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * The model observers.
     *
     * @var array
     */
    protected array $modelObservers = [];

    /**
     * Register services.
     */
    public function register(): void
    {
        $contractsPath = app_path('Contracts/Models');

        if (!is_dir($contractsPath)) {
            return;
        }

        foreach (glob("$contractsPath/*Interface.php") as $interfaceFile) {
            $baseName = basename($interfaceFile, 'Interface.php');
            $interface = "App\Contracts\Models\\{$baseName}Interface";
            $repository = "App\Repositories\Models\\{$baseName}Repository";

            if (class_exists($repository)) {
                $this->app->bind($interface, $repository);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole() || !empty($this->modelObservers)) {
            return;
        }

        $modelsPath = app_path('Models');
        $observersPath = app_path('Observers');

        if (!is_dir($observersPath)) {
            return;
        }

        foreach (glob("{$modelsPath}/*.php") as $modelFile) {
            $modelName = pathinfo($modelFile, PATHINFO_FILENAME);
            $observerFile = "{$observersPath}/{$modelName}Observer.php";

            if (!file_exists($observerFile)) continue;

            $modelClass = "App\Models\\{$modelName}";
            $observerClass = "App\Observers\\{$modelName}Observer";

            $this->modelObservers[$modelClass] = $observerClass;
        }

        foreach ($this->modelObservers as $model => $observer) {
            if (class_exists($model) && class_exists($observer)) {
                $model::observe($observer);
            }
        }
    }
}
