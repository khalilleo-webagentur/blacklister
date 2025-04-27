<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Service\ApiKeysService;
use App\Service\BlackListService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Service\UserSettingService;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:new-admin

#[AsCommand(
    name: 'app:new-admin',
    description: 'Add demo admin for demonstration purposes.',
    hidden: false
)]
class NewAdminCommand extends Command
{
    public const FAILURE = 0;
    public const SUCCESS = 1;

    public function __construct(
        private readonly UserService $userService,
        private readonly TokenGeneratorService $tokenGeneratorService,
        private readonly UserSettingService $userSettingService,
        private readonly ApiKeysService $apiKeysService,
        private readonly BlackListService $blackListService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running ...');

        $faker = Factory::create();

        $email = $faker->safeEmail();

        if (!$this->userService->getByEmail($email)) {

            $user = new User();

            $code = $this->tokenGeneratorService->randomToken();

            $this->userService->save(
                $user
                    ->setName($faker->name())
                    ->setEmail($email)
                    ->setPassword($this->userService->encodePassword($email))
                    ->setRoles(['ROLE_SUPER_ADMIN'])
                    ->setIsVerified(true)
                    ->setToken($code)
            );

            $userSetting = new UserSetting();

            $this->userSettingService->save($userSetting->setUser($user));

            $apiKey = $this->apiKeysService->create($user, 'Default API Key', 'Awesome-App');
            $this->blackListService->create(
                $user,
                $apiKey,
                'John_doe',
                'j.doe@example.com',
                'example.com',
                '172.0.0.1',
                'https://example.com');

            $output->writeln(sprintf('Dashboard added. E:: %s and OTP:: %s', $email, $code));

            return self::SUCCESS;
        }

        $output->writeln('Dashboard cannot be created ...');

        return self::FAILURE;
    }

    protected function configure(): void
    {
        //
    }
}
