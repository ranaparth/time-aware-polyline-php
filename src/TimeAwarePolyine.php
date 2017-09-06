<?php

namespace RanaParth\Polyline;

/**
*  Add description
*
*  @author https://github.com/ranaparth
*/
class TimeAwarePolyine {

    private $polyline;
    private $lastGpxLogs;

    public function setPreviousPolyline(String $polyline) {
        $this->polyline = $polyline;
    }

    public function setLastGpxLogs(Array $gpxLogs) {
        $this->lastGpxLogs = $gpsLogs;
    }

    protected function getCoordinateForPolyline(float $coordinate) {
        return (int) round($coordinate * 1e5, 5);
    }

    protected function getUnixTimeStamp(String $timestamp) {
        return (int) strtotime($timestamp);
    }

    public function getGpxForPolyline(Array $gpxLog) {
        return array(
            $this->getCoordinateForPolyline($gpxLog[0]),
            $this->getCoordinateForPolyline($gpxLog[1]),
            $this->getUnixTimeStamp($gpxLog[2])
        );
    }

    public function encode(Array $gpxLogs) {
        if (empty($this->lastGpxLogs)) {
            $lastLatitude = $lastLongitude = $lastTimestamp = 0;
        } else {
            list($lastLatitude, $lastLongitude, $lastTimestamp) = $this->getGpxForPolyline(
                $this->lastGpxLogs
            );
        }

        if (empty($this->polyline)) {
            $this->polyline = '';
        }

        if (empty($gpxLogs)) {
            return $this->polyline;
        }

        foreach ($gpxLogs as $gpxLog) {
            if (count($gpxLog) != 2) {
                continue;
            }

            list($latitude, $longitude, $timestamp) = $this->getGpxForPolyline($gpxLog);

            $diffLatitude = $latitude - $lastLatitude;
            $diffLongitude = $longitude - $lastLongitude;
            $diffTimestamp = $timestamp - $lastTimestamp;

            foreach (array($diffLatitude, $diffLongitude, $diffTimestamp) as $v) {
                $v = ($v < 0) ? ~($v << 1) : $v << 1;

                while ($v >= 0x20) {
                    $this->polyline += chr((0x20 | ($v & 0x1f)) + 63);
                    $v >>= 5;
                    $this->polyline += chr($v + 63);
                }
            }

            $lastLatitude = $latitude;
            $lastLongitude = $longitude;
            $lastTimestamp = $timestamp;
        }

        return $this->polyline;
    }
}