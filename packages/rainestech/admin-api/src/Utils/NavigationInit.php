<?php

namespace Rainestech\AdminApi\Utils;

use Hash;
use Rainestech\AdminApi\Entity\Privilege;
use Rainestech\AdminApi\Entity\Roles;
use Rainestech\AdminApi\Entity\Users;

class NavigationInit {
    public function initNav() {
        $privilege = Privilege::all();
        if ($privilege->count() > 0) {
            return $this;
        }

        $json = json_decode(file_get_contents(__DIR__ . "/navItems.json"), true);

        foreach ($json as $j) {
            $p = new Privilege();
            $p->app = $j['app'];
            $p->url = $j['url'];
            $p->icon = $j['icon'];
            $p->name = $j['name'];
            $p->module = $j['module'];
            $p->hasChildren = false;
            $p->orderNo = $j['orderNo'];
            $p->privilege = $j['privilege'];
            $p->onNav = true;

            $p->save();
        }

        return $this;
    }

    public function createAdminRole() {
        $role = Roles::find(1);
        if ($role) {
            return $this;
        }

        $role = new Roles();
        $role->role = 'ADMIN';
        $role->id = 1;

        $role->save();

        foreach (Privilege::all() as $priv) {
            $role->privileges()->attach($priv->id);
        }

        return $this;
    }

    public function assignAdmin() {
        if ($user = Users::find(1)) {
            $user->roles()->attach(1);
        } else {
            $user = new Users();
            $user->username = 'raines';
            $user->email = 'dev@rainestech.com';
            $user->firstName = 'Raines';
            $user->lastName = 'Tech';
            $user->phoneNo = '012345678901';
            $user->status = 1;
            $user->password = Hash::make('12345678');
            $user->id = 1;
            $user->save();

            return $this->assignAdmin();
        }
    }
}
