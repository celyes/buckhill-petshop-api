<?php

use Tests\Mocks\MockController;

test('test success response method', function () {
    $controller = new MockController();
    $success = $controller->success(['foo' => 'bar'], ['x' => 'y']);
    expect($success)->toHaveKeys(['success', 'data', 'data.foo', 'error', 'errors', 'extra', 'extra.x']);
});

test('test error response method', function () {
    $controller = new MockController();
    $success = $controller->error('some error', ['foo' => 'bar'], ['x' => 'y']);
    expect($success)->toHaveKeys([
        'success', 'data', 'error', 'errors', 'errors.foo', 'trace', 'trace.x',
    ]);
});
