<?php

declare(strict_types=1);

namespace Roave\ApiCompare\Comparator\BackwardsCompatibility\ConstantBased;

use Roave\ApiCompare\Changes;
use Roave\BetterReflection\Reflection\ReflectionClassConstant;

final class MultiConstantBased implements ConstantBased
{
    /** @var ConstantBased[] */
    private $checks;

    public function __construct(ConstantBased ...$checks)
    {
        $this->checks = $checks;
    }

    public function compare(ReflectionClassConstant $fromConstant, ReflectionClassConstant $toConstant) : Changes
    {
        return array_reduce(
            $this->checks,
            function (Changes $changes, ConstantBased $check) use ($fromConstant, $toConstant) : Changes {
                return $changes->mergeWith($check->compare($fromConstant, $toConstant));
            },
            Changes::new()
        );
    }
}
