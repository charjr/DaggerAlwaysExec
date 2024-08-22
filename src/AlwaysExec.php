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
    private const LAST_EXIT_CODE = '/tmp/exit-code';

    #[DaggerFunction('Execute command, ignore exit code and return Container')]
    public function exec(
        Container $container,
        #[ListOfType('string')]
        array $args,
        ?bool $skipEntrypoint = true,
        ?bool $useEntrypoint = false,
        ?string $stdin = '',
        ?string $redirectStdout = '',
        ?string $redirectStderr = '',
        ?bool $experimentalPrivilegedNesting = false,
        ?bool $insecureRootCapabilities = false,
    ): Container {
        $command = sprintf(
            '%s; echo -n $? > %s',
            implode(' ', $args),
            self::LAST_EXIT_CODE,
        );

        return $container->withExec(
            args: ['sh', '-c', $command],
            skipEntrypoint: $skipEntrypoint,
            useEntrypoint: $useEntrypoint,
            stdin: $stdin,
            redirectStdout: $redirectStdout,
            redirectStderr: $redirectStderr,
            experimentalPrivilegedNesting: $experimentalPrivilegedNesting,
            insecureRootCapabilities: $insecureRootCapabilities
        );
    }

    #[DaggerFunction('Return last ignored exit code')]
    public function lastExitCode(Container $container): string
    {
        return $container->file(self::LAST_EXIT_CODE)->contents();
    }
}
