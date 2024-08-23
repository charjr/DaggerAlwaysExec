# Always Exec

The primary purpose of this module is to always execute commands and return a usable Container.

It serves as an abstraction of [this workaround](https://docs.dagger.io/cookbook/#continue-using-a-container-after-command-execution-fails).
If this workaround proves unnecessary in the future, then this module will be obsolete.

The primary Dagger Function is [exec](#exec).

Every other Dagger Function is secondary;
the secondary Dagger Functions only work on Containers previously used in calls to [exec](#exec).

## Dagger Functions

### exec

This command works similarly to a Container's `with-exec`.
The difference is this command ignores the exit code of your command.
That way no error can be thrown for unsuccessful exit codes.

It takes most of the same arguments as `with-exec` but you cannot redirect stdout or stderr.
For that you will have to rely on [stdout](#stdout) and [stderr](#stderr)

### last-exit-code

Returns the exit code you would have otherwise received.

### stdout

Returns the stdout you would have otherwise received.

### stderr

Returns the stderr you would have otherwise received.
