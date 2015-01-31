<?php namespace Genair\Providers;
use Illuminate\Support\ServiceProvider;
class BugsnagServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bugsnag', function ($app) {
            $client = new \Bugsnag_Client('ccbc02f308bd097ac9ce979fb02394c0');
            $client->setStripPath(base_path());
            $client->setProjectRoot(app_path());
            $client->setAutoNotify(false);
            $client->setBatchSending(false);
            $client->setReleaseStage('idop');
            $client->setNotifier(array(
                'name'    => 'Bugsnag Laravel',
                'version' => '1.2.1',
                'url'     => 'https://github.com/bugsnag/bugsnag-laravel'
            ));
            return $client;
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("bugsnag");
    }
}