<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\PasswordDto;
use Throwable;

class Password
{
    /**
     * The new password strength PHP.
     */
    public static string $bootPassword = <<<'PHP'
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(10)
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
PHP;

    /**
     * Strengthen password requirements.
     */
    public static function run(): PasswordDto
    {
        try {
            // Set stronger password requirements.
            ReplaceContentsInFile::run(
                'app/Providers/AppServiceProvider.php',
                'use Illuminate\\Support\\ServiceProvider;',
                "use Illuminate\\Support\\ServiceProvider;\nuse Illuminate\\Validation\\Rules\\Password;",
            );
            ReplaceContentsInFile::run(
                'app/Providers/AppServiceProvider.php',
                "    public function boot(): void\n    {\n        //",
                static::$bootPassword,
            );

            // Fix tests.
            foreach ([
                'tests/Feature/Auth/AuthenticationTest.php',
                'tests/Feature/Auth/PasswordConfirmationTest.php',
                'tests/Feature/Auth/PasswordResetTest.php',
                'tests/Feature/Auth/RegistrationTest.php',
                'tests/Feature/Settings/ProfileUpdateTest.php',
            ] as $file) {
                foreach ([
                    "->set('password', 'password')" => "->set('password', 'pass123WORD!@£')",
                    "->set('password', 'wrong-password')" => "->set('password', 'wrong-pass123WORD!@£')",
                    "->set('password_confirmation', 'password')" => "->set('password_confirmation', 'pass123WORD!@£')",
                ] as $search => $replace) {
                    ReplaceContentsInFile::run($file, $search, $replace);
                }
            }

            foreach ([
                "Hash::make('password')," => "Hash::make('pass123WORD!@£'),",
                "->set('current_password', 'password')" => "->set('current_password', 'pass123WORD!@£')",
                "->set('password', 'new-password')" => "->set('password', 'new-pass123WORD!@£')",
                "->set('password_confirmation', 'new-password')" => "->set('password_confirmation', 'new-pass123WORD!@£')",
                "Hash::check('new-password'," => "Hash::check('new-pass123WORD!@£',",
            ] as $search => $replace) {
                ReplaceContentsInFile::run('tests/Feature/Settings/PasswordUpdateTest.php', $search, $replace);
            }

            // Update User factory.
            ReplaceContentsInFile::run(
                'database/factories/UserFactory.php',
                "Hash::make('password'),",
                "Hash::make('pass123WORD!@£'),",
            );

            // Update database seeder.
            ReplaceContentsInFile::run(
                'database/seeders/DatabaseSeeder.php',
                "        // User::factory(10)->create();\n\n",
                '',
            );
        } catch (Throwable $t) {
            return new PasswordDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new PasswordDto(
            result: true,
        );
    }
}
