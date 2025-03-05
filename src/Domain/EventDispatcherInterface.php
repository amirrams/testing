<?php

namespace Docfav\Domain;

interface EventDispatcherInterface
{

    public function dispatch(object $event): void;

}
