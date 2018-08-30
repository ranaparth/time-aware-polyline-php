Time Aware Polylines in PHP ![travis-ci](https://travis-ci.org/ranaparth/time-aware-polyline-php.svg?branch=master) [![codecov](https://codecov.io/gh/ranaparth/time-aware-polyline-php/branch/master/graph/badge.svg)](https://codecov.io/gh/ranaparth/time-aware-polyline-php)
=========================

Inspired from [Time aware polylines in javascript](https://github.com/hypertrack/time-aware-polyline-js)

```php
$var = new RanaParth\Polyline\TimeAwarePolyine;

$gpxLogs = [
    [19.13626, 72.92506, '2016-07-21T05:43:09+00:00'],
    [19.13597, 72.92495, '2016-07-21T05:43:15+00:00']
];

$polyline = $var->encode($gpxLogs); // Output $polyline = 'spxsBsdb|Lymo`qvAx@TK';
```

```php
$var = new RanaParth\Polyline\TimeAwarePolyine;

$timeAwarePolyline = 'spxsBsdb|Lymo`qvAx@TK';
$var->setPreviousPolyline($timeAwarePolyline);

$gpxLog = [19.13597, 72.92495, '2016-07-21T05:43:15+00:00'];
$var->setLastGpxLogs($gpxLog);
$gpxLogs = [
    [19.13553, 72.92469, '2016-07-21T05:43:21+00:00']
];

$polyline = $var->encode($gpxLogs) // Output $polyline = 'spxsBsdb|Lymo`qvAx@TKvAr@K';
```

```php
$var = new RanaParth\Polyline\TimeAwarePolyine;

$timeAwarePolyline = 'spxsBsdb|Lymo`qvAx@TKvAr@K';

$gpxLogs = $var->decode($timeAwarePolyline);
/*
// Output

$gpxLogs = [
    [19.13626, 72.92506, '2016-07-21T05:43:09+00:00'],
    [19.13597, 72.92495, '2016-07-21T05:43:15+00:00'],
    [19.13553, 72.92469, '2016-07-21T05:43:21+00:00'],
];
*/

```
