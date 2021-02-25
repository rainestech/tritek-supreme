<?php

namespace Rainestech\AdminApi\Controllers;

use Illuminate\Support\Collection;
use Rainestech\AdminApi\Utils\NavigationInit;

class NavController extends BaseApiController {
    public function index($app) {
        $user = auth('api')->user();

        $privileges = collect();
        $nav = collect();

        foreach ($user->roles as $role) {
            $priv = $role->privileges()->where('app', $app)
                ->where('onNav', 1)
                ->orderBy('id', 'asc')
                ->get();

            foreach ($priv as $p) {
                $privileges->add($p);
            }
        }

        foreach ($privileges as $m) {
            $nav->add($this->mainDropdown($m, $privileges->sortBy('orderNo')->unique('id')->values()->all()));
        }

        return $nav->unique('name')->values()->all();
    }

    private function mainDropdown($module, $privileges) {
        return [
          'name' => $module->name,
          'icon' => $module->icon,
          'url' => $module->url,
          'module' => $module->module,
          'app' => $module->app,
          'orderNo' => $module->orderNo,
          'children' => $module->hasChildren ? $this->setChildren($module, $privileges) : null,
        ];
    }

    private function setChildren($module, $privileges) {
        $c = collect();

        foreach ($privileges as $p) {
            if ($p->module == $module->module && !$p->hasChildren) {
                $c->add([
                    'name' => $p->name,
                    'icon' => $p->icon,
                    'url' => $p->url,
                    'module' => $p->module,
                    'app' => $p->app,
                ]);
            }
        }

        return $c;
    }

    private function getModules(Collection $privileges) {
        $res = collect();

        foreach ($privileges->where('hasChildren', true) as $m) {
            if (!$res->where('module', $m->module)->first()) {
                $res->add($m);
            }
        }

        foreach ($privileges->where('hasChildren', false) as $t) {
            if (!$res->where('module', $t->module)->first()) {
                $res->add($t);
            }
        }
        return $res->sortBy('orderNo')->values();
    }

    public function init() {
        $nav = new NavigationInit();
        $nav->initNav()->createAdminRole()->assignAdmin();
        return response()->json(['success' => true]);
    }
}
