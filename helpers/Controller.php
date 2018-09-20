<?php

class Controller {
    public $app;

    public function __construct() {
        $this->app = Base::instance();
        $this->protectRoutes($this->app);
    }

    public function reloadr($app) {
        header('Content Type: application/json; charset='.$app->get('ECODING')?:'utf-8');
        echo json_encode(Reloadr::instance()->init($app));
        exit();
    }

    protected function protectRoutes(Base $app) {
        if(property_exists($this, 'protectedRoute') && $this->protectedRoute) {
            $this->ifNotSigned($app, $this->protectedRoute);
        }
        if(property_exists($this, 'protectedRoutes') && is_array($this->protectedRoutes)) {
            foreach ($this->protectedRoutes as $route) {
                $this->ifNotSigned($app, $route);
            }
        }
    }

    public function validator(array $data = [], array $rules = []) {
        $validator = Validator::instance();
        return !empty($rules) ? $validator->validate($data, $rules) : $validator;
    }

    protected function isMethod($method) {
        $method = is_array($method) ?: func_get_args();
        $route = Route::instance();
        return in_array($route->current(), $method);
    }

    private function ifNotSigned(Base $app, $route) {
        $instance = Route::instance();
        if($instance->is($route) && !$this->isAuthenticated($app)) {
            $app->set('redirect', $instance->current());
            $app->reroute('@signin');
        }
    }

    protected function isAuthenticated(Base $app) {
        return $app->get('SESSION.user') ?: null;
    }
}