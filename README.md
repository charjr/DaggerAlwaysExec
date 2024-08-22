# Always Exec

## Dagger Functions

### exec

This command works similarly to a Container's `with-exec`.
The difference is this command ignores the exit code of your command.
That way no error will be thrown on unsuccessful exit codes.

### last-exit-code

This returns the last ignored exit code.
