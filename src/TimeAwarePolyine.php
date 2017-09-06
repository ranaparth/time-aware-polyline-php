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

    public function setPreviousPolyline(string $polyline) {
        $this->polyline = $polyline;
    }

    public function setLastGpxLogs(Array $gpxLogs) {
        $this->lastGpxLogs = $gpsLogs;
    }

    protected function getCoordinateForPolyline(float $coordinate) {
        return (int) round($coordinate * 1e5, 5);
    }

    protected function getUnixTimeStamp(string $timestamp) {
        return (int) strtotime($timestamp);
    }

    public function getGpxForPolyline(Array $gpxLog) {
        return array(
            $this->getCoordinateForPolyline($gpxLog[0]),
            $this->getCoordinateForPolyline($gpxLog[1]),
            $this->getUnixTimeStamp($gpxLog[2])
        );
    }

    public function encode(Array $gpxLogs = array()) {
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

    protected function getDecodedDimensionFromPolyline($polyline, $index) {
        $result = 1;
        $shift = 0;

        while (true) {
            $v = ord($polyline[$index]) - 63 - 1;
            $index += 1;
            $result += $v << $shift;
            $shift += 5;
            if ($v < 0x1f) {
                break;
            }
        }

        $result = ($result != 0) ? $result >> 1 : ~$result >> 1;

        return array($index, $result);
    }

    protected function getCoordinateFromPolyline($coordinate) {
        return round($coordinate * 1e-5, 5);
    }

    protected function getTimeFromPolyline($time) {
        return date('c', $time);
    }

    protected function getGpxFromDecoded($latitude, $longitude, $timestamp) {
        return array(
            $this->getCoordinateFromPolyline($latitude),
            $this->getCoordinateFromPolyline($longitude),
            $this->getTimeFromPolyline($timestamp)
        );
    }

    public function decode(string $polyline = '') {
        $gpxLogs = array();
        $index = $latitude = $longitude = $timestamp = 0;

        while ($index < strlen($polyline)) {
            list($index, $partialLat) = $this->getDecodedDimensionFromPolyline($polyline, $index);
            list($index, $partialLon) = $this->getDecodedDimensionFromPolyline($polyline, $index);
            list($index, $partialTime) = $this->getDecodedDimensionFromPolyline($polyline, $index);

            $latitude += $partialLat;
            $longitude += $partialLon;
            $timestamp += $partialTime;

            $gpxLog = $this->getGpxFromDecoded($latitude, $longitude, $timestamp);

            if (!empty($gpxLog)) {
                $gpxLogs[] = $gpxLog;
            }
        }

        return $gpxLogs;
    }
}