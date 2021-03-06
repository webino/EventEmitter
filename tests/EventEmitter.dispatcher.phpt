<?php
/**
 * Webino™ (http://webino.sk)
 *
 * @link        https://github.com/webino/event-emitter
 * @copyright   Copyright (c) 2019 Webino, s.r.o. (http://webino.sk)
 * @author      Peter Bačinský <peter@bacinsky.sk>
 * @license     BSD-3-Clause
 */

namespace Webino;

use Tester\Assert;
use Tester\Environment;

Environment::setup();

$dispatcher = new EventDispatcher;

$emitter = new EventEmitter;
$emitter->setEventDispatcher($dispatcher);

$event = new Event('test');


$dispatcher->on($event, function (Event $event) use ($emitter) {
    $event['emitted'] = true;
    Assert::same($emitter, $event->getTarget());
    return 'Foo';
});

$dispatcher->on($event, function () {
    return 'Bar';
});

$emitter->emit($event);
$results = $event->getResults();


Assert::false(empty($event['emitted']));
Assert::same('FooBar', (string) $results);
Assert::same('Foo', $results->first());
Assert::same('Bar', $results->last());
