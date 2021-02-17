<?php

namespace Rainestech\Authorization;


trait Role
{
    protected $user;
    protected $loginStatus;
    protected $privileges;

    private function setInstance()
    {
        $this->loginStatus = auth('api')->check();
        $this->user = auth('api')->user();
        // clock($this->user);
        // console_print($this->user);
    }

    private function getUserPrivileges() {
        $result = collect();
        foreach ($this->user->roles as $role) {
            $result = $result->merge($role->privileges);
        }

//        return $result->values()->all();
        return array_column( $result->toArray(), 'privilege');
    }

    private function searchList($resource, $privileges): bool {

        if (in_array('ROLE_PUBLIC', $resource)) {
            return true;
        }

            $resp  = false;
        foreach ($resource as $res) {
            if (in_array($res, $privileges)) {
                $resp = true;
                break;
            }
        }

        return $resp;
    }

    private function search($resource, $privileges): bool {
        return in_array($resource, $privileges);
    }

    /**
     * @param $requestResource
     * @return bool
     */
    public function checkAccess($requestResource)
    {
        self::setInstance();
//        clock($this->loginStatus);
        if ($this->loginStatus) {
            $privilegeList = self::getUserPrivileges();

            clock($privilegeList);
            if (is_array($requestResource)) {
                return $this->searchList($requestResource, $privilegeList);
            }

            return $this->search($requestResource, $privilegeList);
        }

        elseif($requestResource == 'ROLE_PUBLIC') {
            return true;
        }

        return false;
    }

    /**
     * @param $model
     * @return bool
     */
    public function checkOwnership($model)
    {
        self::setInstance();
        if ($this->loginStatus)
        {
            /**
             *Escape for user models only
             * @var $model
             */
            if ($model['role_id'])
                return $model['id'] == $this->user['id'];

            elseif (@$model['userID'] == $this->user['id'])
                return true;

            elseif (@$model['editor'] == $this->user['id'])
                return true;
        }

        return false;
    }
}
