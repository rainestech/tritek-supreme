<?php


namespace Rainestech\AdminApi\Utils;


use GeoIp2\Exception\AddressNotFoundException;
use Jenssegers\Agent\Agent;
use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Rainestech\AdminApi\Entity\Tokens;
use Rainestech\AdminApi\Entity\UserDevice;

trait Security {
    public function getIp() {
        return request()->getClientIp();
    }

    public function getDeviceDetails() {
        $agent = new Agent();

        // dd(request()->userAgent());
        if (!$agent->device()) {
            $agent->setUserAgent(request()->userAgent());
        }
        return $agent->device(). ' running ' . $agent->platform() . ' ' . $agent->browser() . ' Browser';
    }

    public function getLocation() {
        return [
            'city' => 'unknown',
            'country' => 'unknown',
            'latitude' => 'unknown',
            'longitude' => 'unknown',
            'ip' => $this->getIp(),
            'device' => $this->getDeviceDetails(),
            'browser' => $this->getDeviceDetails(),
            'deviceRaw' => request()->userAgent()
        ];
        $reader = new Reader('path/to/db');
        try {
            $data = $reader->city($this->getIp());

            return [
              'city' => $data->city,
              'country' => $data->country,
              'latitude' => $data->location->latitude,
              'longitude' => $data->location->longitude,
              'ip' => $this->getIp(),
              'device' => $this->getDeviceDetails(),
              'browser' => $this->getDeviceDetails(),
            ];

        } catch (AddressNotFoundException $e) {
            return [
                'city' => 'unknown',
                'country' => 'unknown',
                'latitude' => 'unknown',
                'longitude' => 'unknown',
                'ip' => $this->getIp(),
                'device' => $this->getDeviceDetails(),
                'browser' => $this->getDeviceDetails(),
            ];
        } catch (InvalidDatabaseException $e) {
            return [
                'city' => 'unknown',
                'country' => 'unknown',
                'latitude' => 'unknown',
                'longitude' => 'unknown',
                'ip' => $this->getIp(),
                'device' => $this->getDeviceDetails(),
                'browser' => $this->getDeviceDetails(),
            ];
        }
    }

    public function verifyDbToken($token) {
        // dd(request()->server('HTTP_USER_AGENT'));
        return Tokens::where('ip', $this->getIp())
            ->where('device', $this->getDeviceDetails())
            ->where('token', $token)
            ->exists();
    }

    public function checkHotList($userID) {
        return UserDevice::where('ip', $this->getIp())
            ->where('device', $this->getDeviceDetails())
            ->where('userID', $userID)
            ->where('hotlist', true)
            ->exists();
    }
}
