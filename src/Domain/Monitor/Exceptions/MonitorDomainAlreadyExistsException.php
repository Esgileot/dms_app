<?php

declare(strict_types=1);

namespace Domain\Monitor\Exceptions;

use Infrastructure\Exceptions\LogicException;

final class MonitorDomainAlreadyExistsException extends LogicException {}
