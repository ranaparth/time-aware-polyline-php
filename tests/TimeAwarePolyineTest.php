<?php

/**
 * Test class
 *
 *
 *  @author https://github.com/ranaparth
 */

class TimeAwarePolyineTest extends PHPUnit_Framework_TestCase {

    public function testIsThereAnySyntaxError(){
        $var = new RanaParth\Polyline\TimeAwarePolyine;
        $this->assertTrue(is_object($var));
        unset($var);
    }

    public function testEncode() {
        $var = new RanaParth\Polyline\TimeAwarePolyine;

        $gpxLogs = [
            [19.13626, 72.92506, '2016-07-21T05:43:09+00:00'],
            [19.13597, 72.92495, '2016-07-21T05:43:15+00:00']
        ];

        $timeAwarePolyline = 'spxsBsdb|Lymo`qvAx@TK';

        $this->assertTrue($var->encode($gpxLogs) == $timeAwarePolyline);

        unset($var);

        $gpxLog = [19.13597, 72.92495, '2016-07-21T05:43:15+00:00'];

        $var = new RanaParth\Polyline\TimeAwarePolyine;

        $var->setPreviousPolyline($timeAwarePolyline);
        $var->setLastGpxLogs($gpxLog);

        $gpxLogs = [
            [19.13553, 72.92469, '2016-07-21T05:43:21+00:00']
        ];

        $timeAwarePolyline = 'spxsBsdb|Lymo`qvAx@TKvAr@K';

        $this->assertTrue($var->encode($gpxLogs) == $timeAwarePolyline);
        unset($var);
    }

    public function testDecode() {
        $var = new RanaParth\Polyline\TimeAwarePolyine;

        $gpxLogs = [
            [19.13626, 72.92506, '2016-07-21T05:43:09+00:00'],
            [19.13597, 72.92495, '2016-07-21T05:43:15+00:00'],
            [19.13553, 72.92469, '2016-07-21T05:43:21+00:00'],
        ];

        $timeAwarePolyline = 'spxsBsdb|Lymo`qvAx@TKvAr@K';

        $this->assertTrue($var->decode($timeAwarePolyline) == $gpxLogs);
        unset($var);
    }
}
