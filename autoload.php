<?php
function __autoload($class_name) {
    include __DIR__ . '/src/' . $class_name . '.php';
}
