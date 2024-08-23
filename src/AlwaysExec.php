<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\ListOfType;
use Dagger\Container;

#[DaggerObject]
class AlwaysExec
{
    private const STDOUT = '/tmp/stdout';
    private const STDERR = '/tmp/stderr';
    private const LAST_EXIT_CODE = '/tmp/exit-code';

    #[DaggerFunction('Execute command, ignore exit code and return Container')]
    public function exec(
        Container $container,
        #[ListOfType('string')]
        array $args,
        ?bool $skipEntrypoint = true,
        ?bool $useEntrypoint = false,
        ?string $stdin = '',
        ?bool $experimentalPrivilegedNesting = false,
        ?bool $insecureRootCapabilities = false,
    ): Container {
        $command = sprintf(
            '%s 1> %s 2> %s; echo -n $? > %s',
            implode(' ', $args),
            self::STDOUT,
            self::STDERR,
            self::LAST_EXIT_CODE,
        );

        return $container->withExec(
            args: ['sh', '-c', $command],
            skipEntrypoint: $skipEntrypoint,
            useEntrypoint: $useEntrypoint,
            stdin: $stdin,
            experimentalPrivilegedNesting: $experimentalPrivilegedNesting,
            insecureRootCapabilities: $insecureRootCapabilities
        );
    }

    #[DaggerFunction('Return stdout')]
    public function stdout(Container $container): string
    {
        return $container->file(self::STDOUT)->contents();
    }

    #[DaggerFunction('Return stderr')]
    public function stderr(Container $container): string
    {
        return $container->file(self::STDERR)->contents();
    }

    #[DaggerFunction('Return last ignored exit code')]
    public function lastExitCode(Container $container): string
    {
        return $container->file(self::LAST_EXIT_CODE)->contents();
    }
}
