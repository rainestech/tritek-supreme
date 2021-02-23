<?php

namespace Rainestech\AdminApi\Controllers;

use Rainestech\AdminApi\Entity\Privilege;
use Rainestech\AdminApi\Entity\Roles;
use Rainestech\AdminApi\Requests\RolesRequest;

class RoleApiController extends BaseApiController
{
    public function index() {
        return response()->json(Roles::with('privileges')->get());
    }

    public function defaultRole(RolesRequest $request) {
        $edit = Roles::findOrFail($request->input('id'));

        foreach (Roles::all() as $role) {
            $role->defaultRole = false;
            $role->save();
        }

        $edit->defaultRole = true;
        $edit->save();

        return response()->json($edit);
    }

    public function save(RolesRequest $request) {
        if ($request->has('id')) {
            return $this->update($request);
        }

        $role = new Roles();
        $role->role = $request->get('role');
        $role->save();

        foreach ($request->get('privileges') as $p) {
            $role->privileges()->attach($p['id']);
        }

        $role->refresh();
        return response()->json($role);
    }

    public function destroy(int $id) {
        $role = Roles::findOrFail($id);

        if ($role->privileges()->count() > 0) {
            abort(422, 'Can not delete Role with Privileges');
        }

        $role->delete();
        return response()->json($role);
    }

    public function domains() {
        $domains = collect();
        foreach (Privilege::all() as $p) {
            $domains->add($p->module);
        }

        return response()->json($domains->unique()->values()->all());
    }

    public function privileges() {
        return response()->json(Privilege::all());
    }

    private function update(RolesRequest $request) {
        $role = Roles::findOrFail($request->get('id'));
        $role->role = $request->get('role');
        $role->save();

        foreach ($role->privileges as $p) {
            $role->privileges()->detach($p->id);
        }

        foreach ($request->get('privileges') as $p) {
            $role->privileges()->attach($p['id']);
        }

        // $role->privileges = $request->get('privileges');
        return response()->json(Roles::with('privileges')->where('id', $role->id)->first());
    }
}
