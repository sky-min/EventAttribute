<?php
declare(strict_types = 1);

namespace skymin\event;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class NotHandler{}