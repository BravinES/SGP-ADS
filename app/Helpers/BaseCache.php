<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Models\System\CacheQueryDB;

class BaseCache extends Controller
{
    function setBaseCache($identifier, $query, $result, $expiration = false)
    {
        $cache = CacheQueryDB::where('identifier', $identifier)->first();

        $dateExpiration = $expiration ? $this->setDateExpiration($expiration) : null;

        if ($cache) {
            $cache->query = $query;
            $cache->result = $result;
            $cache->expiration = $dateExpiration;
            $cache->save();
        } else {
            $cache = new CacheQueryDB();
            $cache->identifier = $identifier;
            $cache->query = $query;
            $cache->result = $result;
            $cache->expiration = $dateExpiration;
            $cache->save();
        }
    }

    function getBaseCache($identifier)
    {
        $cache = CacheQueryDB::where('identifier', $identifier)->first();

        if (!$cache) return null;

        if ($cache->expiration && date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($cache->expiration))) {
            $cache->delete();
            return null;
        }

        return $cache->result;
    }

    function hasBaseCache($identifier)
    {
        $cache = CacheQueryDB::where('identifier', $identifier)->first('expiration');

        if (!$cache) return false;

        if ($cache->expiration && date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($cache->expiration))) {
                $cache->delete();
                return false;
        }

        return true;
    }

    function deleteCache($identifier)
    {
        $cache = CacheQueryDB::where('identifier', $identifier)->first();
        if ($cache) {
            $cache->delete();
        }
    }

    private function setDateExpiration($expiration)
    {
        $stringData = explode(' ', $expiration);

        if(count($stringData) === 1) {
            switch($stringData[0]) {
                case '+00':
                    $zone = new \DateTimeZone('America/New_York'); // Or your own definition of “here”
                    $todayStart = new \DateTime('today midnight', $zone);
                    return date('Y-m-d H:i:s', strtotime('+1 day'));
            }
        }


        $dateExpiration = date('Y-m-d H:i:s', strtotime("+$expiration minutes"));

        return $dateExpiration;
    }
}
