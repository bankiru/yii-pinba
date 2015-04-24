# Pinba extension for Yii framework [![Latest Stable Version](https://img.shields.io/packagist/v/bankiru/yii-pinba.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-pinba) [![Total Downloads](https://img.shields.io/packagist/dt/bankiru/yii-pinba.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-pinba)

###### Simple Yii extension that incapsulates Pinba configuration and methods.

[![Build Status](https://img.shields.io/travis/bankiru/yii-pinba.svg?style=flat-square)](https://travis-ci.org/bankiru/yii-pinba)
[![Scrutinizer Code Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bankiru/yii-pinba.svg?style=flat-square)](https://scrutinizer-ci.com/g/bankiru/yii-pinba/)
[![Coveralls Code Coverage Status](https://img.shields.io/coveralls/bankiru/yii-pinba.svg?style=flat-square)](https://coveralls.io/r/bankiru/yii-pinba)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/bankiru/yii-pinba.svg?style=flat-square)](https://scrutinizer-ci.com/g/bankiru/yii-pinba/)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/205a11b0-0b5b-41b4-8868-0f158bf04244.svg?style=flat-square)](https://insight.sensiolabs.com/projects/205a11b0-0b5b-41b4-8868-0f158bf04244)
[![Dependency Status](https://www.versioneye.com/user/projects/5539faeb1d2989bdd500006b/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5539faeb1d2989bdd500006b)
[![HHVM Status](https://img.shields.io/hhvm/bankiru/yii-pinba.svg?style=flat-square)](http://hhvm.h4cc.de/package/bankiru/yii-pinba)
[![License](https://img.shields.io/packagist/l/bankiru/yii-pinba.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-pinba)

## Installing

You should install php pinba extension manually. See [documentation](https://github.com/tony2001/pinba_engine/wiki/Installation#Pinba_extension_installation).

### Composer

```
"require": {
  "bankiru/yii-pinba": "~0.1"
}
```

### Github

Releases of Pinba extension for Yii framework are available on [Github](https://github.com/bankiru/yii-pinba).


## Documentation

To enable this extension you need add Pinba to component list in config.php and do some simple configurations

```
'pinba' => array(
    'class'         => 'Bankiru\\Yii\\Profiling\\Pinba\\Pinba',
    'fixScriptName' => true, // changes script_name in pinba to controller/action or to command args in cli mode. Default true
    'scriptName'    => null,   // default null (if null pinba would use autodetect)
    'hostName'      => null, // default null (if null pinba would use autodetect)
    'serverName'    => null, // default null (if null pinba would use autodetect)
    'schema'        => null, // default null (if null pinba would use autodetect)
    'profileEvents' => [], // default empty array
)
```

In addition you need to add pinba extension to `preload` section.

Available 2 methods to profile.

* through direct call Timer class
* using yii events

### Timers class

Timer class has static methods:

* start
* stop
* add
* delete
* tagsMerge
* tagsReplace
* dataMerge
* dataReplace
* getInfo
* getAll
* stopAll

which wraps [pinba_* functions](https://github.com/tony2001/pinba_engine/wiki/PHP-extension).

### Yii events

Extension always tracks CApplication request (onBeginRequest, onEndRequest).

Custom profilings can be added through config. For example:

```
'pinba' => array(
    'class' => 'Bankiru\\Yii\\Profiling\\Pinba\\Pinba',
    'profileEvents' => [
        ['my-component-name', 'profiling_action_name', 'onBeginActionEventName', 'onEndActionEventName'],
    ],
)
```