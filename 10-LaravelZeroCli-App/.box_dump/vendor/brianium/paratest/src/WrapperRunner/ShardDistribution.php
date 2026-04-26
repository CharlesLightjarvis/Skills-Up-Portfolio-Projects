<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;


enum ShardDistribution: string
{
case Sequential = 'sequential';
case RoundRobin = 'round-robin';
}
